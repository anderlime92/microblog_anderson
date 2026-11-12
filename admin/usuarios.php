<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Helpers\Utils;
use Microblog\Services\UsuarioServico;

require_once __DIR__ . "/../vendor/autoload.php";

ControleDeAcesso::exigirAdmin();

$mensagemErro = "";
$listaDeUsuarios = [];

try {
    $usuarioServico = new UsuarioServico();
    $listaDeUsuarios = $usuarioServico->listarTodos();
} catch (Throwable $e) {
    // Usamos THrowable para capturar tanto situações de Exception quanto de Error

    // Registramos o erro no log para análise do programador
    Utils::registrarLog($e);

    // Definimos uma mensagem genérica para exibir no Front-End
    $mensagemErro = "Não foi possível carregar a lista de usuários";
}

// Utils::dump($listaDeUsuarios);

require_once __DIR__ . '/../includes/cabecalho-admin.php';
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Usuários <span class="badge bg-dark"><?= count($listaDeUsuarios) ?></span></h2>

        <?php if ($mensagemErro !== ""):  ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <p class="text-center mt-5">
            <a class="btn btn-primary" href="usuario-insere.php">
                <i class="bi bi-plus-circle"></i> Inserir novo usuário
            </a>
        </p>
        <?php if ($mensagemErro === ""): ?>
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
                        <?php foreach ($listaDeUsuarios as $itemUsuario): ?>
                            <tr>
                                <td> <?= Utils::escaparHtml($itemUsuario['nome']) ?></td>
                                <td> <?= Utils::escaparHtml($itemUsuario['email']) ?></td>
                                <td> <?= Utils::escaparHtml($itemUsuario['tipo']) ?></td>
                                <td class="text-center">
                                    <a class="btn btn-warning" href="usuario-atualiza.php?id=<?= Utils::escaparHtml($itemUsuario['id']) ?>"><i class="bi bi-pencil"></i> Atualizar</a>
                                    <a class="btn btn-danger excluir" href="usuario-exclui.php?id=<?= Utils::escaparHtml($itemUsuario['id']) ?>"><i class="bi bi-trash"></i> Excluir</a>
                                </td>
                            </tr>
                            <tr>

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
