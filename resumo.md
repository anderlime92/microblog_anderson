# Resumo do histórico da branch

**Data da análise:** 13/11/2026

**Branch analisada:** `07_aula-13112026`

**HEAD:** `623a6d9` — `Add corn and sandwich images`

**Base da comparação:** `2512912` (ancestral comum com `main`)

**Escopo:** 56 commits desde a base; 33 arquivos alterados, com 1.227 inserções e 122 remoções.

**Estado ao iniciar a análise:** branch sincronizada com `origin/07_aula-13112026` e árvore de trabalho limpa.

## Visão geral

A branch atual consolida o fluxo de autenticação e autorização do painel administrativo, finaliza a edição do perfil do usuário e inicia o cadastro de notícias com persistência no banco. Depois da base de CRUD de usuários e categorias, o desenvolvimento evoluiu para um fluxo completo de login, sessão e controle de acesso por perfil; em seguida, foi iniciado o fluxo de inserção de notícias, com categorias carregadas do banco, validação dos campos e upload de imagem.

O projeto já está em uma etapa mais próxima do uso real: o sistema identifica o usuário pelo e-mail, valida a senha, cria a sessão com nome, tipo e ID, bloqueia páginas administrativas sem login e separa visibilidade de links e ações por nível de acesso. O painel também exibe feedback mais amigável e impede ações inseguras, como a exclusão da própria conta. O formulário de nova notícia já valida os dados e grava a publicação usando PDO; a listagem administrativa de notícias, porém, ainda apresenta conteúdo estático.

## Evolução dos commits

| Período / etapa | Commits representativos | Resultado |
| --- | --- | --- |
| 03/11 a 10/11 | `7def2e8` a `0fcb28d` | Estrutura do projeto, PSR-4, CRUD de usuários e categorias, validações e primeiros elementos de sessão. |
| 10/11 a 12/11 | `863ac7e` a `deeee07` | Login funcional com senha, sessão, proteção de rotas, controle de acesso por perfil, logout e atualização do próprio perfil. |
| 13/11 | `b75dc4f` a `623a6d9` | Início do cadastro de notícias: conexão do serviço, formulário com categorias, preenchimento seguro dos campos, validações, upload e persistência; correções no INSERT e inclusão de imagens de apoio. |

O HEAD atual é `623a6d9`. Desde a análise anterior foram adicionados 14 commits à branch, incluindo o commit de atualização do resumo (`3d8683e`) e 13 commits de trabalho em 13/11. A comparação foi feita contra a base ancestral `2512912`; a referência remota `origin/07_aula-13112026` está sincronizada com o branch local.

## Funcionalidades implementadas

### Infraestrutura e autenticação

- [src/Auth/ControleDeAcesso.php](src/Auth/ControleDeAcesso.php) centraliza a criação, destruição e verificação da sessão.
- Os métodos `login()`, `logout()`, `exigirLogin()` e `exigirAdmin()` já fazem o fluxo de autenticação e autorização do painel.
- A sessão agora salva `id`, `nome` e `tipo`, permitindo usar esse estado em várias páginas administrativas.
- [login.php](login.php) valida campos obrigatórios, localiza o usuário por e-mail e usa `password_verify()` para confirmar a senha.
- [admin/sair.php](admin/sair.php) agora executa `ControleDeAcesso::logout()` e redireciona com feedback `logout`.

### Administração e permissões

- [includes/cabecalho-admin.php](includes/cabecalho-admin.php) exige login antes de renderizar o painel administrativo.
- [admin/index.php](admin/index.php) exibe uma mensagem de boas-vindas com o nome do usuário logado e o tipo do perfil.
- [src/Auth/ControleDeAcesso.php](src/Auth/ControleDeAcesso.php) também possui `usuarioEhAdmin()` e `exigirAdmin()`, usados para limitar ações e links do administrador.
- [admin/usuario-exclui.php](admin/usuario-exclui.php) bloqueia a exclusão da própria conta, evitando que um usuário se remova da sessão atual.
- [admin/usuarios.php](admin/usuarios.php) e outros painéis agora exibem opções de gerenciamento apenas para administradores, com base em `$_SESSION['tipo']`.

### Perfil do usuário logado

- [admin/meu-perfil.php](admin/meu-perfil.php) carrega os dados do usuário autenticado por ID e permite atualizar nome, e-mail e senha.
- Quando a senha fica em branco, o sistema preserva o hash atual; quando preenchida, valida a nova senha e salva o hash atualizado.
- Após a atualização, o nome do usuário em sessão também é sincronizado com `$_SESSION['nome']`, para manter o painel consistente.
- O perfil usa `Utils::escaparHtml()` para evitar renderização insegura dos dados em tela.

