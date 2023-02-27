<?php

namespace Database\Factories;

use App\Models\V1\Client\CommentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{
    protected $model = CommentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text,
            'user_id' => 2,
            // 'post_id' => $this->faker->numberBetween($min = 6, $max = 10),
            'post_id' => 8,
            'id_parent' => $this->faker->numberBetween($min = 2, $max = 3),
        ];
    }
}
