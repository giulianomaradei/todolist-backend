<?php
    require_once(__DIR__."/../class/System.php");

    if (isset($_GET['alterar'])) {
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $id = (int)$_GET['alterar'];
        $pagina_origem = $_GET['pagina-origem'];

        $dados = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_lead_negocio = $id");
        $titulo = $dados[0]['titulo'];
        $lead = $dados[0]['nome'];
        $id_pessoa = $dados[0]['id_pessoa'];
        $id_status = $dados[0]['id_lead_status'];
        $servico = $dados[0]['cod_servico'];
        $id_responsavel = $dados[0]['id_usuario_responsavel'];
        $valor_contrato = converteMoeda($dados[0]['valor_contrato']);
        $valor_adesao = converteMoeda($dados[0]['valor_adesao']);
        $valor_reducao = converteMoeda($dados[0]['valor_reducao']);
        $valor_aumento = converteMoeda($dados[0]['valor_aumento']);
        $data_inicio = converteData($dados[0]['data_inicio']);
        $data_conclusao = converteData($dados[0]['data_conclusao']);
        $descricao = $dados[0]['descricao'];
        $tipo_negocio = $dados[0]['tipo_negocio'];
        $id_plano = $dados[0]['id_plano'];

        if($id_plano == 0){
            $plano_selected = 'selected';
        }

        if ($tipo_negocio == 'Downgrade') {
            $row_reducao = "display: block;";
            $row_aumento = "display: none;";

        } else if ($tipo_negocio == 'Upgrade') {
            $row_aumento = "display: block;";
            $row_reducao = "display: none;";

        } else {
            $row_reducao = "display: none;";
            $row_aumento = "display: none;";
        }

        $read_only = 'readOnly';
        $disabled = 'disabled';
        $display = 'inline';

    }else{
        $tituloPainel = 'Inserir';
        $operacao = 'inserir';
        $id = 1;
        $titulo = '';
        $lead = '';
        $id_pessoa = '';
        $id_status = '';
        $servico = '';
        $id_responsavel = '';
        $valor_contrato = '0,00';
        $valor_adesao = '0,00';
        $data_inicio = '';
        $data_conclusao = '';
        $descricao = '';
        $disabled = '';
        $tipo_negocio = '';
        $id_plano = '';
        $display = 'none';

        if ($_GET['pessoa']) {
            $id_pessoa = (int)$_GET['pessoa'];
            $dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa");
            $lead = $dados[0]['nome'];
        }

        if ($_GET['id_rd_conversao']) {
            $id_rd_conversao = (int)$_GET['id_rd_conversao'];
        }

        $row_aumento = "display: none;";
        $row_reducao = "display: none;";
    }
?>

