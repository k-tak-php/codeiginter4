<?php

namespace App\Services\Sample;

use App\Services\Sample\MakeDataToSampleOperation;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

/**
 * 複数APIリクエストクラス
 * 
 */
class MultiRequestApiManager
{
    private $dbService;
    private $data = [];

    public function __construct()
    {
        $this->dbService = new MakeDataToSampleOperation();
    }

    public function requestApi(array $urls)
    {
        $this->asyncOperationWithGuzzle($urls);
        return true;
    }

    public function getSavedData(int $id):array
    {
        return $this->dbService->findData($id);
    }

    private function asyncOperationWithGuzzle(array $urls)
    {
        $client = new Client();
        $requests = function ($urls){
            foreach($urls as $key => $url){
                yield new Request('GET',$url);
            }
        };

        $pool = new Pool($client, $requests($urls), [
            'concurrency' => 5,
            // fulfilledでコールされる関数の第二引数$indexは$requestsのindexに該当するようだ
            'fulfilled'   => function (Response $response, $index){
                // レスポンスを受け取れたら行う処理
                $data = [];
                $data["responseinfo"] = $index;
                $data["body"] = json_decode($response->getBody()->getContents(), true);
                $this->data[] = $data;
                /** @todo データの加工を */

                // データベースに登録する処理を実行
                $this->dbService->saveData($this->data);
            },
            'rejected' => function (RequestException $reason, $index) {
                // リクエスト失敗したときに実行する
                throw new Exception('API ERROR');
            },
        ]);

        // 全ての非同期リクエストが完了するまで待機
        $promise = $pool->promise();
        $promise->wait();
    }
}
