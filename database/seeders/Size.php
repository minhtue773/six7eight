<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Size extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ["name" => 36],
            ["name" => 37],
            ["name" => 38],
            ["name" => 39],
            ["name" => 40],
            ["name" => 41],
            ["name" => 42],
            ["name" => 43],
            ["name" => 44],
            ["name" => 45],
        ];
        foreach ($sizes as $index => $size) {
            \App\Models\Size::create([
                "id" => $index + 1,
                "name" => $size['name']
            ]);
        }
    }
}