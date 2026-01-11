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
        $model = new ProductModel();
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
            'products'      => $model->findAllByCompany()
        ]);
    }

    public function list()
    {
        $model = new ProductModel();
        return $this->response->setJSON([
            'data' => $model->findAllByCompany()
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['company_id'] = auth()->user()->company_id;

        $data['iva_type'] = $data['iva_type'];

        if ($data['iva_type'] === 'normal') {
            $data['iva_rate'] = 14;
        }

        if ($data['iva_type'] === 'isento') {
            $data['iva_rate'] = 0;
        }

        if ($data['iva_type'] === 'reduzido' && $data['iva_rate'] <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Informe a taxa reduzida de IVA.');
        }

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
        $companyId = auth()->user()->company_id;

        // 🔒 Buscar produto SOMENTE da empresa do usuário
        $product = $this->product
            ->where('id', $id)
            ->where('company_id', $companyId)
            ->first();

        if (! $product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        $data = $this->request->getPost();

        // 🔒 Garantir que company_id não seja alterado
        unset($data['company_id']);

        $product->fill($data);

        // 📷 Upload de imagem
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && ! $img->hasMoved()) {

            $newName = $img->getRandomName();
            $img->move('uploads/produtos', $newName);

            // (Opcional) apagar imagem antiga
            if (!empty($product->image) && file_exists('uploads/produtos/' . $product->image)) {
                unlink('uploads/produtos/' . $product->image);
            }

            $product->image = $newName;
        }

        $this->product->save($product);

        return redirect()->back()->with('success', 'Produto atualizado com sucesso!');
    }

    public function delete($id)
    {
        $companyId = auth()->user()->company_id;

        //Buscar produto apenas da empresa do usuário
        $product = $this->product
            ->where('id', $id)
            ->where('company_id', $companyId)
            ->first();

        if (! $product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        //Remover imagem associada (opcional)
        if (!empty($product->image) && file_exists('uploads/produtos/' . $product->image)) {
            unlink('uploads/produtos/' . $product->image);
        }

        //Apagar produto
        $this->product->delete($product->id);

        return redirect()->back()->with('success', 'Produto removido com sucesso!');
    }

}