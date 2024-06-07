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
        Schema::table('deposit_team', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['team_id']);
            $table->dropForeign(['deposit_id']);

            // Drop the existing unique constraint
            $table->dropUnique(['team_id', 'deposit_id']);

            // Add the new unique constraint with 'role'
            $table->unique(['team_id', 'deposit_id', 'role']);

            // Re-add foreign key constraints
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('deposit_id')->references('id')->on('deposits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_team', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['team_id']);
            $table->dropForeign(['deposit_id']);

            // Drop the new unique constraint
            $table->dropUnique(['team_id', 'deposit_id', 'role']);

            // Re-add the original unique constraint
            $table->unique(['team_id', 'deposit_id']);

            // Re-add foreign key constraints
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('deposit_id')->references('id')->on('deposits')->onDelete('cascade');
        });
    }
};
