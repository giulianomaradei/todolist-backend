<?php
require_once(__DIR__."/../class/System.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">NFS-e:</h3>                    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Status:</label>
                                <select class="form-control" name="status" id="status" onchange="call_busca_ajax();">
									<option></option>
	                                <option value="autorizada">Autorizada</option>
	                                <option value="cancelada">Cancelada</option>
	                                <option value="cancelando">Cancelando</option>
	                                <option value="inserindo">Inserindo</option>
	                                <option value="nao enviado">Não Enviada</option>
	                                <option value="negada">Negada</option>    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data de:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_de" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data até:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_ate" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
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
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var status = $('#status').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();
        var faturamento = $('#faturamento').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'status': status,
            'data_de': data_de,
            'data_ate': data_ate,
            'faturamento': faturamento
        };
        busca_ajax('<?= $request->token ?>' , 'NfsBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>