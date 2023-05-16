<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_lead_tag', "WHERE id_lead_tag = $id");
    $descricao = $dados[0]['descricao'];
   
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $descricao = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> tag:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=LeadTag.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=LeadTag.php" id="lead_tag_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição da tag:</label>
                                    <input name="descricao" autofocus type="text" class="form-control input-sm" value="<?= $descricao; ?>" autocomplete="off" required/>
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
<script>
    $(document).on('submit', '#lead_tag_form', function () {
        modalAguarde();
    });
</script>