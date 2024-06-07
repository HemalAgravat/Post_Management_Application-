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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid_column')->nullable()->after('name');
            $table->softDeletes();
            $table->string('name', 50)->change();
            $table->string('email', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid_column');
            $table->dropSoftDeletes();
            $table->string('name', 50)->change();
            $table->string('email', 50)->change();
        });
    }
};
