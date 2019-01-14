<?php

use Faker\Generator as Faker;
use App\Models\{Transaction, Tag, User, Person, Company, Category};

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'value' => $faker->randomFloat(2, -pow(10, 6), pow(10, 6) - 1),
        'note' => $faker->text(),
        'code' => $faker->swiftBicNumber,
        'payment_method' => $faker->randomElement(['DN', 'CH', 'BB', 'CC', 'CD', 'TB', 'DA', 'NP']),
        'issued_at' => $faker->date(),
        'expires_at' => $faker->date(),
        'paid_at' => $faker->date(),
        'is_paid' => $faker->boolean,
        'user_id' => factory(User::class),
        'person_id' => factory(Person::class),
        'company_id' => factory(Company::class),
        'category_id' => factory(Category::class)->states('leaf'),
    ];
});

$factory->state(Transaction::class, 'sub', function (Faker $faker) {
    return [
        'parent_id' => factory(Transaction::class),
    ];
});
