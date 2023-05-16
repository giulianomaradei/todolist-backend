<?php
require_once(__DIR__."/../class/System.php");

$id_usuario = $_GET['id_usuario'];
$id_monitoria_mes = $_GET['id_monitoria_mes'];
$mes_get = (!empty($_GET['mes'])) ? $_GET['mes'] : 'atual';

$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "b.nome");

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;

$mes_get = (!empty($_GET['mes'])) ? $_GET['mes'] : 'atual';
$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : $mes_get;
$gerar = (!empty($_POST['gerar'])) ? 1 : 1;

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
    
    $soma = 0;
    if($h >= 5){
        $turno = 'Turno integral';
        $qtd_atendimentos = $dados_turno[0]['qtd_texto_monitoria_integral'];

    }else{
        $turno = 'Meio turno';
        $qtd_atendimentos = $dados_turno[0]['qtd_texto_monitoria_meio_turno'];
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

$numero_ligacoes = DBRead('', 'tb_monitoria_avaliacao_texto', '', "COUNT(CASE WHEN data_referencia = '".$data_referencia."' AND id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS qtd_avaliacao, COUNT(CASE WHEN data_referencia = '".$data_referencia."' AND id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id_usuario."' AND id_erro IS NOT NULL AND considerar = 1 THEN 1 END) AS cont_erros ");

$qtd_avaliados = $numero_ligacoes[0]['qtd_avaliacao'];
$cont_erros = $numero_ligacoes[0]['cont_erros'];

if ($numero_ligacoes[0]['qtd_avaliacao'] <= $qtd_atendimentos) {
    $avaliadas += $numero_ligacoes[0]['qtd_avaliacao'];
} else {
    $avaliadas += $qtd_atendimentos;
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
    
    $erros = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.canal_atendimento = 2 AND a.status = 1 AND a.id_usuario = '$id_usuario' AND a.data_erro >= '".$dataprimeirodia->format("Y-m-d")."' AND  a.data_erro <= '".$dataultimodia->format("Y-m-d")."' ORDER BY a.data_erro ASC", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

    if ($erros) {
        $num_erros = sizeof($erros);
    } else {
        $num_erros = 0;
    }

    $datas_atendimentos = DBRead('', 'tb_monitoria_avaliacao_texto a' , "INNER JOIN tb_atendimento b ON a.id_Atendimento = b.id_atendimento WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$id_usuario' AND considerar = 1 GROUP BY b.data_inicio ORDER BY b.data_inicio ASC", 'b.data_inicio');

    $array_datas = array();
    
    if ($datas_atendimentos) {
        foreach ($datas_atendimentos as $conteudo) {
            $mes_texto = new DateTime($conteudo['data_inicio']);
            $mes_texto = $mes_texto->format("m");

            if ($mes_atual == $mes_texto) {
                $data_atendimento_avaliado = converteDataHora($conteudo['data_inicio']);
                $dia_avaliado = substr($data_atendimento_avaliado,0,2);
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
    
    $erros = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.canal_atendimento = 2 AND a.id_usuario = '$id_usuario' AND a.data_erro >= '".$dataprimeirodia->format("Y-m-d")."' AND  a.data_erro <= '".$dataultimodia->format("Y-m-d")."' ORDER BY a.data_erro ASC", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

    if ($erros) {
        $num_erros = sizeof($erros);
    } else {
        $num_erros = 0;
    }

    $data_referencia = $dataprimeirodia->format("Y-m-d");

    $data_atendimento = DBRead('', 'tb_monitoria_avaliacao_texto a' , "INNER JOIN tb_atendimento b ON a.id_atendimento = b.id_atendimento WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$id_usuario'", 'data_inicio');

    $array_datas = array();

    if ($datas_audios) {
        foreach ($datas_audios as $conteudo) {
            $mes_texto = new DateTime($conteudo['data_inicio']);
            $mes_texto = $mes_texto->format("m");

            if ($mes_anterior == $mes_texto) {
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
    .popover{
        color: black !important;
        border-top: 2px solid #D8D8D8;
        border-left: 2px solid #D8D8D8;
        border-right: 2px solid #D8D8D8;
        border-bottom: 2px solid #D8D8D8;
        z-index: 9999 !important;
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

                        <div class="row">
                            <div class="col-md-12">
                                <label> Quantidade a avaliar: </label>                                
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

                                                    if($avaliadas >= $qtd_atendimentos){
                                                        $check_qtd = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }

                                                    if($cont_erros >= $num_erros){
                                                        $check_erros = 'style="background-color: #008B8B; color: white; border-color: #008B8B"';
                                                    }
                                                ?>

                                                <td class="td-qtd" <?=$check_qtd?>>
                                                    <div class="div-css">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-nota-css">
                                                                    <strong>Atendimentos avaliados
                                                                        <i class="fas fa-headset icon-legenda-css"></i>
                                                                    </strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 20px;">
                                                            <div class="col-md-12 text-center">
                                                                <span class="span-numeros-css text-center">
                                                                     <i class="fa fa-check-circle icon-check-css text-center" data-toggle="tooltip" data-placement="bottom" title="Quantidade de atendimentos avaliados"></i> 
                                                                    <strong><?=$avaliadas?></strong>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="td-qtd" <?=$check_qtd?>>
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
                                                                     <i class="fa fa-check-circle icon-check-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade total de atendimentos avaliados"></i> 
                                                                    <strong><?=$avaliadas?> | <?=$qtd_atendimentos?></strong>
                                                                    <i class="fa fa-exclamation-circle icon-exclam-css" data-toggle="tooltip" data-placement="bottom" title="Quantidade total de atendimentos a serem avaliados"></i>
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
                                        relatorio_faturados($mes, $dia, $tipo_atendimento, $id_monitoria_mes, $id_usuario, $request->token);
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

    $(function(){
        $('[data-toggle="popover"]').popover({ trigger: "hover", container: "body" });
        
        var popupElement = "<a class='btn-sim btn btn-xs btn-success' href='class/LeadNegocio.php?excluir=<?php echo $id ?>' style='color: #ccc;' data-ls-popover='hide'>Sim </a><a class='btn-nao btn btn-xs btn-danger' style='color: #ccc;'>Não</a>";

        $('[data-toggle="excluir"]').popover({
            animation: true,
            content: popupElement,
            html: true
        });
    });

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

        if(mes == 'atual'){

            m = parseInt(m) + parseInt(1);
            dias = getDaysInMonth(m, y);

            $('#dia').empty();
            $('#dia').append('<option value="todos">Todos</option>');

            $.ajax({
                type: "POST",
                url: "/api/ajax?class=SelectMonitoriasRealizadas.php",
                dataType: "json",
                data: {
                    canal_atendimento: 2,
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

        }else if(mes == 'anterior'){
            
            dias = getDaysInMonth(m, y);

            $('#dia').empty();
            $('#dia').append('<option value="todos">Todos</option>');

            $.ajax({
                type: "POST",
                url: "/api/ajax?class=SelectMonitoriasRealizadas.php",
                dataType: "json",
                data: {
                    canal_atendimento: 2,
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
    function relatorio_faturados($mes, $dia, $tipo_atendimento, $id_monitoria_mes, $id_usuario, $token){
    
        if ($tipo_atendimento == 2) {
            $filtro_tipo_atendimento = " AND via_texto = 1";
            $filtro_legenda = 'Via texto';
        }

        if ($dia != 'todos') {

            if ($mes == 'atual') {

                $filtro_data = " AND a.data_inicio >= '".substr(getDataHora('data'), 0, -2)."$dia 00:00:00' AND a.data_inicio <= '".substr(getDataHora('data'), 0, -2)."$dia 23:59:59' ";

            } else if ($mes == 'anterior') {

                $dataprimeirodia = new DateTime(getDataHora());
                $dataprimeirodia->modify('first day of last month');

                $mesanterior = $dataprimeirodia->format("Y-m-").$dia;

                $filtro_data = " AND a.data_inicio >= '".$mesanterior." 00:00:00' AND a.data_inicio <= '".$mesanterior." 23:59:59' ";
            }

        } else {

            if ($mes == 'atual') {

                $dataultimodia = new DateTime(getDataHora());
                $dataultimodia->modify('last day of this month');

                $dataprimeirodia = new DateTime(getDataHora());
                $dataprimeirodia->modify('first day of this month');

                $filtro_data = " AND a.data_inicio >= '".$dataprimeirodia->format("Y-m-d")." 00:00:00' AND a.data_inicio <= '".$dataultimodia->format("Y-m-d")." 23:59:59' ";

            } else if($mes == 'anterior') {

                $dataultimodia = new DateTime(getDataHora());
                $dataultimodia->modify('last day of last month');

                $dataprimeirodia = new DateTime(getDataHora());
                $dataprimeirodia->modify('first day of last month');

                $filtro_data = " AND a.data_inicio >= '".$dataprimeirodia->format("Y-m-d")." 00:00:00' AND a.data_inicio <= '".$dataultimodia->format("Y-m-d")." 23:59:59' ";
            }
        }
    
        $dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao WHERE a.gravado = '1' AND a.falha != 2 $filtro_data AND via_texto = 1 AND id_usuario = $id_usuario ORDER BY a.data_inicio ASC", "a.*, c.nome");
    
        if ($dados) {

            echo '<div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm form-control pull-right" name="gerar" id="gerar" value="1" type="button" data-toggle="modal" data-target="#myModal" style="max-height: 30px;">
                            <i class="fa fa-bug"></i> Ver Reclamações/Erros
                        </button>
                    </div>
                  </div>';

            echo '<div class="table-resposive">
                    <table class="table table-hover">
                        <thead>
                            <th>Data</th>
                            <th>Empresa</th>
                            <th>Protocolo</th>
                            <th>Assinante</th>
                            <th>Contato</th>
                            <th>Telefone (1/2)</th>
                            <th>OS</th>
                            <th>Avaliar</th>
                        </thead>
                        <tbody>';

            foreach ($dados as $dado) {
                $id_atendimento = $dado['id_atendimento'];

                $conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");
    
                if ($conteudo_empresa[0]['nome_contrato']) {
                    $nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
                } else {
                    $nome_contrato = '';
                }
    
                $contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;
    
                $nome_empresa = $contrato;
    
                if ($dado['fone2']) {
                    $legenda_telefone = $dado['fone1'].' | '.$dado['fone1'];
                } else {
                    $legenda_telefone = $dado['fone1'];
                }

                $legenda_protocolo = $dado['protocolo'];

                $os = nl2br($dado['os']).'<br>'.nl2br($dado['nome']);

                $verifica_atendimento = DBRead('', 'tb_monitoria_avaliacao_texto', "WHERE id_atendimento = '$id_atendimento' AND considerar = 1", 'id_monitoria_avaliacao_texto');

                ?>
                    <tr>
                        <td><?=  converteDataHora($dado['data_fim']) ?></td>
                        <td><?= $nome_empresa ?></td>
                        <td><?= $legenda_protocolo ?></td>
                        <td><?= $dado['assinante'] ?></td>
                        <td><?= $dado['contato'] ?></td>
                        <td><?= $legenda_telefone ?></td>
                        <td data-toggle="popover" data-html="true" data-placement="top" data-trigger="focus" title="" data-content="<?= $os?>" data-toggle="popover" data-placement="bottom" data-html="true"><?= limitarTexto($os, 60) ?> <i class="far fa-question-circle"></i></td>

                        <?php if ($verifica_atendimento) { ?>
                            <td><i class="fa fa-check-circle" style="font-size: 17px; color: green; margin-left: 10px;" title="Atendimento já avaliado!"></i></td>

                        <?php } else { ?>
<<<<<<< HEAD
                            <td><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-avaliacao-texto-form&id_usuario=<?=$id_usuario?>&id_monitoria_mes=<?=$id_monitoria_mes?>&id_atendimento=<?=$id_atendimento?>" style="margin-left: 10px;"><i class="fa fa-gavel" style="font-size: 17px;"></i></a></td>

=======
                            <td><a href="/api/iframe?token=<?php echo $token ?>&view=monitoria-avaliacao-texto-form&id_usuario=<?=$id_usuario?>&id_monitoria_mes=<?=$id_monitoria_mes?>&id_atendimento=<?=$id_atendimento?>" style="margin-left: 10px;"><i class="fa fa-gavel" style="font-size: 17px;"></i></a></td>
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                        <?php } ?>
                       
                    </tr>

                <?php 
            }
            
            echo '</tbody>
                </table>
            </div>';

        } else {
    
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
    }
?>