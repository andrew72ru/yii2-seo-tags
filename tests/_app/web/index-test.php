<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.03.17
 * Time: 18:04
 */

if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

require dirname(dirname(__DIR__)) . '/_bootstrap.php';

$config = require(dirname(__DIR__) . '/config/test.php');
(new \yii\web\Application($config))->run();


