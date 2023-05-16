<?php
require_once(__DIR__."/System.php");

require('../inc/e-notas/eNotasGW.php');
        
use eNotasGW\Api\Exceptions as Exceptions;

eNotasGW::configure(array(
    'apiKey' => getDadosApiNfs('apiKey')
));

//Inserir
	$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
	$id_natureza_financeira = (!empty($_POST['id_natureza_financeira'])) ? $_POST['id_natureza_financeira'] : '';
	$id_caixa = (!empty($_POST['id_caixa'])) ? $_POST['id_caixa'] : '';
	$data_vencimento_conta_receber = (!empty($_POST['data_vencimento_conta_receber'])) ? converteData($_POST['data_vencimento_conta_receber']) : '';
	$data_emissao = (!empty($_POST['data_emissao'])) ? converteData($_POST['data_emissao']) : '';
	$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : NULL;

	$emitir_boleto = (!empty($_POST['emitir_boleto'])) ? $_POST['emitir_boleto'] : NULL;
	$emitir_nfs = (!empty($_POST['emitir_nfs'])) ? $_POST['emitir_nfs'] : NULL;
	$valor_total = (!empty($_POST['valor'])) ? $_POST['valor'] : '';
	$qtd_parcela = (!empty($_POST['qtd_parcela'])) ? $_POST['qtd_parcela'] : '';

	$parcela = (!empty($_POST['parcela'])) ? $_POST['parcela'] : '';
	$data_vencimento = (!empty($_POST['data_vencimento'])) ? $_POST['data_vencimento'] : '';
	
	$origem = (!empty($_POST['origem'])) ? $_POST['origem'] : '';
	$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

	$dados_parcela_vencimento = array();
//Inserir

//ControleContas
	$selecionar = (!empty($_POST['selecionar_conta_receber'])) ? $_POST['selecionar_conta_receber'] : '';
    $data_pagamento = (!empty($_POST['data_pagamento_hidden_conta_receber'])) ? $_POST['data_pagamento_hidden_conta_receber'] : '';
    $data_vencimento = (!empty($_POST['data_vencimento'])) ? $_POST['data_vencimento'] : '';
	$id_conta_receber = (!empty($_POST['id_conta_receber'])) ? $_POST['id_conta_receber'] : '';
//ControleContas

//enviar_email_conta_receber
	$assunto = (!empty($_POST['assunto'])) ? $_POST['assunto'] : '';
	$descricao_email = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
	$selecionar_conta_receber = (!empty($_POST['selecionar_conta_receber'])) ? $_POST['selecionar_conta_receber'] : '';
	$envia_nfs = (!empty($_POST['envia_nfs'])) ? $_POST['envia_nfs'] : NULL;
	$envia_xml = (!empty($_POST['envia_xml'])) ? $_POST['envia_xml'] : NULL;
	$envia_boleto = (!empty($_POST['envia_boleto'])) ? $_POST['envia_boleto'] : NULL;
//enviar_email_conta_receber
	$nova_data_vencimento = (!empty($_POST['nova_data_vencimento'])) ? $_POST['nova_data_vencimento'] : '';
	$id_conta_receber_altera_vencimento = (!empty($_POST['id_conta_receber_altera_vencimento'])) ? $_POST['id_conta_receber_altera_vencimento'] : '';


if(!empty($_POST['inserir'])) {
    //inicio variavel para datas de vencimento e valores
	    $cont = 0;
		foreach ($parcela as $conteudo_parcela) {
		    $dados_parcela_vencimento[$cont]['parcela'] = $conteudo_parcela;
		    $cont++;
		}

		$cont = 0;
		foreach ($data_vencimento as $conteudo_data_vencimento) {
		    $dados_parcela_vencimento[$cont]['data_vencimento'] = $conteudo_data_vencimento;
		    $cont++;
		}
	//fim

	//Inicio busca pessoa a partir do id_contrato_plano_pessoa
		if($origem == 2){
			$dados_pessoa = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
			$id_pessoa = $dados_pessoa[0]['id_pessoa'];
		}else{
			$id_contrato_plano_pessoa = NULL;
		}
	//Fim busca pessoa a partir do id_contrato_plano_pessoa

	inserir($id_natureza_financeira, $emitir_boleto, $emitir_nfs, $descricao, $id_caixa, $id_pessoa, $dados_parcela_vencimento, $valor_total, $origem, $id_contrato_plano_pessoa);
   
}else if(!empty($_POST['baixar_conta_receber'])){
	
	$baixa_nota = (!empty($_POST['baixa_nota'])) ? $_POST['baixa_nota'] : '';
	$descricao_baixar = (!empty($_POST['descricao_baixar_hidden_conta_receber'])) ? $_POST['descricao_baixar_hidden_conta_receber'] : '';

    baixar_conta_receber($selecionar, $baixa_nota, $descricao_baixar);

}else if(!empty($_POST['quitar_conta_receber'])){

    quitar_conta_receber($selecionar, $data_pagamento);

}else if(isset($_GET['ignorar'])) {

    $id = (int)$_GET['ignorar'];
    ignorar_email_conta_receber($id);

}else if(!empty($_POST['enviar_email_conta_receber'])){
	
	enviar_email_conta_receber($assunto, $descricao_email, $selecionar_conta_receber, $envia_nfs, $envia_xml, $envia_boleto);

}else if (isset($_GET['faturamento'])) {

    $id = (int)$_GET['faturamento'];
    $cancelados = (string)$_GET['cancelados'];
    $ordenacao = (string)$_GET['ordenacao'];
  
    
    $dados_faturamento = DBRead('','tb_conta_receber', "WHERE id_faturamento = '$id' AND (situacao = 'aberta' OR situacao = 'quitada')");
    if(!$dados_faturamento){
        inserir_faturamento($id, $cancelados, $ordenacao);
    }else{
        $dados_faturamento2 = DBRead('','tb_faturamento', "WHERE id_faturamento = '$id'");
        if($dados_faturamento2[0]['adesao'] == 1){
            $ancora_servico = 'adesao';
        }else{
            if($dados_faturamento2[0]['cod_servico'] == 'call_suporte'){
                $ancora_servico = 'call_suporte';
            }else if($dados_consulta[0]['cod_servico'] == 'call_ativo'){
                $ancora_servico = 'call_ativo';
            }else{
                $ancora_servico = 'call_monitoramento';
            }
        }
        $alert = ('Já existe uma Conta a Receber vinculada a esta fatura!','d');
        header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
        exit;
    }

}else if(!empty($_POST['alterar_conta_receber'])){

    alterar_vencimento_conta_receber($nova_data_vencimento, $id_conta_receber_altera_vencimento);

}else if (isset($_GET['reprocessar_nfs'])) {

    $id = (int)$_GET['reprocessar_nfs'];    
    reprocessar_nfs($id);   
        
}else if (isset($_GET['emitir_nfs'])) {

    $id = (int)$_GET['emitir_nfs'];    
    emitir_nfs($id);   
        
}else if (isset($_GET['emitir_boleto'])) {

    $id = (int)$_GET['emitir_boleto'];    
    emitir_boleto($id);   
        
}else if (isset($_GET['faturamento_gestao_redes'])) {
    
    $id = (int)$_GET['faturamento_gestao_redes'];

	$cancelados = (!empty($_GET['cancelados_redes'])) ? $_POST['cancelados_redes'] : '';
    $ordenacao = (!empty($_GET['ordenacao_redes'])) ? $_POST['ordenacao_redes'] : '';
    //$descricao_nota = (!empty($_GET['descricao_redes'])) ? $_POST['descricao_redes'] : '';
    $descricao_nota = '';

    if(!$dados_faturamento){
        inserir_faturamento($id, $cancelados, $ordenacao, $descricao_nota);
    }else{
        $alert = ('Já existe uma Conta a Receber vinculada a esta fatura!','d');
        header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=gestao_redes&cancelados=".$cancelados."&ordenacao=".$ordenacao);
        exit;
    }

}else if (isset($_POST['faturamento_call_ativo'])) {

    $id = (!empty($_POST['id_ajuste_call_ativo'])) ? $_POST['id_ajuste_call_ativo'] : '';
    $cancelados = (!empty($_POST['cancelados_call_ativo'])) ? $_POST['cancelados_call_ativo'] : '';
    $ordenacao = (!empty($_POST['ordenacao_call_ativo'])) ? $_POST['ordenacao_call_ativo'] : '';
    $descricao_nota = (!empty($_POST['descricao_call_ativo'])) ? $_POST['descricao_call_ativo'] : '';

    if(!$dados_faturamento){
        inserir_faturamento($id, $cancelados, $ordenacao, $descricao_nota);
    }else{
        $alert = ('Já existe uma Conta a Receber vinculada a esta fatura!','d');
        header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_ativo&cancelados=".$cancelados."&ordenacao=".$ordenacao);
        exit;
    }

}else if(!empty($_POST['clonar_conta_receber'])){
    clonar_conta_receber($id_conta_receber, $data_vencimento);
}else if(!empty($_POST['conta_receber_check_ativo'])){
    
	$selecionar_ativo = (!empty($_POST['selecionar_ativo'])) ? $_POST['selecionar_ativo'] : '';
    $cancelados = (!empty($_POST['cancelados'])) ? $_POST['cancelados'] : '';
    $ordenacao = (!empty($_POST['ordenacao'])) ? $_POST['ordenacao'] : '';
    if(!$selecionar_ativo || $selecionar_ativo == ""){
        $alert = ('Nenhum faturamento selecionado!','w');
        header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_ativo&cancelados=".$cancelados."&ordenacao=".$ordenacao);
        exit;
    }else{
        inserir_faturamento_check($selecionar_ativo, $cancelados, $ordenacao, 'call_ativo');
    }

}else if(!empty($_POST['conta_receber_check_suporte'])){
    
	$selecionar_suporte = (!empty($_POST['selecionar_suporte'])) ? $_POST['selecionar_suporte'] : '';
    $cancelados = (!empty($_POST['cancelados'])) ? $_POST['cancelados'] : '';
    $ordenacao = (!empty($_POST['ordenacao'])) ? $_POST['ordenacao'] : '';

    if(!$selecionar_suporte || $selecionar_suporte == ""){
        $alert = ('Nenhum faturamento selecionado!','w');
        header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_suporte&cancelados=".$cancelados."&ordenacao=".$ordenacao);
        exit;
    }else{
        inserir_faturamento_check($selecionar_suporte, $cancelados, $ordenacao, 'call_suporte');
    }
}else if(!empty($_POST['conta_receber_check_monitoramento'])){
    
    $selecionar_monitoramento = (!empty($_POST['selecionar_monitoramento'])) ? $_POST['selecionar_monitoramento'] : '';
    $cancelados = (!empty($_POST['cancelados'])) ? $_POST['cancelados'] : '';
    $ordenacao = (!empty($_POST['ordenacao'])) ? $_POST['ordenacao'] : '';
    if(!$selecionar_monitoramento || $selecionar_monitoramento == ""){
        $alert = ('Nenhum faturamento selecionado!','w');
        header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_monitoramento&cancelados=".$cancelados."&ordenacao=".$ordenacao);
        exit;
    }else{
        inserir_faturamento_check($selecionar_monitoramento, $cancelados, $ordenacao,'call_monitoramento');
    }
}else if (isset($_GET['adesao_pix'])) {
   
        $id = (int)$_GET['adesao_pix'];
        $cancelados = (string)$_GET['cancelados'];
        $ordenacao = (string)$_GET['ordenacao'];
      
        echo $id."<br>";
        echo $cancelados."<br>";
        echo $ordenacao."<br>";
        
        $dados_faturamento = DBRead('','tb_conta_receber', "WHERE id_faturamento = '$id' AND (situacao = 'aberta' OR situacao = 'quitada')");
        if(!$dados_faturamento){
            inserir_adesao_pix($id, $cancelados, $ordenacao);
        }else{
            $ancora_servico = 'adesao';
            $alert = ('Já existe uma Conta a Receber vinculada a esta fatura!','d');
            header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
            exit;
        }
    
}else{
    header("location: ../adm.php");
    exit;
}

function inserir_adesao_pix($id_faturamento, $cancelados, $ordenacao, $descricao_nota = ''){

    $descricao = "";
    $id_usuario = $_SESSION['id_usuario'];
    
    $dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e  ON a.id_plano = e.id_plano WHERE b.contrato_pai = '1' AND a.id_faturamento = '".$id_faturamento."'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.cod_servico, e.nome AS 'nome_plano'");

    $id_contrato_plano_pessoa = $dados_consulta[0]['id_contrato_plano_pessoa'];

    $dados_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "a.*, b.*, c.id_cidade, c.nome AS 'nome_cidade', d.sigla AS 'uf', e.cod_servico");
    
    $id_pessoa = $dados_empresa[0]['id_pessoa'];

    $valor_bruto = $dados_consulta[0]['valor_cobranca'];

    $reter_cofins = $dados_empresa[0]['reter_cofins'];
    $reter_csll = $dados_empresa[0]['reter_csll'];
    $reter_ir = $dados_empresa[0]['reter_ir'];
    $reter_pis = $dados_empresa[0]['reter_pis'];

    if($reter_cofins == 1){   
        $valor_cofins = sprintf("%01.2f", round(($valor_bruto*3)/100, 2));
    }else{
        $valor_cofins = 0;
    }

    if($reter_csll == 1){
        $valor_csll = sprintf("%01.2f", round(($valor_bruto*1)/100, 2));
    }else{
        $valor_csll = 0;
    }

    if($reter_ir == 1){
        $valor_ir = sprintf("%01.2f", round(($valor_bruto*1.5)/100, 2));
    }else{
        $valor_ir = 0;
    }

    if($reter_pis == 1){
        $valor_pis = sprintf("%01.2f", round(($valor_bruto*0.65)/100, 2));
    }else{
        $valor_pis = 0;
    }

    if(($valor_cofins + $valor_csll + $valor_pis) < 10){
        $valor_cofins = 0;
        $valor_csll = 0;
        $valor_pis = 0;
    }

    if($valor_ir < 10){
        $valor_ir = 0;
    }
    $valor_liquido = $valor_bruto - $valor_cofins - $valor_csll - $valor_ir - $valor_pis;

    //INICIO CONTA RECEBER

        $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 1");
        $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
        $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
    
        $id_faturamento = $dados_consulta[0]['id_faturamento'];

        $data_emissao = getDataHora('data');
        $data = explode("-",$data_emissao);
        $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
        $data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
        $valor = $valor_liquido;
        

        $descricao .= 'Referente a: Call Center - Adesão';
        $data_vencimento = $dados_consulta[0]['dia_pagamento_adesao'];
        $valor = $dados_consulta[0]['valor_adesao'];
        $valor_bruto = $dados_consulta[0]['valor_adesao'];
        $valor_liquido = $dados_consulta[0]['valor_adesao'];

        $ancora_servico = 'adesao';

		$situacao_conta_receber = 'aberta';
		$numero_parcela = 1;
		$data_cadastro = getDataHora();
	    $envio_email = 0;

	    $dados = array(
		    'id_natureza_financeira' => $id_natureza_financeira,
		    'valor' => $valor,
		    'valor_bruto' => $valor_bruto,
		    'data_emissao' =>  $data_emissao,
		    'data_vencimento' =>  $data_vencimento,
		    'situacao' =>  $situacao_conta_receber,
		    'numero_parcela' =>  $numero_parcela,
		    'id_faturamento' =>  $id_faturamento,
		    'descricao' =>  $descricao,
		    'id_contrato_plano_pessoa' =>  $id_contrato_plano_pessoa,
		    'id_usuario' =>  $id_usuario,
		    'id_caixa' =>  $id_caixa,
		    'data_cadastro' =>  $data_cadastro,
		    'id_pessoa' =>  $id_pessoa,
		    'envio_email' =>  $envio_email
		);

	    // 1º var_dump
	    // echo "<pre>";
	    // 	var_dump($dados);
	    // echo "</pre>";

        $id_conta_receber = DBCreate('', 'tb_conta_receber', $dados, true);
		registraLog('Inserção de nova conta a receber.','i','tb_conta_receber',$id_conta_receber,"id_natureza_financeira: $id_natureza_financeira | valor: $valor | data_emissao: $data_emissao | data_vencimento: $data_vencimento | situacao: $situacao_conta_receber | numero_parcela: $numero_parcela | id_faturamento: $id_faturamento | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: $envio_email");

	//FIM CONTA RECEBER

        //AQUI
        // $valor_liquido = $valor;

    //INICIO NFS-e
	  
	    $nome_empresa = $dados_empresa[0]['nome'];        
	    $cliente_id_cidade = $dados_empresa[0]['id_cidade'];
	    $cliente_logradouro = $dados_empresa[0]['logradouro'];
	    $cliente_numero = $dados_empresa[0]['numero'];
	    $cliente_complemento = $dados_empresa[0]['complemento'];
	    if(!$cliente_complemento){
	        $cliente_complemento = '-';
	    }
	    $cliente_bairro = $dados_empresa[0]['bairro'];
	    $cliente_cep = $dados_empresa[0]['cep'];
	    $cliente_uf = $dados_empresa[0]['uf'];
	    $id_contrato_plano_pessoa = $dados_empresa[0]['id_contrato_plano_pessoa'];
	    
	    $cliente_razao_social = $dados_empresa[0]['razao_social'];
	    $cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];

	    $data_criacao = getDataHora();
	    
	    $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

        $tipo_nota = 'NFS-e';
        
        $valor_total = $valor_bruto;
    
        //BELLUNO

            $codigo_servico_municipio = $dados_faturamento_configuracao[0]['codigo_servico_municipio'];
            $item_lista_servico = $dados_faturamento_configuracao[0]['item_lista_servico'];
            $descricao_servico_municipio = $dados_faturamento_configuracao[0]['descricao_servico_municipio'];
        
        $data_faturamento = new DateTime(getDataHora('data'));
        $data_faturamento->modify('first day of last month');

        $meses = array(
            "01" => "janeiro",
            "02" => "fevereiro",
            "03" => "março",
            "04" => "abril",
            "05" => "maio",
            "06" => "junho",
            "07" => "julho",
            "08" => "agosto",
            "09" => "setembro",
            "10" => "outubro",
            "11" => "novembro",
            "12" => "dezembro",
        );
       
	    $id_usuario = $_SESSION['id_usuario'];

	    if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
	        
	        $dados = array(
	            'cliente_id_cidade' => $cliente_id_cidade,
	            'cliente_logradouro' => $cliente_logradouro,
	            'cliente_numero' => $cliente_numero,
	            'cliente_complemento' => $cliente_complemento,
	            'cliente_bairro' => $cliente_bairro,
	            'cliente_cep' => $cliente_cep,
	            'id_pessoa' => $id_pessoa,
	            'data_criacao' => $data_criacao,
	            'descricao' => $descricao,
	            'valor_total' => $valor_total,
	            'valor_pis' => $valor_pis,
	            'valor_cofins' => $valor_cofins,
	            'valor_csll' => $valor_csll,
	            'valor_ir' => $valor_ir,
	            'codigo_servico_municipio' => $codigo_servico_municipio,
	            'descricao_servico_municipio' => $descricao_servico_municipio,
	            'item_lista_servico' => $item_lista_servico,
	            'cliente_razao_social' => $cliente_razao_social,
	            'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
	            'tipo_pessoa' => $tipo_pessoa,
	            'id_usuario' => $id_usuario
	        );

	        //4º var_dump
    	    // echo "<pre>";
    	    // 	var_dump($dados);
    	    // echo "</pre>";

	        $id_externo_nfs = DBCreate('', 'tb_nfs', $dados, true);
	        registraLog('Inserção de nota fiscal.','i','tb_nfs',$id_externo_nfs,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario");

	        $dados = array(
	            'status' => 'nao enviado'
	        );

	        DBUpdate('', 'tb_nfs', $dados, "id_nfs = $id_externo_nfs");
	        registraLog('Alteração status nota fiscal, nao enviado.','a','tb_nfs',$id_externo_nfs,"status: nao enviado");
              
            // TESTE NOTA
	        try{
                    $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
                        'tipo' => $tipo_nota,
                        'idExterno' => "$id_externo_nfs",
                        'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'       
                        'cliente' => array(
                            'nome' => $cliente_razao_social,
                            'cpfCnpj' => $cliente_cpf_cnpj,
                            'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
                            'endereco' => array(
                                'uf' => $cliente_uf, 
                                'cidade' => $cliente_id_cidade,
                                'logradouro' => $cliente_logradouro,
                                'numero' => $cliente_numero,
                                'complemento' => $cliente_complemento,
                                'bairro' => $cliente_bairro,
                                'cep' => $cliente_cep
                            )
                        ),
                        
                        'servico' => array(
                            'descricao' => $descricao,
                            'issRetidoFonte' => false,
                            'valorPis' => $valor_pis,
                            'valorCofins' => $valor_cofins,
                            'valorCsll' => $valor_csll,
                            'valorIr' => $valor_ir,
                            'codigoServicoMunicipio' => $codigo_servico_municipio,
                            'descricaoServicoMunicipio' => $descricao_servico_municipio,
                            'itemListaServicoLC116' => $item_lista_servico
                        ),

                        'valorTotal' => $valor_total
                    ));
                    
                    $dados_inserindo = array(
                        'status' => 'inserindo',
                    );

                    DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_externo_nfs");
                    registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_externo_nfs,"status: inserindo");
                    
                    $dados = array(
                        'id_nfs' => $id_externo_nfs    
                    );
                    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
                
                }catch(Exceptions\invalidApiKeyException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Erro de autenticação<br>'.$ex->getMessage().'','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\unauthorizedException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Acesso negado<br>'.$ex->getMessage().'','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\apiException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Erro de validação<br>'.$ex->getMessage().'','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\requestException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! Erro de requisição!','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
	        }
	    // fim nota
	    
	    }else{
	        $descricao_erro = '';
	        if(!$cliente_razao_social){
	            $descricao_erro .= 'Nome inválido';
	        }
	        if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'CNPJ/CPF inválido';
	            }else{
	                $descricao_erro .= ', CNPJ/CPF inválido';
	            }
	        }
	        if(!$cliente_uf){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'UF inválida';
	            }else{
	                $descricao_erro .= ', UF inválida';
	            }
	        }
	        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Cidade inválida';
	            }else{
	                $descricao_erro .= ', cidade inválida';
	            }
	        }
	        if(!$cliente_logradouro){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Logradouro inválido';
	            }else{
	                $descricao_erro .= ', logradouro inválido';
	            }
	        }
	        if(!$cliente_numero){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Número do logradouro inválido';
	            }else{
	                $descricao_erro .= ', número do logradouro inválido';
	            }
	        }
	        if(!$cliente_bairro){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Bairro inválido';
	            }else{
	                $descricao_erro .= ', bairro do logradouro inválido';
	            }
	        }
	        $descricao_erro .= '.';
	        $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.')<br> '.$descricao_erro.'','w');
            header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
            exit;
	    }

	//FIM NFS-e

	$alert = ('Nota fiscal e boleto gerados com sucesso! ('.$nome_empresa.')','s');
    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    exit;
}

