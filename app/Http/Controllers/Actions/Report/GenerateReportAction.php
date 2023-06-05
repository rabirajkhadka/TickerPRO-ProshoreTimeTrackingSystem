<?php

namespace App\Http\Controllers\Actions\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimelogReportRequest;
use Illuminate\Http\Request;

class GenerateReportAction extends Controller
{
    /**
     * @param TimelogReportRequest $request
     */
    public function __invoke(TimelogReportRequest $request)
    {
        //
    }
}
