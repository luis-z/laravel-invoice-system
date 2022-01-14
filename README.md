# Laravel billing system
Web app using passport and vueJS for a invoice managment.
### Installation process

```
composer install
php artisan migrate
php artisan passport:install
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ProductoSeeder
php artisan serve
```
### Login
**[https://localhost:8000/api/login](https://localhost:8000/api/login)**
### Credencials
USUARIO:  [cliente1@email.com](mailto:cliente1@email.com)  
CLAVE: 12345678

USUARIO:  [cliente2@email.com](mailto:cliente2@email.com)
CLAVE: 12345678