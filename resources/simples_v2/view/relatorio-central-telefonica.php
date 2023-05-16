<?php
require_once(__DIR__."/../class/System.php");
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$tipo_grafico = (!empty($_POST['tipo_grafico'])) ? $_POST['tipo_grafico'] : 'line';
$data = (!empty($_POST['data'])) ? $_POST['data'] : converteData(getDataHora('data'));
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : converteData(getDataHora('data'));
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$hora_de = (!empty($_POST['hora_de'])) ? $_POST['hora_de'] : '';
$hora_ate = (!empty($_POST['hora_ate'])) ? $_POST['hora_ate'] : '';
$empresa = (!empty($_POST['empresa'])) ? $_POST['empresa'] : '';
$operador = (!empty($_POST['operador'])) ? $_POST['operador'] : '';
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';
$tipo_pausa = (!empty($_POST['tipo_pausa'])) ? $_POST['tipo_pausa'] : '';
$fila = (!empty($_POST['fila'])) ? "'".join("','", $_POST['fila'])."'" : '';
$segundos_atendimento = (!empty($_POST['segundos_atendimento'])) ? $_POST['segundos_atendimento'] : 0;
$segundos_espera_perdida = (!empty($_POST['segundos_espera_perdida'])) ? $_POST['segundos_espera_perdida'] : 0;
$nota = (!empty($_POST['nota'])) ? $_POST['nota'] : '';
$numero = (!empty($_POST['numero'])) ? $_POST['numero'] : '';
$tag_saida = (!empty($_POST['tag_saida'])) ? $_POST['tag_saida'] : '';
$pa = (!empty($_POST['pa'])) ? $_POST['pa'] : '';
$plano = (!empty($_POST['plano'])) ? $_POST['plano'] : '';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$id_asterisk_usuario = $dados[0]['id_asterisk'];

$lider_atendente = (!empty($_POST['lider_atendente'])) ? $_POST['lider_atendente'] : '';

if ($gerar) {
	$collapse = '';
	$collapse_icon = 'plus';

} else {
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if ($tipo_relatorio == 1) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = '';
	$display_row_numero = 'style="display:none;"';
	$display_row_tag_saida = 'style="display:none;"';
	$display_row_lider = 'style="display:none;"';	
	$display_row_pa = 'style="display:none;"';
    $display_row_plano = '';
	$display_row_periodo_hora = 'style="display:none;"';
    
    if($empresa !== ''){
        $display_row_plano = 'style="display:none;"';
    }
	
	$display_row_lider_atendente =  'style="display:none;"';

} else if ($tipo_relatorio == 2) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 3) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = '';
	$display_row_tipo_pausa = '';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 4 || $tipo_relatorio == 6 || $tipo_relatorio == 17 || $tipo_relatorio == 10 || $tipo_relatorio == 11) {
	$display_row_tipo_grafico = '';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 5){
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';
	$display_row_pa =  'style="display:none;"';	
    $display_row_plano =  '';	
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';
    
    if($empresa !== ''){
        $display_row_plano = 'style="display:none;"';
	}
	
} else if ($tipo_relatorio == 27){
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';
	$display_row_pa =  'style="display:none;"';	
    $display_row_plano =  '';	
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';
    
    if($empresa !== ''){
        $display_row_plano = 'style="display:none;"';
	}
	
} else if ($tipo_relatorio == 7) {
	$display_row_tipo_grafico = '';
	$display_row_data = '';
	$display_row_periodo = 'style="display:none;"';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 8) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila =  'style="display:none;"';
	$display_row_segundos_atendimento =  'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota =  'style="display:none;"';
	$display_row_numero =  '';
	$display_row_tag_saida =  '';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 9) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila =  'style="display:none;"';
	$display_row_segundos_atendimento =  'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota =  'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 12) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = '';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  '';
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 13) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 14 || $tipo_relatorio == 15 || $tipo_relatorio == 16 || $tipo_relatorio == 18 || $tipo_relatorio == 19) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 20) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';	
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 21) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  '';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 22) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = 'style="display:none;"';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 23) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  '';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if($tipo_relatorio == 24) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = 'style="display:none;"';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 25) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = '';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 26) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = 'style="display:none;"';
	$display_row_empresa = 'style="display:none;"';
	$display_row_operador = '';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = 'style="display:none;"';
	$display_row_segundos_atendimento = 'style="display:none;"';
	$display_row_segundos_espera_perdida = 'style="display:none;"';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';	
	$display_row_pa =  'style="display:none;"';	
	$display_row_plano =  'style="display:none;"';
	$display_row_lider_atendente = '';
	$display_row_periodo_hora = 'style="display:none;"';

} else if ($tipo_relatorio == 28) {
	$display_row_tipo_grafico = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_empresa = '';
	$display_row_operador = 'style="display:none;"';
	$display_row_tipo_pausa = 'style="display:none;"';
	$display_row_fila = '';
	$display_row_segundos_atendimento = '';
	$display_row_segundos_espera_perdida = '';
	$display_row_nota = 'style="display:none;"';
	$display_row_numero =  'style="display:none;"';
	$display_row_tag_saida =  'style="display:none;"';
	$display_row_lider =  'style="display:none;"';
	$display_row_pa =  'style="display:none;"';	
    $display_row_plano =  '';	
	$display_row_lider_atendente =  'style="display:none;"';
	$display_row_periodo_hora = '';
    
    if($empresa !== ''){
        $display_row_plano = 'style="display:none;"';
	}
}

