<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * table
     *
     * @var string
     */
    protected $table = 'posts';
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'images',
        'post_type',
        'status',
        'user_id',
        'uuid_column',
    ];
    
    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array', // Ensure images are cast to an array
        'uuid_column' => 'string',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid_column = Str::uuid(); // Automatically generate UUID
        });
    }

}