function inserir_faturamento_check($dados_id_faturamento, $cancelados, $ordenacao , $ancora_servico){
    //ERRO BOLETO
    $descricao_erro_boleto = '';
    $descricao_erro_nota = '';
    $descricao_erro_conta_receber = '';
    
    foreach ($dados_id_faturamento as $id_faturamento ) {
        $descricao = "FATURA EM ABERTO, AGUARDAMOS SEU PAGAMENTO VIA BOLETO BANCÁRIO. \r\n";
        //ERRO BOLETO
        $cont_erro_boleto = 0;
        $cont_erro_nota = 0;

        $id_usuario = $_SESSION['id_usuario'];
        
        $dados_consulta_conta_receber = DBRead('', 'tb_conta_receber',"WHERE id_faturamento = '".$id_faturamento."'", "id_faturamento");

        if(!$dados_consulta_conta_receber){

            $dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e  ON a.id_plano = e.id_plano WHERE b.contrato_pai = '1' AND a.id_faturamento = '".$id_faturamento."'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.cod_servico, e.nome AS 'nome_plano'");

            $id_contrato_plano_pessoa = $dados_consulta[0]['id_contrato_plano_pessoa'];

            $dados_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "a.*, b.*, c.id_cidade, c.nome AS 'nome_cidade', d.sigla AS 'uf', e.cod_servico");
            
            $id_pessoa = $dados_empresa[0]['id_pessoa'];

            $valor_bruto = $dados_consulta[0]['valor_cobranca'];

            $reter_cofins = $dados_empresa[0]['reter_cofins'];
            $reter_csll = $dados_empresa[0]['reter_csll'];
            $reter_ir = $dados_empresa[0]['reter_ir'];
            $reter_pis = $dados_empresa[0]['reter_pis'];

            if($reter_cofins == 1){   
                $valor_cofins = sprintf("%01.2f", round(($valor_bruto*3)/100, 2));
            }else{
                $valor_cofins = 0;
            }
            if($reter_csll == 1){
                $valor_csll = sprintf("%01.2f", round(($valor_bruto*1)/100, 2));
            }else{
                $valor_csll = 0;
            }
            if($reter_ir == 1){
                $valor_ir = sprintf("%01.2f", round(($valor_bruto*1.5)/100, 2));
            }else{
                $valor_ir = 0;
            }
            if($reter_pis == 1){
                $valor_pis = sprintf("%01.2f", round(($valor_bruto*0.65)/100, 2));
            }else{
                $valor_pis = 0;
            }
            if(($valor_cofins + $valor_csll + $valor_pis) < 10){
                $valor_cofins = 0;
                $valor_csll = 0;
                $valor_pis = 0;
            }
            if($valor_ir < 10){
                $valor_ir = 0;
            }
            $valor_liquido = $valor_bruto - $valor_cofins - $valor_csll - $valor_ir - $valor_pis;

            //INICIO CONTA RECEBER

                if($dados_consulta[0]['adesao'] == 1){
                    $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 1");
                    $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
                    $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
                }else{
                    $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 0");
                    $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
                    $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
                }

                // $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 0");
                // $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
                // $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
            
                $id_faturamento = $dados_consulta[0]['id_faturamento'];

                $data_emissao = getDataHora('data');
                $data = explode("-",$data_emissao);
                
                
                if($dados_consulta[0]['adesao'] == 1){
                    $descricao .= 'Referente a: Call Center - Adesão';
                    $data_vencimento = $dados_consulta[0]['dia_pagamento_adesao'];
                    $valor = $dados_consulta[0]['valor_adesao'];
                    $valor_bruto = $dados_consulta[0]['valor_adesao'];
                    $valor_liquido = $dados_consulta[0]['valor_adesao'];
                }else{
                    $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
                    $data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
                    $valor = $valor_liquido;

                    if($dados_consulta[0]['cod_servico'] == 'call_suporte'){
                        if($dados_consulta[0]['contrato_filho_separar'] == 1){
                       
                            $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].'. Valor do contrato: R$ '.converteMoeda($valor_bruto, 'moeda');
    
                        }else{
                            $dados_porporcional = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
                            if($dados_porporcional){

                                $dados_meses = array(
                                    "01" => "Janeiro",
                                    "02" => "Fevereiro",
                                    "03" => "Março",
                                    "04" => "Abril",
                                    "05" => "Maio",
                                    "06" => "Junho",
                                    "07" => "Julho",
                                    "08" => "Agosto",
                                    "09" => "Setembro",
                                    "10" => "Outubro",
                                    "11" => "Novembro",
                                    "12" => "Dezembro",
                                );
                                $data_referencia_porporcional = new DateTime($dados_consulta[0]['data_referencia']);
                                $mes_referencia_porporcional = $data_referencia_porporcional->format('m');
                                $ano_referencia_porporcional = $data_referencia_porporcional->format('Y');

                                if($dados_consulta[0]['qtd_excedente'] > 0){
                                    $descricao .= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".\r\nQuantidade contratada proporcional: ".$dados_consulta[0]['qtd_contratada'].". Quantidade efetuada: ".$dados_consulta[0]['qtd_efetuada'].". Quantidade de excedentes: ".$dados_consulta[0]['qtd_excedente'].". Valor unitário do excedente: R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').". ";
                                }else{
                                    $descricao .= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".\r\nQuantidade contratada proporcional: ".$dados_consulta[0]['qtd_contratada'].".";
    
                                }

                                // $descricao= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".";


                                // $descricao.= "\r\nAntecipação de ".$dados_meses[$mes_referencia_antecipacao]." de ".$ano_referencia_antecipacao." referente a ".$dados_antecipacao[0]['qtd_dias']." dias. Valor: R$ ".converteMoeda($dados_antecipacao[0]['valor'], 'moeda');
                                // $valor = $valor_liquido+$dados_antecipacao[0]['valor'];

                            }else{
                                if($dados_consulta[0]['valor_diferente_texto'] == 1){
                                    $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' atendimentos via Telefone - '.$dados_consulta[0]['qtd_contratada_texto'].' atendimentos via Texto. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                                }else{
                                    if($dados_consulta[0]['tipo_cobranca'] == 'x_cliente_base'){
                                        
                                        $total_soma_excedente = $dados_consulta[0]['valor_excedente_contrato'] * $dados_consulta[0]['qtd_excedente'];
                                        if($dados_consulta[0]['qtd_excedente'] > 0){
                                            $descricao .= "Call Center - Plano: ".$dados_consulta[0]['nome_plano']." - ".$dados_consulta[0]['qtd_contratada']." clientes na base. Valor do contrato: R$ ".converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda').".\r\nQuantidade de excedentes: ".$dados_consulta[0]['qtd_excedente'].". Valor unitário por excedente R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').". Valor Total de excedentes R$ ".converteMoeda($total_soma_excedente, 'moeda');    
                                        }else{
                                            $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' clientes na base. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');    
                                        }
                                    }else{
                                        $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' atendimentos. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
            
                                        $total_de_atendimentos = $dados_consulta[0]['qtd_efetuada'];
                                        $descricao .= "\r\nQuantidade efetuada: ".$total_de_atendimentos." atendimentos.";
                                    }
                                }
                            }
                        }

                        $dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
                        if($dados_antecipacao){

                            $dados_meses = array(
                                "01" => "Janeiro",
                                "02" => "Fevereiro",
                                "03" => "Março",
                                "04" => "Abril",
                                "05" => "Maio",
                                "06" => "Junho",
                                "07" => "Julho",
                                "08" => "Agosto",
                                "09" => "Setembro",
                                "10" => "Outubro",
                                "11" => "Novembro",
                                "12" => "Dezembro",
                            );
                            $data_referencia_antecipacao = new DateTime($dados_antecipacao[0]['data_referencia']);
                            
                            $mes_referencia_antecipacao = $data_referencia_antecipacao->format('m');
                            $ano_referencia_antecipacao = $data_referencia_antecipacao->format('Y');

                            $descricao.= "\r\nAntecipação de ".$dados_meses[$mes_referencia_antecipacao]." de ".$ano_referencia_antecipacao." referente a ".$dados_antecipacao[0]['qtd_dias']." dias. Valor: R$ ".converteMoeda($dados_antecipacao[0]['valor'], 'moeda');
                            $valor = $valor_liquido+$dados_antecipacao[0]['valor'];
                        }

                        $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido+$dados_antecipacao[0]['valor'], 'moeda');
                        
    
                        // $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido, 'moeda');
                        // $ancora_servico = 'call_suporte';
    
                    }else if($dados_consulta[0]['cod_servico'] == 'call_ativo'){
                        $data_vencimento = new DateTime($data_vencimento);
                        $data_vencimento->format('Y-m-d');
                        
                        if($data_emissao >= $data_vencimento->format('Y-m-d')){
                            $data_vencimento->modify('+1 month');
                            $data_vencimento = $data_vencimento->format('Y-m-d');

                        }
                        $descricao .= 'Call Center Ativo - Plano: '.$dados_consulta[0]['nome_plano'].'. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                        $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido, 'moeda');
                        // $ancora_servico = 'call_ativo';
                        if($dados_consulta[0]['avulso'] == 1){
                            $id_natureza_financeira = 95;
                        }
                    }else{
                        $descricao .= 'Call Center Monitoramento. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                        $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido, 'moeda');
                        // $ancora_servico = 'call_monitoramento';
                    }
                }
                

                $situacao_conta_receber = 'aberta';
                $numero_parcela = 1;
                $data_cadastro = getDataHora();
                $envio_email = 0;

                $dados = array(
                    'id_natureza_financeira' => $id_natureza_financeira,
                    'valor' => $valor,
                    'valor_bruto' => $valor_bruto,
                    'data_emissao' =>  $data_emissao,
                    'data_vencimento' =>  $data_vencimento,
                    'situacao' =>  $situacao_conta_receber,
                    'numero_parcela' =>  $numero_parcela,
                    'id_faturamento' =>  $id_faturamento,
                    'descricao' =>  $descricao,
                    'id_contrato_plano_pessoa' =>  $id_contrato_plano_pessoa,
                    'id_usuario' =>  $id_usuario,
                    'id_caixa' =>  $id_caixa,
                    'data_cadastro' =>  $data_cadastro,
                    'id_pessoa' =>  $id_pessoa,
                    'envio_email' =>  $envio_email
                );

                //1º var_dump
                // echo "<pre>";
                // 	var_dump($dados);
                // echo "</pre>";
                
                    $id_conta_receber = DBCreate('', 'tb_conta_receber', $dados, true);
                    registraLog('Inserção de nova conta a receber.','i','tb_conta_receber',$id_conta_receber,"id_natureza_financeira: $id_natureza_financeira | valor: $valor | data_emissao: $data_emissao | data_vencimento: $data_vencimento | situacao: $situacao_conta_receber | numero_parcela: $numero_parcela | id_faturamento: $id_faturamento | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: $envio_email");

            //FIM CONTA RECEBER

                $valor_liquido = $valor;

            //INICIO GERAR BOLETO
            
                //CONFIGURÇÃO DO BOLETO
                    $dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");
                        
                    $cedente_conta_numero = $dados_boleto_configuracao[0]['conta_numero'];
                    $cedente_conta_numero_dv = $dados_boleto_configuracao[0]['conta_numero_dv'];
                    $cedente_convenio_numero = $dados_boleto_configuracao[0]['convenio_numero'];
                    $cedente_conta_codigo_banco = $dados_boleto_configuracao[0]['conta_codigo_banco'];

                    $titulo_nosso_numero = $dados_boleto_configuracao[0]['nosso_numero'];
                    $titulo_numero_documento = "V201".$dados_boleto_configuracao[0]['numero_documento'];

                    $titulo_mensagem_01 = $dados_boleto_configuracao[0]['mensagem_1'];
                    $titulo_mensagem_02 = $dados_boleto_configuracao[0]['mensagem_2'];

                    $titulo_local_pagamento = $dados_boleto_configuracao[0]['local_pagamento'];
                    $titulo_aceite = $dados_boleto_configuracao[0]['aceite'];
                    $titulo_doc_especie = $dados_boleto_configuracao[0]['especie_documento'];

                //PRÓXIMOS 'NOSSO NUMERO' E 'NUMERO DO DOCUMENTO'
                    $proximo_titulo_nosso_numero = (int)$dados_boleto_configuracao[0]['nosso_numero']+1;
                    $proximo_titulo_numero_documento = (int)$dados_boleto_configuracao[0]['numero_documento']+1;

                //PESSOA
                    $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

                    $sacado_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
                    $sacado_endereco_numero = $dados_empresa[0]['numero'];
                    $sacado_endereco_bairro = $dados_empresa[0]['bairro'];
                    $sacado_endereco_cep = $dados_empresa[0]['cep'];
                    $sacado_endereco_cidade = $dados_empresa[0]['nome_cidade'];
                    $sacado_endereco_complemento = $dados_empresa[0]['complemento'];
                    $sacado_endereco_logradouro = $dados_empresa[0]['logradouro'];
                    $sacado_endereco_pais = 'Brasil';
                    $sacado_endereco_uf = $dados_empresa[0]['uf'];
                    $sacado_nome = $dados_empresa[0]['razao_social'];      

                //DATAS
                    if($dados_consulta[0]['adesao'] == 1){
                        $titulo_data_emissao = getDataHora('data');
                        $titulo_data_vencimento = $dados_consulta[0]['dia_pagamento_adesao'];
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));                        
                    }else{
                        $titulo_data_emissao = getDataHora('data');
                        $data = explode("-",$titulo_data_emissao);
                        $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
                        $titulo_data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));
                    }
                    
                    
                    //VERIFICA SE DATA DO VENCIMENTO CAI EM FINAL DE SEMANA OU FERIADO E MUDA A DATA DA MULTA JUROS
                    $numero_dia_vencimento = date('w', strtotime($titulo_data_vencimento));
                    $dados_feriado = DBRead('','tb_feriado',"WHERE tipo = 'Nacional' AND data = '".substr($titulo_data_vencimento, 5, 5)."'");
                    if($dados_feriado && $numero_dia_vencimento == 5){
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+3 days",strtotime($titulo_data_multa_juros)));
                    }else if($numero_dia_vencimento == 6){
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+2 days",strtotime($titulo_data_multa_juros)));
                    }else if($numero_dia_vencimento == 0 || ($dados_feriado && $numero_dia_vencimento != 6)){
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_multa_juros)));
                    }

                //NOVOS
                    $situacao = "EMITIDO";
                    $titulo_valor = $valor_liquido;
                    //$titulo_valor_multa_taxa = sprintf("%01.2f", round($titulo_valor*0.02, 2));
                    $titulo_valor_multa_taxa = '2.00';

                if(($sacado_nome && $sacado_endereco_uf && $sacado_endereco_cidade && $sacado_endereco_cidade != 'Não Definida' && $sacado_endereco_logradouro && $sacado_endereco_numero && $sacado_endereco_bairro && $sacado_endereco_cep && $titulo_valor > 0) && (($tipo_pessoa == 'J' && valida_cnpj($sacado_cpf_cnpj)) || ($tipo_pessoa == 'F' && valida_cpf($sacado_cpf_cnpj)))){

                    $dados = array(
                        'id_pessoa' => $id_pessoa,
                        'cedente_conta_numero' => $cedente_conta_numero,
                        'cedente_conta_numero_dv' => $cedente_conta_numero_dv,
                        'cedente_convenio_numero' => $cedente_convenio_numero,
                        'cedente_conta_codigo_banco' => $cedente_conta_codigo_banco,
                            'sacado_cpf_cnpj' => $sacado_cpf_cnpj,
                            'sacado_endereco_numero' => $sacado_endereco_numero,
                            'sacado_endereco_bairro' => $sacado_endereco_bairro,
                            'sacado_endereco_cep' => $sacado_endereco_cep,
                            'sacado_endereco_cidade' => $sacado_endereco_cidade,
                            'sacado_endereco_complemento' => $sacado_endereco_complemento,
                            'sacado_endereco_logradouro' => $sacado_endereco_logradouro,
                            'sacado_endereco_pais' => $sacado_endereco_pais,
                            'sacado_endereco_uf' => $sacado_endereco_uf,
                            'sacado_nome' => $sacado_nome,
                        'titulo_data_emissao' => $titulo_data_emissao,
                        'titulo_data_vencimento' => $titulo_data_vencimento,
                        'titulo_mensagem_01' => $titulo_mensagem_01,
                        'titulo_mensagem_02' => $titulo_mensagem_02,
                        'titulo_nosso_numero' => $titulo_nosso_numero,
                        'titulo_numero_documento' => $titulo_numero_documento,
                        'titulo_valor' => $titulo_valor,
                        'titulo_local_pagamento' => $titulo_local_pagamento,
                        'titulo_aceite' => $titulo_aceite,
                        'titulo_doc_especie' => $titulo_doc_especie,
                        'situacao' => $situacao,
                        'id_usuario' => $id_usuario,
                        'remessa_pendente' => '0'

                    );

                    //2º var_dump
                    // echo "<pre>";
                    // 	var_dump($dados);
                    // echo "</pre>";

                        $id_boleto = DBCreate('', 'tb_boleto', $dados, true);
                        registraLog('Inserção de boleto.','i','tb_boleto',$id_boleto,"id_pessoa: $id_pessoa | cedente_conta_numero: $cedente_conta_numero | cedente_conta_numero_dv: $cedente_conta_numero_dv | cedente_convenio_numero: $cedente_convenio_numero | cedente_conta_codigo_banco: $cedente_conta_codigo_banco | sacado_cpf_cnpj: $sacado_cpf_cnpj | sacado_endereco_numero: $sacado_endereco_numero | sacado_endereco_bairro: $sacado_endereco_bairro | sacado_endereco_cep: $sacado_endereco_cep | sacado_endereco_cidade: $sacado_endereco_cidade | sacado_endereco_complemento: $sacado_endereco_complemento | sacado_endereco_logradouro: $sacado_endereco_logradouro | sacado_endereco_pais: $sacado_endereco_pais | sacado_endereco_uf: $sacado_endereco_uf | sacado_nome: $sacado_nome | titulo_data_emissao: $titulo_data_emissao | titulo_data_vencimento: $titulo_data_vencimento | titulo_mensagem_01: $titulo_mensagem_01 | titulo_mensagem_02: $titulo_mensagem_02 | titulo_nosso_numero: $titulo_nosso_numero | titulo_valor: $titulo_valor | titulo_local_pagamento: $titulo_local_pagamento | titulo_aceite: $titulo_aceite | titulo_doc_especie: $titulo_doc_especie | situacao: $situacao | id_usuario: $id_usuario");
                
                    //incluir boleto
                    $parametros = '
                    [
                        {
                            "CedenteContaNumero": "'.$cedente_conta_numero.'",
                            "CedenteContaNumeroDV": "'.$cedente_conta_numero_dv.'",
                            "CedenteConvenioNumero": "'.$cedente_convenio_numero.'",
                            "CedenteContaCodigoBanco": "'.$cedente_conta_codigo_banco.'",
                            "SacadoCPFCNPJ": "'.$sacado_cpf_cnpj.'",
                            "SacadoEnderecoNumero": "'.$sacado_endereco_numero.'",
                            "SacadoEnderecoBairro": "'.$sacado_endereco_bairro.'",
                            "SacadoEnderecoCEP": "'.$sacado_endereco_cep.'",
                            "SacadoEnderecoCidade": "'.$sacado_endereco_cidade.'",
                            "SacadoEnderecoComplemento": "'.$sacado_endereco_complemento.'",
                            "SacadoEnderecoLogradouro": "'.$sacado_endereco_logradouro.'",
                            "SacadoEnderecoPais": "'.$sacado_endereco_pais.'",
                            "SacadoEnderecoUF": "'.$sacado_endereco_uf.'",
                            "SacadoNome": "'.$sacado_nome.'",
                            "SacadoTelefone": "5532819200",
                            "TituloDataEmissao": "'.converteData($titulo_data_emissao).'",
                            "TituloDataVencimento": "'.converteData($titulo_data_vencimento).'",
                            "TituloMensagem01": "'.$titulo_mensagem_01.'",
                            "TituloMensagem02": "'.$titulo_mensagem_02.'",
                            "TituloNossoNumero": "'.$titulo_nosso_numero.'",
                            "TituloNumeroDocumento": "'.$titulo_numero_documento.'",
                            "TituloValor": "'.str_replace('.', ',', sprintf("%01.2f", $titulo_valor)).'",
                            "TituloLocalPagamento": "'.$titulo_local_pagamento.'",
                            "TituloAceite": "'.$titulo_aceite.'",
                            "TituloDocEspecie": "'.$titulo_doc_especie.'",
                            "TituloCodigoMulta": "1",
                            "TituloValorMultaTaxa": "'.str_replace('.', ',', $titulo_valor_multa_taxa).'",                  
                            "TituloDataMulta": "'.converteData($titulo_data_multa_juros).'",
                            "TituloCodigoJuros": "2",
                            "TituloValorJuros": "0,03",                
                            "TituloDataJuros": "'.converteData($titulo_data_multa_juros).'"
                        }
                    ]
                    ';
                    
                    //3º var_dump
                    // echo "<pre>";
                    // 	var_dump($parametros);
                    // echo "</pre>";

                    $dados_json = array(
                        'json' => $parametros
                    );
                    DBUpdate('', 'tb_boleto', $dados_json, "id_boleto = $id_boleto");
                    
                    $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));

                    //TESTE BOLETO
                        if(!$resultado['_dados']['_sucesso']){
                            $dados_situacao = array(
                                'situacao' => 'FALHA'
                            );

                            DBUpdate('', 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                            registraLog('Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: FALHA");

                            $falhas = '';
                            if($resultado['_dados']['_falha']){
                                foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	
                                    // $falhas.= '<br>Erro(s):';
                                    if($conteudo_resultado['_erro']){
                                        foreach ($conteudo_resultado['_erro']['erros'] as $conteudo) {
                                            $falhas.= ' '.$conteudo;
                                        }
                                    }
                                }
                            }

                            // $alert = ('Não foi possível gerar o boleto! Erro na API!'.$falhas,'w');
                            // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                            // exit;

                        }else{
                            $id_integracao = $resultado['_dados']['_sucesso'][0]['idintegracao'];
                            $dados_id_integrcao = array(
                                'id_integracao' => $id_integracao
                            );
                            DBUpdate('', 'tb_boleto', $dados_id_integrcao, "id_boleto = $id_boleto");
                            registraLog('Inserçao id_integracao boleto.','a','tb_boleto',$id_boleto,"id_integracao: $id_integracao");

                            $dados_configuracao_boleto = array(
                                'nosso_numero' => $proximo_titulo_nosso_numero,
                                'numero_documento' => $proximo_titulo_numero_documento
                            );
                            DBUpdate('', 'tb_boleto_configuracao', $dados_configuracao_boleto, "");

                            $dados = array(
                                'id_boleto' => $id_boleto    
                            );
                            DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
                    }

                    if($falhas != ''){
                        if($descricao_erro_boleto == '' ){
                            $descricao_erro_boleto .= 'Boletos:<br>'.$dados_empresa[0]['nome'].": ".$falhas;
                        }else{
                            $descricao_erro_boleto .= $dados_empresa[0]['nome'].": ".$falhas;
                        }
                    }
                    
                    
                //fim incluir boleto
            
                }else{
                    if($descricao_erro_boleto == '' ){
                        $descricao_erro_boleto .= 'Boletos:<br>'.$dados_empresa[0]['nome'].": ";
                    }else{
                        $descricao_erro_boleto .= $dados_empresa[0]['nome'].": ";
                    }
                    if(!$sacado_nome){
                        $cont_erro_boleto ++;
                        $descricao_erro_boleto .= 'Nome inválido';
                    }
                    if(!$sacado_cpf_cnpj){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'CNPJ/CPF inválido';
                        }else{
                            $descricao_erro_boleto .= ', CNPJ/CPF inválido';
                        }
                    }
                    if(!$sacado_endereco_uf){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'UF inválida';
                        }else{
                            $descricao_erro_boleto .= ', UF inválida';
                        }
                    }
                    if(!$sacado_endereco_cidade || $sacado_endereco_cidade == 'Não Definida'){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'Cidade inválida';
                        }else{
                            $descricao_erro_boleto .= ', cidade inválida';
                        }
                    }
                    if(!$sacado_endereco_logradouro){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'Logradouro inválido';
                        }else{
                            $descricao_erro_boleto .= ', logradouro inválido';
                        }
                    }
                    if(!$sacado_endereco_numero){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'Número do logradouro inválido';
                        }else{
                            $descricao_erro_boleto .= ', número do logradouro inválido';
                        }
                    }
                    if(!$sacado_endereco_bairro){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'Bairro do logradouro inválido';
                        }else{
                            $descricao_erro_boleto .= ', bairro do logradouro inválido';
                        }
                    }
                    if(!$sacado_endereco_cep){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'CEP do logradouro inválido';
                        }else{
                            $descricao_erro_boleto .= ', CEP do logradouro inválido';
                        }
                    }
                    if(!$titulo_valor || $titulo_valor < 0){
                        if($cont_erro_boleto == 0){
                            $cont_erro_boleto ++;
                            $descricao_erro_boleto .= 'Valor do boleto inválido';
                        }else{
                            $descricao_erro_boleto .= ', valor do boleto inválido';
                        }
                    }
                    $descricao_erro_boleto .= '.<br>';

                    // $alert = ('Não foi possível gerar a boleto! Erro na pessoa, '.$descricao_erro.'','w');
                    // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    // exit;
                }

            //FIM GERAR BOLETO

            //INICIO NFS-e
        
                $nome_empresa = $dados_empresa[0]['nome'];        
                $cliente_id_cidade = $dados_empresa[0]['id_cidade'];
                $cliente_logradouro = $dados_empresa[0]['logradouro'];
                $cliente_numero = $dados_empresa[0]['numero'];
                $cliente_complemento = $dados_empresa[0]['complemento'];
                if(!$cliente_complemento){
                    $cliente_complemento = '-';
                }
                $cliente_bairro = $dados_empresa[0]['bairro'];
                $cliente_cep = $dados_empresa[0]['cep'];
                $cliente_uf = $dados_empresa[0]['uf'];
                $id_contrato_plano_pessoa = $dados_empresa[0]['id_contrato_plano_pessoa'];
                
                $cliente_razao_social = $dados_empresa[0]['razao_social'];
                $cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];

                $data_criacao = getDataHora();
                
                $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

                $tipo_nota = 'NFS-e';
                
                if($dados_antecipacao){
                    $valor_total = $valor_bruto+$dados_antecipacao[0]['valor'];
                }else{
                    $valor_total = $valor_bruto;
                }

                /*
                if($dados_empresa[0]['cod_servico'] == 'gestao_redes'){
                    $codigo_servico_municipio = '010700188';
                    $item_lista_servico = '01.07';
                    $descricao_servico_municipio = 'Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados.';
                }else{
                    $codigo_servico_municipio = '010700188';
                    $item_lista_servico = '01.07';
                    $descricao_servico_municipio = 'Datilografia, digitação, estenografia, expediente, secretaria em geral, resposta audível, redação, edição, interpretação, revisão, tradução, apoio e infra-estrutura administrativa e congêneres.';
                }*/

                //BELLUNO

                    $codigo_servico_municipio = $dados_faturamento_configuracao[0]['codigo_servico_municipio'];
                    $item_lista_servico = $dados_faturamento_configuracao[0]['item_lista_servico'];
                    $descricao_servico_municipio = $dados_faturamento_configuracao[0]['descricao_servico_municipio'];
                
                $data_faturamento = new DateTime(getDataHora('data'));
                $data_faturamento->modify('first day of last month');

                $meses = array(
                    "01" => "janeiro",
                    "02" => "fevereiro",
                    "03" => "março",
                    "04" => "abril",
                    "05" => "maio",
                    "06" => "junho",
                    "07" => "julho",
                    "08" => "agosto",
                    "09" => "setembro",
                    "10" => "outubro",
                    "11" => "novembro",
                    "12" => "dezembro",
                );

                if($dados_consulta[0]['adesao'] == 0){
                    $descricao .= "\r\nReferente a ".$meses[$data_faturamento->format('m')].' de '.$data_faturamento->format('Y').'.';
                }

                // if($descricao_nota != '' && $descricao_nota){
                //     $descricao .= "\r\n\r\nObservações:";
                //     $descricao .= "\r\n".$descricao_nota;
                // }
                
                $dados_faturamento_ajuste = DBRead('','tb_faturamento_ajuste',"WHERE id_faturamento = '".$dados_consulta[0]['id_faturamento']."'");

                if($dados_faturamento_ajuste){
                    $descricao .= "\r\n\r\nObservações:";
                    foreach ($dados_faturamento_ajuste as $conteudo_faturamento_ajuste) {
                        if($conteudo_faturamento_ajuste['tipo'] == 'desconto'){
                            $tipo_ajuste = 'Desconto';
                        }else{
                            $tipo_ajuste = 'Acréscimo';
                        }
                        $descricao .= "\r\n".$tipo_ajuste.' de R$ '.converteMoeda($conteudo_faturamento_ajuste['valor'], 'moeda').'. Referente a: '.$conteudo_faturamento_ajuste['descricao'];
                    }
                }

                $id_usuario = $_SESSION['id_usuario'];

                if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
                    
                    $dados = array(
                        'cliente_id_cidade' => $cliente_id_cidade,
                        'cliente_logradouro' => $cliente_logradouro,
                        'cliente_numero' => $cliente_numero,
                        'cliente_complemento' => $cliente_complemento,
                        'cliente_bairro' => $cliente_bairro,
                        'cliente_cep' => $cliente_cep,
                        'id_pessoa' => $id_pessoa,
                        'data_criacao' => $data_criacao,
                        'descricao' => $descricao,
                        'valor_total' => $valor_total,
                        'valor_pis' => $valor_pis,
                        'valor_cofins' => $valor_cofins,
                        'valor_csll' => $valor_csll,
                        'valor_ir' => $valor_ir,
                        'codigo_servico_municipio' => $codigo_servico_municipio,
                        'descricao_servico_municipio' => $descricao_servico_municipio,
                        'item_lista_servico' => $item_lista_servico,
                        'cliente_razao_social' => $cliente_razao_social,
                        'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
                        'tipo_pessoa' => $tipo_pessoa,
                        'id_usuario' => $id_usuario
                    );

                    //4º var_dump
                    // echo "<pre>";
                    // 	var_dump($dados);
                    // echo "</pre>";

                        $id_externo_nfs = DBCreate('', 'tb_nfs', $dados, true);
                        registraLog('Inserção de nota fiscal.','i','tb_nfs',$id_externo_nfs,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario");

                    $dados = array(
                        'status' => 'nao enviado'
                    );

                    DBUpdate('', 'tb_nfs', $dados, "id_nfs = $id_externo_nfs");
                    registraLog('Alteração status nota fiscal, nao enviado.','a','tb_nfs',$id_externo_nfs,"status: nao enviado");
                    
                    // NOTA
                    try{
                            $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
                                'tipo' => $tipo_nota,
                                'idExterno' => "$id_externo_nfs",
                                'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'       
                                'cliente' => array(
                                    'nome' => $cliente_razao_social,
                                    'cpfCnpj' => $cliente_cpf_cnpj,
                                    'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
                                    'endereco' => array(
                                        'uf' => $cliente_uf, 
                                        'cidade' => $cliente_id_cidade,
                                        'logradouro' => $cliente_logradouro,
                                        'numero' => $cliente_numero,
                                        'complemento' => $cliente_complemento,
                                        'bairro' => $cliente_bairro,
                                        'cep' => $cliente_cep
                                    )
                                ),
                                
                                'servico' => array(
                                    'descricao' => $descricao,
                                    'issRetidoFonte' => false,
                                    'valorPis' => $valor_pis,
                                    'valorCofins' => $valor_cofins,
                                    'valorCsll' => $valor_csll,
                                    'valorIr' => $valor_ir,
                                    'codigoServicoMunicipio' => $codigo_servico_municipio,
                                    'descricaoServicoMunicipio' => $descricao_servico_municipio,
                                    'itemListaServicoLC116' => $item_lista_servico
                                ),

                                'valorTotal' => $valor_total
                            ));
                            
                            $dados_inserindo = array(
                                'status' => 'inserindo',
                            );

                            DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_externo_nfs");
                            registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_externo_nfs,"status: inserindo");
                            
                            $dados = array(
                                'id_nfs' => $id_externo_nfs    
                            );
                            DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
                        
                        }catch(Exceptions\invalidApiKeyException $ex) {
                            if($descricao_erro_nota == '' ){
                                $descricao_erro_nota .= 'Notas:<br>'.$dados_empresa[0]['nome'].": Erro de autenticação - ".$ex->getMessage();
                            }else{
                                $descricao_erro_nota .= $dados_empresa[0]['nome'].": Erro de autenticação - ".$ex->getMessage();
                            }
                        }catch(Exceptions\unauthorizedException $ex) {
                            if($descricao_erro_nota == '' ){
                                $descricao_erro_nota .= 'Notas:<br>'.$dados_empresa[0]['nome'].": Acesso negado - ".$ex->getMessage();
                            }else{
                                $descricao_erro_nota .= $dados_empresa[0]['nome'].": Acesso negado - ".$ex->getMessage();
                            }
                        }catch(Exceptions\apiException $ex) {
                            if($descricao_erro_nota == '' ){
                                $descricao_erro_nota .= 'Notas:<br>'.$dados_empresa[0]['nome'].": Erro de validação - ".$ex->getMessage();
                            }else{
                                $descricao_erro_nota .= $dados_empresa[0]['nome'].": Erro de validação - ".$ex->getMessage();
                            }
                        }catch(Exceptions\requestException $ex) {
                            if($descricao_erro_nota == '' ){
                                $descricao_erro_nota .= 'Notas:<br>'.$dados_empresa[0]['nome'].": Erro de requisição";
                            }else{
                                $descricao_erro_nota .= $dados_empresa[0]['nome'].": Erro de requisição";
                            }
                    }
                // fim nota
                
                }else{
                    if($descricao_erro_nota == '' ){
                        $descricao_erro_nota .= 'Notas:<br>'.$dados_empresa[0]['nome'].": ";
                    }else{
                        $descricao_erro_nota .= $dados_empresa[0]['nome'].": ";
                    }
                    
                    if(!$cliente_razao_social){
                        $cont_erro_nota ++;
                        $descricao_erro_nota .= 'Nome inválido';
                    }
                    if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
                        if($cont_erro_nota == 0){
                            $cont_erro_nota ++;
                            $descricao_erro_nota .= 'CNPJ/CPF inválido';
                        }else{
                            $descricao_erro_nota .= ', CNPJ/CPF inválido';
                        }
                    }
                    if(!$cliente_uf){
                        if($cont_erro_nota == 0){
                            $cont_erro_nota ++;
                            $descricao_erro_nota .= 'UF inválida';
                        }else{
                            $descricao_erro_nota .= ', UF inválida';
                        }
                    }
                    if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
                        if($cont_erro_nota == 0){
                            $cont_erro_nota ++;
                            $descricao_erro_nota .= 'Cidade inválida';
                        }else{
                            $descricao_erro_nota .= ', cidade inválida';
                        }
                    }
                    if(!$cliente_logradouro){
                        if($cont_erro_nota == 0){
                            $cont_erro_nota ++;
                            $descricao_erro_nota .= 'Logradouro inválido';
                        }else{
                            $descricao_erro_nota .= ', logradouro inválido';
                        }
                    }
                    if(!$cliente_numero){
                        if($cont_erro_nota == 0){
                            $cont_erro_nota ++;
                            $descricao_erro_nota .= 'Número do logradouro inválido';
                        }else{
                            $descricao_erro_nota .= ', número do logradouro inválido';
                        }
                    }
                    if(!$cliente_bairro){
                        if($cont_erro_nota == 0){
                            $cont_erro_nota ++;
                            $descricao_erro_nota .= 'Bairro inválido';
                        }else{
                            $descricao_erro_nota .= ', bairro do logradouro inválido';
                        }
                    }
                    $descricao_erro_nota .= '.<br>';
                }

            //FIM NFS-e
        }else{
            $dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e  ON a.id_plano = e.id_plano WHERE a.id_faturamento = '".$id_faturamento."'", "d.nome");

            if($descricao_erro_conta_receber == ''){
                $descricao_erro_conta_receber = 'Já possui conta a receber: <br>'.$dados_consulta[0]['nome'];
            }else{
                $descricao_erro_conta_receber = '<br>'.$dados_consulta[0]['nome'];
            }
        }

        sleep(5);

    }
   
    if($descricao_erro_boleto != '' || $descricao_erro_nota != '' || $descricao_erro_conta_receber != ''){
        if($descricao_erro_boleto != '' && $descricao_erro_nota != '' && $descricao_erro_conta_receber != ''){
            $alert = ('Erros em:<br>'.$descricao_erro_boleto.'<br>'.$descricao_erro_nota.'<br>'.$descricao_erro_conta_receber,'w');
        }else if($descricao_erro_boleto != '' && $descricao_erro_conta_receber != ''){
            $alert = ('Erros em:<br>'.$descricao_erro_boleto.'<br>'.$descricao_erro_conta_receber,'w');
        }else if($descricao_erro_nota != '' && $descricao_erro_conta_receber != ''){
            $alert = ('Erros em:<br>'.$descricao_erro_nota.'<br>'.$descricao_erro_conta_receber,'w');
        }else if($descricao_erro_boleto != '' && $descricao_erro_nota != ''){
            $alert = ('Erros em:<br>'.$descricao_erro_boleto.'<br>'.$descricao_erro_nota,'w');
        }else if($descricao_erro_boleto != ''){
            $alert = ('Erros em:<br>'.$descricao_erro_boleto,'w');
        }else if($descricao_erro_nota != ''){
            $alert = ('Erros em:<br>'.$descricao_erro_nota,'w');
        }
    }else{
        $alert = ('Nota(s) fiscal(is) e boleto(s) gerados com sucesso!','s');
    }

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    exit;
}

