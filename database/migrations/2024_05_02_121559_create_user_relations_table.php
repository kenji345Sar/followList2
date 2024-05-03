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
        Schema::create('user_relations', function (Blueprint $table) {
            $table->id('relation_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('target_user_id');
            $table->boolean('is_following');
            $table->boolean('is_blocking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relations');
    }
};
