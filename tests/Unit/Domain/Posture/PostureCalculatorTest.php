<?php

namespace Tests\Unit\Domain\Posture;

use App\Domain\Posture\PostureCalculator;
use App\Domain\Posture\Scoring\IllustrativeScoringStrategy;
use App\Enums\ControlStatus;
use App\Models\Control;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostureCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_the_average_illustrative_score(): void
    {
        $attributes = [
            'function' => 'Protect',
            'category' => 'Demo',
            'title' => 'Test control',
            'description' => 'Test description',
            'recommendation' => 'Test recommendation',
        ];

        Control::create($attributes + ['code' => 'T-1', 'status' => ControlStatus::Implemented]);
        Control::create($attributes + ['code' => 'T-2', 'status' => ControlStatus::Partial]);
        Control::create($attributes + ['code' => 'T-3', 'status' => ControlStatus::NotStarted]);

        $summary = (new PostureCalculator(new IllustrativeScoringStrategy))->summarize();

        $this->assertSame(50, $summary['score']);
        $this->assertSame(3, $summary['evaluated_controls']);
    }
}
