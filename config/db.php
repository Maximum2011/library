<?php

$localFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db-local.php';
$localConf = file_exists($localFile) ? require($localFile) : [];

return array_merge(
    [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=library',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ],
    $localConf
);
