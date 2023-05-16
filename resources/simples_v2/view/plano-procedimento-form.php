<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_plano_procedimento', "WHERE id_plano_procedimento = $id");
	$cod_servico = $dados[0]['cod_servico'];
	$nome = $dados[0]['nome'];
	$descricao = $dados[0]['descricao'];
	$pre_requisito = $dados[0]['pre_requisito'];
	$status = $dados[0]['status'];
    
} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$cod_servico = '';
	$nome = '';
	$descricao = '';
	$pre_requisito = '';
	$status = '';
}
?> 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> Procedimento:</h3>
                    <?php
                    if (isset($_GET['alterar'])) {
                        $dados_plano_procedimento_plano = DBRead('', 'tb_plano_procedimento_plano',"WHERE id_plano_procedimento = '".$id."' LIMIT 1");
                        $dados_plano_procedimento_historico = DBRead('', 'tb_plano_procedimento_historico',"WHERE id_plano_procedimento = '".$id."' LIMIT 1");

                        if(!$dados_plano_procedimento_plano && !$dados_plano_procedimento_historico){
                            echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=PlanoProcedimento.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        } 
                    }
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=PlanoProcedimento.php" id="plano_procedimento_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required autofocus>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Serviço:</label>
                                    <select class="form-control input-sm" name="cod_servico">
                                        <!-- <option>Selecione um serviço...</option> -->
                                        <?php
                                            $sel_servico[$cod_servico] = 'selected';
                                            echo "<option value='call_ativo'".$sel_servico['call_ativo'].">".getNomeServico('call_ativo')."</option>";
                                            echo "<option value='call_monitoramento'".$sel_servico['call_monitoramento'].">".getNomeServico('call_monitoramento')."</option>"; 
                                            echo "<option value='call_suporte'".$sel_servico['call_suporte'].">".getNomeServico('call_suporte')."</option>";
                                            // echo "<option value='gestao_redes'".$sel_servico['gestao_redes'].">".getNomeServico('gestao_redes')."</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select class="form-control input-sm" name="status">
                                        <option value='1' <?php if ($status == '1') {echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == '0') {echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição:</label>
                                    <textarea required class="form-control input-sm" style="height: 240px;" name="descricao" id="descricao"><?= $descricao ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Pré-Requisito:</label>
                                    <textarea class="form-control input-sm" style="height: 240px;" name="pre_requisito" id="pre_requisito"><?= $pre_requisito ?></textarea>
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
    $(document).on('submit', '#plano_procedimento_form', function () {

        var nome = $('#nome').val();
        var cod_servico = $('#cod_servico').val();
        var status = $('#status').val();
        
        if(nome == '' && !nome){
            alert("Deve-se inserir um nome!");
            return false;
        }

        if(cod_servico == '' && !cod_servico){
            alert("Deve-se selecionar um serviço!");
            return false;
        }

        if(status == '' && !status){
            alert("Deve-se selecionar um status!");
            return false;
        }
        
        modalAguarde();
    });
</script>