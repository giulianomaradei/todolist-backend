<?php
require_once(__DIR__."/System.php");

$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$valor_total = (!empty($_POST['valor_total'])) ? $_POST['valor_total'] : '';
$data_vencimento = (!empty($_POST['data_vencimento'])) ? $_POST['data_vencimento'] : '';


$tipo_remessa = (!empty($_POST['tipo_remessa'])) ? $_POST['tipo_remessa'] : '2';

if (isset($_GET['gerar_pdf'])) {

    $id_boleto = $_GET['gerar_pdf'];
    if($id_boleto){
        gerar_pdf($id_boleto);
    }else{
		$alert = ('Não foi possível gerar o boleto! Erro no identificador.','w');
        header("location: /api/iframe?token=$request->token&view=boleto-visualizar&visualizar=$id_boleto'");    
    }

}else if (!empty($_POST['gerar_remessa'])){
	
	if($tipo_remessa == 1){
        $mes_ano = (!empty($_POST['mes_ano_registro'])) ? $_POST['mes_ano_registro'] : '';
		if($mes_ano){
			gerar_remessa_registro($mes_ano);
		}else{
			$alert = ('Não foi possível gerar remessa de '.$mes_ano.'.','w');
        	header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
		}
	}else if($tipo_remessa == 2){
        $mes_ano = (!empty($_POST['mes_ano_baixa'])) ? $_POST['mes_ano_baixa'] : '';
		if($mes_ano){
			gerar_remessa_baixa($mes_ano);
		}else{
			$alert = ('Não foi possível gerar remessa de '.$mes_ano.'.','w');
        	header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
		}
	}else if($tipo_remessa == 3){
        $mes_ano = (!empty($_POST['mes_ano_vencimento'])) ? $_POST['mes_ano_vencimento'] : '';
		if($mes_ano){
			gerar_remessa_vencimento($mes_ano);
		}else{
			$alert = ('Não foi possível gerar remessa de '.$mes_ano.'.','w');
        	header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
		}
	}else{
		$alert = ('Não foi possível gerar remessa, tipo de remessa inválida.','w');
        header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
	}
    
}else if (isset($_GET['visualizar_remessa'])) {

    $id_remessa_bancaria = $_GET['visualizar_remessa'];
	$dados_remessa = DBRead('', 'tb_remessa_bancaria', "WHERE id_remessa_bancaria = '".$id_remessa_bancaria."' ");

    if($dados_remessa[0]['id_remessa_bancaria']){
        visualizar_remessa($id_remessa_bancaria);
    }else{
		$alert = ('Não foi possível fazer o download da remessa! Erro no identificador.','w');
        header("location: /api/iframe?token=$request->token&view=boleto-remessa-busca");    
    }    

}else if (!empty($_POST['retorno'])) {

	importar_retorno();

}else if (!empty($_POST['sincronizar'])){

	sincronizar_boleto();
	
}else{
    header("location: ../adm.php");
    exit;
}

function gerar_pdf($id_boleto){

	$dados = DBRead('', 'tb_boleto',"WHERE id_boleto = '".$id_boleto."' ");
            
    $id_integracao = $dados[0]['id_integracao'];
    $sacado_nome = $dados[0]['sacado_nome'];

	$parametros = '
        {
	        "TipoImpressao" : "99",        
	        "Boletos" :
	        [        
	            "'.$id_integracao.'"        
	        ]        
        }
    ';
	
	$resultado_pdf = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/impressao/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));
	
	if($resultado_pdf['_dados']['protocolo']){

		
		$resultado_processamento_pdf = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/impressao/lote/'.$resultado_pdf['_dados']['protocolo'], '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');

		while($resultado_processamento_pdf['_dados'][0]['situacao'] == 'PROCESSANDO'){
			sleep(5);
			$resultado_processamento_pdf = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/impressao/lote/'.$resultado_pdf['_dados']['protocolo'], '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');			
		}
		
		if($resultado_processamento_pdf['_status'] != 'erro'){
			$resultado_processamento_pdf = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/impressao/lote/'.$resultado_pdf['_dados']['protocolo'], '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET', false);

			header('Cache-Control: public'); 
			header('Content-type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$sacado_nome.' - boleto.pdf"');
			header('Content-Length: '.strlen($resultado_processamento_pdf));

			echo $resultado_processamento_pdf;

		}else{
			$alert = ('Não foi possível gerar o boleto! Erro na API.','d');
        	header("location: /api/iframe?token=$request->token&view=boleto-visualizar&visualizar=$id_boleto'");  
        }
	}
}

