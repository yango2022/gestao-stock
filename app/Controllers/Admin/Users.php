<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;


class Users extends BaseController
{
    protected $provider;
    protected $groups;
    protected $permissions;


    public function __construct()
    {
        $this->provider     = auth()->getProvider();
        $this->groups       = auth()->getProvider('groups');
        $this->permissions  = auth()->getProvider('permissions');
    }

    /* =====================================================
     * LISTAGEM DE USUÁRIOS (APENAS DA EMPRESA)
     * ===================================================== */
    public function index()
    {
        $loggedUser = auth()->user();

        $users = $this->provider
            ->where('company_id', $loggedUser->company_id)
            ->withIdentities()
            ->withGroups()
            ->findAll(20);

        return view('admin/users/index', [
            'users'  => $users,
            'groups' => $this->groups->findAll(),
            'user'   => $loggedUser
        ]);
    }

    /* =====================================================
     * CRIAR USUÁRIO (MESMA EMPRESA)
     * ===================================================== */
    public function store()
    {
        $loggedUser = auth()->user();

        $data = $this->request->getPost();

        $user = new User([
            'username'   => $data['username'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'company_id' => $loggedUser->company_id
        ]);

        $this->provider->save($user);

        $user = $this->provider->findById($this->provider->getInsertID());

        // Grupo
        if (! empty($data['group'])) {
            $user->addGroup($data['group']);
        }

        // Permissões extras
        if (! empty($data['permissions'])) {
            foreach ($data['permissions'] as $perm) {
                $this->permissions->addPermissionToUser($user->id, $perm);
            }
        }

        return redirect()->to('usuarios')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /* =====================================================
     * OBTER USUÁRIO (AJAX / MODAL)
     * ===================================================== */
    public function get($id)
    {
        $loggedUser = auth()->user();

        $user = $this->provider
            ->where('id', $id)
            ->where('company_id', $loggedUser->company_id)
            ->first();

        if (! $user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Usuário não encontrado'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'user'   => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'groups'   => $user->getGroups(),
            ]
        ]);
    }


    /* =====================================================
     * ATUALIZAR USUÁRIO (MESMA EMPRESA)
     * ===================================================== */
    public function update($id)
    {
        $loggedUser = auth()->user();
        $data       = $this->request->getPost();

        $user = $this->provider
            ->where('id', $id)
            ->where('company_id', $loggedUser->company_id)
            ->first();

        if (! $user) {
            return redirect()->back()
                ->with('error', 'Usuário não encontrado ou acesso negado.');
        }

        $updateData = [
            'username' => $data['username'],
            'email'    => $data['email'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->fill($updateData);
        $this->provider->save($user);

        // 🔄 Atualizar grupo
        foreach ($user->getGroups() as $group) {
            $user->removeGroup($group);
        }

        if (! empty($data['group'])) {
            $user->addGroup($data['group']);
        }

        return redirect()->to('usuarios')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /* =====================================================
     * ELIMINAR USUÁRIO (MESMA EMPRESA)
     * ===================================================== */
    public function delete($id)
    {
        $loggedUser = auth()->user();

        // Não permitir apagar a si próprio
        if ($loggedUser->id == $id) {
            return redirect()->back()
                ->with('error', 'Não pode eliminar o seu próprio utilizador.');
        }

        $user = $this->provider
            ->where('id', $id)
            ->where('company_id', $loggedUser->company_id)
            ->first();

        if (! $user) {
            return redirect()->back()
                ->with('error', 'Usuário não encontrado ou acesso negado.');
        }

        $this->provider->delete($user->id);

        return redirect()->to('usuarios')
            ->with('success', 'Usuário eliminado com sucesso!');
    }

    /* =====================================================
     * LISTAGEM AJAX (DATATABLE)
     * ===================================================== */
    public function listAjax()
    {
        $loggedUser = auth()->user();

        $users = $this->provider
            ->where('company_id', $loggedUser->company_id)
            ->withGroups()
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $users
        ]);
    }
}