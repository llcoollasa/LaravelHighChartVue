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
                'Yper' => 2
            ],
            (object) [
                'WK'=>29,
                'Xper' => 40,
                'Yper' => 57
            ],
            (object) [
                'WK'=>29,
                'Xper' => 45,
                'Yper' => 2
            ],
            (object) [
                'WK'=>29,
                'Xper' => 50,
                'Yper' => 2
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
                        [35,2],
                        [40,57],
                        [45,2],
                        [50,2]
                    ],
                    'name' => "WEEK 29"
                ]
            ]
        ];

        DB::shouldReceive('select')
            ->once()
            ->andReturn($data);

        $repo = new \App\Repositories\ReportRepository();

        $this->assertEquals($expected, $repo->getReportOne());
    }
}
