<?php
require_once(__DIR__."/System.php");


if(!empty($_GET['gerar'])) {

    $id = (int)$_GET['gerar'];

    $dados_monitoria_mes = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = '$id' ", 'data_referencia');

    $data_referencia = $dados_monitoria_mes[0]['data_referencia'];

    $verifica = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE id_monitoria_mes = $id", 'data_referencia');

    $arrayData = explode("-",$data_referencia);

    $mes = $arrayData[1];
    $ano = $arrayData[0];
    
    $data = $arrayData[1].'/'.$arrayData[0];

    $dateTime = new DateTime($data_referencia);
    $dateTime->modify('first day of next month'); // deixar next month
    $dia_liberar = $dateTime->format("Y-m-d");

    $hoje = getDataHora();
    $hoje = explode(' ', $hoje);

    if($hoje[0] >= $dia_liberar){
        $disabled = true;
    }else{
        $disabled = false;
    }

    $mes_referencia = substr($data_referencia, 0, -3);
    $mes_referencia = explode("-", $mes_referencia);
    $mes_referencia = $mes_referencia[1].'/'.$mes_referencia[0];

    if ($verifica) {
        $alert = ('Plano de ação já foi gerado anteriormente!','d');
        header("location: /api/iframe?token=$request->token&view=monitoria-plano-acao-busca");

    } else {

        if ($disabled == true) {
            $dados_usuarios_sem_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_perfil_sistema = 3 AND (a.lider_direto is NULL OR a.lider_direto = 0) ORDER BY b.nome", 'b.nome');

            if ($dados_usuarios_sem_lider) {
                $usuarios_sem_lider = '';

                foreach ($dados_usuarios_sem_lider as $conteudo) {
                    if ($usuarios_sem_lider == '') {
                        $usuarios_sem_lider = $conteudo['nome'];

                    } else {
                        $usuarios_sem_lider .= ", ".$conteudo['nome'];
                    }
                }
                if (sizeof($dados_usuarios_sem_lider) > 1) {
                    $notificacao = "os usuários ".$usuarios_sem_lider." estão";

                } else {
                    $notificacao = "o usuário ".$usuarios_sem_lider." esta";
                }

                $alert = ('Não é possível gerar o plano de ação, '.$notificacao.' sem líder!','d');
                header("location: /api/iframe?token=$request->token&view=monitoria-plano-acao-busca");
                exit;
            } else {
                gerar($id);
            }
        } else {
            $alert = ('Ainda não é possível gerar plano de ação!','d');
            header("location: /api/iframe?token=$request->token&view=monitoria-plano-acao-busca");
            exit;
        }
    }
}

