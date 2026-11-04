<?php
namespace Microblog\Models;
use Microblog\Enums\TipoUsuario;
class Usuario
{
    /* Alteramos a forma dos tipos desta propriedade para definir que ela pode aceitar int OU null */
    // private int | null $id;
    private ?int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private TipoUsuario $tipo;

    public function __construct(
        string $nome,
        string $email,
        string $senha,
        TipoUsuario $tipo = TipoUsuario::EDITOR,
        ?int $id = null // parâmetro opcional e com valor padrão null
    ) {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipo = $tipo;
        $this->id = $id;
    }


    public function getTipoUsuario(): TipoUsuario
    {
        return $this->tipo;
    }

    public function getId(): ?int // pode retornar int OU null
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

}
