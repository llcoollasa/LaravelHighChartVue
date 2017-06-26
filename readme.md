## Temper Assignment

**Implementation steps**

Clone the repository
    git clone git@bitbucket.org:llcoollasa/temper.work.git

Run Composer
    
    composer update

Install npm

    npm install

Run Laravelmix  

    npm run dev

Database Configuration

    Create new database called temper
    Update .env with necessary details

    DB_DATABASE=temper
    DB_USERNAME=root
    DB_PASSWORD=root

Run Migration & seed
 
    php artisan migrate

    php artisan db:seed

Start webserver

    php artisan serve




**Notes**

- Test Cases can be found in tests/Feature/ExampleTest.php
- web.php contains the routes
- Please create a user and log in to see the report