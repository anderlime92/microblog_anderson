<?php 
require_once __DIR__ . '/../vendor/autoload.php'; 

use Microblog\Helpers\Utils;
use Microblog\Services\CategoriaServico;

$mensagemErro = "";
$listaDeCategorias = [];

try {
    $categoriaServico = new CategoriaServico();
    $listaDeCategorias = $categoriaServico->listarTodos();
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar a lista de categorias";
}

require_once __DIR__ . '/../includes/cabecalho-admin.php'; 
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Categorias 
            <span class="badge bg-dark"><?= count($listaDeCategorias) ?></span>
        </h2>

        <?php if($mensagemErro !== ""): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <p class="text-center mt-5">
            <a class="btn btn-primary" href="categoria-insere.php">
                <i class="bi bi-plus-circle"></i> Inserir nova categoria
            </a>
        </p>

        <?php if($mensagemErro === ""): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th class="text-center">Operações</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach($listaDeCategorias as $itemCategoria): ?>
                    <tr>
                        <td> <?= Utils::escaparHtml($itemCategoria['nome']) ?> </td>
                        <td class="text-center">
<a class="btn btn-warning" 
href="categoria-atualiza.php?id=<?= Utils::escaparHtml($itemCategoria['id']) ?>"><i class="bi bi-pencil"></i> Atualizar</a>
<a class="btn btn-danger excluir" 
href="categoria-exclui.php?id=<?= Utils::escaparHtml($itemCategoria['id']) ?>"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    
                    
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </article>
</div>

<script src="js/confirm.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>