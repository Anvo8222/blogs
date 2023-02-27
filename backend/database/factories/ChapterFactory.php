<?php

namespace Database\Factories;

use App\Models\V1\Client\ChapterModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ChapterFactory extends Factory
{
    protected $model = ChapterModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'chapter_number' => $this->faker->numberBetween($min = 1, $max = 105),
            'title' => $this->faker->name,
            'content' => $this->faker->text,
            'slug_chapter' => $this->faker->name,
            'post_id' => $this->faker->numberBetween($min = 1, $max = 105),
        ];
    }
}
