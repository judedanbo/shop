<?php

namespace App\Models;

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
        'transactionId',
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

    public static function getForm(): array
    {
        return [
            Select::make('order_id')
                ->relationship('order', 'id')
                ->native(false)
                ->required(),
            DatePicker::make('date')
                ->native(false)
                ->required(),
            TextInput::make('amount')
                ->required()
                ->numeric(),

            TextInput::make('payment_method')
                ->required()
                ->maxLength(100),
            TextInput::make('phone')
                ->tel()
                ->required()
                ->maxLength(15),
            TextInput::make('transactionId')
                ->required()
                ->maxLength(15),
            Textarea::make('comments')
                ->columnSpan(2)
                ->maxLength(255),
        ];
    }
}
