<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.03.17
 * Time: 9:40
 */

return [
    'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',
    'languages' => ['ru-RU'],
    'translator' => 'Yii::t',
    'sort' => true,
    'removeUnused' => true,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/vendor',
    ],
    'format' => 'php',
    'messagePath' => __DIR__,
    'overwrite' => true,
    'ignoreCategories' => ['yii']
];