<div class="container-fluid">
  <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> negócio: <span style="display: <?=$display?>">#<?=$id?></span></h3>
                <?php
                if (isset($_GET['alterar'])) {
                ?>
                    <div class="panel-title text-right pull-right">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="https://www.google.com/calendar" style="color: white;" target="_blank">
                        <i class="fa fa-calendar"></i> Google Calendário
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline" style="color: white;">
                            <i class="fa fa-bars"></i> Timeline
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&pagina-origem=negocio-form" style="color: white;">
                            <i class="fa fa-plus"></i> Nova Empresa/Pessoa
                        </a>
                    </div>
                <?php
                }
                ?>
          </div>
          <form method="post" action="/api/ajax?class=LeadNegocio.php" id="Negocio" style="margin-bottom: 0;">
		    <input type="hidden" name="token" value="<?php echo $request->token ?>">
            <input type="hidden" name="pagina_origem" value="<?=$pagina_origem?>">
            <input type="hidden" name="id_rd_conversao" value="<?=$id_rd_conversao?>">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>*Empresa/Pessoa:</label>
                            <div class="input-group">
                                <input class="form-control input-sm ui-autocomplete-input" id="busca_contrato" type="text" name="busca_contrato" value="<?=$lead?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="" required >

                                <div class="input-group-btn">
                                    <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;" <?=$disabled?>><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa?>">
                        </div>
                    </div>
                </div>
                <div class="row">                 
                    <div class="col-md-6">
                        <div class="form-group">

                        <?php
                            $sel_tipo_negocio[$tipo_negocio] = 'selected';
                        ?>

                            <label>*Tipo:</label>
                            <select class="form-control input-sm" id="tipo_negocio" name="tipo_negocio" require>
                                <option></option>
                                <option value="Novo" <?=$sel_tipo_negocio['Novo']?>>Novo</option>
                                <option value="Upgrade" <?=$sel_tipo_negocio['Upgrade']?>>Upgrade</option>
                                <option value="Downgrade" <?=$sel_tipo_negocio['Downgrade']?>>Downgrade</option>
                                <option value="Cancelado" <?=$sel_tipo_negocio['Cancelado']?>>Cancelado</option>
                                <option value="Pós-venda" <?=$sel_tipo_negocio['Pós-venda']?>>Pós-venda</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <?php
                                $dados_status = DBRead('', 'tb_lead_status', "ORDER BY posicao ASC");
                            ?>

                            <label>*Status:</label>
                            <select class="form-control input-sm" name="status" id="status" require>
                                <option></option>
                                <?php
                                    foreach($dados_status as $conteudo){
                                    $selected = $id_status == $conteudo['id_lead_status'] ? "selected" : "";
                                ?>
                                    <option value="<?=$conteudo['id_lead_status']?>" <?=$selected?>><?=$conteudo['posicao']?> - <?=$conteudo['descricao']?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="id_status" id="id_status" value="<?=$id_status?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Serviço:</label>
                            <select class="form-control input-sm" id="servico" name="servico" require>
                                <?php
                                
                                if (isset($_GET['alterar'])) {
                                    $dados_plano = DBRead('', 'tb_plano', "GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");

                                    $servico = DBRead('', 'tb_plano', "WHERE id_plano = $id_plano");
                                    
                                    echo "<option value='N/D'".$plano_selected.">N/D</option>";
                                    if ($dados_plano) {
                                        foreach ($dados_plano as $conteudo) {
                                            $selected = $servico[0]['cod_servico'] == $conteudo['cod_servico'] ? "selected" : "";
                                            $servico_select = getNomeServico($conteudo['cod_servico']);
                                            echo "<option value='".$conteudo['cod_servico']."' ".$selected.">$servico_select</option>";
                                        }
                                    }

                                }else{
                                    $dados_plano = DBRead('', 'tb_plano', "WHERE cod_servico != 'gestao_redes' GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
                                    if ($dados_plano) {
                                        echo "<option value='N/D'>N/D</option>";
                                        foreach ($dados_plano as $conteudo) {
                                            $servico_select = getNomeServico($conteudo['cod_servico']);
                                            echo "<option value='".$conteudo['cod_servico']."' >$servico_select</option>";
                                        }
                                    }
                                }
                                
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Plano:</label>
                            <select class="form-control input-sm" id="id_plano" name="id_plano" required>
                                <?php
                                $dados_plano = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico[0]['cod_servico']."' ORDER BY cod_servico ASC, nome ASC");
                                if(isset($_GET['alterar']) && $dados){
                                    echo "<option value='N/D'".$plano_selected.">N/D</option>";
                                    if ($dados_plano) {
                                        foreach ($dados_plano as $conteudo) {
                                            $id_select = $conteudo['id_plano'];
                                            $nome_select = $conteudo['nome'];
                                            $selected = $id_plano == $idSelect ? "selected" : "";
                                            echo "<option value='$id_select'".$selected.">$nome_select</option>";
                                        }
                                    }
                                }else{
                                    echo "<option value='0' selected>N/D</option>";
                                }
                                
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Responsável:</label>
                            <select class="form-control input-sm" name="responsavel" require>
                                <option></option>
                                <?php
                                    $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email');

                                    if($usuarios){
                                        foreach($usuarios as $conteudo){
                                            $id_usuario = $conteudo['id_usuario'];
                                            $nomeSelect = $conteudo['nome'];
                                            $selected = $id_responsavel == $id_usuario ? "selected" : "";
                                            echo "<option value='$id_usuario'".$selected.">$nomeSelect</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>*Valor do contrato:</label>
                            <input class="form-control input-sm money" name="valor_contrato" value="<?=$valor_contrato?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>*Valor da adesão:</label>
                            <input class="form-control input-sm money" name="valor_adesao" value="<?=$valor_adesao?>" required>
                        </div>
                    </div>
                </div>
                <div class="row" id="row-reducao" style="<?= $row_reducao ?>">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Valor de <span class="label label-danger" style="font-size: 13px;">redução</span>:</label>
                            <input class="form-control input-sm money" name="valor_reducao" id="valor_reducao" value="<?= $valor_reducao ?>" placeholder="R$ 0,00">
                        </div>
                    </div>
                </div>
                <div class="row" id="row-aumento" style="<?= $row_aumento ?>">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Valor de <span class="label label-success" style="font-size: 13px;">aumento</span>:</label>
                            <input class="form-control input-sm money" name="valor_aumento" id="valor_aumento" value="<?= $valor_aumento ?>" placeholder="R$ 0,00">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Data início:</label>
                            <input class="form-control input-sm date calendar hasDatepicker" name="data_inicio" value="<?=$data_inicio?>" required> 
                        </div> 
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Data de conclusão:</label>
                            <input class="form-control input-sm date calendar hasDatepicker" name="data_conclusao" id="data_conclusao" value="<?=$data_conclusao?>" required> 
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrição:</label>
                            <textarea class="form-control input-sm" rows="5" name="descricao"><?=$descricao?></textarea>
                        </div> 
                    </div> 
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <input type="hidden" id="operacao" value="<?=$id?>" name="<?=$operacao;?>"/>
                        <button class="btn btn-primary" name="salvar" id="ok" type="submit">
                            <i class="fa fa-floppy-o"></i> Salvar
                        </button>
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
        source: function(request, response){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'autocomplete',
                    parametros: { 
                        'nome' : $('#busca_contrato').val(),
                        'pagina' : 'lead-negocio-form'
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        focus: function (event, ui) {
            $("#busca_contrato").val(ui.item.nome);
            carregarDadosContrato(ui.item.id_pessoa);
            return false;
        },
        select: function (event, ui) {
            $("#busca_contrato").val(ui.item.nome);
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
        
        return $("<li>").append("<a><strong>"+ item.nome + "</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta_lead',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_pessoa').val(data[0].id_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if(busca == ""){
            $('#id_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_pesssoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    function selectplano(cod_servico, id_plano){        
        id_plano  = '<?=$id_plano?>';
        pagina = 'negocio-form';
        $("select[name=id_plano]").html('<option value="">Carregando...</option>');
        $.post("class/SelectPlano.php", {cod_servico: cod_servico, id_plano: id_plano, pagina: pagina},
            function(valor){
                $("select[name=id_plano]").html(valor);
                if(id_plano == 6){
                    $('#div_valor_unitario').hide();
                }
            }
        )        
    }

    $(document).on('change', 'select[name=servico]', function(){
        selectplano($(this).val());
    });

    $(document).on('change', 'select[name=tipo_negocio]', function(){
        if ($(this).val() == 'Cancelado') {
            $('#row-reducao').hide();
            $('#row-aumento').hide();
            $("#status").val("15").change();

        } else if ($(this).val() == 'Downgrade') { 
            $('#row-reducao').show();
            $('#row-aumento').hide();
            $('#valor_aumento').val('');
            $("#status").val("").change();

        } else if ($(this).val() == 'Upgrade') { 
            $('#row-reducao').hide();
            $('#row-aumento').show();
            $('#valor_reducao').val('');
            $("#status").val("").change();

        } else {
            $('#row-reducao').hide();
            $("#status").val("").change();
        }
    });

    $('#status').on('change', function(){
        $('#id_status').val($(this).val());
    });

    /* $('#data_conclusao').on('change', function(){
        data_conclusao = $('input[name="data_conclusao"]').val();
        
        var d = new Date();

        var data = (d.getDate()<10 ? '0' : '') + d.getDate() + '/' + (d.getMonth()<10 ? '0' : '') + (parseInt(d.getMonth()) + parseInt(1)) + '/' + d.getFullYear();

        if(data_conclusao < data){
            alert('Data da conclusão já passou!');
            $('input[name="data_conclusao"]').val('');
        }
    }) */

    $(document).on('submit', '#Negocio', function(){

        var status = $('#status').val();

        if (status == '') {
            alert('Informe o status do negócio');
            return false;
        } else {
            modalAguarde();
        }
    });
</script>