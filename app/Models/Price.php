<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\WasteUnitEnum;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
        'unit',
    ];

    protected $casts = [
        'id' => 'integer',
        'waste_id' => 'integer',
        'unit' => WasteUnitEnum::class,
        // 'price' => Money::class,
    ];

    public function waste(): BelongsTo
    {
        return $this->belongsTo(Waste::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Waste Information')

                ->aside()
                ->schema([
                    Select::make('waste_id')
                        ->relationship('waste', 'type')
                        ->editOptionForm(Waste::getForm())
                        ->createOptionForm(Waste::getForm())
                        ->preload()
                        ->searchable()
                        ->required(),
                ]),
            Section::make('Price Information')
                ->columns([
                    'xl' => 2,
                ])
                ->aside()
                ->schema([
                    Select::make('unit')
                        ->enum(WasteUnitEnum::class)
                        ->options(WasteUnitEnum::class)
                        ->native(false)
                        ->label('Unit of Sale')
                        ->default(WasteUnitEnum::POUND),
                    TextInput::make('price')
                        ->required()
                        ->maxLength(255)
                        ->suffix('GHS'),
                ]),
        ];
    }
}
