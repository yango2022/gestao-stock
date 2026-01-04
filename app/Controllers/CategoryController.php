<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected CategoryModel $category;
    protected $companyId;

    public function __construct()
    {
        $this->category  = new CategoryModel();
        $this->companyId = auth()->user()->company_id;
    }

    /**
     * LISTAGEM DE CATEGORIAS (POR EMPRESA)
     */
    public function index()
    {
        $categories = $this->category
            ->where('company_id', $this->companyId)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('categories/index', [
            'categories' => $categories,
            'user'       => auth()->user(),
        ]);
    }

    /**
     * CRIAR CATEGORIA
     */
    public function store()
    {
        $data = $this->request->getPost();

        if (empty($data['name'])) {
            return redirect()->back()->with('error', 'O nome da categoria é obrigatório.');
        }

        $this->category->insert([
            'company_id'  => $this->companyId,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Categoria adicionada com sucesso!');
    }

    /**
     * OBTER CATEGORIA (AJAX)
     */
    public function get($id)
    {
        $category = $this->category
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $category) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Categoria não encontrada.',
            ]);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'category' => $category,
        ]);
    }

    /**
     * ATUALIZAR CATEGORIA
     */
    public function update($id)
    {
        $category = $this->category
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $category) {
            return redirect()->back()->with('error', 'Categoria não encontrada.');
        }

        $data = $this->request->getPost();

        $this->category->update($id, [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * REMOVER CATEGORIA
     */
    public function delete($id)
    {
        $category = $this->category
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $category) {
            return redirect()->back()->with('error', 'Categoria não encontrada.');
        }

        $this->category->delete($id);

        return redirect()->back()->with('success', 'Categoria removida com sucesso!');
    }
}