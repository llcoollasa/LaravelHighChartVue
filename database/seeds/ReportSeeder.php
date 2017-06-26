<?php

use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = base_path('database' . DIRECTORY_SEPARATOR . 'seeds' . DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR . 'export.csv');
        $result   = $this->importCSV($filePath);

        if ($result) {

            foreach($result as $key=>$value){

                DB::table('reports')->insert([
                    "user_id" => $value['user_id'],
                    "created_at" => $value['created_at'],
                    "onboarding_percentage" => $value['onboarding_percentage'],
                    "count_applications" => $value['count_applications'],
                    "count_accepted_applications" => $value['count_accepted_applications']
                ]);
            }

        }

    }

    public function importCSV($filePath)
    {
        if (file_exists($filePath) || is_readable($filePath)) {
            $handle    = fopen($filePath, "r");
            $arrayData = [];
            $num       = 0;
            while (($data = fgetcsv($handle, 10000, ';')) !== false) {



                if (!$num == 0) {
                    $arrayData[] = [
                        "user_id" => $data[0],
                        "created_at" => trim($data[1]),
                        "onboarding_percentage" => empty(trim($data[2])) ? 0 : $data[2],
                        "count_applications" => empty(trim($data[3])) ? 0 : $data[3],
                        "count_accepted_applications" => empty(trim($data[4])) ? 0 : $data[4]
                    ];
                }

                $num++;
            }
            fclose($handle);

            return $arrayData;
        } else {
            return false;
        }


    }
}