function visualizar_remessa($id_remessa_bancaria){
	
	$dados_remessa = DBRead('', 'tb_remessa_bancaria', "WHERE id_remessa_bancaria = '".$id_remessa_bancaria."' ");

  	header('Cache-Control: public'); 
	header('Content-type: text/plain');
	header('Content-Disposition: attachment; filename="'.$dados_remessa[0]['nome_arquivo'].'" ');

	echo base64_decode($dados_remessa[0]['dados']);
}

function gerar_remessa_registro($mes_ano){

	$dados_data = DBRead('', 'tb_boleto', "WHERE titulo_data_vencimento LIKE '%".$mes_ano."%' AND situacao = 'EMITIDO' ");

	$texto_parametros = '';
    foreach ($dados_data as $conteudo_boleto){
    
    	$dados_boleto = DBRead('', 'tb_remessa_bancaria_boleto',"WHERE id_boleto = '".$conteudo_boleto['id_boleto']."' ");
    	if(!$dados_boleto){
    		$texto_parametros = $texto_parametros.'"'.$conteudo_boleto['id_integracao'].'", ';
    	}
    }
    $texto_parametros = substr($texto_parametros, 0, -2);

	$parametros = '
    	['.$texto_parametros.']
    ';	

    $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/remessas/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));

	$certos = array();
    if($resultado['_dados']['_sucesso'][0]['titulos']){
    	foreach ($resultado['_dados']['_sucesso'][0]['titulos'] as $conteudo_resultado){
    		$dados_boleto_certos = DBRead('', 'tb_boleto',"WHERE id_integracao = '".$conteudo_resultado['idintegracao']."' ");
			$certos[$dados_boleto_certos[0]['id_boleto']]['titulo_data_vencimento'] = $dados_boleto_certos[0]['titulo_data_vencimento']; 
			$certos[$dados_boleto_certos[0]['id_boleto']]['titulo_valor'] = $dados_boleto_certos[0]['titulo_valor']; 
	    }
    }
    
    $falhas = '';
    if($resultado['_dados']['_falha']){

    	foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	
    		if(!$conteudo_resultado['idintegracao']){
	    		$falhas = $falhas.'Não registrado, ';
    		}else{
	    		$dados_boleto_falhas = DBRead('', 'tb_boleto',"WHERE id_integracao = '".$conteudo_resultado['idintegracao']."' ");
		    	$falhas = $falhas.'#'.$dados_boleto_falhas[0]['id_boleto'].' ('.$conteudo_resultado['_erro'].'), ';
    		}
	    }
	    $alert = ('Não foi possível gerar a remessa dos boletos: '.substr($falhas, 0, -2).' ', 'd');
        header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
    }else{
    	$alert = ("Remessa gerada com sucesso!", 's');
        header("location: /api/iframe?token=$request->token&view=boleto-remessa-busca");
    }

    if($falhas != ''){
		$alert = ('Não foi possível gerar a remessa do(s) boleto(s) '.$falhas, 'd');
    	header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
    }else{
    	$id_usuario = $_SESSION['id_usuario'];
		$remessa = $resultado['_dados']['_sucesso'][0]['remessa'];
	    $data_gerado = getDataHora('data');

	   	$dados_data = DBRead('', 'tb_remessa_bancaria', "WHERE data_gerado = '".$data_gerado."' ", "COUNT(*) as cont");

	   	$dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");

	   	if($dados_data[0]['cont'] == 0){
	    	$nome_arquivo =  $dados_boleto_configuracao[0]['codigo_beneficiario'].getDataRemessaBoleto($data_gerado).'.CRM';
	   	}else{
	   		$extensao = (int)$dados_data[0]['cont']+1;
	    	$nome_arquivo = $dados_boleto_configuracao[0]['codigo_beneficiario'].getDataRemessaBoleto($data_gerado).'.RM'.$extensao.'';
	    }
	    $dados_remessa_insercao = array(
	        'id_usuario' => $id_usuario,
	        'dados' => $remessa,
	        'data_gerado' => $data_gerado,
	        'nome_arquivo' => $nome_arquivo,
	        'tipo' => 'registro'
	    );

	    $id_remessa = DBCreate('', 'tb_remessa_bancaria', $dados_remessa_insercao, true);
	    registraLog('Inserção de remessa.','i','tb_boleto',$id_remessa,"id_usuario: $id_usuario | dados: $remessa | data_gerado: $data_gerado | nome_arquivo: $nome_arquivo");

	    foreach ($certos as $id_boleto => $conteudo_boleto){
            $titulo_data_vencimento = $conteudo_boleto['titulo_data_vencimento'];
            $titulo_valor = $conteudo_boleto['titulo_valor'];
	    	$dados_remessa_boleto = array(
		        'id_remessa_bancaria' => $id_remessa,
		        'id_boleto' => $id_boleto,
		        'titulo_data_vencimento' => $titulo_data_vencimento,
		        'titulo_valor' => $titulo_valor
		    );
		    
		    $id_remessa_boleto = DBCreate('', 'tb_remessa_bancaria_boleto', $dados_remessa_boleto, true);
	   	 	registraLog('Inserção de remessa.','i','tb_remessa_bancaria_boleto',$id_remessa_boleto,"id_remessa_bancaria: $id_remessa | id_boleto: $id_boleto | titulo_data_vencimento: $titulo_data_vencimento | titulo_valor: $titulo_valor");
	    }
    }
}

