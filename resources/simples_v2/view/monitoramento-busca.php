<?php
require_once(__DIR__."/../class/System.php");

?>
<style>
    .panel-body{
        background-color: #f2f2f2;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Monitoramento:</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="input-group">
                                  <input type="text" class="form-control" autocomplete="off" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome da empresa..." aria-describedby="basic-addon2">
                                  <span class="input-group-addon" id="basic-addon2"><i class="fa fa-search" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>                    
                    <hr>
                    <div id="conteudo"></div>
                </div>
            </div>
        </div>
    </div>
</div>  
<script>
function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        var parametros = {
            'nome': nome
        };
        busca_ajax('<?= $request->token ?>' , 'MonitoramentoBusca', 'conteudo', parametros);
    }
    call_busca_ajax();
</script>