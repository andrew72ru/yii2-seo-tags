<?php

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=seotag_test',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];

if (file_exists(__DIR__ . '/db.local.php')) {
    $db = array_merge($db, require(__DIR__ . '/db.local.php'));
}

return $db;