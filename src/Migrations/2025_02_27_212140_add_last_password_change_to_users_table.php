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
        if (!Schema::hasColumn('users', 'last_password_change')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_password_change')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'last_password_change')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_password_change');
            });
        }
    }
};
