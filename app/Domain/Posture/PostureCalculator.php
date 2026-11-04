<?php

namespace App\Domain\Posture;

use App\Domain\Posture\Contracts\ScoringStrategy;
use App\Models\Control;

final class PostureCalculator
{
    public function __construct(private readonly ScoringStrategy $strategy) {}

    public function summarize(): array
    {
        return $this->strategy->calculate(Control::query()->orderBy('code')->get());
    }
}
