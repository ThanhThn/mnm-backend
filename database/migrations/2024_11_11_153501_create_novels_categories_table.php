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
        Schema::create('novels_categories', function (Blueprint $table) {
            $table->uuid('novel_id');
            $table->uuid('category_id');
            $table->primary(['novel_id', 'category_id']);
            $table->char('novel_type', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novels_categories');
    }
};
