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
        Schema::dropIfExists('pages');
        Schema::dropIfExists('projects');
        Schema::create('projects', function (Blueprint $table) {
            $table->id('id_projects');
            $table->text('projectName');
            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('pages', function (Blueprint $table) {
            $table->id('id_pages');
            $table->text('pageName');
            $table->text('filePath');
            $table->unsignedBigInteger('id_projects');
            $table->foreign('id_projects')->references('id_projects')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project', function (Blueprint $table) {
            //
        });
    }
};
