<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['clonar'])) {
    $tituloPainel = 'Clonar';
    $operacao = 'clonar';
    $id = (int)$_GET['clonar'];
    $id_inicio = (isset($_GET['id_inicio'])) ? $_GET['id_inicio'] : 1;
    $nivel_limite = (isset($_GET['nivel_limite'])) ? $_GET['nivel_limite'] : 1;
    $contrato_select = (isset($_GET['contrato_select'])) ? $_GET['contrato_select'] : '';
}else if(isset($_GET['mover'])){
    $tituloPainel = 'Mover';
    $operacao = 'mover';
    $id = (int)$_GET['mover'];
    $id_inicio = (isset($_GET['id_inicio'])) ? $_GET['id_inicio'] : 1;
    $nivel_limite = (isset($_GET['nivel_limite'])) ? $_GET['nivel_limite'] : 1;
    $contrato_select = (isset($_GET['contrato_select'])) ? $_GET['contrato_select'] : '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> passo <?= $id ?>:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Arvore.php" id="pergunta_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>ID de destino:</label>
                                    <input name="id_destino" type="text" class="form-control input-sm" autocomplete="off" autofocus required>
                                </div>
                            </div>                     
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <input type="hidden" value="<?= $id_inicio; ?>" name="id_inicio"/>
                                <input type="hidden" value="<?= $nivel_limite; ?>" name="nivel_limite"/>
                                <input type="hidden" value="<?= $contrato_select; ?>" name="contrato_select"/>
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
    $(document).on('submit', '#pergunta_form', function () {
        modalAguarde();
    });
</script>