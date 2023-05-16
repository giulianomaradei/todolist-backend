<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$canal_atendimento = addslashes($parametros['canal_atendimento']);
$plano = addslashes($parametros['plano']);
$data = addslashes($parametros['data']);
$hora = addslashes($parametros['hora']);

if($canal_atendimento != 'qualquer'){
    $inner_canal_atendimento = "INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa";
    $filtro_canal_atendimento = "AND d.atendimento_via_texto = '".($canal_atendimento == 'texto' ? '1' : '0')."'";
}else{
    $inner_canal_atendimento = "";
    $filtro_canal_atendimento = "";
} 

if($plano){
    $filtro_plano = "AND b.id_plano = '$plano'";
}else{
    $filtro_plano = ""; 
} 

if($data){
    $data_agora = converteData($data);
}else{
    $data_hora_agora = explode(' ', getDataHora());
    $data_agora = $data_hora_agora[0];  
}
    
if($hora){
    $hora_agora = $hora.":00";
}else{
    $data_hora_agora = explode(' ', getDataHora());
    $hora_agora = $data_hora_agora[1];
}
// $hora_agora = '17:30:00';
// $data_agora = '2020-12-05';

 
$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa $inner_canal_atendimento INNER JOIN tb_cidade e ON c.id_cidade = e.id_cidade INNER JOIN tb_estado f ON e.id_estado = f.id_estado WHERE b.cod_servico = 'call_suporte' AND a.status = 1 AND (c.nome LIKE '%$letra%' OR a.nome_contrato LIKE '%$letra%') $filtro_plano $filtro_canal_atendimento  ORDER BY c.nome ASC ", "a.id_contrato_plano_pessoa, c.nome, a.nome_contrato, c.id_cidade, f.id_estado");

$array_ativo = array();
$array_inativo = array();

$dia_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
$data_numero = date($data_agora);
$diasemana_numero = date('w', strtotime($data_numero));

// echo "DATA: ".$data_agora." - HORA: ".$hora_agora." - diasemana: ".$diasemana_numero." - DIA DA SEMANA: ".$dia_semana[$diasemana_numero]." ";

$data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data_agora)));
$data_ontem_numero = date($data_agora);
$diasemana_ontem_numero = date('w', strtotime($data_numero));

