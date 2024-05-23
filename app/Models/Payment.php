<?php

namespace App\Models;

use App\Enums\PaymentTypesEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'date',
        'payment_method',
        'phone',
        'transaction_id',
        'comments',
        'order_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'order_id' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function getForm($orderID = null, $amount = null): array
    {
        return [
            Select::make('order_id')
                ->relationship('order', 'id')
                ->native(false)
                ->hidden(function () use ($orderID) {
                    return $orderID !== null;
                })
                ->required(),
            DatePicker::make('date')
                ->native(false)
                ->default(now())
                ->required(),
            TextInput::make('amount')
                ->required()
                ->default($amount)
                ->minValue(0.01)
                ->maxValue($amount ?? 10000)
                ->numeric(),

            Select::make('payment_method')
                ->options(PaymentTypesEnum::class),
            TextInput::make('phone')
                ->tel()
                ->required()
                ->maxLength(15),
            TextInput::make('transaction_id')
                ->required()
                ->maxLength(15),
            Textarea::make('comments')
                ->columnSpan(2)
                ->maxLength(255),
        ];
    }
}
