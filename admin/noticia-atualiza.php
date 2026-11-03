<?php require_once __DIR__ . '/../includes/cabecalho-admin.php'; ?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Atualizar dados da notícia</h2>
        <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label" for="categoria">Categoria:</label>
                <select class="form-select" name="categoria" id="categoria">
                    <option value="1" selected>Desenvolvimento</option>
                    <option value="2">Tecnologia</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="titulo">Título:</label>
                <input value="Título provisório da notícia" class="form-control" type="text" id="titulo" name="titulo">
            </div>
            <div class="mb-3">
                <label class="form-label" for="texto">Texto:</label>
                <textarea class="form-control" name="texto" id="texto" cols="50" rows="6">Texto provisório da notícia.</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="resumo">Resumo (máximo de 300 caracteres):</label>
                <span id="maximo" class="badge bg-danger">0</span>
                <textarea class="form-control" name="resumo" id="resumo" cols="50" rows="2" maxlength="300">Resumo provisório da notícia.</textarea>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-5">
                        <label for="imagem-existente" class="form-label">Imagem atual:</label>
                        <input value="conversa.jpg" class="form-control" type="text" id="imagem-existente" readonly disabled>
                    </div>
                    <div class="col-7 text-end">
                        <img src="../images/conversa.jpg" alt="Imagem da notícia" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="imagem" class="form-label">Caso queira mudar, selecione outra imagem:</label>
                <input class="form-control" type="file" id="imagem" name="imagem" accept="image/jpeg, image/gif, image/png, image/webp">
            </div>
            <div class="mb-3">
                <p>Deixar a notícia em destaque?</p>
                <input type="radio" class="btn-check" name="destaque" id="option1" autocomplete="off" value="nao" checked>
                <label class="btn btn-outline-danger" for="option1">Não</label>
                <input type="radio" class="btn-check" name="destaque" id="option2" autocomplete="off" value="sim">
                <label class="btn btn-outline-success" for="option2">Sim</label>
            </div>
            <button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>
    </article>
</div>

<script src="js/contador.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
