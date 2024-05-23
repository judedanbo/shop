<?php

namespace App\Models;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waste extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class)->latest();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->prices->first()->price ?? 'not set';
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('type')
                ->label('Name')
                ->required()
                ->columnSpanFull()
                ->maxLength(100),
            Textarea::make('description')
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }
}
