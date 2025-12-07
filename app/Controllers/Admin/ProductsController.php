<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Entities\Product;
use App\Models\CategoryModel;

class ProductsController extends BaseController
{
    protected $product;
    protected $categoryModel;

    public function __construct()
    {
        $this->product = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

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
        
        $categories = $this->categoryModel->findAll();

        return view('admin/products/index', [
            'users'         =>  $usersList,
            'user'          => $user,
            'categories'    => $categories,
        ]);
    }

    public function list()
    {
        return $this->response->setJSON([
            'data' => $this->product->findAll()
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        $product = new Product($data);

        // Upload de imagem
        $img = $this->request->getFile('image');
        if ($img && $img->isValid()) {
            $newName = $img->getRandomName();
            $img->move('uploads/produtos', $newName);
            $product->image = $newName;
        }

        $this->product->save($product);

        return redirect()->back()->with('success', 'Produto criado!');
    }

    public function get($id)
    {
        return $this->response->setJSON(['product' => $this->product->find($id)]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $product = $this->product->find($id);

        $product->fill($data);

        // Upload
        $img = $this->request->getFile('image');
        if ($img && $img->isValid()) {
            $newName = $img->getRandomName();
            $img->move('uploads/produtos', $newName);
            $product->image = $newName;
        }

        $this->product->save($product);

        return redirect()->back()->with('success', 'Produto atualizado!');
    }

    public function delete($id)
    {
        $this->product->delete($id);
        return redirect()->back()->with('success', 'Produto removido!');
    }
}