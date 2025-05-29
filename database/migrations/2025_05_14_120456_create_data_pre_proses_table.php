<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('data_pre_proses', function (Blueprint $table) {
            $table->id();
            $table->text('cleaned');
            $table->string('sentiment');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('data_pre_proses');
    }
};

