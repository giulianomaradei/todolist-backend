<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 2;
$fila1 = (!empty($_POST['fila1'])) ? $_POST['fila1'] : '';
$fila2 = (!empty($_POST['fila2'])) ? $_POST['fila2'] : '';
$tempo_fila1 = (!empty($_POST['tempo_fila1'])) ? $_POST['tempo_fila1'] : 0;
$tempo_fila2 = (!empty($_POST['tempo_fila2'])) ? $_POST['tempo_fila2'] : 0;
$controle_automatico_fila = (!empty($_POST['controle_automatico_fila'])) ? $_POST['controle_automatico_fila'] : 0;
$tipo_fila_controle_automatico = (!empty($_POST['tipo_fila_controle_automatico'])) ? $_POST['tipo_fila_controle_automatico'] : 'interna';
$pesquisa = (!empty($_POST['pesquisa'])) ? $_POST['pesquisa'] : 0;
$aceita_ligacao = (!empty($_POST['aceita_ligacao'])) ? $_POST['aceita_ligacao'] : 0;
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('snep', 'empresas', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome, $tipo, $status, $fila1, $fila2, $tempo_fila1, $tempo_fila2, $controle_automatico_fila, $tipo_fila_controle_automatico, $pesquisa, $aceita_ligacao);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=prefixo-central-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('snep', 'empresas', "WHERE BINARY nome = '".addslashes($nome)."' AND id != '$id'");
    if (!$dados) {
        alterar($id, $nome, $tipo, $status, $fila1, $fila2, $tempo_fila1, $tempo_fila2, $controle_automatico_fila, $tipo_fila_controle_automatico, $pesquisa, $aceita_ligacao);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=prefixo-central-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else if (isset($_GET['desativar'])) {

    $id = (int)$_GET['desativar'];
    desativar($id);

}else if (isset($_GET['ativar'])) {

    $id = (int)$_GET['ativar'];
    ativar($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $tipo, $status, $fila1, $fila2, $tempo_fila1, $tempo_fila2, $controle_automatico_fila, $tipo_fila_controle_automatico, $pesquisa, $aceita_ligacao){
    $dados = array(
        'nome' => $nome,
        'tipo' => $tipo,
        'fila1' => $fila1,
        'fila2' => $fila2,
        'tempo_fila1' => $tempo_fila1,
        'tempo_fila2' => $tempo_fila2,
        'controle_automatico_fila' => $controle_automatico_fila,
        'tipo_fila_controle_automatico' => $tipo_fila_controle_automatico,
        'pesquisa' => $pesquisa,
        'aceita_ligacao' => $aceita_ligacao,
        'status' => $status
    );
    $insertID = DBCreate('snep', 'empresas', $dados, true);
    registraLog('Inserção de novo prefixo.','i','empresas',$insertID,"nome: $nome | tipo: $tipo | fila1: $fila1 | fila2: $fila2 | tempo_fila1: $tempo_fila1 | tempo_fila2: $tempo_fila2 | controle_automatico_fila: $controle_automatico_fila | tipo_fila_controle_automatico: $tipo_fila_controle_automatico | pesquisa: $pesquisa | aceita_ligacao: $aceita_ligacao | status: $status");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=prefixo-central-busca");   
    exit;
}

function alterar($id, $nome, $tipo, $status, $fila1, $fila2, $tempo_fila1, $tempo_fila2, $controle_automatico_fila, $tipo_fila_controle_automatico, $pesquisa, $aceita_ligacao){
    $dados = array(
        'nome' => $nome,
        'tipo' => $tipo,
        'fila1' => $fila1,
        'fila2' => $fila2,
        'tempo_fila1' => $tempo_fila1,
        'tempo_fila2' => $tempo_fila2,
        'controle_automatico_fila' => $controle_automatico_fila,
        'tipo_fila_controle_automatico' => $tipo_fila_controle_automatico,
        'pesquisa' => $pesquisa,
        'aceita_ligacao' => $aceita_ligacao,
        'status' => $status
    );
    DBUpdate('snep', 'empresas', $dados, "id = $id");
    registraLog('Alteração de prefixo.','a','empresas',$id,"nome: $nome | tipo: $tipo | fila1: $fila1 | fila2: $fila2 | tempo_fila1: $tempo_fila1 | tempo_fila2: $tempo_fila2 | controle_automatico_fila: $controle_automatico_fila | tipo_fila_controle_automatico: $tipo_fila_controle_automatico | pesquisa: $pesquisa | aceita_ligacao: $aceita_ligacao | status: $status");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=prefixo-central-busca");     
    exit;
}

function excluir($id){
    $dados = array(
        'status' => '2'
    );
    DBUpdate('snep', 'empresas',$dados,"id = $id");
    registraLog('Exclusão de prefixo.','e','empresas',$id,'');
    $alert = ('Item excluído com sucesso!','s');   
    header("location: /api/iframe?token=$request->token&view=prefixo-central-busca");
    exit;
}

function desativar($id){
    $dados = array(
        'status' => '0'
    );
    DBUpdate('snep', 'empresas',$dados,"id = $id");
    registraLog('Alteração de prefixo.','a','empresas',$id,'');
    $alert = ('Item desativado com sucesso!','s');   
    header("location: /api/iframe?token=$request->token&view=prefixo-central-busca");
    exit;
}

function ativar($id){
    $dados = array(
        'status' => '1'
    );
    DBUpdate('snep', 'empresas',$dados,"id = $id");
    registraLog('Alteração de prefixo.','a','empresas',$id,'');
    $alert = ('Item ativado com sucesso!','s');   
    header("location: /api/iframe?token=$request->token&view=prefixo-central-busca");
    exit;
}
?>