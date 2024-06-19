<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments';

    protected $fillable = ['post_id', 'user_id', 'uuid_column', 'content'];

    protected $casts = [
        'uuid_column' => 'string',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid_column = Str::uuid(); // Automatically generate UUID
        });
    }

}
