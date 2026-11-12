<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;

require_once __DIR__ . '/../vendor/autoload.php';

ControleDeAcesso::exigirAdmin();

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

// Utils::dump($dadosUsuario);

// Se a consulta retornar null e não tiver, apresentamos uma mensagem avisando que nada foi encontrado
if ($dadosUsuario === null && $mensagemErro === "") {
    Utils::alertaErro("Usuário não encontrado!");
}

/* Inicializa variáveis para armazenar os valores do formulário, usando os dados do usuário (se disponíveis) */

$nome = $dadosUsuario['nome'] ?? "";
$email = $dadosUsuario['email'] ?? "";
$tipo = $dadosUsuario['tipo'] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Captura e normaliza os dados do formulário
        $nome = Utils::normalizarEntrada($_POST['nome'] ?? "");
        $email = Utils::normalizarEntrada($_POST['email'] ?? "", 'email');
        $tipo = Utils::normalizarEntrada($_POST['tipo'] ?? "");

        // Valida os dados e associa o tipo à Enumeração
        Validacoes::validarNome($nome);
        Validacoes::validarEmail($email);
        Validacoes::validarTipo($tipo);
        $tipoEnum = TipoUsuario::from($tipo);

        // Capturando literalmente a senha como foi digitada
        $senhaBruta = $_POST['senha'] ?? '';

        /* Se a senha não foi preenchida, mantenha a senha atual. */
        /* Se o campo senha for preenchido, verificar se é a mesma senha. */
        /* Se for a mesma, não faz nada. se for uma nova senha, codifica (faz o hash) */
        if ($senhaBruta === "") {
            $senha = $dadosUsuario['senha'];
        } else {
            Validacoes::validarSenha($senhaBruta);
            $senha = Utils::verificarSenha($senhaBruta, $dadosUsuario['senha']);
        }

        // Criando um objeto com os dados do usuario visando a atualização no banco
        $usuario = new Usuario($nome, $email, $senha, $tipoEnum, $id);

        // Chamamos o serviço responsável por atualizar o usuario
        $usuarioServico->atualizar($usuario);

        // Se tudo der certo, redirecionamos:
        header("location:usuarios.php");
        exit;

    } catch (InvalidArgumentException $e) { // se validações falharem

    } catch (Throwable $e) { // se qualquer outro erro/exceção acontecer
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível atualizar o usuário. Tente novamente";
    }
}

require_once __DIR__ . '/../includes/cabecalho-admin.php';
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Atualizar dados do usuário</h2>
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
            <div class="mb-3">
                <label class="form-label" for="tipo">Tipo:</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option value="editor" <?php if ($tipo === 'editor') echo 'selected' ?>>Editor</option>
                    <option value="admin" <?php if ($tipo === 'admin') echo 'selected' ?>> Administrador</option>
                </select>
            </div>
            <button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>