?>
<style>
    @media print {
    	@page {size: landscape}
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
        .highcharts-root {
			max-width: 1050px !important; 
			height: 400px; 
			margin: 0 auto;
        }		
       	text{
			font-size:17px !important;
        }
       .highcharts-title{
			font-size:25px !important;
    	}
    	g.highcharts-legend text{

			font-size:13px !important;
		}   
    }    
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/date-euro.js"></script>
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>
<div class="container-fluid">
	<form method="post" action="">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Central Telefônica:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	                		
                			<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
												<option value="26" <?php if($tipo_relatorio == '26'){ echo 'selected';}?>>Atendentes - Filas</option>											
											<?php }?> 
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Ligações Atendidas</option>                                            
                                            <option value="20" <?php if($tipo_relatorio == '20'){ echo 'selected';}?>>Ligações com Histórico</option>
											<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
								        	<option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Ligações Perdidas</option>
											<?php }?>
											<option value="8" <?php if($tipo_relatorio == '8'){ echo 'selected';}?>>Ligações Realizadas</option>
											<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
												<option value="5" <?php if($tipo_relatorio == '5'){ echo 'selected';}?>>Sintético Geral de Ligações</option>
												<option value="27" <?php if($tipo_relatorio == '27'){ echo 'selected';}?>>Sintético Geral de Ligações - Hora</option>
												<option value="28" <?php if($tipo_relatorio == '28'){ echo 'selected';}?>>Sintético Geral de Ligações - Acumulado Hora </option>
												<option value="13" <?php if($tipo_relatorio == '13'){ echo 'selected';}?>>Sintético de Ligações por Empresa</option>
												<option value="6" <?php if($tipo_relatorio == '6'){ echo 'selected';}?>>Gráfico de Ligações por Dia da Semana - Acumulado Geral</option>
												<option value="4" <?php if($tipo_relatorio == '4'){ echo 'selected';}?>>Gráfico de Ligações por Hora - Separado por Dia do Mês</option>
												<option value="10" <?php if($tipo_relatorio == '10'){ echo 'selected';}?>>Gráfico de Ligações por 20min - Separado por Dia do Mês</option>
												<option value="17" <?php if($tipo_relatorio == '17'){ echo 'selected';}?>>Gráfico de Ligações por Hora - Acumulado por Dia da Semana</option>
												<option value="11" <?php if($tipo_relatorio == '11'){ echo 'selected';}?>>Gráfico de Ligações por 20min - Acumulado por Dia da Semana</option>
												<option value="15" <?php if($tipo_relatorio == '15'){ echo 'selected';}?>>Gráfico de Tempos Médios por Dia da Semana - Acumulado Geral</option>	
												<option value="14" <?php if($tipo_relatorio == '14'){ echo 'selected';}?>>Gráfico de Tempos Médios por Hora - Separado por Dia do Mês</option>
												<option value="18" <?php if($tipo_relatorio == '18'){ echo 'selected';}?>>Gráfico de Tempos Médios por 20min - Separado por Dia do Mês</option>
												<option value="16" <?php if($tipo_relatorio == '16'){ echo 'selected';}?>>Gráfico de Tempos Médios por Hora - Acumulado por Dia da Semana</option>
												<option value="19" <?php if($tipo_relatorio == '19'){ echo 'selected';}?>>Gráfico de Tempos Médios por 20min - Acumulado por Dia da Semana</option>
												<option value="7" <?php if($tipo_relatorio == '7'){ echo 'selected';}?>>Gráfico diário de Ligações por Hora - Acumulado por Empresas</option>
												<option value="21" <?php if($tipo_relatorio == '21'){ echo 'selected';}?>>Histórico de Login e Logoff</option>
												<option value="23" <?php if($tipo_relatorio == '23'){ echo 'selected';}?>>Notas</option>
								        	<?php }?>
                                            <?php if($perfil_sistema != '28'){ ?>
											<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Pausas por Atendente</option>
											<option value="25" <?php if($tipo_relatorio == '25'){ echo 'selected';}?>>Pausas por Atendente - Tabela</option>											
                                            <?php }?>
											<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
											<option value="12" <?php if($tipo_relatorio == '12'){ echo 'selected';}?>>Pausas por Equipe</option>
                                            <option value="9" <?php if($tipo_relatorio == '9'){ echo 'selected';}?>>Tempo entre Ponto e Login</option>											
                                            <option value="22" <?php if($tipo_relatorio == '22'){ echo 'selected';}?>>Última Ligação por Empresa</option>
                                            <option value="24" <?php if($tipo_relatorio == '24'){ echo 'selected';}?>>Controle Automático de Filas</option>											
											<?php }?>
								        </select>
								    </div>
                				</div>
                			</div>

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
                			<div class="row" id="row_tipo_grafico" <?=$display_row_tipo_grafico?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo do gráfico:</label>
								        <select name="tipo_grafico" id="tipo_grafico" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_grafico == '1'){ echo 'selected';}?>>Linhas</option>	
								        	<option value="2" <?php if($tipo_grafico == '2'){ echo 'selected';}?>>Colunas</option>
								        	<option value="3" <?php if($tipo_grafico == '3'){ echo 'selected';}?>>Pilhas</option>
								        </select>
								    </div>
                				</div>
                			</div>

							<div class="row" id="row_data" <?=$display_row_data?>>
								<div class="col-md-12">
									<div class="form-group" >
								        <label>*Data:</label>
								        <input type="text" class="form-control date calendar" name="data" value="<?=$data?>" required>
								    </div>
								</div>
							</div>
							<?php }?>

							<div class="row" id="row_periodo" <?=$display_row_periodo?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Data Inicial:</label>
								        <input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=$data_de?>" required>
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Data Final:</label>
								        <input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=$data_ate?>" required>
								    </div>
								</div>
							</div>
							
							<div class="row" id="row_periodo_hora" <?=$display_row_periodo_hora?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Hora Inicial:</label>
								        <input type="time" class="form-control input-sm" name="hora_de" value="<?=$hora_de?>">
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Hora Final:</label>
								        <input type="time" class="form-control input-sm" name="hora_ate" value="<?=$hora_ate?>">
								    </div>
								</div>
							</div>

							<div class="row" id="row_empresa" <?=$display_row_empresa?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Empresa:</label>
								        <select name="empresa" id="empresa" class="form-control input-sm">
								        	<option value="">Todas</option>
								            <?php 
								            	$dados_empresas = DBRead('snep','empresas',"WHERE status!= 2 ORDER BY nome ASC");
								            	if($dados_empresas){
								            		foreach ($dados_empresas as $conteudo_empresas) {
														$selected = $empresa == $conteudo_empresas['id'] ? "selected" : "";
								            			echo "<option value='".$conteudo_empresas['id']."' ".$selected.">".$conteudo_empresas['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>

                            <?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
                			<div class="row" id="row_plano" <?=$display_row_plano?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Plano:</label>
								        <select name="plano" id="plano" class="form-control input-sm">
                                            <option value="">Todos</option>
								            <?php 
								            	$dados_planos = DBRead('','tb_plano',"WHERE cod_servico = 'call_suporte' AND status = 1 ORDER BY nome ASC");
								            	if($dados_planos){
								            		foreach ($dados_planos as $conteudo_planos) {
														$selected = $plano == $conteudo_planos['id_plano'] ? "selected" : "";
								            			echo "<option value='".$conteudo_planos['id_plano']."' ".$selected.">".$conteudo_planos['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
                				</div>
                			</div>
							<?php }?>

                            <?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
							<div class="row" id="row_pa" <?=$display_row_pa?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">PA:</label>
								        <select name="pa" class="form-control input-sm">
								        	<option value="">Todas</option>
								            <?php								            	
												$sel_pa[$pa] = 'selected';
                                                $cont = 5001;
                                                while($cont <= 5048){
													$selected = $pa == $cont ? "selected" : "";
                                                    echo "<option value='".$cont."' ".$sel_pa[$cont].">".substr($cont,1,3)."</option>";
                                                    $cont++;
                                                }
								            ?>
								        </select>
								    </div>
								</div>
							</div>
							<?php } ?>

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
							<div class="row" id="row_operador" <?=$display_row_operador?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Atendente:</label>
								        <select name="operador" id="operador" class="form-control input-sm">
								        	<option value="">Todos</option>
								            <?php
								            	$codigo_operador = explode('/', $operador);
												$codigo_operador = $codigo_operador[1];
								            	$dados_operadores = DBRead('snep','queue_agents',"ORDER BY membername ASC");
								            	if($dados_operadores){
								            		foreach ($dados_operadores as $conteudo_operadores) {
														$selected = $codigo_operador == $conteudo_operadores['codigo'] ? "selected" : "";
								            			echo "<option value='AGENT/".$conteudo_operadores['codigo']."' ".$selected.">".$conteudo_operadores['membername']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>
							<?php } ?>

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
							<div class="row" id="row_lider" <?=$display_row_lider?>>
								<div class="col-md-12">
									<div class="form-group">
									<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm">
												<?php
												$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '13' GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
												if ($dados_lider) {
													foreach ($dados_lider as $conteudo_lider) {
														$selected = $lider == $conteudo_lider['lider_direto'] ? "selected" : "";
														echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome'] . "</option>";
													}
												}
												?>
										</select>
								    </div>
								</div>
							</div>
							<?php } ?>

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
							<div class="row" id="row_tipo_pausa" <?=$display_row_tipo_pausa?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Tipo de Pausa:</label>
								        <select name="tipo_pausa" class="form-control input-sm">
								        	<option value="">Todas</option>
								            <?php
								            	$dados_tipos_pausa = DBRead('snep','tipo_pausa',"ORDER BY nome ASC");
								            	if($dados_tipos_pausa){
								            		foreach ($dados_tipos_pausa as $conteudo_tipos_pausa) {
														$selected = $tipo_pausa == $conteudo_tipos_pausa['id'] ? "selected" : "";
								            			echo "<option value='".$conteudo_tipos_pausa['id']."' title='".$conteudo_tipos_pausa['descricao']."' ".$selected.">".$conteudo_tipos_pausa['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>
							<?php } ?>

							<div class="row" id="row_fila" <?=$display_row_fila?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Fila:</label>
								        <select name="fila[]" class="form-control input-sm" multiple="multiple" size=15>
								            <?php
								            	$dados_filas = DBRead('snep','queues',"ORDER BY name DESC");
								            	if($dados_filas){
								            		foreach ($dados_filas as $conteudo_filas) {
								            			if(preg_match('/'.$conteudo_filas['name'].'/i', $fila)){
								            				$sel_fila = 'selected';
								            			}else{
								            				$sel_fila = '';
								            			}
								            			echo "<option value='".$conteudo_filas['name']."' $sel_fila>".$conteudo_filas['name']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_tag_saida" <?=$display_row_tag_saida?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Tag de Saída:</label>
								        <select name="tag_saida" class="form-control input-sm">
											<option value="">Todas</option>
								            <?php
								            	$dados_tag_saida = DBRead('snep','ccustos',"WHERE tipo = 'S' ORDER BY nome ASC");
								            	if($dados_tag_saida){
								            		foreach ($dados_tag_saida as $conteudo_tag_saida) {
														$selected = $tag_saida == $conteudo_tag_saida['codigo'] ? "selected" : "";
								            			echo "<option value='".$conteudo_tag_saida['codigo']."' ".$selected.">".$conteudo_tag_saida['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_segundos_atendimento" <?=$display_row_segundos_atendimento?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Descartar tempo de atendimento abaixo de (segundos):</label><br>
								        <input type="number" class="form-control input-sm" name="segundos_atendimento" value="<?=$segundos_atendimento?>">
								    </div>
								</div>
							</div>

							<div class="row" id="row_numero" <?=$display_row_numero?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Número:</label><br>
								        <input type="text" class="form-control input-sm number_int" name="numero" value="<?=$numero?>" autocomplete='off'>
								    </div>
								</div>
							</div>

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>							
							<div class="row" id="row_segundos_espera_perdida" <?=$display_row_segundos_espera_perdida?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Descartar tempo de espera das perdidas abaixo de (segundos):</label><br>
								        <input type="number" class="form-control input-sm" name="segundos_espera_perdida" value="<?=$segundos_espera_perdida?>">
								    </div>
								</div>
							</div>
							<?php } ?>

							<div class="row" id="row_nota" <?=$display_row_nota?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Nota:</label>
								        <select name="nota" class="form-control input-sm">
								            <option value="">Todas</option>
								            <option value="n" <?php if($nota == 'n'){ echo 'selected';}?>>Sem nota</option>
								            <option value="1" <?php if($nota == '1'){ echo 'selected';}?>>1</option>
								            <option value="2" <?php if($nota == '2'){ echo 'selected';}?>>2</option>
								            <option value="3" <?php if($nota == '3'){ echo 'selected';}?>>3</option>
								            <option value="4" <?php if($nota == '4'){ echo 'selected';}?>>4</option>
								            <option value="5" <?php if($nota == '5'){ echo 'selected';}?>>5</option>
								        </select>
								    </div>
								</div>
							</div>	

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
							<div class="row" id="row_lider_atendente" <?=$display_row_lider_atendente?>>
								<div class="col-md-12">
									<div class="form-group">
									<label for="">Líder Direto:</label>
										<select name="lider_atendente" id="lider_atendente" class="form-control input-sm">
											<option value="">Todos</option>
											<?php
											$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '13' GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
											// AND b.id_perfil_sistema = '13'
											if ($dados_lider) {
												foreach ($dados_lider as $conteudo_lider) {
													$selected = $lider_atendente == $conteudo_lider['lider_direto'] ? "selected" : "";
													echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome'] . "</option>";
												}
											}
											?>
										</select>
								    </div>
								</div>
							</div>
							<?php } ?>
		                </div>
	            	</div>
	                <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-refresh"></i> Gerar</button>
                                <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </div>
	            </div>
	        </div>
	    </div>
	</form>
	
	<div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div>	
	<div id="resultado" class="row" style="display:none;">		
		<?php 
		if($gerar){
			if ($perfil_sistema == '3' || $perfil_sistema == '28') {
				$operador = 'AGENT/'.$id_asterisk_usuario;
			}

			if ($tipo_relatorio == 1) {
				relatorio_atendidas($data_de, $data_ate, $empresa, $operador, $fila, $segundos_atendimento, $nota, $plano);

			} else if ($tipo_relatorio == 2 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_perdidas($data_de, $data_ate, $empresa, $fila, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 3 && $perfil_sistema != '28') {					
				relatorio_pausas_atendente($data_de, $data_ate, $operador, $tipo_pausa);

			} else if ($tipo_relatorio == 4 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_hora($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 5 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_sintetico_geral($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida, $plano);

			} else if ($tipo_relatorio == 27 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_sintetico_geral_hora($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida, $plano);

			} else if ($tipo_relatorio == 28 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_sintetico_geral_hora_acumulado($data_de, $data_ate, $hora_de, $hora_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida, $plano);

			} else if ($tipo_relatorio == 6 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_grafico_dia_semana_acumulado_geral($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 7 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_grafico_hora_dia_empresas($tipo_grafico, $data, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 8) {					
				relatorio_realizadas($data_de, $data_ate, $operador, $numero, $tag_saida);

			} else if ($tipo_relatorio == 9 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_tempo_ponto_login($data_de, $data_ate, $operador);

			} else if ($tipo_relatorio == 12 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_pausas_equipe($data_de, $data_ate, $lider, $tipo_pausa);

			} else if ($tipo_relatorio == 13 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_sintetico_empresa($data_de, $data_ate, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 14 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_tempos_medios_hora($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 15 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_grafico_tempos_medios_dia_semana_acumulado_geral($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 16 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_grafico_tempos_medios_hora_acumulado_semana($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 17 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_hora_acumulado_semana($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 10 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_20min($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 11 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_20min_acumulado_semana($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 18 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_tempos_medios_20min($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 19 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_grafico_tempos_medios_20min_acumulado_semana($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);

			} else if ($tipo_relatorio == 20) {
				relatorio_licagoes_com_historico($data_de, $data_ate, $empresa, $operador, $fila);

			} else if ($tipo_relatorio == 21 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_historico_login_logoff($data_de, $data_ate, $operador, $pa);

			} else if ($tipo_relatorio == 22 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_ultima_ligacao_empresa();

			} else if ($tipo_relatorio == 23 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_notas($data_de, $data_ate, $empresa, $operador, $segundos_atendimento, $plano);

			} else if ($tipo_relatorio == 24 && $perfil_sistema != '3' && $perfil_sistema != '28') {
				relatorio_controle_filas();

			} else if ($tipo_relatorio == 25 && $perfil_sistema != '28') {					
				relatorio_pausas_atendente_tabela($data_de, $data_ate, $tipo_pausa);

			} else if ($tipo_relatorio == 26 && $perfil_sistema != '3' && $perfil_sistema != '28') {					
				relatorio_atendente_fila($lider_atendente, $operador);

			} else {
				echo '<div class="alert alert-danger text-center">Erro ao exibir relatório!</div>';
			}
		}
		?>
	</div>
</div>
<script>
	$('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		var perfil_sistema = "<?=$perfil_sistema?>";
		if (tipo_relatorio == 1) {
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').show();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').show();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').show();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 2) {
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 3) {
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').show();
			$('#row_tipo_pausa').show();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 4 || tipo_relatorio == 6 || tipo_relatorio == 17 || tipo_relatorio == 10 || tipo_relatorio == 11){
			$('#row_tipo_grafico').show();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 5){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').show();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 27){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').show();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 7){
			$('#row_tipo_grafico').show();
			$('#row_data').show();
			$('#row_periodo').hide();
			$('#row_empresa').hide();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 8){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').show();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').show();
			$('#row_tag_saida').show();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 9){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').show();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 12){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').hide();
			$('#row_tipo_pausa').show();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').show();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 13){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 14 || tipo_relatorio == 15 || tipo_relatorio == 16 || tipo_relatorio == 18 || tipo_relatorio == 19){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 20){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').show();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 21){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').show();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').show();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 22){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').hide();
			$('#row_empresa').hide();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 23){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').show();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').show();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 24){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').hide();
			$('#row_empresa').hide();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 25){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').hide();
			$('#row_operador').hide();
			$('#row_tipo_pausa').show();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').hide();

		} else if (tipo_relatorio == 26){
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').hide();
			$('#row_empresa').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
			$('#row_segundos_espera_perdida').hide();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').hide();
			$('#row_lider_atendente').show();
			$('#row_operador').show();
			$('#row_periodo_hora').hide();
			
		} else if (tipo_relatorio == 28) {
			$('#row_tipo_grafico').hide();
			$('#row_data').hide();
			$('#row_periodo').show();
			$('#row_empresa').show();
			$('#row_operador').hide();
			$('#row_tipo_pausa').hide();
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
			$('#row_segundos_espera_perdida').show();
			$('#row_nota').hide();
			$('#row_numero').hide();
			$('#row_tag_saida').hide();
			$('#row_lider').hide();
			$('#row_pa').hide();
			$('#row_plano').show();
			$('#row_lider_atendente').hide();
			$('#row_periodo_hora').show();
		}
	});   

    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('submit', 'form', function(){
        modalAguarde();
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
    });

    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#plano').on('change',function(){
        if($(this).val() !== ''){
            $('#empresa').val('');
        }
	});

    $('#empresa').on('change',function(){
        if($(this).val() !== ''){
            $('#plano').val('');
            $('#row_plano').hide();
        }else{
            $('#row_plano').show();
        }
	});

	$('#operador').on('change',function(){
		if($(this).val() != ""){
			$("#lider_atendente option").each(function(){
				if ($(this).val() == ""){
					$(this).attr("selected","selected");
				}else{
					$(this).attr("selected",false);
				}
			});
		}
	});

	$('#lider_atendente').on('change',function(){
		if($(this).val() != ""){
			$("#operador option").each(function(){
				if ($(this).val() == ""){
					$(this).attr("selected","selected");
				}else{
					$(this).attr("selected",false);
				}
			});
		}
	});

</script>
<?php

function relatorio_sintetico_geral_hora($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida, $plano){
	
	$data_hora = converteDataHora(getDataHora());
	
	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
        $empresa_legenda = 'Todas';
	}

	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
    }
    
    if($plano){
        $dados_plano = DBRead('','tb_plano', "WHERE id_plano = '$plano'");
        $legenda_plano = ', <strong>Plano - </strong>'.$dados_plano[0]['nome'];
    }elseif(!$empresa){
        $legenda_plano = ', <strong>Plano - </strong>Todos';
    }else{
        $legenda_plano = '';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Sintético Geral de Ligações - Hora</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_plano."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
    echo "</legend>";

	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
		$hora = 0;
		while ($hora < 24) {
			$qtd_atendidas = 0;
			$qtd_atendidas_30 = 0;
			$qtd_atendidas_60 = 0;
			$qtd_atendidas_90 = 0;
			$qtd_atendidas_180 = 0;
			$qtd_atendidas_maior_180 = 0;
			$qtd_perdidas = 0;
			$qtd_perdidas_30 = 0;
			$qtd_perdidas_60 = 0;
			$qtd_perdidas_90 = 0;
			$qtd_perdidas_180 = 0;
			$qtd_perdidas_maior_180 = 0;
			$soma_notas = 0;
			$qtd_notas = 0;
			$soma_ta_atendidas = 0;
			$soma_te_atendidas = 0;
			$soma_te_perdidas = 0;

			$hora_zero = sprintf('%02d', $hora);
		
			$filtro_atendidas = '';
			$filtro_perdidas = '';
			if($data){
				$filtro_atendidas .= " AND b.time >= '".$data." ".$hora_zero.":00:00'";
				$filtro_perdidas .= " AND a.time >= '".$data." ".$hora_zero.":00:00'";
			}
			if($data){		
				$filtro_atendidas .= " AND b.time <= '".$data." ".$hora_zero.":59:59'";
				$filtro_perdidas .= " AND a.time <= '".$data." ".$hora_zero.":59:59'";
			}
			// if($fila){
			// 	$filtro_atendidas .= " AND a.queuename IN ($fila)";
			// 	$filtro_perdidas .= " AND a.queuename IN ($fila)";
			// }
			if($empresa){
				$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
				$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
			}
			if($segundos_atendimento){
				$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
			}
			if($segundos_espera_perdida){
				$filtro_perdidas .= " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
			}
			if($plano){
				$filtro_plano = ' AND (';
				$dados_empresas_plano = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_parametros b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE b.id_asterisk AND a.id_plano = '$plano'",'b.*');
				if($dados_empresas_plano){
					foreach($dados_empresas_plano as $conteudo_empresas_plano){
						$filtro_plano .= "a.data2 LIKE '".$conteudo_empresas_plano['id_asterisk']."%' OR ";
					}
				}
				$filtro_plano = substr($filtro_plano, 0, -4).')';
				$filtro_atendidas .= $filtro_plano;
				$filtro_perdidas .= $filtro_plano;
			}

			$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', c.queuename AS ultimafila, d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			registraLog('Relatório Central - Sintetico Geral.','rel','relatorio_sintetico_geral',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY a.callid ORDER BY a.callid");
			
			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {
					if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){
						$te_entrada = 'n';								
						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];
							$te_entrada = $conteudo_atendidas['finalizacao_data1'];				
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							$te_entrada = $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						if($te_entrada != 'n'){
							if($te_entrada <= 30){
								$qtd_atendidas_30++;
							}else if($te_entrada <= 60){
								$qtd_atendidas_60++;
							}else if($te_entrada <= 90){
								$qtd_atendidas_90++;
							}else if($te_entrada <= 180){
								$qtd_atendidas_180++;
							}else if($te_entrada > 180){
								$qtd_atendidas_maior_180++;
							}
						}
						if($conteudo_atendidas['nota']){
							$soma_notas += $conteudo_atendidas['nota'];
							$qtd_notas++;	
						}			
						$qtd_atendidas++;
					}
				}
			}
			
			$dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', b.queuename AS ultimafila, a.time, a.id");

			//var_dump($dados_perdidas);

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {

					if(preg_match("/'".$conteudo_perdidas['ultimafila']."'/i", $fila) || !$fila){

						if($conteudo_perdidas['finalizacao_data3'] <= 30){
							$qtd_perdidas_30++;

						}else if($conteudo_perdidas['finalizacao_data3'] <= 60){
							$qtd_perdidas_60++;

						}else if($conteudo_perdidas['finalizacao_data3'] <= 90){
							$qtd_perdidas_90++;

						}else if($conteudo_perdidas['finalizacao_data3'] <= 180){
							$qtd_perdidas_180++;

						}else if($conteudo_perdidas['finalizacao_data3'] > 180){
							$qtd_perdidas_maior_180++;
						}
						$qtd_perdidas++;			
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
					}
				}
			}

			// echo "<div class='col-md-12' style=' border: 1px solid'><div class='panel-body'>	 <div class='row'>";

			echo '
			<div class="col-md-12">
	            <div class="panel panel-default" style="border: 1px black solid; border-radius:30px;">
	                <div class="panel-heading clearfix" style="border-top-left-radius: 30px; border-top-right-radius: 30px; background-image: linear-gradient(to bottom,#ffffff 0,#ffffff 100%) !important;">
	                    <h3 class="panel-title text-center pull-center" style="margin-top: 2px;"><strong>'.converteData($data).'<br>'.$hora_zero.':00</strong></h3>
	                </div>
					<div class="panel-body">	                		
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">';
									echo '
									<table class="table table-hover"> 
										<thead> 
											<tr> 
												<th class="text-center" width="16.6666666667%">Total de atendidas</th>
												<th class="text-center" width="16.6666666667%">Atendidas até 30s</th>
												<th class="text-center" width="16.6666666667%">Atendidas até 60s</th>
												<th class="text-center" width="16.6666666667%">Atendidas até 90s</th>
												<th class="text-center" width="16.6666666667%">Atendidas até 180s</th>
												<th class="text-center" width="16.6666666667%">Atendidas maiores de 180s</th>
											</tr>
										</thead> 
										<tbody>
											<tr>
												<td class="text-center success">'.$qtd_atendidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center success">'.$qtd_atendidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_30*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center success">'.$qtd_atendidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_60*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center success">'.$qtd_atendidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_90*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center success">'.$qtd_atendidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center success">'.$qtd_atendidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
											</tr>
										</tbody> 
									</table>
									<table class="table table-hover"> 
										<thead> 
											<tr> 
												<th class="text-center" width="16.6666666667%">Total de perdidas</th>
												<th class="text-center" width="16.6666666667%">Perdidas até 30s</th>
												<th class="text-center" width="16.6666666667%">Perdidas até 60s</th>
												<th class="text-center" width="16.6666666667%">Perdidas até 90s</th>
												<th class="text-center" width="16.6666666667%">Perdidas até 180s</th>
												<th class="text-center" width="16.6666666667%">Perdidas maiores de 180s</th>
											</tr>
										</thead> 
										<tbody>
											<tr>
												<td class="text-center danger">'.$qtd_perdidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center danger">'.$qtd_perdidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_30*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center danger">'.$qtd_perdidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_60*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center danger">'.$qtd_perdidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_90*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center danger">'.$qtd_perdidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
												<td class="text-center danger">'.$qtd_perdidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
											</tr>
										</tbody> 
									</table>	
									<table class="table table-hover"> 
										<thead> 
											<tr> 
												<th class="text-center" width="16.6666666667%">Total de ligações</th>
												<th class="text-center" width="16.6666666667%" title="Tempo Médio de Atendimento">TMA</th>
												<th class="text-center" width="16.6666666667%" title="Nota Média de Atendimento">NMA</th>
												<th class="text-center" width="16.6666666667%" title="Ligações com Nota">LN</th>				
												<th class="text-center" width="16.6666666667%" title="Tempo Médio de Espera">TME</th>
												<th class="text-center" width="16.6666666667%" title="Tempo Médio das Perdidas">TMP</th>
											</tr>
										</thead> 
										<tbody>
											<tr>
												<td class="text-center active">'.($qtd_atendidas+$qtd_perdidas).'</td>
												<td class="text-center info">'.gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
												<td class="text-center info">'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
												<td class="text-center info">'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</td>
												<td class="text-center warning">'.gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
												<td class="text-center warning">'.gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</td>
											</tr>
										</tbody> 
									</table>
									';  

			echo "				</div>
							</div>
						</div>
					</div>
				</div>
			</div>";

			$hora++;
		}
	}

}

function relatorio_atendente_fila($lider_atendente, $operador){

	$data_hora = converteDataHora(getDataHora());

	if($operador){		
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo = '".$codigo_operador."' ");
		if($dados_operador){
			$operador_legenda = $dados_operador[0]['membername'];
		}else{
			$operador_legenda = 'Não identificado';
		}		
		$filtro_operador = " AND codigo = '$codigo_operador'";
	}else{
		$operador_legenda = 'Todos';
	}

	if($lider_atendente){		
		$dados_lider_atendente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$lider_atendente."' ");
		$lider_atendente_legenda = $dados_lider_atendente[0]['nome'];
	}else{
		$lider_atendente_legenda = 'Todos';
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendentes - Filas</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$operador_legenda.", <strong>Líder Direto - </strong>".$lider_atendente_legenda;
	echo "</legend>";


	$dados_atendente_fila = DBRead('snep','queue_agents',"WHERE status != '2' $filtro_operador ORDER BY membername ASC");
	
	registraLog('Relatório Central - Filas.','rel','relatorio_atendente_fila',1,"WHERE status != '2' $filtro_operador ORDER BY membername ASC");

	if($dados_atendente_fila){

		echo "<div class='table-responsive'>";
			echo "<table class='table table-hover' style='font-size: 14px;'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th class=\"col-md-1\">Código</th>";
						echo "<th class=\"col-md-3\">Nome</th>";
						echo "<th class=\"col-md-8\">Filas</th>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";

			foreach ($dados_atendente_fila as $conteudo_atendente_fila) {
				$id = $conteudo_atendente_fila['uniqueid'];
				$codigo = $conteudo_atendente_fila['codigo'];
				$nome = $conteudo_atendente_fila['membername'];
				$id_filas = explode(',', $conteudo_atendente_fila['queue_name']);
				$nome_filas = array();

				if($lider_atendente){
					$dados_operador_lider = DBRead('','tb_usuario',"WHERE id_asterisk = '".$codigo."' AND lider_direto = '".$lider_atendente."' ");
				}

				if($dados_operador_lider || !$lider_atendente){
					
					foreach($id_filas as $value){
						$dados_fila = DBRead('snep','queues',"WHERE id = '$value'",'name');
						$nome_filas[] = $dados_fila[0]['name'];
					}
					$nome_filas = implode(", ", $nome_filas);
					echo "<tr>";
						echo "<td style='vertical-align: middle;'>$codigo</td>";
						echo "<td style='vertical-align: middle;'>$nome</td>";
						echo "<td style='vertical-align: middle;'>$nome_filas</td>";
					echo "</tr>";
				}
				
				
			}
			echo "</tbody>";
		echo "</table>";
		    
	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_pausas_atendente_tabela($data_de, $data_ate, $tipo_pausa){

	$data_hora = converteDataHora(getDataHora());

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($tipo_pausa){		
		$dados_tipo_pausa = DBRead('snep','tipo_pausa',"WHERE id='$tipo_pausa'");
		if($dados_tipo_pausa){
			$pausa_legenda = $dados_tipo_pausa[0]['nome'];
		}else{
			$pausa_legenda = 'Não identificada';
		}
	}else{
		$pausa_legenda = 'Todas';
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pausas por Atendente - Tabela</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Tipo de Pausa - </strong>".$pausa_legenda;
	echo "</legend>";

	$filtro_pausas = "";
    if($data_de){
		$filtro_pausas .= " AND a.data_pause >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro_pausas .= " AND a.data_pause <= '".converteData($data_ate)." 23:59:59'";
	}

	if($tipo_pausa){
		$filtro_pausas .= " AND a.tipo_pausa = '$tipo_pausa'";
	}

    $dados_pausas = DBRead('snep','queue_agents_pause a',"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid $filtro_pausas ORDER BY a.data_pause ASC", "a.*, b.membername AS 'nome_operador', c.nome AS 'nome_pausa'");

	registraLog('Relatório Central - Pausas.','rel','relatorio_pausas_atendente_tabela',1,"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid $filtro_pausas ORDER BY a.data_pause ASC");


	if($dados_pausas){

		$dados_tipos_pausa = DBRead('snep','tipo_pausa',"ORDER BY nome ASC", "nome");


        $array_auxiliar = array();

		$filtro_id_asterisk = array();

		foreach ($dados_pausas as $conteudo_pausas) {
            $data_pausa = strtotime($conteudo_pausas['data_pause']);
            $data_retorno = strtotime($conteudo_pausas['data_unpause']);
            $duracao = $data_retorno ? ($data_retorno - $data_pausa) : 0;
            $nome_pausa = $conteudo_pausas['nome_pausa'];
            $nome_operador = $conteudo_pausas['nome_operador'];

            if(!in_array($conteudo_pausas['codigo'], $filtro_id_asterisk)){
                $filtro_id_asterisk[] = $conteudo_pausas['codigo'];
            }

            $info_pausa = array(
				'data_pausa' =>  date('d/m/Y H:i:s', $data_pausa),
				'data_retorno' => date('d/m/Y H:i:s', $data_retorno),
				'duracao' => $duracao,
				'nome_pausa' => $nome_pausa,
				'nome_operador' => $nome_operador
            );
			
			if($info_pausa['data_pausa'] &&  $info_pausa['nome_pausa'] &&  $info_pausa['nome_operador']){
				$array_auxiliar[$info_pausa['nome_operador']][$info_pausa['nome_pausa']] += $info_pausa['duracao'];
			}			
		}
		
		$dados_final = array();

        foreach ($array_auxiliar as $nome_operador => $pausa) {

            foreach ($pausa as $tipo => $duracao) {
                foreach ($dados_tipos_pausa as $conteudo_tipos_pausa) {
                    if($conteudo_tipos_pausa['nome'] == $tipo){
                        $dados_final[$nome_operador][$tipo] = $pausa[$conteudo_tipos_pausa['nome']];
                    }else{
                        if(!$dados_final[$nome_operador][$conteudo_tipos_pausa['nome']]){
                            $dados_final[$nome_operador][$conteudo_tipos_pausa['nome']] = 0;
                        }
                    }
                }
            }
        }

        //Não inclusos
        $dados_operador_nao_incluso = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND a.id_perfil_sistema = '3' AND a.id_asterisk NOT IN (".implode(",", $filtro_id_asterisk).")  ", "b.nome");
        foreach ($dados_operador_nao_incluso as $conteudo_operador_nao_incluso) {
            $nome_operador = $conteudo_operador_nao_incluso['nome'];
            foreach ($dados_tipos_pausa as $conteudo_tipos_pausa) {
                $dados_final[$nome_operador][$conteudo_tipos_pausa['nome']] = 0;;
            }
        }

        if($dados_final){
            
            ksort($dados_final);
                echo "
                    <table class=\"table table-hover dataTableGeral\"> 
                        <thead> 
                            <tr> 
                                <th>Nome</th>";
                                foreach ($dados_tipos_pausa as $conteudo_tipos_pausa) {
                                    echo "<th>".$conteudo_tipos_pausa['nome']."</th>";
                                }
                                echo "
                                <th>Tempo Total de Pausas</th>
                            </tr>
                        </thead> 
                        <tbody>"
                ;
            

                $dados_total_final = array();
                    foreach ($dados_final as $nome => $conteudo_final) {
                        echo '<tr>';
                            echo '<td style="vertical-align: middle">'.$nome.'</td>';
                            $tempo_total_atendente = 0;

                            foreach ($conteudo_final as $tipo_pausa => $tempo_duracao) {
                                
                                echo '<td style="vertical-align: middle">'.converteSegundosHoras($tempo_duracao).'</td>';
                                $tempo_total_atendente += $tempo_duracao;
                                $dados_total_final[$tipo_pausa] += $tempo_duracao;
                            }
                            $dados_total_final['tempo_total_atendente'] += $tempo_total_atendente;

                            echo '<td style="vertical-align: middle">'.converteSegundosHoras($tempo_total_atendente).'</td>';
                        echo '</tr>';
                    }

                        echo "
                        </tbody> 
                        <tfoot>
                        ";
                        echo '<tr>';
                            echo '<th>Totais</th>';
                            foreach ($dados_total_final as $conteudo_total_final) {
                                echo '<th>'.converteSegundosHoras($conteudo_total_final).'</th>';
                            }
                        echo '</tr>';
                        echo "
                        </tfoot> 
                    </table>
                ";


            echo "
            <script>
                $(document).ready(function(){
                    var table = $('.dataTableGeral').DataTable({
                        \"language\": {
                            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                        },
                        columnDefs: [
                            { type: 'time-uni', targets: 2 },
                        ],				        
                        \"searching\": false,
                        \"paging\":   false,
                        \"info\":     false
                    });

                    var buttons = new $.fn.dataTable.Buttons(table, {
                        buttons: [
                            {
                                extend: 'excelHtml5', footer: true,
                                text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                                filename: 'relatorio_pausas_atendentes_tabela',
                                title : null,
                                exportOptions: {
                                    modifier: {
                                    page: 'all'
                                    }
                                }
                                },
                        ],	
                        dom:
                        {
                            button: {
                                tag: 'button',
                                className: 'btn btn-default'
                            },
                            buttonLiner: { tag: null }
                        }
                    }).container().appendTo($('#panel_buttons'));
                });
            </script>			
            ";
        } 

	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_pausas_atendente($data_de, $data_ate, $operador, $tipo_pausa){
	$data_hora = converteDataHora(getDataHora());
	$tipo_pausa_filtro = $tipo_pausa;

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
		}
	}else{
		$atendente_legenda = 'Todos';
	}
	if($tipo_pausa){		
		$dados_tipo_pausa = DBRead('snep','tipo_pausa',"WHERE id='$tipo_pausa'");
		if($dados_tipo_pausa){
			$pausa_legenda = $dados_tipo_pausa[0]['nome'];
		}else{
			$pausa_legenda = 'Não identificada';
		}
	}else{
		$pausa_legenda = 'Todas';
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pausas por Atendente</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$atendente_legenda.", <strong>Tipo de Pausa - </strong>".$pausa_legenda;
	echo "</legend>";

    $filtro_pausas = "";
    if($data_de){
        $filtro_pausas .= " AND a.data_pause >= '".converteData($data_de)." 00:00:00'";
    }
    if($data_ate){
        $filtro_pausas .= " AND a.data_pause <= '".converteData($data_ate)." 23:59:59'";
    }
    if($operador){
		$filtro_pausas .= " AND a.codigo = '$codigo_operador'";
	}
    if($tipo_pausa){
        $filtro_pausas .= " AND a.tipo_pausa = '$tipo_pausa'";
    }
    
    $dados_pausas = DBRead('snep','queue_agents_pause a',"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid $filtro_pausas ORDER BY a.data_pause ASC", "a.*, b.membername AS 'nome_operador', c.nome AS 'nome_pausa'");
   
	registraLog('Relatório Central - Pausas.','rel','relatorio_pausas_atendente',1,"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid $filtro_pausas ORDER BY a.data_pause ASC");


	if($dados_pausas){
		echo "
			<table class=\"table table-hover dataTableGeral\"> 
				<thead> 
					<tr> 
    					<th>Data Pausa</th>
    					<th>Data Retorno</th>
    					<th>Tempo</th>
    					<th>Tipo</th>
    					<th>Atendente</th>
					</tr>
				</thead> 
				<tbody>"
		;
		$duracao_total = 0;
		$qtd_pausas = 0;
		$duracao_tipos_pausa = array();
		$pausas_operadores = array();
		foreach ($dados_pausas as $conteudo_pausas) {

            $data_pausa = strtotime($conteudo_pausas['data_pause']);
            $data_retorno = strtotime($conteudo_pausas['data_unpause']);
            $duracao = $data_retorno ? ($data_retorno - $data_pausa) : 0;
            $nome_pausa = $conteudo_pausas['nome_pausa'];
            $nome_operador = $conteudo_pausas['nome_operador'];

            $info_pausa = array(
                'data_pausa' =>  date('d/m/Y H:i:s', $data_pausa),
                'data_retorno' => date('d/m/Y H:i:s', $data_retorno),
                'duracao' => $duracao,
                'nome_pausa' => $nome_pausa,
                'nome_operador' => $nome_operador
            );
			
			if($info_pausa['data_pausa'] &&  $info_pausa['nome_pausa'] &&  $info_pausa['nome_operador']){
				if($info_pausa['duracao']){
					$data_retorno = $info_pausa['data_retorno'];
					$duracao = converteSegundosHoras($info_pausa['duracao']);
					$duracao_tipos_pausa[$info_pausa['nome_pausa']] += $info_pausa['duracao'];
					$pausas_operadores[$info_pausa['nome_operador']] += $info_pausa['duracao'];
					$duracao_total += $info_pausa['duracao'];
				}else{
					$data_retorno = 'Não retornou';
					$duracao = 'Não retornou';					
				}
				echo '<tr>';
	    		echo '<td>'.$info_pausa['data_pausa'].'</td>';
				echo '<td>'.$data_retorno.'</td>';
				echo '<td>'.$duracao.'</td>';			
				echo '<td>'.$info_pausa['nome_pausa'].'</td>';	
				echo '<td>'.$info_pausa['nome_operador'].'</td>';
				echo '</tr>';
				$qtd_pausas ++;
			}			
		}
		echo "
			</tbody> 
			<tfoot>
		";
		echo '<tr>';
		echo '<th>Tempo médio: '.converteSegundosHoras($duracao_total/($qtd_pausas == 0 ? 1 : $qtd_pausas)).'</th>';
		echo '<th></th>';
		echo '<th></th>';			
		echo '<th></th>';	
		echo '<th></th>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>Tempo total: '.converteSegundosHoras($duracao_total).'</th>';
		echo '<th></th>';
		echo '<th></th>';			
		echo '<th></th>';	
		echo '<th></th>';
		echo '</tr>';
		echo "
				</tfoot> 
			</table>
		";
		echo "
        <script>
            $(document).ready(function(){
                var table = $('.dataTableGeral').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },
                    columnDefs: [
                        { type: 'time-uni', targets: 2 },
                    ],				        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });

                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'excelHtml5', footer: true,
                            text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                            filename: 'relatorio_pausas_atendetes',
                            title : null,
                            exportOptions: {
                                modifier: {
                                page: 'all'
                                }
                            }
                            },
                    ],	
                    dom:
                    {
                        button: {
                            tag: 'button',
                            className: 'btn btn-default'
                        },
                        buttonLiner: { tag: null }
                    }
                }).container().appendTo($('#panel_buttons'));
            });
        </script>			
        ";
		if(!$tipo_pausa_filtro){
			echo "<hr>";
			echo "
				<table class=\"table table-hover dataTableAgrupado\">  
					<thead> 
						<tr> 
	    					<th class='col-md-6'>Tipo</th>
	    					<th class='col-md-6'>Tempo</th>
						</tr>
					</thead> 
					<tbody>"
			;
			arsort($duracao_tipos_pausa);		
			foreach ($duracao_tipos_pausa as $tipo => $duracao) {
				echo '<tr>';
	    		echo '<td>'.$tipo.'</td>';
				echo '<td>'.converteSegundosHoras($duracao).'</td>';
				echo '</tr>';			
			}
			echo "
					</tbody> 
				</table>
			";			
		}
		if(!$operador){
			echo "<hr>";
			echo "
				<table class=\"table table-hover dataTableAgrupado\"> 
					<thead> 
						<tr> 
	    					<th class='col-md-6'>Atendente</th>
	    					<th class='col-md-6'>Tempo</th>
						</tr>
					</thead> 
					<tbody>"
			;
			arsort($pausas_operadores);		
			foreach ($pausas_operadores as $nome_operador => $duracao) {
				echo '<tr>';
	    		echo '<td>'.$nome_operador.'</td>';
				echo '<td>'.converteSegundosHoras($duracao).'</td>';
				echo '</tr>';			
			}
			echo "
					</tbody> 
				</table>
			";	
		}
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableAgrupado').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 1 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";		
	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_pausas_equipe($data_de, $data_ate, $lider, $tipo_pausa){
	$data_hora = converteDataHora(getDataHora());
	$tipo_pausa_filtro = $tipo_pausa;

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($lider){
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa=b.id_pessoa WHERE a.id_usuario='$lider'");
		$lider_legenda = $dados_lider[0]['nome'];
	}else{
		$lider_legenda = '';
	}


	if($tipo_pausa){		
		$dados_tipo_pausa = DBRead('snep','tipo_pausa',"WHERE id='$tipo_pausa'");
		if($dados_tipo_pausa){
			$pausa_legenda = $dados_tipo_pausa[0]['nome'];
		}else{
			$pausa_legenda = 'Não identificada';
		}
	}else{
		$pausa_legenda = 'Todas';
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pausas por Equipe</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Líder Direto - </strong>".$lider_legenda.", <strong>Tipo de Pausa - </strong>".$pausa_legenda;
	echo "</legend>";
    
    $filtro_pausas = "";
    if($data_de){
        $filtro_pausas .= " AND a.data_pause >= '".converteData($data_de)." 00:00:00'";
    }
    if($data_ate){
        $filtro_pausas .= " AND a.data_pause <= '".converteData($data_ate)." 23:59:59'";
    }
	if($lider){
		$dados_equipe = DBRead('','tb_usuario',"WHERE lider_direto = '$lider'");		
		if($dados_equipe){
			$filtro_lider = array();
			$cont = 0;
			foreach ($dados_equipe as $conteudo_equipe) {
				if($conteudo_equipe['id_asterisk'] && $conteudo_equipe['id_asterisk'] != ''){
					$filtro_lider[$cont] = $conteudo_equipe['id_asterisk'];
					$cont++;
				}
			}
			if($cont>0){
				$filtro_pausas .= " AND a.codigo IN (".join(",",$filtro_lider).")";
			}
		}
	}
	if($tipo_pausa){
        $filtro_pausas .= " AND a.tipo_pausa = '$tipo_pausa'";
    }
    $dados_pausas = DBRead('snep','queue_agents_pause a',"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid $filtro_pausas ORDER BY a.data_pause ASC", "a.*, b.membername AS 'nome_operador', c.nome AS 'nome_pausa'");

	registraLog('Relatório Central - Pausas por Equipe.','rel','relatorio_pausas_equipe',1,"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid $filtro_pausas ORDER BY a.data_pause ASC");

	if($dados_pausas){
		echo "
			<table class=\"table table-hover dataTableGeral\"> 
				<thead> 
					<tr> 
    					<th>Data Pausa</th>
    					<th>Data Retorno</th>
    					<th>Tempo</th>
    					<th>Tipo</th>
    					<th>Atendente</th>
					</tr>
				</thead> 
				<tbody>"
		;
		$duracao_total = 0;
		$qtd_pausas = 0;
		$duracao_tipos_pausa = array();
		$pausas_operadores = array();
		foreach ($dados_pausas as $conteudo_pausas) {
			$data_pausa = strtotime($conteudo_pausas['data_pause']);
            $data_retorno = strtotime($conteudo_pausas['data_unpause']);
            $duracao = $data_retorno ? ($data_retorno - $data_pausa) : 0;
            $nome_pausa = $conteudo_pausas['nome_pausa'];
            $nome_operador = $conteudo_pausas['nome_operador'];

            $info_pausa = array(
                'data_pausa' =>  date('d/m/Y H:i:s', $data_pausa),
                'data_retorno' => date('d/m/Y H:i:s', $data_retorno),
                'duracao' => $duracao,
                'nome_pausa' => $nome_pausa,
                'nome_operador' => $nome_operador
            );		
			
			if($info_pausa['data_pausa'] &&  $info_pausa['nome_pausa'] &&  $info_pausa['nome_operador']){
				if($info_pausa['duracao']){
					$data_retorno = $info_pausa['data_retorno'];
					$duracao = converteSegundosHoras($info_pausa['duracao']);
					$duracao_tipos_pausa[$info_pausa['nome_pausa']] += $info_pausa['duracao'];
					$pausas_operadores[$info_pausa['nome_operador']] += $info_pausa['duracao'];
					$duracao_total += $info_pausa['duracao'];
				}else{
					$data_retorno = 'Não retornou';
					$duracao = 'Não retornou';					
				}
				echo '<tr>';
	    		echo '<td>'.$info_pausa['data_pausa'].'</td>';
				echo '<td>'.$data_retorno.'</td>';
				echo '<td>'.$duracao.'</td>';			
				echo '<td>'.$info_pausa['nome_pausa'].'</td>';	
				echo '<td>'.$info_pausa['nome_operador'].'</td>';
				echo '</tr>';
				$qtd_pausas ++;
			}			
		}
		echo "
			</tbody> 
			<tfoot>
		";
		echo '<tr>';
		echo '<th>Tempo médio: '.converteSegundosHoras($duracao_total/($qtd_pausas == 0 ? 1 : $qtd_pausas)).'</th>';
		echo '<th></th>';
		echo '<th></th>';			
		echo '<th></th>';	
		echo '<th></th>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>Tempo total: '.converteSegundosHoras($duracao_total).'</th>';
		echo '<th></th>';
		echo '<th></th>';			
		echo '<th></th>';	
		echo '<th></th>';
		echo '</tr>';
		echo "
				</tfoot> 
			</table>
		";
		echo "
        <script>
            $(document).ready(function(){
                var table = $('.dataTableGeral').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },
                    columnDefs: [
                        { type: 'time-uni', targets: 2 },
                    ],				        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });

                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'excelHtml5', footer: true,
                            text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                            filename: 'relatorio_pausas_equipe',
                            title : null,
                            exportOptions: {
                                modifier: {
                                page: 'all'
                                }
                            }
                            },
                    ],	
                    dom:
                    {
                        button: {
                            tag: 'button',
                            className: 'btn btn-default'
                        },
                        buttonLiner: { tag: null }
                    }
                }).container().appendTo($('#panel_buttons'));
            });
        </script>			
        ";
		if(!$tipo_pausa_filtro){
			echo "<hr>";
			echo "
				<table class=\"table table-hover dataTableAgrupado\">  
					<thead> 
						<tr> 
	    					<th class='col-md-6'>Tipo</th>
	    					<th class='col-md-6'>Tempo</th>
						</tr>
					</thead> 
					<tbody>"
			;
			arsort($duracao_tipos_pausa);		
			foreach ($duracao_tipos_pausa as $tipo => $duracao) {
				echo '<tr>';
	    		echo '<td>'.$tipo.'</td>';
				echo '<td>'.converteSegundosHoras($duracao).'</td>';
				echo '</tr>';			
			}
			echo "
					</tbody> 
				</table>
			";			
		}		
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableAgrupado').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 1 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";		
	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}
	
