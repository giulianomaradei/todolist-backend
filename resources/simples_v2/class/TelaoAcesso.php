<?php
require_once(__DIR__."/System.php");


$usuarios_atendimento = (!empty($_POST['usuarios_atendimento'])) ? $_POST['usuarios_atendimento'] : '';
$usuarios_monitoramento = (!empty($_POST['usuarios_monitoramento'])) ? $_POST['usuarios_monitoramento'] : '';

if (!empty($_POST['inserir'])) {    
    
    inserir($usuarios_atendimento, $usuarios_monitoramento);

} else if (!empty($_POST['alterar'])) {
   
    alterar($usuarios_atendimento, $usuarios_monitoramento);

} else{
    header("location: ../adm.php");
    exit;
}

function inserir($usuarios_atendimento, $usuarios_monitoramento){

    if ($usuarios_atendimento !='') {
        foreach ($usuarios_atendimento as $id_usuario) {
            $dados = array(        
                'id_usuario' => $id_usuario,
            );
        
            $insertID = DBCreate('', 'tb_telao_acesso_atendimento', $dados, true);
            registraLog('Inserção de novo acesso ao telão atendimento.','i','tb_telao_acesso_atendimento',$insertID,"id_usuario: $id_usuario");
        }
    } 

    if ($usuarios_monitoramento !='') {
        foreach ($usuarios_monitoramento as $id_usuario) {
            $dados = array(        
                'id_usuario' => $id_usuario,
            );
        
            $insertID = DBCreate('', 'tb_telao_acesso_monitoramento', $dados, true);
            registraLog('Inserção de novo acesso ao telão monitoramento.','i','tb_telao_acesso_monitoramento',$insertID,"id_usuario: $id_usuario");
        }
    } 

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=telao-acesso-form");    
    exit;
}

function alterar($usuarios_atendimento, $usuarios_monitoramento){

    DBDelete('', 'tb_telao_acesso_atendimento');
    DBDelete('', 'tb_telao_acesso_monitoramento');

    if ($usuarios_atendimento !='') {
        foreach ($usuarios_atendimento as $id_usuario) {
            $dados = array(        
                'id_usuario' => $id_usuario,
            );
        
            $insertID = DBCreate('', 'tb_telao_acesso_atendimento', $dados, true);
            registraLog('Inserção de novo acesso ao telão atendimento.','i','tb_telao_acesso_atendimento',$insertID,"id_usuario: $id_usuario");
        }
    } 

    if ($usuarios_monitoramento !='') {
        foreach ($usuarios_monitoramento as $id_usuario) {
            $dados = array(        
                'id_usuario' => $id_usuario,
            );
        
            $insertID = DBCreate('', 'tb_telao_acesso_monitoramento', $dados, true);
            registraLog('Inserção de novo acesso ao telão monitoramento.','i','tb_telao_acesso_monitoramento',$insertID,"id_usuario: $id_usuario");
        }
    }
     
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=telao-acesso-form");    
    exit;
}
?>