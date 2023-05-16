<?php
require_once(__DIR__."/System.php");

$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$id_plano = (!empty($_POST['id_plano'])) ? $_POST['id_plano'] : '';
$qtd_contratada = (!empty($_POST['qtd_contratada'])) ? $_POST['qtd_contratada'] : 0;
$valor_unitario = (!empty($_POST['valor_unitario'])) ? converteMoeda($_POST['valor_unitario'],'banco') : 0;

$valor_total = (!empty($_POST['valor_total'])) ? converteMoeda($_POST['valor_total'],'banco') : 0;

$valor_excedente = (!empty($_POST['valor_excedente'])) ? converteMoeda($_POST['valor_excedente'],'banco') : 0;
$valor_inicial = (!empty($_POST['valor_inicial'])) ? converteMoeda($_POST['valor_inicial'],'banco') : 0;
$valor_plantao = (!empty($_POST['valor_plantao'])) ? converteMoeda($_POST['valor_plantao'],'banco') : 0;

$data_inicio_contrato = (!empty($_POST['data_inicio_contrato'])) ? $_POST['data_inicio_contrato'] : '';
$data_inicio_contrato = ($data_inicio_contrato != '') ? converteData($data_inicio_contrato) : '0000-00-00';

$periodo_contrato = (!empty($_POST['periodo_contrato'])) ? $_POST['periodo_contrato'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$indice_reajuste = (!empty($_POST['indice_reajuste'])) ? $_POST['indice_reajuste'] : '';
$dia_pagamento = (!empty($_POST['dia_pagamento'])) ? $_POST['dia_pagamento'] : '';
$obs = (!empty($_POST['obs'])) ? $_POST['obs'] : '';
$tipo_cobranca = (!empty($_POST['tipo_cobranca'])) ? $_POST['tipo_cobranca'] : '';
$nome_contrato = (!empty($_POST['nome_contrato'])) ? $_POST['nome_contrato'] : '';
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$id_responsavel_tecnico = (!empty($_POST['id_responsavel_tecnico'])) ? $_POST['id_responsavel_tecnico'] : '';
 
$id_usuario = $_SESSION['id_usuario'];

$realiza_cobranca = (!empty($_POST['realiza_cobranca'])) ? $_POST['realiza_cobranca'] : '0';
$recebe_ligacao = (!empty($_POST['recebe_ligacao'])) ? $_POST['recebe_ligacao'] : '0';
$desafogo = (!empty($_POST['desafogo'])) ? $_POST['desafogo'] : '0';
$remove_duplicados = (!empty($_POST['remove_duplicados'])) ? $_POST['remove_duplicados'] : '0';
$minutos_duplicados = (!empty($_POST['minutos_duplicados'])) ? $_POST['minutos_duplicados'] : '0';

$data_ajuste = (!empty($_POST['data_ajuste'])) ? $_POST['data_ajuste'] : '';
$data_ajuste = ($data_ajuste != '') ? converteData($data_ajuste) : '0000-00-00';

$data_inicial_cobranca = (!empty($_POST['data_inicial_cobranca'])) ? $_POST['data_inicial_cobranca'] : '';
$data_inicial_cobranca = ($data_inicial_cobranca != '') ? converteData($data_inicial_cobranca) : '0000-00-00';

$data_final_cobranca = (!empty($_POST['data_final_cobranca'])) ? $_POST['data_final_cobranca'] : '';
$data_final_cobranca = ($data_final_cobranca != '') ? converteData($data_final_cobranca) : '0000-00-00';

$tempo_fidelidade = (!empty($_POST['tempo_fidelidade'])) ? $_POST['tempo_fidelidade'] : '0';

$contrato_pai = (!empty($_POST['contrato_pai'])) ? $_POST['contrato_pai'] : '';

$qtd_clientes = (!empty($_POST['qtd_clientes'])) ? $_POST['qtd_clientes'] : '0';

$email_nf = (!empty($_POST['email_nf'])) ? $_POST['email_nf'] : '';

if($contrato_pai == 1){
    $id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '0';
    $realiza_cobranca = '0';
}else{
    $id_contrato_plano_pessoa = '0';
}

$separar_contrato = (!empty($_POST['separar_contrato'])) ? $_POST['separar_contrato'] : '';

if($separar_contrato == 1){
    $id_contrato_plano_pessoa_separar = (!empty($_POST['id_contrato_plano_pessoa_separar'])) ? $_POST['id_contrato_plano_pessoa_separar'] : '0';
}else{
    $id_contrato_plano_pessoa_separar = '0';
}

$flag_perfil = (!empty($_POST['flag_perfil'])) ? $_POST['flag_perfil'] : '';
$reter_cofins = (!empty($_POST['reter_cofins'])) ? $_POST['reter_cofins'] : '2';
$reter_csll = (!empty($_POST['reter_csll'])) ? $_POST['reter_csll'] : '2';
$reter_ir = (!empty($_POST['reter_ir'])) ? $_POST['reter_ir'] : '2';
$reter_pis = (!empty($_POST['reter_pis'])) ? $_POST['reter_pis'] : '2';

$valor_adesao = (!empty($_POST['valor_adesao'])) ? converteMoeda($_POST['valor_adesao'],'banco') : 0;

$tipo_plantao = (!empty($_POST['tipo_plantao'])) ? $_POST['tipo_plantao'] : '0';

$valor_diferente_texto = (!empty($_POST['valor_diferente_texto'])) ? $_POST['valor_diferente_texto'] : '0';
$qtd_contratada_texto = (!empty($_POST['qtd_contratada_texto'])) ? $_POST['qtd_contratada_texto'] : 0;
$valor_unitario_texto = (!empty($_POST['valor_unitario_texto'])) ? converteMoeda($_POST['valor_unitario_texto'],'banco') : 0;
$valor_excedente_texto = (!empty($_POST['valor_excedente_texto'])) ? converteMoeda($_POST['valor_excedente_texto'],'banco') : 0;

$desafogo_texto = (!empty($_POST['desafogo_texto'])) ? $_POST['desafogo_texto'] : '0';

$personalizado = (!empty($_POST['personalizado'])) ? $_POST['personalizado'] : '0';
$versao = (!empty($_POST['versao'])) ? $_POST['versao'] : '';
$procedimentos_selecionados = (!empty($_POST['procedimentos_selecionados'])) ? $_POST['procedimentos_selecionados'] : '';
$desconsidera_notificacao = (!empty($_POST['desconsidera_notificacao'])) ? $_POST['desconsidera_notificacao'] : 0;
$valor_desconsidera_notificacao = (!empty($_POST['valor_desconsidera_notificacao'])) ? converteMoeda($_POST['valor_desconsidera_notificacao'],'banco') : 0;


if($tipo_cobranca == 'x_cliente_base'){
    $qtd_clientes_teto = (!empty($_POST['qtd_clientes_teto'])) ? $_POST['qtd_clientes_teto'] : 0;
}else{
    $qtd_clientes_teto = 0;
}

if(!empty($_POST['inserir'])) {

    inserir($id_pessoa, $id_plano, $valor_unitario, $valor_excedente, $valor_plantao, $data_inicio_contrato, $periodo_contrato, $qtd_contratada, $status, $valor_total, $indice_reajuste, $dia_pagamento, $obs, $tipo_cobranca, $valor_inicial, $id_usuario, $nome_contrato, $realiza_cobranca, $recebe_ligacao, $desafogo, $remove_duplicados, $minutos_duplicados, $data_ajuste, $contrato_pai, $id_contrato_plano_pessoa, $id_contrato_plano_pessoa_separar, $qtd_clientes, $id_responsavel, $id_responsavel_tecnico, $flag_perfil, $email_nf, $reter_cofins, $reter_csll, $reter_ir, $reter_pis, $valor_adesao, $data_final_cobranca, $data_inicial_cobranca, $tipo_plantao, $valor_diferente_texto, $qtd_contratada_texto, $valor_unitario_texto, $valor_excedente_texto, $desafogo_texto, $personalizado, $procedimentos_selecionados, $qtd_clientes_teto, $tempo_fidelidade, $desconsidera_notificacao, $valor_desconsidera_notificacao);

}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];

    $operacao = "Alterar";
    $alteracao = "Contrato";

    $usuario = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = $id_usuario", "a.id_pessoa");
    $pessoa_alterou = $usuario[0]['id_pessoa'];
    $dado_alterado = '';
    // notificacao($alteracao, $id, $operacao, $pessoa_alterou, $id_usuario, $dado_alterado);
    
    alterar($id, $id_pessoa, $id_plano, $valor_unitario, $valor_excedente, $valor_plantao, $data_inicio_contrato, $periodo_contrato, $qtd_contratada, $status, $valor_total, $indice_reajuste, $dia_pagamento, $obs, $tipo_cobranca, $valor_inicial, $nome_contrato, $realiza_cobranca, $recebe_ligacao, $desafogo, $remove_duplicados, $minutos_duplicados, $id_usuario, $data_ajuste, $contrato_pai, $id_contrato_plano_pessoa, $id_contrato_plano_pessoa_separar, $qtd_clientes, $id_responsavel, $id_responsavel_tecnico, $flag_perfil, $email_nf, $reter_cofins, $reter_csll, $reter_ir, $reter_pis, $valor_adesao, $data_final_cobranca, $data_inicial_cobranca, $tipo_plantao, $valor_diferente_texto, $qtd_contratada_texto, $valor_unitario_texto, $valor_excedente_texto, $desafogo_texto, $personalizado, $procedimentos_selecionados, $versao, $qtd_clientes_teto, $tempo_fidelidade, $desconsidera_notificacao, $valor_desconsidera_notificacao);
    
  
}else if (isset($_GET['download'])) {

    $id_contrato_plano_pessoa = $_GET['download'];
    downloadPdf($id_contrato_plano_pessoa);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_pessoa, $id_plano, $valor_unitario, $valor_excedente, $valor_plantao, $data_inicio_contrato, $periodo_contrato, $qtd_contratada, $status, $valor_total, $indice_reajuste, $dia_pagamento, $obs, $tipo_cobranca, $valor_inicial, $id_usuario, $nome_contrato, $realiza_cobranca, $recebe_ligacao, $desafogo, $remove_duplicados, $minutos_duplicados, $data_ajuste, $contrato_pai, $id_contrato_plano_pessoa, $id_contrato_plano_pessoa_separar, $qtd_clientes, $id_responsavel, $id_responsavel_tecnico, $flag_perfil, $email_nf, $reter_cofins, $reter_csll, $reter_ir, $reter_pis, $valor_adesao, $data_final_cobranca, $data_inicial_cobranca, $tipo_plantao, $valor_diferente_texto, $qtd_contratada_texto, $valor_unitario_texto, $valor_excedente_texto, $desafogo_texto, $personalizado, $procedimentos_selecionados, $qtd_clientes_teto, $tempo_fidelidade, $desconsidera_notificacao, $valor_desconsidera_notificacao){

    $data_status = getDataHora('data');
    $data_atualizacao = getDataHora();

    $dados_plano_verificacao = DBRead('', 'tb_plano', "WHERE id_plano = '".$id_plano."' ");
    if($dados_plano_verificacao[0]['cod_servico'] == 'call_suporte'){
        if($personalizado == 1){
            $dados_plano = DBRead('', 'tb_plano', "WHERE id_plano = '$id_plano' ");
            $versao = $dados_plano[0]['versao'];
    
            $dados_plano_procedimento_historico = DBRead('', 'tb_plano_procedimento_historico', "WHERE id_plano = '$id_plano' AND versao LIKE '%p%' AND versao like '%".$versao."%' ORDER BY id_plano_procedimento_historico DESC ");
            if($dados_plano_procedimento_historico){
                $dados_plano_procedimento_historico = explode("p", $dados_plano_procedimento_historico[0]['versao']);
                $numero_versao = $dados_plano_procedimento_historico[1];
                $numero_versao++;
                $plano_versao = $dados_plano_procedimento_historico[0]."p".$numero_versao;
            }else{
                $plano_versao = $dados_plano[0]['versao']."p1";
            }
    
            foreach ($procedimentos_selecionados as $id_plano_procedimento) {
    
                $dados_historico = array(
                    'id_plano' => $id_plano,
                    'versao' => $plano_versao,
                    'data_atualizacao' => $data_atualizacao,
                    'id_usuario' => $id_usuario,
                    'id_plano_procedimento' => $id_plano_procedimento,
                    'personalizado' => $personalizado
                );
            
                $insertIDHistorico = DBCreate('', 'tb_plano_procedimento_historico', $dados_historico, true);
                registraLog('Inserção de plano histórico no sistema.','i','tb_plano_procedimento_historico',$insertIDHistorico,"id_plano: $id_plano | versao: $plano_versao | data_atualizacao: $data_atualizacao | id_usuario: $id_usuario | id_plano_procedimento: $id_plano_procedimento | personalizado: $personalizado");
            }
    
        }else{
            $dados_plano = DBRead('', 'tb_plano', "WHERE id_plano = '$id_plano' ");
            $plano_versao = $dados_plano[0]['versao'];
        }
    }else{
        $plano_versao = NULL;
        $personalizado = 0;
    }

    $dados = array(
        'id_pessoa' => $id_pessoa,
        'id_plano' => $id_plano,
        'valor_unitario' => $valor_unitario,
        'valor_excedente' => $valor_excedente,
        'valor_plantao' => $valor_plantao,
        'data_inicio_contrato' => $data_inicio_contrato,
        'periodo_contrato' => $periodo_contrato,
        'qtd_contratada' => $qtd_contratada,
        'status' => $status,
        'data_status' => $data_status,
        'data_atualizacao' => $data_atualizacao,
        'valor_total' => $valor_total,
        'indice_reajuste' => $indice_reajuste,
        'dia_pagamento' => $dia_pagamento,
        'obs' => $obs,
        'tipo_cobranca' => $tipo_cobranca,
        'valor_inicial' => $valor_inicial,
        'nome_contrato' => $nome_contrato,
        'realiza_cobranca' => $realiza_cobranca,
        'recebe_ligacao' => $recebe_ligacao,
        'desafogo' => $desafogo,
        'remove_duplicados' => $remove_duplicados,
        'minutos_duplicados' => $minutos_duplicados,
        'id_usuario' => $id_usuario,
        'data_ajuste' => $data_ajuste,
        'contrato_pai' => $id_contrato_plano_pessoa,
        'separar_contrato' => $id_contrato_plano_pessoa_separar,
        'qtd_clientes' => $qtd_clientes,
        'id_responsavel' => $id_responsavel,
        'id_responsavel_tecnico' => $id_responsavel_tecnico,
        'email_nf' => $email_nf,
        'reter_cofins' => $reter_cofins,
        'reter_csll' => $reter_csll,
        'reter_ir' => $reter_ir,
        'reter_pis' => $reter_pis,
        'valor_adesao' => $valor_adesao,
        'tipo_plantao' => $tipo_plantao,
        'valor_diferente_texto' => $valor_diferente_texto,
        'qtd_contratada_texto' => $qtd_contratada_texto,
        'valor_unitario_texto' => $valor_unitario_texto,
        'valor_excedente_texto' => $valor_excedente_texto,
        'desafogo_texto' => $desafogo_texto,
        'plano_versao' => $plano_versao,
        'personalizado' => $personalizado,
        'qtd_clientes_teto' => $qtd_clientes_teto,
        'data_inicial_cobranca' => $data_inicial_cobranca,
        'data_final_cobranca' => $data_final_cobranca,
        'tempo_fidelidade' => $tempo_fidelidade,
        'desconsidera_notificacao' => $desconsidera_notificacao,
        'valor_desconsidera_notificacao' => $valor_desconsidera_notificacao
        );

    if($id_pessoa != "" && $id_plano != "" && $data_inicio_contrato != "" && $periodo_contrato != "" &&  $indice_reajuste != "" && $dia_pagamento != "" && $tipo_cobranca != ""){
        $insertID = DBCreate('', 'tb_contrato_plano_pessoa', $dados, true);
        registraLog('Inserção de contrato.','i','tb_contrato_plano_pessoa',$insertID,"id_pessoa: $id_pessoa | id_plano: $id_plano | valor_unitario: $valor_unitario | valor_excedente: $valor_excedente | valor_plantao: $valor_plantao | data_inicio_contrato: $data_inicio_contrato | periodo_contrato: $periodo_contrato | qtd_contratada: $qtd_contratada | status: $status | data_status: $data_status | data_atualizacao: $data_atualizacao | valor_total: $valor_total | indice_reajuste: $indice_reajuste | dia_pagamento: $dia_pagamento | obs: $obs | tipo_cobranca: $tipo_cobranca | valor_inicial: $valor_inicial | nome_contrato: $nome_contrato | realiza_cobranca: $realiza_cobranca | recebe_ligacao: $recebe_ligacao | desafogo: $desafogo | remove_duplicados: $remove_duplicados | minutos_duplicados: $minutos_duplicados | data_ajuste: $data_ajuste | contrato_pai: $id_contrato_plano_pessoa | separar_contrato: $id_contrato_plano_pessoa_separar | data_inicial_cobranca: $data_inicial_cobranca | data_final_cobranca: $data_final_cobranca | qtd_clientes: $qtd_clientes | id_responsavel: $id_responsavel | id_responsavel_tecnico: $id_responsavel_tecnico | email_nf: $email_nf | reter_cofins: $reter_cofins | reter_csll: $reter_csll | reter_ir: $reter_ir | reter_pis: $reter_pis | valor_adesao: $valor_adesao | tipo_plantao: $tipo_plantao | valor_diferente_texto: $valor_diferente_texto | qtd_contratada_texto: $qtd_contratada_texto | valor_unitario_texto: $valor_unitario_texto | valor_excedente_texto: $valor_excedente_texto | desafogo_texto: $desafogo_texto | plano_versao: $plano_versao | personalizado: $personalizado | qtd_clientes_teto: $qtd_clientes_teto | tempo_fidelidade: $tempo_fidelidade | desconsidera_notificacao: $desconsidera_notificacao | valor_desconsidera_notificacao: $valor_desconsidera_notificacao"); 

        

        $operacao = "Inserir";
        $alteracao = "Contrato";

        $usuario = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = $id_usuario", "a.id_pessoa");
        $pessoa_alterou = $usuario[0]['id_pessoa'];

        // $pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.id_contrato_plano_pessoa = $insertID", "a.id_pessoa");
        // $id_alterado = $pessoa[0]['id_pessoa'];
        $dado_alterado = '';
        notificacao($alteracao, $insertID, $operacao, $pessoa_alterou, $id_usuario, $dado_alterado);

        $alert = ('Contrato inserido com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=contrato-busca");
    }else{
        $alert = ('Não foi possível inserir o contrato!','w');
        header("location: /api/iframe?token=$request->token&view=contrato-form");
    }

    exit;
}

function alterar($id, $id_pessoa, $id_plano, $valor_unitario, $valor_excedente, $valor_plantao, $data_inicio_contrato, $periodo_contrato, $qtd_contratada, $status, $valor_total, $indice_reajuste, $dia_pagamento, $obs, $tipo_cobranca, $valor_inicial, $nome_contrato, $realiza_cobranca, $recebe_ligacao, $desafogo, $remove_duplicados, $minutos_duplicados, $id_usuario, $data_ajuste, $contrato_pai, $id_contrato_plano_pessoa, $id_contrato_plano_pessoa_separar, $qtd_clientes, $id_responsavel, $id_responsavel_tecnico, $flag_perfil, $email_nf, $reter_cofins, $reter_csll, $reter_ir, $reter_pis, $valor_adesao, $data_final_cobranca, $data_inicial_cobranca, $tipo_plantao, $valor_diferente_texto, $qtd_contratada_texto, $valor_unitario_texto, $valor_excedente_texto, $desafogo_texto, $personalizado, $procedimentos_selecionados, $versao, $qtd_clientes_teto, $tempo_fidelidade, $desconsidera_notificacao, $valor_desconsidera_notificacao){

    $dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '$id'");
    $data_atualizacao = getDataHora();
  
    $dados_plano_verificacao = DBRead('', 'tb_plano', "WHERE id_plano = '".$id_plano."' ");
    if($dados_plano_verificacao[0]['cod_servico'] == 'call_suporte'){
        if(($personalizado == 1 && $id_plano != $dados[0]['id_plano']) || $personalizado != $dados[0]['personalizado']){
            $dados_plano = DBRead('', 'tb_plano', "WHERE id_plano = '".$dados[0]['id_plano']."' ");
    
            $dados_plano_procedimento_historico_compara = DBRead('', 'tb_plano_procedimento_historico', "WHERE id_plano = '".$dados[0]['id_plano']."' AND versao = '".$versao."' ORDER BY id_plano_procedimento_historico DESC ");
            $array1 = array();
            $cont = 0;
            if($dados_plano_procedimento_historico_compara){
                foreach ($dados_plano_procedimento_historico_compara as $conteudo_plano_procedimento_historico_compara){
                    $array1[$cont] = $conteudo_plano_procedimento_historico_compara['id_plano_procedimento'];
                    $cont++;
                }
            }
    
            if($personalizado != $dados[0]['personalizado'] && $personalizado == 0){
                $plano_versao = $dados_plano[0]['versao'];
            }else if(array_diff($procedimentos_selecionados, $array1) || array_diff($array1, $procedimentos_selecionados)){
                $dados_plano_procedimento_historico = DBRead('', 'tb_plano_procedimento_historico', "WHERE id_plano = '".$id_plano."' AND versao LIKE '%p%' AND versao like '%".$dados_plano[0]['versao']."%' ORDER BY id_plano_procedimento_historico DESC ");

                if($dados_plano_procedimento_historico){
                    $dados_plano_procedimento_historico = explode("p", $dados_plano_procedimento_historico[0]['versao']);
                    $numero_versao = $dados_plano_procedimento_historico[1];
                    $numero_versao++;
                    $plano_versao = $dados_plano_procedimento_historico[0]."p".$numero_versao;
                }else{
                    $plano_versao = $dados_plano[0]['versao']."p1";
                }
    
                foreach ($procedimentos_selecionados as $id_plano_procedimento) {
    
                    $dados_historico = array(
                        'id_plano' => $id_plano,
                        'versao' => $plano_versao,
                        'data_atualizacao' => $data_atualizacao,
                        'id_usuario' => $id_usuario,
                        'id_plano_procedimento' => $id_plano_procedimento,
                        'personalizado' => $personalizado
                    );
                    $insertIDHistorico = DBCreate('', 'tb_plano_procedimento_historico', $dados_historico, true);
                    registraLog('Inserção de plano histórico no sistema.','i','tb_plano_procedimento_historico',$insertIDHistorico,"id_plano: $id_plano | versao: $plano_versao | data_atualizacao: $data_atualizacao | id_usuario: $id_usuario | id_plano_procedimento: $id_plano_procedimento | personalizado: $personalizado");
                }
    
            }else{
                $plano_versao = $dados[0]['versao'];
            }
    

        }else{
    
            if(!$dados[0]['plano_versao'] || $dados[0]['id_plano'] != $id_plano || $personalizado != $dados[0]['personalizado']){
                $dados_plano = DBRead('', 'tb_plano', "WHERE id_plano = '".$id_plano."' ");
                $plano_versao = $dados_plano[0]['versao'];
                
            }else{
                $plano_versao = $dados[0]['plano_versao'];
                
            }
        }
    }else{
        $plano_versao = NULL;
        $personalizado = 0;
    }

   
    if($id_pessoa != $dados[0]['id_pessoa'] || $id_plano != $dados[0]['id_plano'] || $valor_unitario != $dados[0]['valor_unitario'] || $valor_total != $dados[0]['valor_total'] || $valor_excedente != $dados[0]['valor_excedente'] || $valor_plantao != $dados[0]['valor_plantao'] || $qtd_contratada != $dados[0]['qtd_contratada'] || $data_inicio_contrato != $dados[0]['data_inicio_contrato'] || $periodo_contrato != $dados[0]['periodo_contrato'] || $status != $dados[0]['status'] || $dia_pagamento != $dados[0]['dia_pagamento'] || $indice_reajuste != $dados[0]['indice_reajuste'] || $obs != $dados[0]['obs'] || $tipo_cobranca != $dados[0]['tipo_cobranca'] || $valor_inicial != $dados[0]['valor_inicial'] || $nome_contrato != $dados[0]['nome_contrato'] || $realiza_cobranca != $dados[0]['realiza_cobranca'] || $recebe_ligacao != $dados[0]['recebe_ligacao'] || $desafogo != $dados[0]['desafogo'] || $remove_duplicados != $dados[0]['remove_duplicados'] || $minutos_duplicados != $dados[0]['minutos_duplicados'] || $data_ajuste != $dados[0]['data_ajuste'] || $data_inicial_cobranca != $dados[0]['data_inicial_cobranca'] || $data_final_cobranca != $dados[0]['data_final_cobranca'] || $id_responsavel != $dados[0]['id_responsavel'] || $id_responsavel_tecnico != $dados[0]['id_responsavel_tecnico'] || $qtd_clientes != $dados[0]['qtd_clientes'] || $valor_adesao != $dados[0]['valor_adesao'] || $tipo_plantao != $dados[0]['tipo_plantao'] || $valor_diferente_texto != $dados[0]['valor_diferente_texto'] || $qtd_contratada_texto != $dados[0]['qtd_contratada_texto'] || $valor_unitario_texto != $dados[0]['valor_unitario_texto'] || $valor_excedente_texto != $dados[0]['valor_excedente_texto'] || $desafogo_texto != $dados[0]['desafogo_texto'] || $plano_versao != $dados[0]['plano_versao'] || $personalizado != $dados[0]['personalizado'] || $qtd_clientes_teto != $dados[0]['qtd_clientes_teto'] || $tempo_fidelidade != $dados[0]['tempo_fidelidade'] || $id_contrato_plano_pessoa != $dados[0]['contrato_pai'] || $id_contrato_plano_pessoa_separar != $dados[0]['separar_contrato'] || $desconsidera_notificacao != $dados[0]['desconsidera_notificacao'] || $valor_desconsidera_notificacao != $dados[0]['valor_desconsidera_notificacao']){

        $dados_historico = array(
            'id_contrato_plano_pessoa' => $id,
            'id_pessoa' => $dados[0]['id_pessoa'],
            'id_plano' => $dados[0]['id_plano'],
            'valor_unitario' => $dados[0]['valor_unitario'],
            'valor_excedente' => $dados[0]['valor_excedente'],
            'valor_plantao' => $dados[0]['valor_plantao'],
            'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
            'periodo_contrato' => $dados[0]['periodo_contrato'],
            'qtd_contratada' => $dados[0]['qtd_contratada'],
            'status' => $dados[0]['status'],
            'data_status' => $dados[0]['data_status'],
            'data_atualizacao' => $dados[0]['data_atualizacao'],
            'valor_total' => $dados[0]['valor_total'],
            'indice_reajuste' => $dados[0]['indice_reajuste'],
            'dia_pagamento' => $dados[0]['dia_pagamento'],
            'obs' => $dados[0]['obs'],
            'tipo_cobranca' => $dados[0]['tipo_cobranca'],
            'valor_inicial' => $dados[0]['valor_inicial'],
            'nome_contrato' => $dados[0]['nome_contrato'],
            'realiza_cobranca' => $dados[0]['realiza_cobranca'],
            'recebe_ligacao' => $dados[0]['recebe_ligacao'],
            'desafogo' => $dados[0]['desafogo'],
            'remove_duplicados' => $dados[0]['remove_duplicados'],
            'minutos_duplicados' => $dados[0]['minutos_duplicados'],
            'id_usuario' => $dados[0]['id_usuario'],
            'data_ajuste' => $dados[0]['data_ajuste'],
            'contrato_pai' => $dados[0]['contrato_pai'],
            'separar_contrato' => $dados[0]['separar_contrato'],
            'qtd_clientes' => $dados[0]['qtd_clientes'],
            'id_responsavel' => $dados[0]['id_responsavel'],
            'id_responsavel_tecnico' => $dados[0]['id_responsavel_tecnico'],
            'valor_adesao' => $dados[0]['valor_adesao'],
            'tipo_plantao' => $dados[0]['tipo_plantao'],
            'valor_diferente_texto' => $dados[0]['valor_diferente_texto'],
            'qtd_contratada_texto' => $dados[0]['qtd_contratada_texto'],
            'valor_unitario_texto' => $dados[0]['valor_unitario_texto'],
            'valor_excedente_texto' => $dados[0]['valor_excedente_texto'],
            'desafogo_texto' => $dados[0]['desafogo_texto'],
            'plano_versao' => $dados[0]['plano_versao'],
            'personalizado' => $dados[0]['personalizado'],
            'qtd_clientes_teto' => $dados[0]['qtd_clientes_teto'],
            'data_inicial_cobranca' => $dados[0]['data_inicial_cobranca'],
            'data_final_cobranca' => $dados[0]['data_final_cobranca'],
            'tempo_fidelidade' => $dados[0]['tempo_fidelidade'],         
            'desconsidera_notificacao' => $dados[0]['desconsidera_notificacao'],
            'valor_desconsidera_notificacao' => $dados[0]['valor_desconsidera_notificacao']           
        );
        DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);
    }

    $status_antigo = $dados[0]['status'];
    if($status != $status_antigo){
        $data_status = getDataHora('data');
    }else{
        $data_status = $dados[0]['data_status'];
    }

    $dados = array(
        'id_pessoa' => $id_pessoa,
        'id_plano' => $id_plano,
        'valor_unitario' => $valor_unitario,
        'valor_excedente' => $valor_excedente,
        'valor_plantao' => $valor_plantao,
        'data_inicio_contrato' => $data_inicio_contrato,
        'periodo_contrato' => $periodo_contrato,
        'qtd_contratada' => $qtd_contratada,
        'status' => $status,
        'data_status' => $data_status,
        'data_atualizacao' => $data_atualizacao,
        'valor_total' => $valor_total,
        'indice_reajuste' => $indice_reajuste,
        'dia_pagamento' => $dia_pagamento,
        'obs' => $obs,
        'tipo_cobranca' => $tipo_cobranca,
        'valor_inicial' => $valor_inicial,
        'nome_contrato' => $nome_contrato,
        'realiza_cobranca' => $realiza_cobranca,
        'recebe_ligacao' => $recebe_ligacao,
        'desafogo' => $desafogo,
        'remove_duplicados' => $remove_duplicados,
        'minutos_duplicados' => $minutos_duplicados,
        'id_usuario' => $id_usuario,
        'data_ajuste' => $data_ajuste,
        'data_final_cobranca' => $data_final_cobranca,
        'contrato_pai' => $id_contrato_plano_pessoa,
        'separar_contrato' => $id_contrato_plano_pessoa_separar,
        'qtd_clientes' => $qtd_clientes,
        'id_responsavel' => $id_responsavel,
        'id_responsavel_tecnico' => $id_responsavel_tecnico,
        'valor_adesao' => $valor_adesao,
        'tipo_plantao' => $tipo_plantao,
        'valor_diferente_texto' => $valor_diferente_texto,
        'qtd_contratada_texto' => $qtd_contratada_texto,
        'valor_unitario_texto' => $valor_unitario_texto,
        'valor_excedente_texto' => $valor_excedente_texto,
        'desafogo_texto' => $desafogo_texto,
        'plano_versao' => $plano_versao,
        'personalizado' => $personalizado,   
        'qtd_clientes_teto' => $qtd_clientes_teto,
        'data_inicial_cobranca' => $data_inicial_cobranca,
        'data_final_cobranca' => $data_final_cobranca,
        'tempo_fidelidade' => $tempo_fidelidade,
        'desconsidera_notificacao' => $desconsidera_notificacao,
        'valor_desconsidera_notificacao' => $valor_desconsidera_notificacao
    );
    
    DBUpdate('', 'tb_contrato_plano_pessoa', $dados, "id_contrato_plano_pessoa = '$id'");
    registraLog('Alteração de contrato.','a','tb_contrato_plano_pessoa',$id,"id_pessoa: $id_pessoa | id_plano: $id_plano | valor_unitario: $valor_unitario | valor_excedente: $valor_excedente | valor_plantao: $valor_plantao | data_inicio_contrato: $data_inicio_contrato | periodo_contrato: $periodo_contrato | qtd_contratada: $qtd_contratada | status: $status | data_status: $data_status | data_atualizacao: $data_atualizacao | valor_total: $valor_total | indice_reajuste: $indice_reajuste | dia_pagamento: $dia_pagamento | obs: $obs | tipo_cobranca: $tipo_cobranca | valor_inicial: $valor_inicial | nome_contrato: $nome_contrato | realiza_cobranca: $realiza_cobranca | recebe_ligacao: $recebe_ligacao | desafogo: $desafogo | remove_duplicados: $remove_duplicados | minutos_duplicados: $minutos_duplicados | data_ajuste: $data_ajuste | data_inicial_cobranca: $data_inicial_cobranca | contrato_pai: $id_contrato_plano_pessoa | separar_contrato: $id_contrato_plano_pessoa_separar | data_final_cobranca: $data_final_cobranca | qtd_clientes: $qtd_clientes | id_responsavel: $id_responsavel | id_responsavel_tecnico: $id_responsavel_tecnico | valor_adesao: $valor_adesao | tipo_plantao: $tipo_plantao | valor_diferente_texto: $valor_diferente_texto | qtd_contratada_texto: $qtd_contratada_texto | valor_unitario_texto: $valor_unitario_texto | valor_excedente_texto: $valor_excedente_texto | desafogo_texto: $desafogo_texto | plano_versao: $plano_versao | personalizado: $personalizado | qtd_clientes_teto: $qtd_clientes_teto | tempo_fidelidade: $tempo_fidelidade | desconsidera_notificacao: $desconsidera_notificacao | valor_desconsidera_notificacao: $valor_desconsidera_notificacao");
    
    if($flag_perfil == 1){
        $dados2 = array(
            'email_nf' => $email_nf,
            'reter_cofins' => $reter_cofins,
            'reter_csll' => $reter_csll,
            'reter_ir' => $reter_ir,
            'reter_pis' => $reter_pis
        );

        DBUpdate('', 'tb_contrato_plano_pessoa', $dados2, "id_contrato_plano_pessoa = '$id'");
        registraLog('Alteração de dados de NFS-e no contrato.','a','tb_contrato_plano_pessoa',$id,"email_nf: $email_nf | reter_cofins: $reter_cofins | reter_csll: $reter_csll | reter_ir: $reter_ir | reter_pis: $reter_pis");

        $arquivo_tmp = $_FILES['pdf_contrato']['tmp_name'];

        if ($arquivo_tmp && $arquivo_tmp !='') {
            $nome = $_FILES['pdf_contrato']['name'];
            $nome_arquivo = explode('/tmp/', $arquivo_tmp);
            $nome_arquivo = $nome_arquivo[1];
        
            // Pega a extensão
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
            // Somente arquivos .pdf
        
            if ( (strstr('.pdf', $extensao))) {
        
                // Concatena a pasta com o nome
                $destino = '../inc/upload-pdf-contrato/'.$nome_arquivo.'.pdf';
                
                // unlink('../inc/upload-catalogo-equipamento/'.$arquivo_tmp.'.jpg');
        
                // echo 'destino: '.$destino.'<br>';
                // echo 'arquivo_tmp: '. $arquivo_tmp.'<br>';
                // echo 'nome: '. $nome_arquivo.'<br>';
        
                // die();
                //tenta mover o arquivo para o destino
        
                if( (@move_uploaded_file ($arquivo_tmp, $destino))){
                    
                    //adicionar aqui a exclusão do pdf antigo caso ele exista
                    $pdf = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id."' ");
                    $pdf = $pdf[0]['pdf_contrato'];
                    
                    if($pdf){
                        $pdf = '../inc/upload-pdf-contrato/'.$pdf.'.pdf';
                        unlink($pdf);
                    }

                    $dados4 = array(
                        'pdf_contrato' => $nome_arquivo,
                    );

                    DBUpdate('', 'tb_contrato_plano_pessoa', $dados4, "id_contrato_plano_pessoa = '$id'");
                    registraLog('Alteração do pdf do contrato.','a','tb_contrato_plano_pessoa',$id,"pdf_contrato: $nome_arquivo");
            

                }else{
                    $alert = ('Não foi possível inserir PDF!','w');
                }    
            }else{
                    $alert = ('Você poderá enviar apenas imagens "*.pdf"!', 'd', 'AVISO!');
                    header("location: /api/iframe?token=$request->token&view=contrato-form");
                exit;
            }
        }
    }

    $alert = ('Contrato alterado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=contrato-busca");
   
    exit;
}

function downloadPdf($id_contrato_plano_pessoa){
    $dados = DBRead('','tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
    
    $arquivo = $dados[0]['pdf_contrato'];
    $arquivo_download = '../inc/upload-pdf-contrato/'.$arquivo.".pdf";

    if(!file_exists($arquivo_download)){    
        //$alert = ('Não foi possível fazer o download do anexo!<br>'.$arquivo_download,'w');
        $alert = ('Não foi possível fazer o download do anexo!','w');
        header("location: /api/iframe?token=$request->token&view=contrato_form&alterar=".$id_contrato_plano_pessoa."");

    }else{

        header('Cache-control: private');
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($arquivo_download));
        header('Content-Disposition: filename=' . $arquivo_download);
        header("Content-Disposition: attachment; filename=" . basename($arquivo_download));
        readfile($arquivo_download);
    }
}

?>
