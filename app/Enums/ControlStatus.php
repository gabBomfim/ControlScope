<?php

namespace App\Enums;

enum ControlStatus: string
{
    case NotStarted = 'not_started';
    case Partial = 'partial';
    case Implemented = 'implemented';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Not started',
            self::Partial => 'Partially implemented',
            self::Implemented => 'Implemented',
        };
    }

    public function score(): int
    {
        return match ($this) {
            self::NotStarted => 0,
            self::Partial => 50,
            self::Implemented => 100,
        };
    }
}
