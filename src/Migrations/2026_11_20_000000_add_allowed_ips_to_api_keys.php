<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->json('allowed_ips')->nullable()->after('allowed_routes');
        });
    }

    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropColumn('allowed_ips');
        });
    }
};
