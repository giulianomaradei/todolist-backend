<?php
require_once(__DIR__."/../class/System.php");

$id_usuario = $_GET['id_usuario'];
$id_monitoria_mes = $_GET['id_monitoria_mes'];

$nota_get = (!empty($_GET['nota'])) ? $_GET['nota'] : '';
$mes_get = (!empty($_GET['mes'])) ? $_GET['mes'] : 'atual';
$tempo_get = (!empty($_GET['tempo'])) ? $_GET['tempo'] : 30;

$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "b.nome");

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;

$nota = (!empty($_POST['nota'])) ? $_POST['nota'] : $nota_get;
$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : $mes_get;
$gerar = (!empty($_POST['gerar'])) ? 1 : 1;
$tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : $tempo_get;

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$dia_hoje = explode("-", $data_hoje[0]);

if($dia_hoje[2] == '01'){
    $dia_ontem = '01';
}else{
    $dia_ontem = substr(getDataHora('data_ontem'),8,2);
}

$dia_get = (!empty($_GET['dia'])) ? $_GET['dia'] : $dia_ontem;
$dia = (!empty($_POST['dia'])) ? $_POST['dia'] : $dia_get;

$date = new DateTime('now');
$date->modify('first day of last month');
$mes_passado = $date->format('Y-m-d');

$verifica = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes AND status = 1");

$data_referencia = $verifica[0]['data_referencia'];

$data_referencia_formulario = explode("-", $data_referencia);
$data_referencia_formulario = $data_referencia_formulario[1].'/'.$data_referencia_formulario[0];

$tipo_monitoria = $verifica[0]['tipo_monitoria'];
$classificacao_atendente = $verifica[0]['classificacao_atendente'];

if ($classificacao_atendente == 1) {
    $legenda_classificacao = 'Em treinamento';

} else if ($classificacao_atendente == 2) {
    $legenda_classificacao = 'Período de experiência';

} else {
    $legenda_classificacao = 'Efetivado';
}

if ($tipo_monitoria == 1) {
    $legenda_monitoria = 'Via Telefone';

} else if ($tipo_monitoria == 2) {
    $legenda_monitoria = 'Via Texto';

} else {
    $legenda_monitoria = 'Efetivado';
}

$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$id_usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

if($turno){
    $inicial_seg = $turno[0]['inicial_seg'];
    $final_seg = $turno[0]['final_seg'];

    if($inicial_seg > $final_seg){
        //$resultado = $inicial_seg - $final_seg;

        $hora1 = '2000-10-10 '.$inicial_seg.':00';
        $hora2 = '2000-10-11 '.$final_seg.':00';
        $data1 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora1)));
        $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora2)));
        $resultado = strtotime($data2) - strtotime($data1);

    }else{
        //$resultado = $final_seg - $inicial_seg;

        $hora1 = strtotime(''.$inicial_seg.'');
        $hora2 = strtotime(''.$final_seg.'');
        $resultado = ($hora2-$hora1);
    }

    $h = ($resultado/(60*60))%24;

    $dados_turno = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes AND data_referencia = '$data_referencia' AND status = 1");
    
    /* echo '<pre>';
    var_dump($dados_turno);
    echo '</pre>'; */

    $soma = 0;
    if($h >= 5){
        $turno = 'Turno integral';

        $soma = $dados_turno[0]['qtd_audios_monitoria_integral_sn'] + $dados_turno[0]['qtd_audios_monitoria_integral_n1'] + $dados_turno[0]['qtd_audios_monitoria_integral_n2'] + $dados_turno[0]['qtd_audios_monitoria_integral_n3'] + $dados_turno[0]['qtd_audios_monitoria_integral_n4'] + $dados_turno[0]['qtd_audios_monitoria_integral_n5'];

        $sem_nota = $dados_turno[0]['qtd_audios_monitoria_integral_sn'];
        $nota1 = $dados_turno[0]['qtd_audios_monitoria_integral_n1'];
        $nota2 = $dados_turno[0]['qtd_audios_monitoria_integral_n2'];
        $nota3 = $dados_turno[0]['qtd_audios_monitoria_integral_n3'];
        $nota4 = $dados_turno[0]['qtd_audios_monitoria_integral_n4'];
        $nota5 = $dados_turno[0]['qtd_audios_monitoria_integral_n5'];

    }else{
        $turno = 'Meio turno';

        $soma = $dados_turno[0]['qtd_audios_monitoria_meio_turno_sn'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n1'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n2'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n3'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n4'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n5'];

        $sem_nota = $dados_turno[0]['qtd_audios_monitoria_meio_turno_sn'];
        $nota1 = $dados_turno[0]['qtd_audios_monitoria_meio_turno_n1'];
        $nota2 = $dados_turno[0]['qtd_audios_monitoria_meio_turno_n2'];
        $nota3 = $dados_turno[0]['qtd_audios_monitoria_meio_turno_n3'];
        $nota4 = $dados_turno[0]['qtd_audios_monitoria_meio_turno_n4'];
        $nota5 = $dados_turno[0]['qtd_audios_monitoria_meio_turno_n5'];

    }
}else{
    $turno = 'N/D';
    $soma = 'N/D';

    echo 'Não tem escala!';
}

