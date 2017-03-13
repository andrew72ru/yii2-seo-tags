<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 03.03.17
 * Time: 13:15
 *
 * @var integer $index
 */

$faker = Faker\Factory::create();

return [
    'url_id' => $index,
    'word' => $faker->word
];