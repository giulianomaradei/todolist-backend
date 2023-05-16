<?php
require_once(__DIR__."/../class/System.php");


if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_chamado_script', "WHERE id_chamado_script = $id");
    $id_categoria = $dados[0]['id_categoria'];
    $descricao = $dados[0]['descricao'];
    
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $id_categoria = '';
    $descricao = '';

}
?>

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> script:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=ChamadoScript.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=ChamadoScript.php" id="chamado_script_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label>*Categoria:</label>
                                    <select class="form-control" id="id_categoria" name="id_categoria" required>
                                        <option value=""></option>
                                        <?php
                                            $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_topico = 1 ORDER BY nome ASC");
                                            if($dados_categoria){
                                                foreach($dados_categoria as $categoria){
                                                    $idCategoria = $categoria['id_categoria'];
                                                    $nomeSelect = $categoria['nome'];
                                                    $selected = $id_categoria == $idCategoria ? "selected" : "";
                                                    echo "<option value='$idCategoria'".$selected.">$nomeSelect</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label>*Texto a ser inserido no chamado:</label>
                                        <textarea rows="12" cols="50" required name="descricao" class="form-control ckeditor conteudo" id="conteudo"><?= $descricao ?></textarea>
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
<link href='inc/ckeditor/css/select2.min.css' />
<script src="inc/ckeditor/ckeditor.js"></script>     
<script>

    CKEDITOR.replace('descricao', {
        height: 315
    });

    $(document).on('submit', '#topico_form', function(){
        var id_categoria = $("#id_categoria").val();
        var conteudo = $(".conteudo").val();

        if(!id_categoria || id_categoria == ''){
            alert("Deve-se inserir uma categoria válida");
            return false;
        }
        if(!conteudo){
            alert("Deve-se inserir um conteúdo válido");
            return false;
        }
        modalAguarde();
    });
</script>