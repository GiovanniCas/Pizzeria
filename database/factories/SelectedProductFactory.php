<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SelectedProduct>
 */
class SelectedProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => 1,
            'header_id' => session('header_id'),
            'quantity' => 2,
            'price_uni' => 2,
        ];
    }
}
