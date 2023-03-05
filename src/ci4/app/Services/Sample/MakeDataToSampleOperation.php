<?php

namespace App\Services\Sample;

use App\Models\SampleOperation;
use Exception;

/**
 * sample_operationテーブルへのデータ登録など行うサービスクラス
 * 
 */

class MakeDataToSampleOperation
{
    private $pkey;
    private $model;

    public function __construct()
    {
        $this->model = new SampleOperation();
    }

    /**
     * 登録した情報を取得する
     */
    public function findData(int $id):array
    {
        $data = $this->model->find($id);
        if (empty($data)) {
            return ['data' => []];
        }
        return [
            'abstract' => $data['abstract'],
            'data' => json_decode($data['body'], true)
        ];
    }

    /**
     * 登録したいデータ
     * @param array $data
     */
    public function saveData(array $data)
    {
        $abstract = $this->generateRandomKey();
        if (empty($this->pkey)) {
            $this->model->save(['abstract' => $abstract, 'body' => json_encode($data)]);
            $id = $this->model->getInsertID();
            if ($id != false) {
                $this->pkey = $id;
                return true;
            } else {
                throw new Exception('Insert Data Failed');
            }
        }
        $this->model->save(['id' => $this->pkey, 'abstract' => $abstract, 'body' => json_encode($data)]);
    }

    private function generateRandomKey()
    {
        return substr(str_shuffle('HIJKLMNOP'), 0, 3) . rand(1, 999) . date('-YmdHis');
    }
}
