<?php
require_once(__DIR__."/System.php");

$primeiro_dia_mes_atual = new DateTime(getDataHora('data'));
$primeiro_dia_mes_atual->modify('first day of this month');
$mes_atual = $primeiro_dia_mes_atual->format('m');
$primeiro_dia_mes_atual = $primeiro_dia_mes_atual->format('Y-m-d');

$ultimo_dia_mes_atual = new DateTime(getDataHora('data'));
$ultimo_dia_mes_atual->modify('last day of this month');
$ultimo_dia_mes_atual = $ultimo_dia_mes_atual->format('Y-m-d');

$primeiro_dia_proximo_mes = new DateTime(getDataHora('data'));
$primeiro_dia_proximo_mes->modify('first day of next month');
$proximo_mes = $primeiro_dia_proximo_mes->format('m');
$primeiro_dia_proximo_mes = $primeiro_dia_proximo_mes->format('Y-m-d');

$ultimo_dia_proximo_mes = new DateTime(getDataHora('data'));
$ultimo_dia_proximo_mes->modify('last day of next month');
$ultimo_dia_proximo_mes = $ultimo_dia_proximo_mes->format('Y-m-d');

$primeiro_dia_mes_passado = new DateTime(getDataHora('data'));
$primeiro_dia_mes_passado->modify('first day of last month');
$mes_passado = $primeiro_dia_mes_passado->format('m');
$primeiro_dia_mes_passado = $primeiro_dia_mes_passado->format('Y-m-d');

$meses_ano = array(
    '01' => 'janeiro',
    '02' => 'fevereiro',
    '03' => 'março',
    '04' => 'abril',
    '05' => 'maio',
    '06' => 'junho',
    '07' => 'julho',
    '08' => 'agosto',
    '09' => 'setembro',
    '10' => 'outubro',
    '11' => 'novembro',
    '12' => 'dezembro'
);

$cont_contrato_realiza_cobranca = 0;

$dados_faturamento = DBRead('','tb_faturamento', "WHERE data_referencia = '".$primeiro_dia_mes_passado."' AND status = 1 AND adesao = 0",'id_faturamento');
$dados_conta_receber_faturamento = DBRead('','tb_conta_receber',"WHERE id_faturamento AND data_vencimento >= '$primeiro_dia_mes_atual' AND data_vencimento <= '$ultimo_dia_mes_atual' GROUP BY id_faturamento",'id_faturamento');
if($dados_faturamento){
    foreach ($dados_faturamento as $conteudo_faturamento) {
        if($dados_conta_receber_faturamento){
            $flag_conta = 1;
            foreach ($dados_conta_receber_faturamento as $conteudo_conta_receber_faturamento) {
                if($conteudo_conta_receber_faturamento['id_faturamento'] == $conteudo_faturamento['id_faturamento']){
                    $flag_conta = 0;
                }
            }
            if($flag_conta){
                // echo "<br>".$conteudo_faturamento['id_faturamento'];
                $cont_contrato_realiza_cobranca++;
            }
        }else{
            // echo "<br>".$conteudo_faturamento['id_faturamento'];
            $cont_contrato_realiza_cobranca++;
        }
    }
}

$dados_conta_receber_atrasada = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND data_vencimento < '".getDataHora('data')."'","SUM(valor) AS 'valor'");
$valor_conta_receber_atrasada = $dados_conta_receber_atrasada[0]['valor'] ? $dados_conta_receber_atrasada[0]['valor'] : 0.00;
$dados_conta_pagar_atrasada = DBRead('','tb_conta_pagar',"WHERE situacao = 'aberta' AND data_vencimento < '".getDataHora('data')."'","SUM(valor) AS 'valor'");
$valor_conta_pagar_atrasada = $dados_conta_pagar_atrasada[0]['valor'] ? $dados_conta_pagar_atrasada[0]['valor'] : 0.00;

$dados_conta_receber_hoje = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND data_vencimento = '".getDataHora('data')."'","SUM(valor) AS 'valor'");
$valor_conta_receber_hoje = $dados_conta_receber_hoje[0]['valor'] ? $dados_conta_receber_hoje[0]['valor'] : 0.00;
$dados_conta_pagar_hoje = DBRead('','tb_conta_pagar',"WHERE situacao = 'aberta' AND data_vencimento = '".getDataHora('data')."'","SUM(valor) AS 'valor'");
$valor_conta_pagar_hoje = $dados_conta_pagar_hoje[0]['valor'] ? $dados_conta_pagar_hoje[0]['valor'] : 0.00;

