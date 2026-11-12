<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;

require_once __DIR__ . '/../vendor/autoload.php';

ControleDeAcesso::exigirAdmin();

// Inicializa variáveis para armazenar mensagens de erro e valores do formulário
$mensagemErro = "";
$nome = "";
$email = "";
$tipo = "editor"; // valor padrão conforme formulário

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        /* Normaliza as entradas de dados do formulário para minimizar problemas de insegurança e inconsistências*/
        $nome = Utils::normalizarEntrada($_POST["nome"] ?? "");
        $email = Utils::normalizarEntrada($_POST["email"] ?? "", 'email');
        $senhaBruta = $_POST['senha'];
        $tipo = Utils::normalizarEntrada($_POST["tipo"] ?? "");

        /* Aplica validações aos valoresdos campos do formulário */
        Validacoes::validarNome($nome);
        Validacoes::validarEmail($email);
        Validacoes::validarSenha($senhaBruta);
        Validacoes::validarTipo($tipo);

        /* Setodas as validações passarem: */

        // Codificar a senha
        $senha = Utils::codificarSenha($senhaBruta);

        // Converter a string do tipo de usuário (vindo do formulário) para o ENUM
        $tipoEnum = TipoUsuario::from($tipo);

        // Criar um objeto Usuario com os dados normalizados e validados
        $usuario = new Usuario($nome, $email, $senha, $tipoEnum);

        // Criar um serviço para usuario
        $usuarioServico = new UsuarioServico();

        // Iserir o usuário através do serviço
        $usuarioServico->inserir($usuario);

        // Redirecionar para a página usuarios.php
        header("location:usuarios.php");
        exit;
    } catch (InvalidArgumentException $e) {
        // Primeiro catch: para erros de validação
        // Exemplos: nome inválido, e-mail incorreto, senha inadequada etc.
        $mensagemErro = $e->getMessage(); // resgata a mensagem lançada pela exceção
    } catch (Throwable $e) {
        // Segundo catch: para exceções e erros inesperados.

        // Registramos no log
        Utils::registrarLog($e);

        // Definimos uma mensagem genérica ao usuário
        $mensagemErro = "Não foi possível cadastrar o usuário. Tente novamente.";
    }
}

require_once __DIR__ . '/../includes/cabecalho-admin.php';

?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Inserir novo usuário</h2>

        <?php if ($mensagemErro !== ""):  ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <form class="mx-auto w-75" action="" method="post" id="form-inserir" name="form-inserir">

        <!-- Aplicamos o value com valores dinâmicos para garantir que os dados já digitados previamente, não sejam removidos dos campos no caso de uma falha na validação.  -->
            <div class="mb-3">
                <label class="form-label" for="nome">Nome:</label>
                <input class="form-control" type="text" id="nome" name="nome" value="<?= Utils::escaparHtml($nome) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">E-mail:</label>
                <input class="form-control" type="email" id="email" name="email" value="<?php Utils::escaparHtml($email) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="senha">Senha:</label>
                <input class="form-control" type="password" id="senha" name="senha">
            </div>
            <div class="mb-3">
                <label class="form-label" for="tipo">Tipo:</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option value="editor" selected>Editor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button class="btn btn-primary" id="inserir" name="inserir"><i class="bi bi-save"></i> Inserir</button>
        </form>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>