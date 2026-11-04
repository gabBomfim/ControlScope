<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Posture\PostureCalculator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PostureController extends Controller
{
    public function __construct(private readonly PostureCalculator $calculator) {}

    public function show(): JsonResponse
    {
        return response()->json($this->calculator->summarize());
    }
}
