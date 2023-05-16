<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
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

    $observacao_status = $dados[0]['observacao_status'];
    if($dados[0]['prazo_termino']){
        $prazo_termino = converteData($dados[0]['prazo_termino']);
    }

    $dado1 = $dados[0]['dado1'];
    $dado2 = $dados[0]['dado2'];
    $dado3 = $dados[0]['dado3'];

    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

    if($dados_contrato[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
    }

    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";

    if($status == 4 || $status == 5){
        $disabled_liberado = '';
        if($status == 4){
            $disabled_liberado_pausado = '';
        }else{
            $disabled_liberado_pausado = 'disabled';
        }
    }else{
        $disabled_liberado = 'disabled';
        $disabled_liberado_pausado = 'disabled';
    }

    $cont_contatos_faltantes = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa = '0' AND id_pesquisa = '".$id."' ", "COUNT(*) AS cont");
    $contatos_faltantes = $cont_contatos_faltantes[0]['cont'];

}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $titulo = '';
    $status = 1;
    $observacao = '';
    $ramal = '';
    $data_criacao = '';
    $cliente_contrato = 0;

    $dado1 = '';
    $dado2 = '';
    $dado3 = '';

    $contatos_faltantes = 0;
}

    $dados_contatos_pesquisa = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$id."' AND (qtd_tentativas_cliente != 0 OR (status_pesquisa != 0 AND status_pesquisa != 4)) LIMIT 1");
    if($dados_contatos_pesquisa){
        $readonly = 'readonly';
    }else{
        $readonly = '';
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
                <form method="post" action="/api/ajax?class=Pesquisa.php" id="pesquisa_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    
                                        <?php if(!$dados_contatos_pesquisa){
                                           echo ' <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="'.$contrato.'" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />';

                                            echo '<div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                        </div>';
                                        }else{
                                            echo '<input name="titulo" id="titulo" type="text" autofocus class="form-control input-sm" value="'.$contrato.'" '.$readonly.' autocomplete="off" required>';
                                        }?>
                                    
                                    <input type="hidden" required name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Título:</label>
                                    <input name="titulo" id="titulo" type="text" class="form-control input-sm" value="<?= $titulo; ?>" autocomplete="off" required>
                                </div>
                            </div>                    
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select id="status" class="form-control input-sm" name="status" required>
                                        <option value='1' <?php if ($status == 1) {echo 'selected ';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == 0) {echo 'selected ';}?>>Concluido</option>
                                        <option value='5' <?php if ($status == 5) {echo 'selected ';} echo $disabled_liberado ?>>Liberado</option>
                                        <option value='3' <?php if ($status == 3) {echo 'selected ';}?>>Pausado</option>
                                        <option value='4' <?php if ($status == 4) {echo 'selected ';} echo $disabled_liberado_pausado?>>Pausado automaticamente</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ramal:</label>
                                    <input name="ramal" id="ramal" type="number" class="form-control input-sm number_int" value="<?= $ramal; ?>" autocomplete="off">
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Quantidades de tentativas por contato:</label>
                                    <input name="qtd_tentativas_cliente" id="qtd_tentativas_cliente" type="number" class="form-control input-sm number_int" value="<?= $qtd_tentativas_cliente; ?>" autocomplete="off" required>
                                    <input type="hidden" id="qtd_tentativas_cliente_antiga" value="<?= $qtd_tentativas_cliente; ?>"/>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label>Observação de status:</label>
                                    <input name='observacao_status' type='text' class='form-control input-sm' id ='observacao_status' value='<?= $observacao_status ?>' />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Horas entre tentativas:</label>
                                    <input name="horas_entre_tentativas" id="horas_entre_tentativas" type="number" class="form-control input-sm number_int"  value="<?= $horas_entre_tentativas; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prazo de Término:</label>
                                    <input name="prazo_termino" id="prazo_termino" type="text" class="form-control input-sm date calendar"  value="<?= $prazo_termino; ?>" autocomplete="off">
                                </div>
                            </div>
                            
                        </div>
                        <div class='row'>
                            
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label>Dado adicional(1):</label>
                                    <input name='dado1' type='text' class='form-control input-sm' value='<?= $dado1 ?>' />
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label>Dado adicional(2):</label>
                                    <input name='dado2' type='text' class='form-control input-sm'  value='<?= $dado2 ?>' />
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label>Dado adicional(3):</label>
                                    <input name='dado3' type='text' class='form-control input-sm'  value='<?= $dado3 ?>' />
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
    CKEDITOR.replace('observacao', {
        height: 320
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

        if(status == '0'){
            var contatos_faltantes = <?=$contatos_faltantes?>;
            if(contatos_faltantes != '0'){
                var confirmacao = confirm("A pesquisa ainda possui "+contatos_faltantes+" contatos pendentes!");
                if(confirmacao == false){
                    return false;
                }
            }
        }
       
        modalAguarde();
    });

    $('#status').on('change', function(){
        $('#observacao_status').val('');
    });

    $('#qtd_tentativas_cliente').on('keyup', function(){
        if($(this).val() < $('#qtd_tentativas_cliente_antiga').val()){
            alert('Não pode diminuir a quantidade de tentativas!');
            $(this).val($('#qtd_tentativas_cliente_antiga').val());
        }
    });
</script>