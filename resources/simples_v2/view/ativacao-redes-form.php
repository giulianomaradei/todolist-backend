<?php
require_once(__DIR__."/class/System.php");

if(isset($_GET['alterar'])){
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_redes_ativacao', "WHERE id_redes_ativacao = $id");
    $disabled = 'disabled';

    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];
        $id_responsavel = $dados[0]['id_responsavel'];
        $data_inicio = $dados[0]['data_inicio'];
        $data_prazo = $dados[0]['data_prazo'];
        $data_conclusao = $dados[0]['data_conclusao'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        
        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    }else{
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
        exit;
    }

}else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;    
    $id_responsavel = '';
    $data_inicio = '';
    $data_prazo = '';
    $data_conclusao = '';
    $disabled = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> ativação - Redes:</h3>
                    <?php if(isset($_GET['alterar'])){                       
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=AtivacaoRedes.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=AtivacaoRedes.php" id="ativacao_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <?php if(!isset($_GET['alterar'])){ ?>
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                            <?php }else{ ?>
                                                <button class="btn btn-info btn-sm" type="button" <?=$disabled?>><i class="fa fa-search"></i></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='<?=$id_contrato?>' />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Técnico Responsável:</label>
                                    <select class="form-control input-sm" id="id_responsavel" name="id_responsavel" required <?=$disabled?>>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Data início:</label>
                                    <input class="campos-agendar form-control date calendar input-sm" name="data_inicio" id="data_inicio" required value="<?= converteData($data_inicio) ?>" type="text" autocomplete="off" <?=$disabled?> />                                    
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Data prazo:</label>
                                    <input class="campos-agendar form-control date calendar input-sm" name="data_prazo" required id="data_prazo" value="<?= converteData($data_prazo) ?>" type="text" autocomplete="off" <?=$disabled?> />                                    
                                </div>
                            </div>                            
                        </div> 
                        <?php 
                        if(isset($_GET['alterar'])){ 
                            $dados_comentarios = DBRead('','tb_redes_ativacao_comentario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_redes_ativacao = '$id'", "a.*, c.nome");
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Comentários:</h3>
                                        <div class="panel-title text-right pull-right"><button class="btn btn-xs btn-primary" type='button' data-toggle="modal" data-target="#modal_comentario"><i class="fa fa-plus"></i> Incluir</button></div>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                            if($dados_comentarios){
                                                echo '
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="list-group" style="margin-top: 10px;">
                                                ';
                                                foreach ($dados_comentarios as $conteudo_comentario) {
                                                    echo "<li class='list-group-item clearfix'>";
                                                        echo "<div class='row'>";
                                                            echo "<div class='col-md-6'>";
                                                                echo "<p><strong>".$conteudo_comentario['nome']."</strong></p>";
                                                            echo "</div>";
                                                            echo "<div class='col-md-6'>";
                                                                echo " <p class='text-right'><strong>".converteDataHora($conteudo_comentario['data'])."</strong>";
                                                                if($conteudo_comentario['id_usuario'] == $_SESSION['id_usuario']){
                                                                    echo "&nbsp;&nbsp;<a href=\"class/AtivacaoRedes.php?id_ativacao=".$id."&excluir_comentario=".$conteudo_comentario['id_redes_ativacao_comentario']."&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\" type='button'><i class=\"fa fa-trash\"></i></button></a></p>";
                                                                }
                                                            echo "</div>";
                                                        echo "</div>";
                                                        echo "<hr style='margin-top: 0'>";
                                                        echo "<div class='row'>";
                                                            echo "<div class='col-md-12'>";
                                                                echo "<div>";
                                                                    echo nl2br($conteudo_comentario['comentario']);
                                                                echo "</div>";
                                                            echo "</div>";
                                                        echo "</div>";
                                                    echo "</li>";
                                                }
                                                echo '
                                                </ul>
                                            </div>
                                        </div>
                                                ';
                                            } else {
                                                echo '<div class="alert alert-info text-center" style="margin-bottom: 0;">Não existem comentários.</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <?php } ?>                       
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                            <?php if(!isset($_GET['alterar'])){ ?>
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>
                            <?php }else if(isset($_GET['alterar']) && $data_conclusao){ ?>
                                <h3 class="panel-title"><strong>Concluída em: <?=converteData($data_conclusao)?></strong></h3>
                                
                            <?php }else{ ?>
                                <button class='btn btn-success' name='ativar' id='ativar' type='button' data-toggle="modal" data-target="#modal_ativacao"><i class='fa fa-check'></i> Concluir ativação</button>
                            <?php }?>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(isset($_GET['alterar'])){ ?>
<form method="post" action="/api/ajax?class=AtivacaoRedes.php" id="comentario_form" style="margin-bottom: 0;">
    <input type="hidden" name="token" value="<?php echo $request->token ?>">

    <div class="modal fade" id="modal_comentario" role="dialog">
        <div class="modal-dialog">        
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Adicionar comentário:</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comentário:</label>
                                <textarea class="form-control " name="comentario" style="resize: vertical; height: 100px;" required></textarea>
                            </div>                                                
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">                    
                    <input type="hidden" value="<?= $id; ?>" name="adicionar_comentario" />
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Adicionar</button>
                </div>
            </div>            
        </div>
    </div>
</form>
<?php } ?>
<?php if(isset($_GET['alterar']) && !$data_conclusao){ ?>
<form method="post" action="/api/ajax?class=AtivacaoRedes.php" id="concluir_ativacao_form" style="margin-bottom: 0;">
    <input type="hidden" name="token" value="<?php echo $request->token ?>">
    <div class="modal fade" id="modal_ativacao" role="dialog">
        <div class="modal-dialog">        
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Concluir ativação:</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Data da conclusão:</label>
                                <input class="campos-agendar form-control date calendar input-sm" name="data_conclusao" id="data_conclusao" required value="" type="text" autocomplete="off" />                                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <input type="hidden" value="<?= $id; ?>" name="concluir_ativacao" />
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Ativar</button>
                </div>
            </div>            
        </div>
    </div>
</form>
<?php } ?>
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

    $(document).on('submit', '#ativacao_form', function(){
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        
        if(id_contrato_plano_pessoa == 0 || !id_contrato_plano_pessoa){
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }
        modalAguarde();
    });
</script>