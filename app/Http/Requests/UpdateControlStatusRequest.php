<?php

namespace App\Http\Requests;

use App\Enums\ControlStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateControlStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['status' => ['required', new Enum(ControlStatus::class)]];
    }
}
