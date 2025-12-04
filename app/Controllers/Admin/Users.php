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

        return redirect()->to('admin/users')->with('success', 'Usuário criado com sucesso!');
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

    //Buscar 1 utilizador
    public function get2($id)
    {
        $user = $this->provider->findById($id);

        return $this->response->setJSON([
            'status' => 'success',
            'user'   => $user,
            'groups' => $user->getGroups(),
            'permissions' => $user->getPermissions()
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
        $data = $this->request->getJSON(true);

        $user = $this->provider->findById($id);

        $user->username = $data['username'];
        $user->email    = $data['email'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $this->provider->save($user);

        // Atualizar grupos
        $this->groups->removeUserFromAllGroups($id);
        $this->groups->addUserToGroup($id, $data['group']);

        // Atualizar permissões
        $this->permissions->removeAllPermissionsFromUser($id);
        if (! empty($data['permissions'])) {
            foreach ($data['permissions'] as $p) {
                $this->permissions->addPermissionToUser($id, $p);
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    // Apagar usuário
    public function delete2($id)
    {
        $this->provider->delete($id);

        return $this->response->setJSON(['status' => 'success']);
    }

    /** -------------------------------------------------------------
     * ELIMINAR USUÁRIO
     * --------------------------------------------------------------*/
    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('admin/users')->with('success', 'Usuário eliminado!');
    }
} 