<?php
require_once(__DIR__."/System.php");
//Inserir
	$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
	$id_natureza_financeira = (!empty($_POST['id_natureza_financeira'])) ? $_POST['id_natureza_financeira'] : '';
	$valor = (!empty($_POST['valor'])) ? converteMoeda($_POST['valor'], 'banco') : '';
	$data_vencimento_inserir = (!empty($_POST['data_vencimento'])) ? converteData($_POST['data_vencimento']) : '';
	$data_emissao = (!empty($_POST['data_emissao'])) ? converteData($_POST['data_emissao']) : '';
	$id_caixa = (!empty($_POST['id_caixa'])) ? $_POST['id_caixa'] : '';
	$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : NULL;

	$nome_centro_custos = (!empty($_POST['nome_centro_custos'])) ? $_POST['nome_centro_custos'] : '';
	$valor_centro_custos = (!empty($_POST['valor_centro_custos'])) ? $_POST['valor_centro_custos'] : '';
    $porcentagem_centro_custos = (!empty($_POST['porcentagem_centro_custos'])) ? $_POST['porcentagem_centro_custos'] : '';
    
	$dados_centro_custos_formulario = array();
//Inserir

//ControleContas
	$selecionar = (!empty($_POST['selecionar_conta_pagar'])) ? $_POST['selecionar_conta_pagar'] : '';
	$data_vencimento = (!empty($_POST['data_vencimento'])) ? $_POST['data_vencimento'] : '';
	$id_conta_pagar = (!empty($_POST['id_conta_pagar'])) ? $_POST['id_conta_pagar'] : '';
    $data_pagamento = (!empty($_POST['data_pagamento_hidden_conta_pagar'])) ? $_POST['data_pagamento_hidden_conta_pagar'] : '';
//ControleContas

//Alterar
    $numero_parcela = (!empty($_POST['numero_parcela'])) ? $_POST['numero_parcela'] : '';
    $id_conta_pai = (!empty($_POST['id_conta_pai'])) ? $_POST['id_conta_pai'] : '';
//Alterar



