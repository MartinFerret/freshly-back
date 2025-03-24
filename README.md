# Freshly

## Getting Started

1. Start by creating a new repository.

2. Clone the new repository on your computer and open it in your favourite editor or IDE to get started.
3. Open the file `docker.env` and customize any of the environment variables to your needs. You may change the `DC_*`
   values in order to avoid container conflicts with already running containers.

    ```shell
    # Container name for the app service in docker-compose.yaml, must be unique for all containers running on your docker instance
    DC_APP_NAME=Symfony7
    # Forwarded port for the Symfony7 app service
    DC_APP_PORT=8880
    # Forwarded port for phpMyAdmin service to view the underlying application database
    DC_PMA_PORT=8881
    # MySQL credentials - username
    MYSQL_USER=app_development
    # MySQL credentials - password
    MYSQL_PASSWORD=password
    # MySQL credentials - database
    MYSQL_DATABASE=app_db
    ``` 

4. Once you have successfully configured your app via the `docker.env` file, run the `./dkbuild.sh` file to build your
   container images and run the application.
5. Next, run `./dkconnect.sh` to connect to the `app` service container where the *Symfony7* application is running.

6. Run `symfony composer install` to install the application and it's dependencies.

## Applying migrations

`php bin/console doctrine:migrations:migrate`

## Loading fixtures

Run `./dkconnect.sh` and then `php bin/console doctrine:fixtures:load` 

## Create a single order

Run `./dkconnect.sh` and then `php bin/console app:generate-order <status>`.
The command takes as argument the status of the order : "pending", "paid", "in_progress", "delivered", "cancelled".

This command creates a single order with 1 to 5 products attached to it.

## Routing

| **Route**                          | **Méthode HTTP** | **Nom de la Route**         |
|------------------------------------|------------------|-----------------------------|
| `/api/v1/orders`                  | `GET`            | `api_orders`                |
| `/api/v1/orders/{id}/status`      | `PUT`            | `update_order_status`       |
| `/api/v1/orders/{id}`             | `DELETE`         | `delete_order`              |

## General

Inside the container, you may run any `symfony console` or `php bin/console` command as you build your application. Any
changes you make in this container are
immediately visible in your IDE and vice versa.

Happy coding! 🎉

