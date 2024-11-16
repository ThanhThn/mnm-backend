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
        Schema::create('interactions', function (Blueprint $table) {
            $table->uuid('object_a_id');
            $table->uuid('object_b_id');
            $table->smallInteger('object_a_type')->index();
            $table->smallInteger('object_b_type')->index();
            $table->smallInteger('interaction_type')->index()->comment('1: like, 2: follow');
            $table->primary(['object_a_id', 'object_b_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
