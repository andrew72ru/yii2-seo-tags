<?php

return [
    'id' => 'yii2-test-console',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@andrew72ru/seotag' => dirname(dirname(dirname(__DIR__))),
        '@tests' => dirname(dirname(__DIR__)),
    ],
    'bootstrap' => ['andrew72ru\seotag\Bootstrap'],
    'modules' => [
        'seotag' => [
            'class' => 'andrew72ru\seotag\Module',
        ],
    ],
    'components' => [
        'log'   => null,
        'cache' => null,
        'db'    => require __DIR__ . '/db.php',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'andrew72ru\seotag\commands'
            ],
            'migrationPath' => null,
        ],
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'templatePath' => '@tests/fixtures/templates/fixtures',
            'fixtureDataPath' => '@tests/_data'
        ]
    ]
];
