
# LARAVEL FAST CRUD


## Installation

Create Laravel Project
```bash
  laravel new {project_name}
```

Change Composer minimum-stability, open composer.json on your laravel project

```bash
  "minimum-stability": "stable",
  "prefer-stable": true
```
to 
```bash
  "minimum-stability": "dev",
  "prefer-stable": true
```
run your terminal
```bash
composer require satriotol/fastcrud
php artisan vendor:publish --force
```
choose 
```bash
  Provider: Satriotol\Fastcrud\FastCrudServiceProvider
```

## Create User

Open Your Terminal
```bash
    php artisan fastcrud:create-user
```

Follow The Step


## Authors

- [@satriotol](https://www.github.com/octokatherine)

