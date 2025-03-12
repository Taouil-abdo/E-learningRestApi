<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(1, 100),
            'difficulty_level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'category_id' => Category::factory(),
            'status' => $this->faker->randomElement(['open', 'in progress', 'completed']),
        ];
    }
}
