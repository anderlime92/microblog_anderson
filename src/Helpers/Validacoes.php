<?php

use InvalidArgumentException;

class Validacoes
{
    private function __construct() {}

    public static function validarNome(string $nome): void
    {
        if (trim($nome) === '') {
            throw new InvalidArgumentException("Nome não pode ser vazio.");
        }
    }

    public static function validarEmail(string $email): void
    {
        if (trim($email) === '') {
            throw new InvalidArgumentException("Email não pode ser vazio.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email inválido.");
        }
    }

    public static function validarSenha(string $senha): void
    {
        if ($senha === '') {
            throw new InvalidArgumentException("Senha não pode ser vazia.");
        }

        if (mb_strlen($senha) < 5) {
            throw new InvalidArgumentException("Senha deve ter no mínimo 5 caracteres.");
        }
    }

    public static function validarTipo(string $tipo): void
    {
        if ($tipo === '') {
            throw new InvalidArgumentException("Selecione um tipo de usuário.");
        }

        if (!TipoUsuario::tryFrom($tipo)) {
            throw new InvalidArgumentException("Tipo de usuário inválido.");
        }
    }

    public static function validarTitulo(string $titulo): void
    {
        if (trim($titulo) === '') {
            throw new InvalidArgumentException("Título não pode ser vazio.");
        }
    }

    public static function validarTexto(string $texto): void
    {
        if (trim($texto) === '') {
            throw new InvalidArgumentException("Texto não pode ser vazio.");
        }
    }

    public static function validarResumo(string $resumo): void
    {
        if (trim($resumo) === '') {
            throw new InvalidArgumentException("Resumo não pode ser vazio.");
        }

        if (mb_strlen($resumo) > 300) {
            throw new InvalidArgumentException("Resumo deve ter no máximo 300 caracteres.");
        }
    }

    public static function validarDestaque(string $valor): void
    {
        if ($valor === '') {
            throw new InvalidArgumentException("Selecione uma opção de destaque.");
        }

        if (!Destaque::tryFrom($valor)) {
            throw new InvalidArgumentException("Valor de destaque inválido.");
        }
    }

    public static function validarCategoria(int $categoriaId): void
    {
        if ($categoriaId <= 0) {
            throw new InvalidArgumentException("Selecione uma categoria válida.");
        }
    }
}
