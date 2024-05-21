<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'client_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'client_id' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
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
                ->live()
                ->required(),
            Repeater::make('items')
                ->collapsible()
                ->defaultItems(0)
                ->columnSpanFull()
                ->relationship()
                ->schema(OrderItem::getForm(null))
                ->itemLabel(fn (array $state): ?string => $state['waste_id'] ?? null),
            Repeater::make('payment')
                ->collapsible()
                ->defaultItems(1)
                ->columnSpanFull()
                ->relationship()
                ->addActionLabel('Pay')
                ->addable(false)
                ->deletable(false)
                ->schema(Payment::getForm(null))
                ->itemLabel(fn (array $state): ?string => $state['amount'] ?? null),
        ];
    }
}
