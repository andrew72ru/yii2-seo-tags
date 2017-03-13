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
    'url' => "page{$index}",
    'small_pict' => "small_picture{$index}",
    'large_pict' => "large_picture{$index}",
    'keywords_id' => $faker->randomDigit,
    'description' => $faker->sentence(16),
];