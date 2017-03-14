<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 03.03.17
 * Time: 13:10
 *
 * @var integer $index
 */

$faker = Faker\Factory::create();

/** @var \yii\web\UrlManager $urlManager */
$urlManager = Yii::$app->urlManager;
$urlManager->setBaseUrl('http://localhost:8080');
$urlManager->setScriptUrl('/index-test.php');
$urlManager->setHostInfo('http://localhost:8080');

return [
    'url' => "index-test.php?r=site%2Findex",
    'small_pict' => 'http://localhost:8080/17.jpg',
    'large_pict' => 'http://localhost:8080/17.jpg',
    'description' => $faker->sentence(16),
];