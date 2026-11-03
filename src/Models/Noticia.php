<?php

class Noticia
{
    private int $id;
    private string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private Destaque $destaque;
    private int $usuarioId;
    private int $categoriaId;

    public function __construct(
        string $titulo,
        string $texto,
        string $resumo,
        string $imagem,
        Destaque $destaque,
        int $usuarioId,
        int $categoriaId,
        int $id,
        string $data
    ) {
        $this->titulo = $titulo;
        $this->texto = $texto;
        $this->resumo = $resumo;
        $this->imagem = $imagem;
        $this->destaque = $destaque;
        $this->usuarioId = $usuarioId;
        $this->categoriaId = $categoriaId;
        $this->id = $id;
        $this->data = $data;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getData(): ?string
    {
        return $this->data;
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function getTexto(): string
    {
        return $this->texto;
    }
    public function getResumo(): string
    {
        return $this->resumo;
    }
    public function getImagem(): string
    {
        return $this->imagem;
    }
    public function getDestaque(): Destaque
    {
        return $this->destaque;
    }
    public function getUsuarioId(): int
    {
        return $this->usuarioId;
    }
    public function getCategoriaId(): int
    {
        return $this->categoriaId;
    }
}
