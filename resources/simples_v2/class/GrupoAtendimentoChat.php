<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$contrato_grupo = (!empty($_POST['contrato_grupo'])) ? $_POST['contrato_grupo'] : '';
$cor = (!empty($_POST['cor'])) ? $_POST['cor'] : '';

if (!empty($_POST['inserir'])) {
    
    inserir($nome, $contrato_grupo, $cor);
    
} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    alterar($id, $nome, $contrato_grupo, $cor);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

} else {
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $contrato_grupo, $cor){
    $dados = array(
        'nome' => $nome,
        'cor' => $cor
    );

    $id_usuario_alterou = $_SESSION['id_usuario'];

    if(($nome && $nome != "") &&($contrato_grupo && $contrato_grupo != "")){
        $insertID = DBCreate('', 'tb_grupo_atendimento_chat', $dados, true);
        registraLog('Inserção de novo grupo de atendimento por chat.','i','tb_grupo_atendimento_chat',$insertID,"nome: $nome | cor: $cor");

        $data = getDataHora();

        foreach ($contrato_grupo as $contrato) {
            $dados = array(
                'id_grupo_atendimento_chat' => $insertID,
                'id_contrato_plano_pessoa' => $contrato
            );

            $insertID_2 = DBCreate('', 'tb_grupo_atendimento_chat_contrato', $dados, true);
            registraLog('Inserção de novo grupo de atendimento por chat/contrato.','i','tb_grupo_atendimento_chat_contrato',$insertID_2,"id_grupo_atendimento_chat: $insertID | id_contrato_plano_pessoa: $contrato");

            $dados = array(
                'id_grupo_atendimento_chat' => $insertID,
                'id_contrato_plano_pessoa' => $contrato,
                'data' => $data,
                'id_usuario_alterou' => $id_usuario_alterou
            );

            $insertID_3 = DBCreate('', 'tb_grupo_atendimento_chat_contrato_historico', $dados, true);
            registraLog('Alteração de contrato em grupo de atendimento por chat historico.','i','tb_grupo_atendimento_chat_contrato_historico',$insertID_3,"id_grupo_atendimento_chat: $insertID | id_contrato_plano_pessoa: $contrato | data: $data | id_usuario_alterou: $id_usuario_alterou");
        }
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");

    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-form");
    }
    exit;
}

function alterar($id, $nome, $contrato_grupo, $cor){
    
    $id_usuario_alterou = $_SESSION['id_usuario'];

    $dados_contrato_grupo = DBRead('', 'tb_grupo_atendimento_chat_contrato', "WHERE id_grupo_atendimento_chat = '".$id."' ");

    asort($dados_contrato_grupo);
    asort($contrato_grupo);

    $retorno_bd = array();
    $cont=0;
    foreach($dados_contrato_grupo as $key => $valor){
        $retorno_bd[$cont] = $valor['id_contrato_plano_pessoa'];
        $cont++;
    }

    $contrato_grupo_form = array();
    $cont=0;
    foreach($contrato_grupo as $valor){
        $contrato_grupo_form[$cont] = $valor;
        $cont++;
    }

    $dados = array(
        'nome' => $nome,
        'cor' => $cor
    );

    if(($nome && $nome != "") && ($contrato_grupo && $contrato_grupo != "")){

        DBUpdate('', 'tb_grupo_atendimento_chat', $dados, "id_grupo_atendimento_chat = $id");
        registraLog('Inserção de novo grupo de atendimento por chat.','a','tb_grupo_atendimento_chat',$id,"nome: $nome | cor: $cor");
        
        if(array_diff($contrato_grupo_form, $retorno_bd) || array_diff($retorno_bd, $contrato_grupo_form)){        
            $query = "DELETE FROM tb_grupo_atendimento_chat_contrato WHERE id_grupo_atendimento_chat = $id";
            $link = DBConnect('');
            $result = @mysqli_query($link, $query);
            DBClose($link);

            $data = getDataHora();

            foreach ($contrato_grupo as $contrato) {
                $dados = array(
                    'id_grupo_atendimento_chat' => $id,
                    'id_contrato_plano_pessoa' => $contrato
                );

                $insertID_2 = DBCreate('', 'tb_grupo_atendimento_chat_contrato', $dados, true);
                registraLog('Alteração de novo grupo de atendimento por chat/contrato.','i','tb_grupo_atendimento_chat_contrato',$insertID_2,"id_grupo_atendimento_chat: $id | id_contrato_plano_pessoa: $contrato");

                $dados = array(
                    'id_grupo_atendimento_chat' => $id,
                    'id_contrato_plano_pessoa' => $contrato,
                    'data' => $data,
                    'id_usuario_alterou' => $id_usuario_alterou
                );

                $insertID_3 = DBCreate('', 'tb_grupo_atendimento_chat_contrato_historico', $dados, true);
                registraLog('Alteração de contrato em grupo de atendimento por chat historico.','i','tb_grupo_atendimento_chat_contrato_historico',$insertID_3,"id_grupo_atendimento_chat: $id | id_contrato_plano_pessoa: $contrato | data: $data | id_usuario_alterou: $id_usuario_alterou");

            }
            $alert = ('Item inserido com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");
        }else{
            $alert = ('Os itens não foram alterados. Os mesmos contratos já estavam vinculados a este grupo','w');
            header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");
        }
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-form&alterar=$id");
    }
    exit;
  
}

function excluir($id){

    $dados = array(
        'status' => 2
    );
    
    $result = DBUpdate('', 'tb_grupo_atendimento_chat', $dados, "id_grupo_atendimento_chat = $id");
    registraLog('Alteração de grupo de atendimento por chat.','a','tb_grupo_atendimento_chat',$id,"status: 2");

    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=grupo-atendimento-chat-busca");
    exit;
}

?>