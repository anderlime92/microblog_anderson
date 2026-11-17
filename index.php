<?php
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

$listaDeDestaques = [];
$mensagemErroDestaques = null;

try {
    $noticiaServico = new NoticiaServico();
    $listaDeDestaques = $noticiaServico->listarDestaques();
} catch (Exception $e) {
    $mensagemErroDestaques = "Não foi possível carregar as notícias em destaque.";
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">
    <div class="col-12 mb-3">
        <h2 class="fs-3">Notícias em destaque</h2>
    </div>

    <?php if ($mensagemErroDestaques !== null): ?>
        <div class="col-12">
            <p class="alert alert-danger" role="alert"><?= Utils::escaparHtml($mensagemErroDestaques) ?></p>
        </div>
    <?php elseif (empty($listaDeDestaques)): ?>
        <div class="col-12">
            <p class="alert alert-info" role="status">Não há notícias em destaque no momento.</p>
        </div>
    <?php else: ?>
        <?php foreach ($listaDeDestaques as $itemNoticia): ?>
            <?php
            $nomeImagem = basename((string) $itemNoticia['imagem']);
            $caminhoImagem = __DIR__ . '/images/' . $nomeImagem;
            $imagem = $nomeImagem !== '' && is_file($caminhoImagem) ? $nomeImagem : 'abstrato.jpg';
            ?>
            <div class="col-md-6 my-1 px-md-1">
                <article class="card shadow-sm h-100">
                    <a href="noticia.php?id=<?= (int) $itemNoticia['id'] ?>" class="card-link">
                        <img src="images/<?= Utils::escaparHtml($imagem) ?>" class="card-img-top" alt="<?= Utils::escaparHtml((string) $itemNoticia['titulo']) ?>">
                        <div class="card-body">
                            <h3 class="fs-4 card-title"><?= Utils::escaparHtml((string) $itemNoticia['titulo']) ?></h3>
                            <p class="card-text"><?= Utils::escaparHtml((string) $itemNoticia['resumo']) ?></p>
                        </div>
                    </a>
                </article>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
