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
        Schema::create('multitask_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('multitask_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('owner')->default(false);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('multitask_id')->references('id')->on('multitasks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multitask_user');
    }
};