function gerar($id){

    $dados_monitoria_mes = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = '$id' ", 'data_referencia, tipo_monitoria, classificacao_atendente, id_monitoria_mes');

    $data_referencia = $dados_monitoria_mes[0]['data_referencia'];
    $tipo_monitoria = $dados_monitoria_mes[0]['tipo_monitoria'];
    $classificacao_atendente = $dados_monitoria_mes[0]['classificacao_atendente'];
    
    $mes_referencia = explode("-", $data_referencia);
    $mes_referencia = $mes_referencia[1].'/'.$mes_referencia[0];

    $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_perfil_sistema = 3 ORDER BY b.nome", 'a.id_usuario, a.id_perfil_sistema, a.lider_direto, b.nome');

    $link = DBConnect('');
    DBBegin($link);

    foreach ($dados_usuarios as $conteudo) {
        
        $id_usuario = $conteudo['id_usuario'];
        $lider = $conteudo['lider_direto'];
        $nome = $conteudo['nome'];

        if ($tipo_monitoria == 1) {
            $legenda_tipo_monitoria = 'Canal de atendimento - via Telefone';

            $dados_monitoria = DBReadTransaction($link, 'tb_monitoria_avaliacao_audio', "WHERE id_usuario_atendente = '$id_usuario' AND data_referencia = '$data_referencia' AND id_monitoria_mes = $id AND considerar = 1 ORDER BY data_monitoria", 'id_ligacao');

        } else if ($tipo_monitoria == 2) {
            $legenda_tipo_monitoria = 'Canal de atendimento - via Texto';

            $dados_monitoria = DBReadTransaction($link, 'tb_monitoria_avaliacao_texto', "WHERE id_usuario_atendente = '$id_usuario' AND data_referencia = '$data_referencia' AND id_monitoria_mes = $id AND considerar = 1 ORDER BY data_monitoria", 'id_atendimento');
        }

        if ($dados_monitoria) {
            
            $array_pontos = array();
            $observacoes = '';
            $txt_plano_acao = '';

            foreach ($dados_monitoria as $conteudo) {

                $total_pontos_geral = 0;
                $total_pontos_feitos = 0;
                
                if ($tipo_monitoria == 1) {
                    $id_ligacao = $conteudo['id_ligacao'];

                    $dados_monitoria_mes = DBReadTransaction($link, 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_avaliacao_audio_mes b ON a.id_monitoria_avaliacao_audio = b.id_monitoria_avaliacao_audio INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $id AND a.id_usuario_atendente = $id_usuario AND a.id_ligacao = $id_ligacao AND g.status = 1 AND a.considerar = 1", 'a.nome_contato, a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');
                
                } else if ($tipo_monitoria == 2) {
                    $id_atendimento = $conteudo['id_atendimento'];

                    $dados_monitoria_mes = DBReadTransaction($link, 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_avaliacao_texto_mes b ON a.id_monitoria_avaliacao_texto = b.id_monitoria_avaliacao_texto INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $id AND a.id_usuario_atendente = '$id_usuario' AND a.id_atendimento = '$id_atendimento' AND g.status = 1 AND a.considerar = 1", 'a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');
                }                

                $cont_obs = 0;

                foreach($dados_monitoria_mes as $conteudo_avaliacao) {

                    $array_pontos[$conteudo_avaliacao['id_monitoria_quesito']]['pontos'] += $conteudo_avaliacao['pontos'];

                    $array_pontos[$conteudo_avaliacao['id_monitoria_quesito']]['qtd'] += 1;

                    $array_pontos[$conteudo_avaliacao['id_monitoria_quesito']]['pontos_valor'] = $conteudo_avaliacao['pontos_valor'];

                    $array_pontos[$conteudo_avaliacao['id_monitoria_quesito']]['descricao'] = $conteudo_avaliacao['descricao'];

                    $array_pontos[$conteudo_avaliacao['id_monitoria_quesito']]['plano_acao'] = $conteudo_avaliacao['plano_acao'];

                    $array_pontos[$conteudo_avaliacao['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_avaliacao['porcentagem_plano_acao'];

                    if($conteudo_avaliacao['obs_avaliacao'] != '' && $cont_obs == 0){
                        $observacoes .= '<strong>-</strong> '.$conteudo_avaliacao['obs_avaliacao'].'<br><br>';
                    }

                    $cont_obs++;
                }
            }

            $txt_plano_acao = '<table class="table table-striped dataTable" border="1" cellpadding="1" cellspacing="1" style="margin-bottom:0; border: 1px solid #ddd;">
                <thead style="background-color: #337ab7">
                    <tr>
                        <th style="color: white">Quesito</th>
                        <th style="color: white">Plano de ação</th>
                    </tr>
                </thead>
                <tbody>';

            $cont_table = 0;

            foreach($array_pontos as $key => $conteudo_resultados) {

                //$quesito = DBReadTransaction($link, 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.data_referencia = '$data_referencia' AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

                $media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
                $resultado_geral = ($media*100) / $conteudo_resultados['pontos_valor'];

                $porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

                if ($porcentagem >= $resultado_geral) {

                    $txt_plano_acao .= '<tr><td>'.$conteudo_resultados['descricao'].'</td><td>'.$conteudo_resultados['plano_acao'].'</td></tr>';
                    $cont_table++;
                }
            }
            
            if ($observacoes != '') {
                $txt_plano_acao .= '</tbody></table><br><br><strong>Observações:</strong><br><div class="jumbotron" style="padding: 20px 10px 10px 10px; background-color: #fff; margin-bottom: 5px;">'.$observacoes.'</div><br>';
            } else {
                $txt_plano_acao .= '</tbody></table>';
            }

            $resultado_bd = DBReadTransaction($link, 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id AND data_referencia = '$data_referencia' AND id_usuario = $id_usuario");

            $responsavel_chamado = '';
            if ($cont_table == 0) {

                if ($resultado_bd[0]['resultado'] >= 96 && $resultado_bd[0]['resultado'] < 99) {
                    $txt_plano_acao = '<br><span>Resultado geral do mês:<strong> '.$resultado_bd[0]['resultado'].'% </strong></span><br><br><img src="inc/img/monitoria_parabens.jpg" height="30%" width="800">';
                    $responsavel_chamado = 'operador';

                } else if ($resultado_bd[0]['resultado'] >= 99) {
                    $txt_plano_acao = '<br><span>Resultado geral do mês:<strong> '.$resultado_bd[0]['resultado'].'% </strong></span><br><br><img src="inc/img/monitoria_superou_todas.jpg" height="30%" width="800">';
                    $responsavel_chamado = 'operador';

                } else if ($resultado_bd[0]['resultado'] < 96) {
                    $txt_plano_acao = '<br><span>Resultado geral do mês:<strong> '.$resultado_bd[0]['resultado'].'% </strong></span><br>';
                    $responsavel_chamado = 'lider';
                }
                
            } else {

                if ($resultado_bd[0]['resultado'] >= 96 && $resultado_bd[0]['resultado'] < 99) {
                    $txt_plano_acao .= '<br><span>Resultado geral do mês:<strong> '.$resultado_bd[0]['resultado'].'% </strong></span><br><br><img src="inc/img/monitoria_parabens.jpg" height="30%" width="800">';
                    $responsavel_chamado = 'operador';

                } else if ($resultado_bd[0]['resultado'] >= 99) {
                    $txt_plano_acao .= '<br><span>Resultado geral do mês:<strong> '.$resultado_bd[0]['resultado'].'% </strong></span><br><br><img src="inc/img/monitoria_superou_todas.jpg" height="30%" width="800">';
                    $responsavel_chamado = 'operador';

                } else if ($resultado_bd[0]['resultado'] < 96) {
                    $txt_plano_acao .= '<br><span>Resultado geral do mês:<strong> '.$resultado_bd[0]['resultado'].'% </strong></span><br>';
                    $responsavel_chamado = 'lider';
                }
            }

            if ($lider && $lider != '0') {

                abrirChamado($link, $id_usuario, $lider, $txt_plano_acao, $mes_referencia, $legenda_tipo_monitoria, $id, $responsavel_chamado);
            } else {
                $alert = ('O usuário '.$nome.' está sem líder direto!','d');
                header("location: /api/iframe?token=$request->token&view=monitoria-plano-acao-busca");
                exit;
            }
            
        }//end if se tem dados monitoria
    }

    $dados = array(
        'data_referencia' => $data_referencia,
        'id_monitoria_mes' => $id
    );

    $insertID = DBCreateTransaction($link, 'tb_monitoria_mes_plano_acao_chamado', $dados, true);
    registraLogTransaction($link, 'Inserção de monitoria plano de acao.', 'i', 'tb_monitoria_mes_plano_acao_chamado', $insertID, "data_referencia: $data_referencia | id_monitoria_mes: $id");

    DBCommit($link);
    $alert = ('Plano de ação já foi gerado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=monitoria-plano-acao-busca");
    exit;
}

function abrirChamado($link, $id_usuario, $lider, $txt_plano_acao, $mes_referencia, $legenda_tipo_monitoria, $id_monitoria_mes, $responsavel_chamado){

    $data_criacao = getDataHora();

    $prazo_encerramento = date('Y-m-d H:m', strtotime($data_criacao. ' + 15 days'));

    $titulo = 'Monitoria - Plano de ação ('.$mes_referencia.' - '.$legenda_tipo_monitoria.')';

    $remetente = $id_usuario;
    $id_chamado_status = '1';
    $bloqueado = '1';
    $descricao = $txt_plano_acao;
    $visibilidade = '1';
    $id_contrato_plano_pessoa = '0';
    $id_chamado_origem = '3';

    if ($responsavel_chamado == 'operador') {
        $responsavel = $id_usuario;

    } else if ($responsavel_chamado == 'lider') {
        $responsavel = $lider;
    }

    $dados = array(
        'data_criacao' => $data_criacao,
        'titulo' => $titulo,
        'descricao' => $descricao,
        'bloqueado' => $bloqueado,
        'id_usuario_remetente' => $remetente,
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'id_usuario_responsavel' => $responsavel,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_chamado_origem' => $id_chamado_origem,
        'prazo_encerramento' => $prazo_encerramento
    );

    $insertIDchamado = DBCreateTransaction($link, 'tb_chamado', $dados, true);
    registraLogTransaction($link, 'Inserção de chamado.', 'i', 'tb_chamado', $insertIDchamado, "data_criacao: $data_criacao | titulo: $titulo | descricao: $descricao | bloqueado: $bloqueado | id_usuario_remetente: $remetente | status: $id_chamado_status | visibilidade: $visibilidade | id_usuario_responsavel: $responsavel | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_chamado_origem: $id_chamado_origem | prazo_encerramento: $prazo_encerramento");
        
    $acao = "criacao";

    $id_categoria = '45';

    $dadosCategoria = array(
        'id_categoria' => $id_categoria,
        'id_chamado' => $insertIDchamado,
    );
    
    $insertCategoria = DBCreateTransaction($link, 'tb_chamado_categoria', $dadosCategoria, true);
    registraLogTransaction($link, 'Inserção de categoria chamado.','i','tb_chamado_categoria',$insertCategoria,"id_categoria: $id_categoria | id_chamado: $insertIDchamado");
    
    $tempo = 1;
    $id_usuario_acao = $id_usuario;

    $dados_acao = array(
        'data' => $data_criacao,
        'descricao' => $descricao,
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'acao' => $acao,
        'tempo' => $tempo,
        'id_chamado' => $insertIDchamado,
        'id_usuario_responsavel' => $responsavel,
        'id_usuario_acao' => $id_usuario_acao,
        'bloqueado' => $bloqueado,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertAcao = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);
    registraLogTransaction($link, 'Inserção de ação.','i','tb_chamado_acao',$insertAcao,"data: $data_criacao | descricao: $descricao | id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $insertIDchamado | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario_acao | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    
    $perfis = ['13']; //perfil 2 (desenvolvimento) só para teste

    foreach($perfis as $perfil){
        $dados = array(
            'id_chamado' => $insertIDchamado,
            'id_perfil_sistema' => $perfil
        );

        $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado perfil.','i','tb_topico',$insertChamadoPerfil,"id_chamado: $insertIDchamado | id_perfil_sistema: $perfil");
    }

    $dados = array(
        'id_monitoria_mes' => $id_monitoria_mes,
        'id_chamado' => $insertIDchamado
    );

    $insertID = DBCreateTransaction($link, 'tb_monitoria_mes_chamado', $dados, true);
    registraLogTransaction($link, 'Inserção de monitoria mes chamado.', 'i', 'tb_monitoria_mes_chamado', $insertID, "id_monitoria_mes: $id_monitoria_mes | id_chamado: $insertIDchamado");

}

?>