function gerar_remessa_baixa($mes_ano){
	
    $dados_data = DBRead('', 'tb_boleto', "WHERE titulo_data_vencimento LIKE '%".$mes_ano."%' AND situacao = 'BAIXA PENDENTE' ");
    
    $texto_parametros = '';
    foreach ($dados_data as $conteudo_boleto){
    
    	$dados_boleto = DBRead('', 'tb_remessa_bancaria_boleto a',"INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE a.id_boleto = '".$conteudo_boleto['id_boleto']."' AND b.tipo = 'baixa' ");
    	if(!$dados_boleto){
    		$texto_parametros = $texto_parametros.'"'.$conteudo_boleto['id_integracao'].'", ';
    	}
    }
    $texto_parametros = substr($texto_parametros, 0, -2);
	$parametros = '
    	['.$texto_parametros.']
    ';	
        
    $resultado_protocolo = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/baixa/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));

	if($resultado_protocolo['_dados']['protocolo']){

		$protocolo = $resultado_protocolo['_dados']['protocolo'];
        sleep(5);
    	$resultado_baixa = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/baixa/lote/'.$protocolo, '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');
  	
	    if($resultado_baixa['_dados']['_sucesso']){

	    	$certos = array();
		    if($resultado_baixa['_dados']['_sucesso'][0]['titulos']){
		    	foreach ($resultado_baixa['_dados']['_sucesso'][0]['titulos'] as $conteudo_resultado){
		    		$dados_boleto_certos = DBRead('', 'tb_boleto',"WHERE id_integracao = '".$conteudo_resultado['idintegracao']."' ");
					$certos[$dados_boleto_certos[0]['id_boleto']]['titulo_data_vencimento'] = $dados_boleto_certos[0]['titulo_data_vencimento']; 
			        $certos[$dados_boleto_certos[0]['id_boleto']]['titulo_valor'] = $dados_boleto_certos[0]['titulo_valor']; 
			    }
		    }

		   	$falhas = '';
		    if($resultado_baixa['_dados']['_falha']){
		    	foreach ($resultado_baixa['_dados']['_falha'] as $conteudo_resultado){	
		    		if(!$conteudo_resultado['idintegracao']){
			    		$falhas = $falhas.'Não registrado, ';
		    		}else{
			    		$dados_boleto_falhas = DBRead('', 'tb_boleto',"WHERE id_integracao = '".$conteudo_resultado['idintegracao']."' ");
		    			$falhas = $falhas.'#'.$dados_boleto_falhas[0]['id_boleto'].' ('.$conteudo_resultado['_erro'].'), ';
		    		}
			    }
		    }

		    if($falhas != ''){
				$alert = ('Não foi possível gerar a remessa de baixa no(s) boleto(s) '.$falhas, 'd');
    			header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
		    }else{
		    	$id_usuario = $_SESSION['id_usuario'];
				$remessa = $resultado_baixa['_dados']['_sucesso'][0]['remessa'];
			    $data_gerado = getDataHora('data');

			   	$dados_data = DBRead('', 'tb_remessa_bancaria', "WHERE data_gerado = '".$data_gerado."' ", "COUNT(*) as cont");

			   	$dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");

			   	if($dados_data[0]['cont'] == 0){
			    	$nome_arquivo =  $dados_boleto_configuracao[0]['codigo_beneficiario'].getDataRemessaBoleto($data_gerado).'.CRM';
			   	}else{
			   		$extensao = (int)$dados_data[0]['cont']+1;
			    	$nome_arquivo = $dados_boleto_configuracao[0]['codigo_beneficiario'].getDataRemessaBoleto($data_gerado).'.RM'.$extensao.'';
			    }
			    $dados_remessa_insercao = array(
			        'id_usuario' => $id_usuario,
			        'dados' => $remessa,
			        'data_gerado' => $data_gerado,
			        'nome_arquivo' => $nome_arquivo,
			        'tipo' => 'baixa'
			    );

			    $id_remessa = DBCreate('', 'tb_remessa_bancaria', $dados_remessa_insercao, true);
			    registraLog('Inserção de remessa.','i','tb_boleto',$id_remessa,"id_usuario: $id_usuario | dados: $remessa | data_gerado: $data_gerado | nome_arquivo: $nome_arquivo");

                foreach ($certos as $id_boleto => $conteudo_boleto){
                    $titulo_data_vencimento = $conteudo_boleto['titulo_data_vencimento'];
                    $titulo_valor = $conteudo_boleto['titulo_valor'];
                    $dados_remessa_boleto = array(
                        'id_remessa_bancaria' => $id_remessa,
                        'id_boleto' => $id_boleto,
                        'titulo_data_vencimento' => $titulo_data_vencimento,
                        'titulo_valor' => $titulo_valor
                    );
                    
                    $id_remessa_boleto = DBCreate('', 'tb_remessa_bancaria_boleto', $dados_remessa_boleto, true);
                    registraLog('Inserção de remessa.','i','tb_remessa_bancaria_boleto',$id_remessa_boleto,"id_remessa_bancaria: $id_remessa | id_boleto: $id_boleto | titulo_data_vencimento: $titulo_data_vencimento | titulo_valor: $titulo_valor");

			   	 	$dados_boleto_remessa_pendente = array(
		                'remessa_pendente' => '0'
		            );
		            DBUpdate('', 'tb_boleto', $dados_boleto_remessa_pendente, "id_boleto = $id_boleto");
			    }

				$alert = ("Remessa de baixa gerada com sucesso!", 's');
		    	header("location: /api/iframe?token=$request->token&view=boleto-remessa-busca");

		    }
    	}else{
    		$alert = ('Não foi possível gerar remessa a baixa do boleto, problema no protocolo.','d');
		    header("location: /api/iframe?token=$request->token&view=boleto-remessa-busca");
    	}

	}else{
    	$alert = ('Não foi possível gerar a remessa de baixa do boleto.','d');
		header("location: /api/iframe?token=$request->token&view=boleto-remessa-busca");
	}
}

