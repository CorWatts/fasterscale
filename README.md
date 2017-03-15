# The Faster Scale App 
> An online version of Michael Dye's Faster Relapse Awareness Scale 

[![Build Status](https://travis-ci.org/CorWatts/fasterscale.svg?branch=master)](https://travis-ci.org/CorWatts/fasterscale)
[![codecov](https://codecov.io/gh/CorWatts/fasterscale/branch/master/graph/badge.svg)](https://codecov.io/gh/CorWatts/fasterscale)


## Getting Started
These instructions will help you get a local installation set up for development and testing purposes. See the deployment instructions for how to deploy this to a live system.
### Prerequisites
* PHP >= 5.6
* Composer
* A SASS compiler. We recommend the ```sass``` Ruby Gem. Once rubygems is installed on your machine (if you're on MacOS it already is) you should type ```gem install sass```.
* PostgreSQL 9.1 or later (Theoretically any type of SQL compatible with Yii2's ActiveRecord might work, but we've only tested this on PostgreSQL)

### Installation
* Clone this repo with:  
    ```git clone git@github.com:CorWatts/fasterscale.git && cd fasterscale```
* Install necessary dependencies with:  
    ```composer global require "fxp/composer-asset-plugin:^1.2.0" && composer install --dev```
* Execute the init file ```./init --env=Development``` with the environment set to **Development** option
* Edit ```site/config/main-local.php``` and add a cookie validation key in the $config variable
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
* Edit ```common/config/main-local.php``` and edit the default database connection information in the $config variable
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
* run all yii2 db migrations ```./yii migrate```
* startup local PHP server with ```cd site/web && php -S localhost:8080 router.php```
* visit [http://localhost:8080/signup](http://localhost:8080/signup) and create a new user
* log in, start working

## Running the Tests
Testing is provided by Codeception unit tests. The necessary libraries should have already been installed by Composer. First, the testing files must be scaffolded by running:
```bash
composer test-scaffold
```
To then run them, ensure you're in base directory of this repository and execute:
```bash
composer test
```
If you would like to view the code coverage provided by these tests run this command: (XDebug is required for generation of code coverage)
```bash
composer test-coverage
```

## Assets
We have built-in support for minimizing JS and CSS assets. Ensure the npm packages uglifyjs and uglifycss are installed via ```npm install uglifyjs uglifycss -g```. Then uncomment the respective assetManager lines in ```site/config/main.php``` or override them in ```site/config/main-local.php``` and execute:
```bash
./yii asset site/assets/assets.php site/assets/assets-compressed.php
```

## Deployment
This application is deployed live on https://fasterscaleapp.com using Capistrano. A recipe for that can be found in ```config/deploy.rb```. Additional instructions will be added in the near future.

## License
This application is under the BSD-3 license. See [LICENSE.md](https://github.com/CorWatts/fasterscale/blob/master/LICENSE.md) for details.

The _FASTER Relapse Awareness Scale_ is copyrighted by Michael Dye and Patricia Fancher. We have been granted permission to use it.

## Contributions
Want to contribute? Wonderful! We're excited to hear any and every idea you have about the Faster Scale App. If you're struggling to come up with a task of your own to work on, take a look at our issues list and feel free to tackle any of the unassigned issues.
