<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{

    use HasFactory;

    protected $fillable = ['user_id', 'twitter_text', 'is_published', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
