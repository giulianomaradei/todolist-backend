<?php
require_once(__DIR__."/../class/System.php");

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Logs:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Tabela Alterada:</label>
                                <input class="form-control input-sm" type="text" name="tb_alterada" id="tb_alterada" placeholder="Ex: tb_usuario" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>ID da Tabela Alterada:</label>
                                <input class="form-control input-sm" type="number" name="id_tb_alterada" id="id_tb_alterada" placeholder="Ex: 112540" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Tipo de Operação:</label>
                                <select class="form-control input-sm" name="tipo_operacao" id="tipo_operacao">
	                                <option value="">Qualquer</option>
	                                <option value="i">Inserção</option>
	                                <option value="a">Alteração</option>
	                                <option value="e">Exclusão</option>
	                                <option value="la">Login Aceito</option>
	                                <option value="ln">Login Negado</option>
	                                <option value="loa">Logout Aceito</option>
	                                <option value="rel">Rlatório</option>
	                                <!-- <option value="email">Envio de E-mail</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Campo Alterado:</label>
                                <input class="form-control input-sm" type="text" name="campo_alterado" id="campo_alterado" placeholder="Ex: id_pessoa" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Valor Campo Alterado:</label>
                                <input class="form-control input-sm" type="text" name="valor_campo_alterado" id="valor_campo_alterado" placeholder="Ex: 1498" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Data:</label>
                                <input class="form-control input-sm date calendar" type="text" name="data" id="data" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Sistema:</label>
                                <select class="form-control input-sm" name="sistema" id="sistema">
	                                <option value="">Qualquer</option>
	                                <option value="simples">Simples V2</option>
	                                <option value="painel">Painel do Cliente</option>
	                                <option value="painel_rh">Painel RH</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Limitador: <i class="fa fa-info-circle" aria-hidden="true" data-toggle='popover' data-html='true' data-placement='right' data-trigger='focus' title='' data-content='Ao deixar este campo vazio será considerado como LIMIT 100!' aria-hidden='true'></i></label>
                                <input class="form-control input-sm" type="number" name="limitador" id="limitador" placeholder="Ex: 10" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Ordenação:</label>
                                <select class="form-control input-sm" name="ordenacao" id="ordenacao">
	                                <option value="ASC">ASC</option>
	                                <option value="DESC">DESC</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1 text-center">
                            <div class="form-group has-feedback">
                                <label>&nbsp;</label>
                                <br>
                                <button class="btn btn-sm btn-primary" id="gerar"><i class="fas fa-check"></i> Gerar</button>

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

    $(function(){
        $('[data-toggle="popover"]').popover({ trigger: "hover", container: "body" });
    });

    $(document).on('click', '#gerar', function () {
        
        var campo_alterado = $('#campo_alterado').val();
        var valor_campo_alterado = $('#valor_campo_alterado').val();

        if(campo_alterado && !valor_campo_alterado){
            alert("Deve-se inserir o valor do campo alterado, não apenas o campo alterado!");
            return false;
        }else if(!campo_alterado && valor_campo_alterado){
            alert("Deve-se inserir o campo alterado, não apenas o valor do campo alterado!");
            return false;
        }else{
            call_busca_ajax();
        }
    });

    function call_busca_ajax(){
        var tb_alterada = $('#tb_alterada').val();
        var id_tb_alterada = $('#id_tb_alterada').val();
        var tipo_operacao = $('#tipo_operacao').val();
        var campo_alterado = $('#campo_alterado').val();
        var valor_campo_alterado = $('#valor_campo_alterado').val();
        var data = $('input[name = "data"]').val();
        var sistema = $('#sistema').val();
        var limitador = $('#limitador').val();
        var ordenacao = $('#ordenacao').val();

        
        var parametros = {
            'tb_alterada': tb_alterada,
            'id_tb_alterada': id_tb_alterada,
            'tipo_operacao': tipo_operacao,
            'campo_alterado': campo_alterado,
            'valor_campo_alterado': valor_campo_alterado,
            'data': data,
            'sistema': sistema,
            'limitador': limitador,
            'ordenacao': ordenacao,
        };
        busca_ajax('<?= $request->token ?>' , 'LogBusca', 'resultado_busca', parametros);
    }


</script>