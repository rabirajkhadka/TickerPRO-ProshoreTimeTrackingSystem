<?php

namespace App\Http\Controllers\Actions\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimelogReportRequest;
use App\Services\ReportService;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
     *
     * @param TimelogReportRequest $request
     * @return JsonResponse
     */
    public function __invoke(TimelogReportRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $report = $this->reportService->getUsersReport($validated);

            // dd($report);
            $start_date = Carbon::parse($validated['start_date'])->toFormattedDateString();
            $end_date = Carbon::parse($validated['end_date'])->toFormattedDateString();

            // Generate HTML for the report view
            $html = view('reports.reportPdf', compact(['report', 'start_date', 'end_date']))->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->setChroot(public_path());
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();

            // $filename = 'report_' . Carbon::now()->format('YmdHis') . '.pdf';
            // $dompdf->stream($filename);
            
            return $this->successResponse([$report], 'Report successfully retrieved');
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User does not exist.", Response::HTTP_NOT_FOUND);
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], "Could not generate Report", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something went wrong.");
        }
    }
}