foreach ($dados_contrato as $conteudo_contrato) {
    
    $dados_feriado_municipal_estadual_nacional = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_agora, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $conteudo_contrato['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $conteudo_contrato['id_cidade'] . "'))");
    
    if($dados_feriado_municipal_estadual_nacional){

        $filtro_dia = " AND (a.dia = 4 OR a.dia = 5) ";

        //Segunda Ontem
        if($dia_semana[$diasemana_ontem_numero] == 'Segunda'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 7 OR a.dia = 13 ) ) "; 
        }
    
        //Terça Ontem
        else if($dia_semana[$diasemana_ontem_numero] == 'Terça'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 8 OR a.dia = 13 ) ) "; 
        }
    
        //Quarta Ontem
        else if($dia_semana[$diasemana_ontem_numero] == 'Quarta'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 9 OR a.dia = 13 ) ) "; 
        }
    
        //Quinta Ontem
        else if($dia_semana[$diasemana_ontem_numero] == 'Quinta'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 10 OR a.dia = 13 ) ) "; 
        }
    
        //Sexta Ontem
        else if($dia_semana[$diasemana_ontem_numero] == 'Sexta'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11 ) ) "; 
        }
    
        //Sábado Ontem
        else if($dia_semana[$diasemana_ontem_numero] == 'Sábado'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12 ) ) "; 
        }
    
        //Domingo Ontem
        else if($dia_semana[$diasemana_ontem_numero] == 'Domingo'){ 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 4 OR a.dia = 6 ) ) "; 
        }

    }else{            
        //Segunda
        if($dia_semana[$diasemana_numero] == 'Segunda'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 7) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 4 OR a.dia = 6 ) ) "; 
        }
    
        //Terça
        else if($dia_semana[$diasemana_numero] == 'Terça'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 8) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 7 OR a.dia = 13 ) ) "; 
        }
    
        //Quarta
        else if($dia_semana[$diasemana_numero] == 'Quarta'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 9) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 8 OR a.dia = 13 ) ) "; 
        }
    
        //Quinta
        else if($dia_semana[$diasemana_numero] == 'Quinta'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 10) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 9 OR a.dia = 13 ) ) "; 
        }
    
        //Sexta
        else if($dia_semana[$diasemana_numero] == 'Sexta'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 10 OR a.dia = 13 ) ) "; 
        }
    
        //Sábado
        else if($dia_semana[$diasemana_numero] == 'Sábado'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11 ) ) "; 
        }
    
        //Domingo
        else if($dia_semana[$diasemana_numero] == 'Domingo'){ 
            $filtro_dia = " AND (a.dia = 1 OR a.dia = 4 OR a.dia = 6) "; 
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12 ) ) "; 
        }
    }

    $dados_feriado_municipal_estadual_nacional_ontem = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_ontem, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $conteudo_contrato['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $conteudo_contrato['id_cidade'] . "'))");
        
    if($dados_feriado_municipal_estadual_nacional_ontem){
        $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim >= '".$hora_agora."' AND (a.dia = 4 OR a.dia = 5 ) ) "; 
    }

    $dados_horario_contrato = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = '".$conteudo_contrato['id_contrato_plano_pessoa']."' LIMIT 1", "id_contrato_plano_pessoa");
    if($dados_horario_contrato){
        // $dados_horario = DBRead('', 'tb_horario a', "INNER JOIN tb_horario_contrato b ON a.id_horario_contrato = b.id_horario_contrato INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE (b.tipo = 8 OR b.tipo = 9) AND ( (a.hora_inicio <= '".$hora_agora."' AND a.hora_fim >= '".$hora_agora."') OR (a.hora_inicio >= a.hora_fim AND (a.hora_inicio<= '".$hora_agora."' OR a.hora_fim >= '".$hora_agora."')) OR (a.hora_inicio >= a.hora_fim AND (a.hora_inicio<= '".$hora_agora."' OR a.hora_fim >= '".$hora_agora."') AND a.dia= 3) ) $filtro_dia AND b.id_contrato_plano_pessoa = '".$conteudo_contrato['id_contrato_plano_pessoa']."' ", "b.id_contrato_plano_pessoa, d.nome");
        
        $dados_horario = DBRead('', 'tb_horario a', "INNER JOIN tb_horario_contrato b ON a.id_horario_contrato = b.id_horario_contrato INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE (b.tipo = 8 OR b.tipo = 9) AND ( ( ( (a.hora_inicio <= '".$hora_agora."' AND a.hora_fim >= '".$hora_agora."') OR (a.hora_inicio >= a.hora_fim AND (a.hora_inicio<= '".$hora_agora."' OR a.hora_fim >= '".$hora_agora."') ) ) $filtro_dia) $filtro_ontem )  AND b.id_contrato_plano_pessoa = '".$conteudo_contrato['id_contrato_plano_pessoa']."' ", "b.id_contrato_plano_pessoa, d.nome");
  

        if($dados_horario){
            $array_ativo[$conteudo_contrato['nome']] = $conteudo_contrato['id_contrato_plano_pessoa'];
        }else{
            $array_inativo[$conteudo_contrato['nome']] = $conteudo_contrato['id_contrato_plano_pessoa'];
    
        }
    }
}


// $dia_semana_titulo = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
// echo '<h4 class="text-center pull-center col-md-12" style="margin-top: 2px; padding-bottom: 15px; padding-top: 15px;"><strong>'.$data.' ('.$dia_semana_titulo[$diasemana_numero].') - '.$hora.'</strong></h4>';

$tipo_dia_atendimento = array(
    "1" => "Seg. a Dom.",
    "2" => "Seg. a Sáb.",
    "3" => "Seg. a Sex.",
    "4" => "Dom. e Feriados",
    "5" => "Feriados",
    "6" => "Domingo",
    "7" => "Segunda",
    "8" => "Terça",
    "9" => "Quarta",
    "10" => "Quinta",
    "11" => "Sexta",
    "12" => "Sábado",
    "13" => "Seg. a Qui."
);

