<?php
require_once(__DIR__."/System.php");


if (!empty($_POST['sistema_alterar'])) {
    $id = (int)$_POST['sistema_alterar'];
    $dados = DBRead('', 'tb_nfs', "WHERE id_nfs = '$id'");

    $status = (!empty($_POST['status'])) ? $_POST['status'] : $dados[0]['status'];
    $obs_alteracao = (!empty($_POST['obs_alteracao'])) ? $_POST['obs_alteracao'] : NULL;

    alterar($id, $status, $obs_alteracao);
    
}else{
    header("location: ../adm.php");
    exit;
} 


function alterar($id, $status, $obs_alteracao){

    $dados = array(
        'status' => $status,
        'obs_alteracao' => $obs_alteracao
    );

    DBUpdate('', 'tb_nfs', $dados, "id_nfs = $id");
    registraLog('Alteração de boleto via sistema.','a','tb_nfs',$id,"status: $status | obs_alteracao: $obs_alteracao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=sistema-nfs-busca");

    exit;
}

?>