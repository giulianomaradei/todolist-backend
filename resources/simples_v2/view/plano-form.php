<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_plano', "WHERE id_plano = $id");
	$cod_servico = $dados[0]['cod_servico'];
	$nome = $dados[0]['nome'];
	$cor = $dados[0]['cor'];
	$status = $dados[0]['status'];
	$menu = $dados[0]['menu'];
    $nome_pagina = $dados[0]['nome_pagina'];
    $versao_atual = $dados[0]['versao'];

    //tem que tirar o not like %p%
    $dados_historico = DBRead('', 'tb_plano_procedimento_historico', "WHERE id_plano = $id AND personalizado = 0 AND versao NOT LIKE '%p%' GROUP BY versao, data_atualizacao, id_usuario ORDER BY versao DESC", "versao, data_atualizacao, id_usuario");

} else{
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$cod_servico = '';
	$nome = '';
	$cor = '#000000';
	$status = '';
	$menu = '';
    $nome_pagina = '';
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> Plano:</h3>
                    <?php if (isset($_GET['alterar'])) {
                        
                        echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Plano.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        
                        echo "<div class=\"panel-title text-center pull-center\">Versão Atual: ".$versao_atual."</div>";
                        
                        }?>
                </div>
                <form method="post" action="/api/ajax?class=Plano.php" id="plano_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Serviço:</label>
                                    <select class="form-control input-sm" name="cod_servico" id="cod_servico" onchange="call_busca_ajax();">
                                        <?php
                                            if(isset($_GET['alterar'])){
                                                echo "<option value='".$cod_servico."' selected>".getNomeServico($cod_servico)."</option>";
                                            }else{
                                                echo "<option value='call_ativo'>".getNomeServico('call_ativo')."</option>";
                                                echo "<option value='call_monitoramento'>".getNomeServico('call_monitoramento')."</option>"; 
                                                echo "<option value='call_suporte'>".getNomeServico('call_suporte')."</option>";
                                                // echo "<option value='gestao_redes'>".getNomeServico('gestao_redes')."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required>
                                </div>
                            </div>
  
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>*Cor:</label>
                                    <input name="cor" type="color" class="form-control input-sm" value="<?=$cor;?>" autocomplete="off" required>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <select class="form-control input-sm" name="status">
                                        <option value='1' <?php if ($status == '1') {echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == '0') {echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#tab_procedimento">Procedimentos</a></li>
                                            <?php
                                                if(isset($_GET['alterar']) && $dados_historico){ 
                                                    echo '<li><a data-toggle="tab" href="#tab_historico">Histórico de Procedimentos</a></li>';
                                                }
                                            ?>
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane fade in active" id="tab_procedimento" style="padding-top: 10px;">
                                                <div id="resultado_busca"></div>
                                            </div>
                                            <?php 

                                            if(isset($_GET['alterar'])){
                                                $collapse = '';
                                                $collapse_icon = 'plus';
                                                if($dados_historico){
                                                    echo '<div class="tab-pane fade" id="tab_historico" style="padding-top: 10px;">';
                                                    $cont_id = 0;
                                                    foreach($dados_historico as $conteudo){
                                                        $dados_usuario_historico = DBRead('', 'tb_usuario a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario']."'");
                                                        $nome_usuario_historico = $dados_usuario_historico[0]['nome'];
                                                        ?>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading clearfix">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Versão: <?=$conteudo['versao']?> </h3>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6" style="text-align: center" id= 'div_botao'>
                                                                            <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Atualização: <?=converteDataHora($conteudo['data_atualizacao'])." (".$nome_usuario_historico.")"?> </h3>

				                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <div class="panel-title text-right pull-right">
                                                                                    <button data-toggle="collapse" data-target="#accordionPlano_<?=$cont_id?>" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse_<?=$cont_id?>" class="fa fa-plus"></i></button>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="accordionPlano_<?=$cont_id?>" class="panel-collapse collapse accordionPlano">
                                                                    <div class="panel-body">	
                                                                        <div class="table-responsive" style="max-height: 365px; overflow-y:auto;">
                                                                            <table class="table table-hover table_paginas" style="font-size: 14px;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-md-4">Procedimento</th>
                                                                                        <th class="col-md-4">Descrição</th>
                                                                                        <th class="col-md-4">Pré-Requisito</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php

                                                                                $dados_historico_procedimento = DBRead('', 'tb_plano_procedimento_historico a', "INNER JOIN tb_plano_procedimento b ON a.id_plano_procedimento = b.id_plano_procedimento WHERE a.id_plano = '".$id."' AND a.versao = '".$conteudo['versao']."' ORDER BY b.nome ASC ");

                                                                                foreach($dados_historico_procedimento as $conteudo_procedimento){
                                                                                    echo '
                                                                                        <tr>
                                                                                            <td>'.$conteudo_procedimento['nome'].'</td>
                                                                                            <td>'.$conteudo_procedimento['descricao'].'</td>
                                                                                            <td>'.$conteudo_procedimento['pre_requisito'].'</td>
                                                                                        <tr>
                                                                                        ';
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php
                                                        $cont_id ++;  
                                                    }
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
</div>

<script>

    $('.accordionPlano').on('shown.bs.collapse', function(){
        var i_collapse_ = $(this).attr('id').split("_");
        i_collapse_ = '#i_collapse_'+i_collapse_[1];
        $(i_collapse_).removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('.accordionPlano').on('hidden.bs.collapse', function(){
        var i_collapse_ = $(this).attr('id').split("_");
        i_collapse_ = '#i_collapse_'+i_collapse_[1];
        $(i_collapse_).removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    function call_busca_ajax(pagina){
        
        var cod_servico = $("#cod_servico").val();
        var operacao = $("#operacao").attr('name');
        var id = $("#operacao").val();
        var parametros = {
            'cod_servico': cod_servico,
            'operacao': operacao,
            'id': id 
        };
        busca_ajax('<?= $request->token ?>' , 'PlanoFormProcedimentoBusca', 'resultado_busca', parametros);
    }

    call_busca_ajax();

    $(document).on('submit', '#plano_form', function () {
        var nome = $('#nome').val();
        var cod_servico = $('#cod_servico').val();
        var status = $('#status').val();
        var cor = $('#cor').val();
        
        if(cod_servico == '' && !cod_servico){
            alert("Deve-se selecionar um serviço!");
            return false;
        }

        if(nome == '' && !nome){
            alert("Deve-se inserir um nome!");
            return false;
        }

        if(cor == '' && !cor){
            alert("Deve-se selecionar uma cor!");
            return false;
        } 
        
        if(status == '' && !status){
            alert("Deve-se selecionar um status!");
            return false;
        }
        modalAguarde();
    });

</script>