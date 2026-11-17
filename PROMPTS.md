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

### 8. Atualização dos documentos do branch

```text
Atualize o resumo.md de acordo com as alterações feitas neste branch, verifique e atualize o AGENTS.md de acordo com as alterações feitas au longo do branch atual e guarde os prompts restantes aqui realizados no PROMPTS.md.
```

### 9. Destaques e listagem geral públicas

```text
Ok, embasado(analize-os também) em/nos README.md, resumo.md, AGENTS.md e PROMPTS.md, programe a página index.php para exibição das notícias que são **destaque**, mostrando a **imagem**, o **título **e o **resumo**, e com o **link dinâmico **configurado para abrir a página **noticia.php** quando clicar em cima da notícia e logo após, faça a programação do arquivo includes/todas.php onde será realizada a exibição de todas as noticias.

Atente-se aos padrões das classes, os nomes de métodos, propriedades, variáveis, arquivos, variáveis, arquivos, namespaces, etc.
```

### 10. Detalhe público da notícia

```text
Beleza agora, denovo embasado(analize-os também) em/nos README.md, resumo.md, AGENTS.md e PROMPTS.md, programe a página noticia.php para exibição da notícia e seus detalhes: data (formatada para dia/mês/ano), autor, imagem e texto porém, no caso do autor, caso a notícia não tenha um autor associado a ela, deve ser exibido como alternativa o texto "Equipe Microblog".

Atente-se aos padrões das classes, os nomes de métodos, propriedades, variáveis, arquivos, variáveis, arquivos, namespaces, etc.
```

### 11. Ajuste manual no detalhe da notícia

```text
Fiz uma correção/ajuste removendo a variável nl2br ok. Aguade novos requerimentos/pedidos.
```

### 12. Notícias por categoria e menu dinâmico

```text
Bom agora novamente embasado(analize-os também) em/nos README.md, resumo.md, AGENTS.md e PROMPTS.md, programe a página noticias-por-categoria.php para exibição das notícias filtradas pela categoria escolhida no menu do site. É desejável que título, data (formatada para dia/mês/ano), autor e resumo sejam exibidos. Para o autor, caso a notícia não tenha um autor associado a ela, deve ser exibido como alternativa o texto "Equipe Microblog" e também configure o link dinâmico para abrir a página noticia.php quando clicar em cima da notícia.

Atente-se: foque-se primariamente à programação do arquivo includes/cabecalho.php para fazer com que o menu mostre todas as categorias existentes e claro confira os padrões das classes, os nomes de métodos, propriedades, variáveis, arquivos, variáveis, arquivos, namespaces, etc.
```

### 13. Busca dinâmica pública

```text
Enfim, mais uma vez embasado(analise-os também) em/nos README.md, resumo.md, AGENTS.md e PROMPTS.md, programe os arquivos resultados.php e busca.js (resultados.php deve utilizar PHP e busca.js deve utilizar JS).

Além dos enfoques aos padrões das classes, os nomes de métodos, propriedades, variáveis, arquivos, variáveis, arquivos, namespaces, etc, Atente-se também a programação em ambos os arquivos pois o resultados.php continuará sendo o responsável pela comunicação com as classes operações no banco de dados e o JS programado em busca.js fará a comunicação com resultados.php usando fetch e as interações necessárias no Front-End. O JS de busca.js não precisa ser complexo e/ou avançado se não for realmente necessário assim também como o PHP de resultados.php.
```

### 14. Atualização da documentação da branch

```text
Atualize o resumo.md de acordo com as alterações feitas neste branch, verifique e atualize o AGENTS.md de acordo com as alterações feitas au longo do branch atual e guarde os prompts restantes aqui realizados no PROMPTS.md.
```
