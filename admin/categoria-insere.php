<?php 
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Categoria;
use Microblog\Services\CategoriaServico;

require_once __DIR__ . '/../vendor/autoload.php';

$mensagemErro = "";
$nome = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $nome = Utils::normalizarEntrada($_POST['nome'] ?? '');
        Validacoes::validarNome($nome);

        $categoria = new Categoria($nome);
        $categoriaServico = new CategoriaServico();
        $categoriaServico->inserir($categoria);

        header("location:categorias.php");
        exit;
    } catch (InvalidArgumentException $e) {
        $mensagemErro = $e->getMessage();
    } catch (Throwable $e){
        Utils::registrarLog($e);
        $mensagemErro = "Não foi possível cadastrar a categoria. Tente novamente.";
    }
}

require_once __DIR__ . '/../includes/cabecalho-admin.php'; 
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Inserir nova categoria</h2>

        <?php if($mensagemErro !== ""): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <form class="mx-auto w-75" action="" method="post" id="form-inserir" name="form-inserir">
            <div class="mb-3">
                <label class="form-label" for="nome">Nome:</label>
                <input class="form-control" type="text" id="nome" name="nome" 
                value="<?= Utils::escaparHtml($nome) ?>">
            </div>
            <button class="btn btn-primary" id="inserir" name="inserir"><i class="bi bi-save"></i> Inserir</button>
        </form>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>