$dateTime = new DateTime(getDataHora());
$dateTime->modify('last day of this month');
$n_dias = $dateTime->format("d");
$sel_dia[$dia] = 'selected';

$numero_ligacoes = DBRead('', 'tb_monitoria_avaliacao_audio', '', "COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND nota = 5 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n5, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND nota = 4 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n4, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND nota = 3 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n3, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND nota = 2 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n2, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND nota = 1 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n1, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND nota IS NULL AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_sn, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND id_erro IS NOT NULL AND considerar = 1 THEN 1 END) AS cont_erros ");

$cont_n5 = $numero_ligacoes[0]['cont_n5'];
$cont_n4 = $numero_ligacoes[0]['cont_n4'];
$cont_n3 = $numero_ligacoes[0]['cont_n3'];
$cont_n2 = $numero_ligacoes[0]['cont_n2'];
$cont_n1 = $numero_ligacoes[0]['cont_n1'];
$cont_sn = $numero_ligacoes[0]['cont_sn'];
$cont_erros = $numero_ligacoes[0]['cont_erros'];

if ($numero_ligacoes[0]['cont_sn'] <= $sem_nota) {
    $avaliadas += $numero_ligacoes[0]['cont_sn'];
} else {
    $avaliadas += $sem_nota;
}

if ($numero_ligacoes[0]['cont_n1'] <= $nota1) {
    $avaliadas += $numero_ligacoes[0]['cont_n1'];
} else {
    $avaliadas += $nota1;
}

if ($numero_ligacoes[0]['cont_n2'] <= $nota2) {
    $avaliadas += $numero_ligacoes[0]['cont_n2'];
} else {
    $avaliadas += $nota2;
}

if ($numero_ligacoes[0]['cont_n3'] <= $nota3) {
    $avaliadas += $numero_ligacoes[0]['cont_n3'];
} else {
    $avaliadas += $nota3;
}

if ($numero_ligacoes[0]['cont_n4'] <= $nota4) {
    $avaliadas += $numero_ligacoes[0]['cont_n4'];
} else {
    $avaliadas += $nota4;
}

if ($numero_ligacoes[0]['cont_n5'] <= $nota5) {
    $avaliadas += $numero_ligacoes[0]['cont_n5'];
} else {
    $avaliadas += $nota5;
}

$data_ate = substr($data_referencia, 0, -2);
$data_ate .= $n_dias;

/* $erros = DBRead('', 'tb_erro_atendimento', "WHERE id_usuario = '$id_usuario' AND data_erro = '".$data_referencia."' AND '".$data_ate."' ", "COUNT(*) as n_erros"); */

