<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number', 50)->unique();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->foreignId('client_id')->constrained('clients')->restrictOnDelete();
            $table->foreignId('pic_user_id')->constrained('users')->restrictOnDelete();
            $table->string('case_type', 50);
            $table->string('status', 20)->default('Draft');
            $table->string('priority', 10)->default('Medium');
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('pic_user_id');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};