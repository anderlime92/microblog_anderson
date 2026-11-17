<?php
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

$termoBusca = trim((string) ($_POST['busca'] ?? ''));
$termoBusca = mb_substr($termoBusca, 0, 100, 'UTF-8');

if (mb_strlen($termoBusca, 'UTF-8') < 2) {
    echo '<p class="small text-muted mb-0">Digite pelo menos 2 caracteres para pesquisar.</p>';
    exit;
}

try {
    $noticiaServico = new NoticiaServico();
    $listaDeNoticias = $noticiaServico->buscarPublicas($termoBusca);
} catch (Throwable $e) {
    Utils::registrarLog($e);
    http_response_code(500);
    echo '<p class="small text-danger mb-0">Não foi possível realizar a busca. Tente novamente.</p>';
    exit;
}

if (empty($listaDeNoticias)) {
    echo '<p class="small text-muted mb-0">Nenhuma notícia encontrada.</p>';
    exit;
}
?>
<h2 class="fs-6 mb-2">Resultados: <span class="badge rounded-pill text-bg-success"><?= count($listaDeNoticias) ?></span></h2>
<div class="list-group">
    <?php foreach ($listaDeNoticias as $itemNoticia): ?>
        <a class="list-group-item list-group-item-action" href="noticia.php?id=<?= (int) $itemNoticia['id'] ?>">
            <?= Utils::escaparHtml((string) $itemNoticia['titulo']) ?>
        </a>
    <?php endforeach; ?>
</div>
