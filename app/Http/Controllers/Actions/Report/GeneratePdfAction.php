<?php

namespace App\Http\Controllers\Actions\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimelogReportRequest;
use App\Services\{ReportService, UserService};
use Exception;
use Illuminate\Support\Facades\Log;

class GeneratePdfAction extends Controller
{
    protected ReportService $reportService;
    protected UserService $userService;

    /**
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService, UserService $userService)
    {
        $this->reportService = $reportService;
        $this->userService = $userService;
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
            $user = auth()->user();
            if (!$this->userService->hasRoleAdmin($user)) {
                $validated['user_ids'] = [auth()->id()];
            }
            $report = $this->reportService->getUsersReport($validated);
            return $this->reportService->generatePdfReport($report, $validated['start_date'], $validated['end_date']);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something went wrong.");
        }
    }
}
