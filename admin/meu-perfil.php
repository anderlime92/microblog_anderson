<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;

require_once __DIR__ . '/../vendor/autoload.php';
// ControleDeAcesso::exigirLogin();
require_once __DIR__ . '/../includes/cabecalho-admin.php';

$mensagemErro = "";
$dadosUsuario = null;
$usuarioServico = null;

// Capturando o id do usuário logado na sessão
$id = Utils::normalizarEntrada($_SESSION['id'] ?? null, 'inteiro');
// $id = $_SESSION['id'];

try {
    $usuarioServico = new UsuarioServico();
    $dadosUsuario = $usuarioServico->buscarPorId($id);
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar os dados do meu perfil";
}

if ($dadosUsuario === null && $mensagemErro === "") {
    Utils::alertaErro("Usuário não encontrado!");
}

$nome = $dadosUsuario['nome'] ?? "";
$email = $dadosUsuario['email'] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $nome = Utils::normalizarEntrada($_POST['nome'] ?? "");
        $email = Utils::normalizarEntrada($_POST['email'] ?? "", 'email');

        // O tipo do usuário não é  capturado do formulário
        // Usamos o valor atual do banco (ou da SESSION)
        $tipoEnum = TipoUsuario::from($dadosUsuario['tipo']); // $_SESSION['tipo']

        Validacoes::validarNome($nome);
        Validacoes::validarEmail($email);

        $senhaBruta = $_POST['senha'] ?? '';

        if ($senhaBruta === "") {
            $senha = $dadosUsuario['senha'];
        } else {
            Validacoes::validarSenha($senhaBruta);
            $senha = Utils::verificarSenha($senhaBruta, $dadosUsuario['senha']);
        }

        $usuario = new Usuario($nome, $email, $senha, $tipoEnum, $id);

        $usuarioServico->atualizar($usuario);

        // Mostrando o nome atualizado em caso de alteração
        $_SESSION['nome'] = $nome;

        header("location:index.php?perfil_atualizado");
        exit;
    } catch (InvalidArgumentException $e) {
    } catch (Throwable $e) {
        Utils::registrarLog($e);
        $mensagemErro = "Não foi possível atualizar o usuário. Tente novamente";
    }
}

?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Atualizar meus dados</h2>
        <?php if ($mensagemErro !== ""):  ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>
        <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">
            <div class="mb-3">
                <label class="form-label" for="nome">Nome:</label>
                <input class="form-control" type="text" id="nome" name="nome" value="<?= Utils::escaparHtml($nome) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">E-mail:</label>
                <input class="form-control" type="email" id="email" name="email" value="<?= Utils::escaparHtml($email) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="senha">Senha:</label>
                <input class="form-control" type="password" id="senha" name="senha" placeholder="Preencha apenas se for alterar">
            </div>
            <button class="btn btn-primary" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>