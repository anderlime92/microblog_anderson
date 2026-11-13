<?php
namespace Microblog\Services;

use Microblog\Database\ConexaoBD;
use PDO;

final class NoticiaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }
}