<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class ReportsRepoTest extends TestCase
{
    private $prophet;

    public function testGetReportOne()
    {
        $data = [
            (object) [
                'WK'=>29,
                'Xper' => 35,
                'count' => 1,
                'TOTAL' => 47
            ]
        ];

        $expected = [
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
            "series" => [
                (object)[
                    'data' => [
                        [0,100],
                        [35,98]
                    ],
                    'name' => "WEEK 29",
                    'total' => 47
                ]
            ]
        ];

        DB::shouldReceive('select')
            ->once()
            ->andReturn($data);

        $repo = new \App\Repositories\ReportRepository();

        $this->assertEquals($expected, $repo->getReportOne());
    }

    private function testGetDataSet(){

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


        return $dataSet;
    }

    /***
     * Check the Y percentage value always has a dropping value
     *
     * @return array
     */
    public  function  testCalculateYPercentage()
    {

        $data = $this->testGetDataSet();

        foreach ($data as $key=>$val) {

            $dropping = 100;

            foreach($val["data"] as $ele){

                $this->assertTrue($dropping >= $ele[1], sprintf("Invalid data. Dropping %d >= Y percentage %d", $dropping, $ele[1]));
                $dropping = $ele[1];

            }
        }

        return $data;
    }


}
