<?php
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($id);

$dadosNoticia = null;
$mensagemErro = null;

try {
    $noticiaServico = new NoticiaServico();
    $dadosNoticia = $noticiaServico->buscarPublicaPorId($id);
} catch (Throwable $e) {
    $mensagemErro = 'Não foi possível carregar a notícia. Tente novamente mais tarde.';
}

if ($dadosNoticia === null && $mensagemErro === null) {
    http_response_code(404);
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">
    <?php if ($mensagemErro !== null): ?>
        <div class="col-12">
            <p class="alert alert-danger" role="alert"><?= Utils::escaparHtml($mensagemErro) ?></p>
        </div>
    <?php elseif ($dadosNoticia === null): ?>
        <div class="col-12">
            <p class="alert alert-warning" role="status">A notícia solicitada não foi encontrada.</p>
        </div>
    <?php else: ?>
        <?php
        $nomeImagem = basename((string) ($dadosNoticia['imagem'] ?? ''));
        $caminhoImagem = __DIR__ . '/images/' . $nomeImagem;
        $imagem = $nomeImagem !== '' && is_file($caminhoImagem) ? $nomeImagem : 'abstrato.jpg';
        $autor = trim((string) ($dadosNoticia['autor'] ?? ''));
        $autor = $autor !== '' ? $autor : 'Equipe Microblog';
        $dataNoticia = (string) $dadosNoticia['data'];
        ?>
        <article class="col-12">
            <h2><?= Utils::escaparHtml((string) $dadosNoticia['titulo']) ?></h2>
            <p class="font-weight-light">
                <time datetime="<?= Utils::escaparHtml(date(DATE_ATOM, strtotime($dataNoticia))) ?>"><?= Utils::escaparHtml(date('d/m/Y', strtotime($dataNoticia))) ?></time> -
                <span><?= Utils::escaparHtml($autor) ?></span>
            </p>
            <img src="images/<?= Utils::escaparHtml(rawurlencode($imagem)) ?>" alt="<?= Utils::escaparHtml((string) $dadosNoticia['titulo']) ?>" class="float-start pe-2 img-fluid">
            <p class="ajusta-texto"><?= Utils::escaparHtml((string) $dadosNoticia['texto']) ?></p>
        </article>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
