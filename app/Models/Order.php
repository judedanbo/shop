<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'client_id',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'client_id' => 'integer',
        'status' => OrderStatusEnum::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum('total_cost');
    }
    // public function getItemsCountAttribute(): float
    // {
    //     return $this->orderItems->count();
    // }
    // public function getItemsAttribute(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Waste::class, OrderItem::class,);
    // }

    public static function getForm(): array
    {
        return [
            DatePicker::make('date')
                ->default(now())
                ->native(false)
                ->required(),
            Select::make('client_id')
                ->relationship('client', 'full_name')
                ->editOptionForm(Client::getForm())
                ->createOptionForm(Client::getForm())
                ->preload()
                ->searchable()
                ->native(false)
                ->required(),
            Repeater::make('items')
                ->collapsible()
                ->defaultItems(0)
                ->columnSpanFull()
                ->relationship()
                ->schema(OrderItem::getForm(null))
                ->itemLabel(function (array $state): ?string {
                    return Waste::find($state['waste_id'])->type ?? null;
                }),
            // Repeater::make('payment')
            //     ->collapsible()
            //     ->defaultItems(1)
            //     ->columnSpanFull()
            //     ->relationship()
            //     ->addActionLabel('Pay')
            //     ->addable(false)
            //     ->deletable(false)
            //     ->schema(Payment::getForm(null))
            //     ->itemLabel(fn (array $state): ?string => $state['amount'] ?? null),
        ];
    }
}
