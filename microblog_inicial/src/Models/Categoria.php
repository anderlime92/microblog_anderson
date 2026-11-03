<?php

class Categoria
{

    private int $id;
    private string $nome;

    public function __construct(string $nome, int $id)
    {
        $this->nome = $nome;
        $this->id = $id;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

}