### Cadastro de notícias

- [admin/noticia-insere.php](admin/noticia-insere.php) carrega as categorias do banco, mantém os dados digitados após erros, valida categoria, título, texto, resumo e destaque, processa a imagem e associa a notícia ao usuário autenticado.
- [src/Services/NoticiaServico.php](src/Services/NoticiaServico.php) implementa a inserção parametrizada da notícia via PDO, incluindo categoria, autor e valor textual do enum `Destaque`.
- [src/Models/Noticia.php](src/Models/Noticia.php) permite criar notícias sem informar previamente ID e data, que ficam a cargo do banco.
- [src/Helpers/Utils.php](src/Helpers/Utils.php) valida o upload (tipo, extensão e tamanho até 2 MB) e salva imagens em `images/`.
- Foram adicionadas imagens de apoio ao conteúdo de teste: `felicidade.jpg`, `mais.jpg` e `sanduiche.jpg`.

### Mensagens e UX do painel

- O projeto passou a customizar mensagens do painel administrativo, com feedback de `perfil_atualizado`, `logout`, `acesso_proibido`, `dados_incorretos` e `campos_obrigatorios` em [login.php](login.php).
- [admin/index.php](admin/index.php) e [includes/cabecalho-admin.php](includes/cabecalho-admin.php) refazem a apresentação do painel, mostrando o acesso de forma mais clara.
- Os links de categorias e usuários agora ficam ocultos para usuários sem privilégio de administrador.

## Correções relevantes durante a etapa final

- O login passou a consultar o usuário pelo e-mail e a validar a senha com `password_verify()` antes de criar a sessão.
- Os endpoints administrativos começaram a usar `exigirLogin()` e `exigirAdmin()` para impedir acesso indevido.
- A exclusão de usuário foi reforçada para impedir remoção do próprio perfil.
- O perfil do usuário deixou de ser apenas leitura e passou a atualizar os dados persistidos no banco.
- O painel administrativo passou a exibir informações do usuário autenticado dinamicamente, em vez de depender apenas de dados fixos.
- O serviço de notícias passou a inserir os campos com placeholders PDO corretos; também foi corrigido o vínculo de `usuario_id`, que deve receber o ID do usuário, e o enum `Destaque` é persistido pelo seu valor textual (`sim`/`nao`).
- O ID da categoria enviado pelo formulário é normalizado para inteiro antes da validação e da persistência.

## Limitações e riscos observados

1. **Proteção CSRF ausente:** ainda não há token ou verificação de origem para formulários de escrita e exclusão.
2. **Autorização detalhada incompleta:** o sistema distingue apenas administrador e não-administrador; a lógica de editor/usuário comum ainda não está detalhada em todas as rotas.
3. **Exclusões por GET:** páginas de exclusão ainda dependem de links/GET, o que é mais frágil do que um fluxo por POST com confirmação.
4. **Sem suíte de testes automatizados:** não há testes de regressão para login, sessão, perfil, permissões e exclusão.
5. **Listagem de notícias ainda estática:** [admin/noticias.php](admin/noticias.php) não consulta o banco; portanto, uma notícia recém-cadastrada não aparece nessa tabela apesar de ser persistida.
6. **Feedback de erros de edição e formulário ainda pode ser melhorado:** há pontos de validação e UX que ainda podem ser refinados para retorno mais consistente ao usuário.

## Testes e validação

- Não foram encontrados arquivos de testes automatizados no projeto no momento desta revisão.
- A sintaxe de `admin/noticia-insere.php` e `src/Services/NoticiaServico.php` foi validada com `php -l`, sem erros.
- Não houve execução de testes de integração PHP/MySQL; a gravação no banco ainda precisa ser confirmada no ambiente local.
- O comportamento foi revisado por leitura do código e do histórico de commits até o HEAD atual.

## Síntese

A branch `07_aula-13112026` entrega um salto importante no projeto: além da autenticação, sessão, controle de permissões, logout e atualização de perfil, o cadastro de notícias já possui validação e persistência no banco. O próximo passo natural é tornar dinâmicas a listagem administrativa e as demais operações de notícias, reforçar as medidas de segurança com CSRF, padronizar autorização por perfil em todos os endpoints e cobrir os fluxos com testes automatizados.
