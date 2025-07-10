<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Color;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class Product extends Seeder
{
    public function run(): void
    {
        // Tạo danh mục
        $category = ProductCategory::firstOrCreate(['name' => 'Giày Thể Thao']);
        $brand = Brand::firstOrCreate(['name' => 'Nike']);

        // Tạo size
        $sizes = ['38', '39', '40', '41', '42'];
        foreach ($sizes as $sizeName) {
            Size::firstOrCreate(['name' => $sizeName]);
        }

        // Tạo màu
        $colors = [
            ['name' => 'Đen', 'hex_code' => '#000000'],
            ['name' => 'Trắng', 'hex_code' => '#FFFFFF'],
            ['name' => 'Đỏ', 'hex_code' => '#FF0000'],
        ];
        foreach ($colors as $color) {
            Color::firstOrCreate($color);
        }

        // Danh sách sản phẩm mẫu
        $products = [
            'Nike Air Zoom Pegasus',
            'Nike Court Vision Low',
            'Nike Air Max 90',
        ];

        foreach ($products as $productName) {
            $product = \App\Models\Product::create([
                'name' => $productName,
                'slug' => Str::slug($productName),
                'description' => 'Mẫu giày ' . $productName . ' rất nổi bật.',
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'gender' => 'unisex',
                'status' => rand(0, 3),
            ]);

            // Thêm biến thể (variant)
            foreach (Size::all() as $size) {
                foreach (Color::all() as $color) {
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'price' => rand(1000, 2000) * 1000,
                        'stock' => rand(5, 20),
                        'sku' => strtoupper(Str::random(8)),
                    ]);

                    // Thêm ảnh sản phẩm theo màu
                    ProductImage::create([
                        'product_id' => $product->id,
                        'color_id' => $color->id,
                        'image_url' => 'https://via.placeholder.com/300x300?text=' . urlencode($productName . '-' . $color->name),
                        'is_main' => true,
                    ]);
                }
            }
        }
    }

}