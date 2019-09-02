<?php
$config = [
  'components' => [
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => 'pgsql:host=localhost;dbname=fsatest',
      'username' => 'fsatest',
      'password' => 'test123',
      'charset' => 'utf8',
    ],
  ]
  
];

return $config;
