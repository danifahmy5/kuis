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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('config');
            $table->boolean('is_intro')->default(false)->after('status');
            $table->boolean('quiz_started')->default(false)->after('is_intro');
            $table->unsignedInteger('current_question_seq')->nullable()->after('quiz_started');
            $table->enum('question_state', ['blurred', 'unblurred', 'revealed'])->nullable()->after('current_question_seq');
            $table->unsignedBigInteger('timer_started_at')->nullable()->after('question_state');
            $table->unsignedBigInteger('timer_stopped_at')->nullable()->after('timer_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('config')->nullable()->after('status');
            $table->dropColumn([
                'is_intro',
                'quiz_started',
                'current_question_seq',
                'question_state',
                'timer_started_at',
                'timer_stopped_at',
            ]);
        });
    }
};
