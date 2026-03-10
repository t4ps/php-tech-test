# PHP Live Exercise — Env, API Key (ReqRes), Exceptions, JSON

## Getting Started

1. The `.env` file is already provided. It contains:
   ```
   API_KEY=<your_api_key>
   API_URL=https://reqres.in/api
   ```
   To load these variables in your code, use `vlucas/phpdotenv`:
   ```php
   $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
   $dotenv->load();
   ```
   Variables are then accessible via `$_ENV['API_KEY']` and `$_ENV['API_URL']`.

2. Install the required Composer dependency: vlucas/phpdotenv

3. For HTTP requests, use PHP cURL (native extension).

4. Requirements:
   * PHP 8+

---

## Task 1 — apiGet() wrapper function

Write a reusable function `apiGet(string $url): array` that:

* Makes a cURL GET request to the given URL.
* Sets the header `x-api-key: <API_KEY>` (read from `$_ENV['API_KEY']`).
* Validates the HTTP response status is 200; if not, throws an exception.
* Decodes the JSON response body and returns it as an associative array.
* Throws an exception on cURL errors or invalid JSON.

You may define this function in `app.php` or in a separate file and `require` it.

---

## Task 2 — app.php

* Load environment variables from `.env` using `vlucas/phpdotenv`.
* Read `API_KEY` and `API_URL` from the environment.
* If `API_KEY` is missing or empty, print `AUTH_ERROR` and exit.

* Use your `apiGet()` function to make a GET request to `${API_URL}/users?per_page=1`.

* The response JSON has the structure:
  ```json
  {
    "data": [
      { "id": 1, "email": "george.bluth@reqres.in", ... }
    ]
  }
  ```
  Print the first user's email (`response["data"][0]["email"]`) on its own line.

* On any failure (missing API key, non-200 status, or invalid JSON), print `API_ERROR` and exit with a non-zero status.

* Wrap your code in a `try/catch` block. In the `catch`, print `API_ERROR` and exit with a non-zero status.

---

## Output Rules

* `app.php` prints:
  - The user email (e.g. `george.bluth@reqres.in`)

* All other cases should result in `AUTH_ERROR` or `API_ERROR`.

---

## Git Ignore

Complete the `.gitignore` file so that sensitive or unnecessary files are not committed (for example: `vendor/`, `.env`).

---

## Time Limit

This exercise is designed to be completed in **15-20 minutes**.

---

## Bonus (Optional)

* Validate `.env` configuration.
* Retry once on failed API requests.
* Add request timeouts.
