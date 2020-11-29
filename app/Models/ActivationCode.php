<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{

    protected $table = 'users_activation_codes';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