function relatorio_atendidas($data_de, $data_ate, $empresa, $operador, $fila, $segundos_atendimento, $nota, $plano){
	$data_hora = converteDataHora(getDataHora());
	$qtd_atendidas = 0;
	$soma_notas = 0;
	$qtd_notas = 0;
	$soma_ta = 0;
	$soma_te = 0;
	$id_usuario = $_SESSION['id_usuario'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
	$perfil_sistema = $dados[0]['id_perfil_sistema'];
	
	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
		}
	}else{
		$atendente_legenda = 'Todos';
	}
	if($segundos_atendimento){
		$descarte_legenda = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$descarte_legenda = '';
	}	
	if($nota){
		if($nota == 'n'){
			$nota_legenda = ", <strong>Ligações - </strong> Sem nota";
		}else{
			$nota_legenda = ", <strong>Ligações - </strong> Com nota $nota";
		}
	}else{
		$selecao_nota = '';
    }
    
    if($plano){
        $dados_plano = DBRead('','tb_plano', "WHERE id_plano = '$plano'");
        $legenda_plano = ', <strong>Plano - </strong>'.$dados_plano[0]['nome'];
    }elseif(!$empresa){
        $legenda_plano = ', <strong>Plano - </strong>Todos';
    }else{
        $legenda_plano = '';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ligações Atendidas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Empresa - </strong>".$empresa_legenda."".$legenda_plano.", <strong>Atendente - </strong>".$atendente_legenda."".$descarte_legenda."".$nota_legenda;
	echo "</legend>";

	$filtro = '';
    if($data_de){
		$filtro .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
	}
	// if($fila){
	// 	$filtro .= " AND a.queuename IN ($fila)";
	// }
	if($empresa){
		$filtro .= " AND a.data2 LIKE '$empresa%'";
	}
	if($operador){
		$filtro .= " AND b.agent = '$operador'";
	}
	if($segundos_atendimento){
		$filtro .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($nota){
		if($nota == 'n'){
			$filtro .= " AND d.nota IS NULL";
		}else{
			$filtro .= " AND d.nota = '$nota'";
		}
    }
    if($plano){
        $filtro_plano = ' AND (';
        $dados_empresas_plano = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_parametros b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE b.id_asterisk AND a.id_plano = '$plano'",'b.*');
        if($dados_empresas_plano){
            foreach($dados_empresas_plano as $conteudo_empresas_plano){
                $filtro_plano .= "a.data2 LIKE '".$conteudo_empresas_plano['id_asterisk']."%' OR ";
            }
        }
        $filtro_plano = substr($filtro_plano, 0, -4).')';
        $filtro .= $filtro_plano;
    }

	$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro GROUP BY a.callid ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', c.queuename AS ultimafila, d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

	registraLog('Relatório Central - Ligações Atendidas.','rel','relatorio_atendidas',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro GROUP BY a.callid ORDER BY b.id");


	if($dados_atendidas){		
		echo "
			<table class=\"table table-hover dataTable\">
				<thead> 
					<tr>
    					<th>Data</th>
    					<th>Atendente</th>
    					<th>Empresa</th>
    					<th>Número</th>
    					<th>Fila</th>
    					<th>Finalização</th>
    					<th>T. Espera</th>
    					<th>T. Atendimento</th>
						<th>Nota</th>
					"
		;
		if($perfil_sistema != '3' && $perfil_sistema != '28'){
			echo "<th class=\"noprint\">Gravação</th>";
		}
		echo "
					</tr>
				</thead> 
			<tbody>
		";
		foreach($dados_atendidas as $conteudo_atendidas){
			if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){
				
				$enterqueue_data2 = explode('-', $conteudo_atendidas['enterqueue_data2']);
				$id_empresa_entrada = $enterqueue_data2[0];
				$dados_empresa = DBRead('snep','empresas',"WHERE id='$id_empresa_entrada'");
				if($dados_empresa){
					$nome_empresa = $dados_empresa[0]['nome'];
				}else{
					$nome_empresa = 'Não identificada';
				}
				if(is_numeric(end($enterqueue_data2)) && strlen(end($enterqueue_data2)) >= 10){
					$bina = ltrim(end($enterqueue_data2), '0');
					if(substr($bina,0,3) == '+55'){
						$bina = substr($bina,3,11);
					}
				}elseif(end($enterqueue_data2) == 'anonymous'){
					$bina = 'Nº anônimo';
				}else{
					$bina = 'Não identificado';
				}
				$info_chamada = array(
					'data_hora_atendida' => '',
					'nome_operador' => '',
					'fila' => $conteudo_atendidas['ultimafila'],
					'nome_empresa' => $nome_empresa,
					'bina' => $bina,
					'tempo_espera' => '',
					'tempo_atendimento' => '',
					'nota' => '',
					'gravacao' => '',
					'finalizacao' => ''
				);	
				$info_chamada['data_hora_atendida'] = date('d/m/Y H:i:s', strtotime($conteudo_atendidas['connect_time']));
				$operador_chamada = explode('/', $conteudo_atendidas['connect_agent']);
				$tipo_operador_chamada = $operador_chamada[0];
				$cod_operador_chamada = $operador_chamada[1];
				if($tipo_operador_chamada == 'AGENT'){
					$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$cod_operador_chamada'");
					if($dados_operador){
						$info_chamada['nome_operador'] = $dados_operador[0]['membername'];
					}else{
						$info_chamada['nome_operador'] = $conteudo_atendidas['connect_agent'];
					}							
				}elseif($tipo_operador_chamada == 'SIP'){
					$dados_operador = DBRead('snep','peers',"WHERE name='$cod_operador_chamada'");
					if($dados_operador){
						$info_chamada['nome_operador'] = $dados_operador[0]['callerid'];
					}else{
						$info_chamada['nome_operador'] = $conteudo_atendidas['connect_agent'];
					}	
				}else{
					$info_chamada['nome_operador'] = $conteudo_atendidas['connect_agent'];
				}
				$dados_cdr = DBRead('snep','cdr',"WHERE uniqueid ='".$conteudo_atendidas['enterqueue_callid']."'");
				$arquivo_gravacao = explode(';', $dados_cdr[0]['userfield']);

				if(count($arquivo_gravacao) > 1) {
					$data_pasta = explode('_', $arquivo_gravacao[1]);
					$data_pasta = substr_replace($data_pasta[1], '-', -2, -3);
					$data_pasta = substr_replace($data_pasta, '-', -5, -5);

					$info_chamada['gravacao'] = 'https://pabx.bellunotec.com.br/snep/arquivos/'.$data_pasta.'/'.$arquivo_gravacao[1];

				}else{
					$data_pasta = explode('_', $arquivo_gravacao[0]);
					$data_pasta = substr_replace($data_pasta[1], '-', -2, -3);
					$data_pasta = substr_replace($data_pasta, '-', -5, -5);

					$info_chamada['gravacao'] = 'https://pabx.bellunotec.com.br/snep/arquivos/'.$data_pasta.'/'.$arquivo_gravacao[0];					
				}

				$info_chamada['nota'] = $conteudo_atendidas['nota'];
				if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){					
					$info_chamada['tempo_espera'] = $conteudo_atendidas['finalizacao_data1'];
					$info_chamada['tempo_atendimento'] = $conteudo_atendidas['finalizacao_data2'];
					if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT'){
						$info_chamada['finalizacao'] = 'Atendente';
					}else{
						$info_chamada['finalizacao'] = 'Cliente';
					}					
				}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
					$info_chamada['tempo_espera'] = $conteudo_atendidas['finalizacao_data3'];
					$info_chamada['tempo_atendimento'] = $conteudo_atendidas['finalizacao_data4'];
					$info_chamada['finalizacao'] = 'Transferida ('.$conteudo_atendidas['finalizacao_data1'].')';		
				}
				if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
					$info_chamada['tempo_espera'] += $conteudo_atendidas['tempo_espera_timeout'];
				}
				if($info_chamada['data_hora_atendida'] && $info_chamada['nome_operador'] && $info_chamada['nome_empresa'] && $info_chamada['fila'] &&  $info_chamada['finalizacao']){
					echo '<tr>';
					echo '<td>'.$info_chamada['data_hora_atendida'].'</td>';
					echo '<td>'.$info_chamada['nome_operador'].'</td>';
					echo '<td>'.$info_chamada['nome_empresa'].'</td>';
					echo '<td>'.$info_chamada['bina'].'</td>';
					echo '<td>'.$info_chamada['fila'].'</td>';			
					echo '<td>'.$info_chamada['finalizacao'].'</td>';
					echo '<td>'.gmdate("H:i:s", $info_chamada['tempo_espera']).'</td>';
					echo '<td>'. gmdate("H:i:s", $info_chamada['tempo_atendimento']).'</td>';
					echo '<td>'.$info_chamada['nota'].'</td>';
					if($perfil_sistema != '3' && $perfil_sistema != '28'){
						echo '<td class="noprint"><audio controls preload="auto" class="audio_gravacao"><source src="'.$info_chamada['gravacao'].'.mp3" type="audio/mp3"><source src="'.$info_chamada['gravacao'].'.wav" type="audio/wav">Seu navegador não aceita o player nativo.</audio></td>';
					}
					echo '</tr>';
					$qtd_atendidas++;
					if($info_chamada['nota']){
						$soma_notas += $info_chamada['nota'];
						$qtd_notas++;
					}			
					$soma_ta += $info_chamada['tempo_atendimento'];
					$soma_te += $info_chamada['tempo_espera'];				
				}
			}
		}
		echo '</tbody>
			<tfoot>
		';
		echo '    	
    	<tr>
    		<th>Médias:</th>
    		<th></th>
    		<th></th>
    		<th></th>
    		<th></th>    		
    		<th></th>
    		<th>'.gmdate("H:i:s", $soma_te/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</th>
    		<th>'.gmdate("H:i:s", $soma_ta/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</th>
    		<th>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</th>
		';
		if($perfil_sistema != '3' && $perfil_sistema != '28'){
			echo '<th class="noprint"></th>';
		}
		echo '</tr>';

    	echo '    	
    	<tr>
    		<th>Ligações com nota: '.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</th>
    		<th></th>
    		<th></th>
    		<th></th>    		
    		<th></th>    		
    		<th></th>
    		<th></th>
    		<th></th>
    		<th></th>
		';
		if($perfil_sistema != '3' && $perfil_sistema != '28'){
			echo '<th class="noprint"></th>';
		}
		echo '</tr>';

    	echo '    	
    	<tr>
    		<th>Total de registros: '.$qtd_atendidas.'</th>
    		<th></th>
    		<th></th> 
    		<th></th>   		
    		<th></th>  	
    		<th></th>  
    		<th></th>  
    		<th></th>  
    		<th></th>
		';
		if($perfil_sistema != '3' && $perfil_sistema != '28'){
			echo '<th class="noprint"></th>';
		}
		echo '</tr>';

    	echo "
				</tfoot> 
			</table>
		";
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTable').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 6 },
				    	{ type: 'time-uni', targets: 7 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";
	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_perdidas($data_de, $data_ate, $empresa, $fila, $segundos_espera_perdida){
	$data_hora = converteDataHora(getDataHora());
	$qtd_perdidas = 0;
	$soma_te = 0;
    
	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
    }
    
	if($segundos_espera_perdida){
		$descarte_legenda = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$descarte_legenda = '';
	}	
	

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ligações Perdidas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Empresa - </strong>".$empresa_legenda."".$descarte_legenda;
	echo "</legend>";

	$filtro = '';
    if($data_de){
		$filtro .= " AND a.time >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro .= " AND a.time <= '".converteData($data_ate)." 23:59:59'";
	}
	// if($fila){
	// 	$filtro .= " AND a.queuename IN ($fila)";
	// }
	if($empresa){
		$filtro .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_espera_perdida){
		$filtro .= " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
	}
	$dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT d.time FROM queue_log d WHERE d.callid = a.callid AND (d.event = 'ABANDON' OR d.event = 'EXITWITHTIMEOUT') ORDER BY d.id DESC LIMIT 1) AS 'finalizacao_time', b.queuename AS ultimafila, (SELECT e.event FROM queue_log e WHERE e.callid = a.callid AND (e.event = 'ABANDON' OR e.event = 'EXITWITHTIMEOUT') ORDER BY e.id DESC LIMIT 1) AS 'finalizacao_event', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

	registraLog('Relatório Central - Ligações Perdidas.','rel','relatorio_perdidas',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro GROUP BY a.callid ORDER BY a.callid");

	if($dados_perdidas){		
		echo "
			<table class=\"table table-hover dataTable\"> 
				<thead> 
					<tr> 
    					<th>Data</th>
    					<th>Empresa</th>
    					<th>Número</th>
    					<th>Fila</th>
    					<th>Finalização</th>
    					<th>T. Espera</th>
					</tr>
				</thead> 
				<tbody>"
		;
		foreach ($dados_perdidas as $conteudo_perdidas) {
			if(preg_match("/'".$conteudo_perdidas['ultimafila']."'/i", $fila) || !$fila){

				$enterqueue_data2 = explode('-', $conteudo_perdidas['enterqueue_data2']);
				$id_empresa_entrada = $enterqueue_data2[0];
				$dados_empresa = DBRead('snep','empresas',"WHERE id='$id_empresa_entrada'");
				if($dados_empresa){
					$nome_empresa = $dados_empresa[0]['nome'];
				}else{
					$nome_empresa = 'Não identificada';
				}
				if(is_numeric(end($enterqueue_data2)) && strlen(end($enterqueue_data2)) >= 10){
					$bina = ltrim(end($enterqueue_data2), '0');
					if(substr($bina,0,3) == '+55'){
						$bina = substr($bina,3,11);
					}
				}elseif(end($enterqueue_data2) == 'anonymous'){
					$bina = 'Nº anônimo';
				}else{
					$bina = 'Não identificado';
				}
				$info_chamada = array(
					'data_hora_perdida' => '',
					'fila' => $conteudo_perdidas['ultimafila'],
					'nome_empresa' => $nome_empresa,
					'bina' => $bina,
					'tempo_espera' => '',
					'finalizacao' => '',
				);
				$info_chamada['data_hora_perdida'] = date('d/m/Y H:i:s', strtotime($conteudo_perdidas['finalizacao_time']));
				$info_chamada['tempo_espera'] = $conteudo_perdidas['finalizacao_data3'];
				if($conteudo_perdidas['finalizacao_event']=='ABANDON'){
					$info_chamada['finalizacao'] = 'Desistência';
				}else{
					$info_chamada['finalizacao'] = 'Timeout';
				}
				if($info_chamada['data_hora_perdida'] && $info_chamada['nome_empresa'] && $info_chamada['fila'] &&  $info_chamada['finalizacao']){
					echo '<tr>';
					echo '<td>'.$info_chamada['data_hora_perdida'].'</td>';
					echo '<td>'.$info_chamada['nome_empresa'].'</td>';
					echo '<td>'.$info_chamada['bina'].'</td>';
					echo '<td>'.$info_chamada['fila'].'</td>';			
					echo '<td>'.$info_chamada['finalizacao'].'</td>';
					echo '<td>'.gmdate("H:i:s", $info_chamada['tempo_espera']).'</td>';
					echo '</tr>';
					$qtd_perdidas++;
					$soma_te += $info_chamada['tempo_espera'];				
				}
			}
		}
		echo "
			</tbody> 
			</tfoot>
		";
		echo '    	
    	<tr>
    		<th>Médias:</th>
    		<th></th>
    		<th></th>
    		<th></th>    		
    		<th></th>
    		<th>'.gmdate("H:i:s", $soma_te/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</th>
    	</tr>';
    	echo '    	
    	<tr>
    		<th>Total de registros: '.$qtd_perdidas.'</th>
    		<th></th>
    		<th></th>
    		<th></th>    		
    		<th></th>
    		<th></th>
    	</tr>';
    	echo "
				</tfoot> 
			</table>
		";
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTable').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 5 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";
	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_realizadas($data_de, $data_ate, $operador, $numero, $tag_saida){
	$data_hora = converteDataHora(getDataHora());
	
	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}
	
	if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
		}
	}else{
		$codigo_operador = '';
		$atendente_legenda = 'Todos';
	}

	if($tag_saida){
		$dados_tag_saida = DBRead('snep','ccustos',"WHERE codigo = '$tag_saida'");
		$tag_saida_legenda = $dados_tag_saida[0]['nome'];
	}else{
		$tag_saida_legenda = "Todas";
	}
	if($numero){
		$numero_legenda = ", <strong>Contém o número:</strong> $numero</span>";
	}else{
		$numero_legenda = '';
	}		

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ligações Realizadas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$atendente_legenda.", <strong>Tag de Saída - </strong>".$tag_saida_legenda."".$numero_legenda;
	echo "</legend>";

	$filtro  = '';
	if($data_de){
		$filtro .= " AND a.calldate >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro .= " AND a.calldate <= '".converteData($data_ate)." 23:59:59'";
	}
	if($numero){
		$filtro .= " AND a.dst LIKE '%$numero%'";
	}
	if($tag_saida){
		$filtro .= " AND b.codigo = '$tag_saida'";
	}
	$dados_realizadas = array();
	$cont = 0;
    $dados_realizadas_cdr = DBRead('snep','cdr a',"INNER JOIN ccustos b ON a.accountcode = b.codigo WHERE a.dcontext = 'default' AND a.lastapp = 'dial' AND (a.channel LIKE 'SIP/2%' OR a.channel LIKE 'SIP/4%' OR a.channel LIKE 'SIP/5%')  $filtro ORDER BY a.calldate ASC","a.*, b.nome");
	
	if($dados_realizadas_cdr){
        foreach ($dados_realizadas_cdr as $conteudo_realizadas_cdr) {
            $chamada = array(
                'data' => $conteudo_realizadas_cdr['calldate'],
                'agent' => '',
                'numero' => $conteudo_realizadas_cdr['dst'],
                'duracao' => $conteudo_realizadas_cdr['duration'],
                'tag_saida' => $conteudo_realizadas_cdr['nome'],
                'gravacao' => 'https://pabx.bellunotec.com.br/snep/arquivos/'.date('Y-m-d', strtotime($conteudo_realizadas_cdr['calldate'])).'/'.$conteudo_realizadas_cdr['userfield'],
			);
			
            $channel = explode('-',$conteudo_realizadas_cdr['channel']);
			$channel = $channel[0];
			
			$dados_queue_agents_login = DBRead('snep','queue_agents_log',"WHERE data_login <= '".$conteudo_realizadas_cdr['calldate']."' AND interface_logged = '".$channel."' AND (data_logoff >= '".$conteudo_realizadas_cdr['calldate']."' OR data_logoff IS NULL) ORDER BY data_login DESC LIMIT 1");			
			
            if ($dados_queue_agents_login) {
                $chamada['agent'] = $dados_queue_agents_login[0]['codigo'];
			}

            if(!$codigo_operador || $chamada['agent'] == $codigo_operador){
                $dados_realizadas[$cont] = $chamada;
                $cont++;
            }		
            unset($chamada);
        }
    }	
	if($dados_realizadas){
		echo "
			<table class=\"table table-hover dataTable\"> 
				<thead> 
					<tr> 
    					<th>Data</th>
    					<th>Atendente</th>
    					<th>Número</th>
    					<th>Tag de Saída</th>
    					<th>Duração</th>
						<th class=\"noprint\">Gravação</th>
					</tr>
				</thead> 
				<tbody>"
		;

		registraLog('Relatório Central - Ligações Realizadas.','rel','relatorio_realizadas',1,"INNER JOIN ccustos b ON a.accountcode = b.codigo WHERE a.dcontext = 'default' AND a.lastapp = 'dial' AND (a.channel LIKE 'SIP/2%' OR a.channel LIKE 'SIP/4%' OR a.channel LIKE 'SIP/5%')  $filtro ORDER BY a.calldate ASC","a.*, b.nome");


		foreach ($dados_realizadas as $conteudo_realizadas) {

			if($conteudo_realizadas['agent']){
				$agent = $conteudo_realizadas['agent'];
				$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$agent'");
				if($dados_operador){
					$nome_agent = $dados_operador[0]['membername'];
				}else{
					$nome_agent = 'Não identificado';
				}
			}else{
				$nome_agent = 'Não identificado';
			}
			echo '<tr>';
			echo '<td>'.converteDataHora($conteudo_realizadas['data']).'</td>';
			echo '<td>'.$nome_agent.'</td>';
			echo '<td>'.$conteudo_realizadas['numero'].'</td>';
			echo '<td>'.$conteudo_realizadas['tag_saida'].'</td>';
			echo '<td>'.gmdate("H:i:s", $conteudo_realizadas['duracao']).'</td>';
			echo '<td class="noprint"><audio controls preload="auto" class="audio_gravacao"><source src="'.$conteudo_realizadas['gravacao'].'.mp3" type="audio/mp3"><source src="'.$conteudo_realizadas['gravacao'].'.wav" type="audio/wav">Seu navegador não aceita o player nativo.</audio></td>';
			echo '</tr>';

		}
		echo '</tbody>
			<tfoot>
		';    	
    	echo '    	
    	<tr>
    		<th>Total de registros: '.sizeof($dados_realizadas).'</th>
    		<th></th>
    		<th></th> 
    		<th></th>   		
    		<th></th>  
    		<th class="noprint"></th>
    	</tr>';
    	echo "
				</tfoot> 
			</table>
		";
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTable').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 4 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";
	}else{
		echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
	}
	echo "</div>";
}

