<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psycho_assessments', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('student_id');
            $table->enum('assessment_type', ['stress','motivation','focus','anxiety','mood','sleep']);
            $table->decimal('value', 5, 2);
            $table->text('notes')->nullable();
            $table->dateTime('measured_at');
            $table->enum('source', ['self_report','counselor','ai_inferred']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psycho_assessments');
    }
};
