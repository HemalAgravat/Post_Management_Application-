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
        Schema::table('posts', function (Blueprint $table) {
            $table->uuid('uuid_column')->nullable()->after('user_id');
            $table->string('title', 100)->change();
            $table->softDeletes();
            $table->dropIndex(['post_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('uuid_column');
            $table->dropSoftDeletes();
            $table->index(['post_type', 'user_id']);
        });
    }
};
