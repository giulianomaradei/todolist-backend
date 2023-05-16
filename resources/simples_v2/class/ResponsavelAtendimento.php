<?php
require_once(__DIR__."/System.php");


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$acao = (!empty($_POST['acao'])) ? $_POST['acao'] : '';

if($acao == 'verificar'){
    //VOZ
    $data_inicial = addslashes($parametros['data_inicial']);

    $id_usuario = addslashes($parametros['id_usuario']);

    $dados = DBRead('', 'tb_responsavel_atendimento',"WHERE id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 0");

    if($dados){
        $resultado = 1;

    }else{
        $resultado = 0;
    }
    echo json_encode($resultado);


}else if($acao == 'inserir_responsavel'){
    //VOZ
    $id_usuario = addslashes($parametros['id_usuario']);
    $id_usuario_inserido = $_SESSION['id_usuario'];
    $data_hora_inserido = getDataHora();

    $dados = array(
        'id_usuario' => $id_usuario,
        'data_hora_inserido' => $data_hora_inserido,
        'status' => '1',
        'id_usuario_inserido' => $id_usuario_inserido
    );

    $insertID = DBCreate('', 'tb_responsavel_atendimento', $dados, true);
    registraLog('Inserção de responsavel atendimento.','a','tb_responsavel_atendimento',$id_usuario,"id_usuario: $id_usuario | data_hora_inserido: $data_hora_inserido | status: 1 | id_usuario_inserido: $id_usuario_inserido");

}else if($acao == 'remover_responsavel'){
    //VOZ
    $id_usuario = addslashes($parametros['id_usuario']);
    $id_usuario_removido = $_SESSION['id_usuario'];
    $data_hora_removido = getDataHora();

    $dados = array(
        'status' => '0',
        'id_usuario_removido' => $id_usuario_removido,
        'data_hora_removido' => $data_hora_removido
    );
    DBUpdate('', 'tb_responsavel_atendimento', $dados, "id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 0");
    registraLog('Remoção de responsavel atendimento.','a','tb_responsavel_atendimento',$id_usuario,"status: 0 | id_usuario_removido: $id_usuario_removido | data_hora_removido: $data_hora_removido");


}else if($acao == 'verifica_antigo'){
    //VOZ e TEXTO
    
    $dados_responsavel_atendimento = DBRead('', 'tb_responsavel_atendimento',"WHERE status = 1");

    if($dados_responsavel_atendimento){
        $resultado = 0;
        foreach ($dados_responsavel_atendimento as $conteudo_responsavel_atendimento) {
            $id_usuario = $conteudo_responsavel_atendimento['id_usuario'];
            $data_inicial    = new DateTime($conteudo_responsavel_atendimento['data_hora_inserido']); 
            $data_agora      = new DateTime(getDataHora()); 

            $diff = $data_inicial->diff($data_agora); 

            if($diff ->format("%Y-%M-%D %H:%I:%s") >= '00-00-00 12:00:00'){
                $data_hora_removido = getDataHora();
                
                $dados = array(
                    'status' => '0',
                    'data_hora_removido' => $data_hora_removido
                );

                DBUpdate('', 'tb_responsavel_atendimento', $dados, "id_usuario = '".$id_usuario."' AND status = 1");
                registraLog('Verifica antigo responsavel atendimento.','a','tb_intervalo',$id_usuario,"status: 0 | data_hora_removido: $data_hora_removido");
                $resultado = 1;
            }
        }
        echo json_encode($resultado);
    }
}else if($acao == 'inserir_responsavel_texto'){
    //TEXTO
    
    $id_usuario = addslashes($parametros['id_usuario']);
    $id_usuario_inserido = $_SESSION['id_usuario'];
    $data_hora_inserido = getDataHora();
    $grupo_atendimento_chat = addslashes($parametros['grupo_atendimento_chat']);

    $dados_grupo_atendimento_chat_contrato = DBRead('', 'tb_grupo_atendimento_chat_contrato',"WHERE id_grupo_atendimento_chat = '".$grupo_atendimento_chat."' ");

    foreach ($dados_grupo_atendimento_chat_contrato as $conteudo_grupo_atendimento_chat_contrato) {
        $id_contrato_plano_pessoa = $conteudo_grupo_atendimento_chat_contrato['id_contrato_plano_pessoa'];
        $dados = array(
            'id_usuario' => $id_usuario,
            'data_hora_inserido' => $data_hora_inserido,
            'status' => '1',
            'id_usuario_inserido' => $id_usuario_inserido,
            'tipo' => '1',
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_grupo_atendimento_chat' => $grupo_atendimento_chat
        );
    
        $insertID = DBCreate('', 'tb_responsavel_atendimento', $dados, true);
        registraLog('Inserção de responsavel atendimento texto.','a','tb_responsavel_atendimento',$id_usuario,"id_usuario: $id_usuario | data_hora_inserido: $data_hora_inserido | status: 1 | id_usuario_inserido: $id_usuario_inserido | tipo: 1 | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_hora_inserido: $data_hora_inserido | id_grupo_atendimento_chat: $grupo_atendimento_chat");
    }
    
    $resultado = 1;
    echo json_encode($resultado);

}else if($acao == 'remover_responsavel_texto'){
    //TEXTO

    $id_usuario = addslashes($parametros['id_usuario']);
    $id_usuario_removido = $_SESSION['id_usuario'];
    $data_hora_removido = getDataHora();

    $dados = array(
        'status' => '0',
        'id_usuario_removido' => $id_usuario_removido,
        'data_hora_removido' => $data_hora_removido
    );
    DBUpdate('', 'tb_responsavel_atendimento', $dados, "id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 1");
    registraLog('Remoção da responsavel atendimento texto.','a','tb_responsavel_atendimento',$id_usuario,"status: 0 | id_usuario_removido: $id_usuario_removido | data_hora_removido: $data_hora_removido");

    $resultado = 1;
    echo json_encode($resultado);

}else if($acao == 'alterar_responsavel_texto'){
    //TEXTO
    
    $id_usuario = addslashes($parametros['id_usuario']);
    $id_usuario_inserido = $_SESSION['id_usuario'];
    $data_hora_inserido = getDataHora();
    $grupo_atendimento_chat = addslashes($parametros['grupo_atendimento_chat']);



    $dados_grupo_atendimento_chat_contrato = DBRead('', 'tb_grupo_atendimento_chat_contrato',"WHERE id_grupo_atendimento_chat = '".$grupo_atendimento_chat."' ");
    

    foreach ($dados_grupo_atendimento_chat_contrato as $conteudo_grupo_atendimento_chat_contrato) {
        $dados_verificacao = DBRead('', 'tb_responsavel_atendimento',"WHERE id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 1 AND id_grupo_atendimento_chat = '".$conteudo_grupo_atendimento_chat_contrato['id_grupo_atendimento_chat']."' AND id_contrato_plano_pessoa = '".$conteudo_grupo_atendimento_chat_contrato['id_contrato_plano_pessoa']."' ");
        if(!$dados_verificacao){
            $id_contrato_plano_pessoa = $conteudo_grupo_atendimento_chat_contrato['id_contrato_plano_pessoa'];
            $dados = array(
                'id_usuario' => $id_usuario,
                'data_hora_inserido' => $data_hora_inserido,
                'status' => '1',
                'id_usuario_inserido' => $id_usuario_inserido,
                'tipo' => '1',
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                'id_grupo_atendimento_chat' => $grupo_atendimento_chat
            );
        
            $insertID = DBCreate('', 'tb_responsavel_atendimento', $dados, true);
            registraLog('Inserção de responsavel atendimento texto.','a','tb_responsavel_atendimento',$id_usuario,"id_usuario: $id_usuario | data_hora_inserido: $data_hora_inserido | status: 1 | id_usuario_inserido: $id_usuario_inserido | tipo: 1 | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_hora_inserido: $data_hora_inserido | id_grupo_atendimento_chat: $grupo_atendimento_chat");
        }
    }
    
    $resultado = 1;
    echo json_encode($resultado);

}else if($acao == 'verifica_removido_texto'){
    //TEXTO
    
    $id_usuario = addslashes($parametros['id_usuario']);
    $auxiliar = addslashes($parametros['auxiliar']);

    $auxiliar = str_replace(","," AND id_grupo_atendimento_chat != ", $auxiliar);
    
    $dados_verificacao = DBRead('', 'tb_responsavel_atendimento',"WHERE id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 1 ".$auxiliar." ");
    if($dados_verificacao){
        foreach ($dados_verificacao as $conteudo_verificacao) {
            $id_contrato_plano_pessoa = $conteudo_verificacao['id_contrato_plano_pessoa'];

            $id_usuario_removido = $_SESSION['id_usuario'];
            $data_hora_removido = getDataHora();

            $dados = array(
                'status' => '0',
                'id_usuario_removido' => $id_usuario_removido,
                'data_hora_removido' => $data_hora_removido
            );
    
            DBUpdate('', 'tb_responsavel_atendimento', $dados, "id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 1 AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
            registraLog('Remoção da responsavel atendimento texto.','a','tb_responsavel_atendimento',$id_usuario,"status: 0 | id_usuario_removido: $id_usuario_removido | data_hora_removido: $data_hora_removido");
        }
    }
    
    $resultado = 1;
    echo json_encode($resultado);

}




?>
