<?php
require_once(__DIR__."/../class/System.php");

    $diasemana = array(
        "0" => "Domingo",
        "1" => "Segunda",
        "2" => "Terça",
        "3" => "Quarta",
        "4" => "Quinta",
        "5" => "Sexta",
        "6" => "Sábado",
    );

    $data = date('Y-m-d');
    $data_da_consulta = explode('-', $data);
    $data_da_consulta = $data_da_consulta[0].'-'.$data_da_consulta[1].'-01';
    $hora_agora = date('H:i');
    // $hora_agora = '21:15';
    $diasemana_numero = date('w', strtotime($data));
    $data_hora = date('Y-m-d H:i:s');
    $diasemana_numero = $diasemana[$diasemana_numero];
    $data_de_hoje = explode(' ', getDataHora());
    $data_de_hoje = $data_de_hoje[0];

    if($diasemana_numero == 'Domingo'){
        $chave_nome_inicial = 'inicial_dom';
        $chave_nome_final = 'final_dom';
    }else if($diasemana_numero == 'Sábado'){
        $chave_nome_inicial = 'inicial_sab';
        $chave_nome_final = 'final_sab';
    }else{
        $chave_nome_inicial = 'inicial_seg';
        $chave_nome_final = 'final_seg';
    }
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Controle de Intervalos - <?= converteData($data) ?></h3>
                </div>
                <div class="panel-body">                 
                    <?php

                        $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario WHERE a.id_perfil_sistema = '3' AND b.status != 2 AND a.status = 1 AND data_inicial = '".$data_da_consulta."' ORDER BY c.".$chave_nome_final." ASC" , "a.id_usuario, b.nome");

                        if (!$dados) {
                            echo "<p class='alert alert-warning' style='text-align: center'>";
                                echo "Não foram encontrados registros!";
                            echo "</p>";
                        }else{
                            
                            echo '<table class="table table-hover dataTable" style="margin-bottom:0;">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Nome</th>";
                                        echo "<th class=\"text-center\">Horário Inicial</th>";
                                        echo "<th class=\"text-center\">Horário Final</th>";
                                        echo "<th class=\"text-center\">Folga na Semana</th>";
                                        echo "<th class=\"text-center\">Folga no Domingo</th>";
                                        echo "<th class=\"text-center\">Opções</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                            foreach ($dados as $atendente) {
                              
                                $id = $atendente['id_usuario'];
                                $dados_horario = DBRead('', 'tb_horarios_escala',"WHERE id_usuario = '".$id."' AND data_inicial = '".$data_da_consulta."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data."' AND data_ate >= '".$data."')");
                                $dados_folga_domingo = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."'");

                                if($diasemana_numero == 'Domingo'){
                                    $dados_folga_domingo_hoje = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' AND dia = '".$data_de_hoje."'");
                                }

                                if(!$dados_folga_domingo_hoje){
                                    
                                    $dados_horario_especial = DBRead('', 'tb_horarios_especiais',"WHERE dia = '".$data."' AND id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' ");

                                    if($dados_horario_especial){

                                        $hora_inicial_consulta = $data.' '.$dados_horario_especial[0]['inicial_especial'].':00';    
                                        $hora_final_consulta = $data.' '.$dados_horario_especial[0]['final_especial'].':00';    

                                        if($dados_horario_especial[0]['inicial_especial'] > $dados_horario_especial[0]['final_especial']){
                                            //AQUI HABILITA OS INVERTIDOS
                                            $dados_visualizar_intervalo = DBRead('', 'tb_horarios_especiais',"WHERE dia = '".$data."' AND id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' AND (inicial_especial <= '".$hora_agora."' OR final_especial >= '".$hora_agora."') ");

                                            //VERIFICA SE ESTAVA DE INTERVALO ONTEM E HOJE
                                            if($hora_agora < '11:00'){
                                                $hora_inicial_consulta = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($hora_inicial_consulta)));
                                                $hora_final_consulta = date('Y-m-d H:i:s', strtotime("-0 days",strtotime($hora_final_consulta)));
                                                $dados_intervalo = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial BETWEEN '".$hora_inicial_consulta."' AND '".$hora_final_consulta."' ");
                                            
                                            //VERIFICA SE ESTAVA DE INTERVALO HOJE E AMANHA
                                            }else{
                                                $hora_inicial_consulta = date('Y-m-d H:i:s', strtotime("-0 days",strtotime($hora_inicial_consulta)));
                                                $hora_final_consulta = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($hora_final_consulta)));
                                                $dados_intervalo = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial BETWEEN '".$hora_inicial_consulta."' AND '".$hora_final_consulta."' ");
                                            }

                                        }else{

                                            $dados_intervalo = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial BETWEEN '".$hora_inicial_consulta."' AND '".$hora_final_consulta."' AND data_final IS NOT NULL");

                                            $dados_visualizar_intervalo = DBRead('', 'tb_horarios_especiais',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' AND inicial_especial <= '".$hora_agora."' AND final_especial >= '".$hora_agora."' AND dia = '".$data."' ");
                                        }

                                        if($dados_visualizar_intervalo && !$dados_intervalo){
                                        //AQUI DEIXA VERDE

                                            $dados_agendamento = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial > '".getDataHora()."' AND data_final IS NULL");

                                            if(!$dados_agendamento){
                                            
                                                $cor_botao2 = 'warning';
                                                $frase_botao2 = "Agendar <i class='fa fa-calendar'></i>";
                                                $tooltip ="";
                                                $tooltip2 = "";                                            

                                            }else{
                                            
                                                $cor_botao2 = 'primary';
                                                $frase_botao2 = "Agendado <i class='fa fa-hourglass-start'></i>";
                                                $tooltip ="<a data-toggle='tooltip' data-placement='right' title='Agendado para ".converteDataHora($dados_agendamento[0]['data_inicial'])."'>";
                                                $tooltip2 = "</a>";
                                            }

                                            $dados_intervalo_agora = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial IS NOT NULL AND data_final IS NULL ");

                                            if($dados_intervalo_agora){
                                                $cor_botao = 'success';
                                                $frase_botao = 'Concluir Intervalo <i class="fa fa-check-circle-o"></i>';
                                                $valor_botao = $dados_intervalo_agora[0]['data_final'];

                                                $HoraIni = strtotime($data_hora);
                                                $HoraFim = strtotime($dados_intervalo_agora[0]['data_final']);
                                                $result = $HoraFim - $HoraIni;

                                                $botao_de_agendamento = "";
                                                $width = "style=' min-width: 80%'";
                                            }else{
                                                $cor_botao = 'info';
                                                $frase_botao = 'Liberar Intervalo <i class="fa fa-coffee"></i>';
                                                $valor_botao = '';

                                                $botao_de_agendamento = $tooltip."<button class='btn btn-sm btn-".$cor_botao2." botao_agendamento' style=' min-width: 40%;' cor='btn-".$cor_botao2."' id='ag_".$id."' ".$disabled." >".$frase_botao2." </button>".$tooltip2;
                                                $width = "style=' min-width: 40%;'";                                            
                                            }

                                                $hora1 = $dados_horario_especial[0]['inicial_especial'];
                                                $hora2 = $dados_horario_especial[0]['final_especial'];
                                                if($hora2<$hora1){
                                                    
                                                    $hora1 = '2000-10-10 '.$hora1.':00';
                                                    $hora2 = '2000-10-11 '.$hora2.':00';
                                                    $data1 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora1)));
                                                    $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora2)));
                                                    $idade = strtotime($data2) - strtotime($data1);

                                                }else{
                                                    $hora1 = strtotime(''.$dados_horario_especial[0]['inicial_especial'].'');
                                                    $hora2 = strtotime(''.$dados_horario_especial[0]['final_especial'].'');
                                                    $idade = ($hora2-$hora1);
                                                }
                                                
                                                $h = ($idade/(60*60))%24;

                                                if($h){
                                                    if($h >= '4' && $dados_visualizar_intervalo){
                                                        echo "<tr>";    
                                                            echo "<td><span class = 'nome_atendente'>".$atendente['nome']."</span> <strong>(Horário Especial)</strong></td>";
                                                            echo "<td class=\"text-center\"><span class = 'inicial_atendente'>".$dados_horario_especial[0]['inicial_especial']."</span></td>";
                                                            echo "<td class=\"text-center\"><span class = 'final_atendente'>".$dados_horario_especial[0]['final_especial']."</span></td>";
                                                            echo "<td class=\"text-center\">".$dados_horario[0]['folga_seg']."</td>";
                                                            if($dados_folga_domingo){
                                                                echo "<td class=\"text-center\">";
                                                                $domingos = '';
                                                                foreach ($dados_folga_domingo as $folga_domingo) {
                                                                    $domingos = $domingos.' '.converteData($folga_domingo['dia']).',';                  
                                                                }
                                                                $domingos = substr_replace($domingos, ' ', -1);
                                                                echo $domingos;
                                                                echo "</td>";
                                                            }else{
                                                                echo "<td class=\"text-center\"></td>";                        
                                                            }                     
                                                            echo "<td class='text-center'>
                                                                    <button class='btn btn-sm btn-".$cor_botao." botao_1' ".$width." cor='btn-".$cor_botao."' id='id_".$id."' value='".$valor_botao."' >".$frase_botao."</button> ";

                                                                    echo $botao_de_agendamento;
                                                                    
                                                            echo "</td>";  
                                                        echo "</tr>";  
                                                    }else{

                                                        echo "<tr>";    
                                                            echo "<td><span class = 'nome_atendente'>".$atendente['nome']."</span> <strong>(Horário Especial)</strong></td>";
                                                            echo "<td class=\"text-center\"><span class = 'inicial_atendente'>".$dados_horario_especial[0]['inicial_especial']."</span></td>";
                                                            echo "<td class=\"text-center\"><span class = 'final_atendente'>".$dados_horario_especial[0]['final_especial']."</span></td>";
                                                            echo "<td class=\"text-center\">".$dados_horario[0]['folga_seg']."</td>";
                                                            if($dados_folga_domingo){
                                                                echo "<td class=\"text-center\">";
                                                                $domingos = '';
                                                                foreach ($dados_folga_domingo as $folga_domingo) {
                                                                    $domingos = $domingos.' '.converteData($folga_domingo['dia']).',';                  
                                                                }
                                                                $domingos = substr_replace($domingos, ' ', -1);
                                                                echo $domingos;
                                                                echo "</td>";
                                                            }else{
                                                                echo "<td class=\"text-center\"></td>";                        
                                                            }                     
                                                            echo "<td class='text-center'>
                                                                    <button class='btn btn-sm btn-danger' style=' min-width: 80%' disabled >Meio Turno</button> ";
                                                            echo "</td>";
                                                        echo "</tr>";

                                                    }
                                                }
                                                
                                        }
                                        
                                    }else{

                                        $hora_inicial_consulta = $data.' '.$dados_horario[0][$chave_nome_inicial].':00';    
                                        $hora_final_consulta = $data.' '.$dados_horario[0][$chave_nome_final].':00';    
                                    

                                        if($dados_horario[0][$chave_nome_inicial] > $dados_horario[0][$chave_nome_final]){
                                        //AQUI HABILITA OS INVERTIDOS
                                        $dados_visualizar_intervalo = DBRead('', 'tb_horarios_escala',"WHERE  id_usuario = '".$id."' AND (".$chave_nome_inicial." <= '".$hora_agora."' OR ".$chave_nome_final." >= '".$hora_agora."') AND data_inicial = '".$data_da_consulta."' ");

                                        //VERIFICA SE ESTAVA DE INTERVALO ONTEM E HOJE
                                        if($hora_agora < '11:00'){
                                            $hora_inicial_consulta = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($hora_inicial_consulta)));
                                            $hora_final_consulta = date('Y-m-d H:i:s', strtotime("-0 days",strtotime($hora_final_consulta)));
                                            $dados_intervalo = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial BETWEEN '".$hora_inicial_consulta."' AND '".$hora_final_consulta."' ");
                                        
                                        //VERIFICA SE ESTAVA DE INTERVALO HOJE E AMANHA
                                        }else{
                                            $hora_inicial_consulta = date('Y-m-d H:i:s', strtotime("-0 days",strtotime($hora_inicial_consulta)));
                                            $hora_final_consulta = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($hora_final_consulta)));
                                            $dados_intervalo = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial BETWEEN '".$hora_inicial_consulta."' AND '".$hora_final_consulta."' ");
                                            }
                                        }else{

                                            $dados_intervalo = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial BETWEEN '".$hora_inicial_consulta."' AND '".$hora_final_consulta."' AND data_final IS NOT NULL");

                                            $dados_visualizar_intervalo = DBRead('', 'tb_horarios_escala',"WHERE  id_usuario = '".$id."' AND ".$chave_nome_inicial." <= '".$hora_agora."' AND ".$chave_nome_final." >= '".$hora_agora."' AND data_inicial = '".$data_da_consulta."' ");
                                        }

                                        if($dados_visualizar_intervalo && !$dados_intervalo){
                                        //AQUI DEIXA VERDE
                                            
                                            $dados_agendamento = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial > '".getDataHora()."' AND data_final IS NULL");

                                            if(!$dados_agendamento){
                                            
                                                $cor_botao2 = 'warning';
                                                $frase_botao2 = "Agendar <i class='fa fa-calendar'></i>";
                                                $tooltip ="";
                                                $tooltip2 = "";                                            

                                            }else{
                                            
                                                $cor_botao2 = 'primary';
                                                $frase_botao2 = "Agendado <i class='fa fa-hourglass-start'></i>";
                                                $tooltip ="<a data-toggle='tooltip' data-placement='right' title='Agendado para ".converteDataHora($dados_agendamento[0]['data_inicial'])."'>";
                                                $tooltip2 = "</a>";
                                            }

                                            $dados_intervalo_agora = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id."' AND data_inicial IS NOT NULL AND data_inicial <= '".getDataHora()."' AND data_final IS NULL ");
                                            
                                            if($dados_intervalo_agora){
                                                $cor_botao = 'success';
                                                $frase_botao = 'Concluir Intervalo <i class="fa fa-check-circle-o"></i>';

                                                $valor_botao = $dados_intervalo_agora[0]['data_final'];

                                                $HoraIni = strtotime($data_hora);
                                                $HoraFim = strtotime($dados_intervalo_agora[0]['data_final']);
                                                $result = $HoraFim - $HoraIni;
                                                
                                                $botao_de_agendamento = "";
                                                $width = "style=' min-width: 81%'";
                                            }else{
                                                $cor_botao = 'info';
                                                $valor_botao = '';
                                                $frase_botao = 'Liberar Intervalo <i class="fa fa-coffee"></i>';

                                                $botao_de_agendamento = $tooltip."<button class='btn btn-sm btn-".$cor_botao2." botao_agendamento' style=' min-width: 40%; max-width: 40%;' cor='btn-".$cor_botao2."' id='ag_".$id."' ".$disabled." >".$frase_botao2." </button>".$tooltip2;
                                                $width = "style=' min-width: 40%; max-width: 40%;'";

                                            }

                                            if($dados_horario[0]['folga_seg'] != $diasemana_numero){

                                                $hora1 = $dados_horario[0][$chave_nome_inicial];
                                                $hora2 = $dados_horario[0][$chave_nome_final];
                                                if($hora2<$hora1){
                                                    
                                                    $hora1 = '2000-10-10 '.$hora1.':00';
                                                    $hora2 = '2000-10-11 '.$hora2.':00';
                                                    $data1 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora1)));
                                                    $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora2)));
                                                    $idade = strtotime($data2) - strtotime($data1);

                                                }else{
                                                    $hora1 = strtotime(''.$dados_horario[0][$chave_nome_inicial].'');
                                                    $hora2 = strtotime(''.$dados_horario[0][$chave_nome_final].'');
                                                    $idade = ($hora2-$hora1);
                                                }
                                                
                                                $h = ($idade/(60*60))%24;
                                                if($h){
                                                    if($h >= '4' && $dados_visualizar_intervalo){
                                                        echo "<tr>"; 
                                                            echo "<td><span class = 'nome_atendente'>".$atendente['nome']."</span></td>";
                                                            echo "<td class=\"text-center\"><span class = 'inicial_atendente'>".$dados_horario[0][$chave_nome_inicial]."</span></td>";
                                                            echo "<td class=\"text-center\"><span class = 'final_atendente'>".$dados_horario[0][$chave_nome_final]."</span></td>";
                                                            echo "<td class=\"text-center\">".$dados_horario[0]['folga_seg']."</td>";                     

                                                            if($dados_folga_domingo){
                                                                echo "<td class=\"text-center\">";
                                                                $domingos = '';
                                                                foreach ($dados_folga_domingo as $folga_domingo) {
                                                                    $domingos = $domingos.' '.converteData($folga_domingo['dia']).',';                  
                                                                }
                                                                $domingos = substr_replace($domingos, ' ', -1);
                                                                echo $domingos;
                                                                echo "</td>";
                                                            }else{
                                                                echo "<td class=\"text-center\"></td>";                        
                                                            }

                                                            echo "<td class='text-center'>
                                                                    <button class='btn btn-sm btn-".$cor_botao." botao_1' ".$width." cor='btn-".$cor_botao."' id='id_".$id."' value='".$valor_botao."' >".$frase_botao."</button> ";

                                                                    echo $botao_de_agendamento;
                                                                    
                                                            echo "</td>";

                                                        echo "</tr>";  
                                                    }else{

                                                        echo "<tr>"; 
                                                            echo "<td><span class = 'nome_atendente'>".$atendente['nome']."</span></td>";
                                                            echo "<td class=\"text-center\"><span class = 'inicial_atendente'>".$dados_horario[0][$chave_nome_inicial]."</span></td>";
                                                            echo "<td class=\"text-center\"><span class = 'final_atendente'>".$dados_horario[0][$chave_nome_final]."</span></td>";
                                                            echo "<td class=\"text-center\">".$dados_horario[0]['folga_seg']."</td>";                     

                                                            if($dados_folga_domingo){
                                                                echo "<td class=\"text-center\">";
                                                                $domingos = '';
                                                                foreach ($dados_folga_domingo as $folga_domingo) {
                                                                    $domingos = $domingos.' '.converteData($folga_domingo['dia']).',';                  
                                                                }
                                                                $domingos = substr_replace($domingos, ' ', -1);
                                                                echo $domingos;
                                                                echo "</td>";
                                                            }else{
                                                                echo "<td class=\"text-center\"></td>";                        
                                                            }

                                                            echo "<td class='text-center'>
                                                                    <button class='btn btn-sm btn-danger' style=' min-width: 81%' disabled >Meio Turno</button> ";
                                                            echo "</td>";

                                                        echo "</tr>";  

                                                    }
                                                }
                                                
                                            }   
                                        }
                                    }   
                                }
                            }

                                echo "</tbody>";
                            echo "</table>";
                       
                        }

                    ?> 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalAgendar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agendar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="getdatahora" value="<?= getDataHora() ?>"/>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Data:</label>
                            <input type="text" class="form-control input-sm date calendar hasDatepicker" id="data_agendamento" name="data_agendamento" autocomplete="off" value="<?= converteData(getDataHora('data')) ?>">
                        </div>
                    </div><!-- end col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>*Hora:</label>
                            <input type="time" class="form-control input-sm" id="hora_agendamento" value="" name="hora_agendamento" autocomplete="off" autofocus>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
            <div class="modal-footer">
                <button name="salvar_agendamento" id="salvar_agendamento" value="" class="btn btn-primary"><i class="fa fa-check"></i> Ok</button>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function(){
    
    $('.dataTable').DataTable({ 
         "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
        },
        
        aaSorting: [[1, 'asc']],
        "searching": false,
        "paging":   false,
        "info":     false
    });

    $.ajax({
      url: "/api/ajax?class=Intervalo.php",
      dataType: "json",
      method: 'POST',
      data: {
          acao: 'verifica_intervalo_aberto',
          token: '<?= $request->token ?>'
      },
    });
});

