<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

function divide(float $a, float $b): float {
    if ($b === 0.0) {
        throw new Exception('division by zero');
    }
    return $a / $b;
}

function http_get(string $url, array $headers, int $timeoutSeconds = 5): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => $timeoutSeconds,
        CURLOPT_CONNECTTIMEOUT => $timeoutSeconds,
    ]);
    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    return [$httpCode, $body, $err];
}

try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    try {
        $result = divide(10, 2);
        if (!is_finite($result)) {
            fwrite(STDOUT, "ERROR\n");
            exit(1);
        }
        fwrite(STDOUT, (string)($result) . "\n");
    } catch (Throwable $e) {
        fwrite(STDOUT, "ERROR\n");
        exit(1);
    }

    $apiUrl = $_ENV['API_URL'] ?? '';
    $token = $_ENV['GOREST_TOKEN'] ?? '';
    $dbPath = $_ENV['DB_PATH'] ?? '';

    if ($apiUrl === '' || $token === '') {
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    [$codeUsers, $usersBody, $usersErr] = http_get(rtrim($apiUrl, '/') . '/users?per_page=1', [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
    ]);

    if ($codeUsers !== 200) {
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    $users = json_decode($usersBody, true);
    if (!is_array($users) || count($users) < 1 || !isset($users[0]['email']) || !is_string($users[0]['email']) || $users[0]['email'] === '') {
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    fwrite(STDOUT, $users[0]['email'] . "\n");

    [$codePosts, $postsBody, $postsErr] = http_get(rtrim($apiUrl, '/') . '/posts?per_page=5', [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
    ]);

    if ($codePosts !== 200) {
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    $posts = json_decode($postsBody, true);
    if (!is_array($posts)) {
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    if ($dbPath === '') {
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    try {
        $dsn = 'sqlite:' . $dbPath;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA journal_mode = WAL');
        $pdo->exec('CREATE TABLE IF NOT EXISTS posts (id INTEGER PRIMARY KEY, user_id INTEGER, title TEXT, body TEXT)');
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT OR IGNORE INTO posts (id, user_id, title, body) VALUES (:id, :user_id, :title, :body)');
        $inserted = 0;
        foreach ($posts as $post) {
            if (!is_array($post) || !isset($post['id'], $post['user_id'], $post['title'], $post['body'])) {
                continue;
            }
            $stmt->execute([
                ':id' => (int)$post['id'],
                ':user_id' => (int)$post['user_id'],
                ':title' => (string)$post['title'],
                ':body' => (string)$post['body'],
            ]);
            $inserted += ($stmt->rowCount() > 0) ? 1 : 0;
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        fwrite(STDOUT, "API_ERROR\n");
        exit(1);
    }

    fwrite(STDOUT, 'STORED:' . (string)$inserted . "\n");
    exit(0);
} catch (Throwable $e) {
    fwrite(STDOUT, "API_ERROR\n");
    exit(1);
}
