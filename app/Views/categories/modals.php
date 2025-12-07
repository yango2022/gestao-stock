<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <form class="modal-content" action="/categorias/store" method="post">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" required>

                <label class="mt-2">Descrição</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <form class="modal-content" id="editForm" method="post">
            <div class="modal-header">
                <h5 class="modal-title">Editar Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit_id">

                <label>Nome</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>

                <label class="mt-2">Descrição</label>
                <textarea name="description" id="edit_description" class="form-control"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Atualizar</button>
            </div>
        </form>
    </div>
</div>
