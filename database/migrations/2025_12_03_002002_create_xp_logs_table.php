<?php
// database/migrations/2025_12_03_002002_create_xp_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('xp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('source_type'); // exam, path, quest, teacher_reward, ...
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedInteger('xp_amount');
            $table->timestamps();

            $table->index(['student_id', 'source_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_logs');
    }
};
