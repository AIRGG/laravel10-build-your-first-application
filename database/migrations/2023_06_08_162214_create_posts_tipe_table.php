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
        Schema::create('posts_tipe', function (Blueprint $table) {
            $table->id();
            // -- custom -- \\
            $table->string('kode_tipe');
            $table->text('nama_tipe');
            // [END] custom -- \\
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_tipe');
    }
};
