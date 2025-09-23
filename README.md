# PHP Live Exercise — Env, Bearer Token (GoREST), Exceptions, JSON, SQLite

## Getting Started

1. Clone this repository

2. Create a new branch with your name

3. Install the required Composer dependency: vlucas/phpdotenv

4. For HTTP requests you may use:

   * PHP cURL (native extension), or
   * file_get_contents with stream context, or
   * a Composer library of your choice (for example, guzzlehttp/guzzle).

5. Requirements:

   * PHP 8+
   * PDO SQLite or MySQL extension enabled (pdo_sqlite or pdo_mysql)

6. The .env file is already provided.

---

## Tasks

### 1) auth.php

* Load environment variables from .env using vlucas/phpdotenv.
* Read GOREST_TOKEN from environment.
* On success (non-empty token), print only the token to STDOUT.
* On failure (missing/empty token), print AUTH_ERROR and exit with a non-zero status.

### 2) app.php

* Load environment variables from .env using vlucas/phpdotenv.

* Implement:
  function divide($a, $b)
  Returns $a / $b. Throws an Exception if $b === 0.

* Call divide(10, 2) and print the numeric result on its own line.
  If an exception occurs, print ERROR and exit with non-zero status.

* Make a GET request to ${API_URL}/users?per_page=1 with header:
  Authorization: Bearer <GOREST_TOKEN>

* If HTTP 200 and the response is valid JSON containing at least one user, print only the first user’s email (index 0 → email) on its own line.

* On any API failure (bad token, non-200, invalid JSON, empty list), print API_ERROR and exit with a non-zero status.

* Fetch posts from:
  ${API_URL}/posts?per_page=5
  Include the same Authorization header as above.
  If HTTP 200 and valid JSON, store these posts into a local SQLite/MySQL database using PDO.

* Database details (SQLite or MySQL):

  SQLite
  * Database path: DB_PATH from .env (e.g., ./database.sqlite)
  * Create the database file if it does not exist.
  * Create a table if it does not exist:
    posts(id INTEGER PRIMARY KEY, user_id INTEGER, title TEXT, body TEXT)
  * Insert the fetched posts. Avoid duplicates using INSERT OR IGNORE.

  MySQL
  * Connect using environment variables: DB_DRIVER=mysql, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
  * Example DSN: mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_NAME};charset=utf8mb4
  * Create a table if it does not exist:
    posts(id INT PRIMARY KEY, user_id INT, title TEXT, body TEXT)
  * Insert the fetched posts. Avoid duplicates using INSERT IGNORE.

* After inserting, print a third line:
  STORED:<n>
  where <n> is the number of posts inserted during this run (0–5).

* Failure modes for posts:

  * If the posts request fails, or JSON is invalid, or DB operations fail, print API_ERROR and exit with a non-zero status.

---

## Expected Output

php auth.php

# => [long token string]

php app.php

# =>

# 5

# [some.user@example.com](mailto:some.user@example.com)

# STORED:5

Notes:

* The exact email depends on current API data.
* STORED: value may be 0–5 depending on duplicates and API results.

---

## Output Rules

* auth.php prints either: <token>
  or AUTH_ERROR
* app.php prints exactly three lines on success:

  1. 5
  2. <email>  
  3. STORED:<n>
* On failures:

  * divide() error → print ERROR
  * any API or DB issue → print API_ERROR
  * exit with non-zero status.

---

## Git Ignore

You must complete the `.gitignore` file yourself so that sensitive or unnecessary files are not committed

---

## Time Limit

This exercise is designed to be completed in 10–15 minutes.

---

## Bonus (Optional, if you finish early)

* Validate DB_PATH exists in .env.
* Add a single retry on non-200 responses.
* Set short HTTP timeouts.
* Wrap DB writes in a transaction.

---

## MySQL .env example (optional)

DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=php_tech_test
DB_USER=root
DB_PASS=secret
