<?php require_once __DIR__ . '/includes/cabecalho.php'; ?>

<div class="row my-1 mx-md-n1">

    <article class="col-12">
    
        <h2 class="text-center">
            Notícias sobre <span class="badge bg-primary">Desenvolvimento</span>
        </h2>

        <div class="row my-1">
            <div class="col-12 px-md-1">
                <div class="list-group">

                    <a href="noticia.php?id=1" class="list-group-item list-group-item-action">
                        <h3 class="fs-6">Título provisório da notícia</h3>
                        <p><time datetime="2026-01-01">01/01/2026</time> - Nome do autor</p>
                        <p>Resumo provisório da notícia desta categoria.</p>
                    </a>
                    <a href="noticia.php?id=2" class="list-group-item list-group-item-action">
                        <h3 class="fs-6">Outro título provisório</h3>
                        <p><time datetime="2026-01-02">02/01/2026</time> - Nome do autor</p>
                        <p>Outro resumo provisório para demonstrar a listagem.</p>
                    </a>

                </div>
            </div>
        </div>
        
    </article>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
