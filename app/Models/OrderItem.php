<?php

namespace App\Models;

use Blueprint\Builder;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component as Livewire;
use NunoMaduro\Collision\Adapters\Phpunit\State;

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
            Section::make('Order Item Information')
                ->columns([
                    'lg' => 3,
                ])
                ->schema([
                    Select::make('order_id')
                        ->relationship('order', 'id')
                        ->columnStart(['lg' => 2])
                        ->hidden(fn (Get $get) => $get('order_id') === null)
                        ->native(false)
                        ->required(),
                    Select::make('waste_id')
                        ->relationship('waste', 'type',  modifyQueryUsing: fn ($query) => $query->whereHas('prices'),)
                        ->columnSpan(2)
                        ->createOptionForm(Waste::getForm())
                        ->editOptionForm(Waste::getForm())
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $set('price', Price::where('waste_id', $get('waste_id'))->latest()->first()->price);
                            $set('price_id', Price::where('waste_id', $get('waste_id'))->latest()->first()->id);
                        })
                        ->required(),
                    TextInput::make('origin')
                        ->maxLength(255)
                        ->columnStart(['lg' => 1])
                        ->columnSpanFull(),
                    TextInput::make('source')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('weight')
                        ->numeric()
                        ->inputMode('decimal')
                        ->disabled(fn (Get $get) => !$get('waste_id'))
                        ->live(onBlur: true)
                        ->columnStart(['lg' => 1])
                        ->suffix('pounds')
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            // dd($get);
                            if ($get('weight') && $get('waste_id')) {
                                $set('total_price', $get('weight') * Price::where('waste_id', $get('waste_id'))->first()->price);
                            }
                            // $set('total_price', ($get('weight') * Price::find($get('price_id'))->price) ?? null);
                        })
                        ->minValue(0.01)
                        ->required(),
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('GHS')
                        ->inputMode('decimal')
                        ->minValue(0.01)
                        ->disabled(),

                    Hidden::make('price_id'),

                    TextInput::make('total_price')
                        ->numeric()
                        ->prefix('GHS')
                        ->inputMode('decimal')
                        ->disabled(),
                ]),
        ];
    }
}