if ($mes == 'atual') {
                            
    $dataultimodia = new DateTime(getDataHora());
    $dataultimodia->modify('last day of this month');

    $dataprimeirodia = new DateTime(getDataHora());
    $dataprimeirodia->modify('first day of this month');
    $mes_atual = $dataprimeirodia->format("m");

    $filtro_data = " AND b.time >= '".$dataprimeirodia->format("Y-m-d")." 00:00:00' AND b.time <= '".$dataultimodia->format("Y-m-d")." 23:59:59' ";
    
    $erros = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.canal_atendimento = 1 AND a.status = 1 AND a.id_usuario = '$id_usuario' AND a.data_erro >= '".$dataprimeirodia->format("Y-m-d")."' AND  a.data_erro <= '".$dataultimodia->format("Y-m-d")."' ORDER BY a.data_erro ASC", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

    if ($erros) {
        $num_erros = sizeof($erros);
    } else {
        $num_erros = 0;
    }

    $datas_audios = DBRead('', 'tb_monitoria_avaliacao_audio' , "WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$id_usuario' AND considerar = 1 GROUP BY data_audio ORDER BY data_audio ASC", 'data_audio');

    $array_datas = array();
    
    if ($datas_audios) {
        foreach ($datas_audios as $conteudo) {

            $mes_audio = new DateTime($conteudo['data_audio']);
            $mes_audio = $mes_audio->format("m");

            if ($mes_atual == $mes_audio) {
                $data_audio_avaliado = converteDataHora($conteudo['data_audio']);
                $dia_avaliado = substr($data_audio_avaliado,0,2);
                $array_datas[$dia_avaliado] = ' (Já avaliado)';
            }
        }
    }

} else {

    $dataultimodia = new DateTime(getDataHora());
    $dataultimodia->modify('last day of last month');

    $dataprimeirodia = new DateTime(getDataHora());
    $dataprimeirodia->modify('first day of last month');
    $mes_anterior = $dataprimeirodia->format("m");

    $filtro_data = " AND b.time >= '".$dataprimeirodia->format("Y-m-d")." 00:00:00' AND b.time <= '".$dataultimodia->format("Y-m-d")." 23:59:59' ";
    
    $erros = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.canal_atendimento = 1 AND a.id_usuario = '$id_usuario' AND a.data_erro >= '".$dataprimeirodia->format("Y-m-d")."' AND  a.data_erro <= '".$dataultimodia->format("Y-m-d")."' ORDER BY a.data_erro ASC", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

    if ($erros) {
        $num_erros = sizeof($erros);
    } else {
        $num_erros = 0;
    }

    $data_referencia = $dataprimeirodia->format("Y-m-d");

    $datas_audios = DBRead('', 'tb_monitoria_avaliacao_audio' , "WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$id_usuario' AND considerar = 1 GROUP BY data_audio ORDER BY data_audio ASC", 'data_audio');

    $array_datas = array();
    if ($datas_audios) {
        foreach ($datas_audios as $conteudo) {

            $mes_audio = new DateTime($conteudo['data_audio']);
            $mes_audio = $mes_audio->format("m");

            if ($mes_anterior == $mes_audio) {
                $data_audio_avaliado = converteDataHora($conteudo['data_audio']);
                $dia_avaliado = substr($data_audio_avaliado,0,2);
                $array_datas[$dia_avaliado] = ' (Já avaliado)';
            }
        }
    }
}

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<style>
    table, th {
        border: none !important;
    }
    .td-qtd:hover{
        background-color: #265a88 !important;
        color: white;
    }
    .td-qtd{
        max-width: 155px !important;
        background-color: #E6E6E6;
    }
    .div-css{
        min-width: 50px;
        /* max-width: 240px; */
        min-height: 100px;
    }
    .span-nota-css{
        font-size: 17px; 
        font-family: Trebuchet MS;
    }
    .span-numeros-css{
        font-size: 23px; 
        font-family: Trebuchet MS;
    }
    .icon-check-css{
        color: green; font-size: 11px;
    }
    .icon-exclam-css{
        color: #8A0808;
        font-size: 11px;
    }
    .icon-legenda-css{
        font-size: 12px;
    }
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
    .option-green option{
        background-color: #0F0;
    }
    
</style>

