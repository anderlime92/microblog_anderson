<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/../vendor/autoload.php';

ControleDeAcesso::exigirLogin();

$usuarioEhAdmin = ControleDeAcesso::usuarioEhAdmin();
$mensagemErro = '';
$listaDeNoticias = [];

try {
    $noticiaServico = new NoticiaServico();
    $listaDeNoticias = $noticiaServico->listarTodos();
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = 'Não foi possível carregar a lista de notícias';
}

require_once __DIR__ . '/../includes/cabecalho-admin.php';
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Notícias <span class="badge bg-dark"><?= count($listaDeNoticias) ?></span></h2>
        <?php if ($mensagemErro !== ''): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>
        <p class="text-center mt-5">
            <a class="btn btn-primary" href="noticia-insere.php">
                <i class="bi bi-plus-circle"></i> Inserir nova notícia
            </a>
        </p>
        <?php if ($mensagemErro === ''): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Data</th>
                        <?php if ($usuarioEhAdmin): ?>
                            <th>Autor</th>
                        <?php endif; ?>
                        <th class="text-center">Destaque</th>
                        <th class="text-center" colspan="2">Operações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($listaDeNoticias === []): ?>
                        <tr>
                            <td class="text-center" colspan="<?= $usuarioEhAdmin ? 6 : 5 ?>">Nenhuma notícia cadastrada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listaDeNoticias as $itemNoticia): ?>
                            <tr>
                                <td><?= Utils::escaparHtml($itemNoticia['titulo']) ?></td>
                                <td><?= Utils::escaparHtml(Utils::formataData($itemNoticia['data'])) ?></td>
                                <?php if ($usuarioEhAdmin): ?>
                                    <td><?= Utils::escaparHtml($itemNoticia['autor'] ?? 'Autor não informado') ?></td>
                                <?php endif; ?>
                                <td class="text-center"><?= Utils::escaparHtml($itemNoticia['destaque']) ?></td>
                                <td class="text-center">
                                    <a class="btn btn-warning btn-sm" href="noticia-atualiza.php?id=<?= Utils::escaparHtml($itemNoticia['id']) ?>"><i class="bi bi-pencil"></i> Atualizar</a>
                                </td>
                                <td>
                                    <a class="btn btn-danger excluir btn-sm" href="noticia-exclui.php?id=<?= Utils::escaparHtml($itemNoticia['id']) ?>"><i class="bi bi-trash"></i> Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </article>
</div>

<script src="js/confirm.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
