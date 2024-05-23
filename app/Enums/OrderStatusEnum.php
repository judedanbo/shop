<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum OrderStatusEnum: string implements HasLabel, HasColor, HasDescription
{
  case Pending = 'pending';
  case Processing = 'processing';
  case Completed = 'completed';
  case Cancelled = 'cancelled';

  public function getLabel(): string
  {
    return match ($this) {
      self::Pending => 'Pending',
      self::Processing => 'Processing',
      self::Completed => 'Completed',
      self::Cancelled => 'Cancelled',
    };
  }

  public function getDescription(): string
  {
    return match ($this) {
      self::Pending => 'The order is created and saved',
      self::Processing => 'The order is being processed',
      self::Completed => 'The order has been paid for',
      self::Cancelled => 'The order has been cancelled',
    };
  }

  public function getColor(): string | array | null
  {
    return match ($this) {
      self::Pending => Color::Yellow,
      self::Processing => Color::Blue,
      self::Completed => Color::Green,
      self::Cancelled => Color::Red,
    };
  }
}