function gerar_remessa_vencimento($mes_ano){

	$dados_data = DBRead('', 'tb_boleto', "WHERE titulo_data_vencimento LIKE '%".$mes_ano."%' AND situacao = 'ALTERACAO VENCIMENTO PENDENTE' AND remessa_pendente = '1'");

	$texto_parametros = '';
    foreach ($dados_data as $conteudo_boleto){
    	$texto_parametros = $texto_parametros.'{"IdIntegracao": "'.$conteudo_boleto['id_integracao'].'", "TituloDataVencimento": "'.converteData($conteudo_boleto['titulo_data_vencimento']).'"}, ';
   	}
    $texto_parametros = substr($texto_parametros, 0, -2);

    //gerar remessa - alteração de vencimento
    //pode mandar mais de um boleto separado por vírgula
    $parametros = '
    {
    "Tipo": "0",        
    "Boletos": [        
        '.$texto_parametros.'        
    ]         
    }
    ';

    $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/altera/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));

       
    //fim gerar remessa - alteração de vencimento

	if($resultado['_status'] == 'sucesso'){
		$protocolo = $resultado['_dados']['protocolo'];
    	sleep(5);
    	$resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/altera/lote/'.$protocolo, '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');
    	
        //Certos
        $certos = array();
		foreach ($resultado['_dados']['_sucesso'][0]['titulos'] as $conteudo_resultado){
    		$dados_boleto_certos = DBRead('', 'tb_boleto',"WHERE id_integracao = '".$conteudo_resultado['idintegracao']."' ");
			$certos[$dados_boleto_certos[0]['id_boleto']]['titulo_data_vencimento'] = $dados_boleto_certos[0]['titulo_data_vencimento']; 
			$certos[$dados_boleto_certos[0]['id_boleto']]['titulo_valor'] = $dados_boleto_certos[0]['titulo_valor'];  			
	    }

	    //Falhas
	    $falhas = '';
	    if($resultado['_dados']['_falha']){
	    	foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	
	    		if(!$conteudo_resultado['idintegracao']){
		    		$falhas = $falhas.'Não registrado, ';
	    		}else{
	    			$dados_boleto_falhas = DBRead('', 'tb_boleto',"WHERE id_integracao = '".$conteudo_resultado['idintegracao']."' ");
		    		$falhas = $falhas.'#'.$dados_boleto_falhas[0]['id_boleto'].' ('.$conteudo_resultado['_erro'].'), ';
	    		}
		    }
	    }
    	$falhas = substr($falhas, 0, -2);
    	
    	$id_usuario = $_SESSION['id_usuario'];
		$remessa = $resultado['_dados']['_sucesso'][0]['remessa'];
	    $data_gerado = getDataHora('data');

	   	$dados_data = DBRead('', 'tb_remessa_bancaria', "WHERE data_gerado = '".$data_gerado."' ", "COUNT(*) as cont");

	   	$dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");

	   	if($dados_data[0]['cont'] == 0){
	    	$nome_arquivo =  $dados_boleto_configuracao[0]['codigo_beneficiario'].getDataRemessaBoleto($data_gerado).'.CRM';
	   	}else{
	   		$extensao = (int)$dados_data[0]['cont']+1;
	    	$nome_arquivo = $dados_boleto_configuracao[0]['codigo_beneficiario'].getDataRemessaBoleto($data_gerado).'.RM'.$extensao.'';
	    }
	}

	if($falhas != ''){
		$alert = ('Não foi possível gerar a remessa de alteração no(s) boleto(s) '.$falhas, 'd');
    	header("location: /api/iframe?token=$request->token&view=boleto-remessa-form");
	}else{
		$dados_remessa_alteracao_vencimento = array(
	        'id_usuario' => $id_usuario,
	        'dados' => $remessa,
	        'data_gerado' => $data_gerado,
	        'nome_arquivo' => $nome_arquivo,
	        'tipo' => 'alteracao_vencimento'
	    );

	    $id_remessa = DBCreate('', 'tb_remessa_bancaria', $dados_remessa_alteracao_vencimento, true);
	    registraLog('Inserção de remessa de alteração de vencimento.','i','tb_boleto',$id_remessa,"id_usuario: $id_usuario | dados: $remessa | data_gerado: $data_gerado | nome_arquivo: $nome_arquivo. tipo: alteracao_vencimento");

	    foreach ($certos as $id_boleto => $conteudo_boleto){
            $titulo_data_vencimento = $conteudo_boleto['titulo_data_vencimento'];
            $titulo_valor = $conteudo_boleto['titulo_valor'];
	    	$dados_remessa_boleto = array(
		        'id_remessa_bancaria' => $id_remessa,
		        'id_boleto' => $id_boleto,
		        'titulo_data_vencimento' => $titulo_data_vencimento,
		        'titulo_valor' => $titulo_valor
		    );
		    
		    $id_remessa_boleto = DBCreate('', 'tb_remessa_bancaria_boleto', $dados_remessa_boleto, true);
	   	 	registraLog('Inserção de remessa.','i','tb_remessa_bancaria_boleto',$id_remessa_boleto,"id_remessa_bancaria: $id_remessa | id_boleto: $id_boleto | titulo_data_vencimento: $titulo_data_vencimento | titulo_valor: $titulo_valor");

	   	 	$dados_boleto_remessa_pendente = array(
                'remessa_pendente' => '0'
            );
            DBUpdate('', 'tb_boleto', $dados_boleto_remessa_pendente, "id_boleto = $id_boleto");
	    }

		$alert = ("Remessa de alteração de vencimento gerada com sucesso!", 's');
    	header("location: /api/iframe?token=$request->token&view=boleto-remessa-busca");		
    }
}

