<?php require_once __DIR__ . '/../includes/cabecalho-admin.php'; ?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Notícias <span class="badge bg-dark">2</span></h2>
        <p class="text-center mt-5">
            <a class="btn btn-primary" href="noticia-insere.php">
                <i class="bi bi-plus-circle"></i> Inserir nova notícia
            </a>
        </p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Data</th>
                        <th>Autor</th>
                        <th class="text-center">Destaque</th>
                        <th class="text-center" colspan="2">Operações</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Título de exemplo</td>
                        <td>01/01/2026</td>
                        <td>Nome do autor</td>
                        <td class="text-center">sim</td>
                        <td class="text-center">
                            <a class="btn btn-warning btn-sm" href="noticia-atualiza.php?id=1"><i class="bi bi-pencil"></i> Atualizar</a>
                        </td>
                        <td>
                            <a class="btn btn-danger excluir btn-sm" href="noticia-exclui.php?id=1"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Outro título de exemplo</td>
                        <td>02/01/2026</td>
                        <td>Nome do autor</td>
                        <td class="text-center">não</td>
                        <td class="text-center">
                            <a class="btn btn-warning btn-sm" href="noticia-atualiza.php?id=2"><i class="bi bi-pencil"></i> Atualizar</a>
                        </td>
                        <td>
                            <a class="btn btn-danger excluir btn-sm" href="noticia-exclui.php?id=2"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </article>
</div>

<script src="js/confirm.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
