<?php

namespace App\Http\Controllers;

use App\Reports;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\ReportInterface;

class ReportsController extends Controller
{

    protected $resource;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ReportInterface $report)
    {
        $this->middleware('auth');
        $this->resource = $report;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.chart');

    }

    public function report()
    {
        $result = $this->resource->getReportOne();
        return response()->json($result);

    }
}
