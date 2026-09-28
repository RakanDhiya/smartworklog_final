<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->restrictOnDelete();
            $table->string('priority', 15)->default('Medium');
            $table->string('status', 15)->default('Todo');
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->timestamps();

            $table->index('assigned_to');
            $table->index('case_id');
            $table->index('status');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