function pega_cor_tempo($tempo){    
    if ($tempo < 300) { // menor que 5 minutos
        return 'class="success"';
    } else if ($tempo >= 300 && $tempo < 600) { // entre 5 e 10 minutos
        return 'class="warning"';
    } else if ($tempo >= 600 && $tempo < 900) { // entre 10 e 15 minutos
        return 'class="danger"';
    } else if ($tempo >= 900 && $tempo < 1200) { // entre 15 e 20 minutos
        return 'style="background-color:red; color:white;"';        
    } else if ($tempo >= 1200) { // maior que 20 minutos
        return 'style="background-color:black; color:white;"';
    }
}

function relatorio_sintetico_geral($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida, $plano){
	
	$data_hora = converteDataHora(getDataHora());
	$qtd_atendidas = 0;
	$qtd_atendidas_30 = 0;
	$qtd_atendidas_60 = 0;
	$qtd_atendidas_90 = 0;
	$qtd_atendidas_180 = 0;
	$qtd_atendidas_maior_180 = 0;
	$qtd_perdidas = 0;
	$qtd_perdidas_30 = 0;
	$qtd_perdidas_60 = 0;
	$qtd_perdidas_90 = 0;
	$qtd_perdidas_180 = 0;
	$qtd_perdidas_maior_180 = 0;
	$soma_notas = 0;
	$qtd_notas = 0;
	$soma_ta_atendidas = 0;
	$soma_te_atendidas = 0;
	$soma_te_perdidas = 0;

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
        $empresa_legenda = 'Todas';
	}

	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
    }
    
    if($plano){
        $dados_plano = DBRead('','tb_plano', "WHERE id_plano = '$plano'");
        $legenda_plano = ', <strong>Plano - </strong>'.$dados_plano[0]['nome'];
    }elseif(!$empresa){
        $legenda_plano = ', <strong>Plano - </strong>Todos';
    }else{
        $legenda_plano = '';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Sintético Geral de Ligações</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_plano."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
    echo "</legend>";
    
	$filtro_atendidas = '';
	$filtro_perdidas = '';
	if($data_de){
		$filtro_atendidas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
		$filtro_perdidas .= " AND a.time >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){		
		$filtro_atendidas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
		$filtro_perdidas .= " AND a.time <= '".converteData($data_ate)." 23:59:59'";
	}
	// if($fila){
	// 	$filtro_atendidas .= " AND a.queuename IN ($fila)";
	// 	$filtro_perdidas .= " AND a.queuename IN ($fila)";
	// }
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
    }
    if($plano){
        $filtro_plano = ' AND (';
        $dados_empresas_plano = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_parametros b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE b.id_asterisk AND a.id_plano = '$plano'",'b.*');
        if($dados_empresas_plano){
            foreach($dados_empresas_plano as $conteudo_empresas_plano){
                $filtro_plano .= "a.data2 LIKE '".$conteudo_empresas_plano['id_asterisk']."%' OR ";
            }
        }
        $filtro_plano = substr($filtro_plano, 0, -4).')';
        $filtro_atendidas .= $filtro_plano;
        $filtro_perdidas .= $filtro_plano;
    }

	$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', c.queuename AS ultimafila, d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

	registraLog('Relatório Central - Sintetico Geral.','rel','relatorio_sintetico_geral',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY a.callid ORDER BY a.callid");
	
	if($dados_atendidas){
		foreach ($dados_atendidas as $conteudo_atendidas) {
			if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){
				$te_entrada = 'n';								
				if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
					$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
					$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];
					$te_entrada = $conteudo_atendidas['finalizacao_data1'];				
				}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
					$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
					$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
					$te_entrada = $conteudo_atendidas['finalizacao_data3'];
				}
				if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
					$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
				}
				if($te_entrada != 'n'){
					if($te_entrada <= 30){
						$qtd_atendidas_30++;
					}else if($te_entrada <= 60){
						$qtd_atendidas_60++;
					}else if($te_entrada <= 90){
						$qtd_atendidas_90++;
					}else if($te_entrada <= 180){
						$qtd_atendidas_180++;
					}else if($te_entrada > 180){
						$qtd_atendidas_maior_180++;
					}
				}
				if($conteudo_atendidas['nota']){
					$soma_notas += $conteudo_atendidas['nota'];
					$qtd_notas++;	
				}			
				$qtd_atendidas++;
			}
		}
    }
    
	$dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', b.queuename AS ultimafila, a.time, a.id");

	if($dados_perdidas){
		foreach ($dados_perdidas as $conteudo_perdidas) {

			if(preg_match("/'".$conteudo_perdidas['ultimafila']."'/i", $fila) || !$fila){

				if($conteudo_perdidas['finalizacao_data3'] <= 30){
					$qtd_perdidas_30++;

				}else if($conteudo_perdidas['finalizacao_data3'] <= 60){
					$qtd_perdidas_60++;

				}else if($conteudo_perdidas['finalizacao_data3'] <= 90){
					$qtd_perdidas_90++;

				}else if($conteudo_perdidas['finalizacao_data3'] <= 180){
					$qtd_perdidas_180++;

				}else if($conteudo_perdidas['finalizacao_data3'] > 180){
					$qtd_perdidas_maior_180++;
				}
				$qtd_perdidas++;			
				$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
			}
		}
	}

	echo '
	<table class="table table-hover"> 
		<thead> 
			<tr> 
				<th class="text-center" width="16.6666666667%">Total de atendidas</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 30s</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 60s</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 90s</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 180s</th>
				<th class="text-center" width="16.6666666667%">Atendidas maiores de 180s</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td class="text-center success">'.$qtd_atendidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_30*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_60*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_90*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
			</tr>
		</tbody> 
	</table>
	<table class="table table-hover"> 
		<thead> 
			<tr> 
				<th class="text-center" width="16.6666666667%">Total de perdidas</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 30s</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 60s</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 90s</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 180s</th>
				<th class="text-center" width="16.6666666667%">Perdidas maiores de 180s</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td class="text-center danger">'.$qtd_perdidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_30*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_60*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_90*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
			</tr>
		</tbody> 
	</table>	
	<table class="table table-hover"> 
		<thead> 
			<tr> 
				<th class="text-center" width="16.6666666667%">Total de ligações</th>
				<th class="text-center" width="16.6666666667%" title="Tempo Médio de Atendimento">TMA</th>
				<th class="text-center" width="16.6666666667%" title="Nota Média de Atendimento">NMA</th>
				<th class="text-center" width="16.6666666667%" title="Ligações com Nota">LN</th>				
				<th class="text-center" width="16.6666666667%" title="Tempo Médio de Espera">TME</th>
				<th class="text-center" width="16.6666666667%" title="Tempo Médio das Perdidas">TMP</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td class="text-center active">'.($qtd_atendidas+$qtd_perdidas).'</td>
				<td class="text-center info">'.gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
				<td class="text-center info">'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
				<td class="text-center info">'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</td>
				<td class="text-center warning">'.gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
				<td class="text-center warning">'.gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</td>
			</tr>
		</tbody> 
	</table>
	';  
}

function relatorio_sintetico_empresa($data_de, $data_ate, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$data_hora = converteDataHora(getDataHora());
	
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = "<strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Sintético de Ligações por Empresa</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	if($legenda_descarte_segundos_atendimento || $legenda_descarte_segundos_espera_perdida){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida.'</legend>';
	}
	
	$filtro_atendidas = '';
	$filtro_perdidas = '';

	if($data_de){
		$filtro_atendidas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
		$filtro_perdidas .= " AND a.time >= '".converteData($data_de)." 00:00:00'";
	}

	if($data_ate){		
		$filtro_atendidas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
		$filtro_perdidas .= " AND a.time <= '".converteData($data_ate)." 23:59:59'";
	}

	// if($fila){
	// 	$filtro_atendidas .= " AND a.queuename IN ($fila)";
	// 	$filtro_perdidas .= " AND a.queuename IN ($fila)";
	// }

	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
	}

	$dados_empresas = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE d.id_asterisk AND c.cod_servico = 'call_suporte' AND a.status = 1 ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.nome_contrato, a.qtd_contratada, b.nome AS 'nome_empresa', c.nome AS 'nome_plano', d.id_asterisk");

	if($dados_empresas){
		echo '
			<table class="table table-hover dataTable"> 
				<thead> 
					<tr> 
						<th>Empresa</th>
						<th>Sistema</th>
						<th>Plano</th>
						<th>QTD Contratada</th>
						<th>Ligações</th>
						<th>Atendidas</th>
						<th>Perdidas</th>
						<th title="Tempo Médio de Atendimento">TMA</th>
						<th title="Nota Média de Atendimento">NMA</th>
						<th title="Ligações com Nota">LN</th>				
						<th title="Tempo Médio de Espera">TME</th>
						<th title="Tempo Médio das Perdidas">TMP</th>
						<th>Última Ligação</th>
					</tr>
				</thead>
				<tbody>
		';
		$link = DBConnect('snep');
		DBbegin($link);

		foreach ($dados_empresas as $conteudo_empresa) {
			$qtd_atendidas = 0;
			$qtd_perdidas = 0;
			$soma_notas = 0;
			$qtd_notas = 0;
			$soma_ta_atendidas = 0;
			$soma_te_atendidas = 0;
			$soma_te_perdidas = 0;

			$filtro_atendidas_empresa = " AND a.data2 LIKE '".$conteudo_empresa['id_asterisk']."%'";
			$filtro_perdidas_empresa = " AND a.data2 LIKE '".$conteudo_empresa['id_asterisk']."%'";

			$dados_atendidas = DBReadTransaction($link,'queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas $filtro_atendidas_empresa GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', c.queuename AS ultimafila, d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			registraLog('Relatório Central - Sintetico por empresa.','rel','relatorio_sintetico_empresa',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas $filtro_atendidas_empresa GROUP BY b.id ORDER BY b.id");


			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {
					if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){

						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						if($conteudo_atendidas['nota']){
							$soma_notas += $conteudo_atendidas['nota'];
							$qtd_notas++;	
						}			
						$qtd_atendidas++;
					}
				}
			}

			$dados_ultima_chamada = DBReadTransaction($link,'queue_log a',"WHERE a.event = 'ENTERQUEUE' $filtro_atendidas_empresa ORDER BY a.id DESC LIMIT 1", "a.time");

			if($dados_ultima_chamada){
				$data_ultima_chamada = date('d/m/Y H:i:s', strtotime($dados_ultima_chamada[0]['time']));
			}else{
				$data_ultima_chamada = '';
			}
            
            $dados_perdidas = DBReadTransaction($link,'queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas $filtro_perdidas_empresa GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', b.queuename AS ultimafila");

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {	
					if(preg_match("/'".$conteudo_perdidas['ultimafila']."'/i", $fila) || !$fila){
						$qtd_perdidas++;			
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
					}
				}
			}

			if($conteudo_empresa['nome_contrato']){
				$nome_contrato = " (".$conteudo_empresa['nome_contrato'].")";
			}else{
				$nome_contrato = '';
			}

			$dados_sistema = DBRead('','tb_sistema_gestao_contrato a',"INNER JOIN tb_tipo_sistema_gestao b ON a.id_tipo_sistema_gestao = b.id_tipo_sistema_gestao WHERE a.id_contrato_plano_pessoa = '".$conteudo_empresa['id_contrato_plano_pessoa']."'",'b.nome');
			if($dados_sistema){
				$sistema_gestao = $dados_sistema[0]['nome'];
			}else{
				$sistema_gestao = '-';
			}

			echo '
				<tr>
					<td class="">'.$conteudo_empresa['nome_empresa'].$nome_contrato.'</td>
					<td class="">'.$sistema_gestao.'</td>
					<td class="">'.$conteudo_empresa['nome_plano'].'</td>
					<td class="">'.$conteudo_empresa['qtd_contratada'].'</td>
					<td class="active">'.($qtd_atendidas+$qtd_perdidas).'</td>
					<td class="success">'.$qtd_atendidas.'</td>
					<td class="danger">'.$qtd_perdidas.'</td>
					<td class="info">'.gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
					<td class="info">'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
					<td class="info">'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</td>
					<td class="warning">'.gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
					<td class="warning">'.gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</td>
					<td>'.$data_ultima_chamada.'</td>
				</tr>
			';
						
		}

		DBCommit($link);

		echo '		
				</tbody> 
			</table>
		';
		echo "
			<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
							{ type: 'time-uni', targets: 6 },
							{ type: 'time-uni', targets: 9 },
							{ type: 'time-uni', targets: 10 },
						],
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});
					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_sintetico_ligacoes_empresa',
           						title : null,
								exportOptions: {
								  modifier: {
									page: 'all'
								  }
								}
							},
						],	
						dom:
						{
							button: {
								tag: 'button',
								className: 'btn btn-default'
							},
							buttonLiner: { tag: null }
						}
				   }).container().appendTo($('#panel_buttons'));
				});
			</script>			
		";
	}

	echo "<br>";
	echo "</div>";
}

function relatorio_grafico_hora_acumulado_semana($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){

	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$horas = array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00');
	$atendidas = array();
	$perdidas = array();
	$total_entradas = array();
	$data_hora = converteDataHora(getDataHora());
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Ligações por Hora - Acumulado por Dia da Semana</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = '';
	$filtro_perdidas = '';
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}

	registraLog('Relatório Central - Gráfico de Ligações por Hora - Acumulado por Dia da Semana','rel','relatorio_grafico_hora_acumulado_semana',1,"intervalo de datas");
	
	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$atendidas_hora = 0;
			$perdidas_hora = 0;

			$dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

			if($dados_atendidas_grafico){

				foreach ($dados_atendidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$atendidas_hora++;			
					}
				}

			}else{
				$atendidas_hora = 0;
			}

			$dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

			if($dados_perdidas_grafico){

				foreach ($dados_perdidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$perdidas_hora++;			
					}
				}

			}else{
				$perdidas_hora = 0;
			}

			$atendidas[$numero_dia_semana][$hora] += $atendidas_hora;
	        $perdidas[$numero_dia_semana][$hora] += $perdidas_hora;
	        $total_entradas[$numero_dia_semana][$hora] += $atendidas_hora + $perdidas_hora;
			$hora++;
		}		
	}
	$cont_dia = 0;
	while($cont_dia < 7){
		$cont_hora = 0;
		while($cont_hora < 24){
			if(!$atendidas[$cont_dia][$cont_hora]){$atendidas[$cont_dia][$cont_hora]=0;}
			if(!$perdidas[$cont_dia][$cont_hora]){$perdidas[$cont_dia][$cont_hora]=0;}
			if(!$total_entradas[$cont_dia][$cont_hora]){$total_entradas[$cont_dia][$cont_hora]=0;}
			$cont_hora++;
		}		
		$cont_dia++;
	}
	$chart = 0;
	while($chart < 7){	
	?>
		<div id="<?php echo "chart-" . $chart; ?>"></div> 
		<script>
			$(function () {
				// Create the first chart
				$('#<?php echo "chart-" . $chart; ?>').highcharts({
					chart: {
						type: '<?=$nome_tipo_grafico;?>'
					},
					title: {
						text: '<?=$dias_semana[$chart];?>' // Title for the chart
					},
					xAxis: {
						categories: <?php echo json_encode($horas) ?>
						// Categories for the charts
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },                    
                    <?php 
                    if($tipo_grafico == 3){//se for pilha
                        echo "
                        colors: [
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }else{
                        echo "									
                        colors: [
                            '#0000CD',
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }
                    ?>
					plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			";
			    		}
				    	?>
				    },
					series: [	
						<?php 
                    	if($tipo_grafico != 3){//se não for pilha
                    		echo "
							{
                                name: 'Entradas', // Name of your series
	                            data: ".json_encode($total_entradas[$chart], JSON_NUMERIC_CHECK)." // The data in your series
	                        },
                    		";
                    	}
                    	?>
                    	{
                            name: 'Atendidas', // Name of your series
                            data: <?php echo json_encode($atendidas[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Não atendidas', // Name of your series
                            data: <?php echo json_encode($perdidas[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

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
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_tempos_medios_hora_acumulado_semana($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$horas = array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00');
	$tma = array();
    $tme = array();
    $tmp = array();
    $qtd_total_atendidas = array();
    $qtd_total_perdidas = array();
	$data_hora = converteDataHora(getDataHora());
	$tipo_grafico = 1;
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Tempos Médios por Hora - Acumulado por Dia da Semana</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = "";
	$filtro_perdidas = "";

	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
	}

	registraLog('Relatório Central - Gráfico de Tempos Médios por Hora - Acumulado por Dia da Semana','rel','relatorio_grafico_tempos_medios_hora_acumulado_semana',1,"intervalo de datas");

	
	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$qtd_atendidas = 0;
			$qtd_perdidas = 0;
			$soma_ta_atendidas = 0;
			$soma_te_atendidas = 0;
			$soma_te_perdidas = 0;

			$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");	

			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {

					if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						$qtd_atendidas++;			
					}
				}
			}	
            
            $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {
					if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
						$qtd_perdidas++;
					}
				}
			}

			$tma[$numero_dia_semana][$hora] += $soma_ta_atendidas;
	        $tme[$numero_dia_semana][$hora] += $soma_te_atendidas;
	        $tmp[$numero_dia_semana][$hora] += $soma_te_perdidas;
	        $qtd_total_atendidas[$numero_dia_semana][$hora] += $qtd_atendidas;
	        $qtd_total_perdidas[$numero_dia_semana][$hora] += $qtd_perdidas;
			$hora++;
		}		
	}
	
	$cont_dia = 0;
	while($cont_dia < 7){
		$cont_hora = 0;
		while($cont_hora < 24){
			$tma[$cont_dia][$cont_hora] = sprintf("%01.2f", round($tma[$cont_dia][$cont_hora]/($qtd_total_atendidas[$cont_dia][$cont_hora] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora]), 2));
			$tme[$cont_dia][$cont_hora] = sprintf("%01.2f", round($tme[$cont_dia][$cont_hora]/($qtd_total_atendidas[$cont_dia][$cont_hora] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora]), 2));
			$tmp[$cont_dia][$cont_hora] = sprintf("%01.2f", round($tmp[$cont_dia][$cont_hora]/($qtd_total_perdidas[$cont_dia][$cont_hora] == 0 ? 1 : $qtd_total_perdidas[$cont_dia][$cont_hora]), 2));
			$cont_hora++;
		}		
		$cont_dia++;
	}
	$chart = 0;
	while($chart < 7){	
	?>
		<div id="<?php echo "chart-" . $chart; ?>"></div> 
		<script>
			$(function () {
				// Create the first chart
				$('#<?php echo "chart-" . $chart; ?>').highcharts({
					chart: {
						type: '<?=$nome_tipo_grafico;?>'
					},
					title: {
						text: '<?=$dias_semana[$chart];?>' // Title for the chart
					},
					xAxis: {
						categories: <?php echo json_encode($horas) ?>
						// Categories for the charts
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Ligações por Hora'
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
						headerFormat: '<b>{point.x}</b><br/>',
						pointFormat: '{point.y} segundos'
					},
					plotOptions: {
						<?php 
						if($tipo_grafico == 3){//se for pilha
							echo "
							column: {
								stacking: 'normal',
								dataLabels: {
									enabled: true,
									color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
								}
							}
							";
						}else{
							echo "									
							series: {
								dataLabels: {
									enabled: true,
									formatter: function () {
										var decimalTimeString = this.y;
										var decimalTime = parseFloat(decimalTimeString);
										var hours = Math.floor((decimalTime / (60 * 60)));
										decimalTime = decimalTime - (hours * 60 * 60);
										var minutes = Math.floor((decimalTime / 60));
										decimalTime = decimalTime - (minutes * 60);
										var seconds = Math.round(decimalTime);
										if(hours < 10){
											hours = '0' + hours;
										}
										if(minutes < 10){
											minutes = '0' + minutes;
										}
										if(seconds < 10){
											seconds = '0' + seconds;
										}
										return hours + ':' + minutes + ':' + seconds;
									}
								}
							}
							";
						}
						?>
					},
					series: [
						{
							name: 'Tempo Médio de Atendimento', // Name of your series
							data: <?php echo json_encode($tma[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

						},
						{
							name: 'Tempo Médio de Espera', // Name of your series
							data: <?php echo json_encode($tme[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

						},
						{
							name: 'Tempo Médio de Perdidas', // Name of your series
							data: <?php echo json_encode($tmp[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

					}],
					navigation: {
						buttonOptions: {
							enabled: true
						}
					}
				});
			});
		</script>  
	<?php
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_tempos_medios_dia_semana_acumulado_geral($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$tma = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
    $tme = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
    $tmp = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
    $qtd_total_atendidas = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
    $qtd_total_perdidas = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
	$data_hora = converteDataHora(getDataHora());
	$tipo_grafico = 1;
	$nome_tipo_grafico = 'line';

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Tempos Médios por Dia da Semana - Acumulado Geral</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;

	registraLog('Relatório Central - Gráfico de Tempos Médios por Dia da Semana - Acumulado Geral','rel','relatorio_grafico_tempos_medios_dia_semana_acumulado_geral',1,"intervalo de datas");

	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$qtd_atendidas = 0;
			$qtd_perdidas = 0;
			$soma_ta_atendidas = 0;
			$soma_te_atendidas = 0;
			$soma_te_perdidas = 0;

			$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");	

			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {

					if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						$qtd_atendidas++;
					}
				}
			}		
            
            $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {
					if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
						$qtd_perdidas++;
					}
				}
			}

			$tma[$numero_dia_semana] += $soma_ta_atendidas;
	        $tme[$numero_dia_semana] += $soma_te_atendidas;
	        $tmp[$numero_dia_semana] += $soma_te_perdidas;
	        $qtd_total_atendidas[$numero_dia_semana] += $qtd_atendidas;
	        $qtd_total_perdidas[$numero_dia_semana] += $qtd_perdidas;
	        $horas[] = $hora_zero.':00';
			$hora++;
		}		
	}
	$cont = 0;
	while($cont < 7){
		$tma[$cont] = sprintf("%01.2f", round($tma[$cont]/($qtd_total_atendidas[$cont] == 0 ? 1 : $qtd_total_atendidas[$cont]), 2));
		$tme[$cont] = sprintf("%01.2f", round($tme[$cont]/($qtd_total_atendidas[$cont] == 0 ? 1 : $qtd_total_atendidas[$cont]), 2));
		$tmp[$cont] = sprintf("%01.2f", round($tmp[$cont]/($qtd_total_perdidas[$cont] == 0 ? 1 : $qtd_total_perdidas[$cont]), 2));
		$cont++;
	}
	?>
	<div id="<?php echo "chart-" . $chart; ?>"></div> 
	<script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($dias_semana) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
						pointFormat: '{point.y} segundos'
				    },
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true,
									formatter: function () {
										var decimalTimeString = this.y;
										var decimalTime = parseFloat(decimalTimeString);
										var hours = Math.floor((decimalTime / (60 * 60)));
										decimalTime = decimalTime - (hours * 60 * 60);
										var minutes = Math.floor((decimalTime / 60));
										decimalTime = decimalTime - (minutes * 60);
										var seconds = Math.round(decimalTime);
										if(hours < 10){
											hours = '0' + hours;
										}
										if(minutes < 10){
											minutes = '0' + minutes;
										}
										if(seconds < 10){
											seconds = '0' + seconds;
										}
										return hours + ':' + minutes + ':' + seconds;
									}
	                            }
							}
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	{
                            name: 'Tempo Médio de Atendimento', // Name of your series
                            data: <?php echo json_encode($tma, JSON_NUMERIC_CHECK) ?> // The data in your series
                        },
                    	{
                            name: 'Tempo Médio de Espera', // Name of your series
                            data: <?php echo json_encode($tme, JSON_NUMERIC_CHECK) ?> // The data in your series
                        },
                        {
                            name: 'Tempo Médio de Perdidas', // Name of your series
                            data: <?php echo json_encode($tmp, JSON_NUMERIC_CHECK) ?> // The data in your series
                    }],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>  
	<?php
	echo '<hr>';
}

