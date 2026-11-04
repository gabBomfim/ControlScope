# ControlScope

ControlScope is a small security posture dashboard for reviewing a curated set of security controls and deciding what to improve next.

It uses the vocabulary of NIST CSF 2.0 as inspiration, but it is not an official NIST assessment, certification, or compliance product.

## What it does

- Lists demo controls across Govern, Identify, Protect, Detect, Respond, and Recover.
- Calculates an illustrative posture score.
- Shows coverage by security function.
- Filters controls by function and status.
- Updates control status through a validated API.
- Produces practical next-step recommendations.

## Stack

Laravel 13, PHP 8.3, React, TypeScript, Vite, SQLite for local tests, and MySQL through Docker Compose.

## Run locally

~~~bash
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
~~~

Open http://127.0.0.1:8000.

For a MySQL-backed run:

~~~bash
docker compose up -d mysql
# set DB_* in .env to the values in docker-compose.yml
php artisan migrate:fresh --seed
~~~

## Tests

~~~bash
php artisan test
npm run build
~~~

## Engineering notes

The scoring rule is isolated behind ScoringStrategy. The API controllers only coordinate filtering, validation, resources, and the domain calculator. The score is deliberately illustrative so users can inspect the code without mistaking it for a compliance result.