if(getDataHora('data') == $ultimo_dia_mes_atual){
    $primeiro_dia_contas_mes = $primeiro_dia_proximo_mes;
    $ultimo_dia_contas_mes = $ultimo_dia_proximo_mes;
}else{
    $primeiro_dia_contas_mes = date('Y-m-d', strtotime("+1 days",strtotime(getDataHora('data'))));
    $ultimo_dia_contas_mes = $ultimo_dia_mes_atual;
}

$dados_conta_receber_restante_mes = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND data_vencimento >= '$primeiro_dia_contas_mes' AND data_vencimento <= '$ultimo_dia_contas_mes'","SUM(valor) AS 'valor'");
$valor_conta_receber_restante_mes = $dados_conta_receber_restante_mes[0]['valor'] ? $dados_conta_receber_restante_mes[0]['valor'] : 0.00;
$dados_conta_pagar_restante_mes = DBRead('','tb_conta_pagar',"WHERE situacao = 'aberta' AND data_vencimento >= '$primeiro_dia_contas_mes' AND data_vencimento <= '$ultimo_dia_contas_mes'","SUM(valor) AS 'valor'");
$valor_conta_pagar_restante_mes = $dados_conta_pagar_restante_mes[0]['valor'] ? $dados_conta_pagar_restante_mes[0]['valor'] : 0.00;

$dados_conta_receber_mes_atual = DBRead('','tb_conta_receber',"WHERE (situacao = 'aberta' OR situacao = 'quitada') AND data_vencimento >= '$primeiro_dia_mes_atual' AND data_vencimento <= '$ultimo_dia_mes_atual'","COUNT(*) AS 'qtd', SUM(valor) AS 'valor'");
$cont_conta_receber_mes_atual = $dados_conta_receber_mes_atual[0]['qtd'];
$valor_conta_receber_mes_atual = $dados_conta_receber_mes_atual[0]['valor'] ? $dados_conta_receber_mes_atual[0]['valor'] : 0.00;

$dados_conta_pagar_mes_atual = DBRead('','tb_conta_pagar',"WHERE (situacao = 'aberta' OR situacao = 'quitada') AND data_vencimento >= '$primeiro_dia_mes_atual' AND data_vencimento <= '$ultimo_dia_mes_atual'","COUNT(*) AS 'qtd', SUM(valor) AS 'valor'");
$cont_conta_pagar_mes_atual = $dados_conta_pagar_mes_atual[0]['qtd'];
$valor_conta_pagar_mes_atual = $dados_conta_pagar_mes_atual[0]['valor'] ? $dados_conta_pagar_mes_atual[0]['valor'] : 0.00;

$dados_conta_receber_atrasada_mes_atual = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND data_vencimento >= '$primeiro_dia_mes_atual' AND data_vencimento < '".getDataHora('data')."'","COUNT(*) AS 'qtd', SUM(valor) AS 'valor'");
$cont_conta_receber_atrasada_mes_atual = $dados_conta_receber_atrasada_mes_atual[0]['qtd'];
$valor_conta_receber_atrasada_mes_atual = $dados_conta_receber_atrasada_mes_atual[0]['valor'] ? $dados_conta_receber_atrasada_mes_atual[0]['valor'] : 0.00;

$dados_conta_receber_ultimos_30_dias = DBRead('','tb_conta_receber',"WHERE (situacao = 'aberta' OR situacao = 'quitada') AND data_vencimento >= '".date('Y-m-d', strtotime("-30 days",strtotime(getDataHora('data'))))."' AND data_vencimento < '".getDataHora('data')."'","COUNT(*) AS 'qtd', SUM(valor) AS 'valor'");
$cont_conta_receber_ultimos_30_dias = $dados_conta_receber_ultimos_30_dias[0]['qtd'];
$valor_conta_receber_ultimos_30_dias = $dados_conta_receber_ultimos_30_dias[0]['valor'] ? $dados_conta_receber_ultimos_30_dias[0]['valor'] : 0.00;

$dados_conta_receber_atrasada_ultimos_30_dias = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND data_vencimento >= '".date('Y-m-d', strtotime("-30 days",strtotime(getDataHora('data'))))."' AND data_vencimento < '".getDataHora('data')."'","COUNT(*) AS 'qtd', SUM(valor) AS 'valor'");
$cont_conta_receber_atrasada_ultimos_30_dias = $dados_conta_receber_atrasada_ultimos_30_dias[0]['qtd'];
$valor_conta_receber_atrasada_ultimos_30_dias = $dados_conta_receber_atrasada_ultimos_30_dias[0]['valor'] ? $dados_conta_receber_atrasada_ultimos_30_dias[0]['valor'] : 0.00;

