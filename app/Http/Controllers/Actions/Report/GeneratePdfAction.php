<?php

namespace App\Http\Controllers\Actions\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimelogReportRequest;
use App\Services\ReportService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class GeneratePdfAction extends Controller
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
     *
     * @param TimelogReportRequest $request
     * @throws Exception
     */
    public function __invoke(TimelogReportRequest $request)
    {
        try {
            $validated = $request->validated();
            // $validated['user_id'] = explode(",",$validated['user_id']);
            $report = $this->reportService->getUsersReport($validated);
            return $this->reportService->generatePdfReport($report, $validated['start_date'], $validated['end_date']);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something went wrong.");
        }
    }
}
