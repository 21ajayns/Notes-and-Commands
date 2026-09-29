<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Gives every folder, note, task, command folder and command an owning organization.
 * Rows that already exist are moved into a single starting organization so no data
 * is lost. The column stays nullable at the database level because existing rows
 * are filled in after it is added; the application always sets it.
 */
return new class extends Migration
{
    private const TABLES = [
        'folders',
        'notes',
        'tasks',
        'command_folders',
        'commands',
    ];

    private const STARTING_ORGANIZATION_NAME = 'Cove';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignUuid('organization_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('organizations')
                    ->cascadeOnDelete();
            });
        }

        $hasExistingRows = \collect(self::TABLES)
            ->contains(fn (string $tableName) => DB::table($tableName)->exists());

        if ($hasExistingRows === false) {
            return;
        }

        $organizationId = (string) Str::uuid();
        $now = \now();

        DB::table('organizations')->insert([
            'id' => $organizationId,
            'name' => self::STARTING_ORGANIZATION_NAME,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach (self::TABLES as $tableName) {
            DB::table($tableName)
                ->whereNull('organization_id')
                ->update(['organization_id' => $organizationId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('organization_id');
            });
        }
    }
};
