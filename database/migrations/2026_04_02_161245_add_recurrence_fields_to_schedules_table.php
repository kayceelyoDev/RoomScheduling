<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            //
            $table->boolean('is_recurring')->default(false)->after('end_time');
            $table->string('repeat_type')->nullable()->after('is_recurring'); // daily, weekly, mwf
            $table->json('repeat_days')->nullable()->after('repeat_type');    // Stores ['Monday', 'Wednesday']
            $table->date('repeat_until')->nullable()->after('repeat_days');   // The end date of the recurrence
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            //
            $table->dropColumn(['is_recurring', 'repeat_type', 'repeat_days', 'repeat_until']);
        });
    }
};
