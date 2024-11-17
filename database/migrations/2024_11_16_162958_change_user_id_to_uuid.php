<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('new_id')->after('id')->default(Str::uuid()); // Thêm cột uuid
        });

        DB::table('users')->get()->each(function ($user) {
            $newUuid = Str::uuid();
            DB::table('users')
            ->where('id', $user->id)
            ->update(['new_id' => $newUuid]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->primary('new_id');
            $table->renameColumn('new_id', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');

            $table->increments('id')->primary()->first();
        });
    }
};