$(document).on('click', '.botao_1', function(){
    
    var botao = $(this);
    var texto_botao = botao.html().split(" ")[0];

    var hoje = new Date();
    var ano = hoje.getFullYear();
    var mes = hoje.getMonth();
        mes = mes+1;
    var dia = hoje.getDate();
    var hora = hoje.getHours();
    // var hora = 21;
    var minutos = hoje.getMinutes();
    // var minutos = 15;
    var segundos = hoje.getSeconds();

    var id_usuario = $(this).attr("id").split("id_")[1];
    
    var data_inicial = ano+'-'+mes+'-'+dia+' '+hora+':'+minutos+':'+segundos;

    var nome_usuario = $(this).parent().parent().find('.nome_atendente').text();
    
    var botao_agendamento = $('#ag_'+id_usuario+'');            
    
    if(texto_botao == "Concluir"){
        
        var data_final = data_inicial;
        $.ajax({
          url: "/api/ajax?class=Intervalo.php",
          dataType: "json",
          method: 'POST',
          data: {
              acao: 'verificar',
              parametros : {
                'data_inicial': data_inicial, 
                'id_usuario': id_usuario
              },
              token: '<?= $request->token ?>'
          },
       
            success: function (data) {

                if(data == 1){
                    alert('Este usuário já fez intervalo!');
                    botao.closest("tr").remove();
                    botao_agendamento.remove();

                }else{
                    if (!confirm('Você deseja que '+nome_usuario+' conclua o intervalo?')){
                        return false; 
                    }else{
                        $.ajax({
                          url: "/api/ajax?class=Intervalo.php",
                          dataType: "json",
                          method: 'POST',
                          data: {
                              acao: 'inserir_final',
                              parametros : {
                                'data_final': data_final, 
                                'id_usuario': id_usuario
                              },
                              token: '<?= $request->token ?>'
                          },
                        });
                        botao.closest("tr").remove();
                        botao_agendamento.remove();
                    }
                }
                $(this).closest("tr").remove();
            }
        });
            
    }else{
        var inicial_atendente = $(this).parent().parent().find('.inicial_atendente').text();
        var inicial_hora_atendente = inicial_atendente.split(":")[0];
        var inicial_minutos_atendente = inicial_atendente.split(":")[1];
            
            
        var final_atendente = $(this).parent().parent().find('.final_atendente').text();
        var final_hora_atendente = final_atendente.split(":")[0];
        var final_minutos_atendente = final_atendente.split(":")[1];
            
            
        if(hora <10){
            hora = '0'+hora;
        }
        if(minutos <10){
            minutos = '0'+minutos;
        }
        var hora_agora  = hora+':'+minutos;

        if(inicial_atendente <= final_atendente){
            
            inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
            if(inicial_hora_atendente <10){
                inicial_hora_atendente = '0'+inicial_hora_atendente;
            }
            inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;
            
            final_hora_atendente = final_hora_atendente-1;
            if(final_hora_atendente <10){
                final_hora_atendente = '0'+final_hora_atendente;
            }
            final_atendente = final_hora_atendente+':'+final_minutos_atendente;

            if(final_atendente < hora_agora || inicial_atendente > hora_agora){
                if(final_atendente < hora_agora){
                    alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala!');
                    return false;
                }else{
                    alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala!');
                    return false;
                }
            }
        }else{
            // hora: 00:15 - - - - inicial_atendente: 23:30
            if(hora == '00' && inicial_hora_atendente == 23 ){
                var altera_hora_00 = "00:"+inicial_minutos_atendente;
                if(hora_agora <= altera_hora_00){
                    alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala!');
                    return false;
                }
            // hora: 00:15 - - - - final_atendente: 00:30
            }else if(hora == '00' && final_hora_atendente == 00 ){
                alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala!');
                return false;

            // hora: 21:15 - - - - inicial_atendente: 20:30
            }else if(hora >= inicial_hora_atendente){
                inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
                if(inicial_hora_atendente <10){
                    inicial_hora_atendente = '0'+inicial_hora_atendente;
                }
                inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;
                if(inicial_atendente > hora_agora){
                    alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala!');
                    return false;
                }

            // hora: 02:15 - - - - final_atendente: 02:30
            }else if(hora <= final_hora_atendente){
                final_hora_atendente = final_hora_atendente-1;
                if(final_hora_atendente <10){
                    final_hora_atendente = '0'+final_hora_atendente;
                }
                final_atendente = final_hora_atendente+':'+final_minutos_atendente;
                if(final_atendente < hora_agora){
                    alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala!dasdasda');
                    return false;
                }
            }
        }

        $.ajax({
        url: "/api/ajax?class=Intervalo.php",
        dataType: "json",
        method: 'POST',
        data: {
            acao: 'verificar',
            parametros : {
                'data_inicial': data_inicial, 
                'id_usuario': id_usuario
            },
            token: '<?= $request->token ?>'
        },
    
            success: function (data) {

                if(data == 0){
                    if (!confirm('Você deseja liberar '+nome_usuario+' para o intervalo?')){
                        return false; 
                    }
                    botao.html("Concluir Intervalo <i class='fa fa-check-circle-o'></i>");
                    cor = botao.attr('cor');
                    botao.css({
                        "min-width": "80%"
                    });
                    botao.removeClass(cor);
                    botao.addClass('btn-success');
                    botao.attr('cor','btn-success');
                    botao_agendamento.remove();

                    $.ajax({
                    url: "/api/ajax?class=Intervalo.php",
                    dataType: "json",
                    method: 'POST',
                    data: {
                        acao: 'inserir_inicial',
                        parametros : {
                            'data_inicial': data_inicial, 
                            'id_usuario': id_usuario
                        },
                        token: '<?= $request->token ?>'
                    },
                    });

                }else if (data == 1){
                    alert('Este usuário já fez intervalo!');
                    botao.closest("tr").remove();
                    botao_agendamento.remove();

                }else if (data == 2){
                    alert('Este usuário já está em intervalo!');
                    botao.html("Concluir Intervalo <i class='fa fa-check-circle-o'></i>");
                    cor = botao.attr('cor');
                    botao.removeClass(cor);
                    botao.addClass('btn-success');
                    botao.attr('cor','btn-success');
                    botao_agendamento.remove();

                }
            }
        });            
    }
});

