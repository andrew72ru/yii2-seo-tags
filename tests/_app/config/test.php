<?php

return [
    'id' => 'yii2-seotag-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@andrew72ru/seotag' => dirname(dirname(dirname(__DIR__))),
        '@tests' => dirname(dirname(__DIR__)),
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower-asset',
    ],
    'bootstrap' => ['andrew72ru\seotag\Bootstrap'],
    'modules' => [
        'seotag' => [
            'class' => 'andrew72ru\seotag\Module',
            'twitterUsername' => '@twitterUser',
            'imagePath' => '@tests/_envs/share',
            'imageUrl' => '/share',
        ],
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
            'baseUrl' => 'http://localhost:8080/'
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'basePath' => dirname(dirname(__DIR__)) . '/_envs/assets'
        ],
    ],
    'params' => [],
];