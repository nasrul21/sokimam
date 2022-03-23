# Kost Backend API

This is a backend API for Kost Management

## Requirement

-   PHP >= ^7.3 or ^8.0
-   docker & docker-compose
-   mysql (using docker-compose)

## How to Run

-   Clone this repository

```
git clone https://github.com/nasrul21/sokimam.git
cd sokimam
```

-   Install dependensies

```
composer install
```

-   Copy env from .env.dev

```
cp .env.dev .env
```

-   Run Mysql (using docker-compose)

```
docker-compose up -d db
```

-   Run database migration

```
php artisan migrate
```

-   Initialize data for role management

```
php artisan db:seed --class=RolePermissionSeeder
```

-   Run the project

```
php artisan serve
```

## API documentation

You can access the api docs at `localhost:8000/docs`