$(document).on('click', '.botao_agendamento', function(){
    var id_usuario = $(this).attr("id").split("ag_")[1];
    
    var botao_agendamento = $('#ag_'+id_usuario+'');            
    var botao = $('#id_'+id_usuario+'');   
    
    var d = new Date();
    var data_hora_agora = d.getFullYear() + '-' + ((d.getMonth()+1)<10 ? '0' : '') + (d.getMonth()+1) + '-' +((d.getDate())<10 ? '0' : '') + (d.getDate() + ' ' +(d.getHours()<10 ? '0' : '') + d.getHours()+ ':' +(d.getMinutes()<10 ? '0' : '') + d.getMinutes());
    
    if($(this).text() == "Agendado  "){
        var nome_usuario = $(this).parent().parent().parent().find('.nome_atendente').text();
    }else{
        var nome_usuario = $(this).parent().parent().find('.nome_atendente').text();
    }
    
     $.ajax({
        url: "/api/ajax?class=Intervalo.php",
        dataType: "json",
        method: 'POST',
        data: {
            acao: 'verificar',
            parametros : {
              'data_inicial': data_hora_agora, 
              'id_usuario': id_usuario
            },
            token: '<?= $request->token ?>'
        },
       
        success: function (data) {

            if (data == 1){
                alert('Este usuário já fez intervalo!');
                botao.closest("tr").remove();
                botao_agendamento.remove();


            }else if (data == 2){
                alert('Este usuário já está em intervalo!');
                botao.html("Concluir Intervalo <i class='fa fa-check-circle-o'></i>");
                botao.css({
                    "min-width": "300px"
                });
                cor = botao.attr('cor');
                botao.removeClass(cor);
                botao.addClass('btn-success');
                botao.attr('cor','btn-success');
                botao_agendamento.remove();
            }else{
                $.ajax({
                    url: "/api/ajax?class=Intervalo.php",
                    dataType: "json",
                    method: 'POST',
                    data: {
                        acao: 'verifica_agendamento',
                        parametros : {
                          'id_usuario': id_usuario
                        },
                        token: '<?= $request->token ?>'
                    },
                   
                    success: function (data) {

                        if(data == 0){
                            if (!confirm('Confirmar agendamento de intervalo para '+nome_usuario+'?')){
                                $('#modalAgendar').modal('hide');
                            }else{
                                $('#modalAgendar').modal('show');
                            }
                        }else if (data == 1){
                            if (!confirm(nome_usuario+' já possui um intervalo agendado! Confirmar ALTERAÇÂO de agendamento?')){
                                location.reload();                                
                            }else{
                                $('#modalAgendar').modal('show');
                            }
                        }
                    }
                });
            }
        }
    });

    $('#salvar_agendamento').val(id_usuario+'_'+nome_usuario);

});

