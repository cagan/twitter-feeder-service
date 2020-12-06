<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ActivationCode
 *
 * @property int $id
 * @property int $user_id
 * @property string $activation_code
 * @property int $is_activated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode whereActivationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode whereIsActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivationCode whereUserId($value)
 * @mixin \Eloquent
 */
class ActivationCode extends Model
{

    protected $table = 'users_activation_codes';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
