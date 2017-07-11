## Temper Assignment

**Implementation steps**

Clone the repository
    git clone git@github.com:llcoollasa/LaravelHighChartVue.git

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

Unit Tests

    vendor/phpunit/phpunit/phpunit  --filter=testGetReportOne
    vendor/phpunit/phpunit/phpunit  --filter=testReportTest


**Special Notes**

- Test Cases can be found in tests/
- web.php contains the routes
- Please create a user and log in to see the report

**Steps**
1. Click register and register for a user
2. Go to homepage & click "RETENTION CURVES"
3. Click On View Report

Cheers.
