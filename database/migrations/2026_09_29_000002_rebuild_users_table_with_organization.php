<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces Laravel's default users table (integer ids, never used) with one that
 * uses uuids like every other table and belongs to an organization. The Sanctum
 * tokens table is rebuilt too so its polymorphic owner column can hold a uuid.
 */
return new class extends Migration
{
    private const TABLE = 'users';

    private const TOKENS_TABLE = 'personal_access_tokens';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists(self::TOKENS_TABLE);
        Schema::dropIfExists(self::TABLE);

        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create(self::TOKENS_TABLE, function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TOKENS_TABLE);
        Schema::dropIfExists(self::TABLE);

        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create(self::TOKENS_TABLE, function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }
};
