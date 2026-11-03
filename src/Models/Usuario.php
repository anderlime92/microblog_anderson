<?php

class Usuario
{
    private int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private TipoUsuario $tipo;

    public function __construct(
        string $nome,
        string $email,
        string $senha,
        TipoUsuario $tipo = TipoUsuario::EDITOR,
        int $id
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

    public function getId(): ?int
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
