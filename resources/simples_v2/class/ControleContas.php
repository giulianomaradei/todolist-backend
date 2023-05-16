<?php
require_once(__DIR__."/System.php");

$caixa_saida_transferencia = (!empty($_POST['caixa_saida_transferencia'])) ? $_POST['caixa_saida_transferencia'] : '';
$valor_transferencia = (!empty($_POST['valor_transferencia'])) ? $_POST['valor_transferencia'] : '';
$select_caixa_a_transferir = (!empty($_POST['select_caixa_a_transferir'])) ? $_POST['select_caixa_a_transferir'] : '';
$data_movimentacao_transferencia = (!empty($_POST['data_movimentacao_transferencia'])) ? $_POST['data_movimentacao_transferencia'] : '';
$select_caixa_a_transferir_natureza_financeira  = (!empty($_POST['select_caixa_a_transferir_natureza_financeira'])) ? $_POST['select_caixa_a_transferir_natureza_financeira'] : '';

if(isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}if(isset($_GET['excluir_transferencia'])) {

    $id = (int)$_GET['excluir_transferencia'];
    excluir_transferencia($id);

}else if(!empty($_POST['operacao_transferencia'])){

	transferencia($caixa_saida_transferencia, $valor_transferencia, $select_caixa_a_transferir, $data_movimentacao_transferencia, $select_caixa_a_transferir_natureza_financeira);

}else{

    header("location: ../adm.php");
    exit;

}

