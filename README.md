# SnowTricks [![SymfonyInsight](https://insight.symfony.com/projects/e7332a76-7b13-479a-8466-179e742f61dd/mini.svg)](https://insight.symfony.com/projects/e7332a76-7b13-479a-8466-179e742f61dd)

## Prerequisites

* Docker
* PHP 8.1
* Symfony CLI

## Installation and configuration

1. Duplicate and rename the `.env` file to `.env.local` and modify the necessary information (`APP_ENV`, `APP_SECRET`, ...)
2. Install the dependencies with `symfony composer install --optimize-autoloader`
3. Run migrations with `symfony console doctrine:migrations:migrate --no-interaction`
4. Add default datasets with `symfony console doctrine:fixtures:load --no-interaction`

## Launch the local server

Run the command `symfony server:start -d` to start the local server and access the site at the indicated address or type `symfony open:local`.

## Default account credentials

* Username: `Simon`
* Password: `password` *(needs to be modified)*
