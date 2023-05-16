<?php
require_once(__DIR__."/System.php");

$grupo = (!empty($_POST['grupo'])) ? $_POST['grupo'] : '';
$operadores = (!empty($_POST['operadores'])) ? $_POST['operadores'] : '';

if (!empty($_POST['inserir'])) {
    
    inserir($grupo, $operadores);
    
} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    alterar($id, $grupo, $operadores);

} else {
    header("location: ../adm.php");
    exit;
}

function inserir($grupo, $operadores){

    $id_usuario_alterou = $_SESSION['id_usuario'];

    if($grupo != '' && $operadores !=''){

        $data = getDataHora();

        foreach ($operadores as $id) {
            $dados = array(
                'id_grupo_atendimento_chat' => $grupo,
                'id_usuario' => $id
            );

            $insertID = DBCreate('', 'tb_grupo_atendimento_chat_operador', $dados, true);
            registraLog('Inserção de operador em grupo de atendimento por chat.','i','tb_grupo_atendimento_chat_operador',$insertID,"id_grupo_atendimento_chat: $grupo | id_usuario: $id");

            $dados = array(
                'id_grupo_atendimento_chat' => $grupo,
                'id_usuario' => $id,
                'data' => $data,
                'id_usuario_alterou' => $id_usuario_alterou
            );

            $insertID = DBCreate('', 'tb_grupo_atendimento_chat_operador_historico', $dados, true);
            registraLog('Inserção de operador em grupo de atendimento por chat historico.','i','tb_grupo_atendimento_chat_operador_historico',$insertID,"id_grupo_atendimento_chat: $grupo | id_usuario: $id | data: $data | id_usuario_alterou: $id_usuario_alterou");
        }
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");

    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-form");
    }
    exit;
}

function alterar($id, $grupo, $operadores){

    $id_usuario_alterou = $_SESSION['id_usuario'];

    $dados_operadores = DBRead('', 'tb_grupo_atendimento_chat_operador', "WHERE id_grupo_atendimento_chat = '".$grupo."' ", "id_usuario");

    asort($dados_operadores);
    asort($operadores);
    
    $retorno_bd = array();
    $cont=0;
    foreach($dados_operadores as $key => $valor){
        $retorno_bd[$cont] = $valor['id_usuario'];
        $cont++;
    }

    $operadores_form = array();
    $cont=0;
    foreach($operadores as $valor){
        $operadores_form[$cont] = $valor;
        $cont++;
    }

    if($grupo != ''){

        if(array_diff($operadores_form, $retorno_bd) || array_diff($retorno_bd, $operadores_form)){
            DBDelete('','tb_grupo_atendimento_chat_operador',"id_grupo_atendimento_chat = '$grupo'");
            $data = getDataHora();

            if($operadores){
                foreach ($operadores as $id) {
                
                    $dados = array(
                        'id_grupo_atendimento_chat' => $grupo,
                        'id_usuario' => $id
                    );
    
                    $insertID = DBCreate('', 'tb_grupo_atendimento_chat_operador', $dados, true);
                    registraLog('Alteração de operador em grupo de atendimento por chat.','i','tb_grupo_atendimento_chat_operador',$insertID,"id_grupo_atendimento_chat: $grupo | id_usuario: $id");
    
                    $dados = array(
                        'id_grupo_atendimento_chat' => $grupo,
                        'id_usuario' => $id,
                        'data' => $data,
                        'id_usuario_alterou' => $id_usuario_alterou
                    );
    
                    $insertID = DBCreate('', 'tb_grupo_atendimento_chat_operador_historico', $dados, true);
                    registraLog('Alteração de operador em grupo de atendimento por chat historico.','i','tb_grupo_atendimento_chat_operador_historico',$insertID,"id_grupo_atendimento_chat: $grupo | id_usuario: $id | data: $data | id_usuario_alterou: $id_usuario_alterou");
                }
            }
            
            $alert = ('Item inserido com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");
        }else{
            $alert = ('Os itens não foram alterados. Os mesmos atendentes já estavam vinculados a este grupo!','w');
            header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");
        }

    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-vincular-operador&alterar=$id");
    }
    exit;
  
}
