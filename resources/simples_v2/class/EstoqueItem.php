<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$valor_unitario = (!empty($_POST['valor_unitario'])) ? converteMoeda($_POST['valor_unitario'], "banco") : '0.00';
$informacao_adicional = (!empty($_POST['informacao_adicional'])) ? $_POST['informacao_adicional'] : '';
$quantidade_minima = (!empty($_POST['quantidade_minima'])) ? $_POST['quantidade_minima'] : 0;
$quantidade_atual = (!empty($_POST['quantidade_atual'])) ? $_POST['quantidade_atual'] : 0;
$id_estoque_localizacao = (!empty($_POST['id_estoque_localizacao'])) ? $_POST['id_estoque_localizacao'] : 0;

if(!empty($_POST['inserir'])){
    inserir($nome, $valor_unitario, $informacao_adicional, $quantidade_minima, $quantidade_atual, $id_estoque_localizacao);
}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    alterar($nome, $valor_unitario, $informacao_adicional, $id, $quantidade_minima, $id_estoque_localizacao);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $valor_unitario, $informacao_adicional, $quantidade_minima, $quantidade, $id_estoque_localizacao){

    $dados = array(        
        'nome' => $nome,
        'quantidade' => 0,
        'valor_unitario' => $valor_unitario,
        'informacao_adicional' => $informacao_adicional,
        'quantidade_minima' => $quantidade_minima,
        'quantidade' => $quantidade,
        'id_estoque_localizacao' => $id_estoque_localizacao
    );
    $insertID = DBCreate('', 'tb_estoque_item', $dados, true);
    registraLog('Inserção de novo item no estoque.','i','tb_estoque_item',$insertID,"nome: $nome | quantidade: 0 | valor_unitario: $valor_unitario | informacao_adicional: $informacao_adicional | quantidade_minima: $quantidade_minima | quantidade: $quantidade | id_estoque_localizacao: $id_estoque_localizacao");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=estoque-item-form");    
    exit;
}

function alterar($nome, $valor_unitario, $informacao_adicional, $id, $quantidade_minima, $id_estoque_localizacao){

    $dados = array(        
        'nome' => $nome,
        'valor_unitario' => $valor_unitario,
        'informacao_adicional' => $informacao_adicional,
        'quantidade_minima' => $quantidade_minima,
        'id_estoque_localizacao' => $id_estoque_localizacao
    ); 
    DBUpdate('', 'tb_estoque_item', $dados, "id_estoque_item = $id");
    registraLog('Alteração de item no estoque.','a','tb_estoque_item',$id,"nome: $nome | valor_unitario: $valor_unitario | informacao_adicional: $informacao_adicional | quantidade_minima: $quantidade_minima | id_estoque_localizacao: $id_estoque_localizacao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=estoque-item-busca");
    exit;
}

?>