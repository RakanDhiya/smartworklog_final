<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('activity_type_id')->constrained('activity_types')->restrictOnDelete();
            $table->text('description');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('duration_minutes');
            $table->string('status', 20)->default('Submitted');
            $table->text('ai_summary')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('case_id');
            $table->index('activity_type_id');
            $table->index('start_time');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};