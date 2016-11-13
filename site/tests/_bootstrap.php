<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(dirname(__DIR__)));

#defined('SITE_ENTRY_URL') or define('SITE_ENTRY_URL', parse_url(\Codeception\Configuration::config()['config']['test_entry_url'], PHP_URL_PATH));
defined('SITE_ENTRY_FILE') or define('SITE_ENTRY_FILE', YII_APP_BASE_PATH . '/site/web/index-test.php');

require_once(YII_APP_BASE_PATH . '/vendor/autoload.php');
require_once(YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');
Yii::setAlias('@tests', dirname(__DIR__));
#require_once(YII_APP_BASE_PATH . '/common/config/bootstrap.php');
#require_once(YII_APP_BASE_PATH . '/site/config/bootstrap.php');
