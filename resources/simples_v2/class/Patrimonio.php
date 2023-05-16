<?php
require_once(__DIR__."/System.php");


$valor_compra  = (!empty($_POST['valor_compra'])) ? converteMoeda($_POST['valor_compra'], 'banco') : '';
$numero_patrimonio  = (!empty($_POST['numero_patrimonio'])) ? $_POST['numero_patrimonio'] : '';
$id_patrimonio_item  = (!empty($_POST['id_patrimonio_item'])) ? $_POST['id_patrimonio_item'] : '';
$id_patrimonio_localizacao  = (!empty($_POST['id_patrimonio_localizacao'])) ? $_POST['id_patrimonio_localizacao'] : '';
$id_responsavel  = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : NULL;
$id_fornecedor  = (!empty($_POST['id_fornecedor'])) ? $_POST['id_fornecedor'] : NULL;
$data_compra  = (!empty($_POST['data_compra'])) ? converteDataHora($_POST['data_compra'], 'data') : '';
$data_garantia  = (!empty($_POST['data_garantia'])) ? converteDataHora($_POST['data_garantia'], 'data') : NULL;
$status  = (!empty($_POST['status'])) ? $_POST['status'] : '';
$numero_nota_fiscal  = (!empty($_POST['numero_nota_fiscal'])) ? $_POST['numero_nota_fiscal'] : NULL;
$observacao  = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';

if(!empty($_POST['inserir'])){

    inserir($valor_compra, $numero_patrimonio, $id_patrimonio_item, $id_patrimonio_localizacao, $id_responsavel, $id_fornecedor, $data_compra, $data_garantia, $status, $numero_nota_fiscal, $observacao);

}else if(!empty($_POST['alterar'])){
    
    $id = (int)$_POST['alterar'];
    alterar($id, $valor_compra, $numero_patrimonio, $id_patrimonio_item, $id_patrimonio_localizacao, $id_responsavel, $id_fornecedor, $data_compra, $data_garantia, $status, $numero_nota_fiscal, $observacao);

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($valor_compra, $numero_patrimonio, $id_patrimonio_item, $id_patrimonio_localizacao, $id_responsavel, $id_fornecedor, $data_compra, $data_garantia, $status, $numero_nota_fiscal, $observacao){
   
    $data_atualizacao = getDataHora();

    $dados = array(
        'valor_compra' => $valor_compra,
        'numero_patrimonio' => $numero_patrimonio,
        'id_patrimonio_item' => $id_patrimonio_item,
        'id_patrimonio_localizacao' => $id_patrimonio_localizacao,
        'id_responsavel' => $id_responsavel,
        'id_fornecedor' => $id_fornecedor,
        'data_compra' => $data_compra,
        'data_garantia' => $data_garantia,
        'status' => $status,
        'numero_nota_fiscal' => $numero_nota_fiscal,
        'observacao' => $observacao,
        'data_atualizacao' => $data_atualizacao
    );
   
    $insertID = DBCreate('', 'tb_patrimonio', $dados, true);
    registraLog('Inserção de nova localizacao de patrimônio.','i','tb_patrimonio_localizacao',$insertID,"valor_compra: $valor_compra | numero_patrimonio: $numero_patrimonio | id_patrimonio_item: $id_patrimonio_item | id_patrimonio_localizacao: $id_patrimonio_localizacao | id_responsavel: $id_responsavel | id_fornecedor: $id_fornecedor | data_compra: $data_compra | data_garantia: $data_garantia | status: $status | numero_nota_fiscal: $numero_nota_fiscal | observacao: $observacao | data_atualizacao: $data_atualizacao");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-form");
    
    exit;
}

function alterar($id, $valor_compra, $numero_patrimonio, $id_patrimonio_item, $id_patrimonio_localizacao, $id_responsavel, $id_fornecedor, $data_compra, $data_garantia, $status, $numero_nota_fiscal, $observacao){
    
    $data_atualizacao = getDataHora();

    $dados = array(
        'valor_compra' => $valor_compra,
        'numero_patrimonio' => $numero_patrimonio,
        'id_patrimonio_item' => $id_patrimonio_item,
        'id_patrimonio_localizacao' => $id_patrimonio_localizacao,
        'id_responsavel' => $id_responsavel,
        'id_fornecedor' => $id_fornecedor,
        'data_compra' => $data_compra,
        'data_garantia' => $data_garantia,
        'status' => $status,
        'numero_nota_fiscal' => $numero_nota_fiscal,
        'observacao' => $observacao,
        'data_atualizacao' => $data_atualizacao
    );

    DBUpdate('', 'tb_patrimonio', $dados, "id_patrimonio = $id");
    registraLog('Alteração de patrimônio.','a','tb_patrimonio',$id,"valor_compra: $valor_compra | numero_patrimonio: $numero_patrimonio | id_patrimonio_item: $id_patrimonio_item | id_patrimonio_localizacao: $id_patrimonio_localizacao | id_responsavel: $id_responsavel | id_fornecedor: $id_fornecedor | data_compra: $data_compra | data_garantia: $data_garantia | status: $status | numero_nota_fiscal: $numero_nota_fiscal | observacao: $observacao | data_atualizacao: $data_atualizacao");

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-busca");

    exit;
}

function excluir($id){

    $data_atualizacao = getDataHora();

    $dados = array(
        'status' => 6,
        'data_atualizacao' => $data_atualizacao
    );

    DBUpdate('', 'tb_patrimonio', $dados, "id_patrimonio = $id");
    registraLog('Exclusão de patrimônio.','a','tb_patrimonio',$id,"status: 6");
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-busca");

    exit;
}

?>