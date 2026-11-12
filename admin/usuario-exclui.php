<?php 

use Microblog\Auth\ControleDeAcesso;
use Microblog\Helpers\Utils;
use Microblog\Services\UsuarioServico;

require_once __DIR__ . '/../vendor/autoload.php';

ControleDeAcesso::exigirAdmin();

$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($id);

try {
    $categoria = new UsuarioServico();
    $categoria->excluir($id);
    header("location:usuarios.php");
    exit;
} catch (Throwable $e) {
    Utils::registrarLog($e);
    Utils::alertaErro("Não foi possível excluir o usuário. Tente novamente");
}