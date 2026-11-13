<?php

use Microblog\Enums\Destaque;
use Microblog\Helpers\Utils;
use Microblog\Services\CategoriaServico;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/cabecalho-admin.php';

// Capturando o id do usuário logado, ao qual a notícia que será inserida pertencerá
$idUsuario = $_SESSION['id'];

$mensagemErro = '';
$listaDeCategorias = [];
$titulo = '';
$texto = '';
$resumo = '';
$destaque = Destaque::NAO->value;
$categoria = '';
$categoriasCarregadas = false;

// Carregamento das categorias
try {
    $categoriaServico = new CategoriaServico();
    $listaDeCategorias = $categoriaServico->listarTodos();
    $categoriasCarregadas = true; // flag/sinalização de que deu certo
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar as categorias";
    //throw $th;
}
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Inserir nova notícia</h2>

        <?php if($mensagemErro !== ""): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <?php if($categoriasCarregadas): ?>
        <form class="mx-auto w-75" action="" method="post" id="form-inserir" name="form-inserir" enctype="multipart/form-data">
        <?php endif; ?>

            <div class="mb-3">
                <label class="form-label" for="categoria">Categoria:</label>
                <select class="form-select" name="categoria" id="categoria">
                    <option value=""></option>

                    <?php foreach($listaDeCategorias as $itemCategoria): ?>
                        <option value="<?= Utils::escaparHtml($itemCategoria['id']) ?>">
                            <?= Utils::escaparHtml($itemCategoria['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="titulo">Título:</label>
                <input class="form-control" type="text" id="titulo" name="titulo" value="<?= Utils::escaparHtml($titulo) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="texto">Texto:</label>
                <textarea class="form-control" name="texto" id="texto" cols="50" rows="6"><?= Utils::escaparHtml($texto) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="resumo">Resumo (máximo de 300 caracteres):</label>
                <span id="maximo" class="badge bg-danger">0</span>
                <textarea class="form-control" name="resumo" id="resumo" cols="50" rows="2" maxlength="300"><?= Utils::escaparHtml($resumo) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="imagem">Selecione uma imagem:</label>
                <input class="form-control" type="file" id="imagem" name="imagem" accept="image/jpeg, image/gif, image/png, image/webp">
            </div>
            <div class="mb-3">
                <p>Deixar a notícia em destaque?</p>
                <input type="radio" class="btn-check" name="destaque" id="option1" autocomplete="off" value="nao" checked>
                <label class="btn btn-outline-danger" for="option1">Não</label>
                <input type="radio" class="btn-check" name="destaque" id="option2" autocomplete="off" value="sim">
                <label class="btn btn-outline-success" for="option2">Sim</label>
            </div>
            <button class="btn btn-primary" id="inserir" name="inserir"><i class="bi bi-save"></i> Inserir</button>
        </form>
    </article>
</div>

<script src="js/contador.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>