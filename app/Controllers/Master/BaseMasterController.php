<?php

namespace App\Controllers\Master;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseMasterController extends Controller
{
    protected $helpers = [];

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Apenas variáveis globais
        service('renderer')->setVar('isMaster', true);
        service('renderer')->setVar('company', null);
    }
}
