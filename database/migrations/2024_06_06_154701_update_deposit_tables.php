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
        // Update the deposits table
        Schema::table('deposits', function (Blueprint $table) {
            // Rename the 'count' column to 'countP'
            $table->renameColumn('count', 'countP');
            // Add the 'countC' column
            $table->unsignedInteger('countC')->default(0);
        });

        // Update the deposit_team table
        Schema::table('deposit_team', function (Blueprint $table) {
            // Add the 'count' column
            $table->unsignedInteger('count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the changes in the deposits table
        Schema::table('deposits', function (Blueprint $table) {
            // Remove the 'countC' column
            $table->dropColumn('countC');
            // Rename the 'countP' column back to 'count'
            $table->renameColumn('countP', 'count');
        });

        // Revert the changes in the deposit_team table
        Schema::table('deposit_team', function (Blueprint $table) {
            // Drop the 'count' column
            $table->dropColumn('count');
        });
    }
};