$(document).on('click', '#salvar_agendamento', function(){
    
    var id_usuario = $(this).val().split("_")[0];
    var nome_usuario = $(this).val().split("_")[1];
    var data_agendamento = $("input[name=data_agendamento]").val();
    var hora_agendamento = $('#hora_agendamento').val();
    var botao_agendamento = $('#ag_'+id_usuario+'');            

    var d = new Date();
    var data_hora_agora = d.getFullYear() + '-' + ((d.getMonth()+1)<10 ? '0' : '') + (d.getMonth()+1) + '-' +((d.getDate())<10 ? '0' : '') + (d.getDate() + ' ' +(d.getHours()<10 ? '0' : '') + d.getHours()+ ':' +(d.getMinutes()<10 ? '0' : '') + d.getMinutes());

    var ano_agenda = data_agendamento.split("/")[2];
    var mes_agenda = data_agendamento.split("/")[1];
    var dia_agenda = data_agendamento.split("/")[0];

    data_agenda = ano_agenda + '-' + mes_agenda + '-' + dia_agenda + ' ' + hora_agendamento;

    if((new Date(data_agenda).getTime() < new Date(data_hora_agora).getTime())){
        alert('A data do agendamento deve ser maior que a data de agora!');
        $("#hora_agendamento").focus();
        return false;

    }
    if(!data_agendamento){
        alert('A data do agendamento deve ser preenchida!');
        $("#data_agendamento").focus();
        return false;

    }else if(!hora_agendamento){
        alert('A hora do agendamento deve ser preenchida!');
        $("#hora_agendamento").focus();
        return false;
    }
        
    $.ajax({
        url: "/api/ajax?class=Intervalo.php",
        dataType: "json",
        method: 'POST',
        data: {
            acao: 'verificar',
            parametros : {
              'data_inicial': data_hora_agora, 
              'id_usuario': id_usuario
            },
            token: '<?= $request->token ?>'
        },
       
        success: function (data) {

            if(data == 0){
                $.ajax({
                    url: "/api/ajax?class=Intervalo.php",
                    dataType: "json",
                    method: 'POST',
                    data: {
                        acao: 'verifica_agendamento',
                        parametros : {
                          'id_usuario': id_usuario
                        },
                        token: '<?= $request->token ?>'
                    },
                   
                    success: function (data) {
                        $.ajax({
                            url: "/api/ajax?class=Intervalo.php",
                            dataType: "json",
                            method: 'POST',
                            data: {
                                acao: 'cadastrar_agendamento',
                                parametros : {
                                  'data_agendamento': data_agendamento, 
                                  'hora_agendamento': hora_agendamento, 
                                  'id_usuario': id_usuario
                                },
                                token: '<?= $request->token ?>'
                            },
                            success: function (data) {

                                if(data){

                                    botao_agendamento.replaceWith('<a data-toggle="tooltip" data-placement="right" title ="Agendado para '+data+'"><button class="btn btn-sm btn-primary botao_agendamento" style=" min-width: 40%;" data-toggle="modal" data-target="#modalAgendar" cor="btn-primary" id="ag_'+id_usuario+'">Agendado <i class="fa fa-hourglass-start"></i> </button></a>');

                                    $('[data-toggle="tooltip"]').tooltip()

                                }
                            }

                        });
                    }
                });
            }
        }
    });     
    

   $("input[name=data_agendamento]").val('<?= converteData(getDataHora('data')) ?>');
   $('#hora_agendamento').val('');
   $('#modalAgendar').modal('hide');

});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

</script>
