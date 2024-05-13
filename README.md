# Pocket-Bar
### Sales web application
Point of sale with stock management web system
## Instalation
### /pocket-bar-front
 ```properties zsh 
 npm install
 ```
### /pocket-bar-api
#### Dependencies update
```properties zsh 
 composer update
 ```
 #### Dependencies instalation
```properties zsh 
 composer install
 ```
 #### Migrations
```properties zsh 
php artisan migrate:fresh --seed
 ```
## Run project
### /pocket-bar-front
 ```properties zsh 
 npm run serve
 ```
 ### /pocket-bar-api
#### Serve
```properties zsh 
 npm run back-serve-prod
 ```
#### If you want to run multitenant run this
```properties zsh
 php artisan serve --host=localhost 
```
 #### Socket serve
```properties zsh 
 npm run socket-serve-prod
 ```
