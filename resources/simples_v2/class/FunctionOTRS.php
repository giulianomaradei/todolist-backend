<?php
function getTempoPrimeiraRespostaOTRS($tn, $id_tecnico = null){
    $tempo_primeira_resposta_minutos = 0;
    $horario_abertura = 0;
    $horario_primeira_resposta = 0;
    $id_abertura = 0;
    $tempo_trabalhado_minutos = 0;

    //dados do ticket (chamado)
    $dados_ticket = DBRead('otrs', 'ticket',"WHERE tn='$tn'");

    if($dados_ticket){
        $ticket_id = $dados_ticket[0]['id'];

        //dados de histórico do ticket (chamado)
        $dados_historico = DBRead('otrs', 'ticket_history', "WHERE ticket_id = '$ticket_id ' AND history_type_id='1'");
        if($dados_historico){
            foreach($dados_historico as $conteudo_historico){
                //se foi aberto como pendente ou fechado
			    if($conteudo_historico['state_id'] == 2 || $conteudo_historico['state_id'] == 3 || $conteudo_historico['state_id'] == 6){
                    return 0;
                } 
            }
        }

        //dados das interações(ações) do chamado
        $dados_article = DBRead('otrs', 'article', " WHERE ticket_id = '$ticket_id'");
        if($dados_article){
            foreach($dados_article as $conteudo_article){
                $article_id = $conteudo_article['id'];

                if($id_abertura == 0){
                   
                    $horario_abertura = $conteudo_article['create_time'];
                    $id_abertura = $conteudo_article['id'];
                    //se foi aberto com email enviado ao cliente ja na abertura
                    if($conteudo_article['article_type_id'] == 1 && $conteudo_article['article_sender_type_id'] == 1){ 
                        return 0;
                    }

                }else if ($horario_primeira_resposta==0){
                    
                    $dados_dynamic_field_value = DBRead('otrs', 'dynamic_field_value', "WHERE object_id='$article_id' AND field_id='51' AND value_int='1'", "COUNT(*) as 'total'");
                    //se não foi ignorada (Ignorar SLA)
                    if(!$dados_dynamic_field_value[0]['total'] && $conteudo_article['article_sender_type_id'] == 1){
                        $horario_primeira_resposta = $conteudo_article['create_time'];
                        $id_tecnico_primeira_resposta = $conteudo_article['create_by'];
                    }
                }

                if($horario_primeira_resposta != 0 && $tempo_trabalhado_minutos == 0){
                    //dados do tempo da interação
                    $dados_time_accounting = DBRead('otrs', 'time_accounting', "WHERE article_id='$article_id'");
                    if($dados_time_accounting){
                        $tempo_trabalhado_minutos = intval($dados_time_accounting[0]['time_unit']);
                    }
                }
            }

            //verifica se $id_tecnico existe e foi respondido por ele
            if($id_tecnico && $id_tecnico != $id_tecnico_primeira_resposta){
                return -2;
            }


            if(!$horario_abertura || !$horario_primeira_resposta){
                return -1;
            }

            // return $horario_abertura.' | '.$horario_primeira_resposta . ' | '.$tempo_trabalhado_minutos;
            $teste_horarios = $horario_abertura.' | '.$horario_primeira_resposta . ' | '.$tempo_trabalhado_minutos;

            //daqui em diante, deve-se prever os cenários de horário do setor e retornar o tempo da primeira resposta em minutos(int) ($'tempo_primeira_resposta_minutos')
            //horário de funcionamento é de segunda a sexta das 08:30 as 12:00 e das 13:30 as 18:00 os feriádos de parada são apenas os nacionais


            $horario_abertura_date = DateTime::createFromFormat ( 'Y-m-d H:i:s', $horario_abertura );
            $horario_primeira_resposta_date = DateTime::createFromFormat ( 'Y-m-d H:i:s', $horario_primeira_resposta );
           
            //horario do expediente
            $hora_expediente_inicial_manha = '08';
            $minutos_expediente_inicial_manha = '30';
            $hora_expediente_final_manha = '12';
            $minutos_expediente_final_manha = '00';

            $hora_expediente_inicial_tarde = '13';
            $minutos_expediente_inicial_tarde = '30';
            $hora_expediente_final_tarde = '18';
            $minutos_expediente_final_tarde = '00';

            $tempo_total_turno_manha_segundos = '12600';
            $tempo_total_turno_tarde_segundos = '16200';

            $tempo_total_intervalo_meio_dia_segundos = '5400';

            $tempo_primeira_resposta_minutos = 0;

            //Verifica se na data de abertura é feirado
            $feriado_abertura = DBRead('', 'tb_feriado', "WHERE tipo='Nacional' AND data = '".$horario_abertura_date->format('m-d')."' ");
            if($feriado_abertura){
                //$teste_horarios = $tempo_trabalhado_minutos;

                    $teste = '';
                    $tempo_segundos = 0;
                    foreach (rangeDatas($horario_abertura_date->format('Y-m-d'), $horario_primeira_resposta_date->format('Y-m-d')) as $data) {		
                        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                        $data_numero = date($data);
                        $diasemana_numero = date('w', strtotime($data_numero));

                        $mes_ano_data = explode('-', $data);
                        $mes_ano_data = $mes_ano_data[1].'-'.$mes_ano_data[2];
                        $feriado_data = DBRead('', 'tb_feriado', "WHERE tipo='Nacional' AND data = '".$mes_ano_data."' ");

                        if($diasemana[$diasemana_numero] != 'Sabado' && $diasemana[$diasemana_numero] != 'Domingo' && !$feriado_data){
                            
                            if($data == $horario_abertura_date->format('Y-m-d')){
                                $teste .= $horario_abertura_date->format('Y-m-d H:i:s');

                               
                                if($horario_abertura_date->format('H') >= $hora_expediente_inicial_manha && $horario_abertura_date->format('H') <= $hora_expediente_final_manha){
                                    //Hora é 8 os minutos são a partir de 30
                                    if($horario_abertura_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_abertura_date->format('i') >= $minutos_expediente_inicial_manha){
            
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                        }
                                    }
                                    
                                    //Hora é 12 os minutos são menores ou iguais que 00                        
                                    if($horario_abertura_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_abertura_date->format('i') <= $minutos_expediente_final_manha){

                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                        }
                                    }
            
                                    //Normal manhã                        
                                    if($horario_abertura_date->format('H') != $hora_expediente_inicial_manha && $horario_abertura_date->format('H') != $hora_expediente_final_manha){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                        $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                    }            
                                }
                                
                                //abertura turno da tarde
                                if($horario_abertura_date->format('H') >= $hora_expediente_inicial_tarde && $horario_abertura_date->format('H') <= $hora_expediente_final_tarde){
            
                                    //Hora é 13 os minutos são a partir de 30
                                    if($horario_abertura_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_abertura_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00' ));
                                            $tempo_segundos += ($depois - $antes);
            
                                        }
                                    }
                                    
                                    //Hora é 18 os minutos são menores ou iguais que 00                        
                                    if($horario_abertura_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_abertura_date->format('i') <= $minutos_expediente_final_tarde){
                                            
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00' ));
                                            $tempo_segundos += ($depois - $antes);

                                        }
                                    }
            
                                    //Normal tarde                        
                                    if($horario_abertura_date->format('H') != $hora_expediente_inicial_tarde && $horario_abertura_date->format('H') != $hora_expediente_final_tarde){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00' ));
                                        $tempo_segundos += ($depois - $antes);   
                                    }
                                }
            
                                //abertura antes das 8:30
                                if($horario_abertura_date->format('H') <= $hora_expediente_inicial_manha){
                                    if($horario_abertura_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_abertura_date->format('i') < $minutos_expediente_inicial_manha){
                                           
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                        }
                                    }
                                    
                                    //antes das 07:59
                                    if($horario_abertura_date->format('H') < $hora_expediente_inicial_manha){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                        $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;
            
                                    }
                                }
            
                                //abertura depois das 18:00    
                                if($horario_abertura_date->format('H') >= $hora_expediente_final_tarde){
                                   
                                    $tempo_segundos += 0;
                                    
                                }
            
                                //abertura entre 12:01 e 13:29   
                                if($horario_abertura_date->format('H') >= $hora_expediente_final_manha && $horario_abertura_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_abertura_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_abertura_date->format('i') > $minutos_expediente_final_manha){
                                           
                                            $tempo_segundos += $tempo_total_turno_tarde_segundos;

                                        }
            
                                    }else if($horario_abertura_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_abertura_date->format('i') < $minutos_expediente_inicial_tarde){
                                            
                                            $tempo_segundos += $tempo_total_turno_tarde_segundos;
            
                                        }
            
                                    }else if($horario_abertura_date->format('H') != $hora_expediente_final_manha && $horario_abertura_date->format('H') != $hora_expediente_inicial_tarde){
                                        //Abertura entre 12:01 e 13:29
                                        if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                           
                                            $tempo_segundos += $tempo_total_turno_tarde_segundos;

                                        }
                                    }
                                }

                            }else if($data == $horario_primeira_resposta_date->format('Y-m-d')){
                                $teste .= $horario_primeira_resposta_date->format('Y-m-d H:i:s');

                                //Resposta na manhã antes das 07:59
                                if($horario_primeira_resposta_date->format('H') < $hora_expediente_inicial_manha){
                                   
                                    $tempo_segundos += 0;
                                    
                                }

                                //Resposta na manhã antes das 08:30
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                    if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_manha){

                                        $tempo_segundos += 0;

                                    }
                                }

                                //Resposta na manhã
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_manha){

                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes);

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                           
                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes);

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_manha){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos += ($depois - $antes);

                                    }
                                }
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                           
                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_manha_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                              
                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_manha_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                              
                                        $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos += ($depois - $antes) + $tempo_total_turno_manha_segundos;

                                    }
                                }
                                
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            
                                            $tempo_segundos += $tempo_total_turno_manha_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                                
                                            $tempo_segundos += $tempo_total_turno_manha_segundos;

                                        }
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                                
                                            $tempo_segundos += $tempo_total_turno_manha_segundos + $tempo_total_turno_tarde_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                                
                                        $tempo_segundos += $tempo_total_turno_manha_segundos + $tempo_total_turno_tarde_segundos;

                                    }
                                }
                            
                            }else{
                                $tempo_segundos +=  $tempo_total_turno_manha_segundos + $tempo_total_turno_tarde_segundos;
                            }

                        }else{
                            $teste .= $data;

                            $tempo_segundos += 0;
                        }

                        if($feriado_data){
                            $teste .= " | Feriado";
                        }else if($diasemana[$diasemana_numero] == 'Sabado'){
                            $teste .= " | Sabado";
                        }else if($diasemana[$diasemana_numero] == 'Domingo'){
                            $teste .= " | Domingo";
                        }else{
                            $teste .= " | Outro";    
                        }
                        $teste .= "<br>";
                    }

                    $tempo_minutos = $tempo_segundos/60;
                    $tempo_primeira_resposta_minutos = (int)$tempo_minutos - $tempo_trabalhado_minutos;

                    if($tempo_primeira_resposta_minutos < 0){
                        $tempo_primeira_resposta_minutos = 0;
                    }

                    $horas = intval($tempo_segundos/3600);
                    $minutos = intval(($tempo_segundos%3600)/60);
                    $segundos = intval(($tempo_segundos%3600)%60);
                    
                    // return $teste.'<br>'.$teste_horarios.' | '.$horas.' h, '.$minutos.' m, '.$segundos.' s<br>'.$tempo_primeira_resposta_minutos;
                    return $tempo_primeira_resposta_minutos;

            }else{
 
                //Mesmo dia da abertura e da primeira resposta
                if($horario_abertura_date->format('Y-m-d') == $horario_primeira_resposta_date->format('Y-m-d')){

                    //abertura turno da manhã
                    if($horario_abertura_date->format('H') >= $hora_expediente_inicial_manha && $horario_abertura_date->format('H') <= $hora_expediente_final_manha){
                        //Hora é 8 os minutos são a partir de 30
                        if($horario_abertura_date->format('H') == $hora_expediente_inicial_manha){
                            if($horario_abertura_date->format('i') >= $minutos_expediente_inicial_manha){

                                //Resposta na manhã
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_manha){
                                            //id teste = 10308544
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);
                                            
                                            //abertura de manha (08:30) e resposta de manha (08:30)                                            
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha (08:30) e resposta de manha (12:00)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_manha){
                                        //id teste = 10308912
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha (08:30) e resposta entre 9 e 11:59
                                    }
                                }
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha (08:30) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha (08:30) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (08:30) e resposta entre 14:00 e 17:59';
                                    }
                                }
                                
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha (08:30) e resposta intevalo meio dia (12:01)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha (08:30) e resposta intevalo meio dia (13:29)
                                        }
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha (08:30) e resposta depois das 18:00
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (08:30) e resposta depois das 18:01
                                    }
                                }

                            }
                        }
                        
                        //Hora é 12 os minutos são menores ou iguais que 00                        
                        if($horario_abertura_date->format('H') == $hora_expediente_final_manha){
                            if($horario_abertura_date->format('i') <= $minutos_expediente_final_manha){
                                //Resposta na manhã
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);
                                            $tempo_segundos = 0;
                                            //Aqui acho que é 0

                                            //abertura de manha (12:00) e resposta de manha (12:00)
                                        }
                                    }
                                }
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha (12:00) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha (12:00) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (12:0) e resposta entre 14:00 e 17:59';
                                    }
                                }
                                
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            //id teste = 10310373
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha (12:00) e resposta intevalo meio dia (12:01)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha (12:00) e resposta intevalo meio dia (13:29)
                                        }
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha (12:00) e resposta depois das 18:00
                                        }
                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (12:00) e resposta depois das 18:01
                                    }
                                }
                            }
                        }

                        //Normal manhã                        
                        if($horario_abertura_date->format('H') != $hora_expediente_inicial_manha && $horario_abertura_date->format('H') != $hora_expediente_final_manha){
                            //Resposta na manhã
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha (09:00) e resposta de manha (12:00)
                                    }

                                }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_manha){
                                    //id teste = ???????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de manha (09:00) e resposta entre 9 e 11:59
                                }
                            }
                            
                            //Resposta de tarde
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (09:00) e resposta de tarde (13:30)';
                                    }

                                }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (09:00) e resposta de tarde (18:00)';
                                    }

                                }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                    //id teste = ???????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                    $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                    //abertura de manha (09:00) e resposta entre 14:00 e 17:59';
                                }
                            }
                            
                            //Resposta intervalo meio dia
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha (09:00) e resposta intevalo meio dia (12:01)
                                    }

                                }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha (09:00) e resposta intevalo meio dia (13:29)
                                    }
                                }
                            }

                            //Resposta depois das 18:00
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha (08:30) e resposta depois das 18:00
                                    }

                                }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                    //id teste = ?????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                    $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                    //abertura de manha (08:30) e resposta depois das 18:01
                                }
                            }   
                        }            
                    }
                    
                    //abertura turno da tarde
                    if($horario_abertura_date->format('H') >= $hora_expediente_inicial_tarde && $horario_abertura_date->format('H') <= $hora_expediente_final_tarde){

                        //Hora é 13 os minutos são a partir de 30
                        if($horario_abertura_date->format('H') == $hora_expediente_inicial_tarde){
                            if($horario_abertura_date->format('i') >= $minutos_expediente_inicial_tarde){
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de tarde (13:30) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de tarde (13:30) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de tarde (13:30) e resposta entre 14:00 e 17:59';
                                    }
                                }
                                
                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura depois das 18:00 e resposta depois das 18:00
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura depois das 18:00 e resposta depois das 18:01
                                    }
                                }

                            }
                        }
                        
                        //Hora é 18 os minutos são menores ou iguais que 00                        
                        if($horario_abertura_date->format('H') == $hora_expediente_final_tarde){
                            if($horario_abertura_date->format('i') <= $minutos_expediente_final_tarde){
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de tarde (18:00) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de tarde (18:00) e resposta entre 14:00 e 17:59';
                                    }
                                }
                                
                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de tarde (18:00) e resposta depois das 18:00
                                        }
                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de tarde (18:00) e resposta depois das 18:01
                                    }
                                }
                            }
                        }

                        //Normal tarde                        
                        if($horario_abertura_date->format('H') != $hora_expediente_inicial_tarde && $horario_abertura_date->format('H') != $hora_expediente_final_tarde){
                            
                            //Resposta de tarde
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de tarde (13:30) e resposta de tarde (13:30)';
                                    }

                                }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de tarde (13:30) e resposta de tarde (18:00)';
                                    }

                                }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                    //id teste = ???????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de tarde (13:30) e resposta entre 14:00 e 17:59';
                                }
                            }
                          
                            //Resposta depois das 18:00
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de tarde (13:30) e resposta depois das 18:00
                                    }

                                }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                    //id teste = ?????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de tarde (13:30) e resposta depois das 18:01
                                }
                            }   
                        }
                    }

                    //abertura antes das 8:30
                    if($horario_abertura_date->format('H') <= $hora_expediente_inicial_manha){
                        if($horario_abertura_date->format('H') == $hora_expediente_inicial_manha){
                            if($horario_abertura_date->format('i') < $minutos_expediente_inicial_manha){
                               
                                //Resposta na manhã antes das 07:59
                                if($horario_primeira_resposta_date->format('H') < $hora_expediente_inicial_manha){
                                    //id teste = ???????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de manha antes (08:30) e resposta de manha antes (07:59)
                                }

                                //Resposta na manhã antes das 08:30
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                    if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_manha){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha antes (08:30) e resposta de manha antes (08:30)
                                    }
                                }

                                //Resposta na manhã
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_manha){
                                            //id teste = ????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha antes (08:30) e resposta de manha (08:30)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha antes (08:30) e resposta de manha (12:00)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_manha){
                                        //id teste = ??????????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha antes (08:30) e resposta entre 9 e 11:59
                                    }
                                }
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha antes (08:30) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha antes (08:30) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha antes (08:30) e resposta entre 14:00 e 17:59';
                                    }
                                }
                                
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha antes (08:30) e resposta intevalo meio dia (12:01)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura de manha antes (08:30) e resposta intevalo meio dia (13:29)
                                        }
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura de manha antes (08:30) e resposta depois das 18:00
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha antes (08:30) e resposta depois das 18:01
                                    }
                                }
                            }
                        }
                        
                        //antes das 07:59
                        if($horario_abertura_date->format('H') < $hora_expediente_inicial_manha){
                            //Resposta na manhã antes das 07:59
                            if($horario_primeira_resposta_date->format('H') < $hora_expediente_inicial_manha){
                                //id teste = ???????
                                $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                $tempo_segundos = ($depois - $antes);

                                //abertura de manha antes (07:59) e resposta de manha antes (07:59)
                            }

                            //Resposta na manhã antes das 08:30
                            if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_manha){
                                    //id teste = ???????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de manha antes (07:59) e resposta de manha antes (08:30)
                                }
                            }

                            //Resposta na manhã
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                    if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_manha){
                                        //id teste = ????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha antes (07:59) e resposta de manha (08:30)
                                    }

                                }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha antes (07:59) e resposta de manha (12:00)
                                    }

                                }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_manha){
                                    //id teste = ??????????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de manha antes (07:59) e resposta entre 9 e 11:59
                                }
                            }
                            
                            //Resposta de tarde
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha antes (07:59) e resposta de tarde (13:30)';
                                    }

                                }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha antes (07:59) e resposta de tarde (18:00)';
                                    }

                                }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                    //id teste = ???????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                    $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                    //abertura de manha antes (07:59) e resposta entre 14:00 e 17:59';
                                }
                            }
                            
                            //Resposta intervalo meio dia
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha antes (07:59) e resposta intevalo meio dia (12:01)
                                    }

                                }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00'));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura de manha antes (07:59) e resposta intevalo meio dia (13:29)
                                    }
                                }
                            }

                            //Resposta depois das 18:00
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura de manha antes (07:59) e resposta depois das 18:00
                                    }

                                }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                    //id teste = ?????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                    $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                    //abertura de manha antes (07:59) e resposta depois das 18:01
                                }
                            }

                        }
                    }

                    //abertura depois das 18:00    
                    if($horario_abertura_date->format('H') >= $hora_expediente_final_tarde){
                        //Resposta depois das 18:00
                        if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                            if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                    //id teste = ?????
                                    $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                    $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                    $tempo_segundos = ($depois - $antes);

                                    //abertura de tarde depois (18:01) e resposta depois das 18:00
                                }

                            }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                //id teste = ?????
                                $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                $tempo_segundos = ($depois - $antes);

                                //abertura de tarde depois (19:00) e resposta depois das 18:01
                            }
                        }
                    }

                    //abertura entre 12:01 e 13:29   
                    if($horario_abertura_date->format('H') >= $hora_expediente_final_manha && $horario_abertura_date->format('H') <= $hora_expediente_inicial_tarde){
                        if($horario_abertura_date->format('H') == $hora_expediente_final_manha){
                            if($horario_abertura_date->format('i') > $minutos_expediente_final_manha){
                               
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (12:01) e resposta intervalo meio dia (12:01)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$hora_expediente_inicial_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (12:01) e resposta intervalo meio dia (13:29)
                                        }
                                    }
                                }

                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (12:01) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (12:01) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura no intervalo meio dia (12:01) e resposta entre 14:00 e 17:59';
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura no intervalo meio dia (12:01) e resposta depois das 18:00
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura no intervalo meio dia (12:01) e resposta depois das 18:01
                                    }
                                }
                            }

                        }else if($horario_abertura_date->format('H') == $hora_expediente_inicial_tarde){
                            if($horario_abertura_date->format('i') < $minutos_expediente_inicial_tarde){
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$hora_expediente_inicial_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (13:29) e resposta intervalo meio dia (13:29)
                                        }
                                    }
                                }

                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (13:29) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (13:29) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura no intervalo meio dia (13:29) e resposta entre 14:00 e 17:59';
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura no intervalo meio dia (13:29) e resposta depois das 18:00
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura no intervalo meio dia (13:29) e resposta depois das 18:01
                                    }
                                }

                            }

                        }else if($horario_abertura_date->format('H') != $hora_expediente_final_manha && $horario_abertura_date->format('H') != $hora_expediente_inicial_tarde){
                            //Abertura entre 12:01 e 13:29
                            if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta intervalo meio dia (12:01)
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$hora_expediente_inicial_manha.':00'));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta intervalo meio dia (13:29)
                                        }
                                    }
                                }

                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta de tarde (13:30)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                            //id teste = ???????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos = ($depois - $antes);

                                            //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta de tarde (18:00)';
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                        //id teste = ???????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos = ($depois - $antes);

                                        //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta entre 14:00 e 17:59';
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                            //id teste = ?????
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                            $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                            //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta depois das 18:00
                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                        //id teste = ?????
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00'));
                                        $tempo_segundos = ($depois - $antes) - $tempo_total_intervalo_meio_dia_segundos;

                                        //abertura no intervalo meio dia (entre 12:01 e 13:29) e resposta depois das 18:01
                                    }
                                }
                            }
                        }

                    }

                    //_____________________RETORNO AQUI_____________________________________
                    
                    $tempo_minutos = $tempo_segundos/60;
                    $tempo_primeira_resposta_minutos = (int)$tempo_minutos - $tempo_trabalhado_minutos;
                    
                    if($tempo_primeira_resposta_minutos < 0){
                        $tempo_primeira_resposta_minutos = 0;
                    }

                    $horas = intval($tempo_segundos/3600);
                    $minutos = intval(($tempo_segundos%3600)/60);
                    $segundos = intval(($tempo_segundos%3600)%60);
                    
                    // return $teste_horarios.' | '.$horas.' h, '.$minutos.' m,'.$segundos.' s<br>'.$tempo_primeira_resposta_minutos;
                    return $tempo_primeira_resposta_minutos;
                    

 
 
                }else{
                    //$teste_horarios = $tempo_trabalhado_minutos;

                    $teste = '';
                    $tempo_segundos = 0;
                    foreach (rangeDatas($horario_abertura_date->format('Y-m-d'), $horario_primeira_resposta_date->format('Y-m-d')) as $data) {		
                        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                        $data_numero = date($data);
                        $diasemana_numero = date('w', strtotime($data_numero));

                        $mes_ano_data = explode('-', $data);
                        $mes_ano_data = $mes_ano_data[1].'-'.$mes_ano_data[2];
                        $feriado_data = DBRead('', 'tb_feriado', "WHERE tipo='Nacional' AND data = '".$mes_ano_data."' ");

                        if($diasemana[$diasemana_numero] != 'Sabado' && $diasemana[$diasemana_numero] != 'Domingo' && !$feriado_data){
                            
                            if($data == $horario_abertura_date->format('Y-m-d')){
                                $teste .= $horario_abertura_date->format('Y-m-d H:i:s');

                               
                                if($horario_abertura_date->format('H') >= $hora_expediente_inicial_manha && $horario_abertura_date->format('H') <= $hora_expediente_final_manha){
                                    //Hora é 8 os minutos são a partir de 30
                                    if($horario_abertura_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_abertura_date->format('i') >= $minutos_expediente_inicial_manha){
            
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                        }
                                    }
                                    
                                    //Hora é 12 os minutos são menores ou iguais que 00                        
                                    if($horario_abertura_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_abertura_date->format('i') <= $minutos_expediente_final_manha){

                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                        }
                                    }
            
                                    //Normal manhã                        
                                    if($horario_abertura_date->format('H') != $hora_expediente_inicial_manha && $horario_abertura_date->format('H') != $hora_expediente_final_manha){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                        $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                    }            
                                }
                                
                                //abertura turno da tarde
                                if($horario_abertura_date->format('H') >= $hora_expediente_inicial_tarde && $horario_abertura_date->format('H') <= $hora_expediente_final_tarde){
            
                                    //Hora é 13 os minutos são a partir de 30
                                    if($horario_abertura_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_abertura_date->format('i') >= $minutos_expediente_inicial_tarde){
                                            
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00' ));
                                            $tempo_segundos += ($depois - $antes);
            
                                        }
                                    }
                                    
                                    //Hora é 18 os minutos são menores ou iguais que 00                        
                                    if($horario_abertura_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_abertura_date->format('i') <= $minutos_expediente_final_tarde){
                                            
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00' ));
                                            $tempo_segundos += ($depois - $antes);

                                        }
                                    }
            
                                    //Normal tarde                        
                                    if($horario_abertura_date->format('H') != $hora_expediente_inicial_tarde && $horario_abertura_date->format('H') != $hora_expediente_final_tarde){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $horario_abertura_date->format ( 'H:i:s' )));
                                        $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_tarde.':'.$minutos_expediente_final_tarde.':00' ));
                                        $tempo_segundos += ($depois - $antes);   
                                    }
                                }
            
                                //abertura antes das 8:30
                                if($horario_abertura_date->format('H') <= $hora_expediente_inicial_manha){
                                    if($horario_abertura_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_abertura_date->format('i') < $minutos_expediente_inicial_manha){
                                           
                                            $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;

                                        }
                                    }
                                    
                                    //antes das 07:59
                                    if($horario_abertura_date->format('H') < $hora_expediente_inicial_manha){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_abertura_date->format ( 'Y-m-d' ),  $hora_expediente_final_manha.':'.$minutos_expediente_final_manha.':00' ));
                                        $tempo_segundos += ($depois - $antes) + $tempo_total_turno_tarde_segundos;
            
                                    }
                                }
            
                                //abertura depois das 18:00    
                                if($horario_abertura_date->format('H') >= $hora_expediente_final_tarde){
                                   
                                    $tempo_segundos += 0;
                                    
                                }
            
                                //abertura entre 12:01 e 13:29   
                                if($horario_abertura_date->format('H') >= $hora_expediente_final_manha && $horario_abertura_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_abertura_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_abertura_date->format('i') > $minutos_expediente_final_manha){
                                           
                                            $tempo_segundos += $tempo_total_turno_tarde_segundos;

                                        }
            
                                    }else if($horario_abertura_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_abertura_date->format('i') < $minutos_expediente_inicial_tarde){
                                            
                                            $tempo_segundos += $tempo_total_turno_tarde_segundos;
            
                                        }
            
                                    }else if($horario_abertura_date->format('H') != $hora_expediente_final_manha && $horario_abertura_date->format('H') != $hora_expediente_inicial_tarde){
                                        //Abertura entre 12:01 e 13:29
                                        if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                           
                                            $tempo_segundos += $tempo_total_turno_tarde_segundos;

                                        }
                                    }
                                }

                            }else if($data == $horario_primeira_resposta_date->format('Y-m-d')){
                                $teste .= $horario_primeira_resposta_date->format('Y-m-d H:i:s');

                                //Resposta na manhã antes das 07:59
                                if($horario_primeira_resposta_date->format('H') < $hora_expediente_inicial_manha){
                                   
                                    $tempo_segundos += 0;
                                    
                                }

                                //Resposta na manhã antes das 08:30
                                if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                    if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_manha){

                                        $tempo_segundos += 0;

                                    }
                                }

                                //Resposta na manhã
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_manha){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_manha){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_manha){

                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes);

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_inicial_manha){
                                           
                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes);

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_manha && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_manha){
                                        
                                        $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_manha.':'.$minutos_expediente_inicial_manha.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos += ($depois - $antes);

                                    }
                                }
                                
                                //Resposta de tarde
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') <= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') >= $minutos_expediente_inicial_tarde){
                                           
                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_manha_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') <= $minutos_expediente_final_tarde){
                                              
                                            $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                            $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                            $tempo_segundos += ($depois - $antes) + $tempo_total_turno_manha_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') != $hora_expediente_inicial_tarde && $horario_primeira_resposta_date->format('H') != $hora_expediente_final_tarde){
                                              
                                        $antes = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $hora_expediente_inicial_tarde.':'.$minutos_expediente_inicial_tarde.':00'));
                                        $depois = strtotime(sprintf('%s %s', $horario_primeira_resposta_date->format ( 'Y-m-d' ), $horario_primeira_resposta_date->format ( 'H:i:s' )));
                                        $tempo_segundos += ($depois - $antes) + $tempo_total_turno_manha_segundos;

                                    }
                                }
                                
                                //Resposta intervalo meio dia
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_manha && $horario_primeira_resposta_date->format('H') <= $hora_expediente_inicial_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_manha){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_manha){
                                            
                                            $tempo_segundos += $tempo_total_turno_manha_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') == $hora_expediente_inicial_tarde){
                                        if($horario_primeira_resposta_date->format('i') < $minutos_expediente_inicial_tarde){
                                                
                                            $tempo_segundos += $tempo_total_turno_manha_segundos;

                                        }
                                    }
                                }

                                //Resposta depois das 18:00
                                if($horario_primeira_resposta_date->format('H') >= $hora_expediente_final_tarde){
                                    if($horario_primeira_resposta_date->format('H') == $hora_expediente_final_tarde){
                                        if($horario_primeira_resposta_date->format('i') > $minutos_expediente_final_tarde){
                                                
                                            $tempo_segundos += $tempo_total_turno_manha_segundos + $tempo_total_turno_tarde_segundos;

                                        }

                                    }else if($horario_primeira_resposta_date->format('H') > $hora_expediente_final_tarde){
                                                
                                        $tempo_segundos += $tempo_total_turno_manha_segundos + $tempo_total_turno_tarde_segundos;

                                    }
                                }
                            
                            }else{
                                $tempo_segundos +=  $tempo_total_turno_manha_segundos + $tempo_total_turno_tarde_segundos;
                            }

                        }else{
                            $teste .= $data;

                            $tempo_segundos += 0;
                        }

                        if($feriado_data){
                            $teste .= " | Feriado";
                        }else if($diasemana[$diasemana_numero] == 'Sabado'){
                            $teste .= " | Sabado";
                        }else if($diasemana[$diasemana_numero] == 'Domingo'){
                            $teste .= " | Domingo";
                        }else{
                            $teste .= " | Outro";    
                        }
                        $teste .= "<br>";
                    }

                    $tempo_minutos = $tempo_segundos/60;
                    $tempo_primeira_resposta_minutos = (int)$tempo_minutos - $tempo_trabalhado_minutos;

                    if($tempo_primeira_resposta_minutos < 0){
                        $tempo_primeira_resposta_minutos = 0;
                    }

                    $horas = intval($tempo_segundos/3600);
                    $minutos = intval(($tempo_segundos%3600)/60);
                    $segundos = intval(($tempo_segundos%3600)%60);
                    
                    // return $teste.'<br>'.$teste_horarios.' | '.$horas.' h, '.$minutos.' m, '.$segundos.' s<br>'.$tempo_primeira_resposta_minutos;
                    return $tempo_primeira_resposta_minutos;

                  
                }
            }

        }else{
            return 0;
        }
    
    }else{
        return 0;
    }
}
?>