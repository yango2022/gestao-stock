<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class MasterFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 🔐 Não logado
        if (! auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        // 🟣 Não é master
        if (! auth()->user()->inGroup('master')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada
    }
}