function relatorio_grafico_tempos_medios_hora($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$data_hora = converteDataHora(getDataHora());
	$tipo_grafico = 1;
	$nome_tipo_grafico = 'line';
	
	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Tempos Médios por Hora - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";

    $filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;

	registraLog('Relatório Central - Gráfico de Tempos Médios por Hora - Separado por Dia do Mês','rel','relatorio_grafico_tempos_medios_hora',1,"intervalo de datas");

	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
		$tma = array();
        $tme = array();
        $tmp = array();
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$qtd_atendidas = 0;
			$qtd_perdidas = 0;
			$soma_ta_atendidas = 0;
			$soma_te_atendidas = 0;
			$soma_te_perdidas = 0;


			$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {

					if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						
						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						$qtd_atendidas++;
					}
				}
			}				

            $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {	
					if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
						$qtd_perdidas++;
					}
				}
			}	
			
			$tma[] = sprintf("%01.2f", round($soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
	        $tme[] = sprintf("%01.2f", round($soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
			$tmp[] = sprintf("%01.2f", round($soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2));
	        $horas[] = $hora_zero.':00';
			$hora++;
		}

		?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
						pointFormat: '{point.y} segundos'
				    },
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true,
									formatter: function () {
										var decimalTimeString = this.y;
										var decimalTime = parseFloat(decimalTimeString);
										var hours = Math.floor((decimalTime / (60 * 60)));
										decimalTime = decimalTime - (hours * 60 * 60);
										var minutes = Math.floor((decimalTime / 60));
										decimalTime = decimalTime - (minutes * 60);
										var seconds = Math.round(decimalTime);
										if(hours < 10){
											hours = '0' + hours;
										}
										if(minutes < 10){
											minutes = '0' + minutes;
										}
										if(seconds < 10){
											seconds = '0' + seconds;
										}
										return hours + ':' + minutes + ':' + seconds;
									}
	                            }
							}
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	{
                            name: 'Tempo Médio de Atendimento', // Name of your series
                            data: <?php echo json_encode($tma, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                    	{
                            name: 'Tempo Médio de Espera', // Name of your series
                            data: <?php echo json_encode($tme, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Tempo Médio de Perdidas', // Name of your series
                            data: <?php echo json_encode($tmp, JSON_NUMERIC_CHECK) ?> // The data in your series

                        }],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_dia_semana_acumulado_geral($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$atendidas = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
    $perdidas = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
    $total_entradas = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
	$data_hora = converteDataHora(getDataHora());
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Ligações por Dia da Semana - Acumulado Geral</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;

	registraLog('Relatório Central - Gráfico de Ligações por Dia da Semana - Acumulado Geral','rel','relatorio_grafico_dia_semana_acumulado_geral',1,"intervalo de datas");

	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$atendidas_hora = 0;
			$perdidas_hora = 0;

			$dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");		

			if($dados_atendidas_grafico){
				foreach ($dados_atendidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$atendidas_hora++;			
					}
				}
			}else{
				$atendidas_hora = 0;
			}			

			$dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time >= '$data $hora_zero:00:00' AND a.time <= '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

			if($dados_perdidas_grafico){
				foreach ($dados_perdidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$perdidas_hora++;			
					}
				}
			}else{
				$perdidas_hora = 0;
			}

			$atendidas[$numero_dia_semana] += $atendidas_hora;
	        $perdidas[$numero_dia_semana] += $perdidas_hora;
	        $total_entradas[$numero_dia_semana] += $atendidas_hora + $perdidas_hora;
	        $horas[] = $hora_zero.':00';
			$hora++;
		}
	}
	?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($dias_semana) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },
                    <?php 
                    if($tipo_grafico == 3){//se for pilha
                        echo "
                        colors: [
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }else{
                        echo "									
                        colors: [
                            '#0000CD',
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }
                    ?>
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	<?php 
                    	if($tipo_grafico != 3){//se não for pilha
                    		echo "
							{
	                            name: 'Entradas', // Name of your series
	                            data: ".json_encode($total_entradas, JSON_NUMERIC_CHECK)." // The data in your series
	                        },
                    		";
                    	}
                    	?>
                    	{
                            name: 'Atendidas', // Name of your series
                            data: <?php echo json_encode($atendidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Não atendidas', // Name of your series
                            data: <?php echo json_encode($perdidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        }],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
		echo '<hr>';
}

function relatorio_grafico_hora($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$data_hora = converteDataHora(getDataHora());
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Ligações por Hora - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";

	$filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;

	registraLog('Relatório Central - Gráfico de Ligações por Hora - Separado por Dia do Mês','rel','relatorio_grafico_hora',1,"intervalo de datas");

	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
		$atendidas = array();
        $perdidas = array();
        $total_entradas = array();
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$atendidas_hora = 0;
			$perdidas_hora = 0;

			$dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");
			
			if($dados_atendidas_grafico){
				foreach ($dados_atendidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$atendidas_hora++;			
					}
				}
			}else{
				$atendidas_hora = 0;
			}

			$dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

			if($dados_perdidas_grafico){
				foreach ($dados_perdidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$perdidas_hora++;			
					}
				}
			}else{
				$perdidas_hora = 0;
			}

			$atendidas[] = $atendidas_hora;
	        $perdidas[] = $perdidas_hora;
	        $total_entradas[] = $atendidas_hora + $perdidas_hora;
	        $horas[] = $hora_zero.':00';
			$hora++;
		}

		?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },
                    <?php 
                    if($tipo_grafico == 3){//se for pilha
                        echo "
                        colors: [
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }else{
                        echo "									
                        colors: [
                            '#0000CD',
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }
                    ?>
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	<?php 
                    	if($tipo_grafico != 3){//se não for pilha
                    		echo "
							{
	                            name: 'Entradas', // Name of your series
	                            data: ".json_encode($total_entradas, JSON_NUMERIC_CHECK)." // The data in your series
	                        },
                    		";
                    	}
                    	?>
                    	{
                            name: 'Atendidas', // Name of your series
                            data: <?php echo json_encode($atendidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Não atendidas', // Name of your series
                            data: <?php echo json_encode($perdidas, JSON_NUMERIC_CHECK) ?> // The data in your series

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
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_hora_dia_empresas($tipo_grafico, $data, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$data_hora = converteDataHora(getDataHora());
	
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

    $filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;
    $data = converteData($data);
	$dados_empresas = DBRead('snep','empresas',"WHERE status = '1' ORDER BY nome ASC");								            	
	
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = "<strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		if($segundos_atendimento){
			$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
		}else{
			$legenda_descarte_segundos_espera_perdida = "<strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
		}
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	if($data){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Data:</strong> ".converteData($data)."</span>";
	}else{
	    $periodo_amostra = "";
	}
    
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico diário de Ligações por Hora - Acumulado por Empresas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";

	registraLog('Relatório Central - Gráfico diário de Ligações por Hora - Acumulado por Empresas','rel','relatorio_grafico_hora_dia_empresas',1,"intervalo de datas");

	foreach($dados_empresas as $conteudo_empresas){
		$filtro_empresa_atendidas = " AND a.data2 LIKE '".$conteudo_empresas['id']."%'";
		$filtro_empresa_perdidas = " AND a.data2 LIKE '".$conteudo_empresas['id']."%'";

		$atendidas = array();
        $qtd_perdidas = array();
        $total_entradas = array();
        $horas = array();
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$atendidas_hora = 0;
			$perdidas_hora = 0;

			$dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_atendidas $filtro_empresa_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");
			
			if($dados_atendidas_grafico){
				foreach ($dados_atendidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$atendidas_hora++;			
					}
				}
			}else{
				$atendidas_hora = 0;
			}

			$dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas $filtro_empresa_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

			if($dados_perdidas_grafico){
				foreach ($dados_perdidas_grafico as $conteudo) {
					if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
						$perdidas_hora++;			
					}
				}
			}else{
				$perdidas_hora = 0;
			}

			$atendidas[] = $atendidas_hora;
	        $perdidas[] = $perdidas_hora;
	        $total_entradas[] = $atendidas_hora + $perdidas_hora;
	        $horas[] = $hora_zero.':00';
			$hora++;
		}

		?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '<?php echo $conteudo_empresas['nome']; ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },
                    <?php 
                    if($tipo_grafico == 3){//se for pilha
                        echo "
                        colors: [
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }else{
                        echo "									
                        colors: [
                            '#0000CD',
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }
                    ?>
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	<?php 
                    	if($tipo_grafico != 3){//se não for pilha
                    		echo "
							{
	                            name: 'Entradas', // Name of your series
	                            data: ".json_encode($total_entradas, JSON_NUMERIC_CHECK)." // The data in your series
	                        },
                    		";
                    	}
                    	?>
                    	{
                            name: 'Atendidas', // Name of your series
                            data: <?php echo json_encode($atendidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Não atendidas', // Name of your series
                            data: <?php echo json_encode($perdidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        }],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
		echo '<hr>';
		$chart++;
	}
}

function relatorio_tempo_ponto_login($data_de, $data_ate, $operador){
    $data_hora = converteDataHora(getDataHora());

    $dados_ponto_login = array();
    $dados_ponto_login_medias = array();
    $diferenca_ponto_login_total = 0;
    $diferenca_login_ligacao_total = 0;
    $cont_ponto_login = 0;
    $cont_login_ligacao = 0;

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
        }
	}else{
		$atendente_legenda = 'Todos';
	}	

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Tempo entre Ponto e Login</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$atendente_legenda;
    echo "</legend>";
    
    $filtro_usuario = '';
	if($operador){
        $dados_usuario_operador = DBRead('','tb_usuario',"WHERE id_asterisk = '$codigo_operador'");
		$filtro_usuario .= " AND id_usuario = '".$dados_usuario_operador[0]['id_usuario']."'";
    }
    $dados_usuario = DBRead('','tb_usuario',"WHERE id_asterisk AND id_ponto AND status='1' AND id_perfil_sistema = '3'$filtro_usuario");

	registraLog('Relatório Central - Relatório de Tempo entre Ponto e Login','rel','relatorio_tempo_ponto_login',1,"WHERE id_asterisk AND id_ponto AND status='1' AND id_perfil_sistema = '3'$filtro_usuario");

    if($dados_usuario){
        foreach ($dados_usuario as $conteudo_usuario) {
            
            $data_string_ponto = '
				{
					"report": {				
						"start_date": "'.converteData($data_de).'",
				        "end_date": "'.converteData($data_ate).'",
						"group_by": "employee",
						"columns": "date,time_cards",
						"employee_id": '.$conteudo_usuario['id_ponto'].',
						"row_filters": "",
						"format": "json"
					}
				}
			';  
			
            $result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/work_days', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
            if($result_ponto['data'][0][0]['data']){
                foreach($result_ponto['data'][0][0]['data'] as $dia){                
                    //verifica se tem batidas pares
                    if(sizeof($dia['time_cards']) % 2 == 0){
                        $cont_batida = 1;
    
                        foreach($dia['time_cards'] as $conteudo_batida){
                            if($cont_batida % 2 != 0){
                                $dados_batida = explode(" ",$conteudo_batida['title']);
    
                                $data_hora_batida = converteData($dados_batida[3]).' '.$dados_batida[5].":00";
    
                                $dados_login_maior = DBRead('snep','queue_agents_log',"WHERE codigo = '".$conteudo_usuario['id_asterisk']."' AND data_login >= '$data_hora_batida' ORDER BY data_login ASC LIMIT 1");
    
                                $dados_login_menor = DBRead('snep','queue_agents_log',"WHERE codigo = '".$conteudo_usuario['id_asterisk']."' AND data_login < '$data_hora_batida' ORDER BY data_login DESC LIMIT 1");

                                $dados_operador = DBRead('snep','queue_agents',"WHERE codigo='".$conteudo_usuario['id_asterisk']."'");
    
                                $data_hora_login_maior = substr($dados_login_maior[0]['data_login'],0,16).':00';
                                $data_hora_login_menor = substr($dados_login_menor[0]['data_login'],0,16).':00';
    
                                if($data_hora_login_maior != ':00' && $data_hora_batida != ' -:00' && $data_hora_login_menor != ':00'){
                                    $data_hora_batida = new DateTime($data_hora_batida);
                                    $data_hora_login_maior = new DateTime($data_hora_login_maior);
                                    $data_hora_login_menor = new DateTime($data_hora_login_menor);
                                                                  
                                    $diferenca_maior  = $data_hora_batida->diff($data_hora_login_maior); 
                                    $diferenca_maior = $diferenca_maior->format("%H:%I");

                                    $diferenca_menor  = $data_hora_login_menor->diff($data_hora_batida); 
                                    $diferenca_menor = $diferenca_menor->format("%H:%I");

                                    if($diferenca_maior > $diferenca_menor){
                                        $data_hora_login = $data_hora_login_menor;
                                        $diferenca_ponto_login = 0;
                                    }else{
                                        $data_hora_login = $data_hora_login_maior;
                                        $diferenca_ponto_login = $diferenca_maior;
                                        $aux_dif = explode(':',$diferenca_ponto_login);
                                        $diferenca_ponto_login = (($aux_dif[0]*60) + $aux_dif[1]);
                                    }                                    
                                                                      
                                    $diferenca_ponto_login_total += $diferenca_ponto_login;

                                    $dados_atendidas = DBRead('snep','queue_log'," WHERE event = 'CONNECT' AND agent = 'AGENT/".$conteudo_usuario['id_asterisk']."' AND time >= '".$data_hora_login->format('Y-m-d H:i:s')." 00:00:00' ORDER BY time ASC LIMIT 1");
                                    if($dados_atendidas){
                                        $data_hora_ligacao = new DateTime($dados_atendidas[0]['time']);

                                        $diferenca_login_ligacao  = $data_hora_ligacao->diff($data_hora_login); 
                                        $diferenca_login_ligacao = $diferenca_login_ligacao->format("%H:%I");

                                        $aux_dif = explode(':',$diferenca_login_ligacao);
                                        $diferenca_login_ligacao = (($aux_dif[0]*60) + $aux_dif[1]);
                                                                      
                                        $diferenca_login_ligacao_total += $diferenca_login_ligacao;

                                        $str_data_hora_ligacao = $data_hora_ligacao->format('d/m/Y H:i');

                                        $cont_login_ligacao++;

                                    }else{
                                        $str_data_hora_ligacao = 'Não atendeu';
                                        $diferenca_login_ligacao = 0;
                                    }
                                    
                                    $dados_ponto_login[$cont_ponto_login]['nome'] = $dados_operador[0]['membername'];
                                    $dados_ponto_login[$cont_ponto_login]['data_ponto'] = $data_hora_batida->format('d/m/Y H:i');
                                    $dados_ponto_login[$cont_ponto_login]['data_login'] = $data_hora_login->format('d/m/Y H:i');
                                    $dados_ponto_login[$cont_ponto_login]['data_ligacao'] = $str_data_hora_ligacao;
                                    $dados_ponto_login[$cont_ponto_login]['diferenca_login_ligacao'] = $diferenca_login_ligacao;
                                    $dados_ponto_login[$cont_ponto_login]['diferenca_ponto_login'] = $diferenca_ponto_login;
                                    $cont_ponto_login++;
                                    
                                    $dados_ponto_login_medias[$conteudo_usuario['id_asterisk']]['diferenca_login_ligacao'] += $diferenca_login_ligacao;
                                    $dados_ponto_login_medias[$conteudo_usuario['id_asterisk']]['diferenca_ponto_login'] += $diferenca_ponto_login;
                                    $dados_ponto_login_medias[$conteudo_usuario['id_asterisk']]['qtd'] += 1;
                                    $dados_ponto_login_medias[$conteudo_usuario['id_asterisk']]['nome'] = $dados_operador[0]['membername'];

                                }
                                
                            }
                            $cont_batida++;
                        }
                    }                
                }
            }              
        }

        echo '<table class="table table-hover dataTableGeral" style="margin-bottom:0;">';
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Nome</th>";
                    echo "<th>Horário do ponto</th>";
                    echo "<th>Horário de login</th>";
                    echo "<th>Diferença entre ponto e login</th>";                    
                    echo "<th>Horário da primeira ligação</th>";
                    echo "<th>Diferença entre login e ligação</th>";  
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($dados_ponto_login as $conteudo_ponto_login){
                echo "<tr>";
                    echo "<td>".$conteudo_ponto_login['nome']."</td>";
                    echo "<td>".$conteudo_ponto_login['data_ponto']."</td>";
                    echo "<td>".$conteudo_ponto_login['data_login']."</td>";
                    echo "<td>".converteSegundosHoras($conteudo_ponto_login['diferenca_ponto_login']*60)."</td>";
                    echo "<td>".$conteudo_ponto_login['data_ligacao']."</td>";
                    echo "<td>".converteSegundosHoras($conteudo_ponto_login['diferenca_login_ligacao']*60)."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
                echo '<tr>';
                    echo '<th>Média</th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th>'.substr(converteSegundosHoras($diferenca_ponto_login_total*60/$cont_ponto_login),0,5).'</th>';
                    echo '<th></th>';
                    echo '<th>'.substr(converteSegundosHoras($diferenca_login_ligacao_total*60/$cont_login_ligacao),0,5).'</th>';
                echo '</tr>';
            echo "</tfoot> ";
        echo "</table>";
        echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableGeral').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
                        { type: 'date-euro', targets: 1 },
						{ type: 'date-euro', targets: 2 },
						{ type: 'time-uni', targets: 3 }
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";

        echo '<table class="table table-hover dataTableMedias" style="margin-bottom:0;">';
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Nome</th>";
                    echo "<th>Diferença média entre ponto e login</th>";   
                    echo "<th>Diferença média entre login e ligação</th>";  
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($dados_ponto_login_medias as $conteudo_ponto_login_media){
                echo "<tr>";
                    echo "<td>".$conteudo_ponto_login_media['nome']."</td>";
                    echo "<td>".converteSegundosHoras($conteudo_ponto_login_media['diferenca_ponto_login']*60/$conteudo_ponto_login_media['qtd'])."</td>";
                    echo "<td>".converteSegundosHoras($conteudo_ponto_login_media['diferenca_login_ligacao']*60/$conteudo_ponto_login_media['qtd'])."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";
        echo "
        <script>
            $(document).ready(function(){
                var table = $('.dataTableMedias').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },
                    columnDefs: [
                        { type: 'time-uni', targets: 1 },
						{ type: 'time-uni', targets: 2 }
                    ],				        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });

                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'excelHtml5', footer: true,
                            text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                            filename: 'relatorio_ponto_login',
                            title : null,
                            exportOptions: {
                                modifier: {
                                page: 'all'
                                }
                            }
                            },
                    ],	
                    dom:
                    {
                        button: {
                            tag: 'button',
                            className: 'btn btn-default'
                        },
                        buttonLiner: { tag: null }
                    }
                }).container().appendTo($('#panel_buttons'));
            });
        </script>			
        ";
    }
}

