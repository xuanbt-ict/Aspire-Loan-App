# Readme

### Install dependency
```
$ composer install
```

### Create database using docker
```
$ docker-compose up -d
```

### Create .env, update DB config
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=password
```

### Generate application key
```
$ php artisan key:generate
```

### Migrate database
```
$ php artisan migrate --seed
```

### Generate API documentation
```
$ php artisan l5-swagger:generate
```

### Run project
```
$ php artisan serve
```

### Access API Documentation
```
http://localhost:8000/api/documentation
```

### User account
```
Admin: admin@gmail.com - secret
User: user@gmail.com - password
```

### Run test
```
$ php artisan test
```