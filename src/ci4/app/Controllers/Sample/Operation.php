<?php

namespace App\Controllers\Sample;

use App\Controllers\BaseController;
use App\Services\Sample\MultiRequestApiManager;
use CodeIgniter\API\ResponseTrait;

class Operation extends BaseController
{
    use ResponseTrait;
    private $service;

    public function __construct()
    {
        $this->service = new MultiRequestApiManager();
    }

    public function list($id)
    {
        $data = $this->service->getSavedData($id);
        return $this->respond($data, 200);
    }
}
