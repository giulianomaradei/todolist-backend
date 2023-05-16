<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_caixa', "WHERE id_caixa = $id");
    $nome = $dados[0]['nome'];
    $saldo = converteMoeda($dados[0]['saldo'], 'moeda');
    $status = $dados[0]['status'];
    $disabled = 'disabled';

    $aceita_movimentacao = $dados[0]['aceita_movimentacao'];
    if($aceita_movimentacao == 1){
        $checkedAceitaMovimentacaoSim = 'checked';
    }else{
        $checkedAceitaMovimentacaoNao = 'checked';
    }
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';

    $checkedAceitaMovimentacaoSim = 'checked';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Caixa:</h3>
                    <?php
                        if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Caixa.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        } 

                    ?>
                </div>
                <form method="post" action="/api/ajax?class=Caixa.php" id="caixa" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" id="nome" type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required <?= $disabled ?> />
                                </div>
                            </div>
							<div class="col-md-3">
                                <div class="form-group">
                                    <label>*Saldo:</label>
                                    <input name="saldo" id="saldo" type="text" class="form-control input-sm money" value="<?=$saldo;?>" autocomplete="off" required <?= $disabled ?> />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label>*Status:</label>
                                    <select name="status" class="form-control input-sm" >
                                        <option value="1" <?php if($status == '1'){echo 'selected';}?>>Ativo</option>
                                        <option value="0" <?php if($status == '0'){echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label>*Aceita Movimentações:</label>
                                    <div class="radio-inline"> 
                                        <input class="aceita_movimentacao" type="radio" value='1' <?= $checkedAceitaMovimentacaoSim ?> name='aceita_movimentacao' <?=$disabled?>> Sim
                                    </div>
                                    <div class="radio-inline">
                                        <input class="aceita_movimentacao" type="radio" value='0' <?= $checkedAceitaMovimentacaoNao ?> name='aceita_movimentacao' <?=$disabled?>> Não
                                    </div>
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

    $('.aceita_movimentacao').on("click", function(){
        if($(this).val() == 0){
            $('#saldo').val('0,00');
            $('#saldo').prop('disabled', true);
        }else{
            $('#saldo').val('');
            $('#saldo').prop('disabled', false);
        }
    });

    $(document).on('submit', '#caixa', function () {
        modalAguarde();
    });

</script>