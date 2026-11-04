<?php

namespace App\Domain\Posture\Scoring;

use App\Domain\Posture\Contracts\ScoringStrategy;
use Illuminate\Support\Collection;

final class IllustrativeScoringStrategy implements ScoringStrategy
{
    public function calculate(Collection $controls): array
    {
        $total = $controls->count();
        $points = $controls->sum(fn ($control) => $control->status->score());
        $score = $total === 0 ? 0 : (int) round($points / $total);

        $coverage = $controls->groupBy('function')->map(fn (Collection $items) => (int) round(
            $items->avg(fn ($control) => $control->status->score())
        ))->sortKeys()->all();

        $recommendations = $controls
            ->filter(fn ($control) => $control->status->score() < 100)
            ->sortBy(fn ($control) => $control->status->score())
            ->take(4)
            ->map(fn ($control) => [
                'code' => $control->code,
                'title' => $control->title,
                'action' => $control->recommendation,
            ])->values()->all();

        return [
            'score' => $score,
            'coverage' => $coverage,
            'recommendations' => $recommendations,
            'evaluated_controls' => $total,
            'model_note' => 'Illustrative model: not started = 0, partial = 50, implemented = 100. This is not a NIST certification or compliance assessment.',
        ];
    }
}
