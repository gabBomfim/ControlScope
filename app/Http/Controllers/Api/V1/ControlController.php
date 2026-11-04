<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateControlStatusRequest;
use App\Http\Resources\ControlResource;
use App\Models\Control;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ControlController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $controls = Control::query()
            ->when(request('function'), fn ($query, $function) => $query->where('function', $function))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('code')->get();

        return ControlResource::collection($controls);
    }

    public function updateStatus(UpdateControlStatusRequest $request, Control $control): ControlResource
    {
        $control->update($request->validated());

        return new ControlResource($control->refresh());
    }
}
