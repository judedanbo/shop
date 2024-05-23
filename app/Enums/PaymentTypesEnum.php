<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentTypesEnum: string implements HasLabel
{
  case MOMO = 'momo';
  case TCASH = 't-cash';
  case ACASH = 'A-cash';

  public function getLabel(): string
  {
    return match ($this) {
      self::MOMO => 'MTN Mobile Money',
      self::TCASH => 'Telecel T-Cash',
      self::ACASH => 'AT Cash',
    };
  }
}
