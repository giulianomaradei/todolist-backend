<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_email_modelo', "WHERE id_email_modelo = $id");
    $titulo = $dados[0]['titulo'];
    $assunto = $dados[0]['assunto'];
    $descricao = $dados[0]['descricao'];
    $status = $dados[0]['status'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $titulo = '';
    $assunto = '';
    $descricao = '';
    $status = '1';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> modelo de E-mail:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=EmailModelo.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=EmailModelo.php" id="email_modelo_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
   
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>*Título:</label>
                                    <input name="titulo" type="text" autofocus class="form-control input-sm" value="<?= $titulo; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select class="form-control input-sm" name="status" required>
                                        <option value='1' <?php if ($status == 1) {echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == 0) {echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Assunto:</label>
                                    <input name="assunto" type="text" autofocus class="form-control input-sm" value="<?= $assunto; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição:</label>
                                    <textarea class="form-control " name="descricao" style="resize: vertical; height: 200px;"><?=$descricao?></textarea>
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
    $(document).on('submit', '#email_modelo_form', function () {
        modalAguarde();
    });
</script>