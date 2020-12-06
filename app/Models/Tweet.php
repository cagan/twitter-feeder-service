<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tweet
 *
 * @property int $id
 * @property int $user_id
 * @property string $twitter_text
 * @property int $likes
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereTwitterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tweet whereUserId($value)
 * @mixin \Eloquent
 */
class Tweet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'twitter_text', 'is_published', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
