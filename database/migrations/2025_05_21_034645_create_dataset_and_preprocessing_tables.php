<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel dataset
        Schema::create('dataset', function (Blueprint $table) {
            $table->id();
            $table->string('conversation_id_str', 50)->nullable();
            $table->integer('favorite_count')->nullable();
            $table->text('full_text')->nullable();
            $table->string('id_str', 50)->nullable();
            $table->text('image_url')->nullable();
            $table->string('in_reply_to_screen_name', 100)->nullable();
            $table->string('lang', 10)->nullable();
            $table->string('location', 255)->nullable();
            $table->integer('quote_count')->nullable();
            $table->integer('reply_count')->nullable();
            $table->integer('retweet_count')->nullable();
            $table->text('tweet_url')->nullable();
            $table->string('user_id_str', 50)->nullable();
            $table->string('username', 100)->nullable();
            $table->timestamps();
        });

        // Tabel preprocessing (tanpa full_text)
        Schema::create('preprocessing', function (Blueprint $table) {
            $table->id();
            $table->text('data_clean')->nullable();
            $table->text('lowercasing')->nullable();
            $table->text('remove_punctuation')->nullable();
            $table->text('tokenizing')->nullable();
            $table->text('stopword')->nullable();
            $table->text('stemming')->nullable();
            $table->enum('sentiment', ['positif', 'negatif'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preprocessing');
        Schema::dropIfExists('dataset');
    }
};
