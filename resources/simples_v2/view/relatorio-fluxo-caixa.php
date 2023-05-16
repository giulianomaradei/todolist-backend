<?php
require_once(__DIR__."/../class/System.php");

$incluir_contas_receber_atrasadas = 0;
$considerar_contratos = 1;
$data_ate_fluxo = new DateTime(getDataHora('data'));
$ano_ate_fluxo = $data_ate_fluxo->format('Y')+1;
$data_ate_fluxo = '31/12/'.$ano_ate_fluxo;
$dias_descarte = 0;
$caixas = '';
$visualizar = (isset($_GET['visualizar'])) ? (int)$_GET['visualizar'] : 0;

if($visualizar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if($incluir_contas_receber_atrasadas == 1){
    $display_row_dias_descarte = '';
}else{
    $display_row_dias_descarte = 'style="display:none;"';
}

?>

<style>
    @media print {
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
    }
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default noprint">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Fluxo de Caixa:</h3>
                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
                </div>
                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
                    <div class="panel-body" style="max-height: 400px; overflow-y:auto;">                    
                    <?php
                        $dados_fluxo_caixa = DBRead('','tb_fluxo_caixa a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa ORDER BY a.data_criacao DESC", "a.*, c.nome AS 'nome_usuario'"); 
                        if($dados_fluxo_caixa){
                            ?>
                            <div class='table-responsive'>
                                <table class='table table-hover' style='font-size: 14px;'>
                                    <thead>
                                        <tr>
                                            <th>Data Inicial</th>
                                            <th>Data Final</th>
                                            <th>C.R. Atrasadas</th>
                                            <th>Dias de Atraso</th>
                                            <th>Caixas</th>
                                            <th>Contratos</th>
                                            <th>Gerado por</th>
                                            <th class='text-center'>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($dados_fluxo_caixa as $conteudo) {
                                        $dados_fluxo_caixa_caixa = DBRead('','tb_fluxo_caixa_caixa a',"INNER JOIN tb_caixa b ON a.id_caixa = b.id_caixa WHERE a.id_fluxo_caixa = '".$conteudo['id_fluxo_caixa']."' ORDER BY b.nome ASC");
                                        $texto_caixas = '';
                                        if($dados_fluxo_caixa_caixa){
                                            foreach ($dados_fluxo_caixa_caixa as $conteudo_fluxo_caixa_caixa) {
                                                $texto_caixas .=$conteudo_fluxo_caixa_caixa['nome'].'<br>';
                                            }
                                        }else{
                                            $texto_caixas = 'Nenhum';
                                        }
                                        if($conteudo['incluir_contas_receber_atrasadas']){
                                            $texto_incluir_contas_receber_atrasadas = 'Sim';
                                        }else{
                                            $texto_incluir_contas_receber_atrasadas = 'Não';
                                        }
                                        if($conteudo['considerar_contratos']){
                                            $texto_considerar_contratos = 'Sim';
                                        }else{
                                            $texto_considerar_contratos = 'Não';
                                        }
                                        echo"
                                        <tr>
                                            <td class='click_aguarde' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."'\" style='cursor: pointer'>".converteDataHora($conteudo['data_criacao'])."</td>
                                            <td class='click_aguarde' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."'\" style='cursor: pointer'>".converteData($conteudo['data_final'])."</td>
                                            <td class='click_aguarde' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."'\" style='cursor: pointer'>".$texto_incluir_contas_receber_atrasadas."</td>
                                            <td class='click_aguarde' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."'\" style='cursor: pointer'>".$conteudo['dias_conta_receber_atrasadas']."</td>
                                            <td><a tabindex='0' role='button' style='cursor:pointer;' data-toggle='popover' data-html='true' data-trigger='focus' title='Caixas Utilizados:' data-content='".$texto_caixas."'><i class='fas fa-info-circle'></i></a></td>
                                            <td class='click_aguarde' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."'\" style='cursor: pointer'>".$texto_considerar_contratos."</td>
                                            <td class='click_aguarde' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."'\" style='cursor: pointer'>".$conteudo['nome_usuario']."</td>                                            
                                            <td class='text-center'><a href='/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa&visualizar=".$conteudo['id_fluxo_caixa']."' title='Visualizar'><i class='fa fa-eye click_aguarde'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='class/FluxoCaixa.php?excluir=".$conteudo['id_fluxo_caixa']."' title='Excluir' onclick=\"if (!confirm('Tem certeza que deseja excluir?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>
                                        </tr>
                                        ";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }else{
                            echo "<p class='alert alert-warning' style='text-align: center; margin-bottom:0px;'>Não foram encontrados fluxos de caixa salvos. Gere um novo!</p>";                            
                        }
                    ?>	                        
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div id="panel_buttons" class="col-md-12" style="text-align: center">
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal_gerar"><i class="fa fa-plus"></i> Novo</button>
                            <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div>	
	<div id="resultado" class="row" style="display:none;">		
		<?php 
		if($visualizar){
			visualizar($visualizar);
		}
		?>
	</div>
</div>

<div class="modal fade noprint" id="modal_gerar"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gerar novo</h4>
            </div>
            <form action="class/FluxoCaixa.php" method="post">
                <div class="modal-body form" style="margin-bottom: 0px; padding-bottom: 0px;">
                    <div class="row" id="row_periodo_fluxo" <?=$display_row_periodo_fluxo?>>
                        <div class="col-md-12">
                            <div class="form-group" >
                                <label>*Data Até:</label>
                                <input type="text" class="form-control input-sm date calendar" name="data_ate_fluxo" value="<?=$data_ate_fluxo?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row_caixas" <?=$display_row_caixas?>>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">*Caixas Utilizados:</label>
                                <select name="caixas[]" class="form-control input-sm" multiple="multiple" size=7  required>
                                    <?php
                                        $dados_caixas = DBRead('','tb_caixa',"WHERE status = 1 ORDER BY nome ASC");
                                        if($dados_caixas){
                                            foreach ($dados_caixas as $conteudo_caixas) {
                                                if(preg_match('/'.$conteudo_caixas['id_caixa'].'/i', $caixas)){
                                                    $sel_caixa = 'selected';
                                                }else{
                                                    $sel_caixa = '';
                                                }
                                                echo "<option value='".$conteudo_caixas['id_caixa']."' $sel_caixa>".$conteudo_caixas['nome']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row_incluir_contas_receber_atrasadas" <?=$display_row_incluir_contas_receber_atrasadas?>>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Incluir Contas a Receber Atrasadas:</label>
                                <select name="incluir_contas_receber_atrasadas" id="incluir_contas_receber_atrasadas" class="form-control input-sm">
                                    <option value="0" <?php if($incluir_contas_receber_atrasadas == '0'){ echo 'selected';}?>>Não</option>
                                    <option value="1" <?php if($incluir_contas_receber_atrasadas == '1'){ echo 'selected';}?>>Sim</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row_dias_descarte" <?=$display_row_dias_descarte?>>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">*Dias de Atraso:</label><br>
                                <input type="number" class="form-control input-sm" id='dias_descarte' name="dias_descarte" value="<?=$dias_descarte?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row_considerar_contratos" <?=$display_row_considerar_contratos?>>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Considerar Contratos:</label>
                                <select name="considerar_contratos" id="considerar_contratos" class="form-control input-sm">
                                    <option value="0" <?php if($considerar_contratos == '0'){ echo 'selected';}?>>Não</option>
                                    <option value="1" <?php if($considerar_contratos == '1'){ echo 'selected';}?>>Sim</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="submit" class="btn btn-primary" name="gerar" id="gerar" value="1" disabled><i class="fa fa-check"></i> Ok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
    $(document).on('submit', 'form', function(){
        modalAguarde();
    });
    $(".click_aguarde").on('click', function(){
        modalAguarde();
    });
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});
	$('#incluir_contas_receber_atrasadas').on('change',function(){
		if($('#incluir_contas_receber_atrasadas').val() == 1){
			$('#row_dias_descarte').show();
		}else{
            $('#row_dias_descarte').hide();
            $('#incluir_contas_receber_atrasadas').val(0);
            $('#dias_descarte').val(0);
		}
	}); 

    $(function(){
        $('[data-toggle="popover"]').popover();
    })
</script>

<?php

function visualizar($id_fluxo_caixa){

    $dados_fluxo_caixa = DBRead('','tb_fluxo_caixa',"WHERE id_fluxo_caixa = '$id_fluxo_caixa'");
    
    if(!$dados_fluxo_caixa){
        echo '<div class="alert alert-danger text-center">Erro ao gerar o Fluxo de Caixa!</div>';
        exit;
    }

    $data_de = converteData($dados_fluxo_caixa[0]['data_inicial']);
    $data_ate = converteData($dados_fluxo_caixa[0]['data_final']);
    $data_criacao = converteDataHora($dados_fluxo_caixa[0]['data_criacao']);
    $incluir_contas_receber_atrasadas = $dados_fluxo_caixa[0]['incluir_contas_receber_atrasadas'];
    $considerar_contratos = $dados_fluxo_caixa[0]['considerar_contratos'];
    $dias_descarte = $dados_fluxo_caixa[0]['dias_conta_receber_atrasadas'];


	if($incluir_contas_receber_atrasadas == 1){
		$legenda_incluir_contas_receber_atrasadas = "Sim, <strong> Dias de Atraso - </strong>".$dias_descarte." dias";
	}else{
		$legenda_incluir_contas_receber_atrasadas = "Não";
		$dias_descarte = '';
    }	

	if($considerar_contratos == 1){
		$legenda_considerar_contratos = "Sim";
	}else{
		$legenda_considerar_contratos = "Não";
    }	
    
    $dados_fluxo_caixa_caixa = DBRead('','tb_fluxo_caixa_caixa a',"INNER JOIN tb_caixa b ON a.id_caixa = b.id_caixa WHERE a.id_fluxo_caixa = '$id_fluxo_caixa'");
    $legenda_caixas = '';
    if($dados_fluxo_caixa_caixa){
        foreach ($dados_fluxo_caixa_caixa as $conteudo_fluxo_caixa_caixa) {
            $legenda_caixas .=$conteudo_fluxo_caixa_caixa['nome'].', ';
        }
        $legenda_caixas = substr($legenda_caixas, 0, -2);
    }else{
        $legenda_caixas = 'Nenhum';
    }

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_criacao</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Fluxo de Caixa</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Incluir Contas a Receber Atrasadas - </strong>".$legenda_incluir_contas_receber_atrasadas."</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Caixas - </strong>".$legenda_caixas."</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Considerar Contratos - </strong>".$legenda_considerar_contratos."</legend>";
    
    $array_mes_ano = array();
    $array_dia_mes_ano = array();

	$array_total_pagar_mes_ano = array();
    $array_total_pagar_dia_mes_ano = array();
    $array_total_receber_mes_ano = array();
    $array_total_receber_dia_mes_ano = array(); 
    $array_saldo_mes_ano = array();
    $array_saldo_dia_mes_ano = array();  
    $array_resultado_mes_ano = array();
    $array_resultado_dia_mes_ano = array();  

    $dados_fluxo_caixa_dados = DBRead('','tb_fluxo_caixa_dados',"WHERE id_fluxo_caixa = '$id_fluxo_caixa'");
    
    if($dados_fluxo_caixa_dados){
        foreach ($dados_fluxo_caixa_dados as $conteudo_fluxo_caixa_dados) {
            if (!in_array($conteudo_fluxo_caixa_dados['mes_ano'], $array_mes_ano)) { 
                $array_mes_ano[] = $conteudo_fluxo_caixa_dados['mes_ano'];
            }
            if (!in_array($conteudo_fluxo_caixa_dados['dia_mes'], $array_dia_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']]) && $conteudo_fluxo_caixa_dados['dia_mes']) { 
                $array_dia_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']][] = $conteudo_fluxo_caixa_dados['dia_mes'];
            }

            if($conteudo_fluxo_caixa_dados['tipo_grafico'] == 'mensal'){
                if($conteudo_fluxo_caixa_dados['tipo_dado'] == 'conta_receber'){
                    $array_total_receber_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']] = $conteudo_fluxo_caixa_dados['valor'];
                }elseif($conteudo_fluxo_caixa_dados['tipo_dado'] == 'conta_pagar'){
                    $array_total_pagar_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']] = $conteudo_fluxo_caixa_dados['valor'];
                }elseif($conteudo_fluxo_caixa_dados['tipo_dado'] == 'saldo'){
                    $array_saldo_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']] = $conteudo_fluxo_caixa_dados['valor'];
                }elseif($conteudo_fluxo_caixa_dados['tipo_dado'] == 'resultado'){
                    $array_resultado_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']] = $conteudo_fluxo_caixa_dados['valor'];
                }
            }if($conteudo_fluxo_caixa_dados['tipo_grafico'] == 'diario'){
                if($conteudo_fluxo_caixa_dados['tipo_dado'] == 'conta_receber'){
                    $array_total_receber_dia_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']][$conteudo_fluxo_caixa_dados['dia_mes']] = $conteudo_fluxo_caixa_dados['valor'];
                }elseif($conteudo_fluxo_caixa_dados['tipo_dado'] == 'conta_pagar'){
                    $array_total_pagar_dia_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']][$conteudo_fluxo_caixa_dados['dia_mes']] = $conteudo_fluxo_caixa_dados['valor'];
                }elseif($conteudo_fluxo_caixa_dados['tipo_dado'] == 'saldo'){
                    $array_saldo_dia_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']][$conteudo_fluxo_caixa_dados['dia_mes']] = $conteudo_fluxo_caixa_dados['valor'];
                }elseif($conteudo_fluxo_caixa_dados['tipo_dado'] == 'resultado'){
                    $array_resultado_dia_mes_ano[$conteudo_fluxo_caixa_dados['mes_ano']][$conteudo_fluxo_caixa_dados['dia_mes']] = $conteudo_fluxo_caixa_dados['valor'];
                }
            }
        }
    ?>
    
        <div id="chart-meses"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#chart-meses').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Fluxo de Caixa - Previsão de <?=sizeof($array_mes_ano)?> meses' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode(array_values($array_mes_ano)) ?>
                        // Categories for the charts
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
                            var tooltipMarkup = pointsLength ? '<span style="font-size: 10px">' + points[0].key + '</span><br/>' : '';
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
                        '#FF00FF',
                        '#00008B'
                    ],
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: false
                            },
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function () {
                                        var mes_ano = this.category.split('/');
                                        mes_ano = mes_ano[0]+'-'+mes_ano[1];
                                        if($('#'+mes_ano).is(":visible")){
                                            $('#'+mes_ano).hide();
                                        }else{
                                            $('.chart-mensal').hide();
                                            $('#'+mes_ano).show();
                                            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                                        }                                   
                                    }
                                }
                            }
                        }  
                    },
                    series: [
                        {
                            name: 'Contas a Receber', // Name of your series
                            data: <?php echo "[".join(",", $array_total_receber_mes_ano)."]" ?> // The data in your series

                        },
                        {
                            name: 'Contas a Pagar', // Name of your series
                            data: <?php echo "[".join(",", $array_total_pagar_mes_ano)."]" ?> // The data in your series

                        },
                        {
                            name: 'Resultado', // Name of your series
                            data: <?php echo "[".join(",", $array_resultado_mes_ano)."]" ?> // The data in your series

                        },                    
                        {
                            name: 'Saldo em Caixa', // Name of your series
                            data: <?php echo "[".join(",", $array_saldo_mes_ano)."]" ?> // The data in your series

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
        <hr> 

        <?php
        foreach ($array_dia_mes_ano as $mes_ano => $conteudo_dia_mes_ano) {
            $mes_ano_id = explode("/", $mes_ano);
            $mes_ano_id = $mes_ano_id[0].'-'.$mes_ano_id[1];
            ?>        
            <div id="<?php echo $mes_ano_id; ?>" class='chart-mensal' style="display:none;"></div> 
            <script>
                $(function () {
                    // Create the first chart
                    $('#<?php echo $mes_ano_id; ?>').highcharts({
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Fluxo de Caixa - Previsão de <?=$mes_ano?>' // Title for the chart
                        },
                        xAxis: {
                            categories: <?php echo json_encode(array_values($conteudo_dia_mes_ano)) ?>
                            // Categories for the charts
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
                                var tooltipMarkup = pointsLength ? '<span style="font-size: 10px">' + points[0].key + '</span><br/>' : '';
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
                            '#FF00FF',
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
                                data: <?php echo "[".join(",", $array_total_receber_dia_mes_ano[$mes_ano])."]" ?> // The data in your series
                            },
                            {
                            name: 'Contas a Pagar', // Name of your series
                                data: <?php echo "[".join(",", $array_total_pagar_dia_mes_ano[$mes_ano])."]" ?> // The data in your series
                            },
                            {
                                name: 'Resultado', // Name of your series
                                data: <?php echo "[".join(",", $array_resultado_dia_mes_ano[$mes_ano])."]" ?> // The data in your series
                            },                        
                            {
                                name: 'Saldo em Caixa', // Name of your series
                                data: <?php echo "[".join(",", $array_saldo_dia_mes_ano[$mes_ano])."]" ?> // The data in your series
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
            <?php  
        }
    }
    echo "</div>";
}

?>
