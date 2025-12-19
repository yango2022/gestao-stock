<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Users extends BaseController
{
    protected $provider;
    protected $groups;
    protected $permissions;
    protected $users;
    protected $userModel;

    public function __construct()
    {
        $this->provider     = auth()->getProvider();
        $this->groups       = auth()->getProvider('groups');
        $this->permissions  = auth()->getProvider('permissions');
        $this->users        = new UserModel();
        $this->userModel    = new UserModel();
    }

     /** -------------------------------------------------------------
     * LISTAGEM NORMAL (SEM AJAX)
     * --------------------------------------------------------------*/
    public function index()
    {
        //Obter utilizador autenticado
        $user = auth()->user();
        $users = auth()->getProvider();
        $usersList = $users
            ->withIdentities()
            ->withGroups()
            ->withPermissions()
            ->findAll(20);

        return view('admin/users/index', [
            'groups'        => $this->groups->findAll(),
            'permissions'   => $this->permissions->findAll(),
            'users'        =>  $usersList,
            'user'          => $user
        ]);
    }

     /** -------------------------------------------------------------
     * CRIAR USUÁRIO
     * --------------------------------------------------------------*/
    public function create()
    {
        $data = [
            'username'  => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'group' => $this->request->getPost('group'),
        ];

        $user = new User([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'group'    => $data['group'],
        ]);

        $this->provider->save($user);
        $user = $this->provider->findById($this->provider->getInsertId());

        // Grupo
        if (! empty($data['group'])) {
            $user->addGroup($data['group']);
            //$this->groups->addGroup($user->id, $data['group']);
        }

        return redirect()->to('usuarios')->with('success', 'Usuário criado com sucesso!');
    }

    public function listAjax()
    {

        $users = auth()->getProvider();

        $usersList = $users
            ->withIdentities()
            ->withGroups()
            ->withPermissions()
            ->findAll(10);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $usersList
        ]);
    }

    public function get($id)
    {
        $user = $this->users
            ->where('id', $id)
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Usuário não encontrado'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'user'   => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ]
        ]);
    }

    // Criar usuário
    public function store()
    {
        $data = $this->request->getJSON(true);

        $user = new User([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'group'    => $data['group'],
        ]);

        $this->provider->save($user);
        $user = $this->provider->findById($this->provider->getInsertId());

        // Grupo
        if (! empty($data['group'])) {
            $user->addGroup($data['group']);
            //$this->groups->addGroup($user->id, $data['group']);
        }

        // Permissões extras
        if (! empty($data['permissions'])) {
            foreach ($data['permissions'] as $perm) {
                $this->permissions->addPermissionToUser($user->id, $perm);
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    // Editar usuário
    public function update($id)
    {
        //$data = $this->request->getJSON(true);
        $data = $this->request->getPost();

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'JSON inválido.'
            ]);
        }

        // Provider do Shield
        $users = auth()->getProvider();

        $user = $this->provider->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Usuário não encontrado.'
            ]);
        }

        // Atualizar dados gerais
        $user->fill([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password'      => $data['password'],
        ]);


        // ======================
        // GRUPOS
        // ======================
        $gruposAtuais = $user->getGroups();

        foreach ($gruposAtuais as $g) {
            $user->removeGroup($g);
        }

        $user->addGroup($data['group']);

        $users->save($user);

        $this->response->setJSON(['status' => 'success']);
        return redirect()->to('usuarios')->with('success', 'Usuário editado com sucesso!');
    }

    /** -------------------------------------------------------------
     * ELIMINAR USUÁRIO
     * --------------------------------------------------------------*/
    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('usuarios')->with('success', 'Usuário eliminado!');
    }
} 