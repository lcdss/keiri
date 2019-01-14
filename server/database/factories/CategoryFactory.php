<?php

use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});

$factory->state(Category::class, 'leaf', function (Faker $faker) {
    return [
        'parent_id' => factory(Category::class),
    ];
});
