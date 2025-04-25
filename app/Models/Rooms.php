<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rooms extends Model
{
    protected $fillable = [
        'hotel_id',
        'type',
        'accommodation',
        'quantity',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotels::class);
    }

}
