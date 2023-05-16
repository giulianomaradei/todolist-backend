<?php
require_once(__DIR__."/../class/System.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Alertas - Painel do Cliente:</h3>
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
                                <label>Serviço:</label>
                                <select class="form-control input-sm" name="plano" id="plano" onchange="call_busca_ajax();">
									<option></option>
	                                <?php
                                        $dados = DBRead('', 'tb_plano', "WHERE status = 1 ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $id_plano = $conteudo['id_plano'];
                                                $nome_servico = getNomeServico($conteudo['cod_servico'])." - ".$conteudo['nome'];
                                                echo "<option value='$id_plano'>$nome_servico</option>";
                                            }
                                        }
                                    ?>                             
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Status:</label>
                                <select class="form-control input-sm" name="situacao" id="situacao" onchange="call_busca_ajax();">
                                    <option></option>
                                    <option value="2">Aprovado</option>
                                    <option value="3">Descartado</option>
                                    <option value="1">Pendente</option>
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
        var plano = $('#plano').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'plano': plano,
            'situacao': situacao,
            'data_de': data_de,
            'data_ate': data_ate,
        };
        busca_ajax('<?= $request->token ?>' , 'AlertaPainelBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>