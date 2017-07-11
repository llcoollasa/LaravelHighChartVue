<?php
namespace App\Repositories;


use App\Repositories\Interfaces\ReportInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class ReportRepository implements ReportInterface
{
    protected $report;

    function __construct()
    {
        $this->report = [
            "chart" =>[
                "type"=> 'spline'
            ],
            "title" => [
                "text" => 'Temper boarding process insights'
            ],
            "subtitle" => [
                "text" => 'created by: lasanthaindrajith@gmail.com'
            ],
            "yAxis" => [
                "title" => [
                    "text" => 'User as Percentage'
                ]
            ],
            "legend" => [
                "layout" => 'vertical',
                "align" => 'right',
                "verticalAlign" => 'middle'
            ],
            "plotOptions" => [
                "spline" => [
                    'marker' => [
                        "enabled" => true
                    ]
                ]
            ],
            "series" => []
        ];


    }

    public function getReportOne()
    {
        $result = \DB::select('
                                SELECT
                                            A.WK,
                                            A.Xper, A.count ,B.count AS TOTAL
                                        FROM
                                            (SELECT
                                                    WEEK(created_at) AS WK,
                                                    COUNT(*) AS count,
                                                    ROUND(SUM(onboarding_percentage) / COUNT(*)) Xper
                                            FROM
                                                reports
                                            GROUP BY WEEK(created_at), onboarding_percentage) A
                                                LEFT JOIN
                                            (SELECT
                                                WEEK(created_at) AS WK, COUNT(*) AS count
                                            FROM
                                                reports
                                            GROUP BY WEEK(created_at)) B
                                            ON A.WK = B.WK
                                        ORDER BY A.wk , A.Xper');

        $dataSet = [];

        $week =0;
        $remainingCandidates = 0;


        foreach ($result as $record) {

            $total = $record->TOTAL;
            $yPer = 100;
            $dataSet[$record->WK]['name']   = 'WEEK ' . $record->WK;
            $dataSet[$record->WK]['total']  = $total;

            if ($week != $record->WK){

                $week                           = $record->WK;
                $dataSet[$record->WK]['data'][] = [0, $yPer];

            }

            if($remainingCandidates ==0) {

                $remainingCandidates = $total;

            }

            //calculate Y percentage
            $remainingCandidates -= $record->count;

            $yPer =round(($remainingCandidates / $total) * 100);

            $dataSet[$record->WK]['data'][] = [$record->Xper, $yPer];

        }

        return $this->prepareReport($dataSet);
    }

    private function prepareReport($dataSet){

        $series = [];
        $result = $dataSet;

        foreach($result as $record){
            array_push($series, $record);
        }
        $dataSeries =  json_encode($series, JSON_NUMERIC_CHECK);

        $this->report["series"] = json_decode($dataSeries);

        return $this->report;

    }
}
