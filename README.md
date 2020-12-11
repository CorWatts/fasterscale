# The Faster Scale App 
> An online version of Michael Dye's Faster Relapse Awareness Scale 

[![codecov](https://codecov.io/gh/CorWatts/fasterscale/branch/master/graph/badge.svg)](https://codecov.io/gh/CorWatts/fasterscale)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CorWatts/fasterscale/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CorWatts/fasterscale/?branch=master)


## Getting Started
These instructions will help you get a local installation set up for development and testing purposes. See the deployment instructions for how to deploy this to a live system.
### Prerequisites
* PHP >= 7.1
* Composer
* A SASS compiler. We recommend the npm project ```node-sass```. Once npm is installed on your machine  type ```npm i node-sass -g```.
* PostgreSQL 9.1 or later

### Installation
* Clone this repo with:  
    ```git clone git@github.com:CorWatts/fasterscale.git && cd fasterscale```
* Install necessary dependencies with:  
    ```composer install --dev```
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
* startup local PHP server with ```composer start```
* visit [http://localhost:8080/signup](http://localhost:8080/signup) and create a new user
* log in, start working

## Running the Tests
Testing is provided by Codeception unit tests. The necessary libraries should have already been installed by Composer. First, the testing files must be scaffolded by running:
```bash
composer test-scaffold
```
To run the tests, ensure you're in the base directory of this repository and execute:
```bash
composer test
```
To view the code coverage of these tests run: (XDebug is required for generation of code coverage)
```bash
composer test-coverage
```

## Assets
We have built-in support for minimizing JS and CSS assets. The following steps should be sufficient:  
1. Install the the npm packages `uglify-js` and `uglifycss`  via ```npm install uglify-js uglifycss -g```
2. Swap the commented and uncommented code blocks in ```site/config/bundles-local.php```
3. Run the asset compression by executing ```composer assets```

That should result the browser downloading published asset bundles instead of each JS/CSS file individually.

## Deployment
This application is deployed live on https://fasterscaleapp.com using Capistrano. A recipe for that can be found in ```config/deploy.rb```. Additional instructions will be added in the near future.

## License
This application is under the BSD-3 license. See [LICENSE.md](https://github.com/CorWatts/fasterscale/blob/master/LICENSE.md) for details.

The _FASTER Relapse Awareness Scale_ is copyrighted by Michael Dye and Patricia Fancher. We have been granted permission to use it.

## Contributions
Want to contribute? Wonderful! We're excited to hear any and every idea you have about the Faster Scale App. If you're struggling to come up with a task of your own to work on, take a look at our issues list and feel free to tackle any of the unassigned issues.

## Questions?
Questions can be answered by [filing an issue](https://github.com/CorWatts/fasterscale/issues/new) on this project or by joining our mailing list: <https://www.freelists.org/list/fsa-discuss>.
