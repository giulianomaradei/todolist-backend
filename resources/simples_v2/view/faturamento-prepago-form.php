<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['ordenacao']) && isset($_GET['cancelados'])) {
    $operacao = 'inserir_prepago';
    $cancelados = (int)$_GET['cancelados'];
    $ordenacao = $_GET['ordenacao'];

}else{
    header("location: ../adm.php");
    exit;
}

$dados_meses = array(
    "01" => "Janeiro",
    "02" => "Fevereiro",
    "03" => "Março",
    "04" => "Abril",
    "05" => "Maio",
    "06" => "Junho",
    "07" => "Julho",
    "08" => "Agosto",
    "09" => "Setembro",
    "10" => "Outubro",
    "11" => "Novembro",
    "12" => "Dezembro",
);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Inserir Faturamento de Pré-Pago</h3>
                </div>
                <form method="post" action="/api/ajax?class=Faturamento.php" id="prepago" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="servico" id="servico" value="prepago">
                    <input type="hidden" name="cancelados" id="cancelados" value="<?=$cancelados?>">
                    <input type="hidden" name="ordenacao" id="ordenacao" value="<?=$ordenacao?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <select name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" class="form-control input-sm">
                                        <option value="">Selecione um Contrato...</option>
                                        <?php

                                        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.status = '1' AND tipo_cobranca = 'prepago' ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.nome_contrato, a.id_pessoa, b.nome, b.cpf_cnpj, b.razao_social, c.nome AS 'plano', c.cod_servico");

                                        if ($dados_contrato) {
                                            foreach ($dados_contrato as $conteudo_contrato) {
                                                if(!$dados_contrato_faturamento){
                                                    echo "<option value='".$conteudo_contrato['id_contrato_plano_pessoa']."'>".$conteudo_contrato['nome']." - ".getNomeServico($conteudo_contrato['cod_servico'])." - ".$conteudo_contrato['plano']." (".$conteudo_contrato['id_contrato_plano_pessoa'].")</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Mês de Referência: </label>
                                    <select name="mes_referencia" id="mes_referencia" class="form-control input-sm">
                                        <?php
                                            foreach ($dados_meses as $key => $conteudo_mes) {
                                                echo "<option value='2022-".$key."-01'>".$conteudo_mes."/22</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="a" name="<?= $operacao; ?>"/>
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

    $(document).on('submit', '#adesao', function () {
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == ''){
            alert('Deve-se selecionar o contrato!');
            return false;
        }
        modalAguarde();
    });


</script>