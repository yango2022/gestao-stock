<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class CategoryController extends Controller
{
    protected $category;

    public function __construct()
    {
        $this->category = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->category->orderBy('id', 'DESC')->findAll();
        //Obter utilizador autenticado
        $user = auth()->user();
        $users = auth()->getProvider();
        $usersList = $users
            ->withIdentities()
            ->withGroups()
            ->withPermissions()
            ->findAll(20);
        

        return view('categories/index', [
            'users'         =>  $usersList,
            'user'          => $user,
            'categories'    => $categories,
        ]);
    }

    public function store()
    {
        $post = $this->request->getPost();

        $this->category->save([
            'name'        => $post['name'],
            'description' => $post['description']
        ]);

        return redirect()->back()->with('success', 'Categoria adicionada com sucesso!');
    }

    public function get($id)
    {
        $category = $this->category->find($id);

        if (!$category) {
            return $this->response->setJSON(['status' => 'error']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'category' => $category
        ]);
    }

    public function update($id)
    {
        $post = $this->request->getPost();

        $this->category->update($id, [
            'name'        => $post['name'],
            'description' => $post['description']
        ]);

        return redirect()->back()->with('success', 'Categoria atualizada!');
    }

    public function delete($id)
    {
        $this->category->delete($id);

        return redirect()->back()->with('success', 'Categoria removida!');
    }
}