if($array_ativo){
    echo '<h3 class="panel-title text-left pull-left col-md-12" style="margin-top: 2px; padding-bottom: 15px;"><strong>Ativos:</strong></h3>';
    echo "<div class='row'>";
    
    foreach ($array_ativo as $nome => $id_contrato_plano_pessoa) {

        $dados_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.id_cidade, d.id_estado");

        $dados_feriado_municipal_estadual_nacional = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_agora, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $dados_pessoa[0]['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $dados_pessoa[0]['id_cidade'] . "'))");
        
        if($dados_feriado_municipal_estadual_nacional){
            $filtro_dia = " (a.dia = 4 OR a.dia = 5) ";

            //Segunda Ontem
            if($dia_semana[$diasemana_ontem_numero] == 'Segunda'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 7 OR a.dia = 13  ) ) "; 
            }
        
            //Terça Ontem
            else if($dia_semana[$diasemana_ontem_numero] == 'Terça'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 8 OR a.dia = 13 ) ) "; 
            }
        
            //Quarta Ontem
            else if($dia_semana[$diasemana_ontem_numero] == 'Quarta'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 9 OR a.dia = 13 ) ) "; 
            }
        
            //Quinta Ontem
            else if($dia_semana[$diasemana_ontem_numero] == 'Quinta'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 10 OR a.dia = 13 ) ) "; 
            }
        
            //Sexta Ontem
            else if($dia_semana[$diasemana_ontem_numero] == 'Sexta'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11 ) ) "; 
            }
        
            //Sábado  Ontem
            else if($dia_semana[$diasemana_ontem_numero] == 'Sábado'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12 ) ) "; 
            }
        
            //Domingo Ontem
            else if($dia_semana[$diasemana_ontem_numero] == 'Domingo'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 4 OR a.dia = 6 ) ) "; 
            }
        }else{
            //Segunda
            if($dia_semana[$diasemana_numero] == 'Segunda'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 7) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND ( a.dia = 1 OR a.dia = 4 OR a.dia = 6 ) ) "; 
            }
        
            //Terça
            else if($dia_semana[$diasemana_numero] == 'Terça'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 8) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 7 OR a.dia = 13 ) ) "; 
            }
        
            //Quarta
            else if($dia_semana[$diasemana_numero] == 'Quarta'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 9) ";
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 8 OR a.dia = 13 ) ) "; 
            }
        
            //Quinta
            else if($dia_semana[$diasemana_numero] == 'Quinta'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 10) ";
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 9 OR a.dia = 13 ) ) "; 
            }
        
            //Sexta
            else if($dia_semana[$diasemana_numero] == 'Sexta'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 10 OR a.dia = 13 ) ) "; 
            }
        
            //Sábado 
            else if($dia_semana[$diasemana_numero] == 'Sábado'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 12) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11 ) ) "; 
            }
        
            //Domingo
            else if($dia_semana[$diasemana_numero] == 'Domingo'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 4 OR a.dia = 6) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12 ) ) "; 
            }
        }

        $dados_feriado_municipal_estadual_nacional_ontem = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_ontem, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $conteudo_contrato['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $conteudo_contrato['id_cidade'] . "'))");

        if($dados_feriado_municipal_estadual_nacional_ontem){
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND (a.dia = 5 OR a.dia = 6) ) ";
        }
     
        $dados_horario_agora = DBRead('', 'tb_horario a', "INNER JOIN tb_horario_contrato b ON a.id_horario_contrato = b.id_horario_contrato INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE (b.tipo = 8 OR b.tipo = 9) AND ( $filtro_dia $filtro_ontem )  AND b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'  ORDER BY b.tipo ASC", "a.*, b.*, c.nome_contrato, e.cor, d.nome");
