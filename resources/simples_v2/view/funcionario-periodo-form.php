<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar_periodo';
    $id_funcionario_periodo = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_funcionario_periodo', "WHERE id_funcionario_periodo = $id_funcionario_periodo");
    $data_inicio = converteData($dados[0]['data_inicio']);
    $data_fim = converteData($dados[0]['data_fim']);
    $formato = $dados[0]['formato'];
    $motivo = $dados[0]['motivo'];
    $tipo = $dados[0]['tipo'];
    $id_funcionario = $dados[0]['id_funcionario'];
    $escolaridade = $dados[0]['escolaridade'];
    $demissao = $dados[0]['demissao'];

} else { 
    $tituloPainel = 'Inserir';
    $operacao = 'inserir_periodo';
    $id_funcionario = (int)$_GET['id_funcionario'];
    $tipo = (int)$_GET['tipo'];
    $data = '';
    $formato = '';
    $motivo = '';
    $demissao = '';
}
    
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> funcionário:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Funcionario.php" id="funcionario_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="id_funcionario" value="<?= $id_funcionario ?>">
                    <input type="hidden" name="id_funcionario_periodo" value="<?= $id_funcionario_periodo ?>">
                    <input type="hidden" name="tipo" value="<?= $tipo ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Data Inicio:</label>
                                    <input type="text" name="data_inicio" id="data_inicio" required class="form-control input-sm date calendar date" value="<?= $data_inicio ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php $sel_formato[$formato] = 'selected'; ?>

                                    <label>*Formato:</label>
                                    <select class="form-control input-sm" name="formato" id="formato" required>
                                        <option value="" <?= $sel_formato[''] ?>>Selecione</option>
                                        <option value="1" <?= $sel_formato['1'] ?>>Efetivo</option>
                                        <option value="2" <?= $sel_formato['2'] ?>>Estágio</option>
                                        <option value="3" <?= $sel_formato['3'] ?>>Jovem aprendiz</option>
                                        <option value="4" <?= $sel_formato['4'] ?>>PCD</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?php $sel_escolaridade[$escolaridade] = 'selected'; ?>

                                    <label for="escolaridade">*Escolaridade</label>
                                    <select name="escolaridade" id="escolaridade" class="form-control input-sm">
                                        <option value="" <?=$sel_escolaridade['0']?>>Selecione</option>
                                        <option value="1" <?=$sel_escolaridade['1']?>>Primeiro grau completo</option>
                                        <option value="2" <?=$sel_escolaridade['2']?>>Primeiro grau incompleto</option>
                                        <option value="3" <?=$sel_escolaridade['3']?>>Segundo grau completo</option>
                                        <option value="4" <?=$sel_escolaridade['4']?>>Segundo grau incompleto</option> 
                                        <option value="5" <?=$sel_escolaridade['5']?>>Superior completo</option> 
                                        <option value="6" <?=$sel_escolaridade['6']?>>Superior incompleto</option> 
                                        <option value="7" <?=$sel_escolaridade['7']?>>Pós-graduação em andamento</option> 
                                        <option value="8" <?=$sel_escolaridade['8']?>>Pós-graduação em completo</option> 
                                        <option value="9" <?=$sel_escolaridade['9']?>>Mestrando</option> 
                                        <option value="10" <?=$sel_escolaridade['10']?>>Mestre</option> 
                                        <option value="11" <?=$sel_escolaridade['11']?>>Doutorando</option> 
                                        <option value="12" <?=$sel_escolaridade['12']?>>Doutor</option> 
                                    </select>                            
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($operacao == 'alterar_periodo') { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Fim:</label>
                                        <input type="text" name="data_fim" id="data_fim" class="form-control input-sm date calendar date" value="<?= $data_fim ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <?php $sel_demissao[$demissao] = 'selected'; ?>

                                    <label for="demissao">*Demissão</label>
                                    <select name="demissao" id="demissao" class="form-control input-sm">
                                        <option value="" <?=$sel_demissao['0']?>>Selecione</option>
                                        <option value="1" <?=$sel_demissao['1']?>>Dispensado</option>
                                        <option value="2" <?=$sel_demissao['2']?>>Pedido</option>
                                        <option value="3" <?=$sel_demissao['3']?>>Fim de Contrato</option>
                                    </select>                            
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Motivo:</label>
                                        <textarea type="text" class="form-control" name="motivo" id="motivo" rows="10"><?= $motivo ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id_funcionario ?>" name="<?= $operacao; ?>"/>
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

    $(document).on('submit', "#funcionario_form", function(){
        var formato = $("#formato").val();
        var demissao = $("#demissao").val();
        
        if (!formato) {
            alert("Deve-se selecionar o formato!");
            return false;
        }

        modalAguarde();
    });

</script>