$dados_boleto_remessa_pendente_15_dias = DBRead('', 'tb_boleto', "WHERE remessa_pendente = 1 AND titulo_data_vencimento <= '".date('Y-m-d', strtotime("+15 days",strtotime(getDataHora('data'))))."'","COUNT(*) AS 'qtd'");

$cont_boleto_remessa_pendente_15_dias = $dados_boleto_remessa_pendente_15_dias[0]['qtd'];

$array_receber = array();
$array_pagar = array();
$array_saldo = array();
$saldo = 0;
$array_dias = array();
foreach(rangeDatas($primeiro_dia_mes_atual, $ultimo_dia_mes_atual) as $data){
    $data_explode = explode("-", $data);
    $data_dia_mes = $data_explode[2].'/'.$data_explode[1];
        
    if (!in_array($data_dia_mes, $array_dias) && $data_dia_mes) { 
        $array_dias[] = $data_dia_mes;
    }

    $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE (situacao = 'aberta' OR situacao = 'quitada') AND data_vencimento = '".$data."'", "SUM(valor) AS 'valor'");
    $valor_conta_receber = $dados_conta_receber[0]['valor'] ? $dados_conta_receber[0]['valor'] : 0.00;

    $dados_conta_pagar = DBRead('','tb_conta_pagar',"WHERE(situacao = 'aberta' OR situacao = 'quitada') AND data_vencimento = '".$data."'", "SUM(valor) AS 'valor'");
    $valor_conta_pagar = $dados_conta_pagar[0]['valor'] ? $dados_conta_pagar[0]['valor'] : 0.00;

    $array_receber[] = $valor_conta_receber;
    $array_pagar[] = $valor_conta_pagar;
    $array_saldo[] = $saldo + ($valor_conta_receber - $valor_conta_pagar);

    $saldo += $valor_conta_receber - $valor_conta_pagar;
}
?>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-4">
        <button type="button" class="btn btn-danger" style="width: 100%; cursor:default;">
            <div class="col-md-1">
                <i class="fas fa-hand-holding-usd" style="font-size: 70px; opacity: 0.3;"></i>
            </div>
            <div class="col-md-10 col-md-offset-right-1">
                <div class="row"  style="font-size: 25px;">Em atraso (Geral)</div>
                <div class="row">Contas a Receber: <strong>R$ <?=converteMoeda($valor_conta_receber_atrasada,'moeda')?></strong></div>
                <div class="row">Contas a Pagar: <strong>R$ <?=converteMoeda($valor_conta_pagar_atrasada,'moeda')?></strong></div>               
            </div>
        </button>
    </div>
    <div class="col-md-4">
        <button type="button" class="btn btn-warning" style="width: 100%; cursor:default;">
            <div class="col-md-1">
                <i class="fas fa-hand-holding-usd" style="font-size: 70px; opacity: 0.3;"></i>
            </div>
            <div class="col-md-10 col-md-offset-right-1">
                <div class="row"  style="font-size: 25px;">Hoje</div>
                <div class="row">Contas a Receber: <strong>R$ <?=converteMoeda($valor_conta_receber_hoje,'moeda')?></strong></div>
                <div class="row">Contas a Pagar: <strong>R$ <?=converteMoeda($valor_conta_pagar_hoje,'moeda')?></strong></div>               
            </div>
        </button>
    </div>    
    <div class="col-md-4">
        <button type="button" class="btn btn-info" style="width: 100%; cursor:default;">
            <div class="col-md-1">
                <i class="fas fa-hand-holding-usd" style="color: white;font-size: 65px; opacity: 0.3;"></i>
            </div>
            <div class="col-md-10 col-md-offset-right-1">
                <div class="row"  style="font-size: 25px;"><?=(getDataHora('data') == $ultimo_dia_mes_atual ? 'Em '.$meses_ano[$proximo_mes] : 'Restante de '.$meses_ano[$mes_atual])?></div>
                <div class="row">Contas a Receber: <strong>R$ <?=converteMoeda($valor_conta_receber_restante_mes,'moeda')?></strong></div>
                <div class="row">Contas a Pagar: <strong>R$ <?=converteMoeda($valor_conta_pagar_restante_mes,'moeda')?></strong></div>               
            </div>
        </button>
    </div>       
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <legend class="text-center"><strong>Geração de Caixa de <?=$meses_ano[$mes_atual];?></strong></legend>
        <div id="grafico_mensal"></div>
    </div>
    <div class="col-md-6">
        <legend class="text-center"><strong>Outras Informações</strong></legend>
        <ul class="list-group">
            <li class="list-group-item">
                <span class="badge"><?=$cont_contrato_realiza_cobranca?></span>
                Contratos a faturar em <strong><?=$meses_ano[$mes_atual];?></strong>
            </li> 
            <li class="list-group-item">
                <span class="badge"><?=$cont_conta_receber_mes_atual?></span>
                Total de Contas a Receber em <strong><?=$meses_ano[$mes_atual];?></strong> (R$ <?=converteMoeda($valor_conta_receber_mes_atual,'moeda')?>)
            </li>
            <li class="list-group-item">
                <span class="badge"><?=$cont_conta_pagar_mes_atual?></span>
                Total de Contas a Pagar em <strong><?=$meses_ano[$mes_atual];?></strong> (R$ <?=converteMoeda($valor_conta_pagar_mes_atual,'moeda')?>)
            </li>
            <li class="list-group-item">
                <span class="badge"><?=$cont_conta_receber_atrasada_mes_atual?></span>
                Contas a Receber em <strong><?=$meses_ano[$mes_atual];?></strong> atrasadas (R$ <?=converteMoeda($valor_conta_receber_atrasada_mes_atual,'moeda')?>) (<?=sprintf("%01.2f", round($valor_conta_receber_atrasada_mes_atual*100/($valor_conta_receber_mes_atual == 0 ? 1 : $valor_conta_receber_mes_atual), 2))?>%)
            </li>
            <li class="list-group-item">
                <span class="badge"><?=$cont_conta_receber_ultimos_30_dias?></span>
                Total de Contas a Receber nos <strong>últimos 30 dias</strong> (R$ <?=converteMoeda($valor_conta_receber_ultimos_30_dias,'moeda')?>)
            </li>           
            <li class="list-group-item">
                <span class="badge"><?=$cont_conta_receber_atrasada_ultimos_30_dias?></span>
                Contas a Receber nos <strong>últimos 30 dias</strong> atrasadas (R$ <?=converteMoeda($valor_conta_receber_atrasada_ultimos_30_dias,'moeda')?>) (<?=sprintf("%01.2f", round($valor_conta_receber_atrasada_ultimos_30_dias*100/($valor_conta_receber_ultimos_30_dias == 0 ? 1 : $valor_conta_receber_ultimos_30_dias), 2))?>%)
            </li>
            <li class="list-group-item">
                <span class="badge"><?=$cont_boleto_remessa_pendente_15_dias?></span>
                Boletos com remessas bancárias não criadas e vencimento nos <strong>próximos 15 dias</strong>
            </li>
        </ul>
    </div>
