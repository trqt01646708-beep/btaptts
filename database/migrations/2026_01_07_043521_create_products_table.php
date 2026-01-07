<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('regular_price', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
