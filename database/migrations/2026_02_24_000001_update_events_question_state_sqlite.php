<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE events_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                started_at DATETIME NOT NULL,
                finished_at DATETIME NULL,
                status VARCHAR NOT NULL DEFAULT 'draft' CHECK (status IN ('draft', 'running', 'paused', 'finished')),
                created_by INTEGER NOT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                is_intro TINYINT NOT NULL DEFAULT 0,
                quiz_started TINYINT NOT NULL DEFAULT 0,
                current_question_seq INTEGER NULL,
                question_state VARCHAR NULL CHECK (question_state IN ('hidden', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'revealed')),
                timer_started_at BIGINT NULL,
                timer_stopped_at BIGINT NULL,
                FOREIGN KEY(created_by) REFERENCES users(id)
            )
        SQL);

        DB::statement(<<<'SQL'
            INSERT INTO events_new (
                id, title, started_at, finished_at, status, created_by, created_at, updated_at,
                is_intro, quiz_started, current_question_seq, question_state, timer_started_at, timer_stopped_at
            )
            SELECT
                id, title, started_at, finished_at, status, created_by, created_at, updated_at,
                is_intro, quiz_started, current_question_seq,
                CASE
                    WHEN question_state = 'blurred' THEN 'hidden'
                    WHEN question_state = 'unblurred' THEN 'question'
                    ELSE question_state
                END AS question_state,
                timer_started_at, timer_stopped_at
            FROM events
        SQL);

        DB::statement('DROP TABLE events');
        DB::statement('ALTER TABLE events_new RENAME TO events');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE events_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                started_at DATETIME NOT NULL,
                finished_at DATETIME NULL,
                status VARCHAR NOT NULL DEFAULT 'draft' CHECK (status IN ('draft', 'running', 'paused', 'finished')),
                created_by INTEGER NOT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                is_intro TINYINT NOT NULL DEFAULT 0,
                quiz_started TINYINT NOT NULL DEFAULT 0,
                current_question_seq INTEGER NULL,
                question_state VARCHAR NULL CHECK (question_state IN ('blurred', 'unblurred', 'revealed')),
                timer_started_at BIGINT NULL,
                timer_stopped_at BIGINT NULL,
                FOREIGN KEY(created_by) REFERENCES users(id)
            )
        SQL);

        DB::statement(<<<'SQL'
            INSERT INTO events_old (
                id, title, started_at, finished_at, status, created_by, created_at, updated_at,
                is_intro, quiz_started, current_question_seq, question_state, timer_started_at, timer_stopped_at
            )
            SELECT
                id, title, started_at, finished_at, status, created_by, created_at, updated_at,
                is_intro, quiz_started, current_question_seq,
                CASE
                    WHEN question_state = 'hidden' THEN 'blurred'
                    WHEN question_state = 'question' THEN 'unblurred'
                    WHEN question_state IN ('option_a', 'option_b', 'option_c', 'option_d') THEN 'unblurred'
                    ELSE question_state
                END AS question_state,
                timer_started_at, timer_stopped_at
            FROM events
        SQL);

        DB::statement('DROP TABLE events');
        DB::statement('ALTER TABLE events_old RENAME TO events');

        DB::statement('PRAGMA foreign_keys=ON');
    }
};
