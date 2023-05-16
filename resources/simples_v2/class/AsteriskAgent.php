<?php
require_once(__DIR__."/System.php");
$id_usuario = $_SESSION['id_usuario'];
$dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id_usuario'");
$id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];
$comando = (!empty($_POST['comando'])) ? $_POST['comando'] : false;

if ($comando == 'call') {
    $numero = (!empty($_POST['numero'])) ? preg_replace("/[^0-9]/", "", $_POST['numero']) : false;
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if($dados_agent){
        $dados_call = array('comando' => $comando,'sip' => $dados_agent['sip'], 'numero' => $numero);
        $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
    }else{
        $retorno['sucesso'] = false;
    }
    echo json_encode($retorno);

} else if ($comando == 'login') {
    $sip = (!empty($_POST['ramal'])) ? preg_replace("/[^0-9]/", "", $_POST['ramal']) : false;    
    $dados_agent = verificaAgent($id_asterisk_usuario);
    $dados_sip = verificaSIP($sip);
    if(!$dados_agent && !$dados_sip){
        $dados_call = array('comando' => $comando,'sip' => $sip, 'agent' => $id_asterisk_usuario);
        $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
    }else{
        $retorno['sucesso'] = false;
    }
    echo json_encode($retorno);

} else if ($comando == 'verificaLogin') {  
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if($dados_agent){
        $retorno['logado'] = true;
        $retorno['sip'] = $dados_agent['sip'];
        $retorno['pausa'] = $dados_agent['pausa'];
    }else{
        $retorno['logado'] = false;
    }
    echo json_encode($retorno);

} else if ($comando == 'logoff') {
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if($dados_agent){
        $dados_call = array('comando' => $comando,'sip' => $dados_agent['sip'], 'agent' => $id_asterisk_usuario);
        $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
    }else{
        $retorno['sucesso'] = false;
    }
    echo json_encode($retorno);

} else if ($comando == 'verificaLogoff') {  
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if(!$dados_agent){
        $retorno['logado'] = false;
    }else{
        $retorno['logado'] = true;
    }
    echo json_encode($retorno);

} else if ($comando == 'pause') {  
    $tipo_pausa = (!empty($_POST['tipo_pausa'])) ? preg_replace("/[^0-9]/", "", $_POST['tipo_pausa']) : false;    
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if($dados_agent && !$dados_agent['pausa']){
        $dados_call = array('comando' => $comando,'sip' => $dados_agent['sip'], 'agent' => $id_asterisk_usuario, 'dado' => $tipo_pausa);
        $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
        $dados = array(
            'paused' => '1'
        );
        DBUpdate('snep', 'queue_agents', $dados, "codigo = '$id_asterisk_usuario'");
    }else{
        $retorno['sucesso'] = false;
    }
    echo json_encode($retorno);

} else if ($comando == 'unpause') { 
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if($dados_agent && $dados_agent['pausa']){
        $dados_call = array('comando' => $comando,'sip' => $dados_agent['sip'], 'agent' => $id_asterisk_usuario);
        $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
        $dados = array(
            'paused' => '0'
        );
        DBUpdate('snep', 'queue_agents', $dados, "codigo = '$id_asterisk_usuario'");
    }else{
        $retorno['sucesso'] = false;
    }
    echo json_encode($retorno);

} else if ($comando == 'verificaPause') {
    $dados_agent = verificaAgent($id_asterisk_usuario);
    if($dados_agent['pausa']){
        $dados_pausa = DBRead('snep', 'queue_agents_pause a',"INNER JOIN tipo_pausa b ON a.tipo_pausa = b.id WHERE a.codigo = '$id_asterisk_usuario' ORDER BY a.data_pause DESC LIMIT 1", "a.*, b.nome");       
        $retorno['pausa'] = true;
        $retorno['data_pause'] = converteDataHora($dados_pausa[0]['data_pause']);
        $retorno['nome'] = $dados_pausa[0]['nome'];
    }else{
        $retorno['pausa'] = false;
    }
    echo json_encode($retorno);

} else {
    $retorno = array('sucesso' => false, 'dados' => 'Comando não reconhedido!');
    echo json_encode($retorno);
}

function verificaAgent($agent){
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");
    if ($retorno_agents['sucesso']) {
        foreach ($retorno_agents['dados'] as $conteudo) {
            if ($conteudo['agent'] == $agent){ 
                $dados_agent['sip'] = $conteudo['sip'];
                $dados_agent['pausa'] = $conteudo['pausa'];
                return $dados_agent;
            }
        }
    }
    return false;
}

function verificaSIP($sip){
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");
    if ($retorno_agents['sucesso']) {
        foreach ($retorno_agents['dados'] as $conteudo) {
            if ($conteudo['sip'] == $sip){ 
                $dados_agent['agent'] = $conteudo['agent'];
                $dados_agent['pausa'] = $conteudo['pausa'];
                return $dados_agent;
            }
        }
    }
    return false;
}
?>