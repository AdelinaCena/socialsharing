<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'post_id'
    ];


     /**
     * Get the post that owns the media.
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }
        
}
