<?php
require_once(__DIR__."/../class/System.php");

if(isset($_GET['alterar'])){
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $cliente_contrato = 1;
    $dados = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = $id");
    $titulo = $dados[0]['titulo'];
    $status = $dados[0]['status'];
    $observacao = $dados[0]['observacao'];
    $qtd_tentativas_cliente = $dados[0]['qtd_tentativas_cliente'];
    $horas_entre_tentativas = $dados[0]['horas_entre_tentativas'];
    $ramal = $dados[0]['ramal'];
    $data_criacao = $dados[0]['data_criacao'];
    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
    $prazo_termino = $dados[0]['prazo_termino'];

    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

    $contrato = $dados_contrato[0]['nome_pessoa'] . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";

}else{
    $tituloPainel = 'Clonar';
    $operacao = 'inserir';
    $id = 1;
    $titulo = '';
    $status = 1;
    $observacao = '';
    $ramal = '';
    $data_criacao = '';
    $cliente_contrato = 0;
    $prazo_termino = '';
}
?>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> pesquisa:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Pesquisa.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="class/PesquisaClonar.php" id="pesquisa_form" style="margin-bottom: 0;">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" required name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Título:</label>
                                    <input name="titulo" id="titulo" type="text" autofocus class="form-control input-sm" value="<?= $titulo; ?>" autocomplete="off" required>
                                </div>
                            </div>   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select id="status" class="form-control input-sm" name="status" required>
                                        <option value='0' <?php if ($status == 0) {echo 'selected';}?>>Concluido</option>
                                        <option value='1' <?php if ($status == 1) {echo 'selected';}?>>Ativo</option>
                                        <option value='3' <?php if ($status == 3) {echo 'selected';}?>>Pausado</option>
                                    </select>
                                </div>
                            </div>                  
                        </div>
                        <div class="row">
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ramal:</label>
                                    <input name="ramal" id="ramal" type="number" autofocus class="form-control input-sm number_int" value="<?= $ramal; ?>" autocomplete="off">
                                </div>
                            </div> 
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Quantidades de tentativas por cliente:</label>
                                    <input name="qtd_tentativas_cliente" id="qtd_tentativas_cliente" type="number" autofocus class="form-control input-sm number_int" value="<?= $qtd_tentativas_cliente; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Horas entre tentativas:</label>
                                    <input name="horas_entre_tentativas" id="horas_entre_tentativas" type="number" autofocus class="form-control input-sm number_int" value="<?= $horas_entre_tentativas; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Prazo de Término:</label>
                                    <input name="prazo_termino" id="prazo_termino" type="text" class="form-control input-sm date calendar"  value="<?= $prazo_termino; ?>" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                
                                <div class="form-group">
                                    <label>*Clonar Perguntas e Respostas de:</label>
                                        <select class="form-control input-sm" name="clone_pesquisa" id="clone_pesquisa">
                                            <?php
                                            $pesquisas = DBRead('', 'tb_pesquisa a',"INNER JOIN tb_contrato_plano_pessoa b  ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE a.status != 2");

                                            //$empresa = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$pesquisa['id_contrato_plano_pessoa']."'");

                                                //$sel_tipo[$id_tipo_resposta_pesquisa] = 'selected';
                                                foreach($pesquisas as $pesquisa){
                                                    $empresa = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$pesquisa['id_contrato_plano_pessoa']."'");
                                                    if($pesquisa['nome_contrato']){
                                                        $descricao = $empresa[0]['nome']." (".$pesquisa['nome_contrato'].") - ".$pesquisa['titulo'];
                                                    }else{
                                                        $descricao = $empresa[0]['nome']." - ".$pesquisa['titulo'];
                                                    }
                                                    echo "<option value='".$pesquisa['id_pesquisa']."'>".$descricao."</option>";
                                                }
                                            ?>
                                        </select>
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Contatos:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input type="checkbox" name="clona_contato" id="clona_contato" value="1">
                                        </span>
                                        <input type="text" class="form-control mensagem" aria-label="..." disabled value="Clonar" style="cursor: context-menu; background-color: white;">
                                        </div><!-- /input-group -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observação:</label>
                                    <textarea rows="12" cols="50" class="form-control ckeditor conteudo" id="observacao" name="observacao"><?= $observacao ?></textarea>
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
<script>

    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                            'cod_servico' : 'call_ativo',
                            'pagina': 'gerenciar-pesquisa-form-clonar'
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
    function carregarDadosContrato(id) {
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
                success: function (data) {
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato() {
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function () {
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    $(document).on('submit', '#pesquisa_form', function(){
        var titulo = $('#titulo').val();
        var status = $('#status').val();
        var ramal = $('#ramal').val();
        var qtd_tentativas_cliente = $('#qtd_tentativas_cliente').val();
        var horas_entre_tentativas = $('#horas_entre_tentativas').val();
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
        if(!titulo){
            alert("Deve-se descrever um título!");
            return false;
        }
        if(!status){
            alert("Deve-se selecionar um status!");
            return false;
        }
        if(!id_contrato_plano_pessoa){
            alert("Deve-se selecionar um cliente válido!");
            return false;
        }
        if(!qtd_tentativas_cliente && qtd_tentativas_cliente > 0){
            alert("Deve-se incluir uma quantidade de tentativas válida!");
            return false;
        }
        if(!horas_entre_tentativas){
            alert("Deve-se incluir horas entre tentativas!");
            return false;
        }
        modalAguarde();
    });
</script>