<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'weight',
        'origin',
        'source',
        'order_id',
        'waste_id',
        'price_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'waste_id' => 'integer',
        'price_id' => 'integer',
    ];

    protected $appends = [
        'current_price',
        'total_cost',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function waste(): BelongsTo
    {
        return $this->belongsTo(Waste::class);
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class)->latest();
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->price->first()->price;
    }

    public function getTotalCostAttribute(): float
    {
        return $this->weight * $this->current_price;
    }

    public static function getForm($orderId = null): array
    {
        return [
            Select::make('order_id')
                ->relationship('order', 'id')
                ->default($orderId)
                ->native(false)
                // ->hidden(function () use ($orderId) {
                //     return $orderId !== null;
                // })
                ->hidden(fn () => $orderId === null)
                ->required(),
            Select::make('waste_id')
                ->relationship('waste', 'type')
                ->createOptionForm(Waste::getForm())
                ->editOptionForm(Waste::getForm())
                ->preload()
                ->searchable()
                ->live()
                ->required(),
            TextInput::make('origin')
                ->maxLength(255),
            TextInput::make('source')
                ->maxLength(255),
            Group::make()
                ->columns(2)
                ->schema([
                    TextInput::make('weight')
                        ->required()
                        ->numeric(),
                    Select::make('price_id')
                        ->relationship('price', 'price')
                        // ->disabled()
                        ->required(),
                ]),
        ];
    }
}
