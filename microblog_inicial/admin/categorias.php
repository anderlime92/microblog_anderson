<?php require_once __DIR__ . '/../includes/cabecalho-admin.php'; ?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Categorias <span class="badge bg-dark">2</span></h2>
        <p class="text-center mt-5">
            <a class="btn btn-primary" href="categoria-insere.php">
                <i class="bi bi-plus-circle"></i> Inserir nova categoria
            </a>
        </p>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th class="text-center">Operações</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Desenvolvimento</td>
                        <td class="text-center">
                            <a class="btn btn-warning" href="categoria-atualiza.php?id=1"><i class="bi bi-pencil"></i> Atualizar</a>
                            <a class="btn btn-danger excluir" href="categoria-exclui.php?id=1"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Tecnologia</td>
                        <td class="text-center">
                            <a class="btn btn-warning" href="categoria-atualiza.php?id=2"><i class="bi bi-pencil"></i> Atualizar</a>
                            <a class="btn btn-danger excluir" href="categoria-exclui.php?id=2"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </article>
</div>

<script src="js/confirm.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
