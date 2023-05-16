<?php
require_once(__DIR__."/../class/System.php");
$dados = DBRead('snep','queue_automacao',"WHERE id = 1");
if($dados){
    $tempo_fila1 = $dados[0]['tempo_fila1'];
    $tempo_fila2 = $dados[0]['tempo_fila2'];
    $qtd_dias_calculo = $dados[0]['qtd_dias_calculo'];
    $porcentagem_prioridade_alta = $dados[0]['porcentagem_prioridade_alta'];
    $porcentagem_prioridade_baixa = $dados[0]['porcentagem_prioridade_baixa'];
}else{
    $tempo_fila1 = 1200;
    $tempo_fila2 = 600;
    $qtd_dias_calculo = 7;
    $porcentagem_prioridade_alta = 50;
    $porcentagem_prioridade_baixa = 150;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Parâmetros - Controle Automático de Filas:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ControleFilas.php" id="controle_filas" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*QTD dias cálculo base:</label>
                                    <input name="qtd_dias_calculo" type="number" class="form-control input-sm number_int" value="<?=$qtd_dias_calculo;?>" autocomplete="off" required />
                                </div>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Porcentagem máxima para fila alta < <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-container="body" title="Supondo que o valor deste campo seja 50, serão colocados em fila alta os que atingirem a porcentagem proporcional menor que 50"></i>:</label>
                                    <input name="porcentagem_prioridade_alta" id="porcentagem_prioridade_alta" type="number" class="form-control input-sm number_int" value="<?=$porcentagem_prioridade_alta;?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Porcentagem mínima para fila baixa > <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-container="body" title="Supondo que o valor deste campo seja 150, serão colocados em fila baixa os que atingirem a porcentagem proporcional maior que 150"></i>:</label>
                                    <input name="porcentagem_prioridade_baixa" id="porcentagem_prioridade_baixa" type="number" class="form-control input-sm number_int" value="<?=$porcentagem_prioridade_baixa;?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Tempo na Fila 1 (segundos):</label>
                                    <input name="tempo_fila1" type="number" class="form-control input-sm number_int" value="<?=$tempo_fila1;?>" autocomplete="off" required />
                                </div>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tempo na Fila 2 (segundos):</label>
                                    <input name="tempo_fila2" type="number" class="form-control input-sm number_int" value="<?=$tempo_fila2;?>" autocomplete="off" />
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
</div>     
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $(document).on('submit', '#controle_filas', function () {
        console.log(parseInt($('#porcentagem_prioridade_alta').val()) + ' | ' + parseInt($('#porcentagem_prioridade_baixa').val()));
        if(parseInt($('#porcentagem_prioridade_alta').val()) >= parseInt($('#porcentagem_prioridade_baixa').val())){
            alert('O campo "Porcentagem máxima para fila alta <" deve ter um valor MENOR que o campo "Porcentagem mínima para fila baixa >"');
            return false;
        }

        modalAguarde();
    });
</script>