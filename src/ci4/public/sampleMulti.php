<?php

/**
 * FiberとSwooleを使った並列処理
 * 
 */

use Swoole\Coroutine\Http\Client;

function multiProccessBySwoole()
{
    $fibers = [];
    $results = [];

    // APIのURLリスト
    $urls = [
        'https://api.example.com/1',
        'https://api.example.com/2',
        'https://api.example.com/3'
    ];

    // Fiberを作成して、APIリクエストを実行する
    foreach ($urls as $url) {
        $fiber = new Fiber('requestApi', $url, $results);
        $fibers[] = $fiber;
        $fiber->start();
    }

    // 全てのFiberが完了するまで待機する
    foreach ($fibers as $fiber) {
        $fiber->join();
    }

    // 結果を表示する
    print_r($results);
}

// FiberでHTTPリクエストを実行する関数
function requestApi($url, &$results)
{
    $client = new Client($url);
    $client->get('/');
    $response = $client->body;
    $client->close();
    $results[] = $response;
}


// --------------------------------------------
function multiProccessByStream()
{
    // 実行するPHPファイルのパス
    $php_file_paths = [
        "/path/to/your/file1.php",
        "/path/to/your/file2.php",
        "/path/to/your/file3.php"
    ];

    // コマンドライン引数
    $arg1 = "arg1";
    $arg2 = "arg2";

    // パイプを作成する
    $processes = [];
    $pipes = [];

    foreach ($php_file_paths as $key => $php_file_path) {
        // コマンドを作成する
        $command = "php $php_file_path $arg1 $arg2";

        // パイプを作成する
        $descriptorspec = [
            0 => ["pipe", "r"], // 標準入力
            1 => ["pipe", "w"], // 標準出力
            2 => ["pipe", "w"]  // 標準エラー出力
        ];

        // プロセスを実行する
        $process = proc_open($command, $descriptorspec, $pipes[$key]);

        if (is_resource($process)) {
            // プロセスの標準出力を監視する
            $processes[] = $process;
        }
    }

    $read = $pipes;
    $write = $except = null;
    $timeout = 0;

    while (true) {
        $num_streams = stream_select($read, $write, $except, $timeout);

        if ($num_streams === false) {
            // エラーが発生した場合、プロセスを終了する
            foreach ($processes as $process) {
                proc_terminate($process, 9);
            }

            break;
        } else if ($num_streams === 0) {
            // タイムアウトした場合、何もしない
            continue;
        } else {
            // 標準出力からデータを読み込む
            foreach ($read as $key => $stream) {
                $output = fread($stream, 8192);
                if (strlen($output) === 0) {
                    // プロセスが終了した場合、ループを抜ける
                    unset($processes[$key]);
                    unset($read[$key]);
                    continue;
                } else {
                    // データを画面に表示する
                    echo $output;
                }
            }
        }

        if (count($processes) === 0) {
            // 全てのプロセスが終了した場合、ループを抜ける
            break;
        }
    }

    // パイプを閉じる
    foreach ($pipes as $pipe) {
        fclose($pipe[0]);
        fclose($pipe[1]);
        fclose($pipe[2]);
    }

    // プロセスを閉じる
    foreach ($processes as $process) {
        proc_terminate($process, 9);
    }
}
