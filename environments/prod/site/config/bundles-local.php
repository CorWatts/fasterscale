<?php

// uncheck the below to use compressed assets, after running the `./yii asset` command
//return require(dirname($_SERVER['SCRIPT_FILENAME']) . '/../assets/assets-compressed.php');

return [
  yii\bootstrap\BootstrapAsset::class => [
    'css' => [], // disable bootstrap css because we pull it directly into our app.scss file
  ],
];
