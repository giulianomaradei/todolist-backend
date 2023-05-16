<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_monitoria_quesito', "WHERE id_monitoria_quesito = $id");
    $descricao = $dados[0]['descricao'];
    $plano_acao = $dados[0]['plano_acao'];
    $passo_atendimento = $dados[0]['passo_atendimento'];
    $posicao = $dados[0]['posicao'];
    $status = $dados[0]['status'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $descricao = '';
    $plano_acao = '';
    $passo_atendimento = '';
    $posicao = '';
    $status = '';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> quesito:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=MonitoriaQuesito.php?excluir=$id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=MonitoriaQuesito.php" id="monitoria_quesito_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição:</label>
                                    <textarea name="descricao" autofocus type="text" class="form-control input-sm" autocomplete="off" required rows='4'><?=$descricao?></textarea>
                                </div>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Plano ação:</label>
                                    <textarea name="plano_acao" class="form-control" rows="4" required><?=$plano_acao?></textarea>
                                </div>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Passo do atendimento:</label>

                                    <?php
                                        $sel_passo[$passo_atendimento] = 'selected';
                                    ?>

                                    <select class="form-control input-sm" name="passo_atendimento" id="passo_atendimento">
                                        <option value="1" <?=$sel_passo['1']?>>1</option>
                                        <option value="2" <?=$sel_passo['2']?>>2</option>
                                        <option value="3" <?=$sel_passo['3']?>>3</option>
                                        <option value="4" <?=$sel_passo['4']?>>4</option>
                                        <option value="5" <?=$sel_passo['5']?>>5</option>
                                        <option value="6" <?=$sel_passo['6']?>>6</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <?php
                                        $sel_status[$status] = 'selected';
                                    ?>
                                    <select class="form-control input-sm" name="status">
                                        <option value="1" <?=$sel_status['1']?>>Ativo</option>
                                        <option value="0" <?=$sel_status['0']?>>Inativo</option>
                                    </select>
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
    $(document).on('submit', '#monitoria_quesito_form', function () {
        modalAguarde();
    });
</script>