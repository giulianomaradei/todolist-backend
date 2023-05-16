<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_acrescimo_desconto', "WHERE id_acrescimo_desconto = $id");

    $id_contrato = $dados[0]['id_contrato_plano_pessoa'];

    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

    if($dados_contrato[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
    }

    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";


    $tipo = $dados[0]['tipo'];

    $data = $dados[0]['data_referencia'];
    $data = explode('-', $data);
    $mes_referencia = $data[1];
    $ano_referencia = $data[0];

    $valor = converteMoeda($dados[0]['valor']);
    $descricao = $dados[0]['descricao'];


}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';

    $tipo = '';

    $data = getDataHora("data");
    $data = explode('-', $data);
    $mes_referencia = $data[1];
    $ano_referencia = $data[0];

    $valor = '';
    $descricao = '';

}

$dados_meses = array(
	"01" => "Janeiro",
	"02" => "Fevereiro",
	"03" => "Março",
	"04" => "Abril",
	"05" => "Maio",
	"06" => "Junho",
	"07" => "Julho",
	"08" => "Agosto",
	"09" => "Setembro",
	"10" => "Outubro",
	"11" => "Novembro",
	"12" => "Dezembro",
);

$dados_anos = array(
	"2022" => "2022",
	"2023" => "2023",
	"2024" => "2024",
	"2025" => "2025",
	"2026" => "2026"
);


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Acréscimo/Desconto:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=AcrescimoDesconto.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=AcrescimoDesconto.php" id="acrescimo_desconto_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" placeholder="Informe o nome ou CNPJ..." autocomplete="off" value="<?=$contrato?>" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" required name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value='<?=$id_contrato?>'/>

                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label>*Tipo:</label>
                                    <select class="form-control input-sm" id="tipo" name="tipo">
                                        <option value="" <?php if($tipo == ''){echo 'selected';}?>>Selecione o tipo...</option>
                                        <option value="acrescimo" <?php if($tipo == 'acrescimo'){echo 'selected';}?>>Acréscimo</option>
                                        <option value="desconto" <?php if($tipo == 'desconto'){echo 'selected';}?>>Desconto</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label>*Valor:</label>
                                    <input name="valor" type="text" id="valor" class="form-control input-sm money" value="<?=$valor;?>" autocomplete="off">

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label>*Mês de Referência:</label>
                                    <select class="form-control input-sm" id="mes_referencia" name="mes_referencia">
                                    <?php
                                        foreach ($dados_meses as $key => $conteudo_meses) {
                                            $selected = $mes_referencia == $key ? "selected" : "";
                                            echo "<option value='".$key."'".$selected.">".$conteudo_meses."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label>*Ano de Referência:</label>
                                    <select class="form-control input-sm" id="ano_referencia" name="ano_referencia">
                                    <?php
                                        foreach ($dados_anos as $key => $conteudo_anos) {
                                            $selected = $ano_referencia == $key ? "selected" : "";
                                            echo "<option value='".$key."'".$selected.">".$conteudo_anos."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">                                    
                                    <label>*Referente a:</label>
                                    <textarea name="descricao" id="descricao" type="text" class="form-control input-sm" rows="5"><?=$descricao?></textarea>
                                </div>
                            </div>

						</div>                   
                    </div>
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>     
<script>
    $(document).on('submit', '#acrescimo_desconto_form', function () {
        // var nome = $("#nome").val();
        
        // if(!$('#exibe_topico').is(':checked') && !$('#exibe_chamado').is(':checked') && !$('#exibe_alerta').is(':checked')){
        //     alert("Selecione pelo menos uma opção de exibição");
        //     return false;
        // }

        // if(!nome || nome == ""){
        //     alert("Deve-se descrever um nome!");
        //     return false;
        // }
        modalAguarde();
    });

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
                            'pagina' : 'acrescimo_desconto'
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