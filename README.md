## About project required 
- Docker 
- Docker Compose 

## About project installation
### Install vendor folders 
```sh docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
### Copting enviroments 
```sh
cp .env.example .env
```

### Open servers and mysql  
in this step you have option
```sh 
sail up 
```
or 

```sh 
./vendor/bin/sail up 
```
or 
``` sh 
docker-compose up
```
### Creating database 
``` sh 
sail artisan migrate:fresh --seed 
```
or 
``` sh 
./vendor/bin/sail artisan migrate:fresh --seed 
```
or 
```sh 
docker-compose exec laravel.test php artisan migrate:fresh --seed 
```




