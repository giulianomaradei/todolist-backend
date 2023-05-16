<?php
require_once(__DIR__."/../class/System.php");

$data_referencia = new DateTime(getDataHora());
$data_referencia->modify('first day of last month');
$data_referencia = $data_referencia->format('Y-m-d');

$dados_comissoes = DBRead('','tb_plantao_redes_comissao',"WHERE data_referencia = '$data_referencia'");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Plantões - Comissões:</h3>
                </div>
                <form method="post" action="/api/ajax?class=PlantaoComissoes.php" id="plantao_comissoes_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <?php if(!$dados_comissoes){ ?>                            
                            <div class="alert alert-warning text-center">As comissões ainda não foram geradas! Clique no botão "Gerar Comissões".</div>
                        <?php }else{ ?>
                            <div class="alert alert-success text-center">As comissões foram geradas com sucesso! Consulte o resultado no <a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-redes-plantao">relatório de plantões</a> no tipo "Comissões".</div>
                        <?php } ?>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                            <?php if(!$dados_comissoes){ ?>
                                <input type="hidden" id="operacao" value="gerar" name="operacao"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-check"></i> Gerar Comissões</button>
                            <?php }else{ ?>
                                <input type="hidden" id="operacao" value="reprocessar" name="operacao"/>
                                <button class="btn btn-warning" name="salvar" id="ok" type="submit"><i class="fa fa-refresh"></i> Reprocessar Comissões</button>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('submit', '#plantao_comissoes_form', function () {
        if($('#operacao').val() == 'reprocessar'){
            if(!confirm("Ao reprocessar você estará excluindo os dados já gerados!")){
                return false;
            }  
        }        
        modalAguarde();
    });
</script>