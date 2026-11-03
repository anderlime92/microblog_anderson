<?php require_once __DIR__ . '/includes/cabecalho.php'; ?>

<div class="row my-1 mx-md-n1">

    <!-- Conteúdo estático provisório: depois será substituído pelos destaques do banco. -->
    <div class="col-md-6 my-1 px-md-1">
        <article class="card shadow-sm h-100">
            <a href="noticia.php?id=1" class="card-link">
                <img src="images/abstrato.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h2 class="fs-4 card-title">Título de uma notícia em destaque</h2>
                    <p class="card-text">Um resumo provisório para apresentar o visual do cartão.</p>
                </div>
            </a>
        </article>
    </div>

    <div class="col-md-6 my-1 px-md-1">
        <article class="card shadow-sm h-100">
            <a href="noticia.php?id=2" class="card-link">
                <img src="images/cores.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h2 class="fs-4 card-title">Outro título de notícia em destaque</h2>
                    <p class="card-text">Outro resumo provisório para compor a página inicial.</p>
                </div>
            </a>
        </article>
    </div>
    
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