function relatorio_grafico_20min($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$data_hora = converteDataHora(getDataHora());
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Ligações por 20min - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;

	registraLog('Relatório Central - Gráfico de Ligações por 20min - Separado por Dia do Mês','rel','relatorio_grafico_20min',1,"intervalo de datas");

	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
		$atendidas = array();
        $perdidas = array();
        $total_entradas = array();
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $hora_array = 0;
		while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            
            //20min 1
                $atendidas_hora = 0;
                $perdidas_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

                if($dados_atendidas_grafico){
					foreach ($dados_atendidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $atendidas_hora = 0;
                }

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

                if($dados_perdidas_grafico){
					foreach ($dados_perdidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$perdidas_hora++;			
						}
					}
                }else{
                    $perdidas_hora = 0;
                }

                $atendidas[$hora_array] = $atendidas_hora;
                $perdidas[$hora_array] = $perdidas_hora;
                $total_entradas[$hora_array] = $atendidas_hora + $perdidas_hora;
                $horas[$hora_array] = $hora_zero.':00';
            //20min 1 fim
            
            //20min 2
                $atendidas_hora = 0;
                $perdidas_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

                if($dados_atendidas_grafico){
					foreach ($dados_atendidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $atendidas_hora = 0;
                }

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

                if($dados_perdidas_grafico){
					foreach ($dados_perdidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$perdidas_hora++;			
						}
					}
                }else{
                    $perdidas_hora = 0;
                }

                $atendidas[$hora_array+1] = $atendidas_hora;
                $perdidas[$hora_array+1] = $perdidas_hora;
                $total_entradas[$hora_array+1] = $atendidas_hora + $perdidas_hora;
                $horas[$hora_array+1] = $hora_zero.':20';
            //20min 2 fim
            
            //20min 3
                $atendidas_hora = 0;
                $perdidas_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

                if($dados_atendidas_grafico){
					foreach ($dados_atendidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $atendidas_hora = 0;
                }
                
                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

                if($dados_perdidas_grafico){
					foreach ($dados_perdidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $perdidas_hora = 0;
                }

                $atendidas[$hora_array+2] = $atendidas_hora;
                $perdidas[$hora_array+2] = $perdidas_hora;
                $total_entradas[$hora_array+2] = $atendidas_hora + $perdidas_hora;
                $horas[$hora_array+2] = $hora_zero.':40';
            //20min 3 fim

            $hora_array += 3;
			$hora++;
		}

		?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },
                    <?php 
                    if($tipo_grafico == 3){//se for pilha
                        echo "
                        colors: [
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }else{
                        echo "									
                        colors: [
                            '#0000CD',
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }
                    ?>
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	<?php 
                    	if($tipo_grafico != 3){//se não for pilha
                    		echo "
							{
	                            name: 'Entradas', // Name of your series
	                            data: ".json_encode($total_entradas, JSON_NUMERIC_CHECK)." // The data in your series
	                        },
                    		";
                    	}
                    	?>
                    	{
                            name: 'Atendidas', // Name of your series
                            data: <?php echo json_encode($atendidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Não atendidas', // Name of your series
                            data: <?php echo json_encode($perdidas, JSON_NUMERIC_CHECK) ?> // The data in your series

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
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_20min_acumulado_semana($tipo_grafico, $data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){

	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$horas = array();
	$atendidas = array();
	$perdidas = array();
	$total_entradas = array();
	$data_hora = converteDataHora(getDataHora());
	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Ligações por 20min - Acumulado por Dia da Semana</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}

	registraLog('Relatório Central - Gráfico de Ligações por 20min - Acumulado por Dia da Semana','rel','relatorio_grafico_20min_acumulado_semana',1,"intervalo de datas");
	
	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
		$numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $hora_array = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
            
            //20min 1
                $atendidas_hora = 0;
                $perdidas_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

                if($dados_atendidas_grafico){
					foreach ($dados_atendidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $atendidas_hora = 0;
                }

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

                if($dados_perdidas_grafico){
					foreach ($dados_perdidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$perdidas_hora++;			
						}
					}
                }else{
                    $perdidas_hora = 0;
                }

                $atendidas[$numero_dia_semana][$hora_array] += $atendidas_hora;
                $perdidas[$numero_dia_semana][$hora_array] += $perdidas_hora;
                $total_entradas[$numero_dia_semana][$hora_array] += $atendidas_hora + $perdidas_hora;
            //20min 1 fim
            
            //20min 2
                $atendidas_hora = 0;
                $perdidas_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

                if($dados_atendidas_grafico){
					foreach ($dados_atendidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $atendidas_hora = 0;
                }

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

                if($dados_perdidas_grafico){
					foreach ($dados_perdidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$perdidas_hora++;			
						}
					}
                }else{
                    $perdidas_hora = 0;
                }

                $atendidas[$numero_dia_semana][$hora_array+1] += $atendidas_hora;
                $perdidas[$numero_dia_semana][$hora_array+1] += $perdidas_hora;
                $total_entradas[$numero_dia_semana][$hora_array+1] += $atendidas_hora + $perdidas_hora;
            //20min 2 fim

            //20min 3
                $atendidas_hora = 0;
                $perdidas_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id");

                if($dados_atendidas_grafico){
					foreach ($dados_atendidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$atendidas_hora++;			
						}
					}
                }else{
                    $atendidas_hora = 0;
                }

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

                if($dados_perdidas_grafico){
					foreach ($dados_perdidas_grafico as $conteudo) {
						if(preg_match("/'".$conteudo['enterqueue_queuename']."'/i", $fila) || !$fila){
							$perdidas_hora++;			
						}
					}
                }else{
                    $perdidas_hora = 0;
                }

                $atendidas[$numero_dia_semana][$hora_array+2] += $atendidas_hora;
                $perdidas[$numero_dia_semana][$hora_array+2] += $perdidas_hora;
                $total_entradas[$numero_dia_semana][$hora_array+2] += $atendidas_hora + $perdidas_hora;
            //20min 3 fim

            $hora++;
            $hora_array+=3;
		}		
	}
	$cont_dia = 0;
	while($cont_dia < 7){
		$cont_hora = 0;
		$cont_hora_array = 0;
		while($cont_hora < 24){
			if(!$atendidas[$cont_dia][$cont_hora_array]){$atendidas[$cont_dia][$cont_hora_array]=0;}
			if(!$perdidas[$cont_dia][$cont_hora_array]){$perdidas[$cont_dia][$cont_hora_array]=0;}
            if(!$total_entradas[$cont_dia][$cont_hora_array]){$total_entradas[$cont_dia][$cont_hora_array]=0;}
            $horas[$cont_hora_array] = sprintf('%02d', $cont_hora).':00';

            if(!$atendidas[$cont_dia][$cont_hora_array+1]){$atendidas[$cont_dia][$cont_hora_array+1]=0;}
			if(!$perdidas[$cont_dia][$cont_hora_array+1]){$perdidas[$cont_dia][$cont_hora_array+1]=0;}
            if(!$total_entradas[$cont_dia][$cont_hora_array+1]){$total_entradas[$cont_dia][$cont_hora_array+1]=0;}
            $horas[$cont_hora_array+1] = sprintf('%02d', $cont_hora).':20';

            if(!$atendidas[$cont_dia][$cont_hora_array+2]){$atendidas[$cont_dia][$cont_hora_array+2]=0;}
			if(!$perdidas[$cont_dia][$cont_hora_array+2]){$perdidas[$cont_dia][$cont_hora_array+2]=0;}
            if(!$total_entradas[$cont_dia][$cont_hora_array+2]){$total_entradas[$cont_dia][$cont_hora_array+2]=0;}
            $horas[$cont_hora_array+2] = sprintf('%02d', $cont_hora).':40';

			$cont_hora++;
			$cont_hora_array+=3;
		}		
		$cont_dia++;
	}
	$chart = 0;
	while($chart < 7){	
	?>
		<div id="<?php echo "chart-" . $chart; ?>"></div> 
		<script>
			$(function () {
				// Create the first chart
				$('#<?php echo "chart-" . $chart; ?>').highcharts({
					chart: {
						type: '<?=$nome_tipo_grafico;?>'
					},
					title: {
						text: '<?=$dias_semana[$chart];?>' // Title for the chart
					},
					xAxis: {
						categories: <?php echo json_encode($horas) ?>
						// Categories for the charts
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },
                    <?php 
                    if($tipo_grafico == 3){//se for pilha
                        echo "
                        colors: [
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }else{
                        echo "									
                        colors: [
                            '#0000CD',
                            '#228B22',
                            '#B22222'
                        ],
                        ";
                    }
                    ?>
					plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			";
			    		}
				    	?>
				    },
					series: [	
						<?php 
                    	if($tipo_grafico != 3){//se não for pilha
                    		echo "
							{
	                            name: 'Entradas', // Name of your series
	                            data: ".json_encode($total_entradas[$chart], JSON_NUMERIC_CHECK)." // The data in your series
	                        },
                    		";
                    	}
                    	?>
                    	{
                            name: 'Atendidas', // Name of your series
                            data: <?php echo json_encode($atendidas[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Não atendidas', // Name of your series
                            data: <?php echo json_encode($perdidas[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

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
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_tempos_medios_20min($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$data_hora = converteDataHora(getDataHora());
	$tipo_grafico = 1;
	$nome_tipo_grafico = 'line';
	
	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Tempos Médios por 20min - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";

    $filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	$chart = 0;

	registraLog('Relatório Central - Gráfico de Tempos Médios por 20min - Separado por Dia do Mês','rel','relatorio_grafico_tempos_medios_20min',1,"intervalo de datas");

	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
		$tma = array();
        $tme = array();
        $tmp = array();
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $hora_array = 0;
		while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            
            //20min 1
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
                $soma_te_atendidas = 0;
                $soma_te_perdidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");
               
                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
						
						if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
							}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							}
							if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
								$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
							}
							$qtd_atendidas++;
						}
                    }
                }

                $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");
                
                if($dados_perdidas){
                    foreach ($dados_perdidas as $conteudo_perdidas) {
						if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
							$qtd_perdidas++;
						}
                    }
                }	
                
                $tma[$hora_array] = sprintf("%01.2f", round($soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
                $tme[$hora_array] = sprintf("%01.2f", round($soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
                $tmp[$hora_array] = sprintf("%01.2f", round($soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2));
                $horas[$hora_array] = $hora_zero.':00';
            //20min 1 fim

            //20min 2
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
                $soma_te_atendidas = 0;
                $soma_te_perdidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
						if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
							}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							}
							if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
								$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
							}
							$qtd_atendidas++;
						}
                    }
                }

                $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");
                
                if($dados_perdidas){
                    foreach ($dados_perdidas as $conteudo_perdidas) {
						if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
							$qtd_perdidas++;
						}
                    }
                }	
                
                $tma[$hora_array+1] = sprintf("%01.2f", round($soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
                $tme[$hora_array+1] = sprintf("%01.2f", round($soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
                $tmp[$hora_array+1] = sprintf("%01.2f", round($soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2));
                $horas[$hora_array+1] = $hora_zero.':20';
            //20min 2 fim

            //20min 3
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
                $soma_te_atendidas = 0;
                $soma_te_perdidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
						if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
							}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							}
							if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
								$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
							}
							$qtd_atendidas++;
						}
                    }
                }

                $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");
                
                if($dados_perdidas){
                    foreach ($dados_perdidas as $conteudo_perdidas) {
						if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
							$qtd_perdidas++;
						}
                    }
                }	
                
                $tma[$hora_array+2] = sprintf("%01.2f", round($soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
                $tme[$hora_array+2] = sprintf("%01.2f", round($soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2));
                $tmp[$hora_array+2] = sprintf("%01.2f", round($soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2));
                $horas[$hora_array+2] = $hora_zero.':40';
            //20min 3 fim
            
			$hora++;
			$hora_array+=3;
		}

		?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Ligações por Hora'
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
				        headerFormat: '<b>{point.x}</b><br/>',
						pointFormat: '{point.y} segundos'
				    },
				    plotOptions: {
				    	<?php 
			    		if($tipo_grafico == 3){//se for pilha
			    			echo "
							column: {
					            stacking: 'normal',
					            dataLabels: {
					                enabled: true,
					                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
					            }
					        }
			    			";
			    		}else{
			    			echo "									
					        series: {
	                            dataLabels: {
	                                enabled: true,
									formatter: function () {
										var decimalTimeString = this.y;
										var decimalTime = parseFloat(decimalTimeString);
										var hours = Math.floor((decimalTime / (60 * 60)));
										decimalTime = decimalTime - (hours * 60 * 60);
										var minutes = Math.floor((decimalTime / 60));
										decimalTime = decimalTime - (minutes * 60);
										var seconds = Math.round(decimalTime);
										if(hours < 10){
											hours = '0' + hours;
										}
										if(minutes < 10){
											minutes = '0' + minutes;
										}
										if(seconds < 10){
											seconds = '0' + seconds;
										}
										return hours + ':' + minutes + ':' + seconds;
									}
	                            }
							}
			    			";
			    		}
				    	?>
				    },
                    series: [
                    	{
                            name: 'Tempo Médio de Atendimento', // Name of your series
                            data: <?php echo json_encode($tma, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                    	{
                            name: 'Tempo Médio de Espera', // Name of your series
                            data: <?php echo json_encode($tme, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                        {
                            name: 'Tempo Médio de Perdidas', // Name of your series
                            data: <?php echo json_encode($tmp, JSON_NUMERIC_CHECK) ?> // The data in your series

                        }],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
		echo '<hr>';
		$chart++;
	}
}

function relatorio_grafico_tempos_medios_20min_acumulado_semana($data_de, $data_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$horas = array();
	$tma = array();
    $tme = array();
    $tmp = array();
    $qtd_total_atendidas = array();
    $qtd_total_perdidas = array();
	$data_hora = converteDataHora(getDataHora());
	$tipo_grafico = 1;
	$nome_tipo_grafico = 'line';
	
	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
	}
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Tempos Médios por 20min - Acumulado por Dia da Semana</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
	echo "</legend>";
    
	$filtro_atendidas = "";
	$filtro_perdidas = "";
	if($fila){
		//$filtro_atendidas .= " AND a.queuename IN ($fila)";
		//$filtro_perdidas .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
		$filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
	}
	
	registraLog('Relatório Central - Gráfico de Tempos Médios por 20min - Acumulado por Dia da Semana','rel','relatorio_grafico_tempos_medios_20min_acumulado_semana',1,"intervalo de datas");

	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		$hora_array = 0;
		while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            
            //20min 1
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
                $soma_te_atendidas = 0;
                $soma_te_perdidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");	

                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
						if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
							}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							}
							if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
								$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
							}
							$qtd_atendidas++;
						}
                    }
                }			

                $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

                if($dados_perdidas){
                    foreach ($dados_perdidas as $conteudo_perdidas) {	
						if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
							$qtd_perdidas++;
						}
                    }
                }

                $tma[$numero_dia_semana][$hora_array] += $soma_ta_atendidas;
                $tme[$numero_dia_semana][$hora_array] += $soma_te_atendidas;
                $tmp[$numero_dia_semana][$hora_array] += $soma_te_perdidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora_array] += $qtd_atendidas;
                $qtd_total_perdidas[$numero_dia_semana][$hora_array] += $qtd_perdidas;
            //20min 1 fim

            //20min 2
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
                $soma_te_atendidas = 0;
                $soma_te_perdidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");	

                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
						if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
							}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							}
							if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
								$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
							}
							$qtd_atendidas++;
						}
                    }
                }			

                $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

                if($dados_perdidas){
                    foreach ($dados_perdidas as $conteudo_perdidas) {
						if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
							$qtd_perdidas++;
						}
                    }
                }

                $tma[$numero_dia_semana][$hora_array+1] += $soma_ta_atendidas;
                $tme[$numero_dia_semana][$hora_array+1] += $soma_te_atendidas;
                $tmp[$numero_dia_semana][$hora_array+1] += $soma_te_perdidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora_array+1] += $qtd_atendidas;
                $qtd_total_perdidas[$numero_dia_semana][$hora_array+1] += $qtd_perdidas;
            //20min 2 fim

            //20min 3
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
                $soma_te_atendidas = 0;
                $soma_te_perdidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', a.queuename as 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");	

                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
						if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
							}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
								$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
								$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							}
							if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
								$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
							}
							$qtd_atendidas++;
						}
                    }
                }			

                $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') AND a.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

                if($dados_perdidas){
                    foreach ($dados_perdidas as $conteudo_perdidas) {
						if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
							$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
							$qtd_perdidas++;
						}
                    }
                }

                $tma[$numero_dia_semana][$hora_array+2] += $soma_ta_atendidas;
                $tme[$numero_dia_semana][$hora_array+2] += $soma_te_atendidas;
                $tmp[$numero_dia_semana][$hora_array+2] += $soma_te_perdidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora_array+2] += $qtd_atendidas;
                $qtd_total_perdidas[$numero_dia_semana][$hora_array+2] += $qtd_perdidas;
            //20min 3 fim

			$hora_array+=3;
			$hora++;
		}		
	}
	
	$cont_dia = 0;
	while($cont_dia < 7){
        $cont_hora = 0;
        $cont_hora_array = 0;
		while($cont_hora < 24){
			$tma[$cont_dia][$cont_hora_array] = sprintf("%01.2f", round($tma[$cont_dia][$cont_hora_array]/($qtd_total_atendidas[$cont_dia][$cont_hora_array] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array]), 2));
			$tme[$cont_dia][$cont_hora_array] = sprintf("%01.2f", round($tme[$cont_dia][$cont_hora_array]/($qtd_total_atendidas[$cont_dia][$cont_hora_array] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array]), 2));
            $tmp[$cont_dia][$cont_hora_array] = sprintf("%01.2f", round($tmp[$cont_dia][$cont_hora_array]/($qtd_total_perdidas[$cont_dia][$cont_hora_array] == 0 ? 1 : $qtd_total_perdidas[$cont_dia][$cont_hora_array]), 2));            
            $horas[$cont_hora_array] = sprintf('%02d', $cont_hora).':00';

            $tma[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", round($tma[$cont_dia][$cont_hora_array+1]/($qtd_total_atendidas[$cont_dia][$cont_hora_array+1] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+1]), 2));
			$tme[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", round($tme[$cont_dia][$cont_hora_array+1]/($qtd_total_atendidas[$cont_dia][$cont_hora_array+1] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+1]), 2));
            $tmp[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", round($tmp[$cont_dia][$cont_hora_array+1]/($qtd_total_perdidas[$cont_dia][$cont_hora_array+1] == 0 ? 1 : $qtd_total_perdidas[$cont_dia][$cont_hora_array+1]), 2));            
            $horas[$cont_hora_array+1] = sprintf('%02d', $cont_hora).':20';

            $tma[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", round($tma[$cont_dia][$cont_hora_array+2]/($qtd_total_atendidas[$cont_dia][$cont_hora_array+2] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+2]), 2));
			$tme[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", round($tme[$cont_dia][$cont_hora_array+2]/($qtd_total_atendidas[$cont_dia][$cont_hora_array+2] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+2]), 2));
            $tmp[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", round($tmp[$cont_dia][$cont_hora_array+2]/($qtd_total_perdidas[$cont_dia][$cont_hora_array+2] == 0 ? 1 : $qtd_total_perdidas[$cont_dia][$cont_hora_array+2]), 2));
            $horas[$cont_hora_array+2] = sprintf('%02d', $cont_hora).':40';
            
			$cont_hora++;
			$cont_hora_array+=3;
		}		
		$cont_dia++;
	}
	$chart = 0;
	while($chart < 7){	
	?>
		<div id="<?php echo "chart-" . $chart; ?>"></div> 
		<script>
			$(function () {
				// Create the first chart
				$('#<?php echo "chart-" . $chart; ?>').highcharts({
					chart: {
						type: '<?=$nome_tipo_grafico;?>'
					},
					title: {
						text: '<?=$dias_semana[$chart];?>' // Title for the chart
					},
					xAxis: {
						categories: <?php echo json_encode($horas) ?>
						// Categories for the charts
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Ligações por Hora'
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
						headerFormat: '<b>{point.x}</b><br/>',
						pointFormat: '{point.y} segundos'
					},
					plotOptions: {
						<?php 
						if($tipo_grafico == 3){//se for pilha
							echo "
							column: {
								stacking: 'normal',
								dataLabels: {
									enabled: true,
									color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
								}
							}
							";
						}else{
							echo "									
							series: {
								dataLabels: {
									enabled: true,
									formatter: function () {
										var decimalTimeString = this.y;
										var decimalTime = parseFloat(decimalTimeString);
										var hours = Math.floor((decimalTime / (60 * 60)));
										decimalTime = decimalTime - (hours * 60 * 60);
										var minutes = Math.floor((decimalTime / 60));
										decimalTime = decimalTime - (minutes * 60);
										var seconds = Math.round(decimalTime);
										if(hours < 10){
											hours = '0' + hours;
										}
										if(minutes < 10){
											minutes = '0' + minutes;
										}
										if(seconds < 10){
											seconds = '0' + seconds;
										}
										return hours + ':' + minutes + ':' + seconds;
									}
								}
							}
							";
						}
						?>
					},
					series: [
						{
							name: 'Tempo Médio de Atendimento', // Name of your series
							data: <?php echo json_encode($tma[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

						},
						{
							name: 'Tempo Médio de Espera', // Name of your series
							data: <?php echo json_encode($tme[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

						},
						{
							name: 'Tempo Médio de Perdidas', // Name of your series
							data: <?php echo json_encode($tmp[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

					}],
					navigation: {
						buttonOptions: {
							enabled: true
						}
					}
				});
			});
		</script>  
	<?php
		echo '<hr>';
		$chart++;
	}
}

function relatorio_licagoes_com_historico($data_de, $data_ate, $empresa, $operador, $fila){
	$data_hora = converteDataHora(getDataHora());
    $total_ligacoes_atendidas = 0;
    $total_ligacoes_perdidas = 0;
    $tempo_total_historico_ligacoes_atendidas = 0;
    $tempo_total_historico_ligacoes_perdidas = 0;
    $total_ligacoes_perdidas_com_historico = 0;
    $totais_empresas = array();
    $totais_operadores = array();
    $totais_lideres = array();

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
	if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
		}
	}else{
		$atendente_legenda = 'Todos';
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ligações com Histórico</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Empresa - </strong>".$empresa_legenda.", <strong>Atendente - </strong>".$atendente_legenda;
	echo "</legend>";

    $filtro = '';
    if($data_de){
		$filtro .= " AND a.time >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro .= " AND a.time <= '".converteData($data_ate)." 23:59:59'";
	}
	if($fila){
		//$filtro .= " AND a.queuename IN ($fila)";
	}
	if($empresa){
		$filtro .= " AND a.data2 LIKE '$empresa%'";
    }
    
	if($operador){
		$filtro_operador = " AND a.agent = '$operador'";
    }
    
    $dados_ligacoes = DBRead('snep','queue_log a',"WHERE a.event = 'ENTERQUEUE' $filtro GROUP BY a.id ORDER BY a.id", "a.*, a.queuename AS 'enterqueue_queuename'");
    
    if($dados_ligacoes){        

		$link = DBConnect('snep');
		DBBegin($link);

		registraLog('Relatório Central - Relatório de Ligações com Histórico','rel','relatorio_licagoes_com_historico',1,"WHERE a.event = 'ENTERQUEUE' $filtro GROUP BY a.id ORDER BY a.id", "a.*, a.queuename AS 'enterqueue_queuename'");

        foreach($dados_ligacoes as $conteudo_ligacoes){

			if(preg_match("/'".$conteudo_ligacoes['enterqueue_queuename']."'/i", $fila) || !$fila){
			
				$data2 = explode('-', $conteudo_ligacoes['data2']);
				$id_empresa_entrada = $data2[0];
				$dados_empresa = DBReadTransaction($link,'empresas',"WHERE id='$id_empresa_entrada'");
				if($dados_empresa){
					$nome_empresa = $dados_empresa[0]['nome'];
				}else{
					$nome_empresa = 'Não identificada';
				}
				if(is_numeric(end($data2)) && strlen(end($data2)) >= 10){
					$bina = ltrim(end($data2), '0');
					if(substr($bina,0,3) == '+55'){
						$bina = substr($bina,3,11);
					}
				}elseif(end($data2) == 'anonymous'){
					$bina = 'Nº anônimo';
				}else{
					$bina = 'Não identificado';
				}
				
				$dados_historico_ring = DBReadTransaction($link,'queue_log a',"WHERE a.event = 'RINGNOANSWER' AND a.callid = '".$conteudo_ligacoes['callid']."' $filtro_operador ORDER BY a.id");

				$dados_historico_fim = DBReadTransaction($link,'queue_log a',"WHERE (a.event = 'CONNECT' OR a.event = 'ABANDON' OR a.event = 'EXITWITHTIMEOUT') AND a.callid = '".$conteudo_ligacoes['callid']."' ORDER BY a.id DESC");
				
				if($dados_historico_fim && ((!$operador) || ($operador &&($dados_historico_ring ||  $dados_historico_fim[0]['agent'] == $operador)) )){
					echo "
					<div class='panel panel-default'>
						<div class='panel-heading'>
							<div class='row'>
								<div class='col-md-4'>
									<strong>Empresa: </strong>$nome_empresa
								</div>
								<div class='col-md-4 text-center'>
									<strong>Data: </strong>".date('d/m/Y H:i:s', strtotime($conteudo_ligacoes['time']))."
								</div>
								<div class='col-md-4 text-right'>
									<strong>Número: </strong>$bina 
								</div>
							</div>
						</div>
						<div class='panel-body'>
					";

					$tempo_total_historico_ring = 0;
					
					if($dados_historico_ring){
						echo "
							<table class=\"table table-hover dataTable\">
								<thead> 
									<tr>
										<th class='col-md-8'>Chamou para</th>                                
										<th class='col-md-3'>Data</th>                                
										<th class='col-md-1'>Tempo</th>                                
									</tr>
								</thead> 
								<tbody>
								
						";
						foreach($dados_historico_ring as $conteudo_historico_ring){
							$operador_historico_ring = explode('/', $conteudo_historico_ring['agent']);
							$tipo_operador_historico_ring = $operador_historico_ring[0];
							$cod_operador_historico_ring = $operador_historico_ring[1];
							if($tipo_operador_historico_ring == 'AGENT'){
								$dados_operador = DBReadTransaction($link,'queue_agents',"WHERE codigo='$cod_operador_historico_ring'");
								if($dados_operador){
									$nome_operador_historico_ring = $dados_operador[0]['membername'];
								}else{
									$nome_operador_historico_ring = $conteudo_historico_ring['agent'];
								}							
							}elseif($tipo_operador_historico_ring == 'SIP'){
								$dados_operador = DBReadTransaction($link,'peers',"WHERE name='$cod_operador_historico_ring'");
								if($dados_operador){
									$nome_operador_historico_ring = $dados_operador[0]['callerid'];
								}else{
									$nome_operador_historico_ring = $conteudo_historico_ring['agent'];
								}	
							}else{
								$nome_operador_historico_ring = $conteudo_historico_ring['agent'];
							}                    
							echo "
								<tr>
									<td>$nome_operador_historico_ring</td>
									<td>".date('d/m/Y H:i:s', strtotime($conteudo_historico_ring['time']))."</td>
									<td>".gmdate("H:i:s", $conteudo_historico_ring['data1']/1000)."</td>
								</tr>
							";
							$tempo_total_historico_ring += $conteudo_historico_ring['data1'];
							if(!$operador ||($operador && $operador == $conteudo_historico_ring['agent'])){
								$totais_operadores[$conteudo_historico_ring['agent']]['qtd_historico'] += 1;
								$totais_operadores[$conteudo_historico_ring['agent']]['tempo_total'] += $conteudo_historico_ring['data1'];
								$totais_empresas[$nome_empresa]['qtd_historico'] += 1;
								$totais_empresas[$nome_empresa]['tempo_total'] += $conteudo_historico_ring['data1'];
					
								if(($dados_historico_fim[0]['event'] == 'ABANDON' || $dados_historico_fim[0]['event'] == 'EXITWITHTIMEOUT') && !in_array($conteudo_ligacoes['callid'],$totais_operadores[$conteudo_historico_ring['agent']]['perdidas'])){
									$totais_operadores[$conteudo_historico_ring['agent']]['perdidas'][] = $conteudo_ligacoes['callid'];
								}
							}                        
						}
						echo "                            
								</tbody>
								<tfoot>
									<tr>
										<th>Total</th>
										<th></th>
										<th>".gmdate("H:i:s", $tempo_total_historico_ring/1000)."</th>
									</tr>
								</tfoot>
							</table>
						";
					}else{
						echo '<div class="alert alert-info text-center">Sem histórico</div>';
					}  
				

					$fim = '';
					$data_fim = '';
					$tempo_espera_fim = '';
					if($dados_historico_fim[0]['event'] == 'ABANDON' || $dados_historico_fim[0]['event'] == 'EXITWITHTIMEOUT'){
						$total_ligacoes_perdidas++;
						$tempo_total_historico_ligacoes_perdidas += $tempo_total_historico_ring;
						$fim = '<strong style="color:red;">Perdida!</strong>';
						$data_fim = '<strong>Data: </strong>'.date('d/m/Y H:i:s', strtotime($dados_historico_fim[0]['time']));
						$tempo_espera_fim = '<strong>T. Espera: </strong>'.gmdate("H:i:s", $dados_historico_fim[0]['data3']);
						if($dados_historico_ring && !in_array($conteudo_ligacoes['callid'],$totais_empresas[$nome_empresa]['perdidas'])){
							$totais_empresas[$nome_empresa]['perdidas'][] = $conteudo_ligacoes['callid'];
							$total_ligacoes_perdidas_com_historico++;
						}
					}else{
						$total_ligacoes_atendidas++;
						$tempo_total_historico_ligacoes_atendidas += $tempo_total_historico_ring;
						$operador_historico_fim = explode('/', $dados_historico_fim[0]['agent']);
						$tipo_operador_historico_fim = $operador_historico_fim[0];
						$cod_operador_historico_fim = $operador_historico_fim[1];
						if($tipo_operador_historico_fim == 'AGENT'){
							$dados_operador = DBReadTransaction($link,'queue_agents',"WHERE codigo='$cod_operador_historico_fim'");
							if($dados_operador){
								$nome_operador_historico_fim = $dados_operador[0]['membername'];
							}else{
								$nome_operador_historico_fim = $dados_historico_fim[0]['agent'];
							}							
						}elseif($tipo_operador_historico_fim == 'SIP'){
							$dados_operador = DBReadTransaction($link,'peers',"WHERE name='$cod_operador_historico_fim'");
							if($dados_operador){
								$nome_operador_historico_fim = $dados_operador[0]['callerid'];
							}else{
								$nome_operador_historico_fim = $dados_historico_fim[0]['agent'];
							}	
						}else{
							$nome_operador_historico_fim = $dados_historico_fim[0]['agent'];
						}
						if(!$operador ||($operador && $operador == $dados_historico_fim[0]['agent'])){
							$totais_operadores[$dados_historico_fim[0]['agent']]['qtd_atendidas'] += 1;
							$totais_operadores[$dados_historico_fim[0]['agent']]['qtd_historico'] += 1;
							$totais_empresas[$nome_empresa]['qtd_atendidas'] += 1;
							$totais_empresas[$nome_empresa]['qtd_historico'] += 1;
						}
						$fim = '<strong>Atendida por: </strong>'.$nome_operador_historico_fim;
						$data_fim = '<strong>Data: </strong>'.date('d/m/Y H:i:s', strtotime($dados_historico_fim[0]['time']));
						$tempo_espera_fim = '<strong>T. Espera: </strong>'.gmdate("H:i:s", $dados_historico_fim[0]['data1']);
					}      
					
					echo "
						</div>
						<div class='panel-footer'>
							<div class='row'>
								<div class='col-md-4'>
									".$fim."
								</div>
								<div class='col-md-4 text-center'>
									".$data_fim."
								</div>
								<div class='col-md-4 text-right'>
									".$tempo_espera_fim."
								</div>
							</div>
						</div>
					</div>
					";
				}
			}
        }

        echo "<div class='text-center'><strong>Total de ligações atendidas: </strong>".($total_ligacoes_atendidas)." - <strong>Tempo total dos históricos: </strong>".gmdate("H:i:s", $tempo_total_historico_ligacoes_atendidas/1000)."</div>";
        echo "<div class='text-center'><strong>Total de ligações perdidas: </strong>".($total_ligacoes_perdidas)." - <strong>Tempo total dos históricos: </strong>".gmdate("H:i:s", $tempo_total_historico_ligacoes_perdidas/1000)."</div>";
        echo "<div class='text-center'><strong>Total de ligações perdidas com histórico: </strong>".$total_ligacoes_perdidas_com_historico."</div>";
        
        echo "<hr>";

        $qtd_atendidas_total = 0;
        $qtd_historico_total = 0;
        $qtd_perdidas_total = 0;
        $tempo_total = 0;
        echo "
            <table class=\"table table-hover dataTableOperadores\"> 
                <thead> 
                    <tr> 
                        <th class='col-md-2'>Atendente</th>
                        <th class='col-md-2'>Lider Direto</th>
                        <th class='col-md-1'>QTD Atendidas</th>
                        <th class='col-md-1'>QTD Toques</th>
                        <th class='col-md-2'>Tempo</th>
                        <th class='col-md-2'>Toques/Atendidas</th>
                        <th class='col-md-2'>Perdidas com Histórico</th>
                    </tr>
                </thead> 
                <tbody>"
        ;
        foreach ($totais_operadores as $agent => $conteudo) {
            $operador_historico_fim = explode('/', $agent);
            $tipo_operador_historico_fim = $operador_historico_fim[0];
            $cod_operador_historico_fim = $operador_historico_fim[1];
            if($tipo_operador_historico_fim == 'AGENT'){
                $dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa WHERE a.id_asterisk = '$cod_operador_historico_fim'");
                if($dados_lider){
                    $nome_lider = $dados_lider[0]['nome'];
                }else{
                    $nome_lider = '-';
                }
                $dados_operador = DBReadTransaction($link,'queue_agents',"WHERE codigo='$cod_operador_historico_fim'");
                if($dados_operador){
                    $nome_operador = $dados_operador[0]['membername'];
                }else{
                    $nome_operador = $agent;
                }	
            }elseif($tipo_operador_historico_fim == 'SIP'){
                $dados_operador = DBReadTransaction($link,'peers',"WHERE name='$cod_operador_historico_fim'");
                if($dados_operador){
                    $nome_operador = $dados_operador[0]['callerid'];
                }else{
                    $nome_operador = $agent;
                }	
                $nome_lider = '-';
            }else{
                $nome_operador = $agent;
                $nome_lider = '-';
            }
            $conteudo_qtd_atendidas = $conteudo['qtd_atendidas'] ? $conteudo['qtd_atendidas'] : 0;
            $conteudo_qtd_historico = $conteudo['qtd_historico'] ? $conteudo['qtd_historico'] : 0;            
            $conteudo_qtd_perdidas = $conteudo['perdidas'] ? sizeof($conteudo['perdidas']) : 0;   
            echo '<tr>';
            echo '<td>'.$nome_operador.'</td>';
            echo '<td>'.$nome_lider.'</td>';
            echo '<td>'.$conteudo_qtd_atendidas.'</td>';
            echo '<td>'.$conteudo_qtd_historico.'</td>';
            echo '<td>'.gmdate("H:i:s", $conteudo['tempo_total']/1000).'</td>';
            echo '<td>'.sprintf("%01.2f", round($conteudo_qtd_historico/($conteudo_qtd_atendidas ? $conteudo_qtd_atendidas : 1), 2)).'</td>';            
            echo '<td>'.$conteudo_qtd_perdidas.'</td>';
            echo '</tr>';
            $qtd_historico_total += $conteudo_qtd_historico;
            $qtd_atendidas_total += $conteudo_qtd_atendidas;
            $qtd_perdidas_total += $conteudo_qtd_perdidas;
            $tempo_total += $conteudo['tempo_total'];

            $totais_lideres[$nome_lider]['qtd_atendidas'] += $conteudo_qtd_atendidas;
            $totais_lideres[$nome_lider]['qtd_historico'] += $conteudo_qtd_historico;
            $totais_lideres[$nome_lider]['qtd_perdidas'] += $conteudo_qtd_perdidas;
            $totais_lideres[$nome_lider]['tempo_total'] += $conteudo['tempo_total'];
        }
        echo "
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th>$qtd_atendidas_total</th>
                        <th>$qtd_historico_total</th>
                        <th>".gmdate("H:i:s", $tempo_total/1000)."</th>
                        <th>".sprintf("%01.2f", round($qtd_historico_total/($qtd_atendidas_total ? $qtd_atendidas_total : 1), 2))."</th>
                        <th>$qtd_perdidas_total</th>
                    </tr>
                </tfoot>
            </table>
        ";
        echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableOperadores').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 4 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";	
        
        if(!$operador){
            echo "<hr>";

            $qtd_atendidas_total = 0;
            $qtd_historico_total = 0;
            $qtd_perdidas_total = 0;
            $tempo_total = 0;
            echo "
                <table class=\"table table-hover dataTableLideres\"> 
                    <thead> 
                        <tr> 
                            <th class='col-md-4'>Líder Direto</th>
                            <th class='col-md-1'>QTD Atendidas</th>
                            <th class='col-md-1'>QTD Toques</th>
                            <th class='col-md-2'>Tempo</th>
                            <th class='col-md-2'>Toques/Atendidas</th>
                            <th class='col-md-2'>Perdidas com Histórico</th>
                        </tr>
                    </thead> 
                    <tbody>"
            ;
            foreach ($totais_lideres as $nome_lider => $conteudo) {
                $conteudo_qtd_atendidas = $conteudo['qtd_atendidas'] ? $conteudo['qtd_atendidas'] : 0;
                $conteudo_qtd_historico = $conteudo['qtd_historico'] ? $conteudo['qtd_historico'] : 0;
                $conteudo_qtd_perdidas = $conteudo['qtd_perdidas'] ? $conteudo['qtd_perdidas'] : 0;
                echo '<tr>';
                echo '<td>'.$nome_lider.'</td>';
                echo '<td>'.$conteudo_qtd_atendidas.'</td>';
                echo '<td>'.$conteudo_qtd_historico.'</td>';
                echo '<td>'.gmdate("H:i:s", $conteudo['tempo_total']/1000).'</td>';
                echo '<td>'.sprintf("%01.2f", round($conteudo_qtd_historico/($conteudo_qtd_atendidas ? $conteudo_qtd_atendidas : 1), 2)).'</td>';
                echo '<td>'.$conteudo_qtd_perdidas.'</td>';
                echo '</tr>';
                $qtd_historico_total += $conteudo_qtd_historico;
                $qtd_atendidas_total += $conteudo_qtd_atendidas;
                $qtd_perdidas_total += $conteudo_qtd_perdidas;
                $tempo_total += $conteudo['tempo_total'];
            }
            echo "
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th>$qtd_atendidas_total</th>
                            <th>$qtd_historico_total</th>
                            <th>".gmdate("H:i:s", $tempo_total/1000)."</th>
                            <th>".sprintf("%01.2f", round($qtd_historico_total/($qtd_atendidas_total ? $qtd_atendidas_total : 1), 2))."</th>
                            <th>$qtd_perdidas_total</th>
                        </tr>
                    </tfoot>
                </table>
            ";
            echo "
            <script>
                $(document).ready(function(){
                    $('.dataTableLideres').DataTable({
                        \"language\": {
                            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                        },
                        columnDefs: [
                            { type: 'time-uni', targets: 3 },
                        ],
                        \"searching\": false,
                        \"paging\":   false,
                        \"info\":     false
                    });
                });
            </script>			
            ";	
        }
        
        echo "<hr>";

        $qtd_atendidas_total = 0;
        $qtd_historico_total = 0;
        $qtd_perdidas_total = 0;
        $tempo_total = 0;
        echo "
            <table class=\"table table-hover dataTableEmpresas\"> 
                <thead> 
                    <tr> 
                        <th class='col-md-4'>Empresa</th>
                        <th class='col-md-1'>QTD Atendidas</th>
                        <th class='col-md-1'>QTD Toques</th>
                        <th class='col-md-2'>Tempo</th>
                        <th class='col-md-2'>Toques/Atendidas</th>                        
                        <th class='col-md-2'>Perdidas com Histórico</th>
                    </tr>
                </thead> 
                <tbody>"
        ;
        foreach ($totais_empresas as $nome_empresa => $conteudo) {
            $conteudo_qtd_atendidas = $conteudo['qtd_atendidas'] ? $conteudo['qtd_atendidas'] : 0;
            $conteudo_qtd_historico = $conteudo['qtd_historico'] ? $conteudo['qtd_historico'] : 0;           
            $conteudo_qtd_perdidas = $conteudo['perdidas'] ? sizeof($conteudo['perdidas']) : 0;    
            echo '<tr>';
            echo '<td>'.$nome_empresa.'</td>';
            echo '<td>'.$conteudo_qtd_atendidas.'</td>';
            echo '<td>'.$conteudo_qtd_historico.'</td>';
            echo '<td>'.gmdate("H:i:s", $conteudo['tempo_total']/1000).'</td>';
            echo '<td>'.sprintf("%01.2f", round($conteudo_qtd_historico/($conteudo_qtd_atendidas ? $conteudo_qtd_atendidas : 1), 2)).'</td>';
            echo '<td>'.$conteudo_qtd_perdidas.'</td>';
            echo '</tr>';
            $qtd_historico_total += $conteudo_qtd_historico;
            $qtd_atendidas_total += $conteudo_qtd_atendidas;
            $qtd_perdidas_total += $conteudo_qtd_perdidas;
            $tempo_total += $conteudo['tempo_total'];
        }
        echo "
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>$qtd_atendidas_total</th>
                        <th>$qtd_historico_total</th>
                        <th>".gmdate("H:i:s", $tempo_total/1000)."</th>
                        <th>".sprintf("%01.2f", round($qtd_historico_total/($qtd_atendidas_total ? $qtd_atendidas_total : 1), 2))."</th>
                        <th>$qtd_perdidas_total</th>
                    </tr>
                </tfoot>
            </table>
        ";
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableEmpresas').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 3 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";	

		DBCommit($link);

    }else{
        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_historico_login_logoff($data_de, $data_ate, $operador, $pa){
    $data_hora = converteDataHora(getDataHora());

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
        }
	}else{
		$atendente_legenda = 'Todos';
    }	

    if($pa){
        $pa_legenda = ", <strong>PA - </strong>".substr($pa,1,3);
    }else{
        $pa_legenda = ', <strong>PA - </strong> Todas';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Histórico de Login e Logoff</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$atendente_legenda."".$pa_legenda;
    echo "</legend>";

	$filtro = "";
    if($data_de){
		$filtro .= " AND (a.data_login >= '".converteData($data_de)." 00:00:00' OR a.data_logoff >= '".converteData($data_de)." 00:00:00')";
	}
	if($data_ate){
		$filtro .= " AND (a.data_login <= '".converteData($data_ate)." 23:59:59' OR a.data_logoff <= '".converteData($data_ate)." 23:59:59')";
	}
	if($operador){
        $agent = explode('/',$operador);
		$filtro .= " AND a.codigo = '".$agent[1]."'";
	}
	if($pa){
		$filtro .= " AND a.interface_logged = 'SIP/$pa'";
    }

    $dados_historico = DBRead('snep', 'queue_agents_log a', "INNER JOIN queue_agents b ON a.codigo = b.codigo WHERE a.uniqueid $filtro ORDER BY data_login ASC","a.*, b.membername");

    if($dados_historico){
        echo "
			<table class=\"table table-hover dataTable\"> 
				<thead> 
                    <tr> 
                        <th>Data Login</th>
    					<th>Data Logoff</th>
    					<th>Tempo da sessão</th>
    					<th>Atendente</th>
    					<th>PA</th>    					
					</tr>
				</thead> 
				<tbody>"
        ;
        $total_tempo_sessao = 0;

		registraLog('Relatório Central - Relatório de Histórico de Login e Logoff','rel','relatorio_historico_login_logoff',1,"INNER JOIN queue_agents b ON a.codigo = b.codigo WHERE a.uniqueid $filtro ORDER BY data_login ASC");

        foreach($dados_historico as $conteudo_historico){
            
            $atendente = $conteudo_historico['membername'];
            $data_login = new DateTime(date('Y-m-d H:i:s', strtotime($conteudo_historico['data_login'])));
            $data_logoff = new DateTime(date('Y-m-d H:i:s', strtotime($conteudo_historico['data_logoff'])));
            $tempo_sessao = $data_login->diff($data_logoff);

            $pa = explode('/',$conteudo_historico['interface_logged']);
            $pa = substr($pa[1], 1,3);

            $td_data_login = $data_login->format('d/m/Y H:i:s');

            if($conteudo_historico['data_logoff']){
                $td_data_logoff = $data_logoff->format('d/m/Y H:i:s');
                $td_tempo_sessao = $tempo_sessao->format('%H:%I:%S');

                $total_tempo_sessao += ($tempo_sessao->format('%h')*60*60) + ($tempo_sessao->format('%i')*60) + ($tempo_sessao->format('%s'));
            }else{
                $td_data_logoff = 'Sessão aberta';
                $td_tempo_sessao = '00:00:00';
            }

            echo "
                <tr>
                    <td>".$td_data_login."</td>
                    <td>".$td_data_logoff."</td>
                    <td>".$td_tempo_sessao."</td>
                    <td>".$atendente."</td>
                    <td>".$pa."</td>
                </tr>
            ";
        }
        echo "
                </tbody> 
                <tfoot>
                    <tr>
                        <th>Totais</th>
                        <th></th>
                        <th>".converteSegundosHoras($total_tempo_sessao)."</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        ";
        echo "
        <script>
            $(document).ready(function(){
                var table = $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },
                    columnDefs: [
                        { type: 'date-euro', targets: 0 },
				    	{ type: 'date-euro', targets: 1 },
                    ],				        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });

                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'excelHtml5', footer: true,
                            text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                            filename: 'relatorio_historico_login_logoff',
                            title : null,
                            exportOptions: {
                                modifier: {
                                page: 'all'
                                }
                            }
                            },
                    ],	
                    dom:
                    {
                        button: {
                            tag: 'button',
                            className: 'btn btn-default'
                        },
                        buttonLiner: { tag: null }
                    }
                }).container().appendTo($('#panel_buttons'));
            });
        </script>			
        ";
    }else{
        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }
    echo "</div>";
}

function relatorio_ultima_ligacao_empresa(){
    $data_hora = converteDataHora(getDataHora());
	
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";
    echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Última Ligação por Empresa</strong><br>$gerado</legend>";
    
    $dados_empresas = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE d.id_asterisk AND c.cod_servico = 'call_suporte' AND a.status = 1 ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.nome_contrato, a.qtd_contratada, b.nome AS 'nome_empresa', c.nome AS 'nome_plano', d.id_asterisk");

	if($dados_empresas){
		echo '
			<table class="table table-hover dataTable"> 
				<thead> 
					<tr> 
						<th>Empresa</th>
						<th>Última Ligação</th>
					</tr>
				</thead>
				<tbody>
		';

		registraLog('Relatório Central - Relatório de Última Ligação por Empresa','rel','relatorio_ultima_ligacao_empresa',1,"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE d.id_asterisk AND c.cod_servico = 'call_suporte' AND a.status = 1 ORDER BY b.nome ASC");

		foreach ($dados_empresas as $conteudo_empresa) {
			
			$filtro = " AND a.data2 LIKE '".$conteudo_empresa['id_asterisk']."%'";			

			$dados_ultima_chamada = DBRead('snep','queue_log a',"WHERE a.event = 'ENTERQUEUE' $filtro ORDER BY a.id DESC LIMIT 1", "a.time");

			if($dados_ultima_chamada){
				$data_ultima_chamada = date('d/m/Y H:i:s', strtotime($dados_ultima_chamada[0]['time']));
			}else{
				$data_ultima_chamada = '';
			}

			if($conteudo_empresa['nome_contrato']){
				$nome_contrato = " (".$conteudo_empresa['nome_contrato'].")";
			}else{
				$nome_contrato = '';
			}

			echo '
				<tr>
					<td class="">'.$conteudo_empresa['nome_empresa'].$nome_contrato.'</td>
					<td>'.$data_ultima_chamada.'</td>
				</tr>
			';
						
		}
		echo '		
				</tbody> 
			</table>
		';
		echo "
			<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
							{ type: 'date-euro', targets: 1 },
						],
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});
					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_sintetico_ligacoes_empresa',
           						title : null,
								exportOptions: {
								  modifier: {
									page: 'all'
								  }
								}
							},
						],	
						dom:
						{
							button: {
								tag: 'button',
								className: 'btn btn-default'
							},
							buttonLiner: { tag: null }
						}
				   }).container().appendTo($('#panel_buttons'));
				});
			</script>			
		";
	}
}

