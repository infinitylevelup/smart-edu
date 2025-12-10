<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psycho_assessments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('assessment_type', ['stress','motivation','focus','anxiety','mood','sleep']);
            $table->decimal('value', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->dateTime('measured_at');
            $table->enum('source', ['self_report','counselor','ai_inferred'])->default('self_report');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psycho_assessments');
    }
};
