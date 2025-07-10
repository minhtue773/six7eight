<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->foreignId('brand_id')->constrained()->onDelete('restrict');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex');
            $table->tinyInteger('status')->default(0)->comment("0:none, 1:sản phẩm mới, 2:sản phẩm hot, 3:sắp hết hàng, 4:hết hàng, 5:ngừng bán	");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex_code')->nullable();
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('size_id')->constrained()->onDelete('restrict');
            $table->foreignId('color_id')->constrained()->onDelete('restrict');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('sku')->unique();
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('restrict');
            $table->string('image_url');
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('restrict');
            $table->foreignId('product_variant_id')->constrained()->onDelete('restrict');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('shipping_address')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->string('payment_method')->default('COD');
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->foreignId('product_variant_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('post_category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed'])->default('fixed');
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('max_uses_per_user')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->boolean('used')->default(false);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('coupon_user');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};