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
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(GuestBookAnswer::class);
    }
}
