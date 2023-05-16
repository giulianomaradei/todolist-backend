<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Caixas:</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=caixa-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Status:</label>
                                <select class="form-control" name="situacao" id="situacao" onchange="call_busca_ajax();">
	                                <option value="">Todos</option>
	                                <option value="1">Ativo</option>
	                                <option value="0">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });

    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var situacao = $('#situacao').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'situacao': situacao,
        };
        busca_ajax('<?= $request->token ?>' , 'CaixaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>