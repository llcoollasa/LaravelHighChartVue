<?php

namespace Tests\Feature;

use App\Reports;
use App\Repositories\ReportRepository;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function testReportTest()
    {
        $result = DB::select('
                    SELECT
                        A.WK,
                        A.Xper,
                        ROUND((A.count / B.count) * 100) Yper
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

        //"data" => [[0,100],[40,50],[99,25],[100,25]]
        $week =0;
        foreach($result as $record){

            if($week != $record->WK){
                $week = $record->WK;
                $dataSet[$record->WK]['data'][]=[0, 100];
            }

            $dataSet[$record->WK]['name']= 'WEEK '.$record->WK;
            $dataSet[$record->WK]['data'][]=[$record->Xper, $record->Yper];
        }

        dd($dataSet);

    }


    public function testWeeklyReportTest(){
        $series = [];
        $result = $this->testReportTest();

        /*

        [
            "name" =>  'Installation',
            "data" => [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        ]

         */


        foreach($result as $record){
            array_push($series, $record);
        }


        dd(str_replace('"','\'', json_encode($series, JSON_NUMERIC_CHECK)));

    }
}
