<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Code;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Code::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'code' => Str::random(10),
        'capacity' => rand(1, 10000),
        'enable' => rand(0, 1),
        'created_by' => factory(User::class)->create()->id,
        'updated_by' => factory(User::class)->create()->id,
    ];
});
