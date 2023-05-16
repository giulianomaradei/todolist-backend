<?php
require_once(__DIR__."/../class/System.php");


$id_contrato = (int)$_GET['contrato'];
$id_plano = (int)$_GET['id_plano'];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4  col-md-offset-4">

            <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title text-left pull-left">Inserir planos:</h3>
                    </div>
                    <form enctype='multipart/form-data' id="form" action='/api/ajax?class=ImportarCsvPlanosCliente.php' method='POST'>
		                <input type="hidden" name="token" value="<?php echo $request->token ?>">
                        <div class="panel-body" style="padding-bottom: 0;">
                            
                            <div class="col-md-5">
                                                                
                                <input type="hidden" name="id_contrato" value="<?=$id_contrato?>" />
                                <input type="hidden" name="id_plano" value="<?=$id_plano?>" />
                                <div class="form-group">
                                    <input size='50' type='file' id="ImagemUpload" name='filename' />
                                </div>

                            </div>

                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center">
                                        <button class="btn btn-xs btn-success" type="submit"><i class="fa fa-plus"></i> Importar CSV</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
    </div>
</div>

<script>
$(document).on('submit', 'form', function(){
    var value = $('#ImagemUpload').val();
    var values = value.split('.'); 
    if (values.length > 0 && values[values.length - 1] != 'csv'){
      alert ('Formato inválido!');
      return false;
    }
  });
</script>