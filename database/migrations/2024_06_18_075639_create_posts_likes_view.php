<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            CREATE VIEW posts_likes_view AS
            SELECT
                p.id,
                p.title,
                COUNT(l.id) AS like_count
            FROM
                posts p
            LEFT JOIN
                likes l ON p.id = l.post_id
            GROUP BY
                p.id,
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS posts_likes_view");
    }
};