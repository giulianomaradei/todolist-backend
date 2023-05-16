<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$codigo = (!empty($_POST['codigo'])) ? $_POST['codigo'] : '';
$filas = (!empty($_POST['filas'])) ? join(',', $_POST['filas']) : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('snep', 'queue_agents', "WHERE BINARY membername = '".addslashes($nome)."' OR codigo = '$codigo'");
    if (!$dados) {
        inserir($nome, $codigo, $filas, $status);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=atendente-central-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('snep', 'queue_agents', "WHERE BINARY membername = '".addslashes($nome)."' AND uniqueid != '$id'");
    if (!$dados) {
        alterar($id, $nome, $filas, $status);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=atendente-central-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

} else if (isset($_GET['desativar'])) {

    $id = (int)$_GET['desativar'];
    desativar($id);

} else if (isset($_GET['ativar'])) {

    $id = (int)$_GET['ativar'];
    ativar($id);

} else if (isset($_GET['logoff_forcado'])) {

    $agent = (int)$_GET['logoff_forcado'];
    logoff_forcado($agent);

} else if (isset($_GET['desligar_chamada'])) {

    $agent = (int)$_GET['desligar_chamada'];
    desligar_chamada($agent);

} else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $codigo, $filas, $status){
    $dados = array(        
        'codigo' => $codigo,
        'membername' => $nome,
        'queue_name' => $filas,
        'secret' => $codigo,
        'status' => $status
    );
    $insertID = DBCreate('snep', 'queue_agents', $dados, true);
    registraLog('Inserção de novo atendente.','i','queue_agents',$insertID,"membername: $nome | codigo: $codigo | secret: $codigo | queue_name: $filas");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=atendente-central-busca");
    
    exit;
}

function alterar($id, $nome, $filas, $status){
    $dados = array(
        'membername' => $nome,
        'queue_name' => $filas,
        'status' => $status
    );    
    DBUpdate('snep', 'queue_agents', $dados, "uniqueid = $id");
    registraLog('Alteração de atendente.','a','queue_agents',$id,"membername: $nome | queue_name: $filas");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=atendente-central-busca");    
    exit;
}

function excluir($id){
    DBDelete('snep','queue_agents',"uniqueid = '$id'");
    registraLog('Exclusão de atendente.','e','queue_agents',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=atendente-central-busca");
    exit;
}

function desativar($id){
    $dados = array(
        'status' => '0'
    );
    DBUpdate('snep', 'queue_agents',$dados,"uniqueid = $id");
    registraLog('Alteração de atendente.','a','queue_agents',$id,'');
    $alert = ('Item desativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=atendente-central-busca");
    exit;
}

function ativar($id){
    $dados = array(
        'status' => '1'
    );
    DBUpdate('snep', 'queue_agents',$dados,"uniqueid = $id");
    registraLog('Alteração de atendente.','a','queue_agents',$id,'');
    $alert = ('Item ativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=atendente-central-busca");
    exit;
}

function logoff_forcado($agent){
    $dados_call = array('comando' => 'logoff_forcado','sip' => '0000','agent' => $agent);
    $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
    if($retorno["sucesso"] && $retorno["dados"] == 'S'){
        registraLog('Logoff forçado de atendente.','a','queue_agents',$agent,'');
        $alert = ('Atendente deslogado com sucesso!','s');
    }else{
        $alert = ('Não foi possível deslogar o atendente!','d');
    }
    header("location: /api/iframe?token=$request->token&view=controle-sessao-central");
    exit;
}

function desligar_chamada($agent){
    $dados_agent = DBRead('snep','queue_agents',"WHERE codigo = '$agent'");
    $sip_agent = $dados_agent[0]['interface_logged'];
    $sip_agent = explode("/", $sip_agent);
    $sip_agent = $sip_agent[1];
    $canal = '';
    $retorno_entradas = troca_dados_curl("http://172.31.18.211/central_simples/retorna_entradas.php");
    if($retorno_entradas['sucesso']){
        foreach ($retorno_entradas['dados'] as $conteudo) {
            if($conteudo['sip'] == $sip_agent){
                $canal = $conteudo['canal'];
                break;
            }
        }
    }
    if($canal){
        $dados_call = array('comando' => 'desligar_chamada','sip' => '0000','dado' => $canal);
        $retorno = troca_dados_curl("http://172.31.18.211/central_simples/comando_agent.php",$dados_call);
        if($retorno["sucesso"] && $retorno["dados"] == 'S'){
            registraLog('Chamada desligada de atendente.','a','',$agent,'');
            $alert = ('Chamada desligada com sucesso!','s');
        }else{
            $alert = ('Não foi possível desligar a chamada!','d');
        }
    }else{
        $alert = ('Não foi possível identificar o canal da chamada!','d');
    }
    
    header("location: /api/iframe?token=$request->token&view=controle-sessao-central");
    exit;
}
?>