<!--container-fluid-->
<div class="container-fluid">
	<form method="post" action="">
        <div class="row">
	        <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <!-- row-->
                        <div class="row">
                           
                            <input type="hidden" value="<?= $id_monitoria_mes ?>" name="id_monitoria_mes" >
                            <!-- col-->
                            <div class="col-md-4">
                                 <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Áudio ligações: <strong><?=$atendente[0]['nome']?></strong></h3>
                            </div>
                            <!--end col-->

                            <!-- col-->
                            <div class="col-md-4">
                                <h3 class="panel-title text-center">Mês referência: 
                                    <strong><?=$data_referencia_formulario?></strong>
                                    (<?= $legenda_monitoria ?> - <?= $legenda_classificacao ?>)
                                </h3> 
                            </div>
                            <!--end col-->
                            
                            <!-- col-->
                            <div class="col-md-4">
                                 <h3 class="panel-title text-center pull-right">Carga Horária: <strong><?=$turno?></strong> </h3>
                            </div>
                            <!--end col-->

                        </div>
                        <!--end row-->
                    </div>
                    <div class="panel-body">
                        
                        <!-- row-->
                        <div class="row" style="margin-bottom: 16px;">
                            
                            <!-- col-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descartar abaixo de (seg):</label>
                                    <input type="number" class="form-control input-sm" name="tempo" value="<?=$tempo?>">
                                </div>
                            </div>
                            <!--end col-->

                            <!-- col-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Nota:</label>
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
                            <!--end col-->

                            <!-- col-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Mês:</label>
                                    <select class="form-control input-sm" name="mes" id="mes">
                                        <?php
                                            $sel_mes[$mes] = 'selected';
                                        ?>

                                        <option value="atual" <?=$sel_mes['atual']?> >Atual</option>
                                        <option value="anterior" <?=$sel_mes['anterior']?> >Anterior</option>
                                    </select>
                                </div>
                            </div>
                            <!--end col-->

                            <!-- col-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Dia do mês:</label>
                                    <select class="form-control input-sm" name="dia" id="dia">
                                        <option value="todos">Todos</option>
                                        <?php
                                            
                                            for($i = 1; $i<= $n_dias; $i++){
                                                if($i < 10){
                                                    $i = '0'.$i;
                                                }
                                                echo "<option value='".$i."' ".$sel_dia[$i].">".$i.$array_datas[$i]."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--end col-->
                            
                            <!-- col-->
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp</label>
                                    <button class="btn btn-primary btn-sm form-control" name="gerar" id="gerar" value="1" type="submit" style="max-height: 30px;">
                                        <i class="fa fa-refresh"></i> Listar
                                    </button>
                                </div>
                            </div>
                            <!--end col-->
                            
                            <div class="col-md-2">
                            </div>

                        </div>
                        <!--end row-->

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label> Quantidade de ligações: </label>                                
                                <div class="">
                                    <table class="table table-bordered" style="border: none;">
                                        <thead style="border: none;">
                                            <!-- <tr style="border: none; background-color: #e8e8e8">
                                                <th class="text-center">Sem nota</th>
                                                <th class="text-center">Nota 1</th>
                                                <th class="text-center">Nota 2</th>
                                                <th class="text-center">Nota 3</th>
                                                <th class="text-center">Nota 4</th>
                                                <th class="text-center">Nota 5</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Reclamações/Erros</th>
                                            </tr> -->
                                        </thead>
                                        <tbody>
                                            <tr style="border: none;">

                                                <?php

                                                    if($cont_sn >= $sem_nota){
                                                        $check_sn = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($cont_n1 >= $nota1){
                                                        $check_n1 = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($cont_n2 >= $nota2){
                                                        $check_n2 = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($cont_n3 >= $nota3){
                                                        $check_n3 = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }
                                                    if($cont_n4 >= $nota4){
                                                        $check_n4 = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($cont_n5 >= $nota5){
                                                        $check_n5 = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($avaliadas >= $soma){
                                                        $check_avaliadas = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($cont_erros >= $num_erros){
                                                        $check_erros = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }
                                                ?>

                                                <td class="td-qtd" <?=$check_sn?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Sem nota 
                                                                        <i class="fa fa-close icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios avaliados"></i> 
                                                                    <strong><?=$cont_sn?> | <?=$sem_nota?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_n1?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Nota 1 
                                                                        <i class="fa fa-star-o icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios avaliados"></i> 
                                                                    <strong><?=$cont_n1?> | <?=$nota1?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_n2?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Nota 2
                                                                        <i class="fas fa-star-half-alt icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios avaliados"></i> 
                                                                    <strong><?=$cont_n2?> | <?=$nota2?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_n3?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Nota 3 
                                                                        <i class="fas fa-star-half-alt icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios avaliados"></i> 
                                                                    <strong><?=$cont_n3?> | <?=$nota3?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_n4?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Nota 4 
                                                                        <i class="fas fa-star-half-alt icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios avaliados"></i> 
                                                                    <strong><?=$cont_n4?> | <?=$nota4?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_n5?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Nota 5 
                                                                        <i class="fa fa-star icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios avaliados"></i> 
                                                                    <strong><?=$cont_n5?> | <?=$nota5?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_avaliadas?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Total
                                                                        <i class="fa fa-info-circle icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade total de áudios avaliados"></i> 
                                                                    <strong><?=$avaliadas?> | <?=$soma?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade total de áudios a serem avaliados"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_erros?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Reclamações/Erros
                                                                         <i class="fa fa-bug icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css">
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade de erros avaliados"></i> 
                                                                    <strong><?=$cont_erros?> | <?=$num_erros?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade total de erros do atendente este mês"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>  
                        </div>
                 
                        <hr>

                        <!-- row-->
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div> -->	
                                <div id="resultado" style="display:none;">		
                                    <?php 
                                    if($gerar){
                                        ligacoes($id_usuario, $nota, $mes, $dia, $tempo, $id_monitoria_mes, $request->token);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--end row-->

                    </div>
                </div>
            </div>
            <!--end col-->
	    </div>
        <!--end row-->
	</form>
	<div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div>
</div>
<!--end container-fluid-->

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Vincular Reclamação/Erro</h4>
      </div>
      <div class="modal-body">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <?php

                        //var_dump($erros);

                        if($erros){

                            foreach($erros as $conteudo){

                                if($conteudo['protocolo'] == ''){
                                    $protocolo = 'N/D';
                                }else{
                                    $protocolo = $conteudo['protocolo'];
                                }
                    ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h3 class="panel-title"><strong><?=$conteudo['nome_empresa']?></strong></h3>
                                    </div>
                                    <div class="col-md-4">
                                        <h3 class="panel-title text-center">Protocolo: <?=$protocolo?></h3>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- <button type="button" class="btn btn-primary btn-xs pull-right btn-vincular" id="<?=$conteudo['id_erro_atendimento']?>">
                                           <i class="fa fa-bug"></i><span class="txt-vincular"> Vincular</span>
                                        </button> -->
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" style="margin-bottom: 8px;">
                                        <thead>
                                            <tr>
                                                <th class="col-md-3">Tipo Reclamação/Erro</th>
                                                <th class="col-md-3">Criado por</th>
                                                <th class="col-md-3">Assinante</th>
                                                <th class="col-md-3">Data Reclamação/Erro</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?=$conteudo['descricao_erro']?></td>
                                                <td><?=$conteudo['usuario_cadastrou']?></td>
                                                <td><?=$conteudo['assinante']?></td>
                                                <td><?=converteData($conteudo['data_erro'])?> <?=$conteudo['hora_erro']?> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <hr>
                                <div class='conteudo-editor' style="margin-left: 7px;">
                                    <strong>Descrição</strong><br><br>
                                    <?=$conteudo['descricao_cliente']?>
                                </div>
                            </div>
                        </div>

                        <hr>

                    <?php
                            }
                        }
                        else{
                            echo '<p class="alert alert-info" style="text-align: center">Não foram encontrados Reclamações/Erros</p>';
                        }
                    ?>
                </div>
            </div>
            <!-- endo row -->
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">
            OK
        </button> -->
        <!-- <button type="button" class="btn btn-primary">
            <i class="fa fa-floppy-o"></i> Salvar
        </button> -->
      </div>
    </div>
  </div>
</div>
<!-- end modal -->

<script>

    $(document).on('submit', 'form', function(){
        modalAguarde();
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#mes').on('change', function(){

        var mes = $('#mes').val();
        var d = new Date();
        var m = d.getMonth();
        var y = d.getFullYear();

        var getDaysInMonth = function(month,year) {
            return new Date(year, month, 0).getDate();
        };

        var array_dias_avaliados = <?php echo json_encode($array_teste) ?>;

        console.log(array_dias_avaliados);

        if (mes == 'atual') {

            m = parseInt(m) + parseInt(1);
            dias = getDaysInMonth(m, y);

            $('#dia').empty();
            $('#dia').append('<option value="todos">Todos</option>');

            $.ajax({
                type: "POST",
                url: "/api/ajax?class=SelectMonitoriasRealizadas.php",
                dataType: "json",
                data: {
                    canal_atendimento: 1,
                    mes: 'atual',
                    atendente: '<?php echo $id_usuario ?>',
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    for(var i = 1; i<= dias; i++){
                
                        var cont = 0;
                        var frase = '';
                        
                        $.each(data, function(key, value) {

                            console.log('dia: ' + i + ' - valor: ' + value);

                            if(i == value){
                                console.log('igual');

                                cont = cont + 1;
                                frase = ' (Já avaliado)';
                            }
                        });
                    
                        if(cont > 0){
                            $('#dia').append(new Option(i + frase, i));
                        }else{
                            $('#dia').append(new Option(i, i));
                        }
                    }
                }
            });

        } else if(mes == 'anterior') {
            
            dias = getDaysInMonth(m, y);

            $('#dia').empty();
            $('#dia').append('<option value="todos">Todos</option>');

            $.ajax({
                type: "POST",
                url: "/api/ajax?class=SelectMonitoriasRealizadas.php",
                dataType: "json",
                data: {
                    canal_atendimento: 1,
                    mes: 'anterior',
                    atendente: '<?php echo $id_usuario ?>',
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    for(var i = 1; i<= dias; i++){
                
                        var cont = 0;
                        var frase = '';
                        
                        $.each(data, function(key, value) {

                            console.log('dia: ' + i + ' - valor: ' + value);

                            if(i == value){
                                console.log('igual');

                                cont = cont + 1;
                                frase = ' (Já avaliado)';
                            }
                        });
                    
                        if(cont > 0){
                            $('#dia').append(new Option(i + frase, i));
                        }else{
                            $('#dia').append(new Option(i, i));
                        }
                    }
                }
            });
        }

    });

</script>

<?php
    function ligacoes($id_usuario, $nota, $mes, $dia, $tempo, $id_monitoria_mes, $token){
       
        $fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

        if ($nota) {
            if ($nota == 'n') {
                $filtro_nota = " AND d.nota IS NULL";
            } else {
                $filtro_nota = " AND d.nota = '$nota'";
            }
        }

        $id_asterisk = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario' ", 'id_asterisk');

        $agent = 'AGENT/'.$id_asterisk[0]['id_asterisk'];

        if ($dia != 'todos') {

            if ($mes == 'atual') {

                $filtro_data = " AND b.time >= '".substr(getDataHora('data'), 0, -2)."$dia 00:00:00' AND b.time <= '".substr(getDataHora('data'), 0, -2)."$dia 23:59:59' ";

            } else if ($mes == 'anterior') {

                $dataprimeirodia = new DateTime(getDataHora());
                $dataprimeirodia->modify('first day of last month');

                $mesanterior = $dataprimeirodia->format("Y-m-").$dia;

                $filtro_data = " AND b.time >= '".$mesanterior." 00:00:00' AND b.time <= '".$mesanterior." 23:59:59' ";
            }

        } else {

            if($mes == 'atual'){

                $dataultimodia = new DateTime(getDataHora());
                $dataultimodia->modify('last day of this month');

                $dataprimeirodia = new DateTime(getDataHora());
                $dataprimeirodia->modify('first day of this month');

                $filtro_data = " AND b.time >= '".$dataprimeirodia->format("Y-m-d")." 00:00:00' AND b.time <= '".$dataultimodia->format("Y-m-d")." 23:59:59' ";

            }else if($mes == 'anterior'){

                $dataultimodia = new DateTime(getDataHora());
                $dataultimodia->modify('last day of last month');

                $dataprimeirodia = new DateTime(getDataHora());
                $dataprimeirodia->modify('first day of last month');

                $filtro_data = " AND b.time >= '".$dataprimeirodia->format("Y-m-d")." 00:00:00' AND b.time <= '".$dataultimodia->format("Y-m-d")." 23:59:59' ";
            }
        }
        
        // $dados_ligacoes = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' AND b.agent = '$agent' AND (c.data2 >= $tempo OR c.data4 >= $tempo) AND a.queuename IN ('callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT', 'callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP') $filtro_nota $filtro_data GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");
        
        $dados_ligacoes = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' AND b.agent = '$agent' AND (c.data2 >= $tempo OR c.data4 >= $tempo) $filtro_nota $filtro_data GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

        if($dados_ligacoes){

            echo '<div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm form-control pull-right" name="gerar" id="gerar" value="1" type="button" data-toggle="modal" data-target="#myModal" style="max-height: 30px;">
                            <i class="fa fa-bug"></i> Ver Reclamações/Erros
                        </button>
                    </div>
                  </div>';
            
            echo "
                <table class=\"table table-hover dataTable\">
                    <thead> 
                        <tr>
                            <th>Data</th>
                            <th>Empresa</th>
                            <th>Número</th>
                            <th>Finalização</th>
                            <th>T. Atendimento</th>
                            <th>T. Espera</th>
                            <th>Nota</th>
                            <th class=\"noprint\">Gravação</th>
                            <th>Avaliar</th>
                        </tr>
                    </thead> 
                <tbody>
            ";
            
            foreach($dados_ligacoes as $conteudo_atendidas){

                if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){

                    $id_ligacao = $conteudo_atendidas['enterqueue_callid'];

                    $verifica_audio = DBRead('', 'tb_monitoria_avaliacao_audio', "WHERE id_ligacao = '$id_ligacao' AND considerar = 1", 'id_monitoria_avaliacao_audio');

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
                    }elseif(end($enterqueue_data2) == 'anonymous'){
                        $bina = 'Nº anônimo';
                    }else{
                        $bina = 'Não identificado';
                    }

                    $info_chamada = array(
                        'data_hora_atendida' => '',
                        'nome_empresa' => $nome_empresa,
                        'bina' => $bina,
                        'tempo_atendimento' => '',
                        'nota' => '',
                        'gravacao' => '',
                        'finalizacao' => '',
                        'tempo_espera' => ''
                    );

                    $info_chamada['data_hora_atendida'] = date('d/m/Y H:i:s', strtotime($conteudo_atendidas['connect_time']));

                    $operador_chamada = explode('/', $conteudo_atendidas['connect_agent']);
                    $tipo_operador_chamada = $operador_chamada[0];
                    $cod_operador_chamada = $operador_chamada[1];

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
                        $info_chamada['finalizacao'] = 'Transferida';		
                    }
                    if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
                        $info_chamada['tempo_espera'] += $conteudo_atendidas['tempo_espera_timeout'];
                    }
                    if($info_chamada['data_hora_atendida'] && $info_chamada['nome_empresa'] && $info_chamada['finalizacao']){
                        echo '<tr>';
                        echo '<td>'.$info_chamada['data_hora_atendida'].'</td>';
                        echo '<td>'.$info_chamada['nome_empresa'].'</td>';
                        echo '<td>'.$info_chamada['bina'].'</td>';		
                        echo '<td>'.$info_chamada['finalizacao'].'</td>';
                        echo '<td>'.gmdate("H:i:s", $info_chamada['tempo_atendimento']).'</td>';
                        echo '<td>'.gmdate("H:i:s", $info_chamada['tempo_espera']).'</td>';
                        echo '<td>'.$info_chamada['nota'].'</td>';
                        echo '<td class="noprint"><audio controls preload="none" class="audio_gravacao"><source src="'.$info_chamada['gravacao'].'.mp3" type="audio/mp3"><source src="'.$info_chamada['gravacao'].'.wav" type="audio/wav">Seu navegador não aceita o player nativo.</audio></td>';

                        if($verifica_audio){
                            echo '<td><i class="fa fa-check-circle" style="font-size: 17px; color: green; margin-left: 10px;" title="Áudio já avaliado!"></i></td>';
                            echo '</tr>';

                        }else{

<<<<<<< HEAD
                            echo '<td><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-avaliacao-telefone-form&id='.$id_usuario.'&idCal='.$id_ligacao.'&nota='.$nota.'&dia='.$dia.'&mes='.$mes.'&tempo='.$tempo.'&id_monitoria_mes='.$id_monitoria_mes.'" style="margin-left: 10px;"><i class="fa fa-gavel" style="font-size: 17px;"></i></a></td>';
=======
                            echo '<td><a href="/api/iframe?token='.$token.'&view=monitoria-avaliacao-telefone-form&id='.$id_usuario.'&idCal='.$id_ligacao.'&nota='.$nota.'&dia='.$dia.'&mes='.$mes.'&tempo='.$tempo.'&id_monitoria_mes='.$id_monitoria_mes.'" style="margin-left: 10px;"><i class="fa fa-gavel" style="font-size: 17px;"></i></a></td>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                            echo '</tr>';
                        }	
                    }
                }
            }
            echo '</tbody>
                </table>';

            echo "
            <script>
                $(document).ready(function(){
                    $('.dataTable').DataTable({
                        \"language\": {
                            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                        },
                        columnDefs: [
                            { type: 'time-uni', targets: 5 },
                            { type: 'time-uni', targets: 6 },
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
               echo  '<p class="alert alert-info" style="text-align: center">Não foram encontrados áudios</p>';
            echo "</div>";
        }
    }
?>