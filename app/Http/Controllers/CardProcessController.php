<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class CardProcessController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json();
    }
}
