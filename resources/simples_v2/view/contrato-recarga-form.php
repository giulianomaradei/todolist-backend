<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];

    $dados = DBRead('', 'tb_contrato_recarga', "WHERE id_contrato_recarga = $id");
    $quantidade_atendimentos = $dados[0]['quantidade_atendimentos'];
    $data_recarga = converteData($dados[0]['data_recarga']);
    $contrato = getContrato($dados[0]['id_contrato_plano_pessoa']);
    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];

} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$quantidade_atendimentos = '';
    $data_recarga = '';
    $contrato = '';
    $id_contrato_plano_pessoa = '';
}
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> recarga pré-paga:</h3>
                <?php if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=ContratoRecarga.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
            </div>
            <form method="post" action="/api/ajax?class=ContratoRecarga.php" id="recarga_form" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label id='id_contrato'>Contrato (cliente):</label>
                                <div class="input-group">
                                    <input class="form-control input-sm ui-autocomplete-input" id="busca_contrato" type="text" name="busca_contrato" value="<?= $contrato ?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="">

                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?= $id_contrato_plano_pessoa ?>">
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class="form-group">
                                <label>*Quantidade de atendimentos:</label>
                                <select class="form-control input-sm" name="quantidade_atendimentos" id="quantidade_atendimentos" required>
                                    <?php 
                                        $sel_qtd_atendimentos[$quantidade_atendimentos] = 'selected';
                                    ?>
                                    <option value="0" <?= $sel_qtd_atendimentos[''] ?>>Selecione</option>
                                    <option value="10" <?= $sel_qtd_atendimentos[10] ?>>10 atendimentos</option>
                                    <option value="20" <?= $sel_qtd_atendimentos[20] ?>>20 atendimentos</option>
                                    <option value="30" <?= $sel_qtd_atendimentos[30] ?>>30 atendimentos</option>
                                    <option value="40" <?= $sel_qtd_atendimentos[40] ?>>40 atendimentos</option>
                                    <option value="50" <?= $sel_qtd_atendimentos[50] ?>>50 atendimentos</option>
                                    <option value="60" <?= $sel_qtd_atendimentos[60] ?>>60 atendimentos</option>
                                    <option value="70" <?= $sel_qtd_atendimentos[70] ?>>70 atendimentos</option>
                                    <option value="80" <?= $sel_qtd_atendimentos[80] ?>>80 atendimentos</option>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class="form-group">
                                <label>*Data da recarga:</label>
                                <input name="data_recarga" id="data_recarga" type="text" class="form-control data_recarga input-sm date calendar" value="<?=$data_recarga;?>" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
                            <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocompleteresponsavelcontrato',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                            'pagina' : 'chamado-form'
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);

                $("#responsaveis").html('Responsável pelo Relacionamento: '+ui.item.nome_responsavel+'<br>Responsável Técnico: '+ui.item.nome_responsavel_tecnico);

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
        return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+"</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
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
        if(busca == ""){
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    $(document).on('submit', '#recarga_form', function () {
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        var quantidade_atendimentos = $("#quantidade_atendimentos").val();
        var data_recarga = $('input[name="data_recarga"]').val();

        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == ""){
            alert("Informe um contrato!");
            return false;
        }

        if(!quantidade_atendimentos || quantidade_atendimentos == 0){
            alert("Informe a quantidade de atendimentos!");
            return false;
        }

        if(!data_recarga || data_recarga == ""){
            alert("Informe a data da recarga!");
            return false;
        }

        modalAguarde();
    });
</script>