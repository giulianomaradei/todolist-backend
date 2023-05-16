<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_erro_atendimento', "WHERE id_erro_atendimento = $id");
    $id_tipo_erro = $dados[0]['id_tipo_erro'];
    $assinante = $dados[0]['assinante'];
    $protocolo = $dados[0]['protocolo'];
    $data_erro = $dados[0]['data_erro'];
    $hora_erro = $dados[0]['hora_erro'];
    $descricao_cliente = $dados[0]['descricao_cliente'];
    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
    $id_usuario = $dados[0]['id_usuario'];
    $origem = $dados[0]['origem'];
    $canal_atendimento = $dados[0]['canal_atendimento'];
    
    $atendente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = ".$id_usuario);
    $id_usuario = $atendente[0]['id_usuario'];
    $nome_usuario = $atendente[0]['nome'];

    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

    if($dados_contrato[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
    }

    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $id_tipo_erro = '';
    $protocolo = '';
    $data_erro = '0000-00-00';
    $hora_erro = '00:00:00';
    $assinante = '';
    $descricao_cliente = '';
    $id_contrato_plano_pessoa = '';
    $id_usuario = '';
    $nome_usuario = '';
    $origem = '';
    $canal_atendimento = '';
}
?>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> erro/reclamação:</h3>
                    <?php 
                    $tem_justificativa = DBRead('', 'tb_erro_atendimento', "WHERE justificativa IS NULL AND id_erro_atendimento = '".$id."'");
                    if($tem_justificativa){
                        if(isset($_GET['alterar'])){ echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=ErroAtendimento.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; }
                    }
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=ErroAtendimento.php" id="erro_atendimento_cadastro_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm cliente" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Pessoa (funcionário):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm funcionario" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_usuario;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$id_usuario;?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Origem:</label>
                                    <select name="origem" id="origem" class="form-control input-sm">
                                        <option value=""  <?php if($origem == ''){echo 'selected';}?>>Selecione</option>
                                        <option value="2" <?php if($origem == '2'){echo 'selected';}?>>Belluno</option>
                                        <option value="1" <?php if($origem == '1'){echo 'selected';}?>>Cliente</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Assinante:</label>
                                    <input name="assinante" type="text" class="form-control input-sm" value="<?= $assinante; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Protocolo:</label>
                                    <input name="protocolo" type="text" class="form-control input-sm" value="<?= $protocolo; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Canal de atendimento:</label>
                                    <select class="form-control input-sm" name="canal_atendimento" id="canal_atendimento">
                                        <option value="" <?php if($canal_atendimento == ''){echo 'selected';}?>>Selecione</option>
                                        <option value="1" <?php if($canal_atendimento == '1'){echo 'selected';}?>>via Telefone</option>
                                        <option value="2" <?php if($canal_atendimento == '2'){echo 'selected';}?>>via Texto</option>
                                    </select>
                                </div>
                            </div>
                    
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Data da Ocorrência:</label>
                                    <input type="text" name="data_erro" required class="form-control date calendar data_erro date" value="<?=converteDataHora($data_erro)?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Hora da Ocorrência:</label>
                                    <input type="time" name="hora_erro" class="form-control hora_erro hour" required value="<?=$hora_erro?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Tipo de erro/reclamação:</label>
                                    <select class="form-control input-sm" name="id_tipo_erro" required>
                                        <?php
                                        $dados_tipo_erro = DBRead('', 'tb_tipo_erro');
                                        if($dados_tipo_erro){
                                            foreach($dados_tipo_erro as $conteudo){
                                                $selected = $id_tipo_erro == $conteudo['id_tipo_erro'] ? "selected" : "";
                                                echo "<option ".$selected." value='".$conteudo['id_tipo_erro']."'>".$conteudo['nome']."</option>";
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

                                    <label>*Descrição do cliente:</label>
                                    <textarea rows="12" cols="50" name="descricao_cliente" class="form-control ckeditor descricao" id="descricao" required><?= $descricao_cliente ?></textarea>

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

    CKEDITOR.replace('descricao', {
        height: 350
    });

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
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+"</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
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

    //BUSCA PESSOA
    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=UsuarioAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : 'funcionario',
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=UsuarioAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: { 
                        'id' : id,                            
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_usuario').val(data[0].id_usuario);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();

        if (busca == "") {
            $('#id_pessoa_funcionario').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });


    $(document).on('submit', "#erro_atendimento_cadastro_form", function(){
        var funcionario = $(".funcionario").val();
        var descricao = $(".descricao").val();
        var cliente = $(".cliente").val();
        var canal_atendimento = $("#canal_atendimento").val();
        
        if (!cliente) {
            alert("Deve-se selecionar o cliente!");
            return false;
        }
        
        if (!funcionario) {
            alert("Deve-se selecionar o funcionário!");
            return false;
        }
        
        if (!descricao) {
            alert("Deve-se descrever o erro/reclamação!");
            return false;
        }

        if (canal_atendimento == '') {
            alert("Deve-se selecionar um canal de atendimento!");
            return false;
        }

        modalAguarde();
    });

</script>