function clonar_conta_receber($id_conta_receber, $data_vencimento){
	
	$link = DBConnect('');
    DBBegin($link);
    
    $dados_conta_receber = DBReadTransaction($link, 'tb_conta_receber', "WHERE id_conta_receber = '".$id_conta_receber."' ");

    foreach ($data_vencimento as $conteudo_data_vencimento) {
    
        $id_natureza_financeira = $dados_conta_receber[0]['id_natureza_financeira'];
        $valor = $dados_conta_receber[0]['valor'];
        $valor_bruto = $dados_conta_receber[0]['valor_bruto'];
        $data_emissao = getDataHora('data');
        $data_vencimento = converteData($conteudo_data_vencimento);
        $situacao = 'aberta';
        $numero_parcela = '1';
        $descricao = $dados_conta_receber[0]['descricao'];
        $id_contrato_plano_pessoa = $dados_conta_receber[0]['id_contrato_plano_pessoa'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_caixa = $dados_conta_receber[0]['id_caixa'];
        $data_cadastro = getDataHora();
        $id_pessoa = $dados_conta_receber[0]['id_pessoa'];
        $envio_email = '2';
        $observacao = $dados_conta_receber[0]['observacao'];
        
       
        $dados = array(
            "id_natureza_financeira" => $id_natureza_financeira,
            "valor" => $valor,
            "valor_bruto" => $valor_bruto,
            "data_emissao" => $data_emissao,
            "data_vencimento" => $data_vencimento,
            "situacao" => $situacao,
            "numero_parcela" => $numero_parcela,
            "descricao" => $descricao,
            "id_contrato_plano_pessoa" => $id_contrato_plano_pessoa,
            "id_usuario" => $id_usuario,
            "id_caixa" => $id_caixa,
            "data_cadastro" => $data_cadastro,
            "id_pessoa" => $id_pessoa,
            "envio_email" => $envio_email,
            "observacao" => $observacao
        );

        $insertID = DBCreateTransaction($link, 'tb_conta_receber', $dados, true);
        registraLogTransaction($link, 'Inserção clone de conta a receber.','i','tb_conta_receber',$insertID,"id_natureza_financeira: $id_natureza_financeira | valor: $valor | valor_bruto: $valor_bruto | data_emissao: $data_emissao | data_vencimento: $data_vencimento | situacao: $situacao | numero_parcela: $numero_parcela | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: $envio_email | observacao: $observacao");
    }

    DBCommit($link);
    
    $alert = ('Conta a receber clonada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function inserir_faturamento($id_faturamento, $cancelados, $ordenacao, $descricao_nota = ''){
    $descricao = "FATURA EM ABERTO, AGUARDAMOS SEU PAGAMENTO VIA BOLETO BANCÁRIO. \r\n";

    $id_usuario = $_SESSION['id_usuario'];
    
    $dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e  ON a.id_plano = e.id_plano WHERE b.contrato_pai = '1' AND a.id_faturamento = '".$id_faturamento."'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.cod_servico, e.nome AS 'nome_plano'");

    $id_contrato_plano_pessoa = $dados_consulta[0]['id_contrato_plano_pessoa'];

    $dados_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "a.*, b.*, c.id_cidade, c.nome AS 'nome_cidade', d.sigla AS 'uf', e.cod_servico");
    
    $id_pessoa = $dados_empresa[0]['id_pessoa'];

    $valor_bruto = $dados_consulta[0]['valor_cobranca'];

    $reter_cofins = $dados_empresa[0]['reter_cofins'];
    $reter_csll = $dados_empresa[0]['reter_csll'];
    $reter_ir = $dados_empresa[0]['reter_ir'];
    $reter_pis = $dados_empresa[0]['reter_pis'];

    if($reter_cofins == 1){   
        $valor_cofins = sprintf("%01.2f", round(($valor_bruto*3)/100, 2));
    }else{
        $valor_cofins = 0;
    }

    if($reter_csll == 1){
        $valor_csll = sprintf("%01.2f", round(($valor_bruto*1)/100, 2));
    }else{
        $valor_csll = 0;
    }

    if($reter_ir == 1){
        $valor_ir = sprintf("%01.2f", round(($valor_bruto*1.5)/100, 2));
    }else{
        $valor_ir = 0;
    }

    if($reter_pis == 1){
        $valor_pis = sprintf("%01.2f", round(($valor_bruto*0.65)/100, 2));
    }else{
        $valor_pis = 0;
    }

    if(($valor_cofins + $valor_csll + $valor_pis) < 10){
        $valor_cofins = 0;
        $valor_csll = 0;
        $valor_pis = 0;
    }

    if($valor_ir < 10){
        $valor_ir = 0;
    }
    $valor_liquido = $valor_bruto - $valor_cofins - $valor_csll - $valor_ir - $valor_pis;

    //INICIO CONTA RECEBER

    if($dados_consulta[0]['adesao'] == 1){
        $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 1");
        $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
        $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
    }else{
        $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 0");
        $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
        $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
    }
		
        $id_faturamento = $dados_consulta[0]['id_faturamento'];

        $data_emissao = getDataHora('data');
        $data = explode("-",$data_emissao);
        $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
        $data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
        $valor = $valor_liquido;
        
            if($dados_consulta[0]['adesao'] == 1){
                $descricao .= 'Referente a: Call Center - Adesão';
                $data_vencimento = $dados_consulta[0]['dia_pagamento_adesao'];
                $valor = $dados_consulta[0]['valor_adesao'];
                $valor_bruto = $dados_consulta[0]['valor_adesao'];
                $valor_liquido = $dados_consulta[0]['valor_adesao'];

                $ancora_servico = 'adesao';

            }else{
                
                $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
                $data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
                $valor = $valor_liquido;
         
                if($dados_consulta[0]['cod_servico'] == 'call_suporte'){
                    if($dados_consulta[0]['contrato_filho_separar'] == 1){
                       
                        $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].'. Valor do contrato: R$ '.converteMoeda($valor_bruto, 'moeda');

                    }else{
                        $dados_porporcional = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
                        if($dados_porporcional){

                            $dados_meses = array(
                                "01" => "Janeiro",
                                "02" => "Fevereiro",
                                "03" => "Março",
                                "04" => "Abril",
                                "05" => "Maio",
                                "06" => "Junho",
                                "07" => "Julho",
                                "08" => "Agosto",
                                "09" => "Setembro",
                                "10" => "Outubro",
                                "11" => "Novembro",
                                "12" => "Dezembro",
                            );
                            $data_referencia_porporcional = new DateTime($dados_consulta[0]['data_referencia']);
                            $mes_referencia_porporcional = $data_referencia_porporcional->format('m');
                            $ano_referencia_porporcional = $data_referencia_porporcional->format('Y');

                            if($dados_consulta[0]['qtd_excedente'] > 0){
                                $descricao .= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".\r\nQuantidade contratada proporcional: ".$dados_consulta[0]['qtd_contratada'].". Quantidade efetuada: ".$dados_consulta[0]['qtd_efetuada'].". Quantidade de excedentes: ".$dados_consulta[0]['qtd_excedente'].". Valor unitário do excedente: R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').". ";
                            }else{
                                $descricao .= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".\r\nQuantidade contratada proporcional: ".$dados_consulta[0]['qtd_contratada'].".";

                            }


                            // $descricao.= "\r\nAntecipação de ".$dados_meses[$mes_referencia_antecipacao]." de ".$ano_referencia_antecipacao." referente a ".$dados_antecipacao[0]['qtd_dias']." dias. Valor: R$ ".converteMoeda($dados_antecipacao[0]['valor'], 'moeda');
                            // $valor = $valor_liquido+$dados_antecipacao[0]['valor'];

                        }else{
                            if($dados_consulta[0]['valor_diferente_texto'] == 1){
                                $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' atendimentos via Telefone - '.$dados_consulta[0]['qtd_contratada_texto'].' atendimentos via Texto. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                            }else{
                                if($dados_consulta[0]['tipo_cobranca'] == 'x_cliente_base'){
        
                                    $total_soma_excedente = $dados_consulta[0]['valor_excedente_contrato'] * $dados_consulta[0]['qtd_excedente'];
                                    if($dados_consulta[0]['qtd_excedente'] > 0){
                                        $descricao .= "Call Center - Plano: ".$dados_consulta[0]['nome_plano']." - ".$dados_consulta[0]['qtd_contratada']." clientes na base. Valor do contrato: R$ ".converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda').".\r\nQuantidade de excedentes: ".$dados_consulta[0]['qtd_excedente'].". Valor unitário por excedente R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').". Valor Total de excedentes R$ ".converteMoeda($total_soma_excedente, 'moeda');    
                                    }else{
                                        $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' clientes na base. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');    
                                    }
        
                                }else{
                                    $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' atendimentos. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
        
                                    $total_de_atendimentos = $dados_consulta[0]['qtd_efetuada'];
                                    $descricao .= "\r\nQuantidade efetuada: ".$total_de_atendimentos." atendimentos.";
                                }
                                
                            }
                        }
                    }
                    

                    // $total_de_atendimentos = $dados_consulta[0]['qtd_efetuada'] + $dados_consulta[0]['qtd_efetuada_texto'];
                    // $descricao .= "\r\nQuantidade efetuada: ".$total_de_atendimentos." atendimentos.";
                    
                    $dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
                    if($dados_antecipacao){

                        $dados_meses = array(
                            "01" => "Janeiro",
                            "02" => "Fevereiro",
                            "03" => "Março",
                            "04" => "Abril",
                            "05" => "Maio",
                            "06" => "Junho",
                            "07" => "Julho",
                            "08" => "Agosto",
                            "09" => "Setembro",
                            "10" => "Outubro",
                            "11" => "Novembro",
                            "12" => "Dezembro",
                        );
                        $data_referencia_antecipacao = new DateTime($dados_antecipacao[0]['data_referencia']);
                        
                        $mes_referencia_antecipacao = $data_referencia_antecipacao->format('m');
                        $ano_referencia_antecipacao = $data_referencia_antecipacao->format('Y');

                        $descricao .= "\r\nAntecipação de ".$dados_meses[$mes_referencia_antecipacao]." de ".$ano_referencia_antecipacao." referente a ".$dados_antecipacao[0]['qtd_dias']." dias. Valor: R$ ".converteMoeda($dados_antecipacao[0]['valor'], 'moeda');
                        $valor = $valor_liquido+$dados_antecipacao[0]['valor'];
                    }

                    $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido+$dados_antecipacao[0]['valor'], 'moeda');
                    
                    $ancora_servico = 'call_suporte';

                }else if($dados_consulta[0]['cod_servico'] == 'gestao_redes'){
                    // echo "<hr>";
                    // echo "<hr>";
                    // echo "<hr>";
                    // echo "<pre>";
                    // var_dump($dados_consulta[0]);
                    // echo "</pre>";
                    // echo "<hr>";
                    // echo "<hr>";
                    // echo "<hr>";


                    if($dados_consulta[0]['tipo_cobranca'] == 'mensal_desafogo'){
                        $tipo_cobranca = "Mensal com Desafogo";
                    }else if($dados_consulta[0]['tipo_cobranca'] == 'cliente_base'){
                        $tipo_cobranca = "Clientes na Base";
                    }else if($dados_consulta[0]['tipo_cobranca'] == 'cliente_ativo'){
                        $tipo_cobranca = "Clientes Ativos";
                    }else if($dados_consulta[0]['tipo_cobranca'] == 'x_cliente_base'){
                        $tipo_cobranca = "Até ".$dados_consulta[0]['qtd_contratada']." Clientes na Base";
                    }else if($dados_consulta[0]['tipo_cobranca'] == 'prepago'){
                        $tipo_cobranca = "Pré-pago";
                    }else{
                        $tipo_cobranca = ucfirst($dados_consulta[0]['tipo_cobranca']);
                    }
                    $descricao .= 'Gestão de Redes - Plano: '.$dados_consulta[0]['nome_plano'].'. Tipo de cobrança: '.$tipo_cobranca.'. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                    if($dados_consulta[0]['tipo_cobranca'] == 'cliente_ativo' || $dados_consulta[0]['tipo_cobranca'] == 'cliente_base'){
                            $total_soma_excedente = $dados_consulta[0]['valor_excedente_contrato'] * $dados_consulta[0]['qtd_efetuada'];
                            $descricao .= "\r\n Quantidade de clientes: ".$dados_consulta[0]['qtd_efetuada']." (R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').") - R$ ".converteMoeda($total_soma_excedente, 'moeda')." ";
                    }else if($dados_consulta[0]['tipo_cobranca'] == 'x_cliente_base'){
                        if($dados_consulta[0]['qtd_excedente'] != 0){
                            $total_soma_excedente = $dados_consulta[0]['valor_excedente_contrato'] * $dados_consulta[0]['qtd_excedente'];
                            $descricao .= "\r\n ".$dados_consulta[0]['qtd_excedente']." Ampliação de clientes (R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').") - R$ ".converteMoeda($total_soma_excedente, 'moeda')." ";
                        }
                    }else if($dados_consulta[0]['tipo_cobranca'] == 'horas'){
                        if($dados_consulta[0]['qtd_excedente'] != 0){
                            $total_soma_excedente = $dados_consulta[0]['valor_excedente_contrato'] * $dados_consulta[0]['qtd_excedente'];
                            $descricao .= "\r\n ".$dados_consulta[0]['qtd_excedente']." Horas excedentes no mês (R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').") - R$ ".converteMoeda($total_soma_excedente, 'moeda')." ";
                        }
                    }
                    if($dados_consulta[0]['valor_plantao_total'] != '0.00'){
                        
                        $descricao .= "\r\nPlantões: R$ ".converteMoeda($dados_consulta[0]['valor_plantao_total'], 'moeda');

                        $dados_plantao = DBRead('','tb_plantao_redes',"WHERE data_referencia = '".$dados_consulta[0]['data_referencia']."' AND id_contrato_plano_pessoa = '".$dados_consulta[0]['id_contrato_plano_pessoa']."' ");  
                        if($dados_plantao){
                            $descricao .= "\r\nAssunto dos Plantões:";
                            foreach ($dados_plantao as $conteudo_plantao) {
                                $descricao .= "\r\n- ".$conteudo_plantao['assunto'];
                            }
                        }
                    }
                    $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido, 'moeda');

                    $ancora_servico = 'gestao_redes';

                }else if($dados_consulta[0]['cod_servico'] == 'call_ativo'){

                    $data_vencimento = new DateTime($data_vencimento);
                    $data_vencimento->format('Y-m-d');
                    
                    if($data_emissao >= $data_vencimento->format('Y-m-d')){
                        $data_vencimento->modify('+1 month');
                    }
                    $data_vencimento = $data_vencimento->format('Y-m-d');


                    $descricao .= 'Call Center Ativo - Plano: '.$dados_consulta[0]['nome_plano'].'. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                    $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido, 'moeda');

                    $ancora_servico = 'call_ativo';

                    if($dados_consulta[0]['avulso'] == 1){
                        $id_natureza_financeira = 95;
                    }
                }else{
                    //$descricao = 'Call Center Monitoramento - Plano: '.$dados_consulta[0]['nome_plano'].'. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                    $descricao .= 'Call Center Monitoramento. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                    $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido, 'moeda');

                    $ancora_servico = 'call_monitoramento';
                }
            }
            

            

		$situacao_conta_receber = 'aberta';
		$numero_parcela = 1;
		$data_cadastro = getDataHora();
	    $envio_email = 0;

	    $dados = array(
		    'id_natureza_financeira' => $id_natureza_financeira,
		    'valor' => $valor,
		    'valor_bruto' => $valor_bruto,
		    'data_emissao' =>  $data_emissao,
		    'data_vencimento' =>  $data_vencimento,
		    'situacao' =>  $situacao_conta_receber,
		    'numero_parcela' =>  $numero_parcela,
		    'id_faturamento' =>  $id_faturamento,
		    'descricao' =>  $descricao,
		    'id_contrato_plano_pessoa' =>  $id_contrato_plano_pessoa,
		    'id_usuario' =>  $id_usuario,
		    'id_caixa' =>  $id_caixa,
		    'data_cadastro' =>  $data_cadastro,
		    'id_pessoa' =>  $id_pessoa,
		    'envio_email' =>  $envio_email
		);

	    // 1º var_dump
	    // echo "<pre>";
	    // 	var_dump($dados);
	    // echo "</pre>";


        $id_conta_receber = DBCreate('', 'tb_conta_receber', $dados, true);
		registraLog('Inserção de nova conta a receber.','i','tb_conta_receber',$id_conta_receber,"id_natureza_financeira: $id_natureza_financeira | valor: $valor | data_emissao: $data_emissao | data_vencimento: $data_vencimento | situacao: $situacao_conta_receber | numero_parcela: $numero_parcela | id_faturamento: $id_faturamento | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: $envio_email");

	//FIM CONTA RECEBER

        //AQUI
        // $valor_liquido = $valor;

	//INICIO GERAR BOLETO
		
	    //CONFIGURÇÃO DO BOLETO
	        $dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");
	        
            $cedente_conta_numero = $dados_boleto_configuracao[0]['conta_numero'];
            $cedente_conta_numero_dv = $dados_boleto_configuracao[0]['conta_numero_dv'];
            $cedente_convenio_numero = $dados_boleto_configuracao[0]['convenio_numero'];
            $cedente_conta_codigo_banco = $dados_boleto_configuracao[0]['conta_codigo_banco'];

            $titulo_nosso_numero = $dados_boleto_configuracao[0]['nosso_numero'];
            $titulo_numero_documento = "V201".$dados_boleto_configuracao[0]['numero_documento'];

            $titulo_mensagem_01 = $dados_boleto_configuracao[0]['mensagem_1'];
            $titulo_mensagem_02 = $dados_boleto_configuracao[0]['mensagem_2'];

            $titulo_local_pagamento = $dados_boleto_configuracao[0]['local_pagamento'];
            $titulo_aceite = $dados_boleto_configuracao[0]['aceite'];
            $titulo_doc_especie = $dados_boleto_configuracao[0]['especie_documento'];

	    //PRÓXIMOS 'NOSSO NUMERO' E 'NUMERO DO DOCUMENTO'
	        $proximo_titulo_nosso_numero = (int)$dados_boleto_configuracao[0]['nosso_numero']+1;
	        $proximo_titulo_numero_documento = (int)$dados_boleto_configuracao[0]['numero_documento']+1;

	    //PESSOA
	        $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

	        $sacado_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
	        $sacado_endereco_numero = $dados_empresa[0]['numero'];
	        $sacado_endereco_bairro = $dados_empresa[0]['bairro'];
	        $sacado_endereco_cep = $dados_empresa[0]['cep'];
	        $sacado_endereco_cidade = $dados_empresa[0]['nome_cidade'];
	        $sacado_endereco_complemento = $dados_empresa[0]['complemento'];
	        $sacado_endereco_logradouro = $dados_empresa[0]['logradouro'];
	        $sacado_endereco_pais = 'Brasil';
	        $sacado_endereco_uf = $dados_empresa[0]['uf'];
	        $sacado_nome = $dados_empresa[0]['razao_social'];      

	    //DATAS
            if($dados_consulta[0]['adesao'] == 1){
                $titulo_data_emissao = getDataHora('data');
                $titulo_data_vencimento = $dados_consulta[0]['dia_pagamento_adesao'];
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));                        
            }else{
                $titulo_data_emissao = getDataHora('data');
                $data = explode("-",$titulo_data_emissao);
                $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
                $titulo_data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));
            }
            
            //VERIFICA SE DATA DO VENCIMENTO CAI EM FINAL DE SEMANA OU FERIADO E MUDA A DATA DA MULTA JUROS
            $numero_dia_vencimento = date('w', strtotime($titulo_data_vencimento));
            $dados_feriado = DBRead('','tb_feriado',"WHERE tipo = 'Nacional' AND data = '".substr($titulo_data_vencimento, 5, 5)."'");
            if($dados_feriado && $numero_dia_vencimento == 5){
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+3 days",strtotime($titulo_data_multa_juros)));
            }else if($numero_dia_vencimento == 6){
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+2 days",strtotime($titulo_data_multa_juros)));
            }else if($numero_dia_vencimento == 0 || ($dados_feriado && $numero_dia_vencimento != 6)){
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_multa_juros)));
            }

	    //NOVOS
	        $situacao = "EMITIDO";
            $dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
            if($dados_antecipacao){
                $titulo_valor = $valor_liquido+$dados_antecipacao[0]['valor'];
            }else{
                $titulo_valor = $valor_liquido;
            }
	        //$titulo_valor_multa_taxa = sprintf("%01.2f", round($titulo_valor*0.02, 2));
            $titulo_valor_multa_taxa = '2.00';

	    if(($sacado_nome && $sacado_endereco_uf && $sacado_endereco_cidade && $sacado_endereco_cidade != 'Não Definida' && $sacado_endereco_logradouro && $sacado_endereco_numero && $sacado_endereco_bairro && $sacado_endereco_cep && $titulo_valor > 0) && (($tipo_pessoa == 'J' && valida_cnpj($sacado_cpf_cnpj)) || ($tipo_pessoa == 'F' && valida_cpf($sacado_cpf_cnpj)))){

	        $dados = array(
	            'id_pessoa' => $id_pessoa,
	            'cedente_conta_numero' => $cedente_conta_numero,
	            'cedente_conta_numero_dv' => $cedente_conta_numero_dv,
	            'cedente_convenio_numero' => $cedente_convenio_numero,
	            'cedente_conta_codigo_banco' => $cedente_conta_codigo_banco,
	                'sacado_cpf_cnpj' => $sacado_cpf_cnpj,
	                'sacado_endereco_numero' => $sacado_endereco_numero,
	                'sacado_endereco_bairro' => $sacado_endereco_bairro,
	                'sacado_endereco_cep' => $sacado_endereco_cep,
	                'sacado_endereco_cidade' => $sacado_endereco_cidade,
	                'sacado_endereco_complemento' => $sacado_endereco_complemento,
	                'sacado_endereco_logradouro' => $sacado_endereco_logradouro,
	                'sacado_endereco_pais' => $sacado_endereco_pais,
	                'sacado_endereco_uf' => $sacado_endereco_uf,
	                'sacado_nome' => $sacado_nome,
	            'titulo_data_emissao' => $titulo_data_emissao,
	            'titulo_data_vencimento' => $titulo_data_vencimento,
	            'titulo_mensagem_01' => $titulo_mensagem_01,
	            'titulo_mensagem_02' => $titulo_mensagem_02,
	            'titulo_nosso_numero' => $titulo_nosso_numero,
	            'titulo_numero_documento' => $titulo_numero_documento,
	            'titulo_valor' => $titulo_valor,
	            'titulo_local_pagamento' => $titulo_local_pagamento,
	            'titulo_aceite' => $titulo_aceite,
	            'titulo_doc_especie' => $titulo_doc_especie,
	            'situacao' => $situacao,
	            'id_usuario' => $id_usuario,
	            'remessa_pendente' => '0'

	        );

	        //2º var_dump
    	    // echo "<pre>";
    	    // 	var_dump($dados);
    	    // echo "</pre>";

	           	$id_boleto = DBCreate('', 'tb_boleto', $dados, true);
	            registraLog('Inserção de boleto.','i','tb_boleto',$id_boleto,"id_pessoa: $id_pessoa | cedente_conta_numero: $cedente_conta_numero | cedente_conta_numero_dv: $cedente_conta_numero_dv | cedente_convenio_numero: $cedente_convenio_numero | cedente_conta_codigo_banco: $cedente_conta_codigo_banco | sacado_cpf_cnpj: $sacado_cpf_cnpj | sacado_endereco_numero: $sacado_endereco_numero | sacado_endereco_bairro: $sacado_endereco_bairro | sacado_endereco_cep: $sacado_endereco_cep | sacado_endereco_cidade: $sacado_endereco_cidade | sacado_endereco_complemento: $sacado_endereco_complemento | sacado_endereco_logradouro: $sacado_endereco_logradouro | sacado_endereco_pais: $sacado_endereco_pais | sacado_endereco_uf: $sacado_endereco_uf | sacado_nome: $sacado_nome | titulo_data_emissao: $titulo_data_emissao | titulo_data_vencimento: $titulo_data_vencimento | titulo_mensagem_01: $titulo_mensagem_01 | titulo_mensagem_02: $titulo_mensagem_02 | titulo_nosso_numero: $titulo_nosso_numero | titulo_valor: $titulo_valor | titulo_local_pagamento: $titulo_local_pagamento | titulo_aceite: $titulo_aceite | titulo_doc_especie: $titulo_doc_especie | situacao: $situacao | id_usuario: $id_usuario");
	       
	    	//incluir boleto
            $parametros = '
            [
                {
                    "CedenteContaNumero": "'.$cedente_conta_numero.'",
                    "CedenteContaNumeroDV": "'.$cedente_conta_numero_dv.'",
                    "CedenteConvenioNumero": "'.$cedente_convenio_numero.'",
                    "CedenteContaCodigoBanco": "'.$cedente_conta_codigo_banco.'",
                    "SacadoCPFCNPJ": "'.$sacado_cpf_cnpj.'",
                    "SacadoEnderecoNumero": "'.$sacado_endereco_numero.'",
                    "SacadoEnderecoBairro": "'.$sacado_endereco_bairro.'",
                    "SacadoEnderecoCEP": "'.$sacado_endereco_cep.'",
                    "SacadoEnderecoCidade": "'.$sacado_endereco_cidade.'",
                    "SacadoEnderecoComplemento": "'.$sacado_endereco_complemento.'",
                    "SacadoEnderecoLogradouro": "'.$sacado_endereco_logradouro.'",
                    "SacadoEnderecoPais": "'.$sacado_endereco_pais.'",
                    "SacadoEnderecoUF": "'.$sacado_endereco_uf.'",
                    "SacadoNome": "'.$sacado_nome.'",
                    "SacadoTelefone": "5532819200",
                    "TituloDataEmissao": "'.converteData($titulo_data_emissao).'",
                    "TituloDataVencimento": "'.converteData($titulo_data_vencimento).'",
                    "TituloMensagem01": "'.$titulo_mensagem_01.'",
                    "TituloMensagem02": "'.$titulo_mensagem_02.'",
                    "TituloNossoNumero": "'.$titulo_nosso_numero.'",
                    "TituloNumeroDocumento": "'.$titulo_numero_documento.'",
                    "TituloValor": "'.str_replace('.', ',', sprintf("%01.2f", $titulo_valor)).'",
                    "TituloLocalPagamento": "'.$titulo_local_pagamento.'",
                    "TituloAceite": "'.$titulo_aceite.'",
                    "TituloDocEspecie": "'.$titulo_doc_especie.'",
                    "TituloCodigoMulta": "1",
                    "TituloValorMultaTaxa": "'.str_replace('.', ',', $titulo_valor_multa_taxa).'",                  
                    "TituloDataMulta": "'.converteData($titulo_data_multa_juros).'",
                    "TituloCodigoJuros": "2",
                    "TituloValorJuros": "0,03",                
                    "TituloDataJuros": "'.converteData($titulo_data_multa_juros).'"
                }
            ]
            ';
			
            //3º var_dump
			// echo "<pre>";
    	    // 	var_dump($parametros);
    	    // echo "</pre>";

            $dados_json = array(
                'json' => $parametros
            );
            DBUpdate('', 'tb_boleto', $dados_json, "id_boleto = $id_boleto");
            
            $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));

            //TESTE BOLETO
                if(!$resultado['_dados']['_sucesso']){
                    $dados_situacao = array(
                        'situacao' => 'FALHA'
                    );

                    DBUpdate('', 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                    registraLog('Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: FALHA");

                    $falhas = '';
                    if($resultado['_dados']['_falha']){
                        foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	
                            $falhas.= '<br>Erro(s):';
                            if($conteudo_resultado['_erro']){
                                foreach ($conteudo_resultado['_erro']['erros'] as $conteudo) {
                                    $falhas.= ' '.$conteudo;
                                }
                            }
                        }
                    }

                    $alert = ('Não foi possível gerar o boleto! Erro na API!'.$falhas,'w');
                	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                	exit;

                }else{
                    $id_integracao = $resultado['_dados']['_sucesso'][0]['idintegracao'];
                    $dados_id_integrcao = array(
                        'id_integracao' => $id_integracao
                    );
                    DBUpdate('', 'tb_boleto', $dados_id_integrcao, "id_boleto = $id_boleto");
                    registraLog('Inserçao id_integracao boleto.','a','tb_boleto',$id_boleto,"id_integracao: $id_integracao");

                    $dados_configuracao_boleto = array(
                        'nosso_numero' => $proximo_titulo_nosso_numero,
                        'numero_documento' => $proximo_titulo_numero_documento
                    );
                    DBUpdate('', 'tb_boleto_configuracao', $dados_configuracao_boleto, "");

                    $dados = array(
                        'id_boleto' => $id_boleto    
                    );
                    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
            }
            
        //fim incluir boleto
      
	    }else{
	        $descricao_erro = '';
	        if(!$sacado_nome){
	            $descricao_erro .= 'Nome inválido';
	        }
	        if(!$sacado_cpf_cnpj){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'CNPJ/CPF inválido';
	            }else{
	                $descricao_erro .= ', CNPJ/CPF inválido';
	            }
	        }
	        if(!$sacado_endereco_uf){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'UF inválida';
	            }else{
	                $descricao_erro .= ', UF inválida';
	            }
	        }
	        if(!$sacado_endereco_cidade || $sacado_endereco_cidade == 'Não Definida'){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Cidade inválida';
	            }else{
	                $descricao_erro .= ', cidade inválida';
	            }
	        }
	        if(!$sacado_endereco_logradouro){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Logradouro inválido';
	            }else{
	                $descricao_erro .= ', logradouro inválido';
	            }
	        }
	        if(!$sacado_endereco_numero){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Número do logradouro inválido';
	            }else{
	                $descricao_erro .= ', número do logradouro inválido';
	            }
	        }
	        if(!$sacado_endereco_bairro){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Bairro do logradouro inválido';
	            }else{
	                $descricao_erro .= ', bairro do logradouro inválido';
	            }
	        }
	        if(!$sacado_endereco_cep){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'CEP do logradouro inválido';
	            }else{
	                $descricao_erro .= ', CEP do logradouro inválido';
	            }
	        }
	        if(!$titulo_valor || $titulo_valor < 0){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Valor do boleto inválido';
	            }else{
	                $descricao_erro .= ', valor do boleto inválido';
	            }
	        }
	        $descricao_erro .= '.';
	        $alert = ('Não foi possível gerar a boleto! Erro na pessoa, '.$descricao_erro.'','w');
    		header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
			exit;
	    }


	//FIM GERAR BOLETO

    //INICIO NFS-e
	  
	    $nome_empresa = $dados_empresa[0]['nome'];        
	    $cliente_id_cidade = $dados_empresa[0]['id_cidade'];
	    $cliente_logradouro = $dados_empresa[0]['logradouro'];
	    $cliente_numero = $dados_empresa[0]['numero'];
	    $cliente_complemento = $dados_empresa[0]['complemento'];
	    if(!$cliente_complemento){
	        $cliente_complemento = '-';
	    }
	    $cliente_bairro = $dados_empresa[0]['bairro'];
	    $cliente_cep = $dados_empresa[0]['cep'];
	    $cliente_uf = $dados_empresa[0]['uf'];
	    $id_contrato_plano_pessoa = $dados_empresa[0]['id_contrato_plano_pessoa'];
	    
	    $cliente_razao_social = $dados_empresa[0]['razao_social'];
	    $cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];

	    $data_criacao = getDataHora();
	    
	    $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

        $tipo_nota = 'NFS-e';
        
        if($dados_antecipacao){
            $valor_total = $valor_bruto+$dados_antecipacao[0]['valor'];
        }else{
            $valor_total = $valor_bruto;
        }
    
	    



        /*
        if($dados_empresa[0]['cod_servico'] == 'gestao_redes'){
            $codigo_servico_municipio = '010700188';
            $item_lista_servico = '01.07';
            $descricao_servico_municipio = 'Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados.';
        }else{
            $codigo_servico_municipio = '010700188';
            $item_lista_servico = '01.07';
            $descricao_servico_municipio = 'Datilografia, digitação, estenografia, expediente, secretaria em geral, resposta audível, redação, edição, interpretação, revisão, tradução, apoio e infra-estrutura administrativa e congêneres.';
        }*/

        //BELLUNO

            $codigo_servico_municipio = $dados_faturamento_configuracao[0]['codigo_servico_municipio'];
            $item_lista_servico = $dados_faturamento_configuracao[0]['item_lista_servico'];
            $descricao_servico_municipio = $dados_faturamento_configuracao[0]['descricao_servico_municipio'];
        
        $data_faturamento = new DateTime(getDataHora('data'));
        $data_faturamento->modify('first day of last month');

        $meses = array(
            "01" => "janeiro",
            "02" => "fevereiro",
            "03" => "março",
            "04" => "abril",
            "05" => "maio",
            "06" => "junho",
            "07" => "julho",
            "08" => "agosto",
            "09" => "setembro",
            "10" => "outubro",
            "11" => "novembro",
            "12" => "dezembro",
        );
        if($dados_consulta[0]['adesao'] == 0){
            $descricao .= "\r\nReferente a ".$meses[$data_faturamento->format('m')].' de '.$data_faturamento->format('Y').'.';
            if($descricao_nota != '' && $descricao_nota){
                $descricao .= "\r\n\r\nObservações:";
                $descricao .= "\r\n".$descricao_nota;
            }
            
            $dados_faturamento_ajuste = DBRead('','tb_faturamento_ajuste',"WHERE id_faturamento = '".$dados_consulta[0]['id_faturamento']."'");
    
            if($dados_faturamento_ajuste){
                $descricao .= "\r\n\r\nObservações:";
                foreach ($dados_faturamento_ajuste as $conteudo_faturamento_ajuste) {
                    if($conteudo_faturamento_ajuste['tipo'] == 'desconto'){
                        $tipo_ajuste = 'Desconto';
                    }else{
                        $tipo_ajuste = 'Acréscimo';
                    }
                    $descricao .= "\r\n".$tipo_ajuste.' de R$ '.converteMoeda($conteudo_faturamento_ajuste['valor'], 'moeda').'. Referente a: '.$conteudo_faturamento_ajuste['descricao'];
                }
            }
        }
        

	    $id_usuario = $_SESSION['id_usuario'];

	    if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
	        
	        $dados = array(
	            'cliente_id_cidade' => $cliente_id_cidade,
	            'cliente_logradouro' => $cliente_logradouro,
	            'cliente_numero' => $cliente_numero,
	            'cliente_complemento' => $cliente_complemento,
	            'cliente_bairro' => $cliente_bairro,
	            'cliente_cep' => $cliente_cep,
	            'id_pessoa' => $id_pessoa,
	            'data_criacao' => $data_criacao,
	            'descricao' => $descricao,
	            'valor_total' => $valor_total,
	            'valor_pis' => $valor_pis,
	            'valor_cofins' => $valor_cofins,
	            'valor_csll' => $valor_csll,
	            'valor_ir' => $valor_ir,
	            'codigo_servico_municipio' => $codigo_servico_municipio,
	            'descricao_servico_municipio' => $descricao_servico_municipio,
	            'item_lista_servico' => $item_lista_servico,
	            'cliente_razao_social' => $cliente_razao_social,
	            'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
	            'tipo_pessoa' => $tipo_pessoa,
	            'id_usuario' => $id_usuario
	        );

	        //4º var_dump
    	    // echo "<pre>";
    	    // 	var_dump($dados);
    	    // echo "</pre>";

	        $id_externo_nfs = DBCreate('', 'tb_nfs', $dados, true);
	        registraLog('Inserção de nota fiscal.','i','tb_nfs',$id_externo_nfs,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario");

	        $dados = array(
	            'status' => 'nao enviado'
	        );

	        DBUpdate('', 'tb_nfs', $dados, "id_nfs = $id_externo_nfs");
	        registraLog('Alteração status nota fiscal, nao enviado.','a','tb_nfs',$id_externo_nfs,"status: nao enviado");
              
            // TESTE NOTA
	        try{
                    $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
                        'tipo' => $tipo_nota,
                        'idExterno' => "$id_externo_nfs",
                        'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'       
                        'cliente' => array(
                            'nome' => $cliente_razao_social,
                            'cpfCnpj' => $cliente_cpf_cnpj,
                            'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
                            'endereco' => array(
                                'uf' => $cliente_uf, 
                                'cidade' => $cliente_id_cidade,
                                'logradouro' => $cliente_logradouro,
                                'numero' => $cliente_numero,
                                'complemento' => $cliente_complemento,
                                'bairro' => $cliente_bairro,
                                'cep' => $cliente_cep
                            )
                        ),
                        
                        'servico' => array(
                            'descricao' => $descricao,
                            'issRetidoFonte' => false,
                            'valorPis' => $valor_pis,
                            'valorCofins' => $valor_cofins,
                            'valorCsll' => $valor_csll,
                            'valorIr' => $valor_ir,
                            'codigoServicoMunicipio' => $codigo_servico_municipio,
                            'descricaoServicoMunicipio' => $descricao_servico_municipio,
                            'itemListaServicoLC116' => $item_lista_servico
                        ),

                        'valorTotal' => $valor_total
                    ));
                    
                    $dados_inserindo = array(
                        'status' => 'inserindo',
                    );

                    DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_externo_nfs");
                    registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_externo_nfs,"status: inserindo");
                    
                    $dados = array(
                        'id_nfs' => $id_externo_nfs    
                    );
                    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
                
                }catch(Exceptions\invalidApiKeyException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Erro de autenticação<br>'.$ex->getMessage().'','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\unauthorizedException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Acesso negado<br>'.$ex->getMessage().'','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\apiException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Erro de validação<br>'.$ex->getMessage().'','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\requestException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! Erro de requisição!','w');
                    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
	        }
	    // fim nota
	    
	    }else{
	        $descricao_erro = '';
	        if(!$cliente_razao_social){
	            $descricao_erro .= 'Nome inválido';
	        }
	        if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'CNPJ/CPF inválido';
	            }else{
	                $descricao_erro .= ', CNPJ/CPF inválido';
	            }
	        }
	        if(!$cliente_uf){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'UF inválida';
	            }else{
	                $descricao_erro .= ', UF inválida';
	            }
	        }
	        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Cidade inválida';
	            }else{
	                $descricao_erro .= ', cidade inválida';
	            }
	        }
	        if(!$cliente_logradouro){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Logradouro inválido';
	            }else{
	                $descricao_erro .= ', logradouro inválido';
	            }
	        }
	        if(!$cliente_numero){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Número do logradouro inválido';
	            }else{
	                $descricao_erro .= ', número do logradouro inválido';
	            }
	        }
	        if(!$cliente_bairro){
	            if($descricao_erro == ''){
	                $descricao_erro .= 'Bairro inválido';
	            }else{
	                $descricao_erro .= ', bairro do logradouro inválido';
	            }
	        }
	        $descricao_erro .= '.';
	        $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.')<br> '.$descricao_erro.'','w');
            header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
            exit;
	    }

	//FIM NFS-e

	$alert = ('Nota fiscal e boleto gerados com sucesso! ('.$nome_empresa.')','s');
    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    exit;
}

