<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX_NAME = 'audits_created_at_index';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');
        $schema = Schema::connection($connection);

        if (! $schema->hasIndex($table, self::INDEX_NAME)) {
            $schema->table($table, function (Blueprint $table): void {
                $table->index('created_at', self::INDEX_NAME);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');
        $schema = Schema::connection($connection);

        if ($schema->hasIndex($table, self::INDEX_NAME)) {
            $schema->table($table, function (Blueprint $table): void {
                $table->dropIndex(self::INDEX_NAME);
            });
        }
    }
};
