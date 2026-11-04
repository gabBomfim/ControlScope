<?php

namespace Tests\Feature\Api;

use App\Enums\ControlStatus;
use App\Models\Control;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostureApiTest extends TestCase
{
    use RefreshDatabase;

    private function control(string $code, string $function, ControlStatus $status): Control
    {
        return Control::create([
            'code' => $code,
            'function' => $function,
            'category' => 'Demo',
            'title' => 'Test control',
            'description' => 'Test description',
            'recommendation' => 'Test recommendation',
            'status' => $status,
        ]);
    }

    public function test_controls_can_be_filtered_by_function_and_status(): void
    {
        $this->control('T-1', 'Protect', ControlStatus::Partial);
        $this->control('T-2', 'Detect', ControlStatus::Implemented);

        $this->getJson('/api/v1/controls?function=Protect&status=partial')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.function', 'Protect');
    }

    public function test_control_status_update_is_validated_and_persisted(): void
    {
        $control = $this->control('T-1', 'Protect', ControlStatus::NotStarted);

        $this->patchJson('/api/v1/controls/'.$control->id.'/status', ['status' => 'implemented'])
            ->assertOk()->assertJsonPath('data.status', 'implemented');

        $this->assertDatabaseHas('controls', ['id' => $control->id, 'status' => 'implemented']);
    }

    public function test_posture_endpoint_returns_the_scoring_model(): void
    {
        $this->control('T-1', 'Protect', ControlStatus::Implemented);

        $this->getJson('/api/v1/posture')
            ->assertOk()->assertJsonPath('score', 100)
            ->assertJsonPath('evaluated_controls', 1);
    }
}