function importar_retorno(){

    if(is_uploaded_file($_FILES['filename']['tmp_name'])){

        $arquivo = fopen($_FILES['filename']['tmp_name'], "r");
        $identificador = substr(fgets($arquivo, 240), 142,29); 
        fclose($arquivo);

        $dados_retorno = DBRead('','tb_retorno_bancario',"WHERE identificador = '$identificador'");

        if(!$dados_retorno && $identificador){

            $arquivo = fopen($_FILES['filename']['tmp_name'], "r");
            $conteudo = fread($arquivo, filesize($_FILES['filename']['tmp_name']));
            $conteudo_base_64 =  base64_encode($conteudo);   
            fclose($arquivo);        
            
            $parametros = '
            {
            "arquivo": "'.$conteudo_base_64.'"          
            }
            ';

            $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/retornos', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));
                    
            if($resultado['_status'] == 'sucesso'){
                $protocolo = $resultado['_dados']['protocolo'];                          

                $dados = array(
			        'identificador' => $identificador,
			        'dados' => $conteudo_base_64,
			        'protocolo' => $protocolo,
			    );
			    DBCreate('', 'tb_retorno_bancario', $dados);

                $alert = ('Retorno importado com sucesso!', 's');
                header("location: /api/iframe?token=$request->token&view=boleto-busca");
            }else{
                $alert = ('Não foi possível importar o arquivo!', 'd');
                header("location: /api/iframe?token=$request->token&view=boleto-retorno-importar");
                exit;
            }
        }else{
            $alert = ('Retorno já foi importado anteriormente!', 'w');
            header("location: /api/iframe?token=$request->token&view=boleto-retorno-importar");
        }        
    }else{

        $alert = ('Não foi possível ler o arquivo!', 'd');
        header("location: /api/iframe?token=$request->token&view=boleto-retorno-importar");
        exit;

    }
}