if(!empty($_POST['inserir'])) {
    //inicio variavel para armazenar todo o centro de custos
    $cont = 0;
	foreach ($nome_centro_custos as $conteudo_nome_centro_custos) {
		$dados_centro_custos_formulario[$cont]['nome_centro_custos'] = $conteudo_nome_centro_custos;
		$cont++;
	}

	$cont = 0;
	foreach ($valor_centro_custos as $conteudo_valor_centro_custos) {
		$dados_centro_custos_formulario[$cont]['valor_centro_custos'] = $conteudo_valor_centro_custos;
		$cont++;
	}

	$cont = 0;
	foreach ($porcentagem_centro_custos as $conteudo_porcentagem_centro_custos) {
		$dados_centro_custos_formulario[$cont]['porcentagem_centro_custos'] = $conteudo_porcentagem_centro_custos;
		$cont++;
	}
	//fim

    inserir($id_natureza_financeira, $id_pessoa, $data_emissao, $data_vencimento_inserir, $valor, $id_caixa, $descricao, $dados_centro_custos_formulario);
   
}else if(!empty($_POST['alterar'])) {

    $id_conta_pagar = (int)$_POST['alterar'];

    $cont = 0;
    foreach ($nome_centro_custos as $conteudo_nome_centro_custos) {
        $dados_centro_custos_formulario[$cont]['nome_centro_custos'] = $conteudo_nome_centro_custos;
        $cont++;
    }

    $cont = 0;
    foreach ($valor_centro_custos as $conteudo_valor_centro_custos) {
        $dados_centro_custos_formulario[$cont]['valor_centro_custos'] = $conteudo_valor_centro_custos;
        $cont++;
    }

    $cont = 0;
    foreach ($porcentagem_centro_custos as $conteudo_porcentagem_centro_custos) {
        $dados_centro_custos_formulario[$cont]['porcentagem_centro_custos'] = $conteudo_porcentagem_centro_custos;
        $cont++;
    }
    
    alterar($id_natureza_financeira, $id_pessoa, $data_emissao, $data_vencimento_inserir, $valor, $id_caixa, $descricao, $dados_centro_custos_formulario, $id_conta_pagar, $numero_parcela, $id_conta_pai);
   
}else if(!empty($_POST['baixar_conta_pagar'])){

    $descricao_baixar = (!empty($_POST['descricao_baixar_hidden_conta_pagar'])) ? $_POST['descricao_baixar_hidden_conta_pagar'] : '';
    baixar_conta_pagar($selecionar, $descricao_baixar);
}else if(!empty($_POST['clonar_conta_pagar'])){
    clonar_conta_pagar($id_conta_pagar, $data_vencimento);
}else if(!empty($_POST['quitar_conta_pagar'])){
    quitar_conta_pagar($selecionar, $data_pagamento);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_natureza_financeira, $id_pessoa, $data_emissao, $data_vencimento, $valor, $id_caixa, $descricao, $dados_centro_custos_formulario){
	
	$link = DBConnect('');
    DBBegin($link);
    
    $situacao = 'aberta';
	$id_usuario = $_SESSION['id_usuario'];
	$numero_parcela = '1';
	$data_cadastro = getDataHora();

	$dados = array(
	    'id_natureza_financeira' => $id_natureza_financeira,
	    'id_pessoa' => $id_pessoa,
	    'data_emissao' =>  $data_emissao,
	    'data_vencimento' =>  $data_vencimento,
	    'valor' =>  $valor,
	    'descricao' =>  $descricao,
	    'situacao' =>  $situacao,
	    'id_usuario' =>  $id_usuario,
	    'numero_parcela' =>  $numero_parcela,
	    'id_caixa' =>  $id_caixa,
	    'data_cadastro' =>  $data_cadastro
	);

	$insertID = DBCreateTransaction($link, 'tb_conta_pagar', $dados, true);
	registraLogTransaction($link, 'Inserção de nova conta a pagar.','i','tb_conta_pagar',$insertID,"id_natureza_financeira: $id_natureza_financeira | id_pessoa: $id_pessoa | data_emissao: $data_emissao | data_vencimento: $data_vencimento | valor: $valor | descricao: $descricao | situacao: $situacao | id_usuario: $id_usuario | numero_parcela: $numero_parcela | id_caixa: $id_caixa | data_cadastro: $data_cadastro");

	foreach ($dados_centro_custos_formulario as $conteudo_centro_custos_formulario) {
		if($conteudo_centro_custos_formulario['valor_centro_custos'] != '0,00'){
			echo "nome_centro_custos: ".$conteudo_centro_custos_formulario['nome_centro_custos']."<br>valor_centro_custos: ".$conteudo_centro_custos_formulario['valor_centro_custos']."<br>porcentagem_centro_custos: ".$conteudo_centro_custos_formulario['porcentagem_centro_custos']."<hr>";
                
            $dados_centro_custos = DBReadTransaction($link, 'tb_centro_custos', "WHERE nome = '".$conteudo_centro_custos_formulario['nome_centro_custos']."' ");

			$id_conta_pagar = $insertID;
		    $id_centro_custos = $dados_centro_custos[0]['id_centro_custos'];
		    $porcentagem =  $conteudo_centro_custos_formulario['porcentagem_centro_custos'];
		    $valor =  converteMoeda($conteudo_centro_custos_formulario['valor_centro_custos'], 'banco');

			$dados = array(
			    'id_conta_pagar' => $id_conta_pagar,
			    'id_centro_custos' => $id_centro_custos,
			    'porcentagem' =>  $porcentagem,
			    'valor' =>  $valor
			);

			$insertID_centro_custos = DBCreateTransaction($link, 'tb_conta_pagar_centro_custos', $dados, true);
			registraLogTransaction($link, 'Inserção de nova conta a pagar no centro de custos.','i','tb_conta_pagar_centro_custos',$insertID_centro_custos,"id_conta_pagar: $id_conta_pagar | id_centro_custos: $id_centro_custos | porcentagem: $porcentagem | valor: $valor");
		}

	}
    
    DBCommit($link);
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_pagar");
    exit;
}

