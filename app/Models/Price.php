<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price',
        'waste_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'waste_id' => 'integer',
    ];

    public function waste(): BelongsTo
    {
        return $this->belongsTo(Waste::class);
    }
}
