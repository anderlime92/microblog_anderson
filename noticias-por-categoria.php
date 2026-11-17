<?php
use Microblog\Helpers\Utils;
use Microblog\Services\CategoriaServico;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

$idCategoria = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($idCategoria);

$dadosCategoria = null;
$listaDeNoticias = [];
$mensagemErro = null;

try {
    $categoriaServico = new CategoriaServico();
    $dadosCategoria = $categoriaServico->buscarPorId($idCategoria);

    if ($dadosCategoria !== null) {
        $noticiaServico = new NoticiaServico();
        $listaDeNoticias = $noticiaServico->listarPublicasPorCategoria($idCategoria);
    }
} catch (Throwable $e) {
    $mensagemErro = 'Não foi possível carregar as notícias desta categoria.';
}

if ($dadosCategoria === null && $mensagemErro === null) {
    http_response_code(404);
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">
    <article class="col-12">
        <?php if ($mensagemErro !== null): ?>
            <p class="alert alert-danger" role="alert"><?= Utils::escaparHtml($mensagemErro) ?></p>
        <?php elseif ($dadosCategoria === null): ?>
            <p class="alert alert-warning" role="status">A categoria solicitada não foi encontrada.</p>
        <?php else: ?>
            <h2 class="text-center">
                Notícias sobre <span class="badge bg-primary"><?= Utils::escaparHtml((string) $dadosCategoria['nome']) ?></span>
            </h2>

            <div class="row my-1">
                <div class="col-12 px-md-1">
                    <div class="list-group">
                        <?php if (empty($listaDeNoticias)): ?>
                            <p class="alert alert-info" role="status">Ainda não há notícias nesta categoria.</p>
                        <?php else: ?>
                            <?php foreach ($listaDeNoticias as $itemNoticia): ?>
                                <?php
                                $dataNoticia = (string) $itemNoticia['data'];
                                $autor = trim((string) ($itemNoticia['autor'] ?? ''));
                                $autor = $autor !== '' ? $autor : 'Equipe Microblog';
                                ?>
                                <a href="noticia.php?id=<?= (int) $itemNoticia['id'] ?>" class="list-group-item list-group-item-action">
                                    <h3 class="fs-6"><?= Utils::escaparHtml((string) $itemNoticia['titulo']) ?></h3>
                                    <p>
                                        <time datetime="<?= Utils::escaparHtml(date(DATE_ATOM, strtotime($dataNoticia))) ?>"><?= Utils::escaparHtml(date('d/m/Y', strtotime($dataNoticia))) ?></time>
                                        - <?= Utils::escaparHtml($autor) ?>
                                    </p>
                                    <p class="mb-0"><?= Utils::escaparHtml((string) $itemNoticia['resumo']) ?></p>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </article>
</div>

<?php
require_once __DIR__ . '/includes/rodape.php';
?>
