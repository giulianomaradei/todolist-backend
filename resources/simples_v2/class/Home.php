<?php
    require_once(__DIR__."/System.php");

    $parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : array();
    $id_usuario = $_SESSION['id_usuario'];
    $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
    $perfil_usuario = $dados[0]['id_perfil_sistema'];

    $hoje = getDataHora();
	$hoje = explode(" ", $hoje);
	$hoje = explode("-", $hoje[0]);
    $hoje_dia = $hoje[1]."-".$hoje[2];

    $data_de_hoje = new DateTime(getDataHora('data'));
    
    $data_de_hoje_completa = new DateTime(getDataHora('data'));
    $data_de_hoje_completa->modify('last day of next month');
    $data_de_hoje_completa =    $data_de_hoje_completa->format('Y-m-d');
    
    $data_final_reajuste = new DateTime(getDataHora('data'));
    $data_final_reajuste->modify('last day of this month');

    //SE FOR ATENDENTE EXTERNO
    if($perfil_usuario == '28'){
        echo '<div class="alert alert-info text-center">Bem-Vindo!</div>';
        exit;
    }

    $dados_consulta_reajuste = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c on a.id_plano = c.id_plano WHERE a.status = '1' AND a.data_ajuste <= '".$data_final_reajuste->format('Y-m-d')."' ORDER BY a.data_ajuste ASC, c.cod_servico ASC, a.data_ajuste ASC","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico");
    
    $aniversarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_vinculo_pessoa d ON a.id_pessoa = d.id_pessoa_pai INNER JOIN tb_vinculo_tipo_pessoa e ON d.id_vinculo_pessoa = e.id_vinculo_pessoa INNER JOIN tb_pessoa f ON d.id_pessoa_filho = f.id_pessoa WHERE 1 AND a.id_pessoa = '2' AND a.status != 2 AND f.data_nascimento like '%".$hoje_dia."' AND f.status != 2 ORDER BY nome_filho ASC", "a.*, f.nome AS nome_filho, f.data_nascimento AS data_nascimento_filho, f.fone1 AS fone1_filho, d.id_vinculo_pessoa");

    $aniversarios_contrato = DBRead('', 'tb_pessoa', "WHERE cliente = 1 AND status = 1", 'id_pessoa, nome');

    $empresas_aniversariantes = array();

    foreach($aniversarios_contrato as $conteudo){

        $id_pessoa = $conteudo['id_pessoa'];
        $nome = $conteudo['nome'];

        $contratos = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_pessoa = '$id_pessoa' ", 'id_contrato_plano_pessoa, id_responsavel, data_inicio_contrato, status');

        if($contratos && $id_pessoa != 2){

            $cont_contrato = 0;
            $data_aniversario = '';
            foreach($contratos as $c){
                
                $id_responsavel = $c['id_responsavel'];

                if($data_aniversario == ''){
                    $data_aniversario = $c['data_inicio_contrato'];
                }else{
                    if($data_aniversario > $c['data_inicio_contrato']){
                        $data_aniversario = $c['data_inicio_contrato'];
                    }
                }

                if($c['status'] == 1){
                    $cont_contrato++; 
                }
            }

            if($cont_contrato > 0){
                $empresas_aniversariantes[$id_pessoa]['id_pessoa'] = $id_pessoa;
                $empresas_aniversariantes[$id_pessoa]['nome'] = $nome;
                $empresas_aniversariantes[$id_pessoa]['id_responsavel'] = $id_responsavel;
                $empresas_aniversariantes[$id_pessoa]['data_inicio_contrato'] = $data_aniversario;
            }
        }
    }

    $emp_faz_aniversario = array();
    foreach($empresas_aniversariantes as $key => $value){

        $data_inicio_contrato = explode(" ", $value['data_inicio_contrato']);
        $data_inicio_contrato = explode("-", $data_inicio_contrato[0]);
        $mes_aniversario = $data_inicio_contrato[1];
        $dia_aniversario = $data_inicio_contrato[2];
        $data_inicio_contrato = $data_inicio_contrato[1]."-".$data_inicio_contrato[2];

        $dateTime = new DateTime(getDataHora());
        $dateTime->modify('first day of next month');
        $proximo_mes = $dateTime->format("m");

        if($proximo_mes == $mes_aniversario){
            $emp_faz_aniversario[$value['id_pessoa']]['id_pessoa'] = $value['id_pessoa'];
            $emp_faz_aniversario[$value['id_pessoa']]['nome'] = $value['nome'];
            $emp_faz_aniversario[$value['id_pessoa']]['id_responsavel'] = $value['id_responsavel'];
            $emp_faz_aniversario[$value['id_pessoa']]['data_inicio_contrato'] = $value['data_inicio_contrato'];
            $emp_faz_aniversario[$value['id_pessoa']]['dia_aniversario'] = $dia_aniversario;
        }   
    }

    $qtd_emp_aniversarios = sizeof($emp_faz_aniversario);
    
    if($aniversarios){
        $notifica_aniversario = 'color: #c18f00;';
    }
    else{
        $notifica_aniversario = '';
    }

    //panel
    $panel_aberto = $parametros['panel_aberto'];

    if($panel_aberto == 'topicos' || !$panel_aberto){
        $in_topico = 'class="collapse in"';
    }else{
        $in_topico = 'class="collapse"';
    }

    if($panel_aberto == 'chamados'){
        $in_chamado = 'class="collapse in"';
    }else{
        $in_chamado = 'class="collapse"';
    }

    /* if($panel_aberto == 'pessoas_contratos'){
        $in_pessoas_contratos = 'class="collapse in"';
    }else{
        $in_pessoas_contratos = 'class="collapse"';
    } */

    if($panel_aberto == 'erros'){
        $in_erro = 'class="collapse in"';
    }else{
        $in_erro = 'class="collapse"';
    }
    //end panel

    if($perfil_usuario == '2' || $perfil_usuario == '6' || $perfil_usuario == '7' || $perfil_usuario == '10' || $perfil_usuario == '11' || $perfil_usuario == '20' || $perfil_usuario == '22' || $perfil_usuario == '24' || $perfil_usuario == '26' || $perfil_usuario == '18'){
        $tamanho = 'class="col-md-4"';
    }else{
        $tamanho = 'class="col-md-4"';
    }

    if($perfil_usuario != 19){
        //topicos
            $cont_topicos = 0;
            $dados_topicos = DBRead('','tb_topico a',"INNER JOIN tb_perfil_topico b ON a.id_topico = b.id_topico WHERE b.id_perfil_sistema = '$perfil_usuario' AND a.status != 2 AND a.id_pai = 0 AND b.id_topico NOT IN (SELECT c.id_topico FROM tb_topico_visualizado c WHERE c.id_usuario = '$id_usuario' AND c.data_lido IS NOT NULL) GROUP BY b.id_topico ORDER BY a.data_criacao DESC",'b.id_topico');

            if($dados_topicos){
                $cont_topicos = sizeof($dados_topicos);
            }

            $cont_topicos_comentarios = 0;
            $dados_comentarios = DBRead('','tb_topico a',"INNER JOIN tb_perfil_topico b ON a.id_pai = b.id_topico INNER JOIN tb_topico d ON a.id_pai = d.id_topico WHERE (b.id_perfil_sistema = '$perfil_usuario' OR d.id_usuario = '$id_usuario') AND a.status != 2 AND d.status != 2 AND a.id_pai != 0 AND a.id_topico NOT IN (SELECT c.id_topico FROM tb_topico_visualizado c WHERE c.id_usuario = '$id_usuario') AND a.id_pai IN (SELECT e.id_topico FROM tb_topico_visualizado e WHERE e.id_usuario = '$id_usuario' AND e.data_lido IS NOT NULL) GROUP BY a.id_pai",'a.id_pai');
            
            if($dados_comentarios){
                $cont_topicos_comentarios = sizeof($dados_comentarios);
            }
            $total_topicos = $cont_topicos + $cont_topicos_comentarios;
        //end topicos
        
        //erros
            $cont_erros = '';
            $dados_erro = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_erro_atendimento_lider b ON a.id_erro_atendimento = b.id_erro_atendimento WHERE a.id_usuario = '".$id_usuario."' AND status != 2 AND ((a.justificativa = '' OR a.justificativa IS NULL) OR (a.precaucao_futura= '' OR a.precaucao_futura IS NULL) OR (b.parecer= '' OR b.parecer IS NULL))",'a.*, b.parecer');

            $lider = DBRead('', 'tb_usuario', "WHERE lider_direto = '$id_usuario'");

            $cont_erros_lider = '';
            
            $cont_erros_total = '0';

            if($lider || $perfil_usuario == 14){

                if($perfil_usuario == 14){
                    $dados_erro_lider = DBRead('', 'tb_erro_atendimento_lider a', "INNER JOIN  tb_erro_atendimento b ON a.id_erro_atendimento = b.id_erro_atendimento WHERE b.status != 2 AND ((b.justificativa = '' OR b.justificativa IS NULL) OR (b.precaucao_futura= '' OR b.precaucao_futura IS NULL) OR (a.parecer= '' OR a.parecer IS NULL))");

                    if($dados_erro_lider){
                        $cont_erros_lider = sizeof($dados_erro_lider);
                        $cont_erros_total = (int)$cont_erros_total + (int)$cont_erros_lider;
                    }            
                }else{
                    foreach ($lider as $liderado) {
                        $dados_erro_lider = DBRead('', 'tb_erro_atendimento_lider a', "INNER JOIN  tb_erro_atendimento b ON a.id_erro_atendimento = b.id_erro_atendimento WHERE b.id_usuario = '".$liderado['id_usuario']."' AND b.status != 2 AND ((b.justificativa = '' OR b.justificativa IS NULL) OR (b.precaucao_futura = '' OR b.precaucao_futura IS NULL) OR (a.parecer = '' OR a.parecer IS NULL))");

                        if($dados_erro_lider){
                            $cont_erros_lider = sizeof($dados_erro_lider);

                            $cont_erros_total = (int)$cont_erros_total + (int)$cont_erros_lider;
                        }
                    } 
                }
                
            }

            if($dados_erro){
                $cont_erros = sizeof($dados_erro);
            }

            $cont_erros_total = (int)$cont_erros_total + (int)$cont_erros;
        //end erros
        
        //CHAMADOS encerrados
            $cont_chamados_encerrados = 0;

            //meu
            //ESSE
            $chamados_encerrados = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_chamado_origem d ON d.id_chamado_origem = a.id_chamado_origem INNER JOIN tb_chamado_status e ON e.id_chamado_status = a.id_chamado_status INNER JOIN tb_chamado_acao f ON f.id_chamado = a.id_chamado WHERE f.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1) AND (a.id_chamado_status = '3' OR a.id_chamado_status = '4') AND ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '$perfil_usuario' AND id_chamado = a.id_chamado) OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$id_usuario' AND id_chamado = a.id_chamado) ) OR a.id_usuario_remetente = '$id_usuario' OR a.id_usuario_responsavel = '$id_usuario') AND a.id_chamado NOT IN (SELECT f.id_chamado FROM tb_chamado_ignora f WHERE f.id_usuario = '$id_usuario' AND f.id_chamado = a.id_chamado) AND a.id_chamado AND ((SELECT h.id_chamado_acao FROM tb_chamado_acao h WHERE h.id_chamado = a.id_chamado ORDER BY h.id_chamado_acao DESC LIMIT 1) != (SELECT id_chamado_acao FROM tb_chamado_visualizacao i WHERE i.id_chamado = a.id_chamado AND i.id_usuario = '$id_usuario' ORDER BY i.id_chamado_acao DESC LIMIT 1) OR (SELECT COUNT(id_chamado_acao) FROM tb_chamado_visualizacao i WHERE i.id_chamado = a.id_chamado AND i.id_usuario = '$id_usuario') = 0) ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.titulo, a.data_criacao, c.nome AS responsavel, d.id_chamado_origem, d.descricao AS descricao_origem, e.descricao AS descricao_status, f.acao, f.id_usuario_acao AS usuario_acao, f.acao_painel, CASE WHEN a.id_chamado_origem = 4 THEN (SELECT bb.nome FROM bd_simples.tb_usuario_painel aa INNER JOIN tb_pessoa bb ON aa.id_pessoa_usuario = bb.id_pessoa WHERE aa.id_usuario_painel = a.id_usuario_remetente) ELSE (SELECT bb.nome FROM bd_simples.tb_usuario aa INNER JOIN tb_pessoa bb ON aa.id_pessoa = bb.id_pessoa WHERE aa.id_usuario = a.id_usuario_remetente) END AS remetente');

            //meu

            if($chamados_encerrados){
                $cont_chamados_encerrados = sizeof($chamados_encerrados);
            }
            
        //end chamados encerrados
        
        //CHAMADOS responsavel           
            //meu
            //ESSE
                $cont_chamados_responsavel = 0;
                $chamados_responsavel = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_chamado_origem d ON d.id_chamado_origem = a.id_chamado_origem INNER JOIN tb_chamado_status e ON e.id_chamado_status = a.id_chamado_status INNER JOIN tb_chamado_acao f ON f.id_chamado = a.id_chamado WHERE f.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND a.id_usuario_responsavel = '$id_usuario' AND a.data_pendencia IS NULL ORDER BY a.id_chamado DESC", "a.id_chamado, a.id_chamado_origem, a.prazo_encerramento, a.titulo, a.data_criacao, c.nome AS responsavel, d.descricao AS descricao_origem, e.descricao AS descricao_status, f.acao, f.id_usuario_acao, f.acao_painel, CASE WHEN a.id_chamado_origem = 4 THEN (SELECT bb.nome FROM bd_simples.tb_usuario_painel aa INNER JOIN tb_pessoa bb ON aa.id_pessoa_usuario = bb.id_pessoa WHERE aa.id_usuario_painel = a.id_usuario_remetente) ELSE (SELECT bb.nome FROM bd_simples.tb_usuario aa INNER JOIN tb_pessoa bb ON aa.id_pessoa = bb.id_pessoa WHERE aa.id_usuario = a.id_usuario_remetente) END AS remetente");
               
                
                if($chamados_responsavel){
                    $cont_chamados_responsavel = sizeof($chamados_responsavel);
                }
            //meu
       //end chamados responsavel

        //PENDENCIAS responsavel
            $data = getDataHora();
            $cont_chamados_pendencia_responsavel = 0;

            $chamado_pendencia_responsavel = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_remetente INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_origem l ON a.id_chamado_origem = l.id_chamado_origem INNER JOIN tb_chamado_status m ON a.id_chamado_status = m.id_chamado_status INNER JOIN tb_chamado_acao n ON n.id_chamado = a.id_chamado INNER JOIN tb_usuario q ON q.id_usuario = n.id_usuario_acao INNER JOIN tb_pessoa p ON p.id_pessoa = q.id_pessoa WHERE n.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND a.id_usuario_responsavel = '$id_usuario' AND a.data_pendencia <= '".getDataHora()."' ORDER BY a.id_chamado DESC", 'a.id_chamado, a.titulo, c.nome AS remetente, e.nome AS responsavel, l.descricao AS descricao_origem, a.data_criacao, m.descricao AS descricao_status, n.acao, p.nome AS usuario_acao, a.prazo_encerramento');

            if($chamado_pendencia_responsavel){
                $cont_chamados_pendencia_responsavel = sizeof($chamado_pendencia_responsavel);
            }
        //end pendencias responsavel

        //CHAMADOS remetente
            $cont_chamados_remetente = array();
            $chamados_remetente = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_remetente INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_origem l ON a.id_chamado_origem = l.id_chamado_origem INNER JOIN tb_chamado_status m ON a.id_chamado_status = m.id_chamado_status INNER JOIN tb_chamado_acao n ON n.id_chamado = a.id_chamado INNER JOIN tb_usuario q ON q.id_usuario = n.id_usuario_acao INNER JOIN tb_pessoa p ON p.id_pessoa = q.id_pessoa WHERE n.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND a.id_chamado_origem != 4 AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND a.id_usuario_remetente = '$id_usuario' AND a.data_pendencia IS NULL ORDER BY a.id_chamado DESC", 'a.id_chamado, a.prazo_encerramento, a.titulo, a.id_usuario_responsavel, c.nome AS remetente, e.nome AS responsavel, l.descricao AS descricao_origem, a.data_criacao, m.descricao AS descricao_status, n.acao, p.nome AS usuario_acao');

            if($chamados_remetente){
                $cont_chamados_remetente = sizeof($cont_chamados_remetente);
            }else{
                $cont_chamados_remetente = 0;
            }
        //end chamados remetente

        //PENDENCIAS sou remetente
            $data = getDataHora();
            $cont_chamados_pendencia_remetente = 0;
            //f.id_pessoa - remetente; g.id_pessoa - responsavel
            $chamado_pendencia_remetente = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_remetente INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_origem l ON a.id_chamado_origem = l.id_chamado_origem INNER JOIN tb_chamado_status m ON a.id_chamado_status = m.id_chamado_status INNER JOIN tb_chamado_acao n ON n.id_chamado = a.id_chamado INNER JOIN tb_usuario q ON q.id_usuario = n.id_usuario_acao INNER JOIN tb_pessoa p ON p.id_pessoa = q.id_pessoa WHERE n.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND a.id_chamado_origem != 4 AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND a.id_usuario_remetente = '$id_usuario' AND a.data_pendencia <= '".getDataHora()."' ORDER BY a.id_chamado DESC", 'a.id_chamado, a.titulo, a.id_usuario_responsavel, c.nome AS remetente, e.nome AS responsavel, l.descricao AS descricao_origem, a.data_criacao, m.descricao AS descricao_status, n.acao, p.nome AS usuario_acao, a.prazo_encerramento');

            if($chamado_pendencia_remetente){
                $cont_chamados_pendencia_remetente = sizeof($chamado_pendencia_remetente);
            }
        //end pendencias sou remetente

        //CHAMADOS meu setor         

            //meu
            //ESSE
                $cont_chamados_setor_ersponsavel = 0;
                $meu_setor_responsavel = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_chamado_origem d ON d.id_chamado_origem = a.id_chamado_origem INNER JOIN tb_chamado_status e ON e.id_chamado_status = a.id_chamado_status INNER JOIN tb_chamado_acao f ON f.id_chamado = a.id_chamado WHERE f.id_chamado_acao = (SELECT f.id_chamado_acao FROM tb_chamado_acao f WHERE f.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1) AND b.id_perfil_sistema = '$perfil_usuario' AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '$perfil_usuario' AND id_chamado = a.id_chamado) OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$id_usuario' AND id_chamado = a.id_chamado)) AND (a.id_usuario_remetente != '$id_usuario' AND a.id_usuario_responsavel != '$id_usuario')) AND a.data_pendencia IS NULL ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.prazo_encerramento, a.titulo, a.id_chamado_origem, c.nome AS responsavel, d.descricao AS descricao_origem, a.data_criacao, e.descricao as descricao_status, f.acao, f.acao_painel, a.id_chamado_origem, f.id_usuario_acao, f.id_chamado_acao, CASE WHEN a.id_chamado_origem = 4 THEN (SELECT bb.nome FROM bd_simples.tb_usuario_painel aa INNER JOIN tb_pessoa bb ON aa.id_pessoa_usuario = bb.id_pessoa WHERE aa.id_usuario_painel = a.id_usuario_remetente) ELSE (SELECT bb.nome FROM bd_simples.tb_usuario aa INNER JOIN tb_pessoa bb ON aa.id_pessoa = bb.id_pessoa WHERE aa.id_usuario = a.id_usuario_remetente) END AS remetente');

                if($meu_setor_responsavel){
                    $cont_chamados_setor_ersponsavel = sizeof($meu_setor_responsavel);
                }

            //meu
        //end chamados meu setor

        //PENDENCIAS meu setor
            $data = getDataHora();
            $cont_chamados_pendencia_setor = 0;
            $chamado_pendencia_setor = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_remetente INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_origem l ON a.id_chamado_origem = l.id_chamado_origem INNER JOIN tb_chamado_status m ON a.id_chamado_status = m.id_chamado_status INNER JOIN tb_usuario n ON a.id_usuario_responsavel = n.id_usuario INNER JOIN tb_usuario r ON a.id_usuario_responsavel = r.id_usuario INNER JOIN tb_chamado_acao o ON o.id_chamado = a.id_chamado INNER JOIN tb_usuario q ON q.id_usuario = o.id_usuario_acao INNER JOIN tb_pessoa p ON p.id_pessoa = q.id_pessoa WHERE o.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1) AND n.id_perfil_sistema = '$perfil_usuario' AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '$perfil_usuario' AND id_chamado = a.id_chamado) OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$id_usuario' AND id_chamado = a.id_chamado)) AND (a.id_usuario_remetente != '$id_usuario' AND a.id_usuario_responsavel != '$id_usuario')) AND a.data_pendencia <= '".getDataHora()."' GROUP BY a.id_chamado, c.nome, e.nome, o.acao, p.nome ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.titulo, a.id_chamado_origem, c.nome AS remetente, e.nome AS responsavel, l.descricao AS descricao_origem, a.data_criacao, m.descricao as descricao_status, o.acao, p.nome AS usuario_acao, a.prazo_encerramento');

            if($chamado_pendencia_setor){
                $cont_chamados_pendencia_setor = sizeof($chamado_pendencia_setor);
            }
        //end pendencias meu setor
        
        //CHAMADOS outros
            $cont_chamados_envolvidos = 0;

            //meu
            //ESSE
                $que_estou_envolvido = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_chamado_origem d ON d.id_chamado_origem = a.id_chamado_origem INNER JOIN tb_chamado_status e ON e.id_chamado_status = a.id_chamado_status INNER JOIN tb_chamado_acao f ON f.id_chamado = a.id_chamado WHERE f.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND b.id_perfil_sistema != '$perfil_usuario' AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND (EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '$perfil_usuario' AND id_chamado = a.id_chamado) OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$id_usuario' AND id_chamado = a.id_chamado) ) AND (a.id_usuario_remetente != '$id_usuario' AND a.id_usuario_responsavel != '$id_usuario') AND a.data_pendencia IS NULL ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.titulo, a.prazo_encerramento, a.id_chamado_origem, c.nome AS responsavel, d.descricao AS descricao_origem, a.data_criacao, e.descricao as descricao_status, f.acao, f.acao_painel, f.id_chamado_acao, f.id_usuario_acao AS usuario_acao, CASE WHEN a.id_chamado_origem = 4 THEN (SELECT bb.nome FROM bd_simples.tb_usuario_painel aa INNER JOIN tb_pessoa bb ON aa.id_pessoa_usuario = bb.id_pessoa WHERE aa.id_usuario_painel = a.id_usuario_remetente) ELSE (SELECT bb.nome FROM bd_simples.tb_usuario aa INNER JOIN tb_pessoa bb ON aa.id_pessoa = bb.id_pessoa WHERE aa.id_usuario = a.id_usuario_remetente) END AS remetente');

            //meu
            
            if($que_estou_envolvido){
                $cont_chamados_envolvidos = sizeof($que_estou_envolvido);
            }
            
        //end chamados outros

        //PENDENCIAS outros
            $data = getDataHora();
            $cont_chamados_pendencia_outros = 0;
            //f.id_pessoa - remetente; g.id_pessoa - responsavel
            $chamado_pendencia_outros = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_remetente INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_origem l ON a.id_chamado_origem = l.id_chamado_origem INNER JOIN tb_chamado_status m ON a.id_chamado_status = m.id_chamado_status INNER JOIN tb_usuario n ON a.id_usuario_responsavel = n.id_usuario INNER JOIN tb_chamado_acao o ON o.id_chamado = a.id_chamado INNER JOIN tb_usuario q ON q.id_usuario = o.id_usuario_acao INNER JOIN tb_pessoa p ON p.id_pessoa = q.id_pessoa WHERE o.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND n.id_perfil_sistema != '$perfil_usuario' AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '$perfil_usuario' AND id_chamado = a.id_chamado) OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$id_usuario' AND id_chamado = a.id_chamado) ) AND (a.id_usuario_remetente != '$id_usuario' AND a.id_usuario_responsavel != '$id_usuario')) AND a.data_pendencia <= '".getDataHora()."' GROUP BY a.id_chamado, c.nome, e.nome, o.acao, p.nome ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.titulo, a.id_chamado_origem, c.nome AS remetente, e.nome AS responsavel, l.descricao AS descricao_origem, a.data_criacao, m.descricao as descricao_status, o.acao, p.nome AS usuario_acao, a.prazo_encerramento');

            if($chamado_pendencia_outros){
                $cont_chamados_pendencia_outros = sizeof($chamado_pendencia_outros);
            }
        //end pendencias outros

        //CHAMADOS vinculados
            $cont_chamados_vinculados = 0;

            $vinculos = getVinculos($perfil_usuario);

            if ($vinculos != '') {
                $aba = true;
                $vinculos = mudaVinculoArray($vinculos);
                
                $filtro_in = '(';
                
                foreach ($vinculos as $key => $vinculo) {
                    if ($key === array_key_last($vinculos)) {
                        $filtro_in .= $vinculo;
                    } else {
                        $filtro_in .= $vinculo.',';
                    }
                    
                }
                $filtro_in .= ')';

                $chamados_vinculados = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_chamado_origem d ON d.id_chamado_origem = a.id_chamado_origem INNER JOIN tb_chamado_status e ON e.id_chamado_status = a.id_chamado_status INNER JOIN tb_chamado_acao f ON f.id_chamado = a.id_chamado WHERE f.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND b.id_perfil_sistema != '$perfil_usuario' AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND (EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema IN $filtro_in AND id_chamado = a.id_chamado)) AND (NOT EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = $perfil_usuario AND id_chamado = a.id_chamado)) AND (a.id_usuario_remetente != '$id_usuario' AND a.id_usuario_responsavel != '$id_usuario') AND a.data_pendencia IS NULL ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.titulo, a.prazo_encerramento, a.id_chamado_origem, c.nome AS responsavel, d.descricao AS descricao_origem, a.data_criacao, e.descricao as descricao_status, f.acao, f.acao_painel, f.id_chamado_acao, f.id_usuario_acao AS usuario_acao, CASE WHEN a.id_chamado_origem = 4 THEN (SELECT bb.nome FROM bd_simples.tb_usuario_painel aa INNER JOIN tb_pessoa bb ON aa.id_pessoa_usuario = bb.id_pessoa WHERE aa.id_usuario_painel = a.id_usuario_remetente) ELSE (SELECT bb.nome FROM bd_simples.tb_usuario aa INNER JOIN tb_pessoa bb ON aa.id_pessoa = bb.id_pessoa WHERE aa.id_usuario = a.id_usuario_remetente) END AS remetente');

                if($chamados_vinculados){
                    $cont_chamados_vinculados = sizeof($chamados_vinculados);
                }
            }
        //end chamados vinculados

        //PENDENCIAS vinculados
            $data = getDataHora();
            $cont_chamados_pendencia_vinculados = 0;

            if ($vinculos != '') { 
                //f.id_pessoa - remetente; g.id_pessoa - responsavel
                $chamado_pendencia_vinculados = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_remetente INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_origem l ON a.id_chamado_origem = l.id_chamado_origem INNER JOIN tb_chamado_status m ON a.id_chamado_status = m.id_chamado_status INNER JOIN tb_usuario n ON a.id_usuario_responsavel = n.id_usuario INNER JOIN tb_chamado_acao o ON o.id_chamado = a.id_chamado INNER JOIN tb_usuario q ON q.id_usuario = o.id_usuario_acao INNER JOIN tb_pessoa p ON p.id_pessoa = q.id_pessoa WHERE o.id_chamado_acao = (SELECT o.id_chamado_acao FROM tb_chamado_acao o WHERE o.id_chamado = a.id_chamado ORDER BY data DESC LIMIT 1 ) AND n.id_perfil_sistema != '$perfil_usuario' AND a.id_chamado_status != 3 AND a.id_chamado_status != 4 AND ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema IN $filtro_in AND id_chamado = a.id_chamado)) AND (NOT EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = $perfil_usuario AND id_chamado = a.id_chamado)) AND (a.id_usuario_remetente != '$id_usuario' AND a.id_usuario_responsavel != '$id_usuario')) AND a.data_pendencia <= '".getDataHora()."' GROUP BY a.id_chamado, c.nome, e.nome, o.acao, p.nome ORDER BY a.id_chamado DESC" ,'a.id_chamado, a.titulo, a.id_chamado_origem, c.nome AS remetente, e.nome AS responsavel, l.descricao AS descricao_origem, a.data_criacao, m.descricao as descricao_status, o.acao, p.nome AS usuario_acao, a.prazo_encerramento');

                if($chamado_pendencia_vinculados){
                    $cont_chamados_pendencia_vinculados = sizeof($chamado_pendencia_vinculados);
                }
            }
        //end pendencias vinculados
        
        //contratos
            /* if($perfil_usuario == '2' || $perfil_usuario == '6' || $perfil_usuario == '7' || $perfil_usuario == '10' || $perfil_usuario == '11' || $perfil_usuario == '20' || $perfil_usuario == '22' || $perfil_usuario == '24' || $perfil_usuario == '26' || $perfil_usuario == '18'){
                $cont_pessoas_contratos = 0;

                // $notificacoes = DBRead('', 'tb_notificacao_alteracao', "WHERE id_notificacao_alteracao NOT IN (SELECT id_notificacao_alteracao FROM tb_notificacao_alteracao_lida WHERE id_usuario = '$id_usuario')", "COUNT(*) as cont");

                $notificacoes = DBRead('', 'tb_notificacao_alteracao', "WHERE id_notificacao_alteracao NOT IN (SELECT id_notificacao_alteracao FROM tb_notificacao_alteracao_lida WHERE id_usuario = '$id_usuario' GROUP BY id_notificacao_alteracao)");

                if($perfil_usuario == 6 || $perfil_usuario == '26'){
                    $cont_pessoas_contratos = 0;
                    foreach($notificacoes as $notificacao){
                        $alteracao = $notificacao['alteracao'];
                        if($alteracao != "Pessoa") {
                            $id_pessoa_pai = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_vinculo_pessoa c ON a.id_pessoa = c.id_pessoa_pai WHERE c.id_pessoa_filho = '".$notificacao['id_alterado']."' GROUP BY c.id_pessoa_pai", "a.id_pessoa");

                            $planos = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.id_contrato_plano_pessoa = '".$notificacao['id_alterado']."' GROUP BY b.cod_servico", "b.cod_servico");

                            $tem_acesso_redes = 0;
                            $tem_acesso_comercial = 0;
                    
                            if(($perfil_usuario == 6 || $perfil_usuario == '26') && $planos[0]['cod_servico'] == "gestao_redes"){
                                $cont_pessoas_contratos++;
                            }
                        }else{
                            $id_pessoa_pai = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_vinculo_pessoa c ON a.id_pessoa = c.id_pessoa_pai WHERE c.id_pessoa_filho = '".$notificacao['id_alterado']."' GROUP BY c.id_pessoa_pai", "a.id_pessoa");

                            $planos = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.id_pessoa = '".$id_pessoa_pai[0]['id_pessoa']."' GROUP BY b.cod_servico", "b.cod_servico");

                            $redes = 0;
                            foreach ($planos as $plano) {
                                if($plano['cod_servico'] == "gestao_redes"){
                                    $cont_pessoas_contratos++;
                                }
                            }
                        }
                    }
                }else{
                    if($notificacoes){
                        $cont_pessoas_contratos = sizeof($notificacoes);
                    }
                }
            } */
        //end contratos

        //escalas
            $dados_escalas = DBRead('', 'tb_horarios_escala', "WHERE data_lido IS NULL AND liberado = '1' AND id_usuario = '".$id_usuario."' ");
        //end escalas
