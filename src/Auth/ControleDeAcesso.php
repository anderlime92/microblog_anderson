<?php
namespace Microblog\Auth;

/* Sessões no PHP
Funcionalidade usada para controlar o acesso a determinadas páginas e recursos de um site/aplicação.

Exemplos: áreas administrativas, painéis de controle, área de cliente/usuário/aluno/etc. 

Nessas áreas, o acesso depende de alguma forma de autenticação, como login e senha, biometria, telemetria por dois fatores etc.
*/

final class ControleDeAcesso
{
    // Impedir a criação de objeto/instância
    private function __construct() {}

    // Inicia a sessão se ela ainda não estiver ativa
    private static function iniciarSessao(): void
    {
        // Se o status da sessão NÃO FOR ativa (ou, se a sessão está inativa)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            // Então, iniciamos a sessão
            session_start();
        }
    }

    private static function encerrarSessao():void
    {
        $_SESSION = []; // limpar os dados do usuário armazenados na sessão
        session_destroy(); // destroi/remove/exclui quaisquer dados da sessão no navegador
    }

    public static function exigirLogin(): void
    {
        self::iniciarSessao();

        // Se NÃO EXISTIR na sessão uma variável chamada id,
        // significa que o usuário NÃO ESTÁ logado
        if (!isset($_SESSION['id'])) {
            // encerrar a sessão (ainda programar)
            self::encerrarSessao();

            // redirecionar para a página de login
            header("location:../login.php?acesso_proibido");
            exit;
        }
    }

    public static function login(int $id, string $nome, string $tipo): void
    {
        self::iniciarSessao();

        // Cria as variáveis de sessão com os dados de quem está logado
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['tipo'] = $tipo;
    }

    public static function logout(): void
    {
        self::iniciarSessao();
        self::encerrarSessao();
        header("location:../login.php?logout");
        exit;
    }
}