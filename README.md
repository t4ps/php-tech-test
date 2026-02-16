# PHP Live Exercise — Env, Bearer Token (GoREST), Exceptions, JSON

## Getting Started

1. The `.env` file is already provided.

2. Install the required Composer dependency: vlucas/phpdotenv

3. For HTTP requests you may use:
   * PHP cURL (native extension), or  
   * `file_get_contents` with stream context, or  
   * a Composer library of your choice (for example, `guzzlehttp/guzzle`).

4. Requirements:
   * PHP 8+

---

## Task — app.php

* Load environment variables from `.env` using `vlucas/phpdotenv`.
* Read `GOREST_TOKEN` and `API_URL` from the environment.
* If `GOREST_TOKEN` is missing or empty, print `AUTH_ERROR` and exit.

* Make an HTTP GET request to `${API_URL}/users?per_page=1` using the header:
  `Authorization: Bearer <GOREST_TOKEN>`

* If the response is successful and contains valid JSON, print the first user's email on its own line.
* On any failure (invalid token, non-200 status, or invalid JSON), print `API_ERROR` and exit with a non-zero status.

* Wrap your code in a `try/catch` block. In the `catch`, print `API_ERROR` and exit with a non-zero status.

---

## Output Rules

* `app.php` prints:
  - The user email

* All other cases should result in `AUTH_ERROR` or `API_ERROR`.

---

## Git Ignore

Complete the `.gitignore` file so that sensitive or unnecessary files are not committed (for example: `vendor/`, `.env`).

---

## Time Limit

This exercise is designed to be completed in **15–20 minutes**.

---

## Bonus (Optional)

* Validate `.env` configuration.
* Retry once on failed API requests.
* Add request timeouts.