?>
<style>
    .panel-info>.panel-heading{
        background-image:linear-gradient(to bottom, #2aabd2, #2aabd2 100%)
    }
    .cor-topico{
        background-image:linear-gradient(to bottom, #2aabd2, #2aabd2 100%)
    }
    .hr-info{
        border-color: #2aabd2;
    }

    .panel-primary>.panel-heading{
        background-image:linear-gradient(to bottom, #265a88, #265a88 100%)
    }
    .cor-chamado{
        background-image:linear-gradient(to bottom, #265a88, #265a88 100%)
    }
    .hr-primary{
        border-color: #265a88;
    }

    .panel-default>.panel-heading{
        background-image:linear-gradient(to bottom, #808080, #808080 100%)
    }
    .cor-erros{
        background-image:linear-gradient(to bottom, #808080, #808080 100%)
    }
    .hr-erros{
        border-color: #808080;
    }

    .panel-green>.panel-heading{
        background-image:linear-gradient(to bottom, #008B8B, #008B8B 100%)
    }
    .cor-green{
        background-image:linear-gradient(to bottom, #008B8B, #008B8B 100%)
    }

    .noshadow {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    .span-dash{
        font-size: 22px; 
    }
    .nav-tabs>.active>a{
        border-top-color: #265a88 !important;
        border-right-color: #265a88 !important;
        border-left-color: #265a88 !important;
    }
    .nav-tabs>li>a:hover{
        background-color: #E6E6E6;
        border-bottom-color: #265a88;

    }
    .nav-tabs {
        border-bottom: 1px solid #265a88 !important;
    }
</style>

<script>
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

    <div class="container-fluid">

        <div style="opacity: 0.8; margin-bottom: 0px;">
            <span style="font-size: 20px;"><i class="fa fa-home"></i> DASHBOARD </span> / Home
            
            <?php 

            if($qtd_emp_aniversarios > 0){
                
                $cont = 0;

                foreach ($emp_faz_aniversario as $key => $value){
                    if($value['id_responsavel'] == $_SESSION['id_usuario'] || $perfil_usuario == 2  || $perfil_usuario == '4'){
                        $cont++;
                    }
                } 

                if($cont > 0){
                    echo '<a href="#" class="pull-right btn" data-toggle="modal" data-target="#myModal_emp_aniversario"><i class="fa fa-handshake-o faa-float animated" style="font-size: 20px; color: green;"></i></a>';
                }
            }

            if($aniversarios){
                echo '<a href="#" class="pull-right btn" data-toggle="modal" data-target="#myModal_aniversario"><i class="fa fa-birthday-cake faa-float animated" style="font-size: 20px; '.$notifica_aniversario.'"></i></a>';
            }

            if(($dados_consulta_reajuste) && ($perfil_usuario == '2' || $perfil_usuario == '7' || $perfil_usuario == '10' || $perfil_usuario == '11' || $perfil_usuario == '20' || $perfil_usuario == '22' || $perfil_usuario == '24') ){

                echo '<a href="#" class="pull-right btn" data-toggle="modal" data-target="#myModal_reajuste" id="modal_id"><i class="fa fa-balance-scale faa-float animated" style="font-size: 20px; color: #4B088A;"></i></a>';
            }

            if(($perfil_usuario == 3 || $perfil_usuario == 13) && $dados_escalas){
                echo "<a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-escala-horarios&visualizar=".$id_usuario."' class='btn fa fa-table pull-right faa-float animated' style='font-size: 20px; color: green;' data-toggle='tooltip' title='Visualizar escala' data-placement='left'></a>";
            }

            ?>
        </div>
        
        <hr style="margin-top: 10px;">

        <!--  buttons -->
        <div class="row" style="margin-bottom: 15px;">
            <div <?=$tamanho?>>
                <button type="button" class="btn btn-info botao-dash cor-topico" data-dash="topicos"  data-target="#topicos" id="topicos2" style="height: 125px; width: 100%;">
                    <div class="col-md-1">
                        <i class="fa fa-comment-o" style="font-size: 70px; opacity: 0.3;"></i>
                    </div>
                    <div class="col-md-10 col-md-offset-right-1">
                        <span >
                            <h3 class="span-dash">Tópicos
                                <?php 
                                    if($total_topicos > 0){ 
                                        echo '<span class="badge" style="font-size: 15px;">'.$total_topicos.'</span>';
                                    } 
                                ?>
                            </h3>
                            <input type="hidden" id="total_topicos" value="<?= $total_topicos ?>">
                        </span>
                    </div>
                </button>
            </div>

            <div <?=$tamanho?>>
                <button type="button" class="btn btn-primary botao-dash cor-chamado" data-dash="chamados" data-target="#chamados" id="chamados2" style="height: 125px; width: 100%;">
                    <div class="col-md-1">
                        <i class="fa fa-bullhorn" style="font-size: 70px; opacity: 0.3;"></i>
                    </div>
                    <div class="col-md-10 col-md-offset-right-1">
                        <span>
                            <h3 class="span-dash">Chamados <span id='notifica_total_chamado' class="badge" style="display: none;font-size: 15px;"></span>                               
                            </h3>
                        </span>
                    </div>
                </button>
            </div>
                                    
            <?php 
           /*  if($perfil_usuario == '2' || $perfil_usuario == '6' || $perfil_usuario == '7' || $perfil_usuario == '10' || $perfil_usuario == '11' || $perfil_usuario == '20' || $perfil_usuario == '22' || $perfil_usuario == '24' || $perfil_usuario == '26' || $perfil_usuario == '18'){
                ?>
                <div <?=$tamanho?>>
                    <button type="button" class="btn btn-green botao-dash cor-contrato" data-dash="pessoas_contratos" data-target="#pessoas_contratos" id="pessoas_contratos2" style="height: 125px; width: 100%; background-color: #008B8B">
                        <div class="col-md-1">
                            <i class="fa fa-address-card-o" style="color: white;font-size: 65px; opacity: 0.3;"></i>
                        </div>
                        <div class="col-md-10 col-md-offset-right-1">
                            <span>
                                <h3 class="span-dash" style="color: white;">Contratos/Pessoas
                                    <?php 
                                        if($cont_pessoas_contratos > 0){
                                            echo '<span class="badge" id="badge_pessoas" style="font-size: 15px; background-color: white; color: #008B8B;">'.$cont_pessoas_contratos.'</span>';
                                        }

                                    ?>
                                    <input type="hidden" id="cont_total_contratos" value="<?=$cont_pessoas_contratos?>"/>
                                </h3>
                            </span>
                        </div>
                    </button>
                </div>
            <?php
            }else{
                ?>
                <input type="hidden" id="cont_total_contratos" value="0"/>
                <?php
            } */
            ?>

            <div <?=$tamanho?>>
                <button type="button" class="btn btn-secondary botao-dash cor-erros" data-dash="erros" data-target="#erros" id="erros2" style="height: 125px; width: 100%; background-color: #808080">
                    <div class="col-md-1">
                        <i class="fa fa-bug" style="color: white;font-size: 65px; opacity: 0.3;"></i>
                    </div>
                    <div class="col-md-10 col-md-offset-right-1">
                        
                        <h3 class="span-dash" style="color: white;">Reclamações/Erros
                            <?php 
                                if($cont_erros_total > 0) {
                                    echo '<span class="badge" style="color: #808080; background-color: white;font-size: 12px;">'.$cont_erros_total.'</span>';
                                }
                            ?>
                        </h3>
                    </div>
                    <input type="hidden" id="total_erros" value="<?=$cont_erros_total?>">
                </button>
            </div>
            
        </div><!-- end row -->

        <!--  topicos -->
        <div class="row">
            <div id="topicos" <?=$in_topico ?>>
                <div class="col-md-12" class="collapse" id="demo">
                    <div class='panel panel-info' style="border-color: #2aabd2">
                        <div class='panel-heading clearfix' style="color: white;">
                            <h3 class='panel-title text-left pull-left'><strong>Tópicos</strong></h3>
                            <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=topico-form&origem_topico=1"><button class="btn btn-xs btn-default"><i class="fa fa-plus"></i> Novo</button></a></div>
                        </div>
                        <div class='panel-body'>
                            <div class='row'>
                                <div class='col-md-12'>

                                    <?php
                                        if($dados_topicos){
                                    ?>  
                                    <p style='margin:0;'><strong>Novos tópicos:</strong></p>
                                    <div class='panel panel-info noshadow' style='margin:0; border:none;'>
                                        <div class='panel-body noshadow' style="border: none;">
                                            <table class='table table-hover' style='font-size: 14px; margin-bottom: 0'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-3">Título</th>
                                                        <th class="col-md-3">Categoria</th>
                                                        <th class="col-md-3">Autor</th>
                                                        <th class="col-md-2">Data</th>
                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                
                                                <?php
                                                    foreach($dados_topicos as $conteudo_topicos){                               
                                                        $cont_topicos++;
                                                        $dados_topico = DBRead('', 'tb_topico', "WHERE id_topico = '".$conteudo_topicos['id_topico']."' AND status != 2");
                                                        $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados_topico[0]['id_usuario']."'");
                                                        $dados_categoria = DBRead('', 'tb_categoria', "WHERE id_categoria = '".$dados_topico[0]['id_categoria']."'");
                                                        $id = $dados_topico[0]['id_topico'];
                                                        $titulo = $dados_topico[0]['titulo'];
                                                        $conteudo = converteDataHora($dados_topico[0]['data_criacao']);
                                                        $autor = $dados_usuario[0]['nome'];
                                                            
                                                        echo "<tr onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=topico-exibe&id=$id'\" style='cursor: pointer;'>";
                                                            echo "<td style='vertical-align: middle;'>".limitarTexto($titulo, 30)."</td>";
                                                            echo "<td style='vertical-align: middle;'>".$dados_categoria[0]['nome']."</td>";
                                                            echo "<td style='vertical-align: middle;'>$autor</td>";
                                                            echo "<td style='vertical-align: middle;'>$conteudo</td>";
                                                            echo "<td class=\"text-center\" style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=topico-exibe&id=$id' title='Visualizar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                                                        echo "</tr>";
                                                        $cont++;
                                                    }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div><!-- end panel body -->                                       
                                    </div><!-- end panel info -->
                                    <?php
                                        }else{
                                            echo "<h4 class='text-info text-center' style='margin-top:34px;margin-bottom:29px; border: black;'>Não há novos tópicos.</h4>";
                                        }
                                    ?>
                                </div><!-- end col-md-12 -->
                            </div><!-- end row -->

                            <hr class="hr-info">

                            <div class='row'>
                                <div class='col-md-12'>
                                    <?php
                                        if($dados_comentarios){
                                    ?>
                                    <p style='margin:0;'><strong>Novos comentários:</strong></p>
                                    <div class='panel panel-info noshadow' style='margin:0; border-style: none;'>
                                        <div class='panel-body noshadow'>
                                            
                                            
                                            <table class='table table-hover' style='font-size: 14px; margin-bottom: 0'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-3">Título</th>
                                                        <th class="col-md-3">Categoria</th>
                                                        <th class="col-md-3">Autor</th>
                                                        <th class="col-md-2">Data</th>
                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                <?php
                                                    foreach($dados_comentarios as $conteudo_comentarios){   

                                                        $dados_topico = DBRead('', 'tb_topico', "WHERE id_topico = '".$conteudo_comentarios['id_pai']."' AND status != 2");
                                                        $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados_topico[0]['id_usuario']."'");
                                                        $dados_categoria = DBRead('', 'tb_categoria', "WHERE id_categoria = '".$dados_topico[0]['id_categoria']."'");
                                                        $id = $dados_topico[0]['id_topico'];
                                                        $titulo = $dados_topico[0]['titulo'];
                                                        $conteudo = converteDataHora($dados_topico[0]['data_criacao']);
                                                        $autor = $dados_usuario[0]['nome'];
                                                        
                                                        echo "<tr onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=topico-exibe&id=$id'\" style='cursor: pointer;'>";
                                                            echo "<td style='vertical-align: middle;'>$titulo</td>";
                                                            echo "<td style='vertical-align: middle;'>".$dados_categoria[0]['nome']."</td>";
                                                            echo "<td style='vertical-align: middle;'>$autor</td>";
                                                            echo "<td style='vertical-align: middle;'>$conteudo</td>";
                                                            echo "<td class=\"text-center\" style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=topico-exibe&id=$id' title='Visualizar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                                                        echo "</tr>";
                                                    }
                                                ?>
                                                </tbody>
                                            </table>

                                           
                                        </div><!-- end panel body -->
                                    </div><!-- end panel info -->
                                    <?php
                                         }else{
                                            echo "<h4 class = 'text-info text-center' style='margin-top:34px;margin-bottom:38px; border: black;'>Não há novos comentários.</h4>";
                                        }
                                    ?>
                                </div><!-- end col-md-12 -->
                            </div><!-- end row -->
                        </div><!-- end panel body -->
                    </div><!-- end info -->
                </div><!-- end col-md-12 -->
            </div><!-- topicos collaps -->
        </div><!-- end row -->

        <!--  erros -->
        <div class="row">
            <div id="erros" <?=$in_erro ?>>
                <div class="col-md-12">
                    <div class='panel panel-default' style='color: #808080;margin-bottom: 14px; border-color: #808080'>
                        <div class='panel-heading'>
                            <h3 class='panel-title' style="color:white;"><strong>Reclamações/Erros</strong></h3>
                        </div>
                        <div class='panel-body'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <?php
                                        if($dados_erro){
                                    ?>  
                                    <p style='margin:0;'><strong>Minhas reclamações/erros:</strong></p>
                                    <div class='panel panel-default noshadow' style='margin:0; border:none;'>
                                        <div class='panel-body noshadow' style="border: none;">
                                            
                                            <table class='table table-hover' style='font-size: 14px; margin-bottom: 0'>
                                                <thead>
                                                    <tr>
                                                        <th>Contrato</th>
                                                        <th>Data da ocorrência</th>
                                                        <th>Criado por</th>
                                                        <th>Justificativa</th>
                                                        <th>Parecer</th>
                                                        <th class="text-center">Visualizar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                <?php

                                                    foreach($dados_erro as $dado){
                                                        if($id_usuario == $dado['id_usuario']){

                                                            $hora_erro = explode(":", $dado['hora_erro']);
                                                            $hora_erro = $hora_erro[0].":".$hora_erro[1];
                                                            $data_erro = converteData($dado['data_erro'])." ".$hora_erro;

                                                            $nome_cadastrou = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = " . $dado['id_usuario_cadastrou']);


                                                            $nome_cliente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c on b.id_plano = c.id_plano WHERE b.id_contrato_plano_pessoa = " . $dado['id_contrato_plano_pessoa']."","a.nome AS nome_empresa, b.*, c.*, a.*");

                                                            $id_erro_atendimento = $dado['id_erro_atendimento'];
                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-justificativa-form&inserir_justificativa=$id_erro_atendimento'\" style='cursor: pointer;'>";
                                                                echo "<td style='vertical-align: middle;'>".$nome_cliente[0]['nome']." - ".getNomeServico($nome_cliente[0]['cod_servico'])."</td>";
                                                                echo "<td style='vertical-align: middle;'>".$data_erro."</td>";
                                                                echo "<td style='vertical-align: middle;'>".$nome_cadastrou[0]['nome']."</td>";
                                                                if($dado['justificativa']){
                                                                    echo "<td style='vertical-align: middle;'>OK</td>";
                                                                }else{
                                                                    echo "<td style='vertical-align: middle;'>Pendente</td>";
                                                                }
                                                                if($dado['parecer']){
                                                                    echo "<td style='vertical-align: middle;'>OK</td>";
                                                                }else{
                                                                    echo "<td style='vertical-align: middle;'>Pendente</td>";
                                                                }
                                                                echo "<td style='vertical-align: middle;' class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-justificativa-form&inserir_justificativa=$id_erro_atendimento' title='Visualizar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                                                            echo "</tr>";                               
                                                        }
                                                    }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div><!-- end body -->
                                    </div><!-- end panel danger -->
                                    <?php
                                        }else{
                                            echo "<h4 class='text-default text-center' style='color: #808080;margin-top:34px;margin-bottom:29px;border: black;'>Não há novas reclamações/erros.</h4>";
                                        }
                                    ?>
                                </div><!-- end col-md-12 -->
                            </div><!-- end row -->
     
                            <?php 
                                if($lider || $perfil_usuario == 14){
                            ?>
                            <hr class="hr-erros">

                            <div class='row'>
                                <div class='col-md-12'>
                                    <?php
                                        if($cont_erros_lider){
                                            if($perfil_usuario == 14){
                                                echo "<p style='margin:0;'><strong>Reclamações/Erros pendentes:</strong></p>";
                                            }else{
                                                echo "<p style='margin:0;'><strong>Reclamações/Erros de meus liderados:</strong></p>";
                                            }
                                    ?>
                                    <div class='panel panel-default noshadow' style='margin:0; border-style: none;'>
                                        <div class='panel-body noshadow'>  
                                             
                                            <table class='table table-hover' style='font-size: 14px; margin-bottom: 0'>
                                                <thead>
                                                    <tr>
                                                        <th class = "col-md-2">Contrato</th>
                                                        <th class = "col-md-2">Nome liderado</th>
                                                        <th class = "col-md-2">Data da ocorrência</th>
                                                        <th class = "col-md-2">Justificativa</th>
                                                        <th class = "col-md-2">Parecer</th>
                                                        <th class="text-center col-md-2">Visualizar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            
                                                <?php
                                                    if($perfil_usuario == 14){
                                                        
                                                        $dados_erro_lider = DBRead('', 'tb_erro_atendimento_lider a', "INNER JOIN  tb_erro_atendimento b ON a.id_erro_atendimento = b.id_erro_atendimento WHERE b.status != 2 AND ((b.justificativa = '' OR b.justificativa IS NULL) OR (b.precaucao_futura = '' OR b.precaucao_futura IS NULL) OR (a.parecer = '' OR a.parecer IS NULL))");
                                                        if($dados_erro_lider){
                                                                foreach ($dados_erro_lider as $dado) {
                                                                    
                                                                    $cont_erros++;

                                                                    $nome_cliente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c on b.id_plano = c.id_plano WHERE b.id_contrato_plano_pessoa = " . $dado['id_contrato_plano_pessoa']."","a.nome AS nome_empresa, b.*, c.*, a.*");

                                                                    $nome_atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '". $dado['id_usuario']."'");

                                                                    $hora_erro = explode(":", $dado['hora_erro']);
                                                                    $hora_erro = $hora_erro[0].":".$hora_erro[1];
                                                                    $data_erro = converteData($dado['data_erro'])." ".$hora_erro;
                                                                    
                                                                        echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-lider&visualizar=".$dado['id_erro_atendimento']."'\" style='cursor: pointer;'>";
                                                                        echo "<td style='vertical-align: middle;'>".$nome_cliente[0]['nome_empresa']." - ".getNomeServico($nome_cliente[0]['cod_servico'])."</td>";
                                                                        echo "<td style='vertical-align: middle;'>".$nome_atendente[0]['nome']."</td>";
                                                                        echo "<td style='vertical-align: middle;'>".$data_erro."</td>";
                                                                        
                                                                        if($dado['justificativa']){
                                                                            echo "<td style='vertical-align: middle;'>OK</td>";
                                                                        }else{
                                                                            echo "<td style='vertical-align: middle;'>Pendente</td>";
                                                                        }
                                                                                                                                                
                                                                        if($dado['parecer']){
                                                                            echo "<td style='vertical-align: middle;'>OK</td>";
                                                                        }else{
                                                                            echo "<td style='vertical-align: middle;'>Pendente</td>";
                                                                        }

                                                                        echo "<td style='vertical-align: middle;' class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-lider&visualizar=".$dado['id_erro_atendimento']."' title='Visualizar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                                                                    echo "</tr>";                                                           
                                                                }
                                                            }
                                                    }else{

                                                        foreach ($lider as $liderado) {

                                                            $dados_erro_lider = DBRead('', 'tb_erro_atendimento_lider a', "INNER JOIN  tb_erro_atendimento b ON a.id_erro_atendimento = b.id_erro_atendimento WHERE b.id_usuario = '".$liderado['id_usuario']."' AND b.status != 2 AND ((b.justificativa = '' OR b.justificativa IS NULL) OR (b.precaucao_futura = '' OR b.precaucao_futura IS NULL) OR (a.parecer = '' OR a.parecer IS NULL))");
                                                            if($dados_erro_lider){
                                                                foreach ($dados_erro_lider as $dado) {
                                                                    
                                                                    $cont_erros++;

                                                                    $nome_cliente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c on b.id_plano = c.id_plano WHERE b.id_contrato_plano_pessoa = " . $dado['id_contrato_plano_pessoa']."","a.nome AS nome_empresa, b.*, c.*, a.*");

                                                                    $nome_atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '". $dado['id_usuario']."'");

                                                                    $hora_erro = explode(":", $dado['hora_erro']);
                                                                    $hora_erro = $hora_erro[0].":".$hora_erro[1];
                                                                    $data_erro = converteData($dado['data_erro'])." ".$hora_erro;
                                                                    
                                                                        echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-lider&visualizar=".$dado['id_erro_atendimento']."'\" style='cursor: pointer;'>";
                                                                        echo "<td style='vertical-align: middle;'>".$nome_cliente[0]['nome_empresa']." - ".getNomeServico($nome_cliente[0]['cod_servico'])."</td>";
                                                                        echo "<td style='vertical-align: middle;'>".$nome_atendente[0]['nome']."</td>";
                                                                        echo "<td style='vertical-align: middle;'>".$data_erro."</td>";
                                                                        
                                                                        if($dado['justificativa']){
                                                                            echo "<td style='vertical-align: middle;'>OK</td>";
                                                                        }else{
                                                                            echo "<td style='vertical-align: middle;'>Pendente</td>";
                                                                        }
                                                                                                                                                
                                                                        if($dado['parecer']){
                                                                            echo "<td style='vertical-align: middle;'>OK</td>";
                                                                        }else{
                                                                            echo "<td style='vertical-align: middle;'>Pendente</td>";
                                                                        }

                                                                        echo "<td style='vertical-align: middle;' class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-lider&visualizar=".$dado['id_erro_atendimento']."' title='Visualizar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                                                                    echo "</tr>";                                                           
                                                                }
                                                            }
                                                            
                                                        }   
                                                    }
                                                    
                                                ?>
                                                </tbody>
                                            </table>
                                        </div><!-- end panel body -->
                                    </div><!-- end panel danger -->
                                    <?php
                                        }else{
                                            if($perfil_usuario == 14){
                                                echo "<h4 class='text-danger text-center' style='color: #808080;margin-top:34px;margin-bottom: 17px;'>Não há novas reclamações/erros pendentes.</h4><br>";
                                            }else{
                                                echo "<h4 class='text-danger text-center' style='color: #808080;margin-top:34px;margin-bottom: 17px;'>Não há novas reclamações/erros de meus liderados.</h4><br>";
                                            }
                                        }
                                    ?>
                                </div><!-- end col-md-12 -->
                            </div><!-- end row -->
                            <?php 
                                }
                            ?>
                        </div><!-- end panel body -->
                    </div><!-- end danger -->
                </div><!-- end col-md-12 -->
            </div><!-- end erros -->
        </div><!-- end row -->
            
        <?php
            if($perfil_usuario == '2' || $perfil_usuario == '6' || $perfil_usuario == '7' || $perfil_usuario == '10' || $perfil_usuario == '11' || $perfil_usuario == '20' || $perfil_usuario == '22' || $perfil_usuario == '24' || $perfil_usuario == '26' || $perfil_usuario == '18'){
        ?>

        <?php 
            }//end if $id_perfil == 6 || $id_perfil == 2 || $id_perfil == 7 || $id_perfil == 11 || $id_perfil == 10 
        ?>

        <!--  chamados -->
        <div class="row">
            <div id="chamados" <?=$in_chamado ?>>
                <div class='col-md-12'>
                    <div class='panel panel-primary' style="border-color: #265a88">
                        <div class='panel-heading clearfix'>
                            <h3 class='panel-title text-left pull-left'><strong>Chamados</strong></h3>
                            <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=chamado-form&origem_chamado=1">
                                <button class="btn btn-xs btn-default"><i class="fa fa-plus"></i> Novo</button></a>
                            </div>
                        </div>
                        
                        <div class='panel-body' style="padding: 6px !important;">                            
                            
                            <!-- nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="aba2 active">
                                    <a data-toggle="tab" href="#tab2">Sou responsável <span id='notifica_tab2' class='badge' style='display: none; background-color: #204d74;font-size: 12px;'></span></a>
                                </li>
                                <li class="aba4">
                                    <a data-toggle="tab" href="#tab4">Sou remetente <span id='notifica_tab4' class='badge' style='display: none; background-color: #204d74;font-size: 12px;'></span></a>
                                </li>
                                <li class="aba3">
                                    <a data-toggle="tab" href="#tab3">Meu setor <span id='notifica_tab3' class='badge' style='display: none; background-color: #204d74;font-size: 12px;'></span></a>
                                </li>
                                <li class="aba5">
                                    <a data-toggle="tab" href="#tab5">Outros <span id='notifica_tab5' class='badge' style='display: none; background-color: #204d74;font-size: 12px;'></span></a>
                                </li>
                                <li class="aba6">
                                    <a data-toggle="tab" href="#tab6">Encerrados <span id='notifica_tab6' class='badge' style='display: none; background-color: #204d74;font-size: 12px;'></span></a>
                                </li>
                                <?php if ($aba == true) { ?>
                                <li class="aba7">
                                    <a data-toggle="tab" href="#tab7">Vinculados <span id='notifica_tab7' class='badge' style='display: none; background-color: #204d74;font-size: 12px;'></span></a>
                                </li>
                                <?php } ?>
                            </ul>
                            <!-- end nav tabs -->
                            
                            <br>

                            <div class="tab-content">

                                <!-- tab 3 meu setor  -->
                                <div id="tab3" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style='margin:0;'><strong></strong></p>
                                            <div class='panel panel-warning noshadow' style='margin:0; border:none;'>
                                                <div class='panel-body noshadow' style="border: none;">
                                                    <?php
                                                        $cont_notifica_meu_setor_responsavel = 0;
                                                        if($meu_setor_responsavel):
                                                    ?>
                                                    <label>Chamados:</label>
                                                    <div class="table-responsive">
                                                        <table class='table table-hover'>
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                    <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                    <th class="col-md-1">Remetente</th>
                                                                    <th class="col-md-1">Responsável</th>
                                                                    <th class="col-md-1">Data da criação</th>
                                                                    <th class="col-md-1">Status</th>
                                                                    <th class="col-md-1">Última ação</th>
                                                                    <th class="col-md-1">Feita por</th>
                                                                    <th class="col-md-1">Prazo</th>
                                                                    <th class="col-md-1 text-center">Visualizar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php                    
                                                                    foreach($meu_setor_responsavel as $conteudo){

                                                                        if($conteudo['id_chamado_origem'] == 4){
                                                                            $remetente = $conteudo['remetente']." (Painel)";
                                                                        }else{
                                                                            $remetente = $conteudo['remetente'];
                                                                        }

                                                                        $id = $conteudo['id_chamado'];
                                                                        $prazo_encerramento = $conteudo['prazo_encerramento'];
                                                                        $id_chamado_acao = $conteudo['id_chamado_acao'];

                                                                        $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');
                                                                        
                                                                        $usuario_acao = $conteudo['id_usuario_acao'];
                                                                        
                                                                        $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                        if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
                                                                            $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                            $cont_notifica_meu_setor_responsavel++;
                                                                        }else{
                                                                            $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                        }

                                                                        $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                                                                        $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE id_chamado NOT IN (SELECT id_chamado FROM tb_chamado_pendencia WHERE id_chamado = '".$conteudo['id_chamado']."') AND a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');
                                                                        
                                                                        if($ultima_acao == true){
                                                                            if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                            }
                                                                        }

                                                                        $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                        if($tsPrazo <= 0){
                                                                            $cssPrazo = 'alert alert-danger';
                                                                        }else{
                                                                            $cssPrazo = 'alert alert-success';
                                                                        }
                                                                    
                                                                        echo "
                                                                        <tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer;'>
                                                                            <td style='vertical-align: middle;'>".$notifica.$conteudo['id_chamado']."</td>
                                                                            <td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($conteudo['titulo'], 45)."</span></td>
                                                                            <td style='vertical-align: middle;'>".$remetente."</td>
                                                                            <td style='vertical-align: middle;'>".$conteudo['responsavel']."</td>
                                                                            <td style='vertical-align: middle;'>".converteDataHora($conteudo['data_criacao'])."</td>
                                                                            <td style='vertical-align: middle;'>".$conteudo['descricao_status']."</td>
                                                                            <td style='vertical-align: middle;'>".getAcaoChamado($conteudo['acao'])."</td>";
                                                                            
                                                                            if($conteudo['acao_painel'] == 1){
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario_painel a', "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$usuario_acao."' ", 'b.nome');
                                                                                echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)." (Painel)</td>";
                                                                            }else{
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario_acao."' ", 'b.nome');
                                                                                if(strstr($dados_usuario_acao[0]['nome'], ' ', true) == true){
                                                                                    echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)."</td>";
                                                                                }else{
                                                                                    echo "<td style='vertical-align: middle;'>".$dados_usuario_acao[0]['nome']."</td>";
                                                                                }
                                                                            }
                                                                            
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td class='alert alert-warning' style='vertical-align: middle;'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td class='$cssPrazo' style='vertical-align: middle;'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td class=\"text-center\" style='vertical-align: middle;'><a class=\"a_modalAguarde\" href=\"/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=".$id." \" title=\"Visualizar\"><i class=\"fa fa fa-eye\"></i></a>
                                                                            </td>
                                                                        </tr>";

                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div><!-- table responsive -->
                                                    <?php
                                                        else: 
                                                    ?>
                                                        <h4 style='margin-top: 20px; margin-bottom: 5px !important;' class='text-info text-center'>Não há chamados.</h4>
                                                    <?php endif; ?>

                                                    <!-- if pendencias -->
                                                            
                                                        <?php 
                                                            $cont_notifica_chamados_setor_pendencias = 0;
                                                            if($chamado_pendencia_setor){
                                                        ?>
                                                        <br>
                                                        <hr class="hr-primary">

                                                        <label>Com pendências:</label>
                                                        
                                                        <div class="table-responsive">                         
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        
                                                                        foreach($chamado_pendencia_setor as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_setor_pendencias++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>$status</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".strstr($usuario_acao, ' ', true)."</td>";
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td style='vertical-align: middle;' class='alert alert-warning'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td style='vertical-align: middle;' class='$cssPrazo'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td style='vertical-align: middle;' class='text-center'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div> 

                                                        <?php } ?>

                                                    <!-- end pendencias -->

                                                    <input type="hidden" id='total_tab3' value="<?=$cont_notifica_meu_setor_responsavel + $cont_notifica_chamados_setor_pendencias?>">
                                                </div><!-- end panel body -->
                                            </div><!-- end panel warning -->
                                        </div><!-- end col-md-12 -->
                                    </div><!-- end row -->

                                </div><!-- end tab 3 -->

                                <!-- tab 2 que sou responsavel -->
                                <div id="tab2" class="tab-pane fade in active">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style='margin:0;'><strong></strong></p>
                                            <div class='panel panel-warning noshadow' style='margin:0; border:none;'>
                                                <div class='panel-body noshadow' style="border: none;">
                                                    <?php
                                                        $cont_notifica_chamados_responsavel = 0;
                                                        if(!$chamados_responsavel){

                                                            echo "<h4 style='margin-top: 20px; margin-botom: 20px !important;' class='text-info text-center'>Não há chamados.</h4>";
                                                                
                                                        }else{
                                                    ?>  
                                                        <label>Chamados:</label>
                                                        <div class="table-responsive">                         
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        
                                                                        foreach($chamados_responsavel as $conteudo){

                                                                            if($conteudo['id_chamado_origem'] == 4){
                                                                                $remetente = $conteudo['remetente']." (Painel)";
                                                                            }else{
                                                                                $remetente = $conteudo['remetente'];
                                                                            }

                                                                            $id = $conteudo['id_chamado']; 
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                           
                                                                                $usuario_acao = $conteudo['id_usuario_acao'];

                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_responsavel++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE id_chamado NOT IN (SELECT id_chamado FROM tb_chamado_pendencia WHERE id_chamado = '".$conteudo['id_chamado']."') AND a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }
                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$titulo."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."".$legenda_painel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>$status</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            if($conteudo['acao_painel'] == 1){
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario_painel a', "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$usuario_acao."' ", 'b.nome');
                                                                                echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)." (Painel)</td>";
                                                                            }else{
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario_acao."' ", 'b.nome');
                                                                                if(strstr($dados_usuario_acao[0]['nome'], ' ', true) == true){
                                                                                    echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)."</td>";
                                                                                }else{
                                                                                    echo "<td style='vertical-align: middle;'>".$dados_usuario_acao[0]['nome']."</td>";
                                                                                }                                                                            }

                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td class='alert alert-warning' style='vertical-align: middle;'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td class='$cssPrazo' style='vertical-align: middle;'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            
                                                                            echo "<td class='text-center' style='vertical-align: middle;'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                    <?php } ?>                                                 
                                                    
                                                    <!-- if pendencias -->                                                    
                                                        <?php 
                                                            $cont_notifica_chamados_responsavel_pendencias = 0;
                                                            if($chamado_pendencia_responsavel){
                                                        ?>
                                                        <br>
                                                        <hr class="hr-primary">
                                                        
                                                        <label>Com pendências:</label>
                                                        
                                                        <div class="table-responsive">                         
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        
                                                                        foreach($chamado_pendencia_responsavel as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_responsavel_pendencias++;

                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>$status</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".strstr($usuario_acao, ' ', true)."</td>";
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td style='vertical-align: middle;' class='alert alert-warning'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td style='vertical-align: middle;' class='$cssPrazo'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td style='vertical-align: middle;' class='text-center'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                    <!-- END if pendencias -->
                                                    
                                                    <?php  
                                                        }
                                                    ?>
                                                    <input type="hidden" id='total_tab2' value="<?=$cont_notifica_chamados_responsavel + $cont_notifica_chamados_responsavel_pendencias?>">
                                                </div><!-- end panel body -->
                                            </div><!-- end panel warning -->
                                        </div><!-- end col-md-12 -->
                                    </div><!-- end row -->
                                </div><!-- end tab 2 -->
                                
                                <!-- tab 4 sou remetente -->
                                <div id="tab4" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style='margin:0;'><strong></strong></p>
                                            <div class='panel panel-warning noshadow' style='margin:0; border:none;'>
                                                <div class='panel-body noshadow' style="border: none;">
                                                    <?php
                                                        $cont_notifica_chamados_remetente = 0;
                                                        if(!$chamados_remetente){

                                                            echo "<h4 style='margin-top: 20px; margin-botom: 20px !important;' class='text-info text-center'>Não há chamados.</h4>";
                                                                
                                                        }else{
                                                    ?>  
                                                        <label>Chamados:</label>
                                                        <div class="table-responsive">
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        foreach($chamados_remetente as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 && $conteudo['id_usuario_responsavel'] != $_SESSION['id_usuario']){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_remetente++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE id_chamado NOT IN (SELECT id_chamado FROM tb_chamado_pendencia WHERE id_chamado = '".$conteudo['id_chamado']."') AND a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$titulo."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$status."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".strstr($usuario_acao, ' ', true)."</td>";
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td class='alert alert-warning' style='vertical-align: middle;'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td class='$cssPrazo' style='vertical-align: middle;'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td class='text-center' style='vertical-align: middle;'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div><!-- end table responsive -->
                                                    <?php } ?>

                                                    <!-- if pendencias -->

                                                        <?php 
                                                            $cont_notifica_chamados_remetente_pendencias = 0;
                                                            if($chamado_pendencia_remetente){
                                                        ?>
                                                    
                                                        <br>
                                                        <hr class="hr-primary">

                                                        <label>Com pendências:</label>
                                                        
                                                        <div class="table-responsive">                         
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        
                                                                        foreach($chamado_pendencia_remetente as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 && $conteudo['id_usuario_responsavel'] != $_SESSION['id_usuario']){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_remetente_pendencias++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$status."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".strstr($usuario_acao, ' ', true)."</td>";
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td style='vertical-align: middle;' class='alert alert-warning'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td style='vertical-align: middle;' class='$cssPrazo'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td style='vertical-align: middle;' class='text-center'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                    <!-- END if pendencias -->

                                                    <?php
                                                        }
                                                    ?>
                                                    <input type="hidden" id='total_tab4' value="<?=$cont_notifica_chamados_remetente+$cont_notifica_chamados_remetente_pendencias?>">

                                                </div><!-- end pane-body -->
                                            </div><!-- end panel -->                                            
                                        </div><!-- end col-md-12 -->
                                    </div><!-- end col-md-12 -->
                                </div>
                                <!-- end tab 4 -->

                                <!-- tab 5 Outros -->
                                <div id="tab5" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style='margin:0;'><strong></strong></p>
                                            <div class='panel panel-warning noshadow' style='margin:0; border:none;'>
                                                <div class='panel-body noshadow' style="border: none;">
                                                    <?php
                                                        $cont_notifica_chamados_outros = 0;
                                                        if(!$que_estou_envolvido){

                                                            echo "<h4 style='margin-top: 20px; margin-botom: 20px !important;' class='text-info text-center'>Não há chamados.</h4>";
                                                                
                                                        }else{
                                                    ?>  
                                                        <label>Chamados:</label>
                                                        <div class="table-responsive">
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        foreach($que_estou_envolvido as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            if($conteudo['id_chamado_origem'] == 4){
                                                                                $remetente = $conteudo['remetente']." (Painel)";
                                                                            }else{
                                                                                $remetente = $conteudo['remetente'];
                                                                            }
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = $conteudo['id_chamado_acao'];

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_outros++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE id_chamado NOT IN (SELECT id_chamado FROM tb_chamado_pendencia WHERE id_chamado = '".$conteudo['id_chamado']."') AND a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');
                                                                                                                                                        
                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$titulo."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>$status</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                           
                                                                            if($conteudo['acao_painel'] == 1){
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario_painel a', "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$usuario_acao."' ", 'b.nome');
                                                                                echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)." (Painel)</td>";
                                                                            }else{
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario_acao."' ", 'b.nome');
                                                                                if(strstr($dados_usuario_acao[0]['nome'], ' ', true) == true){
                                                                                    echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)."</td>";
                                                                                }else{
                                                                                    echo "<td style='vertical-align: middle;'>".$dados_usuario_acao[0]['nome']."</td>";
                                                                                }                                                                            }

                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td class='alert alert-warning' style='vertical-align: middle;'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td class='$cssPrazo' style='vertical-align: middle;'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td class='text-center' style='vertical-align: middle;'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div><!-- end table responsive -->
                                                    <?php } ?>
                                                    

                                                    <!-- if pendencias -->

                                                        <?php 
                                                            $cont_notifica_chamados_outros_pendencias = 0;
                                                            if($chamado_pendencia_outros){

                                                        ?>
                                                        <br>
                                                        <hr class="hr-primary">

                                                        <label>Com pendências:</label>
                                                        
                                                        <div class="table-responsive">                         
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        
                                                                        foreach($chamado_pendencia_outros as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_outros_pendencias++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                           
                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>$status</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".strstr($usuario_acao, ' ', true)."</td>";
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td style='vertical-align: middle;' class='alert alert-warning'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td style='vertical-align: middle;' class='$cssPrazo'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td style='vertical-align: middle;' class='text-center'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                    <!-- end pendencias -->
                                                    
                                                    <?php
                                                    }
                                                    ?>

                                                    <input type="hidden" id='total_tab5' value="<?=$cont_notifica_chamados_outros+$cont_notifica_chamados_outros_pendencias?>">

                                                </div><!-- end panel-body -->
                                            </div><!-- end panel -->
                                        </div><!-- end col-md-12 -->
                                    </div><!-- end row -->
                                </div>
                                <!-- end tab 5 -->

                                <!-- tab 6 Encerrados -->
                                <div id="tab6" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style='margin:0;'><strong></strong></p>
                                            <div class='panel panel-warning noshadow' style='margin:0; border:none;'>
                                                <div class='panel-body noshadow' style="border: none;">
                                                    <?php
                                                        $cont_notifica_chamados_encerrados = 0;
                                                        if(!$chamados_encerrados){

                                                            echo "<h4 style='margin-top: 20px; margin-botom: 20px !important;' class='text-info text-center'>Não há chamados.</h4>";
                                                                
                                                        }else{
                                                    ?>  
                                                        <label>Chamados:</label>
                                                        <div class="table-responsive">
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-2">Data da criação</th>
                                                                        <th class="col-md-2">Status</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        foreach($chamados_encerrados as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            if($conteudo['id_chamado_origem'] == 4){
                                                                                $remetente = $conteudo['remetente']." (Painel)";
                                                                            }else{
                                                                                $remetente = $conteudo['remetente'];
                                                                            }
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'><i class='fa fa-exclamation-circle faa-flash animated' style='color: rgb(32, 77, 116);'></i> ".$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span data-toggle=\"tooltip\" title=\"".$titulo."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$status."</td>";
                                                                            if($conteudo['acao_painel'] == 1){
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario_painel a', "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$usuario_acao."' ", 'b.nome');
                                                                                echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)." (Painel)</td>";
                                                                            }else{
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario_acao."' ", 'b.nome');
                                                                                if(strstr($dados_usuario_acao[0]['nome'], ' ', true) == true){
                                                                                    echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)."</td>";
                                                                                }else{
                                                                                    echo "<td style='vertical-align: middle;'>".$dados_usuario_acao[0]['nome']."</td>";
                                                                                }                                                                            }

                                                                            echo "<td class='text-center' style='vertical-align: middle;'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }  
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php } ?>
                                                    <input type="hidden" id='total_tab6' value="<?=$cont_chamados_encerrados?>">

                                                </div><!-- end panel-body -->
                                            </div><!-- end panel -->
                                        </div><!-- end col-md-12 -->
                                    </div><!-- end row -->
                                </div>
                                <!-- end tab 6 -->

                                <!-- tab 7 vinculados -->
                                <div id="tab7" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style='margin:0;'><strong></strong></p>
                                            <div class='panel panel-warning noshadow' style='margin:0; border:none;'>
                                                <div class='panel-body noshadow' style="border: none;">
                                                    <?php
                                                        $cont_notifica_chamados_vinculados = 0;
                                                        if(!$chamados_vinculados){

                                                            echo "<h4 style='margin-top: 20px; margin-botom: 20px !important;' class='text-info text-center'>Não há chamados.</h4>";
                                                                
                                                        }else{
                                                    ?>  
                                                        <label>Chamados:</label>
                                                        <div class="table-responsive">
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        foreach($chamados_vinculados as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 && $conteudo['id_usuario_responsavel'] != $_SESSION['id_usuario']){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_remetente++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE id_chamado NOT IN (SELECT id_chamado FROM tb_chamado_pendencia WHERE id_chamado = '".$conteudo['id_chamado']."') AND a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$titulo."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$status."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            if($conteudo['acao_painel'] == 1){
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario_painel a', "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$usuario_acao."' ", 'b.nome');
                                                                                echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)." (Painel)</td>";
                                                                            }else{
                                                                                $dados_usuario_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario_acao."' ", 'b.nome');
                                                                                if(strstr($dados_usuario_acao[0]['nome'], ' ', true) == true){
                                                                                    echo "<td style='vertical-align: middle;'>".strstr($dados_usuario_acao[0]['nome'], ' ', true)."</td>";
                                                                                }else{
                                                                                    echo "<td style='vertical-align: middle;'>".$dados_usuario_acao[0]['nome']."</td>";
                                                                                }                                                                            }
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td class='alert alert-warning' style='vertical-align: middle;'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td class='$cssPrazo' style='vertical-align: middle;'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td class='text-center' style='vertical-align: middle;'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div><!-- end table responsive -->
                                                    <?php } ?>

                                                    <!-- if pendencias -->

                                                        <?php 
                                                            $cont_notifica_chamados_vinculados_pendencias = 0;
                                                            if($chamado_pendencia_vinculados){
                                                        ?>
                                                    
                                                        <br>
                                                        <hr class="hr-primary">

                                                        <label>Com pendências:</label>
                                                        
                                                        <div class="table-responsive">                         
                                                            <table class='table table-hover'>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-md-1">&nbsp;&nbsp;&nbsp;&nbsp;#</th>
                                                                        <th class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Título</th>
                                                                        <th class="col-md-1">Remetente</th>
                                                                        <th class="col-md-1">Responsável</th>
                                                                        <th class="col-md-1">Data da criação</th>
                                                                        <th class="col-md-1">Status</th>
                                                                        <th class="col-md-1">Última ação</th>
                                                                        <th class="col-md-1">Feita por</th>
                                                                        <th class="col-md-1">Prazo</th>
                                                                        <th class="col-md-1 text-center">Visualizar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                    <?php
                                                                        
                                                                        foreach($chamado_pendencia_vinculados as $conteudo){

                                                                            $id = $conteudo['id_chamado'];
                                                                            $titulo = $conteudo['titulo'];
                                                                            $origem = $conteudo['descricao_origem'];
                                                                            $categoria = $conteudo['categoria'];
                                                                            $responsavel = $conteudo['responsavel'];
                                                                            $remetente = $conteudo['remetente'];
                                                                            $data = $conteudo['data_criacao'];
                                                                            $status = $conteudo['descricao_status'];
                                                                            $acao = $conteudo['acao'];
                                                                            $usuario_acao = $conteudo['usuario_acao'];
                                                                            $prazo_encerramento = $conteudo['prazo_encerramento'];

                                                                            $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

                                                                            $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$_SESSION['id_usuario']."' ", 'COUNT(*) as visualizado');

                                                                            $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

                                                                            if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 && $conteudo['id_usuario_responsavel'] != $_SESSION['id_usuario']){
                                                                                $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(32, 77, 116);"></i> ';
                                                                                $cont_notifica_chamados_remetente_pendencias++;
                                                                            }else{
                                                                                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                            }

                                                                            $icone_acao = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

                                                                            if($ultima_acao == true){
                                                                                if( strtotime($ultima_acao[0]['data']) <= strtotime('-7 day') ) {
                                                                                    $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>&nbsp;&nbsp;&nbsp;';
                                                                                }
                                                                            }

                                                                            $tsPrazo = strtotime($prazo_encerramento) - strtotime(getDataHora());
                                                                            if($tsPrazo <= 0){
                                                                                $cssPrazo = 'alert alert-danger';
                                                                            }else{
                                                                                $cssPrazo = 'alert alert-success';
                                                                            }
                                                                            
                                                                            echo "<tr onclick= \"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=$id'\" style='cursor: pointer;'>";
                                                                            echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$icone_acao."<span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($titulo, 45)."</span></td>";
                                                                            echo "<td style='vertical-align: middle;'>".$remetente."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".$status."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".getAcaoChamado($acao)."</td>";
                                                                            echo "<td style='vertical-align: middle;'>".strstr($usuario_acao, ' ', true)."</td>";
                                                                            if(!$prazo_encerramento || $prazo_encerramento == '0000-00-00 00:00:00'){
                                                                                echo "<td style='vertical-align: middle;' class='alert alert-warning'>Não há um prazo!</td>";
                                                                            }else{
                                                                                echo "<td style='vertical-align: middle;' class='$cssPrazo'>".converteDataHora($prazo_encerramento)."</td>";
                                                                            }
                                                                            echo "<td style='vertical-align: middle;' class='text-center'><a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&amp;chamado=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>
                                                                            </td>";
                                                                            echo "</tr>";
                                                                        }           
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                    <!-- END if pendencias -->

                                                    <?php
                                                        }
                                                    ?>
                                                    <input type="hidden" id='total_tab7' value="<?=$cont_notifica_chamados_vinculados+$cont_notifica_chamados_vinculados_pendencias?>">

                                                </div><!-- end pane-body -->
                                            </div><!-- end panel -->                                            
                                        </div><!-- end col-md-12 -->
                                    </div><!-- end col-md-12 -->
                                </div>
                                <!-- end tab 4 -->

                            </div>
                            <br>
                            
                        </div><!-- end panel body -->
                    </div><!-- end panel warning -->
                </div><!-- end col-md-12 -->
            </div><!-- end chamado collapse -->
        </div><!-- end row -->

    </div><!-- end container fluid -->

    <div class="modal fade bs-example-modal-sm" id="myModal_aniversario" tabindex="10" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" id="gridSystemModalLabel">Aniversariantes do dia</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                    <?php 
                        if($aniversarios){
                            foreach ($aniversarios as $conteudo_pessoa) {
                                echo '<i class="fa fa-gift"></i> '.$conteudo_pessoa['nome_filho']."<br>";
                            }
                        }else{
                                echo "Ninguém faz aniversário hoje!";
                        }
                    ?>
                    </div>
                </div>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade bs-example-modal-sm" id="myModal_emp_aniversario" tabindex="10" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" id="gridSystemModalLabel">Aniversário de vínculo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    <?php 
                         if($emp_faz_aniversario){

                            $d_aniversario = array();

                            foreach ($emp_faz_aniversario as $key => $value){

                                $d_aniversario[$key] = $value['dia_aniversario'];
                            }
                            array_multisort($d_aniversario, SORT_ASC, $emp_faz_aniversario);

                            echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
                                    <thead>
                                        <tr>
                                            <th class="col-md-5">Cliente</th>
                                            <th class="col-md-5">Data aniversário (vínculo)</th>
                                            <th class="col-md-2">Tempo</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                            foreach ($emp_faz_aniversario as $key => $value) {

                                if($value['id_responsavel'] == $_SESSION['id_usuario'] || $perfil_usuario == 2 || $perfil_usuario == '4'){
                                    $id = $value['id_pessoa'];
                                    $datetime1 = new DateTime($data_de_hoje_completa);
                                    $datetime2 = new DateTime($value['data_inicio_contrato']);
                                    $interval = $datetime1->diff($datetime2);
                                    $tempo = $interval->y;                                    

                                    $nome = $value['nome'];
                                    $data = $value['data_inicio_contrato'];
                                    if($tempo != 0){

                                        if($tempo == 1){
                                            $tempo = $tempo.' ano';
                                        }else{
                                            $tempo = $tempo.' anos';
                                        }
                                        
                                        echo "<tr>";
                                            echo "<td style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=$id'>".$nome."</a></td>";
                                            echo "<td style='vertical-align: middle;'>".converteDataHora($data)."</td>";
                                            echo "<td style='vertical-align: middle;'>$tempo</td>";
                                        echo "</tr>";    

                                        }
                                    
                                }
                            }
                              echo "</tbody>";
                            echo "</table>";
                        }else{
                                echo "Não existem aniversários de vínculos para o próximo mês!";
                        }
                    ?>
                    </div>
                </div>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="myModal_reajuste" tabindex="1" role="dialog" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" id="gridSystemModalLabel">Reajustes pendentes</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    <?php 
                        if($dados_consulta_reajuste){
                            echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
                                    <thead>
                                        <tr>
                                            <th class="col-md-1">#</th>
                                            <th class="col-md-4">Cliente</th>
                                            <th class="col-md-5">Plano</th>
                                            <th class="col-md-2">Data de reajuste</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                            foreach ($dados_consulta_reajuste as $conteudo_consulta_reajuste) {
                                if($conteudo_consulta_reajuste['nome_contrato']){
                                    $nome_contrato = " (".$conteudo_consulta_reajuste['nome_contrato'].") ";
                                }else{
                                    $nome_contrato = '';
                                }
                                $contrato = $conteudo_consulta_reajuste['nome_pessoa']." ".$nome_contrato ;
                                $nome_plano = $conteudo_consulta_reajuste['nome_plano'];
                                $cod_servico = $conteudo_consulta_reajuste['cod_servico'];
                                $servico = getNomeServico($cod_servico);
                                echo "<tr>";
                                    echo "<td style='vertical-align: middle;'>".$conteudo_consulta_reajuste['id_contrato_plano_pessoa']."</td>";
                                    echo "<td style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=".$conteudo_consulta_reajuste['id_contrato_plano_pessoa']."' target='_blank'>".$contrato."</td>";
                                    echo "<td style='vertical-align: middle;'>$servico - $nome_plano</td>";
                                    echo "<td style='vertical-align: middle;'>".converteDataHora($conteudo_consulta_reajuste['data_ajuste'],'data')."</td>";
                                echo "</tr>";                                 
                              
                            }
                              echo "</tbody>";
                            echo "</table>";
                        }else{
                                echo "Não existem reajustes pendentes!";
                        }
                    ?>
                    </div>
                </div>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        
        total = '';
        total_contratos = parseInt($('#cont_total_contratos').val());

        $(document).ready(function(){
            if( $('.aba1').hasClass('active') ) {
                $('.aba1').css('border-bottom-color', 'white');
            }
            if( $('.aba2').hasClass('active') ) {
                $('.aba2').css('border-bottom-color', 'white');
            }
            if( $('.aba3').hasClass('active') ) {
                $('.aba3').css('border-bottom-color', 'white');
            }
            if( $('.aba4').hasClass('active') ) {
                $('.aba4').css('border-bottom-color', 'white');
            }
            if( $('.aba5').hasClass('active') ) {
                $('.aba5').css('border-bottom-color', 'white');
            }
            if( $('.aba6').hasClass('active') ) {
                $('.aba6').css('border-bottom-color', 'white');
            }
            if( $('.aba7').hasClass('active') ) {
                $('.aba7').css('border-bottom-color', 'white');
            }
            
            var notifica_tab2 = parseInt($('#total_tab2').val());
            var notifica_tab3 = parseInt($('#total_tab3').val());
            var notifica_tab4 = parseInt($('#total_tab4').val());
            var notifica_tab5 = parseInt($('#total_tab5').val());
            var notifica_tab6 = parseInt($('#total_tab6').val());
            var notifica_tab7 = parseInt($('#total_tab7').val());
            var total_topicos = parseInt($('#total_topicos').val());
            var total_erros = parseInt($('#total_erros').val());
            
            var notifica_total_chamado  = notifica_tab2 + notifica_tab3 + notifica_tab4 + notifica_tab5 + notifica_tab6 + notifica_tab7;

            if (notifica_tab2 > 0){
                $('#notifica_tab2').show().html(notifica_tab2);
            }
            if (notifica_tab3 > 0){
                $('#notifica_tab3').show().html(notifica_tab3);
            }
            if (notifica_tab4 > 0){
                $('#notifica_tab4').show().html(notifica_tab4);
            }
            if (notifica_tab5 > 0){
                $('#notifica_tab5').show().html(notifica_tab5);
            }
            if (notifica_tab6 > 0){
                $('#notifica_tab6').show().html(notifica_tab6);
            }
            if (notifica_tab7 > 0){
                $('#notifica_tab7').show().html(notifica_tab7);
            }
            if(notifica_total_chamado > 0){
                $('#notifica_total_chamado').show().html(notifica_total_chamado);
            }

            total = parseInt(total_topicos) + parseInt(notifica_total_chamado) + parseInt(total_erros) + parseInt(total_contratos);

            if(total > 0){
                document.title = '(' + total + ') Simples V2';
            }
            if(total == 0){
                document.title = 'Simples V2';
            }
        });

        $(document).ready(function(){
            var botao_um_existe = $('button.um-lido');
            var botao_dois_existe = $('button.dois-lido');

            if(botao_um_existe.length == 0 && botao_dois_existe.length == 0){
                $('#todos-lidos').remove();
            }
        });
        
        $('.lido').on('click', function(){
            var obj = $(this);
            $.ajax({
                type: "GET",
                url: "class/NotificacaoLida.php",
                dataType: "json",
                data: {
                    id_notificacao_alteracao: obj.attr('dt-id'),
                    id_usuario: $('#id_usuario').val(),
                    lido: obj.attr('lido')
                },
                success: function(data){                    
                    if(obj.attr('dt-id')){
                        $("#painel-notificacao-" + obj.attr('dt-id')).remove();
                        
                        total = parseInt(total) - parseInt(1);
                        total_contratos = parseInt(total_contratos) - parseInt(1);

                        if(total > 0){
                            document.title = '(' + total + ') Simples V2';
                        }
                        if(total == 0){
                            document.title = 'Simples V2';
                        }

                    }else{
                        $('#todos-lidos').remove();
                        $("#notificacao_erro_contrato").html("<h4 style='margin: 0 !important;' class='text-success text-center'> Não há novas notificações de contratos.</h4><br>");
                        $("#notificacao_erro_pessoa").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de pessoas.</h4><br>");
                        
                        $(".erro-contrato").remove();
                        $(".erro-pessoa").remove();

                        $(".painel-notificacao").remove();
                        $("#badge_pessoas").remove();

                        total = parseInt(total) - parseInt(total_contratos);
                        if(total > 0){
                            document.title = '(' + total + ') Simples V2';
                        }
                        if(total == 0){
                            document.title = 'Simples V2';
                        }  
                    }
                   
                    $('#modal_aguarde').modal('hide');
                },
                beforeSend: function(){
                    $('body').append('<div class="modal fade" id="modal_aguarde" role="dialog" data-backdrop="static" style="text-align: center position: absolute; left: 50%; top: 25%;"><i class="fa fa-spinner fa-spin fa-3x fa-fw margin-bottom"></i></pre></div>');
                    $('#modal_aguarde').modal('show');
                }
            });
        });

        $('button.um-lido').on('click', function(){
            var botao_um_existe = $('button.um-lido');
            if(botao_um_existe.length < 2){
                $("#notificacao_erro_contrato").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de contratos.</h4><br>");
                $("#badge_pessoas").text(($('.painel-notificacao').length)-1);

            }else{
                $("#badge_pessoas").text('');
                $("#badge_pessoas").text(($('.painel-notificacao').length)-1);

            }

            if($(".painel-notificacao").length < 2){
                $(".painel-notificacao").remove();
                $("#badge_pessoas").remove();
                $('#todos-lidos').remove();
                $("#badge_pessoas").remove();

                $(".erro-contrato").remove();
                $(".erro-pessoa").remove();

                $("#notificacao_erro_contrato").text('');
                $("#notificacao_erro_pessoa").text('');
                $("#notificacao_erro_contrato").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de contratos.</h4><br>");
                $("#notificacao_erro_pessoa").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de pessoas.</h4><br>");
            }
        });

        $('button.dois-lido').on('click', function(){
            var botao_dois_existe = $('button.dois-lido');
            if(botao_dois_existe.length < 2){
                
                $("#notificacao_erro_pessoa").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de pessoas.</h4><br>");
                $("#badge_pessoas").text(($('.painel-notificacao').length)-1);

            }else{
                $("#badge_pessoas").text('');
                $("#badge_pessoas").text(($('.painel-notificacao').length)-1);
            }

            if($(".painel-notificacao").length < 2){
                $(".painel-notificacao").remove();
                $("#badge_pessoas").remove();
                $('#todos-lidos').remove();
                $("#badge_pessoas").remove();

                $(".erro-contrato").remove();
                $(".erro-pessoa").remove();

                $("#notificacao_erro_contrato").text('');
                $("#notificacao_erro_pessoa").text('');
                $("#notificacao_erro_contrato").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de contratos.</h4><br>");
                $("#notificacao_erro_pessoa").html("<h4 style='margin: 0 !important;' class='text-success text-center'>Não há novas notificações de pessoas.</h4><br>");
            }
        });

        $('#topicos2').on('click', function(){
           $("#topicos").collapse('show');
           $("#erros").collapse('hide');
           $("#chamados").collapse('hide');
           $("#pessoas_contratos").collapse('hide');
        });

        $('#erros2').on('click', function(){
           $("#erros").collapse('show');
           $("#topicos").collapse('hide');
           $("#chamados").collapse('hide');
           $("#pessoas_contratos").collapse('hide');
        });

        $('#chamados2').on('click', function(){
           $("#chamados").collapse('show');
           $("#topicos").collapse('hide');
           $("#erros").collapse('hide');
           $("#pessoas_contratos").collapse('hide');
        });

        $('#pessoas_contratos2').on('click', function(){
           $("#pessoas_contratos").collapse('show');
           $("#topicos").collapse('hide');
           $("#chamados").collapse('hide');
           $("#erros").collapse('hide');
        });

        $(document).ready(function(){    
            $(".modal-backdrop").remove();        
                   
        });
        
    </script>

<?php } ?>