function inserir($id_natureza_financeira, $emitir_boleto, $emitir_nfs, $descricao, $id_caixa, $id_pessoa, $dados_parcela_vencimento, $valor_total, $origem, $id_contrato_plano_pessoa){

    $numero_parcela = 1;
	$situacao_conta_receber = 'aberta';
	$id_usuario = $_SESSION['id_usuario'];
    $data_cadastro = getDataHora();
    
    $dados_caixa = DBRead('', 'tb_caixa', "WHERE id_caixa = '".$id_caixa."'");
    if($dados_caixa[0]['aceita_movimentacao'] == 0){
        $envio_email = '2';
    }else{
        $envio_email = '0';
    }
    $data_emissao = getDataHora('data');
    $id_conta_pai = NULL;

    $alerta_erros_boletos = array();
    $alerta_erro_nfs = '';
    $qtd_parcelas = sizeof($dados_parcela_vencimento);

    if($id_contrato_plano_pessoa){
        $dados_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "a.*, b.*, c.id_cidade, c.nome AS 'nome_cidade', d.sigla AS 'uf', e.cod_servico");
    }else{
        $dados_empresa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado WHERE a.id_pessoa = '".$id_pessoa."' ", "a.*, b.id_cidade, b.nome AS 'nome_cidade', c.sigla AS 'uf'");
    }

    $valor_total = converteMoeda($valor_total, 'banco');

    $reter_cofins = $dados_empresa[0]['reter_cofins'];
    $reter_csll = $dados_empresa[0]['reter_csll'];
    $reter_ir = $dados_empresa[0]['reter_ir'];
    $reter_pis = $dados_empresa[0]['reter_pis'];

    if($reter_cofins == 1){   
        $valor_cofins = sprintf("%01.2f", round(($valor_total*3)/100, 2));
    }else{
        $valor_cofins = 0;
    }

    if($reter_csll == 1){
        $valor_csll = sprintf("%01.2f", round(($valor_total*1)/100, 2));
    }else{
        $valor_csll = 0;
    }

    if($reter_ir == 1){
        $valor_ir = sprintf("%01.2f", round(($valor_total*1.5)/100, 2));
    }else{
        $valor_ir = 0;
    }

    if($reter_pis == 1){
        $valor_pis = sprintf("%01.2f", round(($valor_total*0.65)/100, 2));
    }else{
        $valor_pis = 0;
    }

    if(($valor_cofins + $valor_csll + $valor_pis) < 10){
        $valor_cofins = 0;
        $valor_csll = 0;
        $valor_pis = 0;
    }

    if($valor_ir < 10){
        $valor_ir = 0;
    }
    
    $valor_liquido = $valor_total - $valor_cofins - $valor_csll - $valor_ir - $valor_pis;
    
	foreach ($dados_parcela_vencimento as $conteudo_parcela_vencimento) {

        if($qtd_parcelas > 1){
            $valor_bruto = converteMoeda($conteudo_parcela_vencimento['parcela'], 'banco');
		    $valor = converteMoeda($conteudo_parcela_vencimento['parcela'], 'banco');
        }else{
            $valor_bruto = $valor_total;
		    $valor = $valor_liquido;
        }
		echo $valor.' - '.$valor_bruto;
        $data_vencimento = converteData($conteudo_parcela_vencimento['data_vencimento']);

        
        //INICIO CONTA A RECEBER
            $dados = array(
                'id_natureza_financeira' => $id_natureza_financeira,
                'valor' => $valor,
                'valor_bruto' => $valor_bruto,
                'data_emissao' =>  $data_emissao,
                'data_vencimento' =>  $data_vencimento,
                'situacao' =>  $situacao_conta_receber,
                'numero_parcela' =>  $numero_parcela,
                'descricao' =>  $descricao,
                'id_contrato_plano_pessoa' =>  $id_contrato_plano_pessoa,
                'id_usuario' =>  $id_usuario,
                'id_conta_pai' =>  $id_conta_pai,
                'id_caixa' =>  $id_caixa,
                'data_cadastro' =>  $data_cadastro,
                'id_pessoa' =>  $id_pessoa,
                'envio_email' =>  $envio_email
            );
            
            $id_conta_receber = DBCreate('', 'tb_conta_receber', $dados, true);
            registraLog('Inserção de nova conta a receber.','i','tb_conta_receber',$id_conta_receber,"id_natureza_financeira: $id_natureza_financeira | valor: $valor | data_emissao: $data_emissao | data_vencimento: $data_vencimento | situacao: $situacao_conta_receber | numero_parcela: $numero_parcela | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_conta_pai: $id_conta_pai | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: $envio_email");

            if($numero_parcela == 1){
                $id_conta_pai = $id_conta_receber;
            }
        
        //FIM CONTA A RECEBER

		if($emitir_boleto == 1){

			//INICIO GERAR BOLETO

				//OK CONFIGURÇÃO DO BOLETO
				    $dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");
					
                    $cedente_conta_numero = $dados_boleto_configuracao[0]['conta_numero'];
                    $cedente_conta_numero_dv = $dados_boleto_configuracao[0]['conta_numero_dv'];
                    $cedente_convenio_numero = $dados_boleto_configuracao[0]['convenio_numero'];
                    $cedente_conta_codigo_banco = $dados_boleto_configuracao[0]['conta_codigo_banco'];

                    $titulo_nosso_numero = $dados_boleto_configuracao[0]['nosso_numero'];
                    $titulo_numero_documento = "V201".$dados_boleto_configuracao[0]['numero_documento'];

                    $titulo_mensagem_01 = $dados_boleto_configuracao[0]['mensagem_1'];
                    $titulo_mensagem_02 = $dados_boleto_configuracao[0]['mensagem_2'];

                    $titulo_local_pagamento = $dados_boleto_configuracao[0]['local_pagamento'];
                    $titulo_aceite = $dados_boleto_configuracao[0]['aceite'];
                    $titulo_doc_especie = $dados_boleto_configuracao[0]['especie_documento'];

				//PRÓXIMOS 'NOSSO NUMERO' E 'NUMERO DO DOCUMENTO'
				    $proximo_titulo_nosso_numero = (int)$dados_boleto_configuracao[0]['nosso_numero']+1;
			   		$proximo_titulo_numero_documento = (int)$dados_boleto_configuracao[0]['numero_documento']+1;

			    //PESSOA
			    	$tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

				    $sacado_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
				    $sacado_endereco_numero = $dados_empresa[0]['numero'];
				    $sacado_endereco_bairro = $dados_empresa[0]['bairro'];
				    $sacado_endereco_cep = $dados_empresa[0]['cep'];
				    $sacado_endereco_cidade = $dados_empresa[0]['nome_cidade'];
				    $sacado_endereco_complemento = $dados_empresa[0]['complemento'];
				    $sacado_endereco_logradouro = $dados_empresa[0]['logradouro'];
				    $sacado_endereco_pais = 'Brasil';
				    $sacado_endereco_uf = $dados_empresa[0]['uf'];
				    $sacado_nome = $dados_empresa[0]['razao_social'];	   

				//DATAS
					$titulo_data_emissao = $data_emissao;
			        $titulo_data_vencimento = $data_vencimento;
                    $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));
                    
                    //VERIFICA SE DATA DO VENCIMENTO CAI EM FINAL DE SEMANA OU FERIADO E MUDA A DATA DA MULTA JUROS
                    $numero_dia_vencimento = date('w', strtotime($titulo_data_vencimento));
                    $dados_feriado = DBRead('','tb_feriado',"WHERE tipo = 'Nacional' AND data = '".substr($titulo_data_vencimento, 5, 5)."'");
                    if($dados_feriado && $numero_dia_vencimento == 5){
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+3 days",strtotime($titulo_data_multa_juros)));
                    }else if($numero_dia_vencimento == 6){
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+2 days",strtotime($titulo_data_multa_juros)));
                    }else if($numero_dia_vencimento == 0 || ($dados_feriado && $numero_dia_vencimento != 6)){
                        $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_multa_juros)));
                    }

				//NOVOS
				    $situacao = "EMITIDO";
			        $titulo_valor = $valor;
			        //$titulo_valor_multa_taxa = sprintf("%01.2f", round($titulo_valor*0.02, 2));
			        $titulo_valor_multa_taxa = '2.00';

			    $id_usuario = $_SESSION['id_usuario'];

			    if(($sacado_nome && $sacado_endereco_uf && $sacado_endereco_cidade && $sacado_endereco_cidade != 'Não Definida' && $sacado_endereco_logradouro && $sacado_endereco_numero && $sacado_endereco_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($sacado_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($sacado_cpf_cnpj)) && $sacado_endereco_cep && $titulo_valor > 0){

					$dados = array(
				        'id_pessoa' => $id_pessoa,
				        'cedente_conta_numero' => $cedente_conta_numero,
				        'cedente_conta_numero_dv' => $cedente_conta_numero_dv,
				        'cedente_convenio_numero' => $cedente_convenio_numero,
				        'cedente_conta_codigo_banco' => $cedente_conta_codigo_banco,
					        'sacado_cpf_cnpj' => $sacado_cpf_cnpj,
					        'sacado_endereco_numero' => $sacado_endereco_numero,
					        'sacado_endereco_bairro' => $sacado_endereco_bairro,
					        'sacado_endereco_cep' => $sacado_endereco_cep,
					        'sacado_endereco_cidade' => $sacado_endereco_cidade,
					        'sacado_endereco_complemento' => $sacado_endereco_complemento,
					        'sacado_endereco_logradouro' => $sacado_endereco_logradouro,
					        'sacado_endereco_pais' => $sacado_endereco_pais,
					        'sacado_endereco_uf' => $sacado_endereco_uf,
					        'sacado_nome' => $sacado_nome,
				        'titulo_data_emissao' => $titulo_data_emissao,
				        'titulo_data_vencimento' => $titulo_data_vencimento,
				        'titulo_mensagem_01' => $titulo_mensagem_01,
				        'titulo_mensagem_02' => $titulo_mensagem_02,
				        'titulo_nosso_numero' => $titulo_nosso_numero,
			        	'titulo_numero_documento' => $titulo_numero_documento,
				        'titulo_valor' => $titulo_valor,
				        'titulo_local_pagamento' => $titulo_local_pagamento,
				        'titulo_aceite' => $titulo_aceite,
				        'titulo_doc_especie' => $titulo_doc_especie,
				        'situacao' => $situacao,
			            'id_usuario' => $id_usuario,
                        'remessa_pendente' => '0'

				    );
				        $id_boleto = DBCreate('', 'tb_boleto', $dados, true);
				        registraLog('Inserção de boleto.','i','tb_boleto',$id_boleto,"id_pessoa: $id_pessoa | cedente_conta_numero: $cedente_conta_numero | cedente_conta_numero_dv: $cedente_conta_numero_dv | cedente_convenio_numero: $cedente_convenio_numero | cedente_conta_codigo_banco: $cedente_conta_codigo_banco | sacado_cpf_cnpj: $sacado_cpf_cnpj | sacado_endereco_numero: $sacado_endereco_numero | sacado_endereco_bairro: $sacado_endereco_bairro | sacado_endereco_cep: $sacado_endereco_cep | sacado_endereco_cidade: $sacado_endereco_cidade | sacado_endereco_complemento: $sacado_endereco_complemento | sacado_endereco_logradouro: $sacado_endereco_logradouro | sacado_endereco_pais: $sacado_endereco_pais | sacado_endereco_uf: $sacado_endereco_uf | sacado_nome: $sacado_nome | titulo_data_emissao: $titulo_data_emissao | titulo_data_vencimento: $titulo_data_vencimento | titulo_mensagem_01: $titulo_mensagem_01 | titulo_mensagem_02: $titulo_mensagem_02 | titulo_nosso_numero: $titulo_nosso_numero | titulo_valor: $titulo_valor | titulo_local_pagamento: $titulo_local_pagamento | titulo_aceite: $titulo_aceite | titulo_doc_especie: $titulo_doc_especie | situacao: $situacao | id_usuario: $id_usuario");

			        //incluir boleto
				        $parametros = '
				        [
					        {
						        "CedenteContaNumero": "'.$cedente_conta_numero.'",
						        "CedenteContaNumeroDV": "'.$cedente_conta_numero_dv.'",
						        "CedenteConvenioNumero": "'.$cedente_convenio_numero.'",
						        "CedenteContaCodigoBanco": "'.$cedente_conta_codigo_banco.'",
						        "SacadoCPFCNPJ": "'.$sacado_cpf_cnpj.'",
						        "SacadoEnderecoNumero": "'.$sacado_endereco_numero.'",
						        "SacadoEnderecoBairro": "'.$sacado_endereco_bairro.'",
						        "SacadoEnderecoCEP": "'.$sacado_endereco_cep.'",
						        "SacadoEnderecoCidade": "'.$sacado_endereco_cidade.'",
						        "SacadoEnderecoComplemento": "'.$sacado_endereco_complemento.'",
						        "SacadoEnderecoLogradouro": "'.$sacado_endereco_logradouro.'",
						        "SacadoEnderecoPais": "'.$sacado_endereco_pais.'",
						        "SacadoEnderecoUF": "'.$sacado_endereco_uf.'",
						        "SacadoNome": "'.$sacado_nome.'",
						        "SacadoTelefone": "5532819200",
						        "TituloDataEmissao": "'.converteData($titulo_data_emissao).'",
						        "TituloDataVencimento": "'.converteData($titulo_data_vencimento).'",
						        "TituloMensagem01": "'.$titulo_mensagem_01.'",
						        "TituloMensagem02": "'.$titulo_mensagem_02.'",
						        "TituloNossoNumero": "'.$titulo_nosso_numero.'",
						        "TituloNumeroDocumento": "'.$titulo_numero_documento.'",
						        "TituloValor": "'.str_replace('.', ',', sprintf("%01.2f", $titulo_valor)).'",
						        "TituloLocalPagamento": "'.$titulo_local_pagamento.'",
						        "TituloAceite": "'.$titulo_aceite.'",
			                    "TituloDocEspecie": "'.$titulo_doc_especie.'",
			                    "TituloCodigoMulta": "1",
			                    "TituloValorMultaTaxa": "'.str_replace('.', ',', $titulo_valor_multa_taxa).'",                  
			                    "TituloDataMulta": "'.converteData($titulo_data_multa_juros).'",
			                    "TituloCodigoJuros": "2",
			                    "TituloValorJuros": "0,03",                  
			                    "TituloDataJuros": "'.converteData($titulo_data_multa_juros).'"
					        }
				        ]
                        ';

                        $dados_json = array(
                            'json' => $parametros
                        );
                        DBUpdate('', 'tb_boleto', $dados_json, "id_boleto = $id_boleto");

				        $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));
					
						if(!$resultado['_dados']['_sucesso']){
							$dados_situacao = array(
					        	'situacao' => 'FALHA'
					    	);

					    	DBUpdate('', 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                            registraLog('Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: FALHA");
                            
                            $falhas = '';
                            if($resultado['_dados']['_falha']){
                                foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	                                    
                                    if($conteudo_resultado['_erro']){
                                        foreach ($conteudo_resultado['_erro']['erros'] as $conteudo) {
                                            $falhas.= $conteudo.' ';
                                        }
                                    }
                                }
                            }

                            $alerta_erros_boletos[$id_conta_receber] = $falhas;
						}else{
							$id_integracao = $resultado['_dados']['_sucesso'][0]['idintegracao'];
							$dados_id_integrcao = array(
					        	'id_integracao' => $id_integracao
					    	);
					    	DBUpdate('', 'tb_boleto', $dados_id_integrcao, "id_boleto = $id_boleto");
			        		registraLog('Inserçao id_integracao boleto.','a','tb_boleto',$id_boleto,"id_integracao: $id_integracao");

			        		$dados_configuracao_boleto = array(
					        	'nosso_numero' => $proximo_titulo_nosso_numero,
					        	'numero_documento' => $proximo_titulo_numero_documento
					    	);
                            DBUpdate('', 'tb_boleto_configuracao', $dados_configuracao_boleto, "");
                            
                            $dados = array(
                                'id_boleto' => $id_boleto    
                            );
                            DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");

					    }
						
					//fim incluir boleto
			      
				}else{
			    	$descricao_erro = '';
			    	if(!$sacado_nome){
			    		$descricao_erro .= 'Nome inválido';
			    	}
			    	if(!$sacado_cpf_cnpj){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'CNPJ/CPF inválido';
			    		}else{
			    			$descricao_erro .= ', CNPJ/CPF inválido';
			    		}
			    	}
			    	if(!$sacado_endereco_uf){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'UF inválida';
			    		}else{
			    			$descricao_erro .= ', UF inválida';
			    		}
			    	}
			    	if(!$sacado_endereco_cidade || $sacado_endereco_cidade == 'Não Definida'){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Cidade inválida';
			    		}else{
			    			$descricao_erro .= ', cidade inválida';
			    		}
			    	}
			    	if(!$sacado_endereco_logradouro){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Logradouro inválido';
			    		}else{
			    			$descricao_erro .= ', logradouro inválido';
			    		}
			    	}
			    	if(!$sacado_endereco_numero){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Número do logradouro inválido';
			    		}else{
			    			$descricao_erro .= ', número do logradouro inválido';
			    		}
			    	}
			    	if(!$sacado_endereco_bairro){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Bairro do logradouro inválido';
			    		}else{
			    			$descricao_erro .= ', bairro do logradouro inválido';
			    		}
			    	}
			    	if(!$sacado_endereco_cep){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'CEP do logradouro inválido';
			    		}else{
			    			$descricao_erro .= ', CEP do logradouro inválido';
			    		}
			    	}
			    	if(!$titulo_valor || $titulo_valor < 0){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Valor do boleto inválido';
			    		}else{
			    			$descricao_erro .= ', valor do boleto inválido';
			    		}
			    	}
			    	$descricao_erro .= '.';
                    $alerta_erros_boletos[$id_conta_receber] = $descricao_erro;
			    }

			//FIM GERAR BOLETO

		}else{
			$id_boleto = NULL;
		}

		if($emitir_nfs == 1 && $numero_parcela == 1){
			//só na parcela 1

			//INICIO NFS-E
				$cliente_id_cidade = $dados_empresa[0]['id_cidade'];
				$cliente_logradouro = $dados_empresa[0]['logradouro'];
				$cliente_numero = $dados_empresa[0]['numero'];
				$cliente_complemento = $dados_empresa[0]['complemento'];
			    if(!$cliente_complemento){
			        $cliente_complemento = '-';
			    }
				$cliente_bairro = $dados_empresa[0]['bairro'];
				$cliente_cep = $dados_empresa[0]['cep'];
				$cliente_uf = $dados_empresa[0]['uf'];
				
				$cliente_razao_social = $dados_empresa[0]['razao_social'];
				$cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];

			    $data_criacao = getDataHora();
				
				$tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

                $tipo_nota = 'NFS-e';

		        //BELLUNO
                if($dados_empresa[0]['cod_servico']){
                    $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 0");
                }else{
                    $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = 'call_suporte' AND adesao = 0");
                }

                $codigo_servico_municipio = $dados_faturamento_configuracao[0]['codigo_servico_municipio'];
                $item_lista_servico = $dados_faturamento_configuracao[0]['item_lista_servico'];
                $descricao_servico_municipio = $dados_faturamento_configuracao[0]['descricao_servico_municipio'];                

			    $id_usuario = $_SESSION['id_usuario'];

                $descricao = "FATURA EM ABERTO, AGUARDAMOS SEU PAGAMENTO VIA BOLETO BANCÁRIO. \r\n".$descricao;

			    

			    //nota
			   
			    if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
				    $dados = array(
				        'cliente_id_cidade' => $cliente_id_cidade,
				        'cliente_logradouro' => $cliente_logradouro,
				        'cliente_numero' => $cliente_numero,
				        'cliente_complemento' => $cliente_complemento,
				        'cliente_bairro' => $cliente_bairro,
				        'cliente_cep' => $cliente_cep,
				        'id_pessoa' => $id_pessoa,
				        'data_criacao' => $data_criacao,
				        'descricao' => $descricao,
			            'valor_total' => $valor_total,
			            'valor_pis' => $valor_pis,
			            'valor_cofins' => $valor_cofins,
			            'valor_csll' => $valor_csll,
			            'valor_ir' => $valor_ir,
			            'codigo_servico_municipio' => $codigo_servico_municipio,
			            'descricao_servico_municipio' => $descricao_servico_municipio,
			            'item_lista_servico' => $item_lista_servico,
			            'cliente_razao_social' => $cliente_razao_social,
			            'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
			            'tipo_pessoa' => $tipo_pessoa,
			            'id_usuario' => $id_usuario

				    );
		            $id_externo = DBCreate('', 'tb_nfs', $dados, true);
		            registraLog('Inserção de nota fiscal.','i','tb_nfs',$id_externo,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario");
				   	
			        $dados_nao_enviado = array(
				        'status' => 'nao enviado'
				    );

			        DBUpdate('', 'tb_nfs', $dados_nao_enviado, "id_nfs = $id_externo");
			        registraLog('Alteração status nota fiscal, nao enviado.','a','tb_nfs',$id_externo,"status: nao enviado");

			        try{
			            $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
			                'tipo' => $tipo_nota,
			                'idExterno' => "$id_externo",
			                'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'		
			                'cliente' => array(
			                    'nome' => $cliente_razao_social,
			                    'cpfCnpj' => $cliente_cpf_cnpj,
			                    'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
			                    'endereco' => array(
			                        'uf' => $cliente_uf, 
			                        'cidade' => $cliente_id_cidade,
			                        'logradouro' => $cliente_logradouro,
			                        'numero' => $cliente_numero,
			                        'complemento' => $cliente_complemento,
			                        'bairro' => $cliente_bairro,
			                        'cep' => $cliente_cep
			                    )
			                ),
			                
			                'servico' => array(
			                    'descricao' => $descricao,
			                    'issRetidoFonte' => false,
			                    'valorPis' => $valor_pis,
			                    'valorCofins' => $valor_cofins,
			                    'valorCsll' => $valor_csll,
			                    'valorIr' => $valor_ir,
			                    'codigoServicoMunicipio' => $codigo_servico_municipio,
			                    'descricaoServicoMunicipio' => $descricao_servico_municipio,
			                    'itemListaServicoLC116' => $item_lista_servico
			                ),

			                'valorTotal' => $valor_total
			            ));

			        	$dados_inserindo = array(
					        'status' => 'inserindo'
                        );
                        
				        DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_externo");
                        registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_externo,"status: inserindo");
                        
                        $dados = array(
                            'id_nfs' => $id_externo    
                        );
                        DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
			        
			        }catch(Exceptions\invalidApiKeyException $ex) {
                        $alerta_erro_nfs = 'Não foi possível gerar a nota fiscal! Erro de autenticação'.$ex->getMessage().'';
			        }catch(Exceptions\unauthorizedException $ex) {
                        $alerta_erro_nfs = 'Não foi possível gerar a nota fiscal! Acesso negado'.$ex->getMessage().'';
			        }catch(Exceptions\apiException $ex) {
                        $alerta_erro_nfs= 'Não foi possível gerar a nota fiscal! Erro de validação'.$ex->getMessage().'';
			        }catch(Exceptions\requestException $ex) {			           
                        $alerta_erro_nfs = 'Não foi possível gerar a nota fiscal! Erro de requisição!';  
			        }
			    // fim nota
			    
			    }else{
			    	$descricao_erro = '';
			    	if(!$cliente_razao_social){
			    		$descricao_erro .= 'Nome inválido';
			    	}
			    	if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'CNPJ/CPF inválido';
			    		}else{
			    			$descricao_erro .= ', CNPJ/CPF inválido';
			    		}
			    	}
			    	if(!$cliente_uf){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'UF inválida';
			    		}else{
			    			$descricao_erro .= ', UF inválida';
			    		}
			    	}
			        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Cidade inválida';
			    		}else{
			    			$descricao_erro .= ', cidade inválida';
			    		}
			    	}
			    	if(!$cliente_logradouro){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Logradouro inválido';
			    		}else{
			    			$descricao_erro .= ', logradouro inválido';
			    		}
			    	}
			    	if(!$cliente_numero){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Número do logradouro inválido';
			    		}else{
			    			$descricao_erro .= ', número do logradouro inválido';
			    		}
			    	}
			    	if(!$cliente_bairro){
			    		if($descricao_erro == ''){
			    			$descricao_erro .= 'Bairro inválido';
			    		}else{
			    			$descricao_erro .= ', bairro do logradouro inválido';
			    		}
			    	}
			    	$descricao_erro .= '.';
                    $alerta_erro_nfs = $descricao_erro;  
			    }

			//FIM NFS-E


		}
		
		$numero_parcela++;
	}
    

    if($alerta_erros_boletos || $alerta_erro_nfs !== ''){
        $mensagem_erros = '<br>';
        if($alerta_erro_nfs !== ''){
            $mensagem_erros .= 'Erro na NFS-e: '.$alerta_erro_nfs.'<br>';
        }

        if($alerta_erros_boletos){
            $mensagem_erros .= 'Erro(s) no(s) Boleto(s) da(s) Conta(s) a Receber:<br>';
            foreach ($alerta_erros_boletos as $id_conta_receber => $conteudo) {
                $mensagem_erros .= '#'.$id_conta_receber.': '.$conteudo.'<br>';                
            }
        }
        
        $alert = ('Item inserido com sucesso, porém ouveram erros!'.$mensagem_erros,'w');
        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
        exit;
    }else{
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
        exit;
    }
}

