<?php

namespace App\Http\Controllers\Actions\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimelogReportRequest;
use App\Services\ReportService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class GenerateReportAction extends Controller
{
    use HttpResponses;

    protected ReportService $reportService;

    /**
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }


    /**
     * @param TimelogReportRequest $request
     */
    public function __invoke(TimelogReportRequest $request)
    {
        try {
            $validated = $request->validated();
            $report = $this->reportService->getUsersReport($validated);
            return $this->successResponse([$report], 'Report successfully retrieved');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something went wrong.");
        }
    }
}
