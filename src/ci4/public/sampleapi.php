<?php

main();

function main(){
    $requested_datetime = date('YmdHis');
    $num = $_GET["num"] ?? "0";
    randomSleep(1,3);
    $reponse_datetime = date('YmdHis');
    $sampleResponse = new SampleResponse();
    $data = [
        "id" => $num, 
        "requested_at" => $requested_datetime,
        "reponsed_at" => $reponse_datetime,
        "data" => []
    ];
    $data["data"] = $sampleResponse->generateRandomData();
    echo json_encode($data);
}

function randomSleep(int $min, int $max){
    sleep(rand($min, $max));
}

class SampleResponse{
    private array $responseData = [];
    public function generateRandomData(){
        foreach ($this->dataGenerator() as $key => $row) {
            $this->responseData[] = $row;
        }
        return $this->responseData;
    }

    private function dataGenerator(){
        // 1 ~ N（最大20）のランダム配列を作成する
        foreach (range(1, rand(1, 10)) as $key => $value) {
            $current = [
                'name' => 'name'.$value,
                'searchKey' => substr(str_shuffle('ABCDEFG'),0,3),
                'child' => []
            ];

            foreach (range(1, rand(1, 5)) as $key => $random) {
                $current['child'][] = [
                    'name' => 'child'.$random,
                    'searchKey' => substr(str_shuffle('HIJKLMNOP'),0,3),
                    'number' => rand(1,100)
                ];
            }

            yield $current;
        }
    }
}