function excluir($id){

    $link = DBConnect('');
    DBBegin($link);
    
    $dados_caixa_movimentacao = DBReadTransaction($link, 'tb_caixa_movimentacao', "WHERE id_caixa_movimentacao = '".$id."' ");
    
    $id_caixa_movimentacao = $dados_caixa_movimentacao[0]['id_caixa_movimentacao'];
    $valor = $dados_caixa_movimentacao[0]['valor'];
    $origem = $dados_caixa_movimentacao[0]['origem'];
    $ancora_caixa = $dados_caixa_movimentacao[0]['id_caixa'];

	//Atualizar conta_pagar, conta_recebr
	    if($origem == 'conta_receber'){
	    	$situacao = 'aberta';
	    	$data_pagamento = NULL;
	    	$id_caixa_movimentacao = NULL;

	    	$dados_atualizacao_conta = array(
		        'situacao' => 'aberta',
		        'data_pagamento' => NULL,
		        'id_caixa_movimentacao' => NULL
		    );
		    DBUpdateTransaction($link, 'tb_conta_receber', $dados_atualizacao_conta, "id_caixa_movimentacao = $id");


	    }else if($origem == 'conta_pagar'){
	    	$dados_atualizacao_conta = array(
		        'situacao' => 'aberta',
		        'data_pagamento' => NULL,
		        'id_caixa_movimentacao' => NULL
		    );
		    DBUpdateTransaction($link, 'tb_conta_pagar', $dados_atualizacao_conta, "id_caixa_movimentacao = $id");

	    }else{
	    	$dados_conta = DBReadTransaction($link, 'tb_caixa_movimentacao', "WHERE id_caixa_movimentacao = '".$id."' ");
	    }
	//Atualizar conta_pagar, conta_recebr

	//Deletar movimentação
		DBDeleteTransaction($link, 'tb_caixa_movimentacao',"id_caixa_movimentacao = '$id'");
		RegistraLogTransaction($link, 'Exclusão de caixa movimentação.','e','tb_caixa_movimentacao',$id, '');

    DBCommit($link);
    
    $alert = ('Exclusão realizada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=caixas&caixa=".$ancora_caixa);
    exit;
}

function transferencia($caixa_saida_transferencia, $valor_transferencia, $select_caixa_a_transferir, $data_movimentacao_transferencia, $select_caixa_a_transferir_natureza_financeira){

	$link = DBConnect('');
	DBBegin($link);
    $ancora_caixa = $caixa_saida_transferencia;
    //CAIXA1
        //Inicio tb_caixa_movimentacao

            $tipo = 'saida';
            $valor = converteMoeda($valor_transferencia, 'banco');
            $data_movimentacao = converteData($data_movimentacao_transferencia);
            $origem = 'transferencia';
            $id_caixa_transferencia = $select_caixa_a_transferir;
            $id_usuario = $_SESSION['id_usuario'];
            $id_caixa = $caixa_saida_transferencia;
            $data_cadastro = getDataHora();
            $id_pessoa = '2';
            $id_natureza_financeira = $select_caixa_a_transferir_natureza_financeira;
            $descricao = 'transferencia';
           
            $dados = array(
                'tipo' => $tipo,
                'valor' => $valor,
                'data_movimentacao' => $data_movimentacao,
                'origem' => $origem,
                'id_caixa_transferencia' => $id_caixa_transferencia,
                'id_usuario' => $id_usuario,
                'id_caixa' => $id_caixa,
                'data_cadastro' => $data_cadastro,
                'id_pessoa' => $id_pessoa,
                'id_natureza_financeira' => $id_natureza_financeira
            );

            $insertID = DBCreateTransaction($link, 'tb_caixa_movimentacao', $dados, true);
            registraLogTransaction($link, 'Inserção de caixa movimentação.','i','tb_caixa_movimentacao',$insertID,"tipo: $tipo | valor: $valor | data_movimentacao: $data_movimentacao | origem: $origem | id_caixa_transferencia: $id_caixa_transferencia | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | id_natureza_financeira: $id_natureza_financeira");
        //Fim tb_caixa_movimentacao
       
    //CAIXA1

    //CAIXA2
        //Inicio tb_caixa_movimentacao

            $tipo = 'entrada';
            $valor = converteMoeda($valor_transferencia, 'banco');
            $data_movimentacao = converteData($data_movimentacao_transferencia);
            $origem = 'transferencia';
            $id_caixa_transferencia = $caixa_saida_transferencia;
            $id_usuario = $_SESSION['id_usuario'];
            $id_caixa = $select_caixa_a_transferir;
            $data_cadastro = getDataHora();
            $id_pessoa = '2';
            $id_natureza_financeira = $select_caixa_a_transferir_natureza_financeira;
            $id_transferencia_pai = $insertID;

            $dados = array(
                'tipo' => $tipo,
                'valor' => $valor,
                'data_movimentacao' => $data_movimentacao,
                'origem' => $origem,
                'id_caixa_transferencia' => $id_caixa_transferencia,
                'id_usuario' => $id_usuario,
                'id_caixa' => $id_caixa,
                'data_cadastro' => $data_cadastro,
                'id_pessoa' => $id_pessoa,
                'id_natureza_financeira' => $id_natureza_financeira,
                'id_transferencia_pai' => $id_transferencia_pai
            );

            $insertID2 = DBCreateTransaction($link, 'tb_caixa_movimentacao', $dados, true);
            registraLogTransaction($link, 'Inserção de caixa movimentação.','i','tb_caixa_movimentacao',$insertID2,"tipo: $tipo | valor: $valor | data_movimentacao: $data_movimentacao | origem: $origem | id_caixa_transferencia: $id_caixa_transferencia | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | id_natureza_financeira: $id_natureza_financeira | id_transferencia_pai: $id_transferencia_pai");
        //Fim tb_caixa_movimentacao
       
    //CAIXA2

    DBCommit($link);

    $alert = ('Tranferência realizada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=caixas&caixa=".$ancora_caixa);
    exit;
}

function excluir_transferencia($id){

    $link = DBConnect('');
    DBBegin($link);
    
    
    //MOVIMENTACAO1
    	$dados_caixa_movimentacao = DBReadTransaction($link, 'tb_caixa_movimentacao', "WHERE id_caixa_movimentacao = '".$id."' ");

	    $id_caixa_movimentacao = $dados_caixa_movimentacao[0]['id_caixa_movimentacao'];
	    $valor = $dados_caixa_movimentacao[0]['valor'];
	    $ancora_caixa = $dados_caixa_movimentacao[0]['id_caixa'];

		//Atualizar saldo na conta
		if($dados_caixa_movimentacao[0]['tipo'] == 'saida'){
    		$dados_transferencia_pai = DBReadTransaction($link, 'tb_caixa_movimentacao', "WHERE id_transferencia_pai = '".$dados_caixa_movimentacao[0]['id_caixa_movimentacao']."' ");
			$id_transferencia_pai = $dados_transferencia_pai[0]['id_caixa_movimentacao'];
		}else{
			$id_transferencia_pai = $dados_caixa_movimentacao[0]['id_transferencia_pai'];
		}		
		
		//Deletar movimentação
			DBDeleteTransaction($link, 'tb_caixa_movimentacao',"id_caixa_movimentacao = '$id_caixa_movimentacao'");
			RegistraLogTransaction($link, 'Exclusão de caixa movimentação.','e','tb_caixa_movimentacao',$id_caixa_movimentacao, '');
    //MOVIMENTACAO1

	//MOVIMENTACAO2		

		//Deletar movimentação
			DBDeleteTransaction($link, 'tb_caixa_movimentacao',"id_caixa_movimentacao = '$id_transferencia_pai'");
			RegistraLogTransaction($link, 'Exclusão de caixa movimentação.','e','tb_caixa_movimentacao',$id_transferencia_pai, '');
    //MOVIMENTACAO2

    DBCommit($link);
    
    $alert = ('Exclusão realizada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=caixas&caixa=".$ancora_caixa);
    exit;
}
?>