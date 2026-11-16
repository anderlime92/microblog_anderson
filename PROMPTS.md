# Prompts e contexto do projeto

Este arquivo registra os pedidos enviados pelo usuário nesta conversa e resume o contexto de desenvolvimento para retomada em outra sessão. Para obter as instruções completas e atualizadas do projeto, consulte também [AGENTS.md](./AGENTS.md).

## Contexto de desenvolvimento

- Projeto Microblog: portal didático em PHP orientado a objetos, integrado a MySQL/MariaDB por PDO.
- O código de domínio fica em `src/`, com autoload PSR-4 `Microblog\` configurado em `composer.json`.
- As páginas administrativas ficam em `admin/`; reutilize os serviços, modelos, enums, validações e helpers existentes.
- Use consultas PDO parametrizadas, valide entradas, escape valores dinâmicos na saída com `Utils::escaparHtml()` e mantenha mensagens/interface em português.
- Respeite as permissões no servidor: administradores podem operar sobre todas as notícias; editores, somente sobre as próprias.
- Preserve os padrões de nomes, namespaces, estrutura e estilo existentes. Faça mudanças focadas e valide a sintaxe PHP.
- O projeto segue em evolução; confira o código atual antes de presumir que uma página ou funcionalidade está concluída.

## Prompts enviados nesta conversa

### 1. Análise inicial

```text
Analise todas as classes utilizadas até o momento (contidas na pasta src), em especial a classe NoticiaServico e a classe Noticia.

Veja também as páginas administrativas, em especial as páginas que gerenciam usuários, normalizações e validações.

Aguarde o próximo comando.
```

### 2. Criação do AGENTS.md

```text
Gere um arquivo AGENTS.md contendo todas as informações essenciais a respeito do desenvolvimento e características deste projeto. A idéia é que este arquivo sirva de referência para outras IAs ou caso ocorra a troca de máquina/VSCode e similares, situação em que não há um histórico de conversação.
```

### 3. Busca de notícia por ID

```text
Programe em NoticiaServico o método buscarPorId. Leve em consideração que o SQL deve ser ajustado para quando for usuários do tipo ADMIN, carregar/exibir a notícia de qualquer pessoa de acordo com o id do usuário e id da notícia. Se for usuário do tipo EDITOR, ele/ela só pode carregar/exibir suas próprias notícias. faça apenas o método e aguarde o próximo comando.
```

### 4. Carregamento do formulário de atualização

```text
Analise a página adimin/noticia.php e veja se o link dinâmico para a página admin/noticia-atualiza.php permitindo que ela exiba os dados da notícia a ser atualizada (conforme link que o usuário acionar em noticias.php).

Atenção: o select de categorias deve carregar todas as categorias existentes, e deve trazer selecionada a categoria da notícia escolhida.

Não implemente ainda o código para atualizar a notícia. Foque apenas no carregamento exibição dos dados da página.
```

### 5. Atualização de notícia

```text
Agora, programe a página noticia-atualiza.php, para tal, cheque e indentifique os padrões das classes, os nomes de métodos, propriedades, variáveis, arquivos, variáveis, arquivos, namespaces, etc da dita cuja assim como confira e analize o estilo e estrutura das páginas usuário-atualiza.php, noticia-insere.php e categoria-atualiza.php para nortear.
```

### 6. Exclusão de notícia

```text
Ok, agora programe a página noticia-exclui.php, para tal, cheque e indentifique os padrões das classes, os nomes de métodos, propriedades, variáveis, arquivos, variáveis, arquivos, namespaces, etc da dita cuja assim como confira e analize o estilo e estrutura das páginas usuario-exclui.php, noticia-insere.php, noticia-atualiza.php e categoria-exclui.php para nortear.
```

### 7. Registro dos prompts

```text
Baseado no AGENTS.md crie o PROMPTS.md e guarde todos os meus prompts neste chat que tive com tu.
```
