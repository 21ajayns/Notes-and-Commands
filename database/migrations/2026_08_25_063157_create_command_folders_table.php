<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'command_folders';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->uuid('id');
            // Declared before the self-referencing key below: Postgres adds keys in
            // the order they are declared and needs the primary key to exist first.
            $table->primary('id');
            $table->string('name');
            $table->string('category');
            $table->foreignUuid('command_folder_id')->nullable()->constrained(self::TABLE)->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