function relatorio_notas($data_de, $data_ate, $empresa, $operador, $segundos_atendimento, $plano){

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

    $data_hora = converteDataHora(getDataHora());	
    
	if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
        $empresa_legenda = 'Todas';
    }
    if($operador){
		$codigo_operador = explode('/', $operador);
		$codigo_operador = $codigo_operador[1];
		$dados_operador = DBRead('snep','queue_agents',"WHERE codigo='$codigo_operador'");
		if($dados_operador){
			$atendente_legenda = $dados_operador[0]['membername'];
		}else{
			$atendente_legenda = 'Não identificado';
		}
	}else{
		$atendente_legenda = 'Todos';
	}
	if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}	
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
    }
    
    if($plano){
        $dados_plano = DBRead('','tb_plano', "WHERE id_plano = '$plano'");
        $legenda_plano = ', <strong>Plano - </strong>'.$dados_plano[0]['nome'];
    }elseif(!$empresa){
        $legenda_plano = ', <strong>Plano - </strong>Todos';
    }else{
        $legenda_plano = '';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Notas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_plano.", <strong>Atendente - </strong>".$atendente_legenda."".$legenda_descarte_segundos_atendimento;
	echo "</legend>";

	$filtro_atendidas = "";
	if($data_de){
		$filtro_atendidas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){		
		$filtro_atendidas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
	}
	
    // $filtro_atendidas .= " AND a.queuename IN ('callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT', 'callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP')";
	
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
    }
    if($operador){
		$filtro_atendidas .= " AND b.agent = '$operador'";
	}
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
	}
    if($plano){
        $filtro_plano = ' AND (';
        $dados_empresas_plano = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_parametros b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE b.id_asterisk AND a.id_plano = '$plano'",'b.*');
        if($dados_empresas_plano){
            foreach($dados_empresas_plano as $conteudo_empresas_plano){
                $filtro_plano .= "a.data2 LIKE '".$conteudo_empresas_plano['id_asterisk']."%' OR ";
            }
        }
        $filtro_plano = substr($filtro_plano, 0, -4).')';
        $filtro_atendidas .= $filtro_plano;
    }

    $array_dados_empresas = array();
    $dados_empresas = DBRead('snep','empresas');
    if($dados_empresas){
        foreach($dados_empresas as $conteudo_empresa){
            $array_dados_empresas[$conteudo_empresa['id']] = $conteudo_empresa['nome'];
        }
    }

    $array_dados_operadores = array();
    $dados_operadores = DBRead('snep','queue_agents');;
    if($dados_operadores){
        foreach($dados_operadores as $conteudo_operador){
            $array_dados_operadores[$conteudo_operador['codigo']] = $conteudo_operador['membername'];
        }
    }

    $array_notas_empresas = array();
    $array_notas_operadores = array();
    
    $qtd_atendidas_geral = 0;
    $soma_notas_geral = 0;
	$qtd_notas_geral = 0;

	$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', d.nota AS 'nota'");

	registraLog('Relatório Central - Relatório de Notas','rel','relatorio_notas',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY b.id ORDER BY b.id");

	if($dados_atendidas){
		foreach ($dados_atendidas as $conteudo_atendidas) {
			if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){

				$enterqueue_data2 = explode('-', $conteudo_atendidas['enterqueue_data2']);
				$id_empresa_entrada = $enterqueue_data2[0];
				if($array_dados_empresas[$id_empresa_entrada]){
					$nome_empresa = $array_dados_empresas[$id_empresa_entrada];
				}else{
					$nome_empresa = 'Não identificada';
				}
				$operador_chamada = explode('/', $conteudo_atendidas['connect_agent']);
				$tipo_operador_chamada = $operador_chamada[0];
				$cod_operador_chamada = $operador_chamada[1];
				if($tipo_operador_chamada == 'AGENT' && $array_dados_operadores[$cod_operador_chamada]){
					$dados_operador = $array_dados_operadores[$cod_operador_chamada];							
				}else{
					$nome_operador = $conteudo_atendidas['connect_agent'];
				} 
				
				if($conteudo_atendidas['nota']){
					$array_notas_empresas[$id_empresa_entrada][$conteudo_atendidas['nota']] += 1;
					$array_notas_empresas[$id_empresa_entrada]['qtd_notas'] += 1;
					$array_notas_empresas[$id_empresa_entrada]['soma_notas'] += $conteudo_atendidas['nota'];
					$array_notas_operadores[$cod_operador_chamada][$conteudo_atendidas['nota']] += 1;
					$array_notas_operadores[$cod_operador_chamada]['qtd_notas'] += 1;
					$array_notas_operadores[$cod_operador_chamada]['soma_notas'] += $conteudo_atendidas['nota'];
					$soma_notas_geral += $conteudo_atendidas['nota'];
					$qtd_notas_geral++;	
				}else{
					$array_notas_empresas[$id_empresa_entrada]['sn'] += 1;
					$array_notas_operadores[$cod_operador_chamada]['sn'] += 1;
				}
				$array_notas_empresas[$id_empresa_entrada]['qtd_atendidas'] += 1;
				$array_notas_operadores[$cod_operador_chamada]['qtd_atendidas'] += 1;
				$qtd_atendidas_geral ++;
			}
		}
    }
    
    if($array_notas_empresas){
        $qtd_nota_sn_geral = 0;
        $qtd_nota_1_geral = 0;
        $qtd_nota_2_geral = 0;
        $qtd_nota_3_geral = 0;
        $qtd_nota_4_geral = 0;
        $qtd_nota_5_geral = 0;
        echo "
			<table class=\"table table-hover dataTableEmpresas\"> 
				<thead> 
					<tr> 
    					<th>Empresa</th>
    					<th>Sem nota</th>
    					<th>Nota 1</th>
    					<th>Nota 2</th>
    					<th>Nota 3</th>
    					<th>Nota 4</th>
    					<th>Nota 5</th>
    					<th>QTD LN</th>
    					<th>% LN</th>
    					<th>Nota Média</th>
					</tr>
				</thead> 
				<tbody>"
        ;
        foreach ($array_notas_empresas as $id => $conteudo) {
            $nome = $array_dados_empresas[$id] ? $array_dados_empresas[$id] : $id;
            $qtd_nota_sn = $conteudo['sn'] ? $conteudo['sn'] : 0;
            $qtd_nota_1 = $conteudo['1'] ? $conteudo['1'] : 0;
            $qtd_nota_2 = $conteudo['2'] ? $conteudo['2'] : 0;
            $qtd_nota_3 = $conteudo['3'] ? $conteudo['3'] : 0;
            $qtd_nota_4 = $conteudo['4'] ? $conteudo['4'] : 0;
            $qtd_nota_5 = $conteudo['5'] ? $conteudo['5'] : 0;
            $qtd_notas = $conteudo['qtd_notas'] ? $conteudo['qtd_notas'] : 0;
            $soma_notas = $conteudo['soma_notas'] ? $conteudo['soma_notas'] : 0;
            $qtd_atendidas = $conteudo['qtd_atendidas'] ? $conteudo['qtd_atendidas'] : 0;    

            echo '<tr>';
            echo '<td>'.$nome.'</td>';
            echo '<td>'.$qtd_nota_sn.'</td>';
            echo '<td>'.$qtd_nota_1.'</td>';
            echo '<td>'.$qtd_nota_2.'</td>';	
            echo '<td>'.$qtd_nota_3.'</td>';	
            echo '<td>'.$qtd_nota_4.'</td>';	
            echo '<td>'.$qtd_nota_5.'</td>';
            echo '<td>'.$qtd_notas.'</td>';
            echo '<td>'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).' %</td>';
            echo '<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>';
            echo '</tr>';

            $qtd_nota_sn_geral += $qtd_nota_sn;
            $qtd_nota_1_geral += $qtd_nota_1;
            $qtd_nota_2_geral += $qtd_nota_2;
            $qtd_nota_3_geral += $qtd_nota_3;
            $qtd_nota_4_geral += $qtd_nota_4;
            $qtd_nota_5_geral += $qtd_nota_5;
        }
        echo "
			</tbody> 
			<tfoot>
		";
		echo '<tr>';
		echo '<th>Totais</th>';
		echo '<th>'.$qtd_nota_sn_geral.'</th>';
		echo '<th>'.$qtd_nota_1_geral.'</th>';
		echo '<th>'.$qtd_nota_2_geral.'</th>';
		echo '<th>'.$qtd_nota_3_geral.'</th>';
		echo '<th>'.$qtd_nota_4_geral.'</th>';
		echo '<th>'.$qtd_nota_5_geral.'</th>';
		echo '<th>'.$qtd_notas_geral.'</th>';
		echo '<th>'.sprintf("%01.2f", round($qtd_notas_geral*100/($qtd_atendidas_geral == 0 ? 1 : $qtd_atendidas_geral), 2)).' %</th>';
		echo '<th>'.sprintf("%01.2f", round($soma_notas_geral/($qtd_notas_geral == 0 ? 1 : $qtd_notas_geral), 2)).'</th>';
		echo '</tr>';
		echo "
				</tfoot> 
			</table>
		";
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableEmpresas').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'chinese-string', targets: 0 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
        ";
        echo '<hr>';
    }

    if($array_notas_operadores){
        $qtd_nota_sn_geral = 0;
        $qtd_nota_1_geral = 0;
        $qtd_nota_2_geral = 0;
        $qtd_nota_3_geral = 0;
        $qtd_nota_4_geral = 0;
        $qtd_nota_5_geral = 0;
        echo "
			<table class=\"table table-hover dataTableOperadores\"> 
				<thead> 
					<tr> 
    					<th>Atendente</th>
    					<th>Sem nota</th>
    					<th>Nota 1</th>
    					<th>Nota 2</th>
    					<th>Nota 3</th>
    					<th>Nota 4</th>
    					<th>Nota 5</th>
    					<th>QTD LN</th>
    					<th>% LN</th>    					
    					<th>Nota Média</th>
					</tr>
				</thead> 
				<tbody>"
        ;
        foreach ($array_notas_operadores as $id => $conteudo) {
            $nome = $array_dados_operadores[$id] ? $array_dados_operadores[$id] : $id;
            $qtd_nota_sn = $conteudo['sn'] ? $conteudo['sn'] : 0;
            $qtd_nota_1 = $conteudo['1'] ? $conteudo['1'] : 0;
            $qtd_nota_2 = $conteudo['2'] ? $conteudo['2'] : 0;
            $qtd_nota_3 = $conteudo['3'] ? $conteudo['3'] : 0;
            $qtd_nota_4 = $conteudo['4'] ? $conteudo['4'] : 0;
            $qtd_nota_5 = $conteudo['5'] ? $conteudo['5'] : 0;
            $qtd_notas = $conteudo['qtd_notas'] ? $conteudo['qtd_notas'] : 0;
            $soma_notas = $conteudo['soma_notas'] ? $conteudo['soma_notas'] : 0;
            $qtd_atendidas = $conteudo['qtd_atendidas'] ? $conteudo['qtd_atendidas'] : 0;    

            echo '<tr>';
            echo '<td>'.$nome.'</td>';
            echo '<td>'.$qtd_nota_sn.'</td>';
            echo '<td>'.$qtd_nota_1.'</td>';
            echo '<td>'.$qtd_nota_2.'</td>';	
            echo '<td>'.$qtd_nota_3.'</td>';	
            echo '<td>'.$qtd_nota_4.'</td>';	
            echo '<td>'.$qtd_nota_5.'</td>';
            echo '<td>'.$qtd_notas.'</td>';
            echo '<td>'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).' %</td>';
            echo '<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>';
            echo '</tr>';

            $qtd_nota_sn_geral += $qtd_nota_sn;
            $qtd_nota_1_geral += $qtd_nota_1;
            $qtd_nota_2_geral += $qtd_nota_2;
            $qtd_nota_3_geral += $qtd_nota_3;
            $qtd_nota_4_geral += $qtd_nota_4;
            $qtd_nota_5_geral += $qtd_nota_5;
        }
        echo "
			</tbody> 
			<tfoot>
		";
		echo '<tr>';
		echo '<th>Totais</th>';
		echo '<th>'.$qtd_nota_sn_geral.'</th>';
		echo '<th>'.$qtd_nota_1_geral.'</th>';
		echo '<th>'.$qtd_nota_2_geral.'</th>';
		echo '<th>'.$qtd_nota_3_geral.'</th>';
		echo '<th>'.$qtd_nota_4_geral.'</th>';
		echo '<th>'.$qtd_nota_5_geral.'</th>';
		echo '<th>'.$qtd_notas_geral.'</th>';
		echo '<th>'.sprintf("%01.2f", round($qtd_notas_geral*100/($qtd_atendidas_geral == 0 ? 1 : $qtd_atendidas_geral), 2)).' %</th>';
		echo '<th>'.sprintf("%01.2f", round($soma_notas_geral/($qtd_notas_geral == 0 ? 1 : $qtd_notas_geral), 2)).'</th>';
		echo '</tr>';
		echo "
				</tfoot> 
			</table>
		";
		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableOperadores').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'chinese-string', targets: 0 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
        ";
        echo '<hr>';
    }
}

