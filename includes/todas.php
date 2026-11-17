<?php
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/../vendor/autoload.php';

$listaDeNoticias = [];
$mensagemErroNoticias = null;

try {
    $noticiaServico = new NoticiaServico();
    $listaDeNoticias = $noticiaServico->listarPublicas();
} catch (Exception $e) {
    $mensagemErroNoticias = "Não foi possível carregar as notícias.";
}
?>

<hr class="my-5 w-50 mx-auto">

<div class="row my-1">
    <div class="col-12 px-md-1">
        <div class="list-group">
            <h2 class="fs-6 text-center text-muted">Todas as notícias</h2>

            <?php if ($mensagemErroNoticias !== null): ?>
                <p class="alert alert-danger" role="alert"><?= Utils::escaparHtml($mensagemErroNoticias) ?></p>
            <?php elseif (empty($listaDeNoticias)): ?>
                <p class="alert alert-info" role="status">Ainda não há notícias publicadas.</p>
            <?php else: ?>
                <?php foreach ($listaDeNoticias as $itemNoticia): ?>
                    <?php $dataNoticia = (string) $itemNoticia['data']; ?>
                    <a href="noticia.php?id=<?= (int) $itemNoticia['id'] ?>" class="list-group-item list-group-item-action">
                        <h3 class="fs-6">
                            <time datetime="<?= Utils::escaparHtml(date(DATE_ATOM, strtotime($dataNoticia))) ?>"><?= Utils::escaparHtml(Utils::formataData($dataNoticia)) ?></time>
                            - <?= Utils::escaparHtml((string) $itemNoticia['titulo']) ?>
                        </h3>
                        <p class="mb-0"><?= Utils::escaparHtml((string) $itemNoticia['resumo']) ?></p>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
