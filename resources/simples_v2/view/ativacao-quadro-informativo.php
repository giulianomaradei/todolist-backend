<?php
require_once(__DIR__."/../class/System.php");

$tituloPainel = 'Ativação';

$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, a.nome_contrato, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> quadro informativo:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=SistemaGestao.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>

                <form method="post" action="/api/ajax?class=AtivacaoQuadroInformativo.php" id="ativacao_quadro_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$plano?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <?php
                                $ativacao = $_GET['ativacao'];
                                $pagina = 'ativacao-quadro-informativo';
                                if($ativacao){
                                    echo "<input type='hidden' value='1' id='ativacao' name='ativacao' />";
                                    echo "<input type='hidden' value='" . $pagina . "' name='pagina' />";

                                    echo "<button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-arrow-right' aria-hidden='true'></i> Iniciar</button>";
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </form>
                </div>
                </div>
            </div>
        </div>
        
<script>

    var idVoltar = $("#voltar").val();

    $('#voltar').on('click', function(){
        if(idVoltar){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo";
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo";
        }
        
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
    $(document).on('submit', '#ativacao_quadro_form', function () {
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        if(!id_contrato_plano_pessoa){
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }

        modalAguarde();
    });
    
</script>