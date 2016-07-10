# The Faster Scale App 
> An online version of The Faster Scale 

[![Build Status](https://travis-ci.org/CorWatts/fasterscale.svg?branch=master)](https://travis-ci.org/CorWatts/fasterscale)
[![codecov](https://codecov.io/gh/CorWatts/fasterscale/branch/master/graph/badge.svg)](https://codecov.io/gh/CorWatts/fasterscale)


## Getting Started
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
1. Clone this repo
1. Install necessary dependencies with ```composer global require "fxp/composer-asset-plugin:~1.1.1" && composer install --dev```
1. execute the init file and choose the Development option: ```./init```
1. Add a cookie validation key to ```site/config/main-local.php```  
```php
$config = [ 
  'components' => [
    'request' => [
      'cookieValidationKey' => 'devcookiekey'
    ]
  ]
]
```
1. setup database and database user and password
1. enter database connection information and credentials in common/config/main-local.php
1. run all yii2 db migrations ```./yii migrate```
1. startup local PHP server with ```cd site/web && php -S localhost:8080 router.php```
1. visit [http://localhost:8080/signup](http://localhost:8080/signup) and create a new user
1. log in, start working


## Roadmap
#### Pressing needs
* Create reminder functionality detailed in [issue #13](https://github.com/CorWatts/fasterscale/issues/13)
* ~~Add ability to delete your account detailed in [issue #12](https://github.com/CorWatts/fasterscale/issues/12)~~

#### Lesser needs
* ~~Make it a lot easier to install locally in a development environment~~
* the UI needs tons of work. Pretty darn ugly right now in [issue #43](https://github.com/CorWatts/fasterscale/issues/43)

#### 10000" view
* MOBILE APP
* Move away from bootstrap (to material design?) in [issue #43](https://github.com/CorWatts/fasterscale/issues/43)
* move to Single Page App with one backend api and a frontend framework (Angular?)
