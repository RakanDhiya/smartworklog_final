<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role_in_case', 30)->default('Member');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['case_id', 'user_id']);
            $table->index('case_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_assignments');
    }
};