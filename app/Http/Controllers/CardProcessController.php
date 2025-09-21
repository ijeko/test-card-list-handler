<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCardsJob;
use App\Services\ProcessorFactory\ProcessorFactory;
use App\Support\Helpers\FileUuidHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardProcessController extends Controller
{
    public function __construct(private readonly ProcessorFactory $factory)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $processor = $this->factory->create();
        $uuid = FileUuidHelper::storeWithUuid($request->file('file'), $request->header('X-Url'));

        ProcessCardsJob::dispatch($processor, $uuid);

        return response()->json();
    }
}
