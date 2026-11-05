<?php

namespace Microblog\Services;

use Exception;
use Microblog\Database\ConexaoBD;
use Microblog\Helpers\Utils;
use Microblog\Models\Categoria;
use PDO;
use PDOException;

final class CategoriaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome";

        try {
            $consulta = $this->conexao->query($sql);
            return $consulta->fetchAll();
        } catch (PDOException $e) {

            Utils::registrarLog($e);

            throw new Exception("Erro ao listar categorias");
        }
    }

    public function inserir(Categoria $categoria): void
    {
        $sql = "INSERT INTO categorias (nome)
        VALUES(:nome)";

        try {
            $consulta = $this->conexao->prepare($sql);

            $consulta->bindValue(":nome", $categoria->getNome());

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir categoria");
        }
    }
}