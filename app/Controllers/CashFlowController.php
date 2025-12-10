<?php

namespace App\Controllers;

use App\Models\CashFlowModel;

class CashFlowController extends BaseController
{
    public function index()
    {
        $model = new CashFlowModel();
        $user = auth()->user();

        $data['items']  = $model->orderBy('created_at', 'DESC')->paginate(15);
        $data['pager']  = $model->pager;
        $data['user']   = $user;

        return view('cashflow/index', $data);
    }

    public function store()
    {
        $model = new CashFlowModel();

        $data = $this->request->getPost();
        $data['user_id'] = auth()->id();

        $model->insert($data);

        return redirect()->to('/fluxo-caixa')->with('success', 'Movimento registado com sucesso!');
    }

    public function edit($id)
    {
        $model = new CashFlowModel();

        $data['item'] = $model->find($id);

        return view('cashflow/edit', $data);
    }

    public function update($id)
    {
        $model = new CashFlowModel();

        $data = $this->request->getPost();
        $model->update($id, $data);

        return redirect()->to('/fluxo-caixa')->with('success', 'Movimento atualizado!');
    }

    public function delete($id)
    {
        $model = new CashFlowModel();
        $model->delete($id);

        return redirect()->to('/fluxo-caixa')->with('success', 'Movimento apagado!');
    }
}