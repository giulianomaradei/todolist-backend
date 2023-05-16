<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$telefone = (!empty($_POST['telefone'])) ? preg_replace("/[^0-9]/", "", $_POST['telefone']) : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_pesquisa = (!empty($_POST['id_pesquisa'])) ? $_POST['id_pesquisa'] : '';

$dado1 = (!empty($_POST['dado_contato1'])) ? $_POST['dado_contato1'] : '';
$dado2 = (!empty($_POST['dado_contato2'])) ? $_POST['dado_contato2'] : '';
$dado3 = (!empty($_POST['dado_contato3'])) ? $_POST['dado_contato3'] : '';
$data_inclusao = getDataHora();
$operacao = (!empty($_POST['operacao'])) ? $_POST['operacao'] : '';

$dados_pesquisa = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = '$id_pesquisa'");
$label1 = $dados_pesquisa[0]['dado1'];
$label2 = $dados_pesquisa[0]['dado2'];
$label3 = $dados_pesquisa[0]['dado3'];

if(!empty($_POST['inserir'])){
   
    if($nome != "" && $telefone != ""){
        inserir($nome, $telefone, $observacao, $data_inclusao, $id_pesquisa, $label1, $label2, $label3, $dado1, $dado2, $dado3);
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];

    if($nome != "" && $telefone != ""){
        alterar($id, $nome, $telefone, $observacao, $id_pesquisa, $label1, $label2, $label3, $dado1, $dado2, $dado3);
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&alterar=$id");
        exit;
    }
    

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    $pesquisa = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$id."'");
    $id_pesquisa = $pesquisa[0]['id_pesquisa'];
    excluir($id, $id_pesquisa);

}else if(isset($_GET['excluir_contatos'])){

    $id_pesquisa = (int)$_GET['excluir_contatos'];   
    $dados = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$id_pesquisa."' AND status_pesquisa = 0 AND qtd_tentativas_cliente = 0");
    if($dados){
        excluir_contatos($id_pesquisa);
    }else{
        $alert = ('Não existem contatos sem ações!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form");
        exit; 
    }

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $telefone, $observacao, $data_inclusao, $id_pesquisa, $label1, $label2, $label3, $dado1, $dado2, $dado3){

    $status_pesquisa = 0;
    $dados = array(
        'nome' => $nome,
        'telefone' => $telefone,
        'observacao' => $observacao,
        'data_inclusao' => $data_inclusao,
        'status_pesquisa' =>$status_pesquisa,
        'id_pesquisa' => $id_pesquisa,
        'qtd_tentativas_cliente' => 0,
        'label1' => $label1,
        'label2' => $label2,
        'label3' => $label3,
        'dado1' => $dado1,
        'dado2' => $dado2,
        'dado3' => $dado3
    );
    $insertID = DBCreate('', 'tb_contatos_pesquisa', $dados, true);
    registraLog('Inserção de pesquisa.','i','tb_pesquisa',$insertID,"nome: $nome | telefone: $telefone | observacao: $observacao | data_inclusao: $data_inclusao | id_pesquisa: $id_pesquisa | qtd_tentativas_cliente: 0 | label1: $label1 | label2: $label2 | label3: $label3 | dado1: $dado1 | dado2: $dado2 | dado3: $dado3");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id_pesquisa");
    exit;

}

function alterar($id, $nome, $telefone, $observacao, $id_pesquisa, $label1, $label2, $label3, $dado1, $dado2, $dado3){

    $dados = array(
        'nome' => $nome,
        'telefone' => $telefone,
        'observacao' => $observacao,
        'id_pesquisa' => $id_pesquisa,
        'label1' => $label1,
        'label2' => $label2,
        'label3' => $label3,
        'dado1' => $dado1,
        'dado2' => $dado2,
        'dado3' => $dado3
    );
    DBUpdate('', 'tb_contatos_pesquisa', $dados, "id_contatos_pesquisa = $id");
    registraLog('Alteração de tipo de vínculo.','a','tb_pesquisa',$id,"nome: $nome | telefone: $telefone | observacao: $observacao | id_pesquisa: $id_pesquisa | label1: $label1 | label2: $label2 | label3: $label3 | dado1: $dado1 | dado2: $dado2 | dado3: $dado3");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id_pesquisa");
    exit;

}

function excluir($id, $id_pesquisa){
    $query = "DELETE FROM tb_contatos_pesquisa WHERE id_contatos_pesquisa = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de contato pesquisa.','e','tb_contatos_pesquisa',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id_pesquisa");
    exit;
}

function excluir_contatos($id_pesquisa){
    $dados = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$id_pesquisa."' AND status_pesquisa = 0 AND qtd_tentativas_cliente = 0");

    foreach ($dados as $conteudo) {
        $query = "DELETE FROM tb_contatos_pesquisa WHERE id_contatos_pesquisa = '".$conteudo['id_contatos_pesquisa']."'";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de contato pesquisa.','e','tb_contatos_pesquisa', $conteudo['id_contatos_pesquisa'],'');
    }
    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id_pesquisa");
    exit;
}

?>
