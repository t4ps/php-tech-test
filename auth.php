<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $token = $_ENV['GOREST_TOKEN'] ?? '';
    if (!is_string($token) || trim($token) === '') {
        fwrite(STDOUT, "AUTH_ERROR\n");
        exit(1);
    }
    fwrite(STDOUT, $token . "\n");
    exit(0);
} catch (Throwable $e) {
    fwrite(STDOUT, "AUTH_ERROR\n");
    exit(1);
}
