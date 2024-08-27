# Installation

#### Using GIT

``` bash
git clone https://github.com/2016denver2016/olx_api.git ./
```
### Deploy

In a production server we can omit development packages autoload using this command

```bash
docker-compose up

docker exec -it olx-app php artisan composer install
```

### 2 configure your project


Now give to your project access tou your database and create the users table whit a test user.

```bash
docker exec -it olx-app php artisan migrate

docker exec -it olx-app php artisan db:seed
```

## Documentation

We have some routes to start. http(s)://localhost:8001/apidoc/index.html



