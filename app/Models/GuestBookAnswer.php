<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestBookAnswer extends Model
{
    protected $fillable = [
        'content',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guestBookItem()
    {
        return $this->belongsTo(GuestBookItem::class);
    }
}
