<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

$id_conta_receber = addslashes($parametros['id_conta_receber']);
$id_conta_pagar = addslashes($parametros['id_conta_pagar']);
$observacao = addslashes($parametros['observacao']);

if($id_conta_receber){
    $dados = array(
        'observacao' => $observacao
    );
    
    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
    registraLog('Alteração de observação na conta a receber.','a','tb_conta_receber',$id,"observacao: $observacao");
}

if($id_conta_pagar){
    $dados = array(
        'observacao' => $observacao
    );
    
    DBUpdate('', 'tb_conta_pagar', $dados, "id_conta_pagar = $id_conta_pagar");
    registraLog('Alteração de observação na conta a pagar.','a','tb_conta_pagar',$id,"observacao: $observacao");
}


?>