function sincronizar_boleto(){
    	
	$dados_boleto = DBRead('', 'tb_boleto', "WHERE remessa_pendente = 0 AND (situacao = 'EMITIDO' OR situacao = 'REGISTRADO' OR situacao = 'BAIXA PENDENTE' OR situacao = 'ALTERACAO VENCIMENTO PENDENTE' OR situacao = 'ALTERACAO VALOR PENDENTE' OR situacao = 'PENDENTE_RETENTATIVA')");

    $cont = 0;
    if($dados_boleto){
    	foreach ($dados_boleto as $conteudo_boleto) {
            $link = DBConnect('');
            DBBegin($link);
	    	$id_boleto = $conteudo_boleto['id_boleto'];
	    	$id_integracao = $conteudo_boleto['id_integracao'];

	    	$resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos?idIntegracao='.$id_integracao, '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');

			if($resultado['_dados'][0]['situacao'] && $resultado['_dados'][0]['situacao'] != 'SALVO' && $resultado['_dados'][0]['situacao'] != ''){
				if($resultado['_dados'][0]['situacao'] != $conteudo_boleto['situacao']){                   
                    $situacao = $resultado['_dados'][0]['situacao'];
                    $motivo_situacao = $resultado['_dados'][0]['motivo'];
                    $dados_situacao = array(
                        'situacao' => $situacao,
                        'motivo_situacao' => $motivo_situacao,
                        'data_sincronizacao' => getDataHora()
                    );

                    DBUpdateTransaction($link, 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                   	registraLogTransaction($link, 'Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: $situacao");

                    if($resultado['_dados'][0]['situacao'] == 'LIQUIDADO'){
                    	$dados_conta_receber = DBReadTransaction($link, 'tb_conta_receber', "WHERE id_boleto = '".$id_boleto."' AND situacao = 'aberta' ");
                    	
                    	$pagamento_data = explode(' ',$resultado['_dados'][0]['PagamentoData']);
                    	$pagamento_data = converteData($pagamento_data[0]).' '.$pagamento_data[1];
                        $pagamento_valor_pago = converteMoeda($resultado['_dados'][0]['PagamentoValorPago'], 'banco');

                    	if($dados_conta_receber){

                    		 //Inicio tb_caixa_movimentacao
					            $tipo = 'entrada';
					            $valor = $dados_conta_receber[0]['valor'];
					            $data_movimentacao = $pagamento_data;
					            $origem = 'conta_receber';
					            $id_usuario = $_SESSION['id_usuario'];
					            $id_caixa = $dados_conta_receber[0]['id_caixa'];
					            $data_cadastro = getDataHora();
					            $id_pessoa = $dados_conta_receber[0]['id_pessoa'];
					            $id_natureza_financeira = $dados_conta_receber[0]['id_natureza_financeira'];
					            $descricao = $dados_conta_receber[0]['descricao'];
					           
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

                    		//Quitar a conta e criar conta movimentacao
					            $dados = array(
					                'data_pagamento' => $data_movimentacao,
					                'situacao' => 'quitada',
					                'id_caixa_movimentacao' => $insertID
                                );
                                
                                DBUpdateTransaction($link, 'tb_conta_receber', $dados, "id_conta_receber = '".$dados_conta_receber[0]['id_conta_receber']."'");
                                
					            registraLogTransaction($link, 'Quitar conta receber.','a','tb_conta_receber',$dados_conta_receber[0]['id_conta_receber'],"data_pagamento: $data_movimentacao | situacao: quitada | id_caixa_movimentacao: $insertID");
                            //Fim quitar a conta e criar conta movimentacao   

                    		if($pagamento_valor_pago > $valor){
                    			//criar conta a receber quitada com o valor da diferenca e id_natureza_financeira_juros_multa da tabela boleto_configuracao
                                
                    			$valor_diferenca = $pagamento_valor_pago - $valor;

								$data_vencimento = $dados_conta_receber[0]['data_vencimento'];
								$id_nfs = $dados_conta_receber[0]['id_nfs'];
								$id_contrato_plano_pessoa = $dados_conta_receber[0]['id_contrato_plano_pessoa'];
								$id_conta_pai = $dados_conta_receber[0]['id_conta_pai'];
								$id_faturamento = $dados_conta_receber[0]['id_faturamento'];

                    			$dados = array(
								    'id_natureza_financeira' => $id_natureza_financeira,
								    'valor' => $valor_diferenca,
								    'data_emissao' =>  $data_movimentacao,
								    'data_pagamento' =>  $data_movimentacao,
								    'data_vencimento' =>  $data_vencimento,
								    'situacao' =>  'quitada',
								    'numero_parcela' =>  1,
								    'id_boleto' =>  $id_boleto,
								    'id_nfs' =>  $id_nfs,
								    'id_faturamento' =>  $id_faturamento,
								    'descricao' =>  $descricao,
								    'id_contrato_plano_pessoa' =>  $id_contrato_plano_pessoa,
								    'id_usuario' =>  $id_usuario,
								    'id_conta_pai' =>  $id_conta_pai,
								    'id_caixa' =>  $id_caixa,
								    'data_cadastro' =>  $data_cadastro,
								    'id_pessoa' =>  $id_pessoa,
								    'envio_email' =>  2
								);
								
								$insertID = DBCreateTransaction($link, 'tb_conta_receber', $dados, true);
								registraLogTransaction($link, 'Inserção de nova conta a receber.','i','tb_conta_receber',$insertID,"id_natureza_financeira: $id_natureza_financeira | valor: $valor_diferenca | data_emissao: $data_movimentacao | data_pagamento: $data_movimentacao | data_vencimento: $data_vencimento | situacao: quitada | numero_parcela: 1 | id_boleto: $id_boleto | id_nfs: $id_nfs | id_faturamento: $id_faturamento | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_conta_pai: $id_conta_pai | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: 2");

								//Inicio tb_caixa_movimentacao
					          
	                    			$dados_boleto_configuracao = DBReadTransaction($link, 'tb_boleto_configuracao', "LIMIT 1");
						            $id_natureza_financeira = $dados_boleto_configuracao[0]['id_natureza_financeira_juros_multa'];
						            
						            $dados = array(
						                'tipo' => $tipo,
						                'valor' => $valor_diferenca,
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
						            registraLogTransaction($link, 'Inserção de caixa movimentação.','i','tb_caixa_movimentacao',$insertID,"tipo: $tipo | valor: $valor_diferenca | data_movimentacao: $data_movimentacao | origem: $origem | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | id_natureza_financeira: $id_natureza_financeira | descricao: $descricao");
                                //Fim tb_caixa_movimentacao
                                
                    		}
                    	}  
                        $dados_pagamento = array(
                            'pagamento_data' => $pagamento_data,
                            'pagamento_valor_pago' => $pagamento_valor_pago
                        );
                        DBUpdateTransaction($link, 'tb_boleto', $dados_pagamento, "id_boleto = '$id_boleto'");
                        registraLogTransaction($link, 'Alteração de pagamento do boleto.','a','tb_boleto',$id_boleto,"pagamento_data: $pagamento_data | pagamento_valor_pago: $pagamento_valor_pago");    			
                    }
                    $cont++;
				}
            }
            DBCommit($link);
	    }
	    if($cont == 1){
	    	$alert = ($cont.' boleto sincronizado!','s');
	    }else if($cont > 1){
	    	$alert = ($cont.' boletos sincronizados!','s');
	    }else{
	    	$alert = ('Nenhum boleto sincronizado, aguardando retorno bancário!','w');
	    }
    }else{
    	$alert = ('Não existem boletos a serem sincronizados!','w');
    }    
	header("location: /api/iframe?token=$request->token&view=boleto-busca");
}

?>