function baixar_conta_receber($selecionar, $baixa_nota, $descricao_baixar){
        
    foreach ($selecionar as $conteudo_conta) {
        
        $link = DBConnect('');
        DBBegin($link);

        $dados_busca_selecionar = DBReadTransaction($link, 'tb_conta_receber', "WHERE id_conta_receber = '".$conteudo_conta."' ");
    	if($dados_busca_selecionar[0]['situacao'] == 'aberta'){
            if($baixa_nota == 1){

                if(!$dados_busca_selecionar[0]['id_conta_pai']){
                    $id_conta_pai = $dados_busca_selecionar[0]['id_conta_receber'];
                }else{
                    $id_conta_pai = $dados_busca_selecionar[0]['id_conta_pai'];
                }

                $dados_busca_filho = DBReadTransaction($link, 'tb_conta_receber', "WHERE id_conta_pai = '".$id_conta_pai."' OR id_conta_receber = '".$id_conta_pai."' ");

                foreach ($dados_busca_filho as $conteudo_busca_filho) {

                        $data_criacao_compara = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($conteudo_busca_filho['data_cadastro'])));
                        $data_hoje = date('Y-m-d H:i:s', strtotime("+0 days",strtotime(getDataHora())));

                        $total = (strtotime($data_criacao_compara) - strtotime($data_hoje))/3600;
                        $total = $total*(-1);

                    if($conteudo_busca_filho['situacao'] != 'aberta'){
                        $alert = ('Não foi possivel cancelar a nota referente a #'.$conteudo_conta.', conta a receber vinculada já está quitada!','w');
                        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
                        exit;
                    
                    }else if($total >= 24){
                        $alert = ('Não foi possivel cancelar a nota referente a #'.$conteudo_conta.', já se passaram mais de 24 horas!','w');
                        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
                        exit;

                    }else{

                        if($conteudo_busca_filho['id_conta_receber'] == $id_conta_pai){
                            if($conteudo_busca_filho['id_nfs']){
                                echo "id_nfs: ".$conteudo_busca_filho['id_nfs'];

                                $id = $conteudo_busca_filho['id_nfs'];
                                $dados =DBReadTransaction($link, 'tb_nfs', "WHERE id_nfs = '".$id."' ");
                                if($dados[0]['status'] != 'autorizada'){
                                    if($dados[0]['status'] == 'cancelada'){
                                        $alert = ('Erro: A nota referente a #'.$conteudo_conta.' já esta cancelada!','w');
                                    }else if($dados[0]['status'] == 'nao enviada'){
                                        $alert = ('Erro: A nota referente a #'.$conteudo_conta.' não foi enviada!','w');
                                    }else if($dados[0]['status'] == 'cancelando'){
                                        $alert = ('Erro: A nota referente a #'.$conteudo_conta.' já esta em processo de cancelamento!','w');
                                    }else if($dados[0]['status'] == 'inserindo'){
                                        $alert = ('Erro: A nota referente a #'.$conteudo_conta.' ainda não foi processada!','w');
                                    }
                                }else{
                                    try{
                                        eNotasGW::$NFeApi->cancelarPorIdExterno(getDadosApiNfs('empresaId'), $id);

                                        $id_usuario = $_SESSION['id_usuario'];
                                        $dados = array(
                                            'status' => 'cancelando',
                                            'id_usuario' => $id_usuario
                                        );

                                        DBUpdateTransaction($link, 'tb_nfs', $dados, "id_nfs = $id");
                                        registraLogTransaction($link, 'Alteração status nota fiscal, cancelando.','a','tb_nfs',$id,"status: cancelando | id_usuario: $id_usuario");
                                        $alert = ('Cancelamento solicitado com sucesso!','s');

                                    }catch(Exceptions\invalidApiKeyException $ex) {
                                        $alert = ('Erro de autenticação: </br></br>'.$ex->getMessage().'','w');
                                        
                                    }catch(Exceptions\unauthorizedException $ex) {
                                        $alert = ('Acesso negado: </br></br>'.$ex->getMessage().'','w');

                                    }catch(Exceptions\apiException $ex) {
                                        $alert = ('Erro de validação: </></>'.$ex->getMessage().'','w');

                                    }catch(Exceptions\requestException $ex) {
                                        $alert = ('Erro na requisição web: </br></br>Requested url: ' . $ex->requestedUrl.'</br>Response Code: ' . $ex->getCode().'</br>Message: ' . $ex->getMessage().'</br>Response Body: ' . $ex->responseBody.'','w');
                                    }
                                }
                            }
                        }

                    }
                }

            }
            $dados = array(
                'situacao' => 'baixada'
            );
            DBUpdateTransaction($link, 'tb_conta_receber', $dados, "id_conta_receber = $conteudo_conta");
            registraLogTransaction($link, 'Baixa conta receber.','a','tb_conta_receber',$conteudo_conta,"situacao: baixada");

            $id_usuario = $_SESSION['id_usuario'];
            $data_hora = getDataHora();

            $justificativa = $descricao_baixar;

            $dados = array(
                'id_conta' => $conteudo_conta,
                'tipo' => 'conta_receber',
                'id_usuario' => $id_usuario,
                'data' => $data_hora,
                'justificativa' => $justificativa
            );
            $insertID = DBCreateTransaction($link, 'tb_conta_baixa', $dados, true);
            registraLogTransaction($link, 'Inserção de conta de baixa.','i','tb_conta_baixa',$insertID,"id_conta: $conteudo_conta | tipo: conta_receber | id_usuario: $id_usuario | data: $data_hora | justificativa: $justificativa");

            //INICIO BOLETO

                $dados_boleto = DBReadTransaction($link, 'tb_conta_receber a', "INNER JOIN tb_boleto b ON a.id_boleto = b.id_boleto WHERE a.id_conta_receber = '".$conteudo_conta."' AND b.situacao != 'REJEITADO' AND b.situacao != 'FALHA'", "b.id_boleto");
                $id_boleto = $dados_boleto[0]['id_boleto'];
                if($id_boleto){
                    $dados_situacao = array(
                        'situacao' => 'BAIXA PENDENTE',
                        'remessa_pendente' => 1
                    );

                    DBUpdateTransaction($link, 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                    registraLogTransaction($link, 'Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: BAIXA PENDENTE");
                }
                
            //FIM BOLETO
        }
        DBCommit($link);
    }
    
    $alert = ('Baixa inserida com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function quitar_conta_receber($selecionar, $data_pagamento){

    foreach ($selecionar as $conteudo_conta) {
    	
    	$link = DBConnect('');
		DBBegin($link);
        
        $dados_conta = DBReadTransaction($link, 'tb_conta_receber', "WHERE id_conta_receber = '".$conteudo_conta."' ");
        
        if($dados_conta[0]['situacao'] == 'aberta'){
            //Inicio tb_caixa_movimentacao

                $tipo = 'entrada';
                $valor = $dados_conta[0]['valor'];
                $data_movimentacao = converteData($data_pagamento);
                $origem = 'conta_receber';
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
            
            //Inicio tb_conta_receber

                $data_pagamento_conta_receber = converteData($data_pagamento);

                $dados = array(
                    'data_pagamento' => $data_pagamento_conta_receber,
                    'situacao' => 'quitada',
                    'id_caixa_movimentacao' => $insertID
                );
                DBUpdateTransaction($link, 'tb_conta_receber', $dados, "id_conta_receber = $conteudo_conta");
                registraLogTransaction($link, 'Quitar conta receber.','a','tb_conta_receber',$conteudo_conta,"data_pagamento: $data_pagamento_conta_receber | situacao: quitada | id_caixa_movimentacao: $insertID");
            //Fim tb_conta_receber
        }
   		DBCommit($link);
    }

    $alert = ('Quitação realizada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function ignorar_email_conta_receber($id){
    
    $link = DBConnect('');
    DBBegin($link);
    
    $dados = array(
        'envio_email' => 2
    );
    DBUpdateTransaction($link, 'tb_conta_receber', $dados, "id_conta_receber = $id");
    registraLogTransaction($link, 'Ignorar envio de email conta receber.','a','tb_conta_receber',$id,"envio_email: 2");
    
    DBCommit($link);
    
    $alert = ('Conta a receber ignorada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function enviar_email_conta_receber($assunto, $descricao_email, $selecionar_conta_receber, $envia_nfs, $envia_xml, $envia_boleto){
    $cont = 0;
    $conta_receber_erros = '';
    
    $id_usuario = $_SESSION['id_usuario'];
    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_perfil_sistema c ON a.id_perfil_sistema = c.id_perfil_sistema WHERE a.id_usuario = '".$id_usuario."' LIMIT 1", "b.nome, c.nome as cargo");
    $nome_funcionario = $dados_usuario[0]['nome'];
    $cargo_funcionario = $dados_usuario[0]['cargo'];

   	foreach ($selecionar_conta_receber as $conteudo_selecionar_conta_receber) {

		$dados_conta_receber = DBRead('', 'tb_conta_receber', "WHERE id_conta_receber = '".$conteudo_selecionar_conta_receber."' ");

		if($dados_conta_receber[0]['id_boleto']){
			$dados_boleto = DBRead('', 'tb_boleto', "WHERE id_boleto = '".$dados_conta_receber[0]['id_boleto']."' AND (situacao = 'REGISTRADO' OR situacao = 'LIQUIDADO') ");
		}

		if($dados_conta_receber[0]['id_nfs']){
			$dados_nfs = DBRead('', 'tb_nfs', "WHERE id_nfs = '".$dados_conta_receber[0]['id_nfs']."' AND status = 'autorizada'");
		}

		$contato_email = '';
		$flag = 0;

		$mensagem = '';
        // <a href="https://www.bellunotec.com.br" target="_blank"><img src="https://rh.bellunotec.com.br/inc/keen/theme/classic/assets/media/logos/logobranco-1.png" style="padding: 10px 10px 10px 10px;"></a>

        

		if( (($envia_nfs && $dados_nfs)  || !$envia_nfs) && (($envia_xml && $dados_nfs) || !$envia_xml) && (($envia_boleto && $dados_boleto) || !$envia_boleto) ){
			//Mensagem
            $flag_modelo_email = 0;
            if (preg_match("/{<nome_usuario>}/", $descricao_email)) {
                $flag_modelo_email = 1;

                $descricao_email = str_replace("<nome_usuario>", $nome_funcionario, $descricao_email);
                $descricao_email = str_replace("<cargo_usuario>", $cargo_funcionario, $descricao_email);
                if( (($envia_nfs && $dados_nfs) ) || (($envia_xml && $dados_nfs) ) || (($envia_boleto && $dados_boleto) ) ){

                    if($envia_nfs && $dados_nfs){
                        $descricao_email = str_replace("<link_nota_fiscal>",'<a href="'.$dados_nfs[0]['link_pdf'].'" style="margin-top: 10px ;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #04B45F; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para NFS-e</a>', $descricao_email);
                    }
                    if($envia_xml && $dados_nfs){
                        $descricao_email = str_replace("<link_xml>",'<a href="'.$dados_nfs[0]['link_xml'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #8A2908; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para XML</a>', $descricao_email);
                    }
                    if($envia_boleto && $dados_boleto){
                        $descricao_email = str_replace("<link_boleto>",'<a href="https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #610B38; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para Boleto</a>', $descricao_email);
                    }                      
                }
            }
            

				$mensagem = 
				'<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none; background-color: #0A122A; border-top-left-radius: 6px; border-top-right-radius: 6px;">
	                <tr style="border: none;">
	                    <td width="260" valign="top" style="border: none;">
	                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none;">
	                            <tr>
	                                <td>
	                                    <a href="https://www.bellunotec.com.br" target="_blank"><img src="https://rh.belluno.company/inc/keen/theme/classic/assets/media/logos/logobranco-1.png" style="padding: 10px 10px 10px 10px;"></a>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                </tr>
	            </table>
	            <tr>
	                <td width="8" style="width: 8px">
	                </td>
	                <td>
	                    <div style="border-style: solid; border-width: thin; border-color: #dadce0; border-radius: 0px; padding: 40px 20px" align="center" class="mdv2rw">
	                        <div style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 14px; color: rgba(0,0,0,0.87); line-height: 20px; padding-top: 20px; text-align: left">
	                            <div class="row">
		                            <div class="col-md-12">
		                                <span style="padding-bottom: 24px; text-align: start">'.nl2br($descricao_email).'</span>
		                            </div>
								</div>
								<br>
	                            <table style="font-size: 14px; letter-spacing: 0.2; line-height: 20px; text-align: center">
	                                <tbody>';
                                        if($flag_modelo_email == 0){
                                            if( (($envia_nfs && $dados_nfs) ) || (($envia_xml && $dados_nfs) ) || (($envia_boleto && $dados_boleto) ) ){
                                                $mensagem .= '<tr><td>';
    
                                                if($envia_nfs && $dados_nfs){
                                                    $mensagem .='
                                                        <a href="'.$dados_nfs[0]['link_pdf'].'" style="margin-top: 10px ;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #04B45F; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para NFS-e
                                                        </a>
                                                    ';
                                                }
                                                if($envia_xml && $dados_nfs){
                                                    $mensagem .='
                                                        <a href="'.$dados_nfs[0]['link_xml'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #8A2908; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para XML
                                                        </a>
                                                    ';
                                                }
                                                if($envia_boleto && $dados_boleto){
                                                    $mensagem .='
                                                        <a href="https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #610B38; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para Boleto
                                                        </a>
                                                    ';
                                                }
    
                                                $mensagem .= '</td></tr><tr style="font-size: 12px; line-height: 150%; text-align: center">';
                                                $mensagem .= '<td style="padding-top: 12px;"><span style="color: rgba(0, 0, 0, 0.54);">Também pode acessar diretamente pelo link:</span><br>';
                                                        
    
                                                if($envia_nfs && $dados_nfs){
                                                    $mensagem .='
                                                    <strong>NFS-e: </strong><a href="'.$dados_nfs[0]['link_pdf'].'" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit;">'.$dados_nfs[0]['link_pdf'].'</a><br>';
                                                }
                                                if($envia_xml && $dados_nfs){
                                                    $mensagem .='
                                                    <strong>XML: </strong><a href="'.$dados_nfs[0]['link_xml'].'" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit;">'.$dados_nfs[0]['link_xml'].'</a><br>';
                                                }
                                                if($envia_boleto && $dados_boleto){
                                                    $mensagem .='
                                                    <strong>Boleto: </strong><a href="https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit;">https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'"</a>';
                                                } 
                                                
                                                $mensagem .= '</td></tr>';
                                            }
                                        }
	                                	

	                                $mensagem .= 
	                                '</tbody>
	                            </table>
	                        </div>
	                    </div>
				        <div style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; color: rgba(0,0,0,0.54); font-size: 11px; line-height: 18px; padding-top: 12px; text-align: center">
				            <div style="direction: ltr"; font-family: Roboto-Regular,Helvetica,Arial,sans-serif; color: rgba(0,0,0,0.54); font-size: 11px; line-height: 18px; padding-top: 12px; text-align: center>© 2019 Belluno Tecnologia, Caçapava do Sul, Rio Grande do Sul, Brasil
				            </div>
				        </div>
	                </td>
	                <td width="8" style="width: 8px"></td>
	            </tr>';

			//Mensagem

			if($dados_conta_receber[0]['id_contrato_plano_pessoa']){
				$dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$dados_conta_receber[0]['id_contrato_plano_pessoa']."' ");
	            $contato_email = $dados_contrato_plano_pessoa[0]['email_nf'];
	            if($contato_email){
	                $flag++;
	            }
			}else{

				$dados_pessoa =DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados_conta_receber[0]['id_pessoa']."' ");

				if($dados_pessoa[0]['email1'] || $dados_pessoa[0]['email2']){
					$flag++;

					if($dados_pessoa[0]['email1']){
						$contato_email = $dados_pessoa[0]['email1'];
					}
					if($dados_pessoa[0]['email2']){
						if($dados_pessoa[0]['email1']){
							$contato_email .= ';'.$dados_pessoa[0]['email2'];
						}else{
							$contato_email = $dados_pessoa[0]['email2'];
						}
					}
				}
			}
 		}
		if($contato_email && $contato_email != '' && $mensagem !== ''){
    		envia_email($assunto, $mensagem, $contato_email, 'financeiro');
    		$dados = array(
		        'envio_email' => 1
		    );
		    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $conteudo_selecionar_conta_receber");
		    registraLog('Email enviado conta receber.','a','tb_conta_receber',$conteudo_selecionar_conta_receber,"envio_email: 1");
		}
		if($flag == 0){

			$cont++;

			if(!$conta_receber_erros){
				$conta_receber_erros = '#'.$conteudo_selecionar_conta_receber;
			}else{
				$conta_receber_erros .= ', #'.$conteudo_selecionar_conta_receber;
			}

		}

	}

    if($cont == 0){
    	$alert = ('E-mail(s) enviados com sucesso!','s');
    }else{
    	$alert = ('Não foi possível enviar e-mail(s) para '.$cont.' conta(s) a receber!<br>Ids das contas: '.$conta_receber_erros,'w');
    }
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function alterar_vencimento_conta_receber($nova_data_vencimento, $id_conta_receber_altera_vencimento){

    $nova_data_vencimento = converteData($nova_data_vencimento);
    
    $link = DBConnect('');
    DBBegin($link);
    
    $dados = array(
        'data_vencimento' => $nova_data_vencimento
    );
    DBUpdateTransaction($link, 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber_altera_vencimento");
    registraLogTransaction($link, 'Alteracao de data de vencimento de conta receber.','a','tb_conta_receber',$id_conta_receber_altera_vencimento,"data_vencimento: $nova_data_vencimento");
    
	$dados_boleto = DBReadTransaction($link, 'tb_conta_receber', "WHERE id_conta_receber = '".$id_conta_receber_altera_vencimento."' ");
	$id_boleto = $dados_boleto[0]['id_boleto'];
	if($id_boleto){
		$dados = array(
	        'titulo_data_vencimento' => $nova_data_vencimento,
	        'situacao' => 'ALTERACAO VENCIMENTO PENDENTE',
	        'remessa_pendente' => 1
	    );
	    DBUpdateTransaction($link, 'tb_boleto', $dados, "id_boleto = $id_boleto");
	    registraLogTransaction($link, 'Alteracao de data de vencimento de boleto.','a','tb_boleto',$id_boleto,"titulo_data_vencimento: $nova_data_vencimento | remessa_pendente: 1");
	}

    DBCommit($link);
    
    $alert = ('Alteração de vencimento realizada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function reprocessar_nfs($id_conta_receber){

    $id_usuario = $_SESSION['id_usuario'];

    $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_conta_receber = '$id_conta_receber'");
    $id_pessoa = $dados_conta_receber[0]['id_pessoa'];
    $id_nfs = $dados_conta_receber[0]['id_nfs'];

    $dados_empresa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado WHERE a.id_pessoa = '".$id_pessoa."'", "a.*, b.id_cidade, b.nome AS 'nome_cidade', c.sigla AS 'uf'");

    $cliente_id_cidade = $dados_empresa[0]['id_cidade'];
    $cliente_logradouro = $dados_empresa[0]['logradouro'];
    $cliente_numero = $dados_empresa[0]['numero'];
    $cliente_complemento = $dados_empresa[0]['complemento'];
    if(!$cliente_complemento){
        $cliente_complemento = '-';
    }
    $cliente_bairro = $dados_empresa[0]['bairro'];
    $cliente_cep = $dados_empresa[0]['cep'];
    $cliente_uf = $dados_empresa[0]['uf'];
    
    $cliente_razao_social = $dados_empresa[0]['razao_social'];
    $cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
    
    $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

    $data_criacao = getDataHora();

    $tipo_nota = 'NFS-e';

    $dados_reprocessar = DBRead('', 'tb_nfs',"WHERE id_nfs = '".$id_nfs."' ");

    $valor_total = $dados_reprocessar[0]['valor_total'];

    $valor_cofins = $dados_reprocessar[0]['valor_cofins'];
    $valor_csll = $dados_reprocessar[0]['valor_csll'];
    $valor_ir = $dados_reprocessar[0]['valor_ir'];
    $valor_pis = $dados_reprocessar[0]['valor_pis'];

    $codigo_servico_municipio = $dados_reprocessar[0]['codigo_servico_municipio'];
    $item_lista_servico = $dados_reprocessar[0]['item_lista_servico'];
    $descricao_servico_municipio = $dados_reprocessar[0]['descricao_servico_municipio'];

    $descricao = $dados_reprocessar[0]['descricao'];

    //nota
   
    if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
        
        $dados = array(
	        'cliente_id_cidade' => $cliente_id_cidade,
	        'cliente_logradouro' => $cliente_logradouro,
	        'cliente_numero' => $cliente_numero,
	        'cliente_complemento' => $cliente_complemento,
	        'cliente_bairro' => $cliente_bairro,
	        'cliente_cep' => $cliente_cep,
	        'id_pessoa' => $id_pessoa,
	        'data_criacao' => $data_criacao,
	        'descricao' => $descricao,
            'valor_total' => $valor_total,
            'valor_pis' => $valor_pis,
            'valor_cofins' => $valor_cofins,
            'valor_csll' => $valor_csll,
            'valor_ir' => $valor_ir,
            'codigo_servico_municipio' => $codigo_servico_municipio,
            'descricao_servico_municipio' => $descricao_servico_municipio,
            'item_lista_servico' => $item_lista_servico,
            'cliente_razao_social' => $cliente_razao_social,
            'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
            'tipo_pessoa' => $tipo_pessoa,
            'id_usuario' => $id_usuario,
            'status' => 'nao enviado'

        );

        DBUpdate('', 'tb_nfs', $dados, "id_nfs = $id_nfs");
        registraLog('Reprocessamento de nota fiscal.','a','tb_nfs',$id_nfs,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario | status: nao enviado");
       

        try{
            $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
                'tipo' => $tipo_nota,
                'idExterno' => "$id_nfs",
                'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'		
                'cliente' => array(
                    'nome' => $cliente_razao_social,
                    'cpfCnpj' => $cliente_cpf_cnpj,
                    'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
                    'endereco' => array(
                        'uf' => $cliente_uf, 
                        'cidade' => $cliente_id_cidade,
                        'logradouro' => $cliente_logradouro,
                        'numero' => $cliente_numero,
                        'complemento' => $cliente_complemento,
                        'bairro' => $cliente_bairro,
                        'cep' => $cliente_cep
                    )
                ),
                
                'servico' => array(
                    'descricao' => $descricao,
                    'issRetidoFonte' => false,
                    'valorPis' => $valor_pis,
                    'valorCofins' => $valor_cofins,
                    'valorCsll' => $valor_csll,
                    'valorIr' => $valor_ir,
                    'codigoServicoMunicipio' => $codigo_servico_municipio,
                    'descricaoServicoMunicipio' => $descricao_servico_municipio,
                    'itemListaServicoLC116' => $item_lista_servico
                ),

                'valorTotal' => $valor_total
            ));

            $dados_inserindo = array(
                'status' => 'inserindo'
            );
            
            DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_nfs");
            registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_nfs,"status: inserindo");
        
        }catch(Exceptions\invalidApiKeyException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Erro de autenticação<br>'.$ex->getMessage().'','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;
        }catch(Exceptions\unauthorizedException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Acesso negado<br>'.$ex->getMessage().'','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;
        }catch(Exceptions\apiException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Erro de validação<br>'.$ex->getMessage().'','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;
        }catch(Exceptions\requestException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Erro de requisição!','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;         
        }
    // fim nota
    
    }else{
        $descricao_erro = '';
        if(!$cliente_razao_social){
            $descricao_erro .= 'Nome inválido';
        }
        if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
            if($descricao_erro == ''){
                $descricao_erro .= 'CNPJ/CPF inválido';
            }else{
                $descricao_erro .= ', CNPJ/CPF inválido';
            }
        }
        if(!$cliente_uf){
            if($descricao_erro == ''){
                $descricao_erro .= 'UF inválida';
            }else{
                $descricao_erro .= ', UF inválida';
            }
        }
        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
            if($descricao_erro == ''){
                $descricao_erro .= 'Cidade inválida';
            }else{
                $descricao_erro .= ', cidade inválida';
            }
        }
        if(!$cliente_logradouro){
            if($descricao_erro == ''){
                $descricao_erro .= 'Logradouro inválido';
            }else{
                $descricao_erro .= ', logradouro inválido';
            }
        }
        if(!$cliente_numero){
            if($descricao_erro == ''){
                $descricao_erro .= 'Número do logradouro inválido';
            }else{
                $descricao_erro .= ', número do logradouro inválido';
            }
        }
        if(!$cliente_bairro){
            if($descricao_erro == ''){
                $descricao_erro .= 'Bairro inválido';
            }else{
                $descricao_erro .= ', bairro do logradouro inválido';
            }
        }
        $descricao_erro .= '.';

        $alert = ('Não foi possível gerar a nota fiscal! '.$descricao_erro.'','w');
        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
        exit;
    }

    $alert = ('Reprocessamento solicitado com sucesso! Aguarde a resposta da prefeitura!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}

function emitir_nfs($id_conta_receber){
    $descricao = "FATURA EM ABERTO, AGUARDAMOS SEU PAGAMENTO VIA BOLETO BANCÁRIO.\r\n";

    $id_usuario = $_SESSION['id_usuario'];

    $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_conta_receber = '$id_conta_receber'");
    $id_pessoa = $dados_conta_receber[0]['id_pessoa'];
    $id_contrato_plano_pessoa = $dados_conta_receber[0]['id_contrato_plano_pessoa'];

    if($id_contrato_plano_pessoa){
        $dados_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "a.*, b.*, c.id_cidade, c.nome AS 'nome_cidade', d.sigla AS 'uf', e.cod_servico");
    }else{
        $dados_empresa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado WHERE a.id_pessoa = '".$id_pessoa."' ", "a.*, b.id_cidade, b.nome AS 'nome_cidade', c.sigla AS 'uf'");
    }

    $cliente_id_cidade = $dados_empresa[0]['id_cidade'];
    $cliente_logradouro = $dados_empresa[0]['logradouro'];
    $cliente_numero = $dados_empresa[0]['numero'];
    $cliente_complemento = $dados_empresa[0]['complemento'];
    if(!$cliente_complemento){
        $cliente_complemento = '-';
    }
    $cliente_bairro = $dados_empresa[0]['bairro'];
    $cliente_cep = $dados_empresa[0]['cep'];
    $cliente_uf = $dados_empresa[0]['uf'];
    
    $cliente_razao_social = $dados_empresa[0]['razao_social'];
    $cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
    
    $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

    $data_criacao = getDataHora();

    $tipo_nota = 'NFS-e';

    $reter_cofins = $dados_empresa[0]['reter_cofins'];
    $reter_csll = $dados_empresa[0]['reter_csll'];
    $reter_ir = $dados_empresa[0]['reter_ir'];
    $reter_pis = $dados_empresa[0]['reter_pis'];

    $valor_total = $dados_conta_receber[0]['valor_bruto'];

    if($reter_cofins == 1){   
        $valor_cofins = sprintf("%01.2f", round(($valor_total*3)/100, 2));
    }else{
        $valor_cofins = 0;
    }

    if($reter_csll == 1){
        $valor_csll = sprintf("%01.2f", round(($valor_total*1)/100, 2));
    }else{
        $valor_csll = 0;
    }

    if($reter_ir == 1){
        $valor_ir = sprintf("%01.2f", round(($valor_total*1.5)/100, 2));
    }else{
        $valor_ir = 0;
    }

    if($reter_pis == 1){
        $valor_pis = sprintf("%01.2f", round(($valor_total*0.65)/100, 2));
    }else{
        $valor_pis = 0;
    }

    if(($valor_cofins + $valor_csll + $valor_pis) < 10){
        $valor_cofins = 0;
        $valor_csll = 0;
        $valor_pis = 0;
    }

    if($valor_ir < 10){
        $valor_ir = 0;
    }

    //BELLUNO

    if($dados_empresa[0]['cod_servico']){
        $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 0");
    }else{
        $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = 'call_suporte' AND adesao = 0");
    }

    $codigo_servico_municipio = $dados_faturamento_configuracao[0]['codigo_servico_municipio'];
    $item_lista_servico = $dados_faturamento_configuracao[0]['item_lista_servico'];
    $descricao_servico_municipio = $dados_faturamento_configuracao[0]['descricao_servico_municipio'];
    
    $descricao .= $dados_conta_receber[0]['descricao'];

    //nota
   
    if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
        
        $dados = array(
            'cliente_id_cidade' => $cliente_id_cidade,
            'cliente_logradouro' => $cliente_logradouro,
            'cliente_numero' => $cliente_numero,
            'cliente_complemento' => $cliente_complemento,
            'cliente_bairro' => $cliente_bairro,
            'cliente_cep' => $cliente_cep,
            'id_pessoa' => $id_pessoa,
            'data_criacao' => $data_criacao,
            'descricao' => $descricao,
            'valor_total' => $valor_total,
            'valor_pis' => $valor_pis,
            'valor_cofins' => $valor_cofins,
            'valor_csll' => $valor_csll,
            'valor_ir' => $valor_ir,
            'codigo_servico_municipio' => $codigo_servico_municipio,
            'descricao_servico_municipio' => $descricao_servico_municipio,
            'item_lista_servico' => $item_lista_servico,
            'cliente_razao_social' => $cliente_razao_social,
            'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
            'tipo_pessoa' => $tipo_pessoa,
            'id_usuario' => $id_usuario

        );
        $id_externo = DBCreate('', 'tb_nfs', $dados, true);
        registraLog('Inserção de nota fiscal.','i','tb_nfs',$id_externo,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario");
           
        $dados_nao_enviado = array(
            'status' => 'nao enviado'
        );

        DBUpdate('', 'tb_nfs', $dados_nao_enviado, "id_nfs = $id_externo");
        registraLog('Alteração status nota fiscal, nao enviado.','a','tb_nfs',$id_externo,"status: nao enviado");

        try{
            $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
                'tipo' => $tipo_nota,
                'idExterno' => "$id_externo",
                'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'		
                'cliente' => array(
                    'nome' => $cliente_razao_social,
                    'cpfCnpj' => $cliente_cpf_cnpj,
                    'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
                    'endereco' => array(
                        'uf' => $cliente_uf, 
                        'cidade' => $cliente_id_cidade,
                        'logradouro' => $cliente_logradouro,
                        'numero' => $cliente_numero,
                        'complemento' => $cliente_complemento,
                        'bairro' => $cliente_bairro,
                        'cep' => $cliente_cep
                    )
                ),
                
                'servico' => array(
                    'descricao' => $descricao,
                    'issRetidoFonte' => false,
                    'valorPis' => $valor_pis,
                    'valorCofins' => $valor_cofins,
                    'valorCsll' => $valor_csll,
                    'valorIr' => $valor_ir,
                    'codigoServicoMunicipio' => $codigo_servico_municipio,
                    'descricaoServicoMunicipio' => $descricao_servico_municipio,
                    'itemListaServicoLC116' => $item_lista_servico
                ),

                'valorTotal' => $valor_total
            ));

            $dados_inserindo = array(
                'status' => 'inserindo'
            );

            DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_externo");
            registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_externo,"status: inserindo");

            $dados = array(
                'id_nfs' => $id_externo    
            );
            DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");

        
        }catch(Exceptions\invalidApiKeyException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Erro de autenticação<br>'.$ex->getMessage().'','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;
        }catch(Exceptions\unauthorizedException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Acesso negado<br>'.$ex->getMessage().'','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;
        }catch(Exceptions\apiException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Erro de validação<br>'.$ex->getMessage().'','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;
        }catch(Exceptions\requestException $ex) {
            $alert = ('Não foi possível gerar a nota fiscal! Erro de requisição!','w');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
            exit;         
        }
       
    // fim nota
    
    }else{
        $descricao_erro = '';
        if(!$cliente_razao_social){
            $descricao_erro .= 'Nome inválido';
        }
        if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
            if($descricao_erro == ''){
                $descricao_erro .= 'CNPJ/CPF inválido';
            }else{
                $descricao_erro .= ', CNPJ/CPF inválido';
            }
        }
        if(!$cliente_uf){
            if($descricao_erro == ''){
                $descricao_erro .= 'UF inválida';
            }else{
                $descricao_erro .= ', UF inválida';
            }
        }
        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
            if($descricao_erro == ''){
                $descricao_erro .= 'Cidade inválida';
            }else{
                $descricao_erro .= ', cidade inválida';
            }
        }
        if(!$cliente_logradouro){
            if($descricao_erro == ''){
                $descricao_erro .= 'Logradouro inválido';
            }else{
                $descricao_erro .= ', logradouro inválido';
            }
        }
        if(!$cliente_numero){
            if($descricao_erro == ''){
                $descricao_erro .= 'Número do logradouro inválido';
            }else{
                $descricao_erro .= ', número do logradouro inválido';
            }
        }
        if(!$cliente_bairro){
            if($descricao_erro == ''){
                $descricao_erro .= 'Bairro inválido';
            }else{
                $descricao_erro .= ', bairro do logradouro inválido';
            }
        }
        $descricao_erro .= '.';

        $alert = ('Não foi possível gerar a nota fiscal! '.$descricao_erro.'','w');
        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta-receber");
        exit;
    }

    $alert = ('Reprocessamento solicitado com sucesso! Aguarde a resposta da prefeitura!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta-receber");
    exit;
}

