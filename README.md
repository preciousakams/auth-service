## Liveizy Auth Service

#### Requirements
* php >=7.2
* mysql >= 5.6
* Apache/Nginx
* composer


## Step to run application
* clone/pull the application from the repo
* cd into project into project folder
* run ```
      composer install
      ``` to install the dependencies
* duplicate the .env.example file and name it .env
* update the necessary value
- __[such as ]()__
    + run ``` php artisan generate:key ``` to generate an application key
    + update the [APP_URL=](). e.g http://127.0.0.1
    + [DB_DATABASE=]() database name
    + [DB_USERNAME=]() database user
    + [DB_PASSWORD=]() database password
   
- migrate the database tables with dummy data by running
    + ``` php artisan migrate --seed ``` 
- should incase something failed or for whatever reason
 and you need the run this command  again, please add :refresh to
  rollback the previous table from migrating e.g
  ``` php artisan migrate:refresh --seed```
  
  Currently the User feeds uses seeds data from user factory. so you'll
  have to check your user table after migration to get email if you want
  to login, and the password is the same for all user which is: password 
