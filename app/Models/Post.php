<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'user_id'
        ];

    /**
     * Get the Media for the blog post.
     */
    public function media()
    {
        return $this->hasMany('App\Models\Media');
    }


     /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
