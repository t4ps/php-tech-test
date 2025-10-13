# PHP Live Exercise — Env, Bearer Token (GoREST), Exceptions, JSON, SQLite

## Getting Started

1. The `.env` file is already provided.

2. Install the required Composer dependency:  

3. For HTTP requests you may use:
   * PHP cURL (native extension), or  
   * `file_get_contents` with stream context, or  
   * a Composer library of your choice (for example, `guzzlehttp/guzzle`).

4. Requirements:
   * PHP 8+  
   * PDO SQLite or MySQL extension enabled (`pdo_sqlite` or `pdo_mysql`)

---

## Tasks

### 1) divide.php

* Implement a function called `divide($a, $b)` that divides two numbers.  
* The function must throw an `Exception` if `$b === 0`.  
* Call the function with the values `(10, 2)` and print the result.  
* If an exception occurs, print `ERROR` and exit with a non-zero status.

---

### 2) app.php

* Load environment variables from `.env` using `vlucas/phpdotenv`.  
* Read `GOREST_TOKEN` and `API_URL` from the environment.  
* If `GOREST_TOKEN` is missing or empty, print `AUTH_ERROR` and exit.  

* Make an HTTP GET request to `${API_URL}/users?per_page=1` using the header:  
  `Authorization: Bearer <GOREST_TOKEN>`

* If the response is successful and contains valid JSON, print the first user’s email on its own line.  
* On any failure (invalid token, non-200 status, or invalid JSON), print `API_ERROR` and exit with a non-zero status.

* Fetch posts from `${API_URL}/posts?per_page=5` using the same Authorization header.  
* Store these posts in a local SQLite or MySQL database using PDO.

---

### Database Requirements

**SQLite**  
* Use `DB_PATH` from `.env` (e.g., `./database.sqlite`).  
* Create the database file if it doesn’t exist.  
* Create a table `posts` with columns:  
  `id, user_id, title, body`.  
* Insert fetched posts, avoiding duplicates.

**MySQL**  
* Connect using environment variables (`DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).  
* Create a table `posts` with columns:  
  `id, user_id, title, body`.  
* Insert fetched posts, avoiding duplicates.

* After insertion, print `STORED:<n>` where `<n>` is the number of posts inserted.  

* Handle all errors (API, DB, or invalid data) by printing `API_ERROR` and exiting with a non-zero status.

---

## Output Rules

* `divide.php` prints:  
  - The result of the division, or  
  - `ERROR`

* `app.php` prints:  
  1. The user email  
  2. `STORED:<n>`

* All other cases should result in `AUTH_ERROR` or `API_ERROR`.

---

## Git Ignore

Complete the `.gitignore` file so that sensitive or unnecessary files are not committed (for example: `vendor/`, `.env`, `database.sqlite`).

---

## Time Limit

This exercise is designed to be completed in **15–20 minutes**.

---

## Bonus (Optional)

* Validate `.env` configuration.  
* Retry once on failed API requests.  
* Add request timeouts.  
* Use transactions for database writes.  

