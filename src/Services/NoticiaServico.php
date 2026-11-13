<?php

namespace Microblog\Services;

use Exception;
use Microblog\Auth\ControleDeAcesso;
use Microblog\Database\ConexaoBD;
use Microblog\Helpers\Utils;
use Microblog\Models\Noticia;
use PDO;
use PDOException;

final class NoticiaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    public function listarTodos(): array
    {
        try {
            if (ControleDeAcesso::usuarioEhAdmin()) {
                $sql = "SELECT n.id, n.data, n.titulo, n.destaque, u.nome AS autor
                FROM noticias n
                LEFT JOIN usuarios u ON n.usuario_id = u.id
                ORDER BY n.data DESC";

                $consulta = $this->conexao->query($sql);
            } else {
                $sql = "SELECT n.id, n.data, n.titulo, n.destaque
                FROM noticias n
                WHERE n.usuario_id = :usuario_id
                ORDER BY n.data DESC";

                $consulta = $this->conexao->prepare($sql);
                $consulta->bindValue(":usuario_id", (int) $_SESSION['id'], PDO::PARAM_INT);
                $consulta->execute();
            }

            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias");
        }
    }

    public function inserir(Noticia $noticia): void
    {
        $sql = "INSERT INTO noticias (titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id)
        VALUES (:titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id)";

        try {
            $consulta = $this->conexao->prepare($sql);

            $consulta->bindValue(":titulo", $noticia->getTitulo());
            $consulta->bindValue(":texto", $noticia->getTexto());
            $consulta->bindValue(":resumo", $noticia->getResumo());
            $consulta->bindValue(":imagem", $noticia->getImagem());
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value);
            $consulta->bindValue(":usuario_id", $noticia->getUsuarioId(), PDO::PARAM_INT);
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId(), PDO::PARAM_INT);

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir notícia");
        }
    }
}
