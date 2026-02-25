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
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->string('name')->nullable()->after('product_id');
            // We'll keep size_id for now but make it nullable if we want to fully detach later
            // For this step, let's just add 'name' and populate it.
            $table->foreignId('size_id')->nullable()->change();
        });

        // Migrate existing data: Copy name from 'sizes' table to 'product_sizes' table
        $productSizes = \DB::table('product_sizes')->get();
        foreach ($productSizes as $ps) {
            if ($ps->size_id) {
                $sizeName = \DB::table('sizes')->where('id', $ps->size_id)->value('number');
                if ($sizeName) {
                    \DB::table('product_sizes')->where('id', $ps->id)->update(['name' => $sizeName]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->foreignId('size_id')->nullable(false)->change();
        });
    }
};
