<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'ajustar_redes';
    $id = (int)$_GET['alterar'];
    $cancelados = (int)$_GET['cancelados'];
    $ordenacao = $_GET['ordenacao'];

    $dados = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa
     = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_faturamento = $id", "a.*, d.nome, e.nome AS nome_plano, e.cod_servico");

    $nome = $dados[0]['nome'];
    
    $qtd_clientes = $dados[0]['qtd_clientes'];
    $servico = $dados[0]['cod_servico'];

}else{
    header("location: ../adm.php");
    exit;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?php echo $tituloPainel.' Quantidade de Clientes no Faturamento de Gestão de Redes: <strong>'.$nome.'</strong>'; ?></h3>
                </div>
                <form method="post" action="/api/ajax?class=Faturamento.php" id="ajustar_faturamento" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="cancelados" id="cancelados" value="<?=$cancelados?>">
                    <input type="hidden" name="ordenacao" id="ordenacao" value="<?=$ordenacao?>">
                    <input type="hidden" name="servico" id="servico" value="<?=$servico?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<div class="row">
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Qtd. Clientes:</label>
                                    <input name="qtd_clientes" id="qtd_clientes" type="number" class="form-control input-sm number_int" value="<?=$qtd_clientes?>" autocomplete="off" />
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

    $(document).on('submit', '#ajustar_faturamento', function () {
        modalAguarde();
    });

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    }) 

</script>