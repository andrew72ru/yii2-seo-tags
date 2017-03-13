<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 01.03.17
 * Time: 13:23
 */

/*
$config =  \yii\helpers\ArrayHelper::merge(
    require (dirname(dirname(__DIR__))) . '/common/config/main-local.php',
    require (dirname(dirname(__DIR__))) . '/common/config/main.php', [
        'id' => 'app-tests',
        'components' => [
            'db' => 'mysql:unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=myroom_test',
        ]
    ]
);

unset($config['components']['mailer']);

return $config;
*/

require dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/common/config/bootstrap.php';

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'app-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=myroom_tests',
            'username' => 'root',
            'password' => '111',
            'charset' => 'utf8',
        ],
        'request' => [
            'cookieValidationKey' => 'tests'
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
        ]
    ],
    'modules' => [
        'seotag' => [
            'class' => 'common\modules\seotag\Module',
        ],
    ]
];