function baixar_conta_pagar($selecionar, $descricao_baixar){
    
    foreach ($selecionar as $conteudo_conta) {
        
        $link = DBConnect('');
        DBBegin($link);

        $dados_conta_pagar = DBReadTransaction($link, 'tb_conta_pagar', "WHERE id_conta_pagar = '".$conteudo_conta."' ");
        
        if($dados_conta_pagar[0]['situacao'] == 'aberta'){
            $dados = array(
                'situacao' => 'baixada'
            );
            DBUpdateTransaction($link, 'tb_conta_pagar', $dados, "id_conta_pagar = $conteudo_conta");
            registraLogTransaction($link, 'Baixa conta pagar.','a','tb_conta_pagar',$conteudo_conta,"situacao: baixada");

            $id_usuario = $_SESSION['id_usuario'];
            $data_hora = getDataHora();

            $justificativa = $descricao_baixar;

            $dados = array(
                'id_conta' => $conteudo_conta,
                'tipo' => 'conta_pagar',
                'id_usuario' => $id_usuario,
                'data' => $data_hora,
                'justificativa' => $justificativa
            );
            $insertID = DBCreateTransaction($link, 'tb_conta_baixa', $dados, true);
            registraLogTransaction($link, 'Inserção de conta de baixa.','i','tb_conta_baixa',$insertID,"id_conta: $conteudo_conta | tipo: conta_pagar | id_usuario: $id_usuario | data: $data_hora | justificativa: $justificativa");
        }
        DBCommit($link);
    }
    
    $alert = ('Baixa inserida com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_pagar");
    exit;
}

function clonar_conta_pagar($id_conta_pagar, $data_vencimento){
	
	$link = DBConnect('');
    DBBegin($link);
    
    $dados_conta_pagar = DBReadTransaction($link, 'tb_conta_pagar', "WHERE id_conta_pagar = '".$id_conta_pagar."' ");
    $dados_conta_pagar_centro_custos = DBReadTransaction($link, 'tb_conta_pagar_centro_custos', "WHERE id_conta_pagar = '".$id_conta_pagar."' ");

    foreach ($data_vencimento as $conteudo_data_vencimento) {
    
        $id_natureza_financeira = $dados_conta_pagar[0]['id_natureza_financeira'];
        $id_pessoa = $dados_conta_pagar[0]['id_pessoa'];
        $data_emissao = getDataHora('data');
        $data_vencimento = converteData($conteudo_data_vencimento);
        $valor = $dados_conta_pagar[0]['valor'];
        $descricao = $dados_conta_pagar[0]['descricao'];
        $situacao = 'aberta';
        $id_usuario = $_SESSION['id_usuario'];
        $numero_parcela = '1';
        $id_conta_pai = $id_conta_pagar;
        $id_caixa = $dados_conta_pagar[0]['id_caixa'];
        $data_cadastro = getDataHora();
       
        $dados = array(
            "id_natureza_financeira" => $id_natureza_financeira,
            "id_pessoa" => $id_pessoa,
            "data_emissao" => $data_emissao,
            "data_vencimento" => $data_vencimento,
            "valor" => $valor,
            "descricao" => $descricao,
            "situacao" => $situacao,
            "id_usuario" => $id_usuario,
            "numero_parcela" => $numero_parcela,
            "id_conta_pai" => $id_conta_pai,
            "id_caixa" => $id_caixa,
            "data_cadastro" => $data_cadastro
        );

        $insertID = DBCreateTransaction($link, 'tb_conta_pagar', $dados, true);
        registraLogTransaction($link, 'Inserção clone de conta a pagar.','i','tb_conta_pagar',$insertID,"id_natureza_financeira: $id_natureza_financeira | id_pessoa: $id_pessoa | data_emissao: $data_emissao | data_vencimento: $data_vencimento | valor: $valor | descricao: $descricao | situacao: $situacao | id_usuario: $id_usuario | numero_parcela: $numero_parcela | id_conta_pai: $id_conta_pai | id_caixa: $id_caixa | data_cadastro: $data_cadastro");
 
        foreach ($dados_conta_pagar_centro_custos as $conteudo_conta_pagar_centro_custos) {

            $id_conta_pagar_centro_custos = $insertID;
            $id_centro_custos = $conteudo_conta_pagar_centro_custos['id_centro_custos'];
            $porcentagem = $conteudo_conta_pagar_centro_custos['porcentagem'];
            $valor = $conteudo_conta_pagar_centro_custos['valor'];
           
            $dados = array(
                "id_conta_pagar" => $id_conta_pagar_centro_custos,
                "id_centro_custos" => $id_centro_custos,
                "porcentagem" => $porcentagem,
                "valor" => $valor
            );

            $insertID_centro_custos = DBCreateTransaction($link, 'tb_conta_pagar_centro_custos', $dados, true);
            registraLogTransaction($link, 'Inserção clone de conta a pagar centro de custos.','i','tb_conta_pagar_centro_custos',$insertID,"id_conta_pagar: $id_conta_pagar | id_centro_custos: $id_centro_custos | porcentagem: $porcentagem | valor: $valor");
        }
    }

    DBCommit($link);
    
    $alert = ('Conta a pagar clonada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_pagar");
    exit;
}

function quitar_conta_pagar($selecionar, $data_pagamento){
    
    foreach ($selecionar as $conteudo_conta) {
        
        $link = DBConnect('');
        DBBegin($link);
            
        $dados_conta = DBReadTransaction($link, 'tb_conta_pagar', "WHERE id_conta_pagar = '".$conteudo_conta."' ");
        
        if($dados_conta[0]['situacao'] == 'aberta'){
            //Inicio tb_caixa_movimentacao

                $tipo = 'saida';
                $valor = $dados_conta[0]['valor'];
                $data_movimentacao = converteData($data_pagamento);
                $origem = 'conta_pagar';
                $id_usuario = $_SESSION['id_usuario'];
                $id_caixa = $dados_conta[0]['id_caixa'];
                $data_cadastro = getDataHora();
                $id_pessoa = $dados_conta[0]['id_pessoa'];
                $id_natureza_financeira = $dados_conta[0]['id_natureza_financeira'];
                $descricao = $dados_conta[0]['descricao'];
               
                $dados = array(
                    'tipo' => $tipo,
                    'valor' => $valor,
                    'data_movimentacao' => $data_movimentacao,
                    'origem' => $origem,
                    'id_usuario' => $id_usuario,
                    'id_caixa' => $id_caixa,
                    'data_cadastro' => $data_cadastro,
                    'id_pessoa' => $id_pessoa,
                    'id_natureza_financeira' => $id_natureza_financeira,
                    'descricao' => $descricao
                );

                $insertID = DBCreateTransaction($link, 'tb_caixa_movimentacao', $dados, true);
                registraLogTransaction($link, 'Inserção de caixa movimentação.','i','tb_caixa_movimentacao',$insertID,"tipo: $tipo | valor: $valor | data_movimentacao: $data_movimentacao | origem: $origem | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | id_natureza_financeira: $id_natureza_financeira | descricao: $descricao");
            //Fim tb_caixa_movimentacao
            
            //Inicio tb_conta_pagar

                $data_pagamento_conta_pagar = converteData($data_pagamento);

                $dados = array(
                    'data_pagamento' => $data_pagamento_conta_pagar,
                    'situacao' => 'quitada',
                    'id_caixa_movimentacao' => $insertID
                );
                DBUpdateTransaction($link, 'tb_conta_pagar', $dados, "id_conta_pagar = $conteudo_conta");
                registraLogTransaction($link, 'Quitar conta pagar.','a','tb_conta_pagar',$conteudo_conta,"data_pagamento: $data_pagamento_conta_pagar | situacao: quitada | id_caixa_movimentacao: $insertID");
            //Fim tb_conta_pagar
        }
        DBCommit($link);
    }

    $alert = ('Quitação realizada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_pagar");
    exit;
}

function alterar($id_natureza_financeira, $id_pessoa, $data_emissao, $data_vencimento, $valor, $id_caixa, $descricao, $dados_centro_custos_formulario, $id_conta_pagar, $numero_parcela, $id_conta_pai){
    
    if(!$id_conta_pai || $id_conta_pai == ''){
        $id_conta_pai = NULL;
    }

    /*
        echo "id_conta_pagar :".$id_conta_pagar."<br>";
        echo "id_pessoa :".$id_pessoa."<br>";
        echo "id_natureza_financeira :".$id_natureza_financeira."<br>";
        echo "valor :".$valor."<br>";
        echo "data_vencimento :".$data_vencimento."<br>";
        echo "data_emissao :".$data_emissao."<br>";
        echo "id_caixa :".$id_caixa."<br>";
        echo "descricao :".$descricao."<br>";
    */

    $link = DBConnect('');
    DBBegin($link);
    
    $situacao = 'aberta';
    $id_usuario = $_SESSION['id_usuario'];
    $data_cadastro = getDataHora();

    $dados = array(
        'id_natureza_financeira' => $id_natureza_financeira,
        'id_pessoa' => $id_pessoa,
        'data_emissao' =>  $data_emissao,
        'data_vencimento' =>  $data_vencimento,
        'valor' =>  $valor,
        'descricao' =>  $descricao,
        'situacao' =>  $situacao,
        'id_usuario' =>  $id_usuario,
        'numero_parcela' =>  $numero_parcela,
        'id_conta_pai' =>  $id_conta_pai,
        'id_caixa' =>  $id_caixa,
        'data_cadastro' =>  $data_cadastro
    );

    DBUpdateTransaction($link, 'tb_conta_pagar', $dados, "id_conta_pagar = $id_conta_pagar");
    registraLogTransaction($link, 'Baixa conta pagar.','a','tb_conta_pagar',$id_conta_pagar,"id_natureza_financeira: $id_natureza_financeira | id_pessoa: $id_pessoa | data_emissao: $data_emissao | data_vencimento: $data_vencimento | valor: $valor | descricao: $descricao | situacao: $situacao | id_usuario: $id_usuario | numero_parcela: $numero_parcela | id_conta_pai: $id_conta_pai | id_caixa: $id_caixa | data_cadastro: $data_cadastro");

    DBDeleteTransaction($link, 'tb_conta_pagar_centro_custos', "id_conta_pagar = '".$id_conta_pagar."' ");
    registraLogTransaction($link,'Exclusão de conta pagar centro custos.','e','tb_conta_pagar_centro_custos',$id_conta_pagar,"id_conta_pagar: $id_conta_pagar");

    foreach ($dados_centro_custos_formulario as $conteudo_centro_custos_formulario) {
        if($conteudo_centro_custos_formulario['valor_centro_custos'] != '0,00'){
                
            $dados_centro_custos = DBReadTransaction($link, 'tb_centro_custos', "WHERE nome = '".$conteudo_centro_custos_formulario['nome_centro_custos']."' ");

            $id_centro_custos = $dados_centro_custos[0]['id_centro_custos'];
            $porcentagem =  $conteudo_centro_custos_formulario['porcentagem_centro_custos'];
            $valor =  converteMoeda($conteudo_centro_custos_formulario['valor_centro_custos'], 'banco');

            $dados = array(
                'id_conta_pagar' => $id_conta_pagar,
                'id_centro_custos' => $id_centro_custos,
                'porcentagem' =>  $porcentagem,
                'valor' =>  $valor
            );

            $insertID_centro_custos = DBCreateTransaction($link, 'tb_conta_pagar_centro_custos', $dados, true);
            registraLogTransaction($link, 'Inserção de nova conta a pagar no centro de custos.','i','tb_conta_pagar_centro_custos',$insertID_centro_custos,"id_conta_pagar: $id_conta_pagar | id_centro_custos: $id_centro_custos | porcentagem: $porcentagem | valor: $valor");
        }
    }
    
    DBCommit($link);
    
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_pagar");
    exit;
}

?>