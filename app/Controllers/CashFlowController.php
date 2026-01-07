<?php

namespace App\Controllers;

use App\Models\CashFlowModel;

class CashFlowController extends BaseController
{
    protected CashFlowModel $model;
    protected int $companyId;

    public function __construct()
    {
        $this->model = new CashFlowModel();

        $user = auth()->user();

        if (! $user || empty($user->company_id)) {
            redirect()->to('/logout')->send();
            exit;
        }

        $this->companyId = $user->company_id;
    }

    // =====================================================
    // 📄 LISTAGEM
    // =====================================================
    public function index()
    {
        $data = [
            'items' => $this->model
                ->where('company_id', $this->companyId)
                ->orderBy('created_at', 'DESC')
                ->paginate(15),

            'pager' => $this->model->pager,
            'user'  => auth()->user(),
        ];

        return view('cashflow/index', $data);
    }

    // =====================================================
    // ➕ REGISTAR MOVIMENTO
    // =====================================================
    public function store()
    {
        $data = $this->request->getPost();

        $data['user_id']    = auth()->id();
        $data['company_id'] = $this->companyId;

        $this->model->insert($data);

        return redirect()
            ->to('/fluxo-caixa')
            ->with('success', 'Movimento registado com sucesso!');
    }

    // =====================================================
    // ✏️ EDITAR
    // =====================================================
    public function edit($id)
    {
        $item = $this->model
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $item) {
            return redirect()
                ->to('/fluxo-caixa')
                ->with('error', 'Movimento não encontrado.');
        }

        return view('cashflow/edit', [
            'item' => $item,
            'user' => auth()->user(),
        ]);
    }

    // =====================================================
    // 🔄 ATUALIZAR
    // =====================================================
    public function update($id)
    {
        $item = $this->model
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $item) {
            return redirect()
                ->to('/fluxo-caixa')
                ->with('error', 'Movimento inválido.');
        }

        $data = $this->request->getPost();

        // Proteção: nunca permitir alterar empresa ou utilizador
        unset($data['company_id'], $data['user_id']);

        $this->model->update($id, $data);

        return redirect()
            ->to('/fluxo-caixa')
            ->with('success', 'Movimento atualizado com sucesso!');
    }

    // =====================================================
    // 🗑️ APAGAR
    // =====================================================
    public function delete($id)
    {
        $item = $this->model
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $item) {
            return redirect()
                ->to('/fluxo-caixa')
                ->with('error', 'Movimento inválido.');
        }

        $this->model->delete($id);

        return redirect()
            ->to('/fluxo-caixa')
            ->with('success', 'Movimento apagado com sucesso!');
    }
}