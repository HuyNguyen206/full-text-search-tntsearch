<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UserSeeder::class);
        factory(User::class, 50)->create();
        factory(Post::class, 50)->create();
        factory(Comment::class, 100)->create();
    }
}
