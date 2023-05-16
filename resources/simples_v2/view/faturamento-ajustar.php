<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'ajustar_ativo';
    $id = (int)$_GET['alterar'];
    $cancelados = (int)$_GET['cancelados'];
    $ordenacao = $_GET['ordenacao'];

    $dados = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa
     = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_faturamento = $id", "a.*, d.nome, e.nome AS nome_plano, e.cod_servico");

    $nome = $dados[0]['nome'];

    if($$dados[0]['tipo_cobranca'] == 'mensal_desafogo'){
        $tipo_cobranca = "Mensal com Desafogo";
    }else if($$dados[0]['tipo_cobranca'] == 'cliente_base'){
        $tipo_cobranca = "Clientes na Base";
    }else if($$dados[0]['tipo_cobranca'] == 'cliente_ativo'){
        $tipo_cobranca = "Clientes Ativos";
    }else if($$dados[0]['tipo_cobranca'] == 'x_cliente_base'){
        $tipo_cobranca = "Até X Clientes na Base";
    }else if($$dados[0]['tipo_cobranca'] == 'prepago'){
        $tipo_cobranca = "Pré-pago";
    }else{
        $tipo_cobranca = ucfirst($$dados[0]['tipo_cobranca']);
    }

    $nome_plano = $dados[0]['nome_plano'];
    $valor_total = $dados[0]['valor_total'];
    $qtd_contratada = $dados[0]['qtd_contratada'];
    $qtd_efetuada = $dados[0]['qtd_efetuada'];
    $qtd_excedente = $dados[0]['qtd_excedente'];
    $valor_excedente_contrato = $dados[0]['valor_excedente_contrato'];
    $valor_inicial_contrato = $dados[0]['valor_inicial_contrato'];
    $valor_cobranca = $dados[0]['valor_cobranca'];
    $valor_unitario_contrato = $dados[0]['valor_unitario_contrato'];
    $valor_total_contrato = $dados[0]['valor_total_contrato'];
    $valor_plantao = $dados[0]['valor_plantao_contrato'];
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
                    <h3 class="panel-title text-left pull-left"><?php echo $tituloPainel.' Faturamento: <strong>'.$nome.'</strong>'; ?></h3>
                </div>
                <form method="post" action="/api/ajax?class=Faturamento.php" id="ajustar_faturamento" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="cancelados" id="cancelados" value="<?=$cancelados?>">
                    <input type="hidden" name="ordenacao" id="ordenacao" value="<?=$ordenacao?>">
                    <input type="hidden" name="servico" id="servico" value="<?=$servico?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Cobrança:</label>
                                    <input name="tipo_cobranca" id="tipo_cobranca" type="text" class="form-control input-sm" value="<?=$tipo_cobranca;?>" autocomplete="off" disabled/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Plano:</label>
                                    <input name="nome_plano" id="nome_plano" type="text" class="form-control input-sm" value="<?=$nome_plano;?>" autocomplete="off" disabled />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Total do Contrato:</label>
                                    <input name="valor_total_contrato" id="valor_total_contrato" type="text" class="form-control input-sm money" value="<?=converteMoeda($valor_total_contrato);?>" autocomplete="off" required <?= $disabled ?> />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Qtd. Contratada:</label>
                                    <input name="qtd_contratada" id="qtd_contratada" type="number" class="form-control input-sm number_int" value="<?=$qtd_contratada;?>" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Qtd. Efetuada:</label>
                                    <input name="qtd_efetuada" id="qtd_efetuada" type="number" class="form-control input-sm number_int" value="<?=$qtd_efetuada;?>" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Qtd. Excedente:</label>
                                    <input name="qtd_excedente" id="qtd_excedente" type="number" class="form-control input-sm number_int" value="<?=$qtd_excedente;?>" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Unitário do Excedente:</label>
                                    <input name="valor_excedente_contrato" id="valor_excedente_contrato" type="text" class="form-control input-sm money" value="<?=converteMoeda($valor_excedente_contrato);?>" autocomplete="off" />
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Unitário:</label>
                                    <input name="valor_unitario_contrato" id="valor_unitario_contrato" type="text" class="form-control input-sm money" value="<?=converteMoeda($valor_unitario_contrato);?>" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Total: <a tabindex="0" data-toggle="tooltip" title="Soma de todos os valores."><i class="fa fa-question-circle"></i></a></label>
                                    <input name="valor_total" id="valor_total" type="text" class="form-control input-sm money" value="<?=converteMoeda($valor_total);?>" autocomplete="off" style="border: 1px solid #809fff;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor Cobrança: <a tabindex="0" data-toggle="tooltip" title="Valor total com descontos ou acréscimos."><i class="fa fa-question-circle"></i></a></label>
                                    <input name="valor_cobranca" id="valor_cobranca" type="text" class="form-control input-sm money" value="<?=converteMoeda($valor_cobranca);?>" autocomplete="off" style="border: 1px solid #809fff;" />
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