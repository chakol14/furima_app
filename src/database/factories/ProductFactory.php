<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $fileName = 'sample' . $this->faker->numberBetween(1, 5) . '.jpg';

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'brand' => $this->faker->company(),
            'category_id' => null,
            'image_url' => 'images/' . $fileName,
        ];
    }
}
