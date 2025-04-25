<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotels extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'nit',
        'total_rooms',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Rooms::class, 'hotel_id');
    }

}
