Rest API task for user creation, file upload.

Instalation:
------
```bash
$git clone https://github.com/Grazvydas-M/rest-api-task.git
$cd rest-api-task
```
Run the project:

```bash
$ docker compose up -d
```
Enter php container:
```bash
docker exec -it symfony_php bash
```
Install packages:
```bash
composer install
```
Create database and run migrations:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
Load fixtures:
```bash
php bin/console doctrine:fixtures:load
```
