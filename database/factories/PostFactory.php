<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        //
        'title' => $faker->word(3),
        'body' => $faker->paragraph(3),
        'user_id' => User::all()->random()
    ];
});
