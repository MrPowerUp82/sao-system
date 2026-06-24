<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuildMessage extends Model
{
    protected $fillable = [
        'guild_id',
        'user_id',
        'message',
    ];

    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
