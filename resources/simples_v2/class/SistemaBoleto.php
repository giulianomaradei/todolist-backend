<?php
require_once(__DIR__."/System.php");


if (!empty($_POST['sistema_alterar'])) {
    $id = (int)$_POST['sistema_alterar'];
    $dados = DBRead('', 'tb_boleto', "WHERE id_boleto = '$id'");

    $situacao = (!empty($_POST['situacao'])) ? $_POST['situacao'] : $dados[0]['situacao'];
    $obs_alteracao = (!empty($_POST['obs_alteracao'])) ? $_POST['obs_alteracao'] : NULL;
    alterar($id, $situacao, $obs_alteracao);
}else{
    header("location: ../adm.php");
    exit;
} 

function alterar($id, $situacao, $obs_alteracao){

    $dados = array(
        'situacao' => $situacao,
        'obs_alteracao' => $obs_alteracao
    );

    DBUpdate('', 'tb_boleto', $dados, "id_boleto = $id");
    registraLog('Alteração de boleto via sistema.','a','tb_boleto',$id,"situacao: $situacao | obs_alteracao: $obs_alteracao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=sistema-boleto-busca");

    exit;
}

?>