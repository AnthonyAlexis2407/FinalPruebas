<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_recipes', function (Blueprint $col) {
            $col->id();
            $col->foreignId('product_size_id')->constrained('product_sizes')->onDelete('cascade');
            $col->foreignId('component_product_size_id')->constrained('product_sizes')->onDelete('cascade');
            $col->decimal('quantity', 8, 2)->default(1); // How many to reduce (e.g., 1 cup)
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_recipes');
    }
};
