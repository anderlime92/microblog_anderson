# Resumo do histórico da branch

**Data da análise:** 17/11/2026

**Branch analisada:** `10_aula-17112026`

**HEAD:** `8ff130b` — `Implementa busca dinâmica de notícias`

**Base da comparação:** `35ad172` — `09_aula-16112026`

**Escopo desta atualização:** quatro commits na branch atual, com integração das principais páginas públicas e busca dinâmica.

**Estado ao iniciar a análise:** branch sincronizada com `origin/10_aula-17112026` e árvore de trabalho limpa.

## Visão geral

A branch herda a autenticação e autorização do painel, os CRUDs administrativos de usuários, categorias e notícias, com escopo de edição por perfil. Nesta etapa, as páginas públicas passaram a consultar o banco: a inicial exibe destaques e listagem geral; o detalhe carrega uma notícia pelo ID; o menu mostra categorias do banco; a página de categoria filtra notícias; e a busca usa `fetch` para consultar o servidor enquanto a pessoa digita.

O painel mantém autorização no serviço e nas rotas: administradores podem operar sobre qualquer notícia e editores apenas sobre as próprias. As consultas públicas não dependem da sessão. O detalhe e as listagens escapam valores dinâmicos na saída; notícias sem autor exibem “Equipe Microblog”. A sintaxe foi revisada, mas não houve teste de integração com o banco nesta atualização.

## Evolução dos commits

| Período / etapa | Commits representativos | Resultado |
| --- | --- | --- |
| 03/11 a 10/11 | `7def2e8` a `0fcb28d` | Estrutura do projeto, PSR-4, CRUD de usuários e categorias, validações e primeiros elementos de sessão. |
| 10/11 a 12/11 | `863ac7e` a `deeee07` | Login funcional com senha, sessão, proteção de rotas, controle de acesso por perfil, logout e atualização do próprio perfil. |
| 13/11 | `b75dc4f` a `623a6d9` | Início do cadastro de notícias: conexão do serviço, formulário com categorias, preenchimento seguro dos campos, validações, upload e persistência; correções no INSERT e inclusão de imagens de apoio. |
| 13/11 | `ec78bb9` | Listagem administrativa de notícias conectada ao banco, com todas as publicações e autores para admin e apenas publicações próprias para editor. |
| 16/11 | `f55a36f` a `067f800` | Configuração do projeto para IDE, reorganização das imagens, documentação para agentes e prompts e conclusão do CRUD administrativo de notícias com autorização por perfil. |
| 17/11 | `bafff1b` a `8ff130b` | Integração da página inicial, detalhe público, listagem por categoria, menu dinâmico e busca assíncrona de notícias. |

O HEAD atual é `8ff130b`. Desde a base desta branch (`35ad172`), foram adicionados quatro commits: `bafff1b` (notícias públicas na inicial), `43e7a81` (detalhe público), `3361248` (listagem por categoria) e `8ff130b` (busca dinâmica). A referência remota `origin/10_aula-17112026` está sincronizada com o branch local; a árvore de trabalho estava limpa na análise.

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

### Área pública e busca

- [index.php](index.php) consulta notícias destacadas pelo método `listarDestaques()` e inclui [includes/todas.php](includes/todas.php), que lista as notícias públicas em ordem decrescente de data. Imagens ausentes nos destaques usam `images/abstrato.jpg` como alternativa.
- [noticia.php](noticia.php) valida o ID recebido, carrega a notícia pública por `buscarPublicaPorId()` e exibe título, texto, data, autor e imagem. Para notícia sem autor, mostra “Equipe Microblog”; para imagem ausente, usa uma imagem alternativa. A consulta pública atual não inclui/exibe a categoria, embora o README ainda a descreva como parte do detalhe.
- [includes/cabecalho.php](includes/cabecalho.php) consulta as categorias por `CategoriaServico::listarTodos()` e monta os links do menu dinamicamente. Uma falha ao consultar as categorias não impede a renderização do restante do cabeçalho.
- [noticias-por-categoria.php](noticias-por-categoria.php) valida o ID, confirma a existência da categoria e lista título, data (dia/mês/ano), autor e resumo. O método `listarPublicasPorCategoria()` usa `LEFT JOIN` para autor, com fallback “Equipe Microblog” na página. Categoria inexistente responde com status HTTP 404; categoria sem notícias recebe mensagem informativa.
- [resultados.php](resultados.php) atende somente POST, limita a busca a 100 caracteres e exige pelo menos dois caracteres; devolve fragmento HTML com até dez títulos encontrados ou mensagens de estado. Erros de banco são registrados e apresentados sem detalhes técnicos ao público.
- [js/busca.js](js/busca.js) faz consultas com `fetch` após debounce de 300 ms, cancela requisições anteriores, atualiza a área acessível de resultados, permite envio do formulário e fecha a lista ao clicar fora. A interface de busca depende de JavaScript.
- [src/Services/NoticiaServico.php](src/Services/NoticiaServico.php) inclui métodos de leitura pública: `listarDestaques()`, `listarPublicas()`, `listarPublicasPorCategoria()`, `buscarPublicas()` e `buscarPublicaPorId()`. A busca procura por título, resumo e texto com `LIKE`, ordena por data e limita a dez resultados. Os métodos públicos não aplicam o escopo administrativo de sessão.

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
5. **Integração com o banco não foi exercitada nesta análise:** a sintaxe e a disponibilidade dos métodos foram verificadas, mas a execução das consultas públicas depende de banco local configurado.
6. **Busca com curingas:** `%` e `_` digitados são interpretados como curingas do `LIKE`; as listagens não têm paginação e a busca retorna no máximo dez resultados.
7. **Detalhe público sem categoria:** o README descreve categoria como parte dos detalhes, mas a consulta pública atual e a página `noticia.php` não a exibem.

## Testes e validação

- Não foram encontrados arquivos de testes automatizados no projeto no momento desta revisão.
- A sintaxe dos arquivos públicos PHP alterados nesta branch e de `src/Services/NoticiaServico.php` foi validada com `php -l`, sem erros; `js/busca.js` passou em `node --check`.
- O autoload foi consultado para confirmar os métodos de serviço adicionados.
- Não houve execução de testes de integração PHP/MySQL; o comportamento com banco e dados reais ainda precisa ser confirmado no ambiente local.
- O estado da branch foi revisado até o HEAD `8ff130b`.

## Síntese

A branch `10_aula-17112026` mantém os fluxos administrativos herdados e integra páginas públicas dinâmicas para destaques, listagem geral, detalhe, categorias e busca assíncrona. A autorização administrativa continua respeitando o papel e a propriedade das notícias, enquanto as consultas públicas não dependem de sessão. Próximos passos recomendados: testar a integração com MySQL, avaliar paginação, tratar `%` e `_` como literais se esse for o comportamento desejado, alinhar o detalhe público à descrição do README quanto à categoria e acrescentar testes automatizados.
