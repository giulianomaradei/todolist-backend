<?php
require_once(__DIR__."/System.php");

$data_inicio = (!empty($_POST['data_inicio'])) ? converteData($_POST['data_inicio']) : '';
$data_prazo = (!empty($_POST['data_prazo'])) ? converteData($_POST['data_prazo']) : '';
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : 0;
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';


if(!empty($_POST['inserir'])){

    $verificacao_contrato = DBRead('', 'tb_redes_ativacao', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($id_contrato_plano_pessoa, $data_inicio, $data_prazo, $id_responsavel);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=ativacao-redes-form");
        exit;
    }

}else if(!empty($_POST['concluir_ativacao'])){
    $id = (int)$_POST['concluir_ativacao'];
    $data_conclusao = (!empty($_POST['data_conclusao'])) ? converteData($_POST['data_conclusao']) : '';
    concluir_ativacao($id, $data_conclusao);
    
}else if(!empty($_POST['adicionar_comentario'])){
    $id = (int)$_POST['adicionar_comentario'];
    $comentario = (!empty($_POST['comentario'])) ? $_POST['comentario'] : 0;
    adicionar_comentario($id, $comentario);
    
}else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id);
}else if(isset($_GET['excluir_comentario'])){
    $id = (int)$_GET['excluir_comentario'];
    $id_ativacao = (int)$_GET['id_ativacao'];
    excluir_comentario($id, $id_ativacao);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $data_inicio, $data_prazo, $id_responsavel){

    $dados = array(        
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'data_inicio' => $data_inicio,
        'data_prazo' => $data_prazo,
        'id_responsavel' => $id_responsavel
    );

    $insertID = DBCreate('', 'tb_redes_ativacao', $dados, true);
    registraLog('Inserção de nova ativação de redes.','i','tb_redes_ativacao', $insertID, "id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_inicio: $data_inicio | data_prazo: $data_prazo | id_responsavel: $id_responsavel");
    
    $alert = ('Item inserido com sucesso!', 's');

    header("location: /api/iframe?token=$request->token&view=ativacao-redes-busca");
    exit;
}

function concluir_ativacao($id, $data_conclusao){

    $dados = array(        
        'data_conclusao' => $data_conclusao
    );

    DBUpdate('', 'tb_redes_ativacao', $dados, "id_redes_ativacao = $id");

    registraLog('Conclusão de ativação de redes.','a','tb_redes_ativacao', $id, "data_conclusao: $data_conclusao ");
    $alert = ('Ativação concluída com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=ativacao-redes-form&alterar=$id");
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_redes_ativacao WHERE id_redes_ativacao = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de ativação de redes.','e','tb_redes_ativacao',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=ativacao-redes-busca");   
    exit;
}

function adicionar_comentario($id, $comentario){
    $data = getDataHora();
    $id_usuario = $_SESSION['id_usuario'];
    $dados = array(        
        'comentario' => $comentario,
        'data' => getDataHora(),
        'id_usuario' => $id_usuario,
        'id_redes_ativacao' => $id
    );

    $insertID = DBCreate('', 'tb_redes_ativacao_comentario', $dados, true);
    registraLog('Inserção de novo comentário de ativação de redes.','i','tb_redes_ativacao_comentario', $insertID, "comentario: $comentario | data: $data | id_usuario: $id_usuario | id_redes_ativacao: $id");
    
    $alert = ('Comentário inserido com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=ativacao-redes-form&alterar=$id");
    exit;
}

function excluir_comentario($id, $id_ativacao){
    $query = "DELETE FROM tb_redes_ativacao_comentario WHERE id_redes_ativacao_comentario = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de ativação de redes.','e','tb_redes_ativacao',$id,'');
    if(!$result){
        $alert = ('Erro ao excluir comentário!','d');
    }else{
        $alert = ('Comentário excluído com sucesso!','s');
    }
    header("location: /api/iframe?token=$request->token&view=ativacao-redes-form&alterar=$id_ativacao");
    exit;
}

?>