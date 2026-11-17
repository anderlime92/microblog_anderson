# Referência do projeto para agentes

## Visão geral

Microblog é um portal de notícias didático, desenvolvido em PHP com orientação a objetos e MySQL/MariaDB via PDO. O objetivo é praticar autenticação, autorização, CRUD, validação, upload e integração entre páginas PHP e banco de dados. O painel administrativo e as principais páginas públicas consultam o banco; confirme sempre o estado atual do código antes de assumir que uma funcionalidade está concluída.

## Ambiente e configuração

- O README documenta PHP 8.5 ou superior, com `pdo_mysql`, `mbstring` e `fileinfo`, além de Apache (por exemplo, XAMPP) e MySQL/MariaDB.
- O banco esperado pelo código é `microblog_2026`. O dump completo de referência está em `_materiais/microblog_2026_completo.sql`; há também `_materiais/microblog_2026_inicial.sql` para uma base inicial.
- A conexão PDO e suas credenciais de desenvolvimento estão em `src/Database/ConexaoBD.php`. Revise a configuração para cada máquina/ambiente e não grave credenciais privadas no repositório.
- `composer.json` configura apenas o autoload PSR-4: `Microblog\` aponta para `src/`. Os scripts PHP carregam `vendor/autoload.php`. Depois de alterar a configuração de autoload, regenere-o com `composer dump-autoload`.
- `composer.lock` está versionado, mas não lista pacotes externos no estado atual. Não presuma que bibliotecas adicionais estão disponíveis sem atualizar as dependências do projeto.
- Não há scripts de teste ou suíte de testes automatizados configurados no `composer.json` no estado atual. Para uma verificação de sintaxe, use `php -l caminho/do/arquivo.php` (ou o executável PHP do XAMPP se PHP não estiver no `PATH`). Testes que dependam de banco requerem MySQL/MariaDB configurado e o banco importado.

## Organização

- `src/Auth/`: sessão, autenticação e autorização.
- `src/Database/`: conexão PDO.
- `src/Enums/`: valores permitidos para perfil e destaque.
- `src/Helpers/`: normalização, escape de saída, validações, senha, upload e log.
- `src/Models/`: `Usuario`, `Categoria` e `Noticia`, com propriedades tipadas e getters.
- `src/Services/`: consultas e operações PDO para usuários, categorias e notícias.
- `admin/`: painel e páginas de operação; `admin/js/` contém scripts de interface.
- `includes/`: cabeçalhos, rodapés e blocos compartilhados.
- `js/`, `css/`, `images/`: recursos da área pública e imagens. `images/` é a pasta de imagens da aplicação e também o destino dos uploads; materiais de imagem foram reorganizados para cá.
- `_materiais/`: scripts SQL e materiais de apoio; não é código de aplicação.
- `logs/erros.log`: destino usado para registrar exceções.

## Arquitetura e convenções

- Classes do domínio usam namespace `Microblog\...` e autoload PSR-4 pelo Composer. Prefira reutilizar as classes e helpers existentes em vez de duplicar lógica nas páginas.
- A camada `Models` representa dados; `Services` concentra SQL e usa PDO com parâmetros preparados para valores variáveis. As páginas fazem a ponte entre requisição, validação, serviço e resposta HTML.
- `ConexaoBD` compartilha uma conexão PDO, com charset `utf8mb4`, modo de erro por exceções e fetch associativo.
- Em erros de banco, os serviços registram a exceção com `Utils::registrarLog()` e lançam uma mensagem genérica para a página. Não exponha detalhes de banco ou stack traces ao usuário.
- Para dados vindos de requisições, use `Utils::normalizarEntrada()` e as validações de `Validacoes` apropriadas. A normalização não substitui validação nem autorização.
- Para renderizar valores dinâmicos em HTML, use `Utils::escaparHtml()`. Escape na saída, não altere o conteúdo armazenado apenas para neutralizar HTML.
- Senhas devem ser armazenadas com `Utils::codificarSenha()` (`password_hash`) e verificadas com `password_verify()` ou `Utils::verificarSenha()`. Nunca exiba nem registre senhas.
- Use `Destaque` (`sim`, `nao`) e `TipoUsuario` (`admin`, `editor`) em vez de strings arbitrárias quando o fluxo já trabalha com essas enumerações.
- Mantenha mensagens e interface em português, seguindo os padrões Bootstrap e a estrutura das páginas existentes.
- Em mudanças, leia o arquivo e seus chamadores relevantes, mantenha o escopo solicitado e não remova alterações preexistentes. Não considere controles de interface como substitutos de autorização no servidor.

## Banco de dados

O esquema do dump completo contém:

- `usuarios`: `id`, `nome`, `email`, `senha`, `tipo`; e-mail único; tipo enum `admin`/`editor`.
- `categorias`: `id`, `nome`.
- `noticias`: `id`, `data` (padrão `CURRENT_TIMESTAMP`), `titulo`, `texto`, `resumo`, `imagem`, `destaque` (`sim`/`nao`), `usuario_id` e `categoria_id`.
- As referências de notícia a usuário e categoria podem ser nulas; as chaves estrangeiras do dump usam `ON DELETE SET NULL`.

Há uma divergência histórica em `_materiais/modelagem-fisica.md` (nome de banco `microblog_2025` e limite de resumo diferente). Para o banco usado pelo código, confira `ConexaoBD.php` e o dump `microblog_2026_completo.sql`; não aplique a modelagem antiga sem reconciliá-la.

## Autenticação, autorização e funcionalidades atuais

- `ControleDeAcesso` mantém `id`, `nome` e `tipo` na sessão. `exigirLogin()` protege o painel e `exigirAdmin()` restringe operações administrativas; o cabeçalho compartilhado do painel exige login.
- Os tipos existentes são administrador e editor. O serviço de notícias lista todas as notícias para administrador e filtra pelo ID da sessão para editor. Verifique a autorização no endpoint ao alterar operações ou adicionar novas rotas.
- O CRUD de usuários e categorias está implementado nos serviços e nas páginas administrativas; operações de gerenciamento dessas entidades exigem administrador. A edição de perfil mantém o tipo atual e permite preservar a senha quando o campo fica vazio.
- `NoticiaServico` implementa CRUD administrativo com escopo de perfil e consultas públicas. `buscarPorId()`, `atualizar()` e `excluir()` permitem que administradores operem sobre qualquer notícia e restringem editores às próprias notícias. Os métodos de leitura pública incluem `listarDestaques()`, `listarPublicas()`, `listarPublicasPorCategoria()`, `buscarPublicas()` e `buscarPublicaPorId()`; eles não filtram por sessão.
- A página `admin/noticia-atualiza.php` carrega a notícia pelo ID, mostra as categorias disponíveis com a categoria atual selecionada, valida os campos e persiste alterações; uma imagem nova é opcional e, quando não enviada, a imagem existente é preservada. O formulário administrativo de inserção também valida campos, categoria e destaque e usa `Utils::upload()`.
- A página `admin/noticia-exclui.php` valida o ID, confirma que a notícia existe e pode ser acessada pelo usuário atual, e então solicita a exclusão ao serviço. Ela não apaga o arquivo de imagem do disco.
- O upload permite JPEG, GIF, PNG e WebP, limita o tamanho a 2 MB, verifica tipo/extensão e grava em `images/`. Considere colisões de nomes e a segurança de arquivos ao modificar esse fluxo.
- A página pública inicial lista destaques e todas as notícias; o detalhe consulta uma notícia por ID; o menu público carrega as categorias existentes; e a página de categoria lista notícias da categoria escolhida. Essas páginas tratam valores dinâmicos com escape HTML e exibem “Equipe Microblog” quando o autor não está associado. O detalhe público atual mostra título, texto, data, autor e imagem, mas não exibe a categoria, apesar da descrição do README.
- A busca pública usa `js/busca.js` com `fetch` para enviar POST a `resultados.php`. O endpoint aceita termos de 2 a 100 caracteres e retorna até dez títulos encontrados por título, resumo ou texto. A busca depende de JavaScript; o método de serviço usa `LIKE`, portanto `%` e `_` digitados são curingas. As listagens públicas não têm paginação.
- O cabeçalho busca categorias no banco; se essa consulta falhar, renderiza o restante da página com o menu sem categorias. Nas consultas públicas, falhas são convertidas em mensagens genéricas para a interface, sem expor detalhes do banco.
- A documentação geral pode não refletir os controles atuais: em particular, confira a autorização real de cada rota (as operações de categorias no código exigem administrador).

## Segurança e limitações conhecidas

- Formulários de escrita/exclusão não têm proteção CSRF implementada no estado atual.
- As páginas de exclusão existentes usam parâmetros GET. Preserve a autorização do servidor e considere essa limitação ao modificar ações de escrita.
- As operações de busca, atualização e exclusão de notícia restringem editores à propriedade da notícia no servidor, além de conferir o papel do usuário.
- A autenticação usa dados da sessão e não deve ser substituída por verificações apenas de interface ou links ocultos.
- Validações existentes incluem campos obrigatórios, e-mail, tamanho mínimo de senha (5 caracteres), tipos enumerados e resumo (até 300 caracteres). Mudanças no esquema ou nas regras devem manter banco, modelo, formulário e validação coerentes.

