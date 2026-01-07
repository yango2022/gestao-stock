<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
    public function index()
    {
        // Já autenticado? redireciona
        if (auth()->loggedIn()) {
            return $this->redirectAfterLogin();
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $credentials = $this->request->getPost([
            'email',
            'password',
        ]);

        $remember = (bool) $this->request->getPost('remember');

        $result = auth()->attempt($credentials, $remember);

        if (! $result->isOK()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $result->reason());
        }

        // 🔑 Login OK
        return $this->redirectAfterLogin();
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to('/login');
    }

    /**
     * 🎯 Redirecionamento inteligente após login
     */
    protected function redirectAfterLogin(): ResponseInterface
    {
        $user = auth()->user();

        // 🟣 MASTER
        if ($user->inGroup('superadmin')) {
            return redirect()->to('/master/dashboard');
        }

        // 🔵 Usuário normal sem empresa (evita crash)
        if (empty($user->company_id)) {
            auth()->logout();

            return redirect()
                ->to('/login')
                ->with('error', 'Conta sem empresa associada.');
        }

        // 🟢 Usuários normais
        return redirect()->to('/dashboard');
    }
}