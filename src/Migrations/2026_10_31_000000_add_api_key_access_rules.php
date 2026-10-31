<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            foreach (['allow_web', 'allow_mobile', 'allow_flutter', 'allow_desktop', 'allow_backend', 'allow_localhost'] as $column) {
                $table->boolean($column)->default(false);
            }
            $table->json('allowed_routes')->nullable();
            $table->timestamp('last_used_at')->nullable()->change();
        });

        Schema::create('api_key_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->constrained('api_keys')->cascadeOnDelete();
            $table->string('method', 10);
            $table->string('endpoint');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('client_type', 30)->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_key_logs');
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropColumn(['allow_web', 'allow_mobile', 'allow_flutter', 'allow_desktop', 'allow_backend', 'allow_localhost', 'allowed_routes']);
        });
    }
};
