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

    public function listarDestaques(): array
    {
        $sql = "SELECT id, data, titulo, resumo, imagem
                FROM noticias
                WHERE destaque = :destaque
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":destaque", "sim");
            $consulta->execute();
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias em destaque");
        }
    }

    public function listarPublicas(): array
    {
        $sql = "SELECT id, data, titulo, resumo, imagem
                FROM noticias
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->query($sql);
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias públicas");
        }
    }

    public function buscarPorId(int $id): ?array
    {
        $usuarioEhAdmin = ControleDeAcesso::usuarioEhAdmin();

        $sql = "SELECT n.id, n.data, n.titulo, n.texto, n.resumo, n.imagem,
                n.destaque, n.usuario_id, n.categoria_id,
                u.nome AS autor, c.nome AS categoria
                FROM noticias n
                LEFT JOIN usuarios u ON n.usuario_id = u.id
                LEFT JOIN categorias c ON n.categoria_id = c.id
                WHERE n.id = :id";

        if (!$usuarioEhAdmin) {
            $sql .= " AND n.usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);

            if (!$usuarioEhAdmin) {
                $consulta->bindValue(":usuario_id", (int) $_SESSION['id'], PDO::PARAM_INT);
            }

            $consulta->execute();
            return $consulta->fetch() ?: null;
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar notícia por ID");
        }
    }

    public function buscarPublicaPorId(int $id): ?array
    {
        $sql = "SELECT n.id, n.data, n.titulo, n.texto, n.resumo, n.imagem,
                u.nome AS autor
                FROM noticias n
                LEFT JOIN usuarios u ON n.usuario_id = u.id
                WHERE n.id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetch() ?: null;
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar notícia pública por ID");
        }
    }

    public function atualizar(Noticia $noticia): void
    {
        $id = $noticia->getId();
        if ($id === null) {
            throw new \InvalidArgumentException("A notícia precisa ter um ID para ser atualizada.");
        }

        $usuarioEhAdmin = ControleDeAcesso::usuarioEhAdmin();

        $sql = "UPDATE noticias
                SET titulo = :titulo, texto = :texto, resumo = :resumo,
                    imagem = :imagem, destaque = :destaque, categoria_id = :categoria_id
                WHERE id = :id";

        if (!$usuarioEhAdmin) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $noticia->getTitulo());
            $consulta->bindValue(":texto", $noticia->getTexto());
            $consulta->bindValue(":resumo", $noticia->getResumo());
            $consulta->bindValue(":imagem", $noticia->getImagem());
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value);
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId(), PDO::PARAM_INT);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);

            if (!$usuarioEhAdmin) {
                $consulta->bindValue(":usuario_id", (int) $_SESSION['id'], PDO::PARAM_INT);
            }

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar notícia");
        }
    }

    public function excluir(int $id): void
    {
        $usuarioEhAdmin = ControleDeAcesso::usuarioEhAdmin();

        $sql = "DELETE FROM noticias WHERE id = :id";

        if (!$usuarioEhAdmin) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);

            if (!$usuarioEhAdmin) {
                $consulta->bindValue(":usuario_id", (int) $_SESSION['id'], PDO::PARAM_INT);
            }

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir notícia");
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
