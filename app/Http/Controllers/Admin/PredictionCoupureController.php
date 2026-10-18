<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePredictionCoupureRequest;
use App\Models\PredictionCoupure;
use App\Models\Zone;
use App\Services\HeatAlertMlService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class PredictionCoupureController extends Controller
{
    public function __construct(private HeatAlertMlService $mlService) {}

    public function store(StorePredictionCoupureRequest $request, Zone $zone): JsonResponse
    {
        try {
            $referenceAt = $request->filled('reference_at')
                ? Carbon::parse($request->validated('reference_at'))
                : now();
            $prediction = $this->mlService->predictForZone($zone, $referenceAt);
            $result = $prediction['response'];

            $stored = PredictionCoupure::create([
                'zone_id' => $zone->id,
                'reference_at' => $referenceAt,
                'target_window_start' => $result['target_window_start'],
                'target_window_end' => $result['target_window_end_exclusive'],
                'probability' => $result['probability'],
                'risk_level' => $result['risk_level'],
                'risk_thresholds_defined' => $result['risk_thresholds_defined'],
                'model_version' => $result['model_version'],
                'warnings' => $result['warnings'],
                'input_snapshot' => $prediction['input_snapshot'],
            ]);

            return response()->json([
                'success' => true,
                'prediction' => $stored->load('zone'),
            ], 201);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 503);
        }
    }
}
