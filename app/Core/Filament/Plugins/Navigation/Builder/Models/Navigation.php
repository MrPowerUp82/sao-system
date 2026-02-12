<?php

namespace App\Core\Filament\Plugins\Navigation\Builder\Models;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $handle
 * @property array $items
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Navigation extends AbstractModel
{
    use HasFactory;


    protected $guarded = ['id'];

    protected $casts = [
        'items' => 'json',
    ];
 

    public static function fromHandle(string $handle): ?static
    {
        return static::query()->firstWhere('handle', $handle);
    }
 
}