function relatorio_controle_filas(){
    $data_hora = converteDataHora(getDataHora());	
    
    $dados_automacao = DBRead('snep','queue_automacao',"WHERE id = 1");
    $qtd_dias_calculo = $dados_automacao[0]['qtd_dias_calculo'];
    $tempo_fila1 = $dados_automacao[0]['tempo_fila1'];
    $tempo_fila2 = $dados_automacao[0]['tempo_fila2'];
    $porcentagem_prioridade_alta = $dados_automacao[0]['porcentagem_prioridade_alta'];
    $porcentagem_prioridade_baixa = $dados_automacao[0]['porcentagem_prioridade_baixa'];

    $data_hoje = new DateTime(getDataHora('data'));
    $dia_data_hoje = (int) $data_hoje->format('d');
    $data_ate = $data_hoje->modify('yesterday')->format('Y-m-d');
    $dia_data_ate = (int) substr($data_ate, 8, 2);

    //if(($dia_data_ate-$qtd_dias_calculo) >= 0 ){
        $data_de = date('Y-m-d', strtotime("-".$qtd_dias_calculo." days",strtotime(getDataHora('data'))));
    //}else{
    //    $data_de = $data_hoje->modify('first day of this month')->format('Y-m-d');
    //    $qtd_dias_calculo = $dia_data_ate;
    //}

    $qtd_dias_mes = (int) $data_hoje->modify('last day of this month')->format('d');
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De ".converteData($data_de)." até ".converteData($data_ate)."</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de ".converteData($data_de)."</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até ".converteData($data_ate)."</span>";
	}else{
	    $periodo_amostra = "";
    }    

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Controle Automático de Filas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>QTD dias no mês atual:</strong> $qtd_dias_mes</span></legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>QTD dias cálculo base:</strong> $qtd_dias_calculo</span></legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Porcentagem máxima para fila alta < <i class=\"fa fa-question-circle\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title=\"Supondo que o valor deste campo seja 50, serão colocados em fila alta os que atingirem a porcentagem proporcional menor que 50\"></i>:</strong> $porcentagem_prioridade_alta%</span></legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Porcentagem máxima para fila baixa > <i class=\"fa fa-question-circle\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title=\"Supondo que o valor deste campo seja 150, serão colocados em fila baixa os que atingirem a porcentagem proporcional maior que 150\"></i>:</strong> $porcentagem_prioridade_baixa%</span></legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Tempo na Fila 1(segundos):</strong> $tempo_fila1</span></legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Tempo na Fila 2(segundos):</strong> $tempo_fila2</span></legend>";

	$dados_prefixos = DBRead('snep','empresas', "WHERE controle_automatico_fila = '1'");
    if($dados_prefixos){
        echo "
			<table class=\"table table-hover dataTable\"> 
				<thead> 
                    <tr> 
                        <th>Nome</th>
    					<th>QTD Contratada</th>
    					<th>QTD Proporcinal</th>
    					<th>QTD Realizado</th>
    					<th>Porcentagem Utilizada</th>    					
    					<th>Fila 1</th>    					
    					<th>Fila 2</th>    					
					</tr>
				</thead> 
				<tbody>"
        ;

		registraLog('Relatório Central - Relatório de controle de filas','rel','relatorio_controle_filas',1,"WHERE controle_automatico_fila = '1'");

        foreach ($dados_prefixos as $conteudo_prefixo) {
            $dados_contrato = DBRead('','tb_parametros a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_asterisk = '".$conteudo_prefixo['id']."' AND b.status != '3' AND b.status != '2' AND b.status != '0'","b.*, c.nome AS 'nome_cliente'");

            if($dados_contrato){
                $qtd_atendimentos = 0;                

                if($dados_contrato[0]['contrato_pai']){
                    $dados_contrato_pai = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dados_contrato[0]['contrato_pai']."'", "a.*, b.nome AS 'nome_cliente'");

                    $cont_dados_faturados = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_contrato[0]['contrato_pai']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND via_texto != 1' : ''),"COUNT(*) AS cont");
                    $qtd_atendimentos += $cont_dados_faturados[0]['cont'];

                    $dados_contrato_irmaos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.contrato_pai = '".$dados_contrato[0]['contrato_pai']."' AND a.id_contrato_plano_pessoa != '".$dados_contrato[0]['id_contrato_plano_pessoa']."'","a.*, b.nome AS 'nome_cliente'");
                    if($dados_contrato_irmaos){
                        foreach ($dados_contrato_irmaos as $conteudo_contrato_irmao) {
                            $cont_dados_faturados = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND id_contrato_plano_pessoa = '".$conteudo_contrato_irmao['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND via_texto != 1' : ''),"COUNT(*) AS cont");
                            $qtd_atendimentos += $cont_dados_faturados[0]['cont'];
                        }
                    }
                    
                }else{  
                    $dados_contrato_pai = $dados_contrato;

                    $dados_contrato_filhos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.contrato_pai = '".$dados_contrato[0]['id_contrato_plano_pessoa']."'","a.*, b.nome AS 'nome_cliente'");
                    if($dados_contrato_filhos){
                        foreach ($dados_contrato_filhos as $conteudo_contrato_filho) {
                            $cont_dados_faturados = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND id_contrato_plano_pessoa = '".$conteudo_contrato_filho['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND via_texto != 1' : ''),"COUNT(*) AS cont");
                            $qtd_atendimentos += $cont_dados_faturados[0]['cont'];
                        }
                    }
                }                
                
                $cont_dados_faturados = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_contrato[0]['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND via_texto != 1' : ''),"COUNT(*) AS cont");
				$qtd_atendimentos += $cont_dados_faturados[0]['cont'];
               
                $qtd_proporcional_contratada = sprintf("%01.2f", round($dados_contrato_pai[0]['qtd_contratada'] / $qtd_dias_mes * $qtd_dias_calculo, 2));
                $porcentagem_utilizada = sprintf("%01.2f", round($qtd_atendimentos * 100 / ($qtd_proporcional_contratada == 0 ? 1 : $qtd_proporcional_contratada), 2));

                if($conteudo_prefixo['tipo_fila_controle_automatico'] == 'interna'){
                    $fila_alta = 'callATENDIMENTOalta';
                    $fila_normal = 'callATENDIMENTOnormal';
                    $fila_baixa = 'callATENDIMENTObaixa';
                    $fila_vip = 'callATENDIMENTOvip';
                }else if($conteudo_prefixo['tipo_fila_controle_automatico'] == 'experiencia'){
                    $fila_alta = 'callATENDIMENTOaltaEXP';
                    $fila_normal = 'callATENDIMENTOnormalEXP';
                    $fila_baixa = 'callATENDIMENTObaixaEXP';
                    $fila_vip = 'callATENDIMENTOvipEXP';
                }else{
                    $fila_alta = 'callATENDIMENTOaltaEXT';
                    $fila_normal = 'callATENDIMENTOnormalEXT';
                    $fila_baixa = 'callATENDIMENTObaixaEXT';
                    $fila_vip = 'callATENDIMENTOvipEXT';
                }

				if($porcentagem_utilizada < $porcentagem_prioridade_alta){
					$fila1 = $fila_alta;                    
				}else if($porcentagem_utilizada >= $porcentagem_prioridade_alta && $porcentagem_utilizada <= $porcentagem_prioridade_baixa){
					$fila1 = $fila_normal;                    
				}else{
					$fila1 = $fila_baixa;                   
				}
                
                $fila2 = $fila_vip;
               
                echo '
                    <tr>
                        <td>'.$conteudo_prefixo['nome'].'</td>
                        <td>'.$dados_contrato_pai[0]['qtd_contratada'].'</td>
                        <td>'.$qtd_proporcional_contratada.'</td>
                        <td>'.$qtd_atendimentos.'</td>
                        <td>'.$porcentagem_utilizada.'%</td>    					
                        <td>'.$fila1.'</td>    					
                        <td>'.$fila2.'</td>    		
                    </tr>
                ';
            }            
        }
        echo "
                </tbody> 
            </table>
        ";
        echo "
		<script>
			$(document).ready(function(){
			    $('.dataTable').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'chinese-string', targets: 0 }
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
		    	});
			});
		</script>			
		";	
    }

	echo "</div>";
}

function relatorio_sintetico_geral_hora_acumulado($data_de, $data_ate, $hora_de, $hora_ate, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida, $plano){

	if ((strtotime($hora_de) >= strtotime($hora_ate)) && $hora_ate != '00:00') {
		echo '<div id="aguarde" class="alert alert-danger text-center">Hora FINAL deve ser maior que a hora inicial!
				<i class="fa fa-atention"></i>
			  </div>';

	} else {

		$data_temp = new DateTime(converteData($data_ate));
		$data_temp->modify('+1day');

		$period = new DatePeriod(
			new DateTime(converteData($data_de)),
			new DateInterval('P1D'),
			new DateTime($data_temp->format('Y-m-d'))
		);

		$data_hora = converteDataHora(getDataHora());
		$qtd_atendidas = 0;
		$qtd_atendidas_30 = 0;
		$qtd_atendidas_60 = 0;
		$qtd_atendidas_90 = 0;
		$qtd_atendidas_180 = 0;
		$qtd_atendidas_maior_180 = 0;
		$qtd_perdidas = 0;
		$qtd_perdidas_30 = 0;
		$qtd_perdidas_60 = 0;
		$qtd_perdidas_90 = 0;
		$qtd_perdidas_180 = 0;
		$qtd_perdidas_maior_180 = 0;
		$soma_notas = 0;
		$qtd_notas = 0;
		$soma_ta_atendidas = 0;
		$soma_te_atendidas = 0;
		$soma_te_perdidas = 0;

		if ($empresa) {
			$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
			if ($dados_empresa) {
				$nome_empresa = $dados_empresa[0]['nome'];
				$empresa_legenda = $dados_empresa[0]['nome'];

			} else {
				$empresa_legenda = 'Não identificada';
			}
		} else {
			$empresa_legenda = 'Todas';
		}

		if ($segundos_atendimento) {
			$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";

		} else {
			$legenda_descarte_segundos_atendimento = '';
		}
		
		if ($segundos_espera_perdida) {
			$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";

		} else {
			$legenda_descarte_segundos_espera_perdida = '';
		}
		
		if ($data_de && $data_ate) {
			$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate das $hora_de até as $hora_ate</span>";

		} else if ($data_de) {
			$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";

		} else if ($data_ate) {
			$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";

		} else {
			$periodo_amostra = "";
		}
		
		if ($plano) {
			$dados_plano = DBRead('','tb_plano', "WHERE id_plano = '$plano'");
			$legenda_plano = ', <strong>Plano - </strong>'.$dados_plano[0]['nome'];

		} else if (!$empresa) {
			$legenda_plano = ', <strong>Plano - </strong>Todos';

		} else {
			$legenda_plano = '';
		}

		$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

		echo "<div class=\"col-md-12\" style=\"padding: 0\">";
		echo "<legend style=\"text-align:center;\"><strong>Sintético Geral de Ligações - Acumulado hora</strong><br>$gerado</legend>";
		echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda."".$legenda_plano."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
		echo "</legend>";

		$filtro_empresa = '';
		if ($empresa) {
			$filtro_empresa = " AND a.data2 LIKE '$empresa%'";
		} 

		if ($segundos_atendimento) {
			$filtro_segundos_atendidas = " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
		}

		if ($segundos_espera_perdida) {
			$filtro_segundos_perdidas = " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
		}

		if ($plano) {
			$filtro_plano = ' AND (';
			$dados_empresas_plano = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_parametros b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE b.id_asterisk AND a.id_plano = '$plano'",'b.*');
			if ($dados_empresas_plano) {
				foreach ($dados_empresas_plano as $conteudo_empresas_plano) {
					$filtro_plano .= "a.data2 LIKE '".$conteudo_empresas_plano['id_asterisk']."%' OR ";
				}
			}
			$filtro_plano = substr($filtro_plano, 0, -4).')';
			$filtro_plano_atendidas = $filtro_plano;
			$filtro_plano_perdidas = $filtro_plano;
		}

		foreach ($period as $key => $value) {
			$filtro_atendidas = '';
			$filtro_perdidas = '';
			
			if ($data_de) {
				$filtro_atendidas .= " AND b.time >= '".$value->format('Y-m-d')." ".$hora_de.":00'";
				$filtro_perdidas .= " AND a.time >= '".$value->format('Y-m-d')." ".$hora_de.":00'";
			}
			if ($data_ate) {		
				$filtro_atendidas .= " AND b.time < '".$value->format('Y-m-d')." ".$hora_ate.":00'";
				$filtro_perdidas .= " AND a.time < '".$value->format('Y-m-d')." ".$hora_ate.":00'";
			}

			$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_empresa $filtro_segundos_atendidas $filtro_plano_atendidas $filtro_atendidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', c.queuename AS ultimafila, d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout', b.time");

			registraLog('Relatório Central - Sintetico Geral.','rel','relatorio_sintetico_geral',1,"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_empresa $filtro_segundos_atendidas $filtro_plano_atendidas $filtro_atendidas GROUP BY a.callid ORDER BY a.callid");
			
			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {

					if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){

						$te_entrada = 'n';								
						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];
							$te_entrada = $conteudo_atendidas['finalizacao_data1'];				
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
							$te_entrada = $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						if($te_entrada != 'n'){
							if($te_entrada <= 30){
								$qtd_atendidas_30++;
							}else if($te_entrada <= 60){
								$qtd_atendidas_60++;
							}else if($te_entrada <= 90){
								$qtd_atendidas_90++;
							}else if($te_entrada <= 180){
								$qtd_atendidas_180++;
							}else if($te_entrada > 180){
								$qtd_atendidas_maior_180++;
							}
						}
						if($conteudo_atendidas['nota']){
							$soma_notas += $conteudo_atendidas['nota'];
							$qtd_notas++;	
						}			
						$qtd_atendidas++;
					}
				}
			}
			
			$dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_empresa $filtro_segundos_perdidas $filtro_plano_perdidas $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', b.queuename AS ultimafila, a.time, a.id");

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {

					if(preg_match("/'".$conteudo_perdidas['ultimafila']."'/i", $fila) || !$fila){

						if($conteudo_perdidas['finalizacao_data3'] <= 30){
							$qtd_perdidas_30++;

						}else if($conteudo_perdidas['finalizacao_data3'] <= 60){
							$qtd_perdidas_60++;

						}else if($conteudo_perdidas['finalizacao_data3'] <= 90){
							$qtd_perdidas_90++;

						}else if($conteudo_perdidas['finalizacao_data3'] <= 180){
							$qtd_perdidas_180++;

						}else if($conteudo_perdidas['finalizacao_data3'] > 180){
							$qtd_perdidas_maior_180++;
						}
						$qtd_perdidas++;			
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
					}
				}
			}
		}

		echo '
		<table class="table table-hover"> 
			<thead> 
				<tr> 
					<th class="text-center" width="16.6666666667%">Total de atendidas</th>
					<th class="text-center" width="16.6666666667%">Atendidas até 30s</th>
					<th class="text-center" width="16.6666666667%">Atendidas até 60s</th>
					<th class="text-center" width="16.6666666667%">Atendidas até 90s</th>
					<th class="text-center" width="16.6666666667%">Atendidas até 180s</th>
					<th class="text-center" width="16.6666666667%">Atendidas maiores de 180s</th>
				</tr>
			</thead> 
			<tbody>
				<tr>
					<td class="text-center success">'.$qtd_atendidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center success">'.$qtd_atendidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_30*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center success">'.$qtd_atendidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_60*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center success">'.$qtd_atendidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_90*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center success">'.$qtd_atendidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center success">'.$qtd_atendidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				</tr>
			</tbody> 
		</table>
		<table class="table table-hover"> 
			<thead> 
				<tr> 
					<th class="text-center" width="16.6666666667%">Total de perdidas</th>
					<th class="text-center" width="16.6666666667%">Perdidas até 30s</th>
					<th class="text-center" width="16.6666666667%">Perdidas até 60s</th>
					<th class="text-center" width="16.6666666667%">Perdidas até 90s</th>
					<th class="text-center" width="16.6666666667%">Perdidas até 180s</th>
					<th class="text-center" width="16.6666666667%">Perdidas maiores de 180s</th>
				</tr>
			</thead> 
			<tbody>
				<tr>
					<td class="text-center danger">'.$qtd_perdidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center danger">'.$qtd_perdidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_30*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center danger">'.$qtd_perdidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_60*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center danger">'.$qtd_perdidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_90*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center danger">'.$qtd_perdidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
					<td class="text-center danger">'.$qtd_perdidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				</tr>
			</tbody> 
		</table>	
		<table class="table table-hover"> 
			<thead> 
				<tr> 
					<th class="text-center" width="16.6666666667%">Total de ligações</th>
					<th class="text-center" width="16.6666666667%" title="Tempo Médio de Atendimento">TMA</th>
					<th class="text-center" width="16.6666666667%" title="Nota Média de Atendimento">NMA</th>
					<th class="text-center" width="16.6666666667%" title="Ligações com Nota">LN</th>				
					<th class="text-center" width="16.6666666667%" title="Tempo Médio de Espera">TME</th>
					<th class="text-center" width="16.6666666667%" title="Tempo Médio das Perdidas">TMP</th>
				</tr>
			</thead> 
			<tbody>
				<tr>
					<td class="text-center active">'.($qtd_atendidas+$qtd_perdidas).'</td>
					<td class="text-center info">'.gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
					<td class="text-center info">'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
					<td class="text-center info">'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</td>
					<td class="text-center warning">'.gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
					<td class="text-center warning">'.gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</td>
				</tr>
			</tbody> 
		</table>
		'; 
	}
}
?>