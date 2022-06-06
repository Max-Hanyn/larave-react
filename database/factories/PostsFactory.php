<?php

namespace Database\Factories;

use App\Models\Posts;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostsFactory extends Factory
{


    protected $model = Posts::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'header' => $this->faker->title,
            'content' => $this->faker->paragraph,
            'image_src' => $this->faker->image,
            'user_id' => $this->faker->numberBetween(0,10),
            'created_at' => now(),
            'updated_at' => now()

        ];
    }
}
