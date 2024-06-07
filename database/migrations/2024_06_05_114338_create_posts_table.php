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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('description');
            $table->json('images');
            $table->enum('post_type', [1,2,3,4])->default(1)->comment('1 = technical, 2 = medical, 3 = descriptive, 4 = informational');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('uuid_column')->nullable();
            $table->enum('status',[0,1])->default(0)->comment('0 = inactive, 1 = active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('post_type');
            $table->index('user_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