// var_dump($dados_horario_agora);

        if($dados_horario_agora){
            echo "<div class='col-lg-4 col-md-4' style = 'padding-bottom: 15px;'>";
                echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
                    echo '<div class="btn-group" role="group">';
                        echo '<a href="/api/iframe?token=<?php echo $request->token ?>&view=atendimento-inicio-form&contrato='.$dados_horario_agora[0]['id_contrato_plano_pessoa'].'" class="btn btn-default" style="border-left: 20px solid '.$dados_horario_agora[0]['cor'].'; padding-top: 16px; padding-bottom: 16px; text-shadow: 0px 0px 0px !important; background-image: none !important; background-color: rgb(217, 217, 217) !important; color: rgb(0, 0, 0); height: 176px !important;">';
                        echo '<span style="font-size: 13px; display: inline;" class="pull-left">'.$dados_horario_agora[0]['id_contrato_plano_pessoa'].'</span>';
                        echo '<span style="font-size: 16px;" >';
                        echo $dados_horario_agora[0]['nome'];
                        if($dados_horario_agora[0]['nome_contrato']){
                            echo " (".$dados_horario_agora[0]['nome_contrato'].")";
                        } 
                        echo '</span>';


                        echo "<hr style='margin-top: 5px; margin-bottom: 5px; border-top: 1px solid #808080;'>";
                        $aux_tipo = '';
                        foreach($dados_horario_agora as $conteudo){

                                if($conteudo['tipo'] != $aux_tipo){
                                    if($aux_tipo != ''){
                                        echo "<br>";
                                    }

                                    echo '<span style="font-size: 13px; display: inline;" class="pull-left">' ;
                                    if($conteudo['tipo'] == 8){
                                        $tipo = ' <i class="fa fa-phone"></i> Via Telefone:';
                                    }else if($conteudo['tipo'] == 9){
                                        $tipo = ' <i class="fab fa-whatsapp"></i> Via Texto:';
                                    }
                                    echo $tipo; 
                                    echo '</span>';

                                }
                                $aux_tipo = $conteudo['tipo'];

                            echo "<span style='font-size: 13px;'>";
                        
                            $hora_inicio = explode(':', $conteudo['hora_inicio']);
                            $hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
                            $hora_fim = explode(':', $conteudo['hora_fim']);
                            $hora_fim = $hora_fim[0].":".$hora_fim[1];
                            
                            if(($conteudo['hora_inicio'] <= $hora_agora && $conteudo['hora_fim'] >= $hora_agora) || ($conteudo['hora_inicio'] >= $conteudo['hora_fim'] && ($conteudo['hora_inicio'] <= $hora_agora || $conteudo['hora_fim'] >= $hora_agora))){
                                echo '<br><strong>Início: '.$hora_inicio.' - Final: '.$hora_fim.'</strong> - '.$tipo_dia_atendimento[$conteudo['dia']];
                            }else{
                                echo '<br><s>Início: '.$hora_inicio.' - Final: '.$hora_fim.'</s> - '.$tipo_dia_atendimento[$conteudo['dia']];
                            }
                            echo '</span>';

                        }

                        echo '</a>';
                    echo "</div>";  
                echo "</div>";
            echo "</div>";
        }

    }
    echo "</div>";
}

if($array_ativo && $array_inativo){
    echo "<hr style='border: 1px solid #d9d9d9;'>";
}

