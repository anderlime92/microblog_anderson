<?php require_once __DIR__ . '/../includes/cabecalho-admin.php'; ?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Usuários <span class="badge bg-dark">2</span></h2>
        <p class="text-center mt-5">
            <a class="btn btn-primary" href="usuario-insere.php">
                <i class="bi bi-plus-circle"></i> Inserir novo usuário
            </a>
        </p>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th class="text-center">Operações</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Usuário de exemplo</td>
                        <td>editor@exemplo.com</td>
                        <td>editor</td>
                        <td class="text-center">
                            <a class="btn btn-warning" href="usuario-atualiza.php?id=1"><i class="bi bi-pencil"></i> Atualizar</a>
                            <a class="btn btn-danger excluir" href="usuario-exclui.php?id=1"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Administradora de exemplo</td>
                        <td>admin@exemplo.com</td>
                        <td>admin</td>
                        <td class="text-center">
                            <a class="btn btn-warning" href="usuario-atualiza.php?id=2"><i class="bi bi-pencil"></i> Atualizar</a>
                            <a class="btn btn-danger excluir" href="usuario-exclui.php?id=2"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </article>
</div>

<script src="js/confirm.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
