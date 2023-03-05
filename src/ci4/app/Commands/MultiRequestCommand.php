<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\Sample\MultiRequestApiManager;

class MultiRequestCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'CodeIgniter';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'multirequest:save';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];


    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        // http://codeiginter4_php:80/sampleapi.php?num=1
        print_r($params);
        $service = new MultiRequestApiManager();
        $service->requestApi($params);
    }
}
