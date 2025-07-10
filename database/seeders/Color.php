<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Color extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Đen', 'hex' => '#000000'],
            ['name' => 'Trắng', 'hex' => '#FFFFFF'],
            ['name' => 'Đỏ', 'hex' => '#FF0000'],
            ['name' => 'Xanh dương', 'hex' => '#0074D9'],
            ['name' => 'Xanh lá', 'hex' => '#2ECC40'],
            ['name' => 'Vàng', 'hex' => '#FFDC00'],
            ['name' => 'Cam', 'hex' => '#FF851B'],
            ['name' => 'Tím', 'hex' => '#B10DC9'],
            ['name' => 'Hồng', 'hex' => '#FF69B4'],
            ['name' => 'Xám', 'hex' => '#AAAAAA'],
            ['name' => 'Nâu', 'hex' => '#8B4513'],
            ['name' => 'Xanh navy', 'hex' => '#001F3F'],
            ['name' => 'Xanh ngọc', 'hex' => '#39CCCC'],
            ['name' => 'Vàng nhạt', 'hex' => '#FFFACD'],
            ['name' => 'Bạc', 'hex' => '#C0C0C0'],
            ['name' => 'Vàng gold', 'hex' => '#FFD700'],
        ];
        foreach ($colors as $index => $color) {
            \App\Models\Color::create([
                "id" => $index + 1,
                "name" => $color['name'],
                "hex_code" => $color['hex'],
            ]);
        }
    }
}