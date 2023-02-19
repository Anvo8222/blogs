<?php

namespace Database\Factories;

use App\Models\V1\Client\PostModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    protected $model = PostModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->unique()->email,
            'desription' => $this->faker->text,
            'image' => $this->faker->imageUrl(640, 480),
            'status' => 'active',
            'slug' => 'slug',
            'user_id' => 1,
        ];
    }
}
