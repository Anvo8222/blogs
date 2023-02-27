<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\V1\Client\CategoryPostModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostCategoryFactory extends Factory
{
    protected $model = CategoryPostModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => $this->faker->numberBetween($min = 1, $max = 8),
            'post_id' => $this->faker->numberBetween($min = 3, $max = 105),
        ];
    }
}
