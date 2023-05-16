<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_vinculo_tipo', "WHERE id_vinculo_tipo = $id");
    $nome = $dados[0]['nome'];
    $exibe_painel = $dados[0]['exibe_painel'];
    $descricao = $dados[0]['descricao'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $exibe_painel = '';
    $descricao = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> tipo de vínculo:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=VinculoTipo.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=VinculoTipo.php" id="vinculo_tipo_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" type="text" autofocus class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Exibe no painel:</label>
                                    <select class="form-control input-sm" name="exibe_painel" required>
                                        <option value='0' <?php if ($exibe_painel == 0) {echo 'selected';}?>>Não</option>
                                        <option value='1' <?php if ($exibe_painel == 1) {echo 'selected';}?>>Sim</option>
                                    </select>
                                </div>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição:</label>
                                    <textarea class="form-control " name="descricao" style="resize: vertical; height: 100px;"><?=$descricao?></textarea>
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
    $(document).on('submit', '#vinculo_tipo_form', function () {
        modalAguarde();
    });
</script>