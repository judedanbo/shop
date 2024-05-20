<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'other_names',
        'last_name',
        'phone',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('first_name')
                ->required()
                ->maxLength(100),
            TextInput::make('other_names')
                ->maxLength(100),
            TextInput::make('last_name')
                ->required()
                ->columnSpanFull()
                ->maxLength(100),
            TextInput::make('phone')
                ->tel()
                ->required()
                ->columnStart(1)
                ->maxLength(15),
        ];
    }
}
