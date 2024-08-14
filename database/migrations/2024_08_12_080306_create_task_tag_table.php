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
        Schema::create('task_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');//on delete cascade cvd si  un utilisateur (ligne dans la table users) est supprimé, tous les enregistrements dans user_teams qui se réfèrent à cet utilisateur seront aussi supprimés.
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');//on delete cascade cvd si  un utilisateur (ligne dans la table users) est supprimé, tous les enregistrements dans user_teams qui se réfèrent à cet utilisateur seront aussi supprimés.

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_tag');
    }
};
