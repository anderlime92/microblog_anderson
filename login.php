<?php

use Microblog\Helpers\Utils;

require_once __DIR__ . '/vendor/autoload.php';

/* Mensagens relacionadas ao processo de login/logout */
if( isset($_GET["campos_obrigatorios"]) ){
	$feedback = "Preencha e-mail e senha!";
} elseif( isset($_GET['dados_incorretos']) ){
	$feedback = "Algo de errado não está certo!";
} elseif( isset($_GET['logout']) ){
	$feedback = "Você saiu do sistema!";
} elseif( isset($_GET['acesso_proibido']) ){
	$feedback = "Você deve logar primeiro";
} elseif (isset($_GET['erro'])) {
	$feedback = "Não foi possível entrar agora. Tente novamente.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturamos/normalizamos a entrada de dados
    $email = Utils::normalizarEntrada($_POST['email'] ?? '', 'email');
    $senhaRecebida = $_POST['senha'] ?? '';

    // Verificar se algum campo está vazio
    if($email === '' || $senhaRecebida === '') {
        header("location:login.php?campos_obrigatorios");
        exit;
    }
}

require_once __DIR__ . '/includes/cabecalho.php'; ?>

<div class="row">
    <div class="bg-white rounded shadow col-12 my-1 py-4">
        <h2 class="text-center fw-light">Acesso à área administrativa</h2>

        <form action="" method="post" id="form-login" class="mx-auto w-50">

        <!-- Se existir alguma mensagem na variável $feedback, mostramos a mensagem para a pessoa. -->
          <?php if (isset($feedback)): ?>
            <p class="my-2 alert alert-warning text-center">
                <?= Utils::escaparHtml($feedback) ?>
            </p>
          <?php endif; ?>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input autofocus class="form-control" type="email" id="email" name="email" autocomplete="username">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input class="form-control" type="password" id="senha" name="senha" autocomplete="current-password">
            </div>

            <button class="btn btn-primary btn-lg" type="submit">Entrar</button>
            
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
