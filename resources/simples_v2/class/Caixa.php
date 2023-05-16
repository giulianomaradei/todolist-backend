<?php
require_once(__DIR__."/System.php");

$nome  = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$saldo  = (!empty($_POST['saldo'])) ? $_POST['saldo'] : '';
$status  = (!empty($_POST['status'])) ? $_POST['status'] : '0';
$aceita_movimentacao  = (!empty($_POST['aceita_movimentacao'])) ? $_POST['aceita_movimentacao'] : 0;

// echo "aceita_movimentacao - ".$aceita_movimentacao."<hr>";

if(!empty($_POST['inserir'])){
    if($nome != "" && $nome){
        if($aceita_movimentacao != '0'){
            if($saldo != "" && $saldo){
                inserir($nome, $saldo, $status, $aceita_movimentacao);
            }else{
                $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                        header("location: /api/iframe?token=$request->token&view=caixa-form"); 
            }
        }else{
            $saldo = '0,00';
            inserir($nome, $saldo, $status, $aceita_movimentacao);            
        }
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=caixa-form"); 
    }
}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    alterar($id, $status);

}else if(isset($_GET['ativar'])){

    $id = (int)$_GET['ativar'];
    ativar($id);

}else if(isset($_GET['desativar'])){

    $id = (int)$_GET['desativar'];
    desativar($id);

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $saldo, $status, $aceita_movimentacao){
    $saldo = converteMoeda($saldo, 'banco');
    
    $dados = array(
        'nome' => $nome,
        'saldo' => $saldo,
        'status' => $status,
        'aceita_movimentacao' => $aceita_movimentacao
    );
   
    $insertID = DBCreate('', 'tb_caixa', $dados, true);
    registraLog('Inserção de nova caixa.','i','tb_caixa',$insertID,"nome: $nome | saldo: $saldo | status: $status | aceita_movimentacao: $aceita_movimentacao");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=caixa-busca");
    
    exit;
}

function alterar($id, $status){
    $dados = array(
        'status' => $status
    );

    DBUpdate('', 'tb_caixa', $dados, "id_caixa = $id");
    registraLog('Alteração de caixa.','a','tb_caixa',$id,"status: $status");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=caixa-busca");

    exit;
}

function ativar($id){
    $dados = array(
        'status' => '1'
    );

    DBUpdate('', 'tb_caixa', $dados, "id_caixa = $id");
    registraLog('Alteração de centro de custos.','a','tb_caixa',$id,"status: 1");
    $alert = ('Item ativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=caixa-busca");

    exit;
}

function desativar($id){
    $dados = array(
        'status' => '0'
    );

    DBUpdate('', 'tb_caixa', $dados, "id_caixa = $id");
    registraLog('Alteração de centro de custos.','a','tb_caixa',$id,"status: 0");
    $alert = ('Item desativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=caixa-busca");

    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_caixa WHERE id_caixa = ".$id;
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de caixa.', 'e', 'tb_caixa', $id, '');
    if(!$result){
        $alert = ('Erro ao excluir item, o mesmo está em uso!', 'd');
        header("location: /api/iframe?token=$request->token&view=caixa-busca-form&alterar=".$id);
    }else{
        $alert = ('Item excluído com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=caixa-busca");
    }
    exit;
}

?>