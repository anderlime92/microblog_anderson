# Resumo do histórico da branch

**Data da análise:** 16/11/2026

**Branch analisada:** `09_aula-16112026`

**HEAD:** `067f800` — `Documenta contexto e prompts da conversa`

**Base da comparação:** `2512912` (ancestral comum com `main`)

**Escopo:** 67 commits desde a base; 58 arquivos alterados, com 1.795 inserções e 202 remoções.

**Estado ao iniciar a análise:** branch sincronizada com `origin/09_aula-16112026` e árvore de trabalho limpa.

## Visão geral

A branch atual consolida a autenticação e autorização do painel, o CRUD administrativo de usuários e categorias, e o CRUD de notícias com escopo por perfil. Além de cadastrar e listar notícias, o sistema agora carrega os dados para edição, permite atualizar os campos e a imagem opcional, e exclui notícias respeitando a propriedade do editor. Também foram organizadas as imagens na pasta de recursos da aplicação e incluídos os arquivos de referência para agentes e prompts.

O fluxo de notícias aplica autorização no serviço e nas rotas: administradores podem operar sobre qualquer notícia e editores apenas sobre as próprias. A edição carrega todas as categorias e seleciona a categoria atual; valida título, texto, resumo, destaque e categoria; e mantém a imagem existente quando não há novo upload. A página de exclusão confirma a existência/acessibilidade do registro antes de chamar o serviço. As páginas públicas de notícias ainda incluem conteúdo estático provisório.

## Evolução dos commits

| Período / etapa | Commits representativos | Resultado |
| --- | --- | --- |
| 03/11 a 10/11 | `7def2e8` a `0fcb28d` | Estrutura do projeto, PSR-4, CRUD de usuários e categorias, validações e primeiros elementos de sessão. |
| 10/11 a 12/11 | `863ac7e` a `deeee07` | Login funcional com senha, sessão, proteção de rotas, controle de acesso por perfil, logout e atualização do próprio perfil. |
| 13/11 | `b75dc4f` a `623a6d9` | Início do cadastro de notícias: conexão do serviço, formulário com categorias, preenchimento seguro dos campos, validações, upload e persistência; correções no INSERT e inclusão de imagens de apoio. |
| 13/11 | `ec78bb9` | Listagem administrativa de notícias conectada ao banco, com todas as publicações e autores para admin e apenas publicações próprias para editor. |
| 16/11 | `f55a36f` a `067f800` | Configuração do projeto para IDE, ajuste local da conexão, reorganização das imagens, documentação para agentes e prompts, carregamento/edição/exclusão de notícias com autorização por perfil. |

O HEAD atual é `067f800`. Desde o resumo anterior (`f9c84e6`), oito commits foram adicionados: configuração de IDE, ajuste da configuração local do banco, movimentação e reorganização das imagens, criação do `AGENTS.md`, carregamento da notícia no formulário de edição, implementação da atualização e exclusão de notícias e registro dos prompts. A referência remota `origin/09_aula-16112026` está sincronizada com o branch local; a árvore de trabalho estava limpa na análise.

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

### Notícias: cadastro, listagem, edição e exclusão

- [admin/noticia-insere.php](admin/noticia-insere.php) carrega as categorias do banco, mantém os dados digitados após erros, valida categoria, título, texto, resumo e destaque, processa a imagem e associa a notícia ao usuário autenticado.
- [src/Services/NoticiaServico.php](src/Services/NoticiaServico.php) implementa operações parametrizadas via PDO: `inserir()`, `listarTodos()`, `buscarPorId()`, `atualizar()` e `excluir()`.
- O método `listarTodos()` de [src/Services/NoticiaServico.php](src/Services/NoticiaServico.php) retorna todas as notícias com o nome do autor para administradores; para editores, filtra pelo ID da sessão e não inclui o nome do autor.
- [admin/noticias.php](admin/noticias.php) apresenta a listagem real do banco, a contagem dinâmica, os autores somente para administradores, os links de operação e uma mensagem quando não há notícias.
- `buscarPorId()` seleciona os dados da notícia e os nomes do autor/categoria; o editor recebe apenas notícia associada ao ID da própria sessão.
- [admin/noticia-atualiza.php](admin/noticia-atualiza.php) usa o ID da URL para carregar a notícia, lista as categorias disponíveis e seleciona a categoria atual. No POST, valida os campos, preserva a imagem caso não seja enviado arquivo e redireciona à listagem depois da atualização.
- `atualizar()` não altera o autor da publicação e restringe updates de editor por `usuario_id`, mesmo que a rota seja chamada diretamente.
- [admin/noticia-exclui.php](admin/noticia-exclui.php) valida o ID, confirma o acesso à notícia e chama `excluir()`. O serviço filtra por usuário para editores. O arquivo de imagem não é removido do disco, pois pode ser reutilizado por outras notícias.
- [src/Models/Noticia.php](src/Models/Noticia.php) permite criar notícias sem informar previamente ID e data, que ficam a cargo do banco.
- [src/Helpers/Utils.php](src/Helpers/Utils.php) valida o upload (tipo, extensão e tamanho até 2 MB) e salva imagens em `images/`.
- As imagens usadas pela aplicação foram movidas de `_materiais/Fotos` para `images/`; alguns arquivos que estavam em `images/Fotos` foram reorganizados diretamente em `images/`. As imagens `felicidade.jpg`, `mais.jpg` e `sanduiche.jpg` também estão disponíveis nessa pasta.

### Documentação e ambiente

- [AGENTS.md](AGENTS.md) registra arquitetura, configuração, padrões, autorização e limitações conhecidas para agentes de desenvolvimento.
- [PROMPTS.md](PROMPTS.md) mantém um resumo operacional e as solicitações do usuário nesta conversa.
- `composer.lock` foi gerado, mas não fixa pacotes externos: o arquivo registra listas `packages` e `packages-dev` vazias. O `composer.json` mantém o autoload PSR-4 do projeto.
- Foram adicionadas configurações de projeto em `.idea/` para IntelliJ. A conexão de banco continua sendo configurada em `src/Database/ConexaoBD.php`; os valores locais não devem ser copiados para documentação pública.

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
- A edição e a exclusão de notícias foram concluídas com consultas PDO parametrizadas e condições de propriedade para editores.
- A edição de notícia agora mantém a imagem atual quando o upload é opcional e repopula os campos enviados quando a validação falha.
- As imagens de aplicação foram retiradas da pasta de materiais e centralizadas em `images/`, que é também o destino dos uploads.

## Limitações e riscos observados

1. **Proteção CSRF ausente:** ainda não há token ou verificação de origem para formulários de escrita e exclusão.
2. **Autorização deve continuar sendo verificada por rota:** há apenas os papéis `admin` e `editor`; as rotas de notícias aplicam escopo por propriedade, mas qualquer nova operação precisa manter a validação no servidor.
3. **Exclusões por GET:** páginas de exclusão ainda dependem de links/GET, o que é mais frágil do que um fluxo por POST com confirmação.
4. **Sem suíte de testes automatizados:** não há testes de regressão para login, sessão, perfil, permissões e exclusão.
5. **Integração com o banco não foi exercitada nesta análise:** a sintaxe e a lógica foram revisadas, mas a execução de operações CRUD depende de banco local configurado.
6. **Feedback de erros de edição e formulário ainda pode ser melhorado:** há pontos de validação e UX que ainda podem ser refinados para retorno mais consistente ao usuário.

## Testes e validação

- Não foram encontrados arquivos de testes automatizados no projeto no momento desta revisão.
- A sintaxe de `admin/noticia-atualiza.php`, `admin/noticia-exclui.php` e `src/Services/NoticiaServico.php` foi validada com `php -l`, sem erros.
- Não houve execução de testes de integração PHP/MySQL; a gravação no banco ainda precisa ser confirmada no ambiente local.
- O comportamento foi revisado por leitura do código e do histórico de commits até o HEAD atual.

## Síntese

A branch `09_aula-16112026` entrega o fluxo administrativo de notícias com cadastro, listagem, edição e exclusão, mantendo acesso de editores restrito às próprias publicações e acesso de administradores a todas. O projeto também dispõe de documentação para continuidade por outra IA e de imagens organizadas para a aplicação. Próximos passos recomendados: proteger mutações contra CSRF, trocar exclusões GET por POST e acrescentar testes automatizados e integração com banco.
