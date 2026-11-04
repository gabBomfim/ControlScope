<?php

namespace App\Domain\Posture\Contracts;

use Illuminate\Support\Collection;

interface ScoringStrategy
{
    public function calculate(Collection $controls): array;
}
