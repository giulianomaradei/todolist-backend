<?php
require_once(__DIR__."/System.php");


$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$plano_acao = (!empty($_POST['plano_acao'])) ? $_POST['plano_acao'] : '';
$passo_atendimento = (!empty($_POST['passo_atendimento'])) ? $_POST['passo_atendimento'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;

if(!empty($_POST['inserir'])) {

    $verifica = DBRead('', 'tb_monitoria_quesito', "WHERE descricao = '$descricao' AND status != 2 ");

    if($verifica){
       
        $alert = ('Já existe um quesito com esta descrição!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
        exit;
           
    }else{
        inserir($descricao, $plano_acao, $passo_atendimento, $status);
    }

}else if(!empty($_POST['alterar'])) {

    $id = (int)$_POST['alterar'];

    $verifica = DBRead('', 'tb_monitoria_quesito', "WHERE descricao = '$descricao' AND id_monitoria_quesito != '$id' AND status != 2 ");

    if($verifica){
       
        $alert = ('Já existe um quesito com esta descrição!','w');
        header("location: /api/iframe?token=$request->token&view=monitoria-quesito-form&alterar=$id");
        exit;
           
    }else{
        
        alterar($id, $descricao, $plano_acao, $passo_atendimento, $status);
    }

}else if(isset($_GET['excluir'])) {
    
    $id = (int) $_GET['excluir'];
    excluir($id);

}else if(isset($_GET['ativar'])) {
    
    $id = (int) $_GET['ativar'];
    ativar($id);

}else if(isset($_GET['inativar'])) {
    
    $id = (int) $_GET['inativar'];
    inativar($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($descricao, $plano_acao, $passo_atendimento, $status){
    
    if($descricao !='' &&  $plano_acao !='' && $passo_atendimento !='' && $status !=''){
        
        $dados = array(
            'descricao' =>$descricao,
            'plano_acao' => $plano_acao,
            'passo_atendimento' => $passo_atendimento,
            'status' => $status
        );

        $insertID = DBCreate('', 'tb_monitoria_quesito', $dados, true);
        registraLog('Inserção de monitoria quesito.','i','tb_monitoria_quesito',$insertID,"descricao: $descricao | plano_acao: $plano_acao | passo_atendimento: $passo_atendimento | status: $status");
        
        $alert = ('Quesito da monitoria inserido com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
        exit; 
        
    }else{
        
        $alert = ('Não foi possível inserir o quesito!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
   	    exit;
    } 
}

function alterar($id, $descricao, $plano_acao, $passo_atendimento, $status){

    if($id != ''){

        $dados = array(
            'descricao' =>$descricao,
            'plano_acao' => $plano_acao,
            'passo_atendimento' => $passo_atendimento,
            'status' => $status
        );

        DBUpdate('','tb_monitoria_quesito', $dados, "id_monitoria_quesito = $id");
        registraLog('Alteração no quesito monitoria.', 'a', 'tb_monitoria_quesito', $id, "descricao: $descricao | plano_acao: $plano_acao | passo_atendimento: $passo_atendimento | status: $status");

        $alert = ('Quesito da monitoria alterado com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
        exit; 

    }else{

        $alert = ('Não foi possível alterar o quesito!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-form&alterar=$id");
   	    exit;
    }
}

function excluir($id){

    if($id !=''){
        
        $dados = array(
            'status' => 2
        );

        DBUpdate('','tb_monitoria_quesito', $dados, "id_monitoria_quesito = $id");
        registraLog('Exclusão do quesito monitoria.', 'a', 'tb_monitoria_quesito', $id, "status: 2");

        $alert = ('Quesito da monitoria excluido com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
        exit;

    }else{

        $alert = ('Não foi possível excluir o quesito!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
   	    exit;
    }
}

function inativar($id){

    if($id !=''){
        
        $dados = array(
            'status' => 0
        );

        DBUpdate('','tb_monitoria_quesito', $dados, "id_monitoria_quesito = $id");
        registraLog('Exclusão do quesito monitoria.', 'a', 'tb_monitoria_quesito', $id, "status: 0");

        $alert = ('Quesito da monitoria excluido com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
        exit;

    }else{

        $alert = ('Não foi possível excluir o quesito!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
   	    exit;
    }
}

function ativar($id){

    if($id !=''){

        $dados = array(
            'status' => 1
        );

        DBUpdate('','tb_monitoria_quesito', $dados, "id_monitoria_quesito = $id");
        registraLog('Exclusão do quesito monitoria.', 'a', 'tb_monitoria_quesito', $id, "status: 1");

        $alert = ('Quesito da monitoria ativa com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
        exit;

    }else{

        $alert = ('Não foi possível ativar o quesito!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-quesito-busca");
   	    exit;
    }
}

?>