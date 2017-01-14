# The Faster Scale App 
> An online version of Michael Dye's Faster Relapse Awareness Scale 

[![Build Status](https://travis-ci.org/CorWatts/fasterscale.svg?branch=master)](https://travis-ci.org/CorWatts/fasterscale)
[![codecov](https://codecov.io/gh/CorWatts/fasterscale/branch/master/graph/badge.svg)](https://codecov.io/gh/CorWatts/fasterscale)


## Getting Started
These instructions will help you get a local installation set up for development and testing purposes. See the deployment instructions for how to deploy this to a live system.
### Prerequisites
* PHP >= 7.0
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
    ```composer global require "fxp/composer-asset-plugin:^1.2.0" && composer install --dev```
1. You'll need a sass compiler. the ```sass``` Ruby Gem is recommended. Once rubygems is installed on your machine (if you're on MacOS it already is) yousimply need to type ```gem install sass```.
1. execute the init file ```./init --env=Development``` with the environment set to **Development** option
1. Edit ```site/config/main-local.php``` and add a cookie validation key in the $config variable
```php
<?php
$config = [ 
  'components' => [
    'request' => [
      'cookieValidationKey' => 'devcookiekey'
    ],
    [...other good stuff...]
  ]
];
```
1. Edit ```common/config/main-local.php``` and edit the default database connection information in the $config variable
```php
<?php
$config = [ 
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => '[DB_CONNECTION_STRING]',
      'username' => '[DB_USERNAME]',
      'password' => '[DB_PASSWORD]',
      'charset' => 'utf8',
    ],
    [...other good stuff...]
  }
};
```
1. run all yii2 db migrations ```./yii migrate```
1. startup local PHP server with ```cd site/web && php -S localhost:8080 router.php```
1. visit [http://localhost:8080/signup](http://localhost:8080/signup) and create a new user
1. log in, start working

## Running the Tests
Testing is provided by Codeception unit tests. The necessary libraries should have already been installed by Composer. To run them, ensure you're in base directory of this repository and execute:
```bash
composer exec -v codecept build
composer exec -v codecept run
```
To generate code coverage ensure you have Xdebug installed and run the codecept again with the appropriate flags:  
    ```composer exec -v codecept run --coverage --coverage-xml --coverage-html --ansi```

## Deployment
This application is deployed live on https://fasterscaleapp.com using Capistrano. A recipe for that can be found in ```config/deploy.rb```. Additional instructions will be added in the near future.

## License
This application is under the BSD-3 license. See [LICENSE.md](https://github.com/CorWatts/fasterscale/blob/master/LICENSE.md) for details.

The _FASTER Relapse Awareness Scale_ is copyrighted by Michael Dye and Patricia Fancher. We have been granted permission to use it.

## Contributions
Want to contribute? Wonderful! We're excited to hear any and every idea you have about the Faster Scale App. If you're struggling to come up with a task of your own to work on, take a look at our issues list and feel free to tackle any of the unassigned issues.
