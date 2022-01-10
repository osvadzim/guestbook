<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestBookItem extends Model
{
    protected $fillable = [
        'content',
    ];


    public function user()
    {
        return $this->hasOne(User::class);
    }
}
