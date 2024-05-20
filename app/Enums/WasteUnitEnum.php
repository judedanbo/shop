<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WasteUnitEnum: string implements HasLabel
{
  case POUND = 'pound';

  public function getLabel(): ?string
  {
    return match ($this) {
      self::POUND => 'Per pound',
    };
  }
}
