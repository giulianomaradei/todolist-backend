<?php
require_once(__DIR__ . "/System.php");

$dados_automacao = DBRead('snep','queue_automacao',"WHERE id = 1");
if($dados_automacao){
    $tempo_fila1_automacao = $dados_automacao[0]['tempo_fila1'];
    $tempo_fila2_automacao = $dados_automacao[0]['tempo_fila2'];
    $qtd_dias_calculo = $dados_automacao[0]['qtd_dias_calculo'];
    $porcentagem_prioridade_alta = $dados_automacao[0]['porcentagem_prioridade_alta'];
    $porcentagem_prioridade_baixa = $dados_automacao[0]['porcentagem_prioridade_baixa'];
}else{
    $tempo_fila1_automacao = 1200;
    $tempo_fila2_automacao = 600;
    $qtd_dias_calculo = 7;
    $porcentagem_prioridade_alta = 50;
    $porcentagem_prioridade_baixa = 150;
}

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

//echo $data_de.' | '.$data_ate.' | '.$qtd_dias_mes.' | '.$qtd_dias_calculo.'<hr>';

$dados_prefixos = DBRead('snep','empresas', "WHERE controle_automatico_fila = '1'");

if($dados_prefixos){
    foreach ($dados_prefixos as $conteudo_prefixo) {

        $dados_contrato = DBRead('','tb_parametros a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_asterisk = '".$conteudo_prefixo['id']."' AND b.status != '3' AND b.status != '2' AND b.status != '0'","b.*, c.nome AS 'nome_cliente', b.desconsidera_notificacao");

        if($dados_contrato){
            if($dados_contrato[0]['desconsidera_notificacao'] == 1){
                $qtd_atendimentos = 0;                
                $qtd_atendimentos_notificacao = 0;                

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

                    //Desconsidera Notificacao
                    $cont_dados_faturados_notificacao = DBRead('','tb_atendimento a',"INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento WHERE b.id_subarea_problema = 26 AND a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$dados_contrato[0]['contrato_pai']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND a.via_texto != 1' : ''),"COUNT(a.*) AS cont");
                    $qtd_atendimentos_notificacao += $cont_dados_faturados_notificacao[0]['cont'];

                    if($dados_contrato_irmaos){
                        foreach ($dados_contrato_irmaos as $conteudo_contrato_irmao) {
                            $cont_dados_faturados_notificacao = DBRead('','tb_atendimento a',"INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento WHERE b.id_subarea_problema = 26 AND a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$conteudo_contrato_irmao['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND a.via_texto != 1' : ''),"COUNT(*) AS cont");
                            $qtd_atendimentos_notificacao += $cont_dados_faturados_notificacao[0]['cont'];
                        }
                    }
                    
                }else{  
                    $dados_contrato_pai = $dados_contrato;

                    $dados_contrato_filhos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.contrato_pai = '".$dados_contrato[0]['id_contrato_plano_pessoa']."'","a.*, b.nome AS 'nome_cliente'");
                    if($dados_contrato_filhos){
                        foreach ($dados_contrato_filhos as $conteudo_contrato_filho) {
                            $cont_dados_faturados = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND id_contrato_plano_pessoa = '".$conteudo_contrato_filho['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND via_texto != 1' : ''),"COUNT(*) AS cont");
                            $qtd_atendimentos += $cont_dados_faturados[0]['cont'];
                    
                            //Desconsidera Notificacao
                            $cont_dados_faturados_notificacao = DBRead('','tb_atendimento a',"INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento WHERE b.id_subarea_problema = 26 AND a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$conteudo_contrato_filho['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND a.via_texto != 1' : ''),"COUNT(a.*) AS cont");
                            $qtd_atendimentos_notificacao += $cont_dados_faturados_notificacao[0]['cont'];
                        }
                    }

                }                
                
                $cont_dados_faturados = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_contrato[0]['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND via_texto != 1' : ''),"COUNT(*) AS cont");
                $qtd_atendimentos += $cont_dados_faturados[0]['cont'];

                //Desconsidera Notificacao
                $cont_dados_faturados_notificacao = DBRead('','tb_atendimento a',"INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento WHERE b.id_subarea_problema = 26 AND a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$dados_contrato[0]['id_contrato_plano_pessoa']."'".($dados_contrato_pai[0]['valor_diferente_texto'] ? ' AND a.via_texto != 1' : ''),"COUNT(a.*) AS cont");
                $qtd_atendimentos_notificacao += $cont_dados_faturados_notificacao[0]['cont'];

                $qtd_atendimentos = $qtd_atendimentos - $qtd_atendimentos_notificacao;
                if($qtd_atendimentos <= 0){
                    $qtd_atendimentos == 0;
                }
               
            }else{
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
            }

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

            //if($dia_data_hoje == 1){
            //    $fila1 = $fila_normal;
            //}else{
                if($porcentagem_utilizada < $porcentagem_prioridade_alta){
                    $fila1 = $fila_alta;                    
                }else if($porcentagem_utilizada >= $porcentagem_prioridade_alta && $porcentagem_utilizada <= $porcentagem_prioridade_baixa){
                    $fila1 = $fila_normal;                    
                }else{
                    $fila1 = $fila_baixa;                   
                }
            //}            

            $tempo_fila1 = $tempo_fila1_automacao;
            $fila2 = $fila_vip;
            $tempo_fila2 = $tempo_fila2_automacao;

            $dados = array(
                'fila1' => $fila1,
                'tempo_fila1' => $tempo_fila1,
                'fila2' => $fila2,
                'tempo_fila2' => $tempo_fila2,
            );
            DBUpdate('snep', 'empresas', $dados, "id = '".$conteudo_prefixo['id']."'");
            registraLog('Controle automático de filas do Call Center.','a','empresas',$conteudo_prefixo['id'],"fila1: $fila1 | tempo_fila1: $tempo_fila1 | fila2: $fila2 | tempo_fila2: $tempo_fila2");

           /*  echo 'regra: '.$conteudo_prefixo['nome'].'<br>';
            echo 'contrato: '.$dados_contrato_pai[0]['nome_cliente'].'<br>';
            echo 'qtd contratada: '.$dados_contrato_pai[0]['qtd_contratada'].' | qtd proporcional: '.$qtd_proporcional_contratada.' | qtd realizada: '.$qtd_atendimentos.' | porcentagem utilizada: '.$porcentagem_utilizada;
            echo '<pre>';
            var_dump($dados);
            echo '</pre>';
            echo '<hr>'; */

        }            
    }
}
?>