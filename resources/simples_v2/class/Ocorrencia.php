<?php
require_once(__DIR__."/System.php");


$id_usuario_ocorrencia = (!empty($_POST['id_usuario_ocorrencia'])) ? $_POST['id_usuario_ocorrencia'] : '';
$data = (!empty($_POST['data'])) ? converteData($_POST['data']) : getDataHora('data');
$id_ocorrencia_tipo = (!empty($_POST['id_ocorrencia_tipo'])) ? $_POST['id_ocorrencia_tipo'] : '';
$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : '';
$comentario = (!empty($_POST['comentario'])) ? $_POST['comentario'] : '';

$liderados = busca_liderados($_SESSION['id_usuario']);
$dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '".$_SESSION['id_usuario']."'");

if (!empty($_POST['inserir'])) {

    if(in_array($id_usuario_ocorrencia,$liderados) || $dados_usuario[0]['id_perfil_sistema'] != '20' || $dados_usuario[0]['id_perfil_sistema'] != '12'){
        inserir($id_usuario_ocorrencia, $data, $id_ocorrencia_tipo, $classificacao, $comentario);
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=ocorrencia-form");
        exit;
    }
    

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];  

    if(in_array($id_usuario_ocorrencia,$liderados) || $dados_usuario[0]['id_perfil_sistema'] != '20' || $dados_usuario[0]['id_perfil_sistema'] != '12'){
        alterar($id, $id_usuario_ocorrencia, $data, $id_ocorrencia_tipo, $classificacao, $comentario);
    }else{
        $alert = ('Erro ao alterar item!','d');
        header("location: /api/iframe?token=$request->token&view=ocorrencia-busca");
        exit;
    }   

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    $dados_ocorrencia = DBRead('','tb_ocorrencia',"WHERE id_ocorrencia = '$id'");
    if($dados_ocorrencia && (in_array($dados_ocorrencia[0]['id_usuario_ocorrencia'],$liderados) || $dados_usuario[0]['id_perfil_sistema'] != '20' || $dados_usuario[0]['id_perfil_sistema'] != '12')){
        excluir($id);
    }else{
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';        header("location: /api/iframe?token=$request->token&view=ocorrencia-busca");
        exit;
    }

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_usuario_ocorrencia, $data, $id_ocorrencia_tipo, $classificacao, $comentario){

    $id_usuario_registro = $_SESSION['id_usuario'];
    $data_registro = getDataHora();

    $dados = array(
        'id_usuario_ocorrencia' => $id_usuario_ocorrencia,
        'data' => $data,
        'id_ocorrencia_tipo' => $id_ocorrencia_tipo,
        'classificacao' => $classificacao,
        'comentario' => $comentario,
        'id_usuario_registro' => $id_usuario_registro,
        'data_registro' => $data_registro,
        'status' => '1'
    );

    $isertID = DBCreate('', 'tb_ocorrencia', $dados, true);
    registraLog('Inserção de ocorrência.','i','tb_ocorrencia',$isertID,"id_usuario_ocorrencia: $id_usuario_ocorrencia | data: $data | id_ocorrencia_tipo: $id_ocorrencia_tipo | classificacao: $classificacao | comentario: $comentario | id_usuario_registro: $id_usuario_registro | data_registro: $data_registro | status: 1");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=ocorrencia-busca");
    exit;

}

function alterar($id, $id_usuario_ocorrencia, $data, $id_ocorrencia_tipo, $classificacao, $comentario){
    
    $dados = array(
        'id_usuario_ocorrencia' => $id_usuario_ocorrencia,
        'data' => $data,
        'id_ocorrencia_tipo' => $id_ocorrencia_tipo,
        'classificacao' => $classificacao,
        'comentario' => $comentario
    );
    DBUpdate('', 'tb_ocorrencia', $dados, "id_ocorrencia = $id");
    registraLog('Alteração de ocorrência.','a','tb_ocorrencia',$id,"id_usuario_ocorrencia: $id_usuario_ocorrencia | data: $data | id_ocorrencia_tipo: $id_ocorrencia_tipo | classificacao: $classificacao | comentario: $comentario");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=ocorrencia-busca");
    exit;

}

function excluir($id){
    $dados = array(
		'status' => '2',
	);
	DBUpdate('', 'tb_ocorrencia', $dados, "id_ocorrencia = '$id'");
	registraLog('Exclusão de ocorrência.', 'e', 'tb_ocorrencia', $id, '');
	$alert = ('Item excluído com sucesso!', 's');
	header("location: /api/iframe?token=$request->token&view=ocorrencia-busca");
	exit;
}

?>
