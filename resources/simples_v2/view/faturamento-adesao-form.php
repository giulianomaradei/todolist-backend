<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['ordenacao']) && isset($_GET['cancelados'])) {
    $operacao = 'inserir_adesao';
    $cancelados = (int)$_GET['cancelados'];
    $ordenacao = $_GET['ordenacao'];

}else{
    header("location: ../adm.php");
    exit;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Inserir Faturamento de Adesão</h3>
                </div>
                <form method="post" action="/api/ajax?class=Faturamento.php" id="adesao" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="servico" id="servico" value="adesao">
                    <input type="hidden" name="cancelados" id="cancelados" value="<?=$cancelados?>">
                    <input type="hidden" name="ordenacao" id="ordenacao" value="<?=$ordenacao?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <select name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" class="form-control input-sm">
                                        <option value="">Selecione um Contrato...</option>
                                        <?php

                                        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.status = '5' ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.nome_contrato, a.id_pessoa, b.nome, b.cpf_cnpj, b.razao_social, c.nome AS 'plano', c.cod_servico");

                                        if ($dados_contrato) {
                                            foreach ($dados_contrato as $conteudo_contrato) {
                                                $dados_contrato_faturamento = DBRead('', 'tb_faturamento_contrato a', "INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento WHERE a.id_contrato_plano_pessoa = '".$conteudo_contrato['id_contrato_plano_pessoa']."' AND b.adesao = '1' ", "a.id_contrato_plano_pessoa");
                                                if(!$dados_contrato_faturamento){
                                                    echo "<option value='".$conteudo_contrato['id_contrato_plano_pessoa']."'>".$conteudo_contrato['nome']." - ".getNomeServico($conteudo_contrato['cod_servico'])." - ".$conteudo_contrato['plano']." (".$conteudo_contrato['id_contrato_plano_pessoa'].")</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Dia do Vencimento: </label>
                                    <input name="dia_pagamento_adesao" id="dia_pagamento_adesao" type="text" class="form-control input-sm date calendar" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Valor da Adesão: </label>
                                    <input name="valor_adesao" id="valor_adesao" type="text" class="form-control input-sm money" autocomplete="off" required>
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

    $(document).on('submit', '#adesao', function () {
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == ''){
            alert('Deve-se selecionar o contrato!');
            return false;
        }
        modalAguarde();
    });

    // //Atribui evento e função para limpeza dos campos
        // $('#busca_contrato').on('input', limpaCamposContrato);
        // //Dispara o Autocomplete da pessoa a partir do segundo caracter
        // $("#busca_contrato").autocomplete({
        //         minLength: 2,
        //         source: function(request, response){
        //             $.ajax({
        //                 url: "class/ContratoAutocomplete.php",
        //                 dataType: "json",
        //                 data: {
        //                     acao: 'autocomplete',
        //                     parametros: { 
        //                         'nome' : $('#busca_contrato').val(),
        //                         'cod_servico' : 'call_ativo',
        //                         'pagina' : 'gerenciar-pesquisa-form'
        //                     }
        //                 },
        //                 success: function(data){
        //                     response(data);
        //                 }
        //             });
        //         },
        //         focus: function(event, ui){
        //             $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
        //             carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
        //             return false;
        //         },
        //         select: function(event, ui){
        //             $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
        //             $('#busca_contrato').attr("readonly", true);
        //             return false;
        //         }
        //     })
        //     .autocomplete("instance")._renderItem = function(ul, item){
        //         if(!item.razao_social){
        //             item.razao_social = '';
        //         }
        //         if(!item.cpf_cnpj){
        //             item.cpf_cnpj = '';
        //         }
        //         if(!item.nome_contrato){
        //             item.nome_contrato = '';
        //         }else{
        //             item.nome_contrato = ' ('+item.nome_contrato+') '; 
        //         }
        //         return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        //     };
        // // Função para carregar os dados da consulta nos respectivos campos
        // function carregarDadosContrato(id){
        //     var busca = $('#busca_contrato').val();
        //     if(busca != "" && busca.length >= 2){
        //         $.ajax({
        //             url: "class/ContratoAutocomplete.php",
        //             dataType: "json",
        //             data: {
        //                 acao: 'consulta',
        //                 parametros: {
        //                     'id' : id,
        //                 }
        //             },
        //             success: function(data){
        //                 $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
        //             }
        //         });
        //     }
        // }
        // // Função para limpar os campos caso a busca esteja vazia
        // function limpaCamposContrato(){
        //     var busca = $('#busca_contrato').val();
        //     if (busca == "") {
        //         $('#id_contrato_plano_pessoa').val('');
        //     }
        // }
        
        // $(document).on('click', '#habilita_busca_contrato', function(){
        //     $('#id_contrato_plano_pessoa').val('');
        //     $('#busca_contrato').val('');
        //     $('#busca_contrato').attr("readonly", false);
        //     $('#busca_contrato').focus();
    // });

</script>