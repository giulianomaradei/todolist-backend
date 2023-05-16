<?php
require_once(__DIR__."/../class/System.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Boletos:</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-sincronizar-busca"><button class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i> Sincronização Bancária</button></a>                        
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-remessa-busca"><button class="btn btn-xs btn-primary"><i class="fa fa-mail-forward" aria-hidden="true"></i> Remessas</button></a>
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-retorno-importar"><button class="btn btn-xs btn-primary"><i class="fa fa-mail-reply" aria-hidden="true"></i> Retornos</button></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Situação:</label>
                                <select class="form-control input-sm" name="situacao" id="situacao" onchange="call_busca_ajax();">
									<option></option>
	                                <option value="alteracao vencimento pendente">Alteração de Vencimento Pendente</option>
	                                <option value="baixado">Baixado</option>
	                                <option value="baixa pendente">Baixa Pendente</option>
	                                <option value="emitido">Emitido</option>
	                                <option value="falha">Falha</option>
	                                <option value="liquidado">Liquidado</option>
	                                <option value="registrado">Registrado</option>
                                    <option value="rejeitado">Rejeitado</option>   
                                    <option value="salvo">Salvo</option>                                        
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data de:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" type="text" name="data_de" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data até:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" type="text" name="data_ate" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Buscar Datas Por:</label>
                                <select class="form-control input-sm" name="select_data" id="select_data" onchange="call_busca_ajax();">
                                    <option value="sincronizacao">Data de Sincronização</option>
                                    <option value="vencimento">Data de Vencimento</option>
                                    <option value="pagamento">Data de Pagamento</option>
                                    <option value="emissao">Data de Emissão</option>
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
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();
        var faturamento = $('#faturamento').val();
        var select_data = $('#select_data').val();

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
            'data_de': data_de,
            'data_ate': data_ate,
            'faturamento': faturamento,
            'select_data': select_data
        };
        busca_ajax('<?= $request->token ?>' , 'BoletoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>