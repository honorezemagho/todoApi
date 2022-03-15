# Sport API

## How to setup the project

### Requirements

-   php >= 7.4
- PHP Extensions (openssl, php-common, php-curl, php-json, php-mbstring, php-mysql, php-xml, php-zip)
-   Mysql >=5.7

-   Composer

### **Setup**

-   Clone your repository
-   go inside the repository folder
-   open terminal and run `composer install`
-   On the terminal `cp .env.example .env`
-   On project folder open `.env` file and edit database configuration **`(DB_DATABASE=databaseName DB_USERNAME=dbusername DB_PASSWORD=dbpassword)`**
-   On the terminal run `php artisan key:generate`
-   Migrate the db by running `php artisan migrate`
