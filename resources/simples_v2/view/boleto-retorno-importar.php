<?php
require_once(__DIR__."/../class/System.php");
$retorno = 1;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4  col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Importar Retorno:</h3>
                </div>
                <form enctype='multipart/form-data' id="form" action='/api/ajax?class=Boleto.php' method='POST'>
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="col-md-5">
                            <input type="hidden" name="retorno" value="<?=$retorno?>">
                            <div class="form-group">
                                <input size='50' type='file' id="ImagemUpload" name='filename'>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Importar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('submit', 'form', function () {
        var value = $('#ImagemUpload').val();
        var values = value.split('.'); 
        if (!value){
            alert ('Selecione um arquivo válido!');
            return false;
        }
        modalAguarde();
    });
</script>