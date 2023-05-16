<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_painel_novidades', "WHERE id_painel_novidades = $id");
   
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
}
?>

<link href='inc/ckeditor/css/select2.min.css' />
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> novidade:</h3>
            </div>
            <form method="post" action="/api/ajax?class=PainelClienteNovidade.php" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea name="descricao" id="descricao" class="form-control ckeditor" required><?=$dados[0]['descricao']?></textarea>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Data produção:</label>
                                <input class="form-control input-sm date calendar hasDatepicker" name="data" value="<?=converteData($dados[0]['data'])?>" required>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?=$operacao?>">
                            <button class="btn btn-primary" name="salvar" id="ok" type="submit">
                                <i class="fa fa-floppy-o"></i> Salvar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>  
    CKEDITOR.replace('descricao',  {
        height: 315
    });
</script>