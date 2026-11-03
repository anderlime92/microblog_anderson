<?php require_once __DIR__ . '/includes/cabecalho.php'; ?>

<div class="row">
    <div class="bg-white rounded shadow col-12 my-1 py-4">
        <h2 class="text-center fw-light">Acesso à área administrativa</h2>

        <form action="" method="post" id="form-login" class="mx-auto w-50">

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
