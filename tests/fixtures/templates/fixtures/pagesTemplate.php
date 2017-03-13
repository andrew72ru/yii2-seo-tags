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

return [
    'url' => "/",
    'small_pict' => $faker->url,
    'large_pict' => $faker->url,
    'description' => $faker->sentence(16),
];