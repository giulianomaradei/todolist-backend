<?php
require_once(__DIR__."/System.php");


$configuracao_cadastro = (!empty($_POST['configuracao_cadastro'])) ? $_POST['configuracao_cadastro'] : '';
$nome_contrato = (!empty($_POST['nome_contrato'])) ? $_POST['nome_contrato'] : '';

if (!empty($_POST['inserir'])) {

    inserir($configuracao_cadastro, $nome_contrato);

}else if (!empty($_POST['alterar'])) {
	
	$id = (!empty($_POST['alterar'])) ? $_POST['alterar'] : '';
    alterar($configuracao_cadastro, $nome_contrato, $id);

}else if (isset($_GET['excluir'])) {

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

function inserir($configuracao_cadastro, $nome_contrato){
    
    $dados_verificacao = DBRead('', 'tb_contrato_configuracao', "WHERE nome_contrato = '$nome_contrato'");
	if($configuracao_cadastro && $nome_contrato && !$dados_verificacao){

		$dados = array(
	        'nome_contrato' => $nome_contrato,
	        'contrato_descricao' => $configuracao_cadastro
	    );
	    $insertID = DBCreate('', 'tb_contrato_configuracao', $dados, true);
	    registraLog('Inserção de modelo de contrato.','i','tb_contrato_configuracao',$insertID,"nome_contrato: $nome_contrato | contrato_descricao: $configuracao_cadastro");
	    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';	    header("location: /api/iframe?token=$request->token&view=modelo-contrato-busca");
	    exit;       	

	}else{
	    $alert = ('Não foi possível inserir o item!','w');
	    header("location: /api/iframe?token=$request->token&view=modelo-contrato-form");
   	    exit;

	}
}

function alterar($configuracao_cadastro, $nome_contrato, $id){

	$dados_verificacao = DBRead('', 'tb_contrato_configuracao', "WHERE nome_contrato = '$nome_contrato' AND id_contrato_configuracao != '".$id."' ");
	if($configuracao_cadastro && $nome_contrato && !$dados_verificacao ){

		$dados = array(
	        'nome_contrato' => $nome_contrato,
	        'contrato_descricao' => $configuracao_cadastro
	    );
    	DBUpdate('', 'tb_contrato_configuracao', $dados, "id_contrato_configuracao = $id");
	    registraLog('Alteração de modelo de contrato.','a','tb_contrato_configuracao',$id,"nome_contrato: $nome_contrato | contrato_descricao: $configuracao_cadastro");
	    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';	    header("location: /api/iframe?token=$request->token&view=modelo-contrato-busca");
	    exit;       	

	}else{
	    $alert = ('Não foi possível inserir o item!','w');
	    header("location: /api/iframe?token=$request->token&view=modelo-contrato-busca");
   	    exit;

	}

}

function excluir($id){
    
  	$dados = array(
    	'status' => '0'
	);

    DBUpdate('', 'tb_contrato_configuracao',$dados,"id_contrato_configuracao = $id");
    registraLog('Exclusão de modelo de contrato.','e','tb_usuario',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';	header("location: /api/iframe?token=$request->token&view=modelo-contrato-busca");
    exit;

}

function desativar($id){

    $dados = array(
        'status' => '2',
    );
    DBUpdate('', 'tb_contrato_configuracao',$dados,"id_contrato_configuracao = $id");
    registraLog('Desativação de modelo de contrato.','e','tb_contrato_configuracao',$id,'');
    $alert = ('Modelo de contrato desativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=modelo-contrato-busca");
    exit;
}

function ativar($id){

    $dados = array(
        'status' => '1'
    );
    DBUpdate('', 'tb_contrato_configuracao',$dados,"id_contrato_configuracao = $id");
    registraLog('Ativação de modelo de contrato.','e','tb_contrato_configuracao',$id,'');
    $alert = ('Modelo de contrato ativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=modelo-contrato-busca");
    exit;
}