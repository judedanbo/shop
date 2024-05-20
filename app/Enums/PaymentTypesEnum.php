<?php

namespace App\Enums;

// use Filament\Support\Contracts\HasLabel;

enum PaymentTypesEnum: string
{
  case MOMO = 'momo';
  case TCASH = 't-cash';
  case ACASH = 'A-cash';

  public static function getLabel(): array
  {
    return [
      self::MOMO => 'MTN Mobile Money',
      self::TCASH => 'Telecel T-Cash',
      self::ACASH => 'Airtel Tigo A-Cash',
    ];
  }
}
