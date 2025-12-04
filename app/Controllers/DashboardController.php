<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;

class DashboardController extends BaseController
{
    public function __construct()
    {
        helper('auth');
    }

    /**
     * Página inicial do sistema após login.
     */
    public function index()
    {
        // Impede acesso de utilizadores não autenticados
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        // Obter utilizador autenticado
        $user = auth()->user();

        // Verificar se pertence ao grupo admin
        /* (Shield: método ->inGroup('admin'))
        if (!$user->inGroup('admin')) {
            return redirect()->to('/acesso-negado');
        } */

        // Aqui podes futuramente enviar dados reais ao dashboard
        $data = [
            'title' => 'Dashboard',
            'user'  => $user,
        ];

        return view('dashboard', $data);
    }
}