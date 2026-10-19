<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = array('title', 'content', 'status', 'user_id', 'category_id');

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public $timestamps = true;
}