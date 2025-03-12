<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'course_id' => Course::factory(),
        ];
    }
}
