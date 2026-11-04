<?php

use Microblog\Helpers\Utils;
use Microblog\Services\UsuarioServico;

require_once __DIR__ . '/../vendor/autoload.php';

// Inicializar variáveis para mensagem de erro e dados do usuário
$mensagemErro = "";
$dadosUsuario = null;
$usuarioServico = null;

// Normaliza o valor do id recebido via parâmetro de URL
$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');

// Verificação do valor do id (se for inválido, vai redirecionar index)
Utils::verificarId($id);

/* 1* try/catch: responsável pela busca/carregamento dos dados do usuário */
try {
    $usuarioServico = new UsuarioServico();
    $dadosUsuario = $usuarioServico->buscarPorId($id);
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar os dados do usuário";
}

Utils::dump($dadosUsuario);

// Se a consulta retornar null e não tiver, apresentamos uma mensagem avisando que nada foi encontrado
if($dadosUsuario === null && $mensagemErro === ""){
    Utils::alertaErro("Usuário não encontrado!");
}

require_once __DIR__ . '/../includes/cabecalho-admin.php';
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Atualizar dados do usuário</h2>
        <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">
            <div class="mb-3">
                <label class="form-label" for="nome">Nome:</label>
                <input class="form-control" type="text" id="nome" name="nome" value="Usuário de exemplo">
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">E-mail:</label>
                <input class="form-control" type="email" id="email" name="email" value="editor@exemplo.com">
            </div>
            <div class="mb-3">
                <label class="form-label" for="senha">Senha:</label>
                <input class="form-control" type="password" id="senha" name="senha" placeholder="Preencha apenas se for alterar">
            </div>
            <div class="mb-3">
                <label class="form-label" for="tipo">Tipo:</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option value="editor" selected>Editor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
