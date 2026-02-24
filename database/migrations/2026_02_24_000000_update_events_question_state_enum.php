<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY question_state ENUM('hidden','question','option_a','option_b','option_c','option_d','revealed') NULL");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY question_state ENUM('blurred','unblurred','revealed') NULL");
        }
    }
};
