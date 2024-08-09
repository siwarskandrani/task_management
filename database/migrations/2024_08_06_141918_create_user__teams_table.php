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
        Schema::create('user__teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_user');
            $table->unsignedBigInteger('ID_team');
            $table->string('role');
            $table->timestamps();
            
            $table->foreign('ID_user')->references('id')->on('users')->onDelete('cascade');//on delete cascade cvd si  un utilisateur (ligne dans la table users) est supprimé, tous les enregistrements dans user_teams qui se réfèrent à cet utilisateur seront aussi supprimés.
            $table->foreign('ID_team')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user__teams');
    }
};
