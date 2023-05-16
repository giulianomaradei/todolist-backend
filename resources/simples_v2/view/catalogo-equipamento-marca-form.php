<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_catalogo_equipamento_marca', "WHERE id_catalogo_equipamento_marca = $id");
    $nome = $dados[0]['nome'];

}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Catálogo de equipamentos - Marca - <?= $tituloPainel ?> </h3>
                    <?php 
                    $dados = DBRead('', 'tb_catalogo_equipamento_qi a', "INNER JOIN tb_catalogo_equipamento b ON a.id_catalogo_equipamento = b.id_catalogo_equipamento INNER JOIN tb_catalogo_equipamento_marca c ON b.id_catalogo_equipamento_marca = c.id_catalogo_equipamento_marca WHERE c.id_catalogo_equipamento_marca = '".$id."' ");

                    if (isset($_GET['alterar']) && !$dados) {
                         echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=CatalogoEquipamentoMarca.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir a marca de equipamento?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } 
                    
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=CatalogoEquipamentoMarca.php" id="catalogo_equipamento_marca_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Marca:</label>
                                    <input name="nome" id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>     
<script>
    $(document).on('submit', '#catalogo_equipamento_marca_form', function () {
        var nome = $("#nome").val();
       
        if(!nome || nome == ""){
            alert("Deve-se descrever a marca!");
            return false;
        }
        modalAguarde();
    });
</script>