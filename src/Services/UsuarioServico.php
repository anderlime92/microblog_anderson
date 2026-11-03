<?php
namespace Microblog\Services;

use Microblog\Database\ConexaoBD;
use PDO;

final class UsuarioServico
{
    private PDO $conexao;

    /* Ao criar um objeto UsuarioServico, o construtor fica responsável por chamar/executar a conexão com o banco de dados (proveniente do método estático getConexao). Os dados da conexão ficaram guardados na propriedade conexão */
    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }
}