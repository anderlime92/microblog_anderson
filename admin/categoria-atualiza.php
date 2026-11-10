<?php 
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Categoria;
use Microblog\Services\CategoriaServico;

require_once __DIR__ . '/../vendor/autoload.php'; 

$mensagemErro = "";
$dadosCategoria = null;
$categoriaServico = null;

$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($id);

try {
    $categoriaServico = new CategoriaServico();
    $dadosCategoria = $categoriaServico->buscarPorId($id);
} catch(Throwable $e){
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar os dados da categoria";
}

if($dadosCategoria === null && $mensagemErro === ""){
    Utils::alertaErro("Categoria não encontrada!");
}

$nome = $dadosCategoria['nome'] ?? "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    try {
        $nome = Utils::normalizarEntrada($_POST['nome'] ?? "");
        Validacoes::validarNome($nome);

        $categoria = new Categoria($nome, $id);
        $categoriaServico->atualizar($categoria);

        header("location:categorias.php");
        exit;
    } catch (InvalidArgumentException $e) { 
        $mensagemErro = $e->getMessage();
    } catch (Throwable $e){ 
        Utils::registrarLog($e);
        $mensagemErro = "Não foi possível atualizar a categoria. Tente novamente";
    }
}


require_once __DIR__ . '/../includes/cabecalho-admin.php'; 
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Atualizar dados da categoria</h2>

        <?php if($mensagemErro !== ""): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">
            <div class="mb-3">
                <label class="form-label" for="nome">Nome:</label>
                <input value="<?= Utils::escaparHtml($nome) ?>" class="form-control" type="text" id="nome" name="nome">
            </div>
            <button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>