<?php
require_once(__DIR__."/System.php");


$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$id_responsavel_tecnico = (!empty($_POST['id_responsavel_tecnico'])) ? $_POST['id_responsavel_tecnico'] : '';
if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];

    if($id_responsavel && $id_responsavel_tecnico && $id){
        alterar($id, $id_responsavel, $id_responsavel_tecnico);
    }else{
        $alert = ('Deve-se inserir os responsáveis!','w');
        header("location: /api/iframe?token=$request->token&view=responsavel-contrato-busca");
        exit;
    } 

}else{
    header("location: ../adm.php");
    exit;
}

function alterar($id, $id_responsavel, $id_responsavel_tecnico){

    $dados = array(
        'id_responsavel' => $id_responsavel,
        'id_responsavel_tecnico' => $id_responsavel_tecnico
    );
    
    DBUpdate('', 'tb_contrato_plano_pessoa', $dados, "id_contrato_plano_pessoa = '$id'");
    registraLog('Alteração de responsáveis do contrato.','a','tb_contrato_plano_pessoa',$id,"id_responsavel: $id_responsavel | id_responsavel_tecnico: $id_responsavel_tecnico");
    
    $alert = ('Responsáveis alterados com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=responsavel-contrato-busca");
   
    exit;

     
}

?>
