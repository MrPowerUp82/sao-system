<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

class SqlReport extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'format',
        'sql',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }
}