if($array_inativo){
    echo '<h3 class="panel-title text-left pull-left col-md-12" style="margin-top: 2px; padding-bottom: 15px;"><strong>Inativos:</strong></h3>';
    echo "<div class='row'>";
    
    foreach ($array_inativo as $nome => $id_contrato_plano_pessoa) {

        $dados_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.id_cidade, d.id_estado");

        $dados_feriado_municipal_estadual = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_agora, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $dados_pessoa[0]['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $dados_pessoa[0]['id_cidade'] . "'))");

        if($dados_feriado_municipal_estadual_nacional){
            $filtro_dia = " AND (a.dia = 4 OR a.dia = 5) ";

            //Segunda
            if($dia_semana[$diasemana_numero] == 'Segunda'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 7 OR a.dia = 13 ) ) "; 
            }
        
            //Terça
            else if($dia_semana[$diasemana_numero] == 'Terça'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 8 OR a.dia = 13 ) ) ";  
            }
        
            //Quarta
            else if($dia_semana[$diasemana_numero] == 'Quarta'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 9 OR a.dia = 13 ) ) "; 
            }
        
            //Quinta
            else if($dia_semana[$diasemana_numero] == 'Quinta'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 10 OR a.dia = 13 ) ) "; 
            }
           
            //Sexta
            else if($dia_semana[$diasemana_numero] == 'Sexta'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11 ) ) "; 
            }
        
            //Sábado 
            else if($dia_semana[$diasemana_numero] == 'Sábado'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12 ) ) "; 
            }
        
            //Domingo
            else if($dia_semana[$diasemana_numero] == 'Domingo'){ 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 4 OR a.dia = 6 ) ) "; 
            }
        }else{
            //Segunda
            if($dia_semana[$diasemana_numero] == 'Segunda'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 7) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 4 OR a.dia = 6 ) ) "; 
            }
        
            //Terça
            else if($dia_semana[$diasemana_numero] == 'Terça'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 8) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 7 OR a.dia = 13 ) ) ";  
            }
        
            //Quarta
            else if($dia_semana[$diasemana_numero] == 'Quarta'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 9) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 8 OR a.dia = 13 ) ) "; 
            }
        
            //Quinta
            else if($dia_semana[$diasemana_numero] == 'Quinta'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 13 OR a.dia = 10) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 9 OR a.dia = 13 ) ) "; 
            }
           
            //Sexta
            else if($dia_semana[$diasemana_numero] == 'Sexta'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 10 OR a.dia = 13 ) ) "; 
            }
        
            //Sábado 
            else if($dia_semana[$diasemana_numero] == 'Sábado'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 2 OR a.dia = 12) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 3 OR a.dia = 11 ) ) "; 
            }
        
            //Domingo
            else if($dia_semana[$diasemana_numero] == 'Domingo'){ 
                $filtro_dia = " (a.dia = 1 OR a.dia = 4 OR a.dia = 6) "; 
                $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 1 OR a.dia = 2 OR a.dia = 12 ) ) "; 
            }
        }

        $dados_feriado_municipal_estadual_nacional_ontem = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_ontem, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $conteudo_contrato['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $conteudo_contrato['id_cidade'] . "'))");

        if($dados_feriado_municipal_estadual_nacional_ontem){
            $filtro_ontem = " OR (a.hora_inicio >= a.hora_fim AND a.hora_fim <= '".$hora_agora."' AND (a.dia = 6 OR a.dia = 5 ) ) "; 
        }
        $dados_horario_agora = DBRead('', 'tb_horario a', "INNER JOIN tb_horario_contrato b ON a.id_horario_contrato = b.id_horario_contrato INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE (b.tipo = 8 OR b.tipo = 9) AND ( $filtro_dia $filtro_ontem ) AND b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ORDER BY b.tipo ASC ", "a.*, b.*, c.nome_contrato, e.cor, d.nome");

        if($dados_horario_agora){
            echo "<div class='col-lg-3 col-md-4' style = 'padding-bottom: 15px;'>";
                echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
                    echo '<div class="btn-group" role="group">';
                        echo '<a href="/api/iframe?token=<?php echo $request->token ?>&view=atendimento-inicio-form&contrato='.$dados_horario_agora[0]['id_contrato_plano_pessoa'].'" class="btn btn-default" style="border-left: 20px solid '.$dados_horario_agora[0]['cor'].'; padding-top: 16px; padding-bottom: 16px; text-shadow: 0px 0px 0px !important; background-image: none !important; background-color: rgb(217, 217, 217) !important; color: rgb(0, 0, 0); height: 176px !important;">';
                        echo '<span style="font-size: 13px; display: inline;" class="pull-left">'.$dados_horario_agora[0]['id_contrato_plano_pessoa'].'</span>';
                        echo '<span style="font-size: 16px;" >';
                        echo $dados_horario_agora[0]['nome'];
                        if($dados_horario_agora[0]['nome_contrato']){
                            echo " (".$dados_horario_agora[0]['nome_contrato'].")";
                        }
                        echo '</span>';


                        echo "<hr style='margin-top: 5px; margin-bottom: 5px; border-top: 1px solid #808080;'>";
                        $aux_tipo = '';
                        foreach($dados_horario_agora as $conteudo){
                            
                                if($conteudo['tipo'] != $aux_tipo){
                                    if($aux_tipo != ''){
                                        echo "<br>";
                                    }

                                    echo '<span style="font-size: 13px; display: inline;" class="pull-left">' ;

                                    if($conteudo['tipo'] == 8){
                                        $tipo = ' <i class="fa fa-phone"></i> Via Telefone:';
                                    }else if($conteudo['tipo'] == 9){
                                        $tipo = ' <i class="fab fa-whatsapp"></i> Via Texto:';
                                    }
                                    echo $tipo; 
                                    echo '</span>';

                                }
                                $aux_tipo = $conteudo['tipo'];

                                $hora_inicio = explode(':', $conteudo['hora_inicio']);
                                $hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
                                $hora_fim = explode(':', $conteudo['hora_fim']);
                                $hora_fim = $hora_fim[0].":".$hora_fim[1];
    
                                echo "<span style='font-size: 13px;'>";
                                echo '<br><s>Início: '.$hora_inicio.' - Final: '.$hora_fim.'</s> - '.$tipo_dia_atendimento[$conteudo['dia']];
                                echo '</span>';

                        }

                        echo '</a>';
                    echo "</div>";  
                echo "</div>";
            echo "</div>";
        }

    }
    echo "</div>";

}

if(!$array_ativo && !$array_inativo){
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if(!$letra){
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
}
    
?>