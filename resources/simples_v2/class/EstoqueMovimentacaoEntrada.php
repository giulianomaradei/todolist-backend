<?php
require_once(__DIR__."/System.php");

$id_estoque_item = (!empty($_POST['id_estoque_item'])) ? $_POST['id_estoque_item'] : '';
$tipo_movimentacao = (!empty($_POST['tipo_movimentacao'])) ? $_POST['tipo_movimentacao'] : '';
$quantidade = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : '';

$item_nome = (!empty($_POST['item_nome'])) ? $_POST['item_nome'] : '';
$item_id = (!empty($_POST['item_id'])) ? $_POST['item_id'] : '';
$item_valor_unitario = (!empty($_POST['item_valor_unitario'])) ? $_POST['item_valor_unitario'] : '';
$item_quantidade = (!empty($_POST['item_quantidade'])) ? $_POST['item_quantidade'] : '';
$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '0';
$item_observacao = (!empty($_POST['item_observacao'])) ? $_POST['item_observacao'] : '';
$item_tipo_entrada = (!empty($_POST['item_tipo_entrada'])) ? $_POST['item_tipo_entrada'] : '1';


if (!empty($_POST['inserir'])) {    
    $dados_item = array();

    if($item_nome){
        foreach ($item_nome as $key => $item) {
            $dados_item[$key]['item'] = $item;
            $dados_item[$key]['item_id'] = $item_id[$key];
            $dados_item[$key]['item_valor_unitario'] = converteMoeda($item_valor_unitario[$key], 'banco');
            $dados_item[$key]['item_quantidade'] = $item_quantidade[$key];
            $dados_item[$key]['id_pessoa'] = $id_pessoa[$key];
            $dados_item[$key]['item_observacao'] = $item_observacao[$key];
            $dados_item[$key]['item_tipo_entrada'] = $item_tipo_entrada[$key];
        }
    }

    inserir($dados_item);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

} else{
    header("location: ../adm.php");
    exit;
}

function inserir($dados_item){

    $id_usuario = $_SESSION['id_usuario'];
    $data = getDataHora();

    $dados_estoque_movimentacao = array(        
        'id_usuario' => $id_usuario,
        'data' => $data
    );
    $insertIDEstoqueMovimentacao = DBCreate('', 'tb_estoque_movimentacao', $dados_estoque_movimentacao, true);
    registraLog('Inserção de novo movimentacao no estoque.','i','tb_estoque_movimentacao',$insertIDEstoqueMovimentacao,"id_usuario: $id_usuario | data: $data");
    
    $cont = 0;
    foreach($dados_item as $conteudo_item){
        $cont++;
        $id_estoque_item = $conteudo_item['item_id'];
        $tipo_movimentacao = 'entrada';
        $quantidade = $conteudo_item['item_quantidade'];   
        $valor_unitario = $conteudo_item['item_valor_unitario'];
        $id_pessoa = $conteudo_item['id_pessoa'];
        $observacao = $conteudo_item['item_observacao'];
        if($conteudo_item['item_tipo_entrada'] == 'Compra'){
            $tipo_entrada = 1;
        }else{
            $tipo_entrada = 2;
        }

        if($id_pessoa){
            $dados_estoque_movimentacao_item = array(        
                'id_estoque_item' => $id_estoque_item,
                'id_estoque_movimentacao' => $insertIDEstoqueMovimentacao,
                'quantidade' => $quantidade,
                'tipo_movimentacao' => $tipo_movimentacao,
                'valor_unitario' => $valor_unitario,
                'id_fornecedor' => $id_pessoa,
                'observacao' => $observacao,
                'status' => '1',
                'tipo' => $tipo_entrada
            );
            $insertIDEstoqueMovimentacaoItem = DBCreate('', 'tb_estoque_movimentacao_item', $dados_estoque_movimentacao_item, true);
            registraLog('Inserção de novo movimentacao no estoque.','i','tb_estoque_movimentacao_item',$insertIDEstoqueMovimentacaoItem,"id_estoque_item: $id_estoque_item | id_estoque_movimentacao: $insertIDEstoqueMovimentacao | quantidade: $quantidade | tipo_movimentacao: $tipo_movimentacao | valor_unitario: $valor_unitario | id_fornecedor: $id_pessoa | observacao: $observacao | status: 1 | tipo: $tipo_entrada");
        
        }else{
            $dados_estoque_movimentacao_item = array(        
                'id_estoque_item' => $id_estoque_item,
                'id_estoque_movimentacao' => $insertIDEstoqueMovimentacao,
                'quantidade' => $quantidade,
                'tipo_movimentacao' => $tipo_movimentacao,
                'valor_unitario' => $valor_unitario,
                'observacao' => $observacao,
                'status' => '1',
                'tipo' => $tipo_entrada
            );
            $insertIDEstoqueMovimentacaoItem = DBCreate('', 'tb_estoque_movimentacao_item', $dados_estoque_movimentacao_item, true);
            registraLog('Inserção de novo movimentacao no estoque.','i','tb_estoque_movimentacao_item',$insertIDEstoqueMovimentacaoItem,"id_estoque_item: $id_estoque_item | id_estoque_movimentacao: $insertIDEstoqueMovimentacao | quantidade: $quantidade | tipo_movimentacao: $tipo_movimentacao | valor_unitario: $valor_unitario | observacao: $observacao | status: 1 | tipo: $tipo_entrada");
        }
        
        $dados = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id_estoque_item."' ");

        $quantidade_total = (int)$dados[0]['quantidade'] + (int)$quantidade;
        
        if($conteudo_item['item_tipo_entrada'] == 'Compra' || $valor_unitario > '0.00' ){
            $dados_estoque_item = array(        
                'quantidade' => $quantidade_total,
                'valor_unitario' => $valor_unitario
            ); 
            DBUpdate('', 'tb_estoque_item', $dados_estoque_item, "id_estoque_item = $id_estoque_item");
            registraLog('Alteração de item no estoque.','a','tb_estoque_item',$id_estoque_item,"quantidade: $quantidade_total || valor_unitario: $valor_unitario");
        }else{
            $dados_estoque_item = array(        
                'quantidade' => $quantidade_total
            ); 
            DBUpdate('', 'tb_estoque_item', $dados_estoque_item, "id_estoque_item = $id_estoque_item");
            registraLog('Alteração de item no estoque.','a','tb_estoque_item',$id_estoque_item,"quantidade: $quantidade_total");
        }        
    }
    if($cont == 1){
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    }else{
        $alert = ('Itens inseridos com sucesso!','s');
    }
    header("location: /api/iframe?token=$request->token&view=estoque-movimentacao-entrada-busca");    
    exit;
}

function excluir($id_estoque_movimentacao_item){

    $dados_estoque_movimentacao_item = DBRead('', 'tb_estoque_movimentacao_item', "WHERE id_estoque_movimentacao_item = '".$id_estoque_movimentacao_item."' ");
    
    $id_estoque_movimentacao = $dados_estoque_movimentacao_item[0]['id_estoque_movimentacao'];
    $id_estoque_item = $dados_estoque_movimentacao_item[0]['id_estoque_item'];
    $quantidade_movimentacao = $dados_estoque_movimentacao_item[0]['quantidade'];

    $dados_estoque_item = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id_estoque_item."' ");

    $quantidade_estoque_item = $dados_estoque_item[0]['quantidade'];

    $quantidade_total = $quantidade_estoque_item - $quantidade_movimentacao;
    
    $dados = array(        
        'quantidade' => $quantidade_total
    ); 
    DBUpdate('', 'tb_estoque_item', $dados, "id_estoque_item = $id_estoque_item");
    registraLog('Exclusão de movimentação no estoque.','a','tb_estoque_item',$id_estoque_item,"quantidade: $quantidade_total");

    $dados_status = array(        
        'status' => 2
    ); 
    DBUpdate('', 'tb_estoque_movimentacao_item', $dados_status, "id_estoque_movimentacao_item = $id_estoque_movimentacao_item");
    registraLog('Exclusão de movimentação no estoque.','a','tb_estoque_movimentacao_item',$id_estoque_movimentacao_item,"status: 2");

    // $dados_estoque_movimentacao = DBRead('', 'tb_estoque_movimentacao_item', "WHERE id_estoque_movimentacao = '".$id_estoque_movimentacao."' ");
    // if(sizeof($dados_estoque_movimentacao) < 1){
    //     DBDelete('','tb_estoque_movimentacao',"id_estoque_movimentacao = '$id_estoque_movimentacao'");
    //     registraLog('Exclusão de movimentação no estoque.','e','tb_estoque_movimentacao',$id_estoque_movimentacao,'');
    // }

    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=estoque-movimentacao-entrada-busca");
    exit;
}

?>