<?php
require_once(__DIR__."/../class/System.php");

$anotacoes = DBRead('', 'tb_usuario', "WHERE id_usuario = $id_usuario");
?>
<script src="inc/ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - Anotações';
    });
</script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left">Anotações:</h3>
            </div>
            <form method="post" action="/api/ajax?class=Usuario.php" style="margin-bottom: 0;">
                <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control ckeditor" rows="20" name="nota"><?= $anotacoes[0]['anotacoes'];?></textarea>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="inserir_nota" name="inserir_nota">
                            <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>