<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['ordenacao']) && isset($_GET['cancelados'])) {
    $operacao = 'inserir_avulso';
    $cancelados = (int)$_GET['cancelados'];
    $ordenacao = $_GET['ordenacao'];

   
}else{
    header("location: ../adm.php");
    exit;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Inserir Faturamento de Avulso</h3>
                </div>
                <form method="post" action="/api/ajax?class=Faturamento.php" id="inserir_avulso" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="servico" id="servico" value="call_ativo">
                    <input type="hidden" name="cancelados" id="cancelados" value="<?=$cancelados?>">
                    <input type="hidden" name="ordenacao" id="ordenacao" value="<?=$ordenacao?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" required name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa"/>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Dia do Vencimento:</label>
                                    <input name="dia_vencimento" id="dia_vencimento" type="number" class="form-control input-sm" autocomplete="off" required/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Total do Contrato:</label>
                                    <input name="valor_total_contrato" id="valor_total_contrato" type="text" class="form-control input-sm money" autocomplete="off" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Qtd. Contratada:</label>
                                    <input name="qtd_contratada" id="qtd_contratada" type="number" class="form-control input-sm number_int" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Qtd. Efetuada:</label>
                                    <input name="qtd_efetuada" id="qtd_efetuada" type="number" class="form-control input-sm number_int" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Qtd. Excedente:</label>
                                    <input name="qtd_excedente" id="qtd_excedente" type="number" class="form-control input-sm number_int" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Total: <a tabindex="0" data-toggle="tooltip" title="Soma de todos os valores."><i class="fa fa-question-circle"></i></a></label>
                                    <input name="valor_total" id="valor_total" type="text" class="form-control input-sm money" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Cobrança: <a tabindex="0" data-toggle="tooltip" title="Valor total com descontos ou acréscimos."><i class="fa fa-question-circle"></i></a></label>
                                    <input name="valor_cobranca" id="valor_cobranca" type="text" class="form-control input-sm money" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
						</div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="a" name="<?= $operacao; ?>"/>
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

    $(document).on('submit', '#ajustar_faturamento', function () {
        modalAguarde();
    });

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    }) 

    //Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    //Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                            'cod_servico' : 'call_ativo',
                            'pagina' : 'gerenciar-pesquisa-form'
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function(event, ui){
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function(event, ui){
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
            if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

</script>