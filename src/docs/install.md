# Installation

#### Using GIT

``` bash
composer install
cp .env.example .env
php artisan jwt:secret
php artisan key:generate
```

### 2 configure your project


Now give to your project access tou your database and create the users table whit a test user.

```bash
vim .env
  DB_*
    configure your database access

php artisan migrate --seed
```

## Documentation

### Serve Your API

```bash
php -S localhost:8000 -t public
# or
php artisan serve
```

### Deploy

In a production server we can omit development packages autoload using this command

```bash
composer install --no-dev
```

### Routes

We have some routes to start. http(s)://domain/apidoc/index.html
