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
            $table->unsignedBigInteger('user_id')->after('post_type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status',[0,1])->default(0)->comment('0 = inactive, 1 = active')->change();
            $table->enum('post_type',[1,2,3,4])->default(1)->comment('1 = technical, 2 = medical, 3 = descriptive, 4 = informational')->change();
            $table->index(['post_type','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropIndex(['post_type','user_id']);
        });
    }
};