</div>
<script>
    $(function () {
        // Create the first chart
        $('#grafico_mensal').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: '' // Title for the chart
            },
            xAxis: {
                categories: <?php echo json_encode(array_values($array_dias)) ?>
                // Categories for the charts
                ,labels: {
                formatter: function () {
                        if ('<?=(new DateTime(getDataHora('data')))->format('d/m')?>' === this.value) {
                            return '<span style="fill: red;">' + this.value + '</span>';
                        } else {
                            return this.value;
                        }
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Valor em R$'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                    }
                }
            },
            legend: {
                enabled:true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {                    
                shared: true,
                formatter: function () {
                    var points = this.points;
                    var pointsLength = points.length;
                    var keyColor = '';
                    if ('<?=(new DateTime(getDataHora('data')))->format('d/m')?>' === points[0].key) {
                        keyColor = 'fill: red;';
                    }
                    var tooltipMarkup = pointsLength ? '<span style="font-size: 10px;'+keyColor+'">' + points[0].key + '</span><br/>' : '';
                    var index;
                    var value;

                    for(index = 0; index < pointsLength; index += 1) {
                        value = floatMoeda((points[index].y).toFixed(2));
                        if(points[index].y < 0){
                            value = '-'+value;
                        }
                        tooltipMarkup += '<span style="color:' + points[index].series.color + '">\u25CF</span> ' + points[index].series.name + ': <b>R$ ' + value  + ' </b><br/>';
                    }

                    return tooltipMarkup;
                }
            },
            colors: [
                '#006400',
                '#800000',
                '#00008B'
            ],
            plotOptions: {                                                        
                series: {
                    dataLabels: {
                        enabled: false
                    }
                }
            },
            series: [
                {
                    name: 'Contas a Receber', // Name of your series
                    data: <?php echo "[".join(",", $array_receber)."]" ?> // The data in your series
                },
                {
                name: 'Contas a Pagar', // Name of your series
                    data: <?php echo "[".join(",", $array_pagar)."]" ?> // The data in your series
                },                       
                {
                    name: 'Saldo Mensal', // Name of your series
                    data: <?php echo "[".join(",", $array_saldo)."]" ?> // The data in your series
                }
            ],
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            }
        });
    });
</script> 