<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 01.03.17
 * Time: 13:23
 *
 * Команда на миграцию
 * ./yii  migrate/up --appconfig=common/modules/seotag/tests/app/config-console.php --migrationPath=console/migrations
 */

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'app-tests-console',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=myroom_tests',
            'username' => 'root',
            'password' => '111',
            'charset' => 'utf8',
        ],
    ],
];