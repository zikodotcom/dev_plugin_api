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
        Schema::create('data_for_dev_pl', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->string('id_project', 10);
            $table->string('id_page', 10);
            $table->string('language_name', 50);
            $table->string('viewport_name', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_for_dev_pl');
    }
};