function emitir_boleto($id_conta_receber){

    $id_usuario = $_SESSION['id_usuario'];

    $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_conta_receber = '$id_conta_receber'");
    $id_pessoa = $dados_conta_receber[0]['id_pessoa'];

    if($dados_conta_receber[0]['id_boleto']){
        $dados_boleto = DBRead('','tb_boleto',"WHERE id_boleto='".$dados_conta_receber[0]['id_boleto']."'");

        if($dados_boleto[0]['situacao'] == 'REJEITADO'){
            $dados = array(
                'id_boleto' => NULL   
            );
            DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
            DBDelete('', 'tb_remessa_bancaria_boleto', "id_boleto='".$dados_conta_receber[0]['id_boleto']."'");
            DBDelete('', 'tb_boleto', "id_boleto='".$dados_conta_receber[0]['id_boleto']."'");
        }else{
            $alert = ('Esta Conta a Receber já possui um boleto vinculado!','d');
            header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta-receber");
            exit;
        }
    }

    $dados_empresa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado WHERE a.id_pessoa = '".$id_pessoa."' ", "a.*, b.id_cidade, b.nome AS 'nome_cidade', c.sigla AS 'uf'");

    //OK CONFIGURÇÃO DO BOLETO
    $dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");
        
    $cedente_conta_numero = $dados_boleto_configuracao[0]['conta_numero'];
    $cedente_conta_numero_dv = $dados_boleto_configuracao[0]['conta_numero_dv'];
    $cedente_convenio_numero = $dados_boleto_configuracao[0]['convenio_numero'];
    $cedente_conta_codigo_banco = $dados_boleto_configuracao[0]['conta_codigo_banco'];

    $titulo_nosso_numero = $dados_boleto_configuracao[0]['nosso_numero'];
        $titulo_numero_documento = "V201".$dados_boleto_configuracao[0]['numero_documento'];

        $titulo_mensagem_01 = $dados_boleto_configuracao[0]['mensagem_1'];
    $titulo_mensagem_02 = $dados_boleto_configuracao[0]['mensagem_2'];

    $titulo_local_pagamento = $dados_boleto_configuracao[0]['local_pagamento'];
    $titulo_aceite = $dados_boleto_configuracao[0]['aceite'];
    $titulo_doc_especie = $dados_boleto_configuracao[0]['especie_documento'];

    //PRÓXIMOS 'NOSSO NUMERO' E 'NUMERO DO DOCUMENTO'
        $proximo_titulo_nosso_numero = (int)$dados_boleto_configuracao[0]['nosso_numero']+1;
        $proximo_titulo_numero_documento = (int)$dados_boleto_configuracao[0]['numero_documento']+1;

    //PESSOA
        $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

        $sacado_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
        $sacado_endereco_numero = $dados_empresa[0]['numero'];
        $sacado_endereco_bairro = $dados_empresa[0]['bairro'];
        $sacado_endereco_cep = $dados_empresa[0]['cep'];
        $sacado_endereco_cidade = $dados_empresa[0]['nome_cidade'];
        $sacado_endereco_complemento = $dados_empresa[0]['complemento'];
        $sacado_endereco_logradouro = $dados_empresa[0]['logradouro'];
        $sacado_endereco_pais = 'Brasil';
        $sacado_endereco_uf = $dados_empresa[0]['uf'];
        $sacado_nome = $dados_empresa[0]['razao_social'];	   

    //DATAS
        $titulo_data_emissao = getDataHora('data');
        $titulo_data_vencimento = $dados_conta_receber[0]['data_vencimento'];
        $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));

        //VERIFICA SE DATA DO VENCIMENTO CAI EM FINAL DE SEMANA OU FERIADO E MUDA A DATA DA MULTA JUROS
        $numero_dia_vencimento = date('w', strtotime($titulo_data_vencimento));
        $dados_feriado = DBRead('','tb_feriado',"WHERE tipo = 'Nacional' AND data = '".substr($titulo_data_vencimento, 5, 5)."'");
        if($dados_feriado && $numero_dia_vencimento == 5){
            $titulo_data_multa_juros = date('Y-m-d', strtotime("+3 days",strtotime($titulo_data_multa_juros)));
        }else if($numero_dia_vencimento == 6){
            $titulo_data_multa_juros = date('Y-m-d', strtotime("+2 days",strtotime($titulo_data_multa_juros)));
        }else if($numero_dia_vencimento == 0 || ($dados_feriado && $numero_dia_vencimento != 6)){
            $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_multa_juros)));
        }

    //NOVOS
        $situacao = "EMITIDO";
        $titulo_valor = $dados_conta_receber[0]['valor'];
        //$titulo_valor_multa_taxa = sprintf("%01.2f", round($titulo_valor*0.02, 2));
        $titulo_valor_multa_taxa = '2.00';

    if(($sacado_nome && $sacado_endereco_uf && $sacado_endereco_cidade && $sacado_endereco_cidade != 'Não Definida' && $sacado_endereco_logradouro && $sacado_endereco_numero && $sacado_endereco_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($sacado_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($sacado_cpf_cnpj)) && $sacado_endereco_cep && $titulo_valor > 0){

        $dados = array(
            'id_pessoa' => $id_pessoa,
            'cedente_conta_numero' => $cedente_conta_numero,
            'cedente_conta_numero_dv' => $cedente_conta_numero_dv,
            'cedente_convenio_numero' => $cedente_convenio_numero,
            'cedente_conta_codigo_banco' => $cedente_conta_codigo_banco,
                'sacado_cpf_cnpj' => $sacado_cpf_cnpj,
                'sacado_endereco_numero' => $sacado_endereco_numero,
                'sacado_endereco_bairro' => $sacado_endereco_bairro,
                'sacado_endereco_cep' => $sacado_endereco_cep,
                'sacado_endereco_cidade' => $sacado_endereco_cidade,
                'sacado_endereco_complemento' => $sacado_endereco_complemento,
                'sacado_endereco_logradouro' => $sacado_endereco_logradouro,
                'sacado_endereco_pais' => $sacado_endereco_pais,
                'sacado_endereco_uf' => $sacado_endereco_uf,
                'sacado_nome' => $sacado_nome,
            'titulo_data_emissao' => $titulo_data_emissao,
            'titulo_data_vencimento' => $titulo_data_vencimento,
            'titulo_mensagem_01' => $titulo_mensagem_01,
            'titulo_mensagem_02' => $titulo_mensagem_02,
            'titulo_nosso_numero' => $titulo_nosso_numero,
            'titulo_numero_documento' => $titulo_numero_documento,
            'titulo_valor' => $titulo_valor,
            'titulo_local_pagamento' => $titulo_local_pagamento,
            'titulo_aceite' => $titulo_aceite,
            'titulo_doc_especie' => $titulo_doc_especie,
            'situacao' => $situacao,
            'id_usuario' => $id_usuario,
            'remessa_pendente' => '0'

        );
            $id_boleto = DBCreate('', 'tb_boleto', $dados, true);
            registraLog('Inserção de boleto.','i','tb_boleto',$id_boleto,"id_pessoa: $id_pessoa | cedente_conta_numero: $cedente_conta_numero | cedente_conta_numero_dv: $cedente_conta_numero_dv | cedente_convenio_numero: $cedente_convenio_numero | cedente_conta_codigo_banco: $cedente_conta_codigo_banco | sacado_cpf_cnpj: $sacado_cpf_cnpj | sacado_endereco_numero: $sacado_endereco_numero | sacado_endereco_bairro: $sacado_endereco_bairro | sacado_endereco_cep: $sacado_endereco_cep | sacado_endereco_cidade: $sacado_endereco_cidade | sacado_endereco_complemento: $sacado_endereco_complemento | sacado_endereco_logradouro: $sacado_endereco_logradouro | sacado_endereco_pais: $sacado_endereco_pais | sacado_endereco_uf: $sacado_endereco_uf | sacado_nome: $sacado_nome | titulo_data_emissao: $titulo_data_emissao | titulo_data_vencimento: $titulo_data_vencimento | titulo_mensagem_01: $titulo_mensagem_01 | titulo_mensagem_02: $titulo_mensagem_02 | titulo_nosso_numero: $titulo_nosso_numero | titulo_valor: $titulo_valor | titulo_local_pagamento: $titulo_local_pagamento | titulo_aceite: $titulo_aceite | titulo_doc_especie: $titulo_doc_especie | situacao: $situacao | id_usuario: $id_usuario");

        //incluir boleto
            $parametros = '
            [
                {
                    "CedenteContaNumero": "'.$cedente_conta_numero.'",
                    "CedenteContaNumeroDV": "'.$cedente_conta_numero_dv.'",
                    "CedenteConvenioNumero": "'.$cedente_convenio_numero.'",
                    "CedenteContaCodigoBanco": "'.$cedente_conta_codigo_banco.'",
                    "SacadoCPFCNPJ": "'.$sacado_cpf_cnpj.'",
                    "SacadoEnderecoNumero": "'.$sacado_endereco_numero.'",
                    "SacadoEnderecoBairro": "'.$sacado_endereco_bairro.'",
                    "SacadoEnderecoCEP": "'.$sacado_endereco_cep.'",
                    "SacadoEnderecoCidade": "'.$sacado_endereco_cidade.'",
                    "SacadoEnderecoComplemento": "'.$sacado_endereco_complemento.'",
                    "SacadoEnderecoLogradouro": "'.$sacado_endereco_logradouro.'",
                    "SacadoEnderecoPais": "'.$sacado_endereco_pais.'",
                    "SacadoEnderecoUF": "'.$sacado_endereco_uf.'",
                    "SacadoNome": "'.$sacado_nome.'",
                    "SacadoTelefone": "5532819200",
                    "TituloDataEmissao": "'.converteData($titulo_data_emissao).'",
                    "TituloDataVencimento": "'.converteData($titulo_data_vencimento).'",
                    "TituloMensagem01": "'.$titulo_mensagem_01.'",
                    "TituloMensagem02": "'.$titulo_mensagem_02.'",
                    "TituloNossoNumero": "'.$titulo_nosso_numero.'",
                    "TituloNumeroDocumento": "'.$titulo_numero_documento.'",
                    "TituloValor": "'.str_replace('.', ',', sprintf("%01.2f", $titulo_valor)).'",
                    "TituloLocalPagamento": "'.$titulo_local_pagamento.'",
                    "TituloAceite": "'.$titulo_aceite.'",
                    "TituloDocEspecie": "'.$titulo_doc_especie.'",
                    "TituloCodigoMulta": "1",
                    "TituloValorMultaTaxa": "'.str_replace('.', ',', $titulo_valor_multa_taxa).'",                  
                    "TituloDataMulta": "'.converteData($titulo_data_multa_juros).'",
                    "TituloCodigoJuros": "2",
                    "TituloValorJuros": "0,03",                 
                    "TituloDataJuros": "'.converteData($titulo_data_multa_juros).'"
                }
            ]
            ';

            $dados_json = array(
                'json' => $parametros
            );
            DBUpdate('', 'tb_boleto', $dados_json, "id_boleto = $id_boleto");

            $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));
        
            if(!$resultado['_dados']['_sucesso']){
                $dados_situacao = array(
                    'situacao' => 'FALHA'
                );

                DBUpdate('', 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                registraLog('Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: FALHA");

                $falhas = '';
                if($resultado['_dados']['_falha']){
                    foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	
                        $falhas.= '<br>Erro(s):';
                        if($conteudo_resultado['_erro']){
                            foreach ($conteudo_resultado['_erro']['erros'] as $conteudo) {
                                $falhas.= ' '.$conteudo;
                            }
                        }
                    }
                }

                $alert = ('Não foi possível gerar o boleto! Erro na API!'.$falhas,'w');
	        	header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
				exit;

            }else{
                $id_integracao = $resultado['_dados']['_sucesso'][0]['idintegracao'];
                $dados_id_integrcao = array(
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_boleto', $dados_id_integrcao, "id_boleto = $id_boleto");
                registraLog('Inserçao id_integracao boleto.','a','tb_boleto',$id_boleto,"id_integracao: $id_integracao");

                $dados_configuracao_boleto = array(
                    'nosso_numero' => $proximo_titulo_nosso_numero,
                    'numero_documento' => $proximo_titulo_numero_documento
                );
                DBUpdate('', 'tb_boleto_configuracao', $dados_configuracao_boleto, "");

                $dados = array(
                    'id_boleto' => $id_boleto    
                );
                DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");

            }

        //fim incluir boleto
        
    }else{
        $descricao_erro = '';
        if(!$sacado_nome){
            $descricao_erro .= 'Nome inválido';
        }
        if(!$sacado_cpf_cnpj){
            if($descricao_erro == ''){
                $descricao_erro .= 'CNPJ/CPF inválido';
            }else{
                $descricao_erro .= ', CNPJ/CPF inválido';
            }
        }
        if(!$sacado_endereco_uf){
            if($descricao_erro == ''){
                $descricao_erro .= 'UF inválida';
            }else{
                $descricao_erro .= ', UF inválida';
            }
        }
        if(!$sacado_endereco_cidade || $sacado_endereco_cidade == 'Não Definida'){
            if($descricao_erro == ''){
                $descricao_erro .= 'Cidade inválida';
            }else{
                $descricao_erro .= ', cidade inválida';
            }
        }
        if(!$sacado_endereco_logradouro){
            if($descricao_erro == ''){
                $descricao_erro .= 'Logradouro inválido';
            }else{
                $descricao_erro .= ', logradouro inválido';
            }
        }
        if(!$sacado_endereco_numero){
            if($descricao_erro == ''){
                $descricao_erro .= 'Número do logradouro inválido';
            }else{
                $descricao_erro .= ', número do logradouro inválido';
            }
        }
        if(!$sacado_endereco_bairro){
            if($descricao_erro == ''){
                $descricao_erro .= 'Bairro do logradouro inválido';
            }else{
                $descricao_erro .= ', bairro do logradouro inválido';
            }
        }
        if(!$sacado_endereco_cep){
            if($descricao_erro == ''){
                $descricao_erro .= 'CEP do logradouro inválido';
            }else{
                $descricao_erro .= ', CEP do logradouro inválido';
            }
        }
        if(!$titulo_valor || $titulo_valor < 0){
            if($descricao_erro == ''){
                $descricao_erro .= 'Valor do boleto inválido';
            }else{
                $descricao_erro .= ', valor do boleto inválido';
            }
        }
        $descricao_erro .= '.';

        $alert = ('Não foi possível gerar a boleto! Erro na pessoa, '.$descricao_erro.'','w');
        header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
        exit;
    }

    $alert = ('Boleto emitido com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=controle-contas&ancora=conta_receber");
    exit;
}
?>