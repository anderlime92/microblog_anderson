<?php
namespace Microblog\Services;

use Exception;
use Microblog\Database\ConexaoBD;
use PDO;
use PDOException;

final class UsuarioServico
{
    private PDO $conexao;

    /* Ao criar um objeto UsuarioServico, o construtor fica responsável por chamar/executar a conexão com o banco de dados (proveniente do método estático getConexao). Os dados da conexão ficaram guardados na propriedade conexão */
    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    public function listarTodos():array
    {
        $sql = "SELECT id, nome, email, tipo FROM usuarios ORDER BY nome";

        try {
            $consulta = $this->conexao->query($sql);
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            // Registrar/logar os detalhes do erro em logs/erro.log
            Utils::registrarLog($e);

            // Lançando uma mensagem de erro amigável (sem detalhes)
            // Essa mensagem será posteriormente capturada e exibida no Front-End
            throw new Exception("Erro ao listar usuários");
        }
    }
}