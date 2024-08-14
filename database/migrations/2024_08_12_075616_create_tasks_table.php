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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('To do');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null')->change();
            $table->unsignedBigInteger('owner');
            $table->unsignedTinyInteger('type')->default(1); // 1: main_task, 2: sub_task
            $table->unsignedBigInteger('parent_task')->nullable();
            $table->timestamps();
    
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('owner')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_task')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
