# The Faster Scale App 
> An online version of Michael Dye's Faster Relapse Awareness Scale 

[![Build Status](https://travis-ci.org/CorWatts/fasterscale.svg?branch=master)](https://travis-ci.org/CorWatts/fasterscale)
[![codecov](https://codecov.io/gh/CorWatts/fasterscale/branch/master/graph/badge.svg)](https://codecov.io/gh/CorWatts/fasterscale)


## Getting Started
These instructions will help you get a local installation set up for development and testing purposes. See the deployment instructions for how to deploy this to a live system.
### Prerequisites
* PHP >= 5.6
* Composer
* A type of SQL compatible with Yii2's ActiveRecord:
  * MySQL 4.1 or later
  * PostgreSQL 7.3 or later
  * SQLite 2 and 3
  * Microsoft SQL Server 2008 or later
  * Oracle

### Installation
1. Clone this repo with:  
    ```git clone git@github.com:CorWatts/fasterscale.git && cd fasterscale```
1. Install necessary dependencies with:  
    ```composer global require "fxp/composer-asset-plugin:~1.1.1" && composer install --dev```
1. execute the init file ```./init``` and choose the **Development** option
1. Create ```site/config/main-local.php``` and add a cookie validation key and database connection information (replacing the strings enclosed by [])
```php
<?php
return [ 
  'components' => [
    'request' => [
      'cookieValidationKey' => 'devcookiekey'
    ],
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => '[DB_CONNECTION_STRING]',
      'username' => '[DB_USERNAME]',
      'password' => '[DB_PASSWORD]',
      'charset' => 'utf8',
    ],
  ]
];
```
1. run all yii2 db migrations ```./yii migrate```
1. startup local PHP server with ```cd site/web && php -S localhost:8080 router.php```
1. visit [http://localhost:8080/signup](http://localhost:8080/signup) and create a new user
1. log in, start working

## Running the Tests
Testing is provided by Codeception unit tests. The necessary libraries should have already been installed by Composer. To run them, ensure you're in the correct directory and execute:
```bash
cd tests/codeception/common
./../../../vendor/bin/codecept run --ansi
```
To generate code coverage, add the appropriate flags:  
    ```./../../../vendor/bin/codecept run --coverage --coverage-xml --coverage-html --ansi```

## Deployment
This application is deployed live on https://fasterscaleapp.com using Capistrano. A recipe for that can be found in ```config/deploy.rb```. Additional instructions will be added in the near future.

## License
This application is under the MIT license. See [LICENSE.md](https://github.com/CorWatts/fasterscale/blob/master/LICENSE.md) for details.

## Roadmap
#### Pressing needs
* Create reminder functionality detailed in [issue #13](https://github.com/CorWatts/fasterscale/issues/13)
* the UI needs tons of work. Pretty darn ugly right now in [issue #43](https://github.com/CorWatts/fasterscale/issues/43)
* ~~Add ability to delete your account detailed in [issue #12](https://github.com/CorWatts/fasterscale/issues/12)~~

#### 10000" view
* MOBILE APP
* Move away from bootstrap (to material design?) in [issue #43](https://github.com/CorWatts/fasterscale/issues/43)
