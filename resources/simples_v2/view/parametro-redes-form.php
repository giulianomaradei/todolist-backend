<?php
require_once(__DIR__."/../class/System.php");

if(isset($_GET['alterar'])){
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_parametro_redes_contrato', "WHERE id_parametro_redes_contrato = $id");

    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

       
        $id_otrs = $dados[0]['id_otrs'];
        $id_responsavel = $dados[0]['id_responsavel'];
        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        
        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    }else{
<<<<<<< HEAD
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
=======
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
        exit;
    }

}else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;    
    $id_otrs = '';    
    $id_responsavel = '';    
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> parâmetros - Redes:</h3>
                    <?php if(isset($_GET['alterar'])){                       
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=ParametroRedes.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=ParametroRedes.php" id="parametro_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">    
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='<?=$id_contrato?>' />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Parâmetros:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>*Técnico Responsável:</label>
                                                    <select class="form-control input-sm" id="id_responsavel" name="id_responsavel" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $dados_responsavel = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE (a.id_perfil_sistema = 6 OR a.id_perfil_sistema = 26) AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                            
                                                            if ($dados_responsavel) {
                                                                foreach ($dados_responsavel as $conteudo_responsavel) {
                                                                    $selected = $id_responsavel == $conteudo_responsavel['id_usuario'] ? "selected" : "";
                                                                    echo "<option value='".$conteudo_responsavel['id_usuario']."' ".$selected.">".$conteudo_responsavel['nome']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>   
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>*ID OTRS:</label>
                                                    <input id="id_otrs" type="text" class="form-control id_otrs input-sm" value="<?=$id_otrs;?>" name="id_otrs" autocomplete="off" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>
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
                            'cod_servico' : 'gestao_redes'
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

    $(document).on('submit', '#parametro_form', function(){
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        
        if(id_contrato_plano_pessoa == 0 || !id_contrato_plano_pessoa){
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }
        modalAguarde();
    });
</script>