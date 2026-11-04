<?php
namespace Microblog\Services;

use Exception;
use Microblog\Database\ConexaoBD;
use Microblog\Helpers\Utils;
use Microblog\Models\Usuario;
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

    public function inserir(Usuario $usuario): void
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
        VALUES(:nome, :email, :senha, :tipo)";

        try {
            $consulta = $this->conexao->prepare($sql);

            /* Repare abaixo o acesso aos dados do objeto $usuario atráves de seus getters. No caso do getTipoUsuario, como se trata de uma ENUM, acessamos seu valor usando -> value */
            $consulta->bindValue(":nome", $usuario->getNome());
            $consulta->bindValue(":email", $usuario->getEmail());
            $consulta->bindValue(":senha", $usuario->getSenha());
            $consulta->bindValue(":tipo", $usuario->getTipoUsuario()->value);

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir usuário");
        }
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id);
            $consulta->execute();

            //Ternário conciso/abreviado (se fetch for TRUE, retorna ele mesmo; senão null)
            return $consulta->fetch() ?: null;

            // Usando ternário tradicional (faz a mesma coisa)
            // return $consulta->fetch() ? $consulta->fetch() : null;

        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar usuário por ID.");
        }
    }
}
