<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\V1\BlogModel;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // BlogFactory()
        // BlogModel::factory()->count(3)->create();
        for ($i = 0; $i < 10; $i++) {
            $blog = new BlogModel;
            $blog->title = fake()->title;
            $blog->content = fake()->text;
            $blog->image = fake()->imageUrl(640, 480);
            $blog->id_user = 1;
        }
    }
}