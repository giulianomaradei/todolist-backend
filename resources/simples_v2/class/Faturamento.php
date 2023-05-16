<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$acao = (!empty($_POST['acao'])) ? $_POST['acao'] : '';

$servico = (!empty($_POST['servico'])) ? $_POST['servico'] : '';
$cancelados = (!empty($_POST['cancelados'])) ? $_POST['cancelados'] : '0';
$ordenacao = (!empty($_POST['ordenacao'])) ? $_POST['ordenacao'] : 'cliente';

$data_inicial = new DateTime(getDataHora('data'));
$data_inicial->modify('first day of last month');
$data_final = new DateTime(getDataHora('data'));
$data_final->modify('last day of last month');

$selecionar_ativo = (!empty($_POST['selecionar_ativo'])) ? $_POST['selecionar_ativo'] : '';

if (!empty($_POST['gerar'])) {
	if($servico == 'call_suporte'){
		inserir_call_suporte($data_inicial->format('Y-m-d'), $data_final->format('Y-m-d'), $cancelados, $ordenacao);
	}else if($servico == 'gestao_redes'){
		inserir_gestao_redes($data_inicial->format('Y-m-d'), $data_final->format('Y-m-d'), $cancelados, $ordenacao);
	}else if($servico == 'call_ativo'){
		inserir_call_ativo($data_inicial->format('Y-m-d'), $data_final->format('Y-m-d'), $cancelados, $ordenacao);
	}else if($servico == 'call_monitoramento'){
		inserir_call_monitoramento($data_inicial->format('Y-m-d'), $data_final->format('Y-m-d'), $cancelados, $ordenacao);
	}else if($servico == 'adesao'){
		exibir_adesao($cancelados, $ordenacao);
	}else if($servico == 'prepago'){
		exibir_prepago($cancelados, $ordenacao);
	}else{
		header("location: ../adm.php");
	    exit;
	}

}else if(isset($_GET['cancelar'])){
	$id = (int)$_GET['cancelar'];
	$cancelados = (int)$_GET['cancelados'];
	$ordenacao = $_GET['ordenacao'];
	$servico = $_GET['servico'];
	cancelar($id, $data_inicial->format('Y-m-d'), $cancelados, $ordenacao, $servico);

}else if(isset($_GET['reativar'])){
	$id = (int)$_GET['reativar'];
	$cancelados = (int)$_GET['cancelados'];
	$ordenacao = $_GET['ordenacao'];
	$servico = $_GET['servico'];
	
	reativar($id, $data_inicial->format('Y-m-d'), $cancelados, $ordenacao, $servico);
	
}else if($acao == 'ajustar_call_suporte'){
	//Funciona para o monitoramento (São iguais)
	$tipo_ajuste = addslashes($parametros['tipo_ajuste']);
	$valor_ajuste = addslashes($parametros['valor_ajuste']);
	$descricao = addslashes($parametros['descricao']);
	$id_ajuste = addslashes($parametros['id_ajuste']);
	$id_usuario = $_SESSION['id_usuario'];
	
	ajustar_call_suporte($tipo_ajuste, $valor_ajuste, $descricao, $id_ajuste, $id_usuario, $data_inicial->format('Y-m-d'));

}else if($acao == 'excluir_ajuste'){
	$tipo_ajuste = addslashes($parametros['tipo_ajuste']);
	$valor_ajuste = addslashes($parametros['valor_ajuste']);
	$id_ajuste = addslashes($parametros['id_ajuste']);
	
	excluir_ajuste($tipo_ajuste, $valor_ajuste, $id_ajuste);

}else if(!empty($_POST['ajustar_ativo'])){
	//Variáveis
	  	$id_faturamento = (int)$_POST['ajustar_ativo'];

		$valor_total = (!empty($_POST['valor_total'])) ? converteMoeda($_POST['valor_total'], 'banco') : '0';
		$qtd_contratada = (!empty($_POST['qtd_contratada'])) ? $_POST['qtd_contratada'] : '0';
		$qtd_efetuada = (!empty($_POST['qtd_efetuada'])) ? $_POST['qtd_efetuada'] : '0';
		$qtd_excedente = (!empty($_POST['qtd_excedente'])) ? $_POST['qtd_excedente'] : '0';
		$valor_excedente_contrato = (!empty($_POST['valor_excedente_contrato'])) ? converteMoeda($_POST['valor_excedente_contrato'], 'banco') : '0';
		$valor_cobranca = (!empty($_POST['valor_cobranca'])) ? converteMoeda($_POST['valor_cobranca'], 'banco') : '0';
		$valor_unitario_contrato = (!empty($_POST['valor_unitario_contrato'])) ? converteMoeda($_POST['valor_unitario_contrato'], 'banco') : '0';
		$valor_total_contrato = (!empty($_POST['valor_total_contrato'])) ? converteMoeda($_POST['valor_total_contrato'], 'banco') : '0';
		
		$dados = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_faturamento = $id_faturamento", "a.*, c.id_contrato_plano_pessoa, d.nome, e.nome AS nome_plano");

	    $valor_total_banco = $dados[0]['valor_total'];
	    $qtd_contratada_banco = $dados[0]['qtd_contratada'];
	    $qtd_efetuada_banco = $dados[0]['qtd_efetuada'];
	    $qtd_excedente_banco = $dados[0]['qtd_excedente'];
	    $valor_excedente_contrato_banco = $dados[0]['valor_excedente_contrato'];
	    $valor_cobranca_banco = $dados[0]['valor_cobranca'];
	    $valor_unitario_contrato_banco = $dados[0]['valor_unitario_contrato'];
	    $valor_total_contrato_banco = $dados[0]['valor_total_contrato'];
	    $qtd_clientes_banco = $dados[0]['qtd_clientes'];

	    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
	//Variáveis

	if( ($valor_total != $valor_total_banco) || ($qtd_contratada != $qtd_contratada_banco) || ($qtd_efetuada != $qtd_efetuada_banco) || ($qtd_excedente != $qtd_excedente_banco) || ($valor_excedente_contrato != $valor_excedente_contrato_banco) || ($valor_cobranca != $valor_cobranca_banco) || ($valor_unitario_contrato != $valor_unitario_contrato_banco) || ($valor_total_contrato != $valor_total_contrato_banco) ){

		ajustar_call_ativo($id_faturamento, $valor_total, $qtd_contratada, $qtd_efetuada, $qtd_excedente, $valor_excedente_contrato, $valor_cobranca, $valor_unitario_contrato, $cancelados, $ordenacao, $servico, $valor_total_contrato, $id_contrato_plano_pessoa);

	}else{
		$alert = ('Não existem ajustes a serem realizados!','w');
	   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	    exit;
	}

}else if(!empty($_POST['ajustar_redes'])){
	//Variáveis
	  	$id_faturamento = (int)$_POST['ajustar_redes'];

		$qtd_clientes = (!empty($_POST['qtd_clientes'])) ? $_POST['qtd_clientes'] : '0';
		
		$dados = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_faturamento = $id_faturamento", "a.*, c.id_contrato_plano_pessoa, d.nome, e.nome AS nome_plano, a.valor_total AS valor_total_faturamento");

	    $qtd_clientes_banco = $dados[0]['qtd_clientes'];

	    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
	//Variáveis

	if($qtd_clientes != $qtd_clientes_banco){

		ajustar_gestao_redes($id_faturamento, $cancelados, $ordenacao, $servico, $qtd_clientes, $id_contrato_plano_pessoa);

	}else{
		$alert = ('Não existem ajustes a serem realizados!','w');
	   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	    exit;
	}

}else if(!empty($_POST['ajustar_call_suporte'])){
	//Variáveis
	  	$id_faturamento = (int)$_POST['ajustar_call_suporte'];

		$qtd_clientes = (!empty($_POST['qtd_clientes'])) ? $_POST['qtd_clientes'] : '0';
		
		$dados = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_faturamento = $id_faturamento", "a.*, c.id_contrato_plano_pessoa, d.nome, e.nome AS nome_plano, a.valor_total AS valor_total_faturamento");

	    $qtd_clientes_banco = $dados[0]['qtd_clientes'];

	    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
	//Variáveis

	if($qtd_clientes != $qtd_clientes_banco){

		ajustar_clientes_call_suporte($id_faturamento, $cancelados, $ordenacao, $servico, $qtd_clientes, $id_contrato_plano_pessoa);

	}else{
		$alert = ('Não existem ajustes a serem realizados!','w');
	   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	    exit;
	}

}else if(!empty($_POST['inserir_avulso'])){
	//Variáveis
	$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '0';
	$valor_total_contrato = (!empty($_POST['valor_total_contrato'])) ? converteMoeda($_POST['valor_total_contrato'], 'banco') : '0';
	$qtd_contratada = (!empty($_POST['qtd_contratada'])) ? $_POST['qtd_contratada'] : '0';
	$qtd_efetuada = (!empty($_POST['qtd_efetuada'])) ? $_POST['qtd_efetuada'] : '0';
	$qtd_excedente = (!empty($_POST['qtd_excedente'])) ? $_POST['qtd_excedente'] : '0';
	$valor_total = (!empty($_POST['valor_total'])) ? converteMoeda($_POST['valor_total'], 'banco') : '0';
	$valor_cobranca = (!empty($_POST['valor_cobranca'])) ? converteMoeda($_POST['valor_cobranca'], 'banco') : '0';

	$dia_vencimento = (!empty($_POST['dia_vencimento'])) ? $_POST['dia_vencimento'] : '';

	$dados = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND a.adesao = '0' ", "b.id_contrato_plano_pessoa");

	if(!$dados){
		inserir_avulso_call_ativo($id_contrato_plano_pessoa, $cancelados, $ordenacao, $servico, $valor_total_contrato, $qtd_contratada, $qtd_efetuada, $qtd_excedente, $valor_total, $valor_cobranca, $data_inicial->format('Y-m-d'), $dia_vencimento);
	}else{
		$alert = ('Esse contrato já possui faturamento para este mês!','w');
	   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	    exit;
	}

}else if(!empty($_POST['inserir_adesao'])){
	$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '0';
	$valor_adesao = (!empty($_POST['valor_adesao'])) ? converteMoeda($_POST['valor_adesao'], 'banco') : '0.00';
	$dia_pagamento_adesao = (!empty($_POST['dia_pagamento_adesao'])) ?converteDataHora($_POST['dia_pagamento_adesao'], 'data') : getDataHora('data');
	$data_inicial->modify('first day of this month');
	inserir_adesao($cancelados, $ordenacao, $data_inicial->format('Y-m-d'), $id_contrato_plano_pessoa, $valor_adesao, $dia_pagamento_adesao);
}else if(!empty($_POST['inserir_prepago'])){
	$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '0';
	$mes_referencia = (!empty($_POST['mes_referencia'])) ? $_POST['mes_referencia'] : '';
	inserir_prepago($cancelados, $ordenacao, $mes_referencia, $id_contrato_plano_pessoa);
}else if(isset($_GET['cancelar_adesao'])){
	$id = (int)$_GET['cancelar_adesao'];
	$cancelados = (int)$_GET['cancelados'];
	$ordenacao = $_GET['ordenacao'];
	$servico = $_GET['servico'];
	// var_dump($id, $data_inicial->format('Y-m-d'), $cancelados, $ordenacao, $servico);
	$data_inicial->modify('first day of this month');

	cancelar_adesao($id, $data_inicial->format('Y-m-d'), $cancelados, $ordenacao, $servico);

// }else if(isset($_GET['cancelar_prepago'])){
// 	$id = (int)$_GET['cancelar_prepago'];
// 	$cancelados = (int)$_GET['cancelados'];
// 	$ordenacao = $_GET['ordenacao'];
// 	$servico = $_GET['servico'];
// 	// var_dump($id, $data_inicial->format('Y-m-d'), $cancelados, $ordenacao, $servico);
// 	$data_inicial->modify('first day of this month');

// 	cancelar_adesao($id, $data_inicial->format('Y-m-d'), $cancelados, $ordenacao, $servico);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir_prepago($cancelados, $ordenacao, $data_referencia, $id_contrato_plano_pessoa){

	$dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];

	$dados_faturamento = array(
		//'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
		'id_plano' => $dados[0]['id_plano'],
		'id_usuario' => $id_usuario,
		'data_referencia' => $data_referencia,
		'valor_total' => $dados[0]['valor_total'],
		'valor_cobranca' => $dados[0]['valor_total'],
		'acrescimo' => '0',
		'desconto' => '0',
		'status' => '1',
		'qtd_contratada' => $dados[0]['qtd_contratada'],
		'qtd_efetuada' => '0',
		'qtd_excedente' => '0',
		'valor_excedente_contrato' => '0.00',
		'qtd_desafogo' => '0',
		'valor_inicial_contrato' => '0.00',
		'tipo_cobranca' => $dados[0]['tipo_cobranca'],
		'data_gerado' => $data_gerado,
		'valor_unitario_contrato' => $dados[0]['valor_unitario'],
		'valor_total_contrato' => $dados[0]['valor_total'],
		'desafogo_contrato' => '0',
		'remove_duplicados_contrato' => $dados[0]['remove_duplicados'],
		'qtd_duplicados' => '0',
		'minutos_duplicados_contrato' => $dados[0]['minutos_duplicados'],
		'qtd_contratada_texto' => $dados[0]['qtd_contratada_texto'],
		'valor_unitario_texto_contrato' => $dados[0]['valor_unitario_texto'],
		'valor_excedente_texto_contrato' => $dados[0]['valor_excedente_texto'],
		'qtd_efetuada_texto' => '0',
		'qtd_desafogo_texto' => '0',
		'qtd_excedente_texto' => '0',
		'valor_diferente_texto' => $dados[0]['valor_diferente_texto'],
		'qtd_monitoramento' => '0',
		'qtd_clientes_teto' => '0',
		'qtd_clientes' => '0',
	);	
	
	$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
	registraLog('Inserção de faturamento de adesão.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dados[0]['id_plano']." | 
	id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: ".$dados[0]['valor_total']." | valor_cobranca: ".$dados[0]['valor_total']." | acrescimo: 0 | 	desconto: 0 | status: 1 | qtd_contratada: ".$dados[0]['qtd_contratada']." | qtd_efetuada: 0 | qtd_excedente: 0 | valor_excedente_contrato: 0.00 | qtd_desafogo: 0 | valor_inicial_contrato: 0.00 | tipo_cobranca: ".$dados[0]['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dados[0]['valor_unitario']." | valor_total_contrato: ".$dados[0]['valor_total']." | desafogo_contrato: 0 | remove_duplicados_contrato: ".$dados[0]['remove_duplicados']." | qtd_duplicados: 0 | minutos_duplicados_contrato: ".$dados[0]['minutos_duplicados']." | qtd_contratada_texto: ".$dados[0]['qtd_contratada_texto']." | valor_unitario_texto_contrato: ".$dados[0]['valor_unitario_texto']." | valor_excedente_texto_contrato: ".$dados[0]['valor_excedente_texto']." | qtd_efetuada_texto: 0 | qtd_desafogo_texto: 0 | qtd_excedente_texto: 0 | valor_diferente_texto: ".$dados[0]['valor_diferente_texto']." | qtd_monitoramento: 0 | qtd_clientes_teto: 0 | 	qtd_clientes: 0");
	
	$dados_contrato = array(
		'id_faturamento' => $insertID_contrato,
		'id_contrato_plano_pessoa' => $dados[0]['id_contrato_plano_pessoa'],
		'contrato_pai' => 1
	);

	$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
	registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dados[0]['id_contrato_plano_pessoa']." | contrato_pai: 1");
	
	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=prepago&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	exit;

}

function exibir_prepago($cancelados, $ordenacao){

	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=prepago&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	exit;
  
}

function exibir_adesao($cancelados, $ordenacao){

  header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=adesao&cancelados=".$cancelados."&ordenacao=".$ordenacao);
  exit;

}

function inserir_call_suporte($data_inicial, $data_final, $cancelados, $ordenacao){

    $dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' AND realiza_cobranca = '1' AND recebe_ligacao = '1' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato, c.nome AS nome_plano, c.cod_servico, a.desconsidera_notificacao");  
	
	$data_referencia = $data_inicial;

	$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_suporte' AND a.data_referencia = '".$data_referencia."' AND a.adesao = '0' LIMIT 1");

	if(!$dados_verificacao_faturamento && $dados_consulta){

		foreach($dados_consulta as $dado_consulta){
			if($dado_consulta['tipo_cobranca'] != 'prepago'){
				$dados_consulta_historico = DBRead('','tb_contrato_plano_pessoa_historico',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_atualizacao <= '".$data_final." 23:59:59' ");  

				$dados_consulta_status = DBRead('','tb_contrato_plano_pessoa',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_status <= '".$data_final." 23:59:59' ");  

				if($dados_consulta_historico || $dados_consulta_status){

					$valor_cobranca_texto = 0;

					$cont_faturado_texto = 0;

					$cont_faturado = 0;
					$contador_duplicados = 0;
					$exibir = 0;

					if($dado_consulta['status_contrato'] == '1' || ($dado_consulta['data_status'] >= $data_inicial && $dado_consulta['status_contrato'] != 7 && $dado_consulta['status_contrato'] != 5)){
						$exibir = 1;
					}
									
					if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

						//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

						$dados_consulta_filho = DBRead('','tb_contrato_plano_pessoa',"WHERE contrato_pai = '".$dado_consulta['id_contrato_plano_pessoa']."' ");

						if($dados_consulta_filho){

							$cont_faturado_filho = 0;                            
							$contador_duplicados_filho = 0;
							$cont_faturado_filho_texto = 0;
						
							foreach ($dados_consulta_filho as $conteudo_consulta_filho) {
								

								$dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ");

								$dados_faturado_filho = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' ");
													
								if($dado_consulta['remove_duplicados'] == '1'){
							
									if($dados_faturado_filho){
										foreach($dados_faturado_filho as $conteudo_faturado_filho){

											$data_fim_filho = date('Y-m-d H:i:s', strtotime("-".$dado_consulta['minutos_duplicados']." minutes",strtotime($conteudo_faturado_filho['data_inicio_contrato'])));

											if(valida_cpf($conteudo_faturado_filho['cpf_cnpj']) || valida_cnpj($conteudo_faturado_filho['cpf_cnpj'])){

												$dados_duplicado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio <= '".$conteudo_faturado_filho['data_inicio']."' AND data_inicio >= '".$data_fim_filho."' AND id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' AND cpf_cnpj = '".$conteudo_faturado_filho['cpf_cnpj']."' AND id_atendimento != '".$conteudo_faturado_filho['id_atendimento']."'");                                           
												
												if(!$dados_duplicado){
													if($conteudo_faturado_filho['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
														$cont_faturado_filho_texto++;
													}else{
														$cont_faturado_filho++;
													}
			
												}else{
													$contador_duplicados_filho++;
												}
			
											}else{
												if($conteudo_faturado_filho['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
													$cont_faturado_filho_texto++;
												}else{
													$cont_faturado_filho++;
												}
											}

										
										}
									}

								}else{

									$cont_dados_faturado_filho = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
								
									$cont_faturado_filho = $cont_dados_faturado_filho[0]['cont'];
								}
							
								$dados_monitoramento_filho = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");
								
								$cont_monitoramento_filho = $dados_monitoramento_filho[0]['cont'] ? $dados_monitoramento_filho[0]['cont'] : 0;
							}

						}else{
							$cont_monitoramento_filho = 0;
							$cont_faturado_filho = 0;
							$contador_duplicados_filho = 0;
							$cont_faturado_filho_texto = 0;

						}


						//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
						if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){

							$cont_dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
							
							$cont_faturado = $cont_dados_faturado[0]['cont'];

							$dados_monitoramento = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");

							$cont_monitoramento = $dados_monitoramento[0]['cont'] ? $dados_monitoramento[0]['cont'] : 0;

							$qtd_efetuada = $cont_faturado + $cont_monitoramento + $cont_faturado_filho + $cont_monitoramento_filho;

							$qtd_clientes = $dado_consulta['qtd_clientes'];
							$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];

							$excedente_realizado = ($qtd_clientes) - $qtd_clientes_teto;
							
							if($excedente_realizado <= 0){
								$excedente_realizado = 0;
							}

							$qtd_efetuada_texto = 0;
							$desafogo_realizado = 0;
							$qtd_desafogo_texto = 0;
							$excedente_realizado_texto = 0;
							
							$valor_excedente_realizado = $excedente_realizado * $dado_consulta['valor_excedente'];

							if($excedente_realizado == 0){
								$valor_cobranca_total = $dado_consulta['valor_total'];
							}else{
								$valor_cobranca_total = $dado_consulta['valor_total'] + $valor_excedente_realizado;
							}


							$qtd_duplicados = $contador_duplicados + $contador_duplicados_filho;

							$data_gerado = getDataHora();
							$id_usuario = $_SESSION['id_usuario'];



							//Minha implementação
							$qtd_monitoramento = 0;
							


							

						
						
						
						}else{
							if($dado_consulta['remove_duplicados'] == '1'){

								$dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ");

								if($dados_faturado){
									foreach($dados_faturado as $conteudo_faturado){

										$data_fim = date('Y-m-d H:i:s', strtotime("-".$dado_consulta['minutos_duplicados']." minutes",strtotime($conteudo_faturado['data_inicio'])));

										if(valida_cpf($conteudo_faturado['cpf_cnpj']) || valida_cnpj($conteudo_faturado['cpf_cnpj'])){

											$dados_duplicado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio <= '".$conteudo_faturado['data_inicio']."' AND data_inicio >= '".$data_fim."' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND cpf_cnpj = '".$conteudo_faturado['cpf_cnpj']."' AND id_atendimento != '".$conteudo_faturado['id_atendimento']."'");
										
											if(!$dados_duplicado){
												if($conteudo_faturado['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
													$cont_faturado_texto++;
												}else{
													$cont_faturado++;
												}

											}else{
												$contador_duplicados++;
											}

										}else{
											if($conteudo_faturado['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
												$cont_faturado_texto++;
											}else{
												$cont_faturado++;
											}
										}
									}
								}

							}else{
								if($dado_consulta['valor_diferente_texto'] == 1){
									$cont_dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND via_texto != 1 AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
							
									$cont_faturado = $cont_dados_faturado[0]['cont'];

									$cont_dados_faturado_texto = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND via_texto = 1 AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");

									$cont_faturado_texto = $cont_dados_faturado_texto[0]['cont'];
								}else{
									$cont_dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
							
									$cont_faturado = $cont_dados_faturado[0]['cont'];

									$cont_dados_faturado_texto = 0;
								}
							}

							
							$dados_monitoramento = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");
							
							$cont_monitoramento = $dados_monitoramento[0]['cont'] ? $dados_monitoramento[0]['cont'] : 0;
							
							//Voz
							$qtd_efetuada = $cont_faturado + $cont_monitoramento + $cont_faturado_filho + $cont_monitoramento_filho;

							//CONTADOR EXCEDENTE
							$cont_excedente = ($qtd_efetuada) - $dado_consulta['qtd_contratada'];
							
							if($cont_excedente <= 0){
								$cont_excedente = 0;
							}

							//Texto
							if($dado_consulta['valor_diferente_texto'] == 1){
								$qtd_efetuada_texto = $cont_faturado_texto + $cont_faturado_filho_texto;

								//CONTADOR EXCEDENTE
								$cont_excedente_texto = ($qtd_efetuada_texto) - $dado_consulta['qtd_contratada_texto'];
								
								if($cont_excedente_texto <= 0){
									$cont_excedente_texto = 0;
								}
							}else{
								$qtd_efetuada_texto = 0;

								//CONTADOR EXCEDENTE
								$cont_excedente_texto = 0;
							}

							

							


							if($dado_consulta['tipo_cobranca'] == 'unitario'){
								//Voz
								$desafogo_realizado = 0;
								$excedente_realizado = 0;

								$valor_excedente_realizado = 0;
								$valor_total_desafogo = 0;

								$valor_cobranca = $dado_consulta['valor_inicial'] + ($qtd_efetuada * $dado_consulta['valor_unitario']);

								//Texto
								if($dado_consulta['valor_diferente_texto'] == 1){
									$desafogo_realizado_texto = 0;
									$excedente_realizado_texto = 0;

									$valor_excedente_realizado_texto = 0;
									$valor_total_desafogo_texto = 0;

									$valor_cobranca_texto = $cont_excedente_texto * $dado_consulta['valor_unitario_texto'];
								}				

							}else{
								if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
									
									$qtd_desafogo = $dado_consulta['qtd_contratada']*($dado_consulta['desafogo']/100);
									
									//SE FOR MAIOR DO QUE 5 ELE ARREDONDA PRA CIMA, SENÃO PRA BAIXO
									$qtd_desafogo = round($qtd_desafogo);

									//CONTAGEM DESAFOGO
									if(($cont_excedente - $qtd_desafogo) > 0){
										$desafogo_realizado = $qtd_desafogo;
										$excedente_realizado = $cont_excedente - $qtd_desafogo;
									}else if(($cont_excedente - $qtd_desafogo) == 0){
										$desafogo_realizado = $qtd_desafogo;
										$excedente_realizado = 0;

									}else if(($cont_excedente - $qtd_desafogo) < 0){

										if($cont_excedente == 0){
											$desafogo_realizado = 0;
											$excedente_realizado = 0;
										}else{
											$desafogo_realizado = $cont_excedente;
											$excedente_realizado = 0;
										}
									}

									//Texto
									if($dado_consulta['valor_diferente_texto'] == 1){
										//CONTAGEM DESAFOGO 

										$qtd_desafogo_texto = $dado_consulta['qtd_contratada_texto']*($dado_consulta['desafogo_texto']/100);
										$qtd_desafogo_texto = round($qtd_desafogo_texto);

										if(($cont_excedente_texto - $qtd_desafogo_texto) > 0){
											$desafogo_realizado_texto = $qtd_desafogo_texto;
											$excedente_realizado_texto = $cont_excedente_texto - $qtd_desafogo_texto;
										}else if(($cont_excedente_texto - $qtd_desafogo_texto) == 0){
											$desafogo_realizado_texto = $qtd_desafogo_texto;
											$excedente_realizado_texto = 0;
			
										}else if(($cont_excedente_texto - $qtd_desafogo_texto) < 0){
			
											if($cont_excedente_texto == 0){
												$desafogo_realizado_texto = 0;
												$excedente_realizado_texto = 0;
											}else{
												$desafogo_realizado_texto = $cont_excedente_texto;
												$excedente_realizado_texto = 0;
											}
										}
				
									}else{
										$qtd_desafogo_texto = 0;
										$desafogo_realizado_texto = 0;
										$excedente_realizado_texto = 0;

									}
									
								}else{
									$desafogo_realizado = 0;
									$excedente_realizado = $cont_excedente;

									if($dado_consulta['valor_diferente_texto'] == 1){
										//Texto
										$desafogo_realizado_texto = 0;
										$excedente_realizado_texto = $cont_excedente_texto;
				
									}else{
										$desafogo_realizado_texto = 0;
										$excedente_realizado_texto = 0;

									}
									
								}

								$valor_excedente_realizado = $excedente_realizado * $dado_consulta['valor_excedente'];
								$valor_total_desafogo = $desafogo_realizado * $dado_consulta['valor_unitario'];

								if($cont_excedente == 0){
									$valor_cobranca = $dado_consulta['valor_total'];
								}else{
									$valor_cobranca = $dado_consulta['valor_total'] + $valor_excedente_realizado + $valor_total_desafogo;
								}

								if($dado_consulta['valor_diferente_texto'] == 1){
									//Texto
									$valor_excedente_realizado_texto = $excedente_realizado_texto * $dado_consulta['valor_excedente_texto'];
									$valor_total_desafogo_texto = $desafogo_realizado_texto * $dado_consulta['valor_unitario_texto'];

										$valor_cobranca_texto = $valor_excedente_realizado_texto + $valor_total_desafogo_texto;
			
								}else{
									$valor_excedente_realizado_texto = 0;
									$valor_total_desafogo_texto = 0;
									$valor_cobranca_texto = 0;
									
								}
							}	

							$valor_cobranca_total = $valor_cobranca + $valor_cobranca_texto;

							$qtd_duplicados = $contador_duplicados + $contador_duplicados_filho;

							$data_gerado = getDataHora();
							$id_usuario = $_SESSION['id_usuario'];

							$qtd_monitoramento = $cont_monitoramento + $cont_monitoramento_filho;
							$qtd_clientes_teto = 0;
							$qtd_clientes = $dado_consulta['qtd_clientes'];

						}
	
						// echo $dado_consulta['status_contrato'];

						//AQUI É O SEPARADO
						if($dado_consulta['separar_contrato'] != 0 && $dado_consulta['separar_contrato']){
							$valor_cobranca_total = $valor_cobranca_total/2;
							$dados_faturamento = array(
								//'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
								'id_plano' => $dado_consulta['id_plano'],
								'id_usuario' => $id_usuario,
								'data_referencia' => $data_referencia,
								'valor_total' => $valor_cobranca_total,
								'valor_cobranca' => $valor_cobranca_total,
								'acrescimo' => '0',
								'desconto' => '0',
								'status' => '1',
								'qtd_contratada' => $dado_consulta['qtd_contratada'],
								'qtd_efetuada' => $qtd_efetuada,
								'qtd_excedente' => $excedente_realizado,
								'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
								'qtd_desafogo' => $desafogo_realizado,
								'valor_inicial_contrato' => $dado_consulta['valor_inicial'],
								'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
								'data_gerado' => $data_gerado,
								'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
								'valor_total_contrato' => $dado_consulta['valor_total'],
								'desafogo_contrato' => $dado_consulta['desafogo'],
								'remove_duplicados_contrato' => $dado_consulta['remove_duplicados'],
								'qtd_duplicados' => $qtd_duplicados,
								'minutos_duplicados_contrato' => $dado_consulta['minutos_duplicados'],
								'qtd_contratada_texto' => $dado_consulta['qtd_contratada_texto'],
								'valor_unitario_texto_contrato' => $dado_consulta['valor_unitario_texto'],
								'valor_excedente_texto_contrato' => $dado_consulta['valor_excedente_texto'],
								'qtd_efetuada_texto' => $qtd_efetuada_texto,
								'qtd_desafogo_texto' => $qtd_desafogo_texto,
								'qtd_excedente_texto' => $excedente_realizado_texto,
								'valor_diferente_texto' => $dado_consulta['valor_diferente_texto'],
								'qtd_monitoramento' => $qtd_monitoramento,
								'qtd_clientes_teto' => $qtd_clientes_teto,
								'qtd_clientes' => $qtd_clientes,
								'contrato_filho_separar' => 1,
							);		
		
							// echo "<pre>";
							// 	var_dump($dados_faturamento);
							// echo "</pre>";
		
							$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
							registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: $valor_cobranca_total | valor_cobranca: $valor_cobranca_total | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | qtd_efetuada: $qtd_efetuada | qtd_excedente: $excedente_realizado | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | qtd_desafogo: $desafogo_realizado | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." | desafogo_contrato: ".$dado_consulta['desafogo']." | remove_duplicados_contrato: ".$dado_consulta['remove_duplicados']." | qtd_duplicados: $qtd_duplicados | minutos_duplicados_contrato: ".$dado_consulta['minutos_duplicados']." | qtd_contratada_texto: ".$dado_consulta['qtd_contratada_texto']." | valor_unitario_texto_contrato: ".$dado_consulta['valor_unitario_texto']." | valor_excedente_texto_contrato: ".$dado_consulta['valor_excedente_texto']." | qtd_efetuada_texto: $qtd_efetuada_texto | qtd_desafogo_texto: $qtd_desafogo_texto | qtd_excedente_texto: $excedente_realizado_texto | valor_diferente_texto: ".$dado_consulta['valor_diferente_texto']." | qtd_monitoramento: $qtd_monitoramento ");
							
							$dados_contrato = array(
								'id_faturamento' => $insertID_contrato,
								'id_contrato_plano_pessoa' => $dado_consulta['separar_contrato'],
								'contrato_pai' => 1
							);
							// echo "<pre>";
							// 	var_dump($dados_contrato);
							// echo "</pre>";
							// die();
		
							$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
							registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");
		
							$data_inicial_antecipacao = new DateTime(getDataHora('data'));
							$data_inicial_antecipacao->modify('first day of this month');
							$data_referencia_antecipacao = $data_inicial_antecipacao->format('Y-m-d');
							$data_inicial_antecipacao = $data_inicial_antecipacao->format('Y-m');
		
							$data_final_cobranca = new DateTime($dado_consulta['data_final_cobranca']);
							$dia_data_final_cobranca = $data_final_cobranca->format('d');
							$data_final_cobranca = $data_final_cobranca->format('Y-m');
		
							if($data_inicial_antecipacao == $data_final_cobranca){
								$data_inicial_antecipacao = $data_inicial_antecipacao."-10";
								if($dado_consulta['data_final_cobranca'] <= $data_inicial_antecipacao){
									// $data_final_antecipacao = new DateTime(getDataHora('data'));
									// $data_final_antecipacao->modify('last day of this month');
									// $data_final_antecipacao = $data_final_antecipacao->format('d');
		
									$qtd_dias = $dia_data_final_cobranca;
									$data_referencia_antecipacao = $data_referencia_antecipacao;
									// $valor_antecipacao = ($dado_consulta['valor_total']/$data_final_antecipacao)*$dia_data_final_cobranca;
									$valor_antecipacao = ($dado_consulta['valor_total']/30)*$dia_data_final_cobranca;
		
									$dados_faturamento_antecipacao = array(
										'id_faturamento' => $insertID_contrato,
										'qtd_dias' => $qtd_dias,
										'valor' => $valor_antecipacao,
										'data_referencia' => $data_referencia_antecipacao
									);
		
									$insertID_antecipacao = DBCreate('', 'tb_faturamento_antecipacao', $dados_faturamento_antecipacao, true);
									registraLog('Inserção de antecipacao de faturamento.','i','tb_faturamento_contrato',$insertID_antecipacao,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | valor: ".$valor_antecipacao." | data_referencia: ".$data_referencia_antecipacao." ");
								}
							}
						}			
						
						$dados_faturamento = array(
							//'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
							'id_plano' => $dado_consulta['id_plano'],
							'id_usuario' => $id_usuario,
							'data_referencia' => $data_referencia,
							'valor_total' => $valor_cobranca_total,
							'valor_cobranca' => $valor_cobranca_total,
							'acrescimo' => '0',
							'desconto' => '0',
							'status' => '1',
							'qtd_contratada' => $dado_consulta['qtd_contratada'],
							'qtd_efetuada' => $qtd_efetuada,
							'qtd_excedente' => $excedente_realizado,
							'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
							'qtd_desafogo' => $desafogo_realizado,
							'valor_inicial_contrato' => $dado_consulta['valor_inicial'],
							'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
							'data_gerado' => $data_gerado,
							'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
							'valor_total_contrato' => $dado_consulta['valor_total'],
							'desafogo_contrato' => $dado_consulta['desafogo'],
							'remove_duplicados_contrato' => $dado_consulta['remove_duplicados'],
							'qtd_duplicados' => $qtd_duplicados,
							'minutos_duplicados_contrato' => $dado_consulta['minutos_duplicados'],
							'qtd_contratada_texto' => $dado_consulta['qtd_contratada_texto'],
							'valor_unitario_texto_contrato' => $dado_consulta['valor_unitario_texto'],
							'valor_excedente_texto_contrato' => $dado_consulta['valor_excedente_texto'],
							'qtd_efetuada_texto' => $qtd_efetuada_texto,
							'qtd_desafogo_texto' => $qtd_desafogo_texto,
							'qtd_excedente_texto' => $excedente_realizado_texto,
							'valor_diferente_texto' => $dado_consulta['valor_diferente_texto'],
							'qtd_monitoramento' => $qtd_monitoramento,
							'qtd_clientes_teto' => $qtd_clientes_teto,
							'qtd_clientes' => $qtd_clientes,
						);		

						// echo "<pre>";
						// 	var_dump($dados_faturamento);
						// echo "</pre>";
						// die();
	
						$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
						registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: $valor_cobranca_total | valor_cobranca: $valor_cobranca_total | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | qtd_efetuada: $qtd_efetuada | qtd_excedente: $excedente_realizado | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | qtd_desafogo: $desafogo_realizado | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." | desafogo_contrato: ".$dado_consulta['desafogo']." | remove_duplicados_contrato: ".$dado_consulta['remove_duplicados']." | qtd_duplicados: $qtd_duplicados | minutos_duplicados_contrato: ".$dado_consulta['minutos_duplicados']." | qtd_contratada_texto: ".$dado_consulta['qtd_contratada_texto']." | valor_unitario_texto_contrato: ".$dado_consulta['valor_unitario_texto']." | valor_excedente_texto_contrato: ".$dado_consulta['valor_excedente_texto']." | qtd_efetuada_texto: $qtd_efetuada_texto | qtd_desafogo_texto: $qtd_desafogo_texto | qtd_excedente_texto: $excedente_realizado_texto | valor_diferente_texto: ".$dado_consulta['valor_diferente_texto']." | qtd_monitoramento: $qtd_monitoramento ");
						
						$dados_contrato = array(
							'id_faturamento' => $insertID_contrato,
							'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
							'contrato_pai' => 1
						);

						$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
						registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");


						//AQUI É O PROPORCIONAL CANCELADO
						if($dado_consulta['status_contrato'] == 3){

							$data_final_cobranca = new DateTime($dado_consulta['data_final_cobranca']);
							$dia_data_final_cobranca = $data_final_cobranca->format('d');
							$data_final_cobranca = $data_final_cobranca->format('Y-m');

							if($data_referencia == $data_final_cobranca."-01"){
								if($dia_data_final_cobranca <= 29){
									$qtd_dias = $dia_data_final_cobranca;
									$valor_proporcionalidade_cancelado = ($dado_consulta['valor_total']/30)*$dia_data_final_cobranca;
									$qtd_contratada = round(($dado_consulta['qtd_contratada']/30)*$qtd_dias, 0);
									$dados_faturamento_proporcional = array(
										'id_faturamento' => $insertID_contrato,
										'qtd_dias' => $qtd_dias,
										'tipo' => 1,
										'qtd_contratada' => $qtd_contratada,
									);
		
									$insertID_proporcional_cancelado = DBCreate('', 'tb_faturamento_proporcional', $dados_faturamento_proporcional, true);
									registraLog('Inserção de prporcionalidade de faturamento cancelado.','i','tb_faturamento_proporcional',$insertID_proporcional_cancelado,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | tipo: 1 | qtd_contratada: ".$qtd_contratada."");
									
									$qtd_excedente = $qtd_efetuada - $qtd_contratada;
		
									if($qtd_excedente <= 0){
										$qtd_excedente = 0;
										$valor_excedente = 0;
									}else{
										$valor_excedente = $dado_consulta['valor_excedente'] * $qtd_excedente;
										
									}
									$dados_faturamento = array(
										'valor_total' => $valor_proporcionalidade_cancelado+$valor_excedente,
										'valor_cobranca' => $valor_proporcionalidade_cancelado+$valor_excedente,
										'qtd_excedente' => $qtd_excedente,
									);
								
									DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");
								}
								
							}						
						}

						//AQUI É O PROPORCIONAL ATIVO
						if($dado_consulta['status_contrato'] == 1){

							$data_inicial_cobranca = new DateTime($dado_consulta['data_inicial_cobranca']);
							$dia_data_inicial_cobranca = $data_inicial_cobranca->format('d');
							$data_inicial_cobranca = $data_inicial_cobranca->format('Y-m');

							if($data_referencia == $data_inicial_cobranca."-01"){
								$qtd_dias = 30 - ($dia_data_inicial_cobranca - 1);
								$valor_proporcionalidade_ativo = ($dado_consulta['valor_total']/30)*$qtd_dias;
								$qtd_contratada = round(($dado_consulta['qtd_contratada']/30)*$qtd_dias, 0);

								$dados_faturamento_proporcional = array(
									'id_faturamento' => $insertID_contrato,
									'qtd_dias' => $qtd_dias,
									'tipo' => 2,
									'qtd_contratada' => $qtd_contratada,
								);

								$insertID_proporcional_ativo = DBCreate('', 'tb_faturamento_proporcional', $dados_faturamento_proporcional, true);
								registraLog('Inserção de prporcionalidade de faturamento cancelado.','i','tb_faturamento_proporcional',$insertID_proporcional_ativo,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | tipo: 2 | qtd_contratada: ".$qtd_contratada."");

								$qtd_excedente = $qtd_efetuada - $qtd_contratada;

								if($qtd_excedente <= 0){
									$qtd_excedente = 0;
									$valor_excedente = 0;
								}else{
									$valor_excedente = $dado_consulta['valor_excedente'] * $qtd_excedente;
									
								}

								$dados_faturamento = array(
									'valor_total' => $valor_proporcionalidade_ativo+$valor_excedente,
									'valor_cobranca' => $valor_proporcionalidade_ativo+$valor_excedente,
									'qtd_excedente' => $qtd_excedente,
								);

								DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");
							}						
						}
						

						$data_inicial_antecipacao = new DateTime(getDataHora('data'));
						$data_inicial_antecipacao->modify('first day of this month');
						$data_referencia_antecipacao = $data_inicial_antecipacao->format('Y-m-d');
						$data_inicial_antecipacao = $data_inicial_antecipacao->format('Y-m');

						$data_final_cobranca = new DateTime($dado_consulta['data_final_cobranca']);
						$dia_data_final_cobranca = $data_final_cobranca->format('d');
						$data_final_cobranca = $data_final_cobranca->format('Y-m');

						if($data_inicial_antecipacao == $data_final_cobranca){
							$data_inicial_antecipacao = $data_inicial_antecipacao."-10";
							if($dado_consulta['data_final_cobranca'] <= $data_inicial_antecipacao){
								// $data_final_antecipacao = new DateTime(getDataHora('data'));
								// $data_final_antecipacao->modify('last day of this month');
								// $data_final_antecipacao = $data_final_antecipacao->format('d');

								$qtd_dias = $dia_data_final_cobranca;
								$data_referencia_antecipacao = $data_referencia_antecipacao;
								// $valor_antecipacao = ($dado_consulta['valor_total']/$data_final_antecipacao)*$dia_data_final_cobranca;
								$valor_antecipacao = ($dado_consulta['valor_total']/30)*$dia_data_final_cobranca;

								$dados_faturamento_antecipacao = array(
									'id_faturamento' => $insertID_contrato,
									'qtd_dias' => $qtd_dias,
									'valor' => $valor_antecipacao,
									'data_referencia' => $data_referencia_antecipacao
								);

								// echo "<pre>";
								// 	var_dump($dados_faturamento_antecipacao);
								// echo "</pre>";

								$insertID_antecipacao = DBCreate('', 'tb_faturamento_antecipacao', $dados_faturamento_antecipacao, true);
								registraLog('Inserção de antecipacao de faturamento.','i','tb_faturamento_contrato',$insertID_antecipacao,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | valor: ".$valor_antecipacao." | data_referencia: ".$data_referencia_antecipacao." ");



								$dados_faturamento = array(
									'valor_total' => $valor_antecipacao+$valor_cobranca_total,
									'valor_cobranca' => $valor_antecipacao+$valor_cobranca_total,
								);

								DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");
							}
						}

						if($dados_consulta_filho){
						
							foreach ($dados_consulta_filho as $conteudo_consulta_filho) {
							
								$dados_contrato_filho = array(
									'id_faturamento' => $insertID_contrato,
									'id_contrato_plano_pessoa' => $conteudo_consulta_filho['id_contrato_plano_pessoa'],
									'contrato_pai' => 0
								);

								$insertID_filho = DBCreate('', 'tb_faturamento_contrato', $dados_contrato_filho, true);
								registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID_filho,"id_faturamento: $insertID_contrato | id_contrato_plano_pessoa: ".$conteudo_consulta_filho['id_contrato_plano_pessoa']." | contrato_pai: 0");

							}
						}

						$dados_acrescimo_desconto = DBRead('','tb_acrescimo_desconto',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_referencia = '".$data_referencia."' ");

						if($dados_acrescimo_desconto){					
							foreach($dados_acrescimo_desconto as $conteudo_acrescimo_desconto){					
								$dados_faturamento_ajuste = array(
									'data' => getDataHora(),
									'valor' => $conteudo_acrescimo_desconto['valor'],
									'tipo' => $conteudo_acrescimo_desconto['tipo'],
									'descricao' => $conteudo_acrescimo_desconto['descricao'],
									'id_usuario' => $conteudo_acrescimo_desconto['id_usuario'],
									'id_faturamento' => $insertID_contrato
								);
							
								$insertID = DBCreate('', 'tb_faturamento_ajuste', $dados_faturamento_ajuste, true);
								
								$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_referencia."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.status = '1' AND a.adesao = '0' ");

								if($conteudo_acrescimo_desconto['tipo'] == 'acrescimo'){
									$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'] + $conteudo_acrescimo_desconto['valor'];
									$desconto = $dados_verificacao_faturamento[0]['desconto'];
									$valor_cobranca = $valor_cobranca_total + $conteudo_acrescimo_desconto['valor'];
								}else{
									$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'];
									$desconto = $dados_verificacao_faturamento[0]['desconto'] + $conteudo_acrescimo_desconto['valor'];
									$valor_cobranca = $valor_cobranca_total - $conteudo_acrescimo_desconto['valor'];
								}
							
								$dados_faturamento = array(
									'acrescimo' => $acrescimo,
									'desconto' => $desconto,
									'valor_cobranca' => $valor_cobranca
								);
							
								DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");

								$dados_atualiza_acrescimo_desconto = array(
									'id_faturamento' => $insertID_contrato,
								);
							
								DBUpdate('', 'tb_acrescimo_desconto', $dados_atualiza_acrescimo_desconto, "id_acrescimo_desconto = '".$conteudo_acrescimo_desconto['id_acrescimo_desconto']."' ");
							}
						}


					}
				}			
			}
		}
	}

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_suporte&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    
    exit;
}

//Removo o id_faturamento do acrescimo/desconto
function cancelar($id, $mes_referencia, $cancelados, $ordenacao, $servico){
    $dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$mes_referencia."' AND b.id_contrato_plano_pessoa = '".$id."' AND a.adesao = '0' ");

	$id_faturamento = $dados_verificacao_faturamento[0]['id_faturamento'];

	$dados_faturamento = array(
	    'status' => '0'
	);

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

    $dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dados_verificacao_faturamento[0]['id_contrato_plano_pessoa']."' ");

	$ano_atual = $mes_referencia[0].$mes_referencia[1].$mes_referencia[2].$mes_referencia[3];
	$mes_atual = $mes_referencia[5].$mes_referencia[6];

	if($dados_contrato_plano_pessoa[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato_plano_pessoa[0]['nome_contrato'].")";
    }else{
        $nome_contrato = '';
    }
    $contrato = "".$dados_contrato_plano_pessoa[0]['nome']." ".$nome_contrato ;

	DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $id_faturamento");
	$alert = ('Cancelada a fatura referente a '.$dados_meses[$mes_atual].' de '.$ano_atual.', da empresa '.$contrato.'!','s');

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
}

function reativar($id, $mes_referencia, $cancelados, $ordenacao, $servico){
    $dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$mes_referencia."' AND b.id_contrato_plano_pessoa = '".$id."' AND a.adesao = '0' ");

	$id_faturamento = $dados_verificacao_faturamento[0]['id_faturamento'];

	$dados_faturamento = array(
	    'status' => '1'
	);

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

    $dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dados_verificacao_faturamento[0]['id_contrato_plano_pessoa']."' ");

	$ano_atual = $mes_referencia[0].$mes_referencia[1].$mes_referencia[2].$mes_referencia[3];
	$mes_atual = $mes_referencia[5].$mes_referencia[6];
				
	if($dados_contrato_plano_pessoa[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato_plano_pessoa[0]['nome_contrato'].")";
    }else{
        $nome_contrato = '';
    }
    $contrato = "".$dados_contrato_plano_pessoa[0]['nome']." ".$nome_contrato ;

	DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $id_faturamento");
	$alert = ('Reativado a fatura referente a '.$dados_meses[$mes_atual].' de '.$ano_atual.', da empresa '.$contrato.'!','s');

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&ancora=".$id);
}

function cancelar_adesao($id, $mes_referencia, $cancelados, $ordenacao, $servico){
    $dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$mes_referencia."' AND a.id_faturamento = '".$id."' AND a.adesao = '1' ", "a.id_faturamento, b.id_faturamento_contrato, b.id_contrato_plano_pessoa");

    DBDelete('', 'tb_faturamento_contrato', "id_faturamento_contrato = '".$dados_verificacao_faturamento[0]['id_faturamento_contrato']."'");	
    DBDelete('', 'tb_faturamento', "id_faturamento = '".$dados_verificacao_faturamento[0]['id_faturamento']."'");	

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

    $dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dados_verificacao_faturamento[0]['id_contrato_plano_pessoa']."' ");

	$ano_atual = $mes_referencia[0].$mes_referencia[1].$mes_referencia[2].$mes_referencia[3];
	$mes_atual = $mes_referencia[5].$mes_referencia[6];

	if($dados_contrato_plano_pessoa[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato_plano_pessoa[0]['nome_contrato'].")";
    }else{
        $nome_contrato = '';
    }
    $contrato = "".$dados_contrato_plano_pessoa[0]['nome']." ".$nome_contrato ;

	$alert = ('Cancelada a adesão referente a '.$dados_meses[$mes_atual].' de '.$ano_atual.', da empresa '.$contrato.'!','s');
    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=adesao&cancelados=".$cancelados."&ordenacao=".$ordenacao);
}

function inserir_adesao($cancelados, $ordenacao, $data_referencia, $id_contrato_plano_pessoa, $valor_adesao, $dia_pagamento_adesao){

	$dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "id_plano, tipo_cobranca");

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];
	$id_plano = $dados[0]['id_plano'];
	$dados_faturamento = array(
		'id_usuario' => $id_usuario,
		'data_referencia' => $data_referencia,
		'id_plano' => $id_plano,
		'status' => '1',
		'data_gerado' => $data_gerado,
		'adesao' => '1',
		'valor_adesao' => $valor_adesao,
		'dia_pagamento_adesao' => $dia_pagamento_adesao
	);

	$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
	registraLog('Inserção de faturamento de adesão.','i','tb_faturamento',$insertID_contrato,"id_usuario: $id_usuario | data_referencia: $data_referencia | id_plano: $id_plano | status: 1 | data_gerado: ".$data_gerado." | valor_adesao: $valor_adesao | adesao: 1 | dia_pagamento_adesao: $dia_pagamento_adesao");
	
	$dados_contrato = array(
		'id_faturamento' => $insertID_contrato,
		'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
		'contrato_pai' => 1
	);

	$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
	registraLog('Inserção de contrato de faturamento de adesão.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$id_contrato_plano_pessoa." | contrato_pai: 1");
	
	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=adesao&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	exit;

}

function inserir_avulso_call_ativo($id_contrato_plano_pessoa, $cancelados, $ordenacao, $servico, $valor_total_contrato, $qtd_contratada, $qtd_efetuada, $qtd_excedente, $valor_total, $valor_cobranca, $data_referencia, $dia_vencimento){

	$dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "id_plano, tipo_cobranca");

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];

	$dados_faturamento = array(
		'id_plano' => $dados[0]['id_plano'],
		'id_usuario' => $id_usuario,
		'data_referencia' => $data_referencia,
		'valor_total' => $valor_total,
		'valor_cobranca' => $valor_cobranca,
		'acrescimo' => '0',
		'desconto' => '0',
		'status' => '1',
		'qtd_contratada' => $qtd_contratada,
		'qtd_efetuada' => $qtd_efetuada,
		'qtd_excedente' => $qtd_excedente,
		'data_gerado' => $data_gerado,
		'valor_total_contrato' => $valor_total_contrato,
		'tipo_cobranca' => $dados[0]['tipo_cobranca'],
		'avulso' => '1'
	);

	/*echo "<pre>";
		var_dump($dados_faturamento);
	echo "</pre>";*/
	
	$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
	registraLog('Inserção de faturamento avulso.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dados[0]['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: ".$valor_total." | valor_cobranca: $valor_cobranca | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$qtd_contratada." | tipo_cobranca: ".$dados[0]['tipo_cobranca']." | data_gerado: $data_gerado | valor_total_contrato: ".$valor_total_contrato." | qtd_efetuada: ".$qtd_efetuada." | qtd_excedente: ".$qtd_excedente." | avulso: 1 ");
	
	$dados_contrato = array(
		'id_faturamento' => $insertID_contrato,
		'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
		'contrato_pai' => 1
	);

	$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
	registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$id_contrato_plano_pessoa." | contrato_pai: 1");

	//tb_contrato_plano_pessoa_historico
        $dados_historico = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_pessoa' => $dados[0]['id_pessoa'],
            'id_plano' => $dados[0]['id_plano'],
            'valor_unitario' => $dados[0]['valor_unitario'],
            'valor_excedente' => $dados[0]['valor_excedente'],
            'valor_plantao' => $dados[0]['valor_plantao'],
            'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
            'periodo_contrato' => $dados[0]['periodo_contrato'],
            'qtd_contratada' => $dados[0]['qtd_contratada'],
            'status' => $dados[0]['status'],
            'data_status' => $dados[0]['data_status'],
            'data_atualizacao' => $dados[0]['data_atualizacao'],
            'valor_total' => $dados[0]['valor_total'],
            'indice_reajuste' => $dados[0]['indice_reajuste'],
            'dia_pagamento' => $dados[0]['dia_pagamento'],
            'obs' => $dados[0]['obs'],
            'tipo_cobranca' => $dados[0]['tipo_cobranca'],
            'valor_inicial' => $dados[0]['valor_inicial'],
            'nome_contrato' => $dados[0]['nome_contrato'],
            'realiza_cobranca' => $dados[0]['realiza_cobranca'],
            'recebe_ligacao' => $dados[0]['recebe_ligacao'],
            'desafogo' => $dados[0]['desafogo'],
            'remove_duplicados' => $dados[0]['remove_duplicados'],
            'minutos_duplicados' => $dados[0]['minutos_duplicados'],
            'id_usuario' => $dados[0]['id_usuario'],
            'data_ajuste' => $dados[0]['data_ajuste'],
            'data_final_cobranca' => $dados[0]['data_final_cobranca'],
            'qtd_clientes' => $dados[0]['qtd_clientes'],
            'id_responsavel' => $dados[0]['id_responsavel'],
            'id_responsavel_tecnico' => $dados[0]['id_responsavel_tecnico'],
            'valor_adesao' => $dados[0]['valor_adesao'],
            'tipo_plantao' => $dados[0]['tipo_plantao'],
            'valor_diferente_texto' => $dados[0]['valor_diferente_texto'],
            'qtd_contratada_texto' => $dados[0]['qtd_contratada_texto'],
            'valor_unitario_texto' => $dados[0]['valor_unitario_texto'],
            'valor_excedente_texto' => $dados[0]['valor_excedente_texto'],
            'desafogo_texto' => $dados[0]['desafogo_texto'],
            'plano_versao' => $dados[0]['plano_versao'],
            'personalizado' => $dados[0]['personalizado'],
            'qtd_clientes_teto' => $dados[0]['qtd_clientes_teto']
            
        );
        DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);

		$id_usuario = $_SESSION['id_usuario'];

		$dados = array(
			'dia_pagamento' => $dia_vencimento,
			'id_usuario' => $id_usuario,
		);

    

    DBUpdate('', 'tb_contrato_plano_pessoa', $dados, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
    registraLog('Alteração de dia de vencimento de contrato via faturamento.','a','tb_contrato_plano_pessoa',$id_contrato_plano_pessoa,"dia_pagamento: $dia_vencimento | id_usuario: $id_usuario");



	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_ativo&cancelados=".$cancelados."&ordenacao=".$ordenacao);
	
	exit;
}

function ajustar_clientes_call_suporte($id_faturamento, $cancelados, $ordenacao, $servico, $qtd_clientes, $id_contrato_plano_pessoa){

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];

	$dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
	
	if($qtd_clientes != $dados[0]['qtd_clientes']){
		
		$dados_faturamento = DBRead('', 'tb_faturamento', "WHERE id_faturamento = '".$id_faturamento."' ");

		if($qtd_clientes - $dados_faturamento[0]['qtd_clientes_teto'] > 0){
			$qtd_excedente = $qtd_clientes - $dados_faturamento[0]['qtd_clientes_teto'];
		}else{
			$qtd_excedente = 0;
		}

		$valor_total_excedente = $dados_faturamento[0]['valor_excedente_contrato'] * $qtd_excedente;
		//valor da cobranca

			$valor_total = $dados_faturamento[0]['valor_total_contrato'] + $valor_total_excedente;
			$valor_cobranca = $valor_total;

			$dados_contrato = array(
				'qtd_clientes' => $qtd_clientes,
				'id_usuario' => $id_usuario,
				'data_atualizacao' => $data_gerado
			);
	
			DBUpdate('', 'tb_contrato_plano_pessoa', $dados_contrato, "id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
			registraLog('Alteração contrato de Call suporte através do faturamento.','a','tb_contrato_plano_pessoa',$id_contrato_plano_pessoa,"qtd_clientes: $qtd_clientes | id_usuario: $id_usuario | data_atualizacao: $data_gerado ");

			$dados_historico = array(
				'id_contrato_plano_pessoa' => $dados[0]['id_contrato_plano_pessoa'],
				'id_pessoa' => $dados[0]['id_pessoa'],
				'id_plano' => $dados[0]['id_plano'],
				'valor_unitario' => $dados[0]['valor_unitario'],
				'valor_excedente' => $dados[0]['valor_excedente'],
				'valor_plantao' => $dados[0]['valor_plantao'],
				'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
				'periodo_contrato' => $dados[0]['periodo_contrato'],
				'qtd_contratada' => $dados[0]['qtd_contratada'],
				'status' => $dados[0]['status'],
				'data_status' => $dados[0]['data_status'],
				'data_atualizacao' => $dados[0]['data_atualizacao'],
				'valor_total' => $dados[0]['valor_total'],
				'indice_reajuste' => $dados[0]['indice_reajuste'],
				'dia_pagamento' => $dados[0]['dia_pagamento'],
				'obs' => $dados[0]['obs'],
				'tipo_cobranca' => $dados[0]['tipo_cobranca'],
				'valor_inicial' => $dados[0]['valor_inicial'],
				'nome_contrato' => $dados[0]['nome_contrato'],
				'realiza_cobranca' => $dados[0]['realiza_cobranca'],
				'recebe_ligacao' => $dados[0]['recebe_ligacao'],
				'desafogo' => $dados[0]['desafogo'],
				'remove_duplicados' => $dados[0]['remove_duplicados'],
				'minutos_duplicados' => $dados[0]['minutos_duplicados'],
				'id_usuario' => $dados[0]['id_usuario'],
				'data_ajuste' => $dados[0]['data_ajuste'],
				'data_final_cobranca' => $dados[0]['data_final_cobranca'],
				'qtd_clientes' => $dados[0]['qtd_clientes'],
				'id_responsavel' => $dados[0]['id_responsavel'],
				'valor_adesao' => $dados[0]['valor_adesao'],
				'qtd_clientes_teto' => $dados[0]['qtd_clientes_teto'],
			);
		
				DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);
		
		
		$dados_faturamento_ajuste = array(
		    'qtd_clientes' => $qtd_clientes,
		    'qtd_excedente' => $qtd_excedente,
		    'valor_total' => $valor_total,
		    'valor_cobranca' => $valor_cobranca,
	  		'id_usuario' => $id_usuario,
        	'data_gerado' => $data_gerado
		);

		DBUpdate('', 'tb_faturamento', $dados_faturamento_ajuste, "id_faturamento = $id_faturamento");
	    registraLog('Alteração de faturamento de Gestão de Redes.','a','tb_faturamento',$id_faturamento,"qtd_clientes: $qtd_clientes | qtd_excedente: $qtd_excedente | valor_total: $valor_total | valor_cobranca: $valor_cobranca | id_usuario: $id_usuario | data_gerado: $data_gerado ");

	}

    $alert = ('Ajuste realizado com sucesso!','s');
   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&ancora=".$id_contrato_plano_pessoa);
    exit;
}

function ajustar_call_suporte($tipo_ajuste, $valor_ajuste, $descricao, $id_ajuste, $id_usuario, $mes_referencia){

    $dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$mes_referencia."' AND b.id_contrato_plano_pessoa = '".$id_ajuste."' AND a.status = '1' AND a.adesao = '0' ");

	$id_faturamento = $dados_verificacao_faturamento[0]['id_faturamento'];

	$dados_faturamento_ajuste = array(
	    'data' => getDataHora(),
	    'valor' => $valor_ajuste,
	    'tipo' => $tipo_ajuste,
	    'descricao' => $descricao,
	    'id_usuario' => $id_usuario,
	    'id_faturamento' => $id_faturamento
	);

	$insertID = DBCreate('', 'tb_faturamento_ajuste', $dados_faturamento_ajuste, true);

	if($tipo_ajuste == 'acrescimo'){
		$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'] + $valor_ajuste;
		$desconto = $dados_verificacao_faturamento[0]['desconto'];
		$valor_cobranca = $dados_verificacao_faturamento[0]['valor_cobranca'] + $valor_ajuste;
	}else{
		$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'];
		$desconto = $dados_verificacao_faturamento[0]['desconto'] + $valor_ajuste;
		$valor_cobranca = $dados_verificacao_faturamento[0]['valor_cobranca'] - $valor_ajuste;
	}

	$dados_faturamento = array(
	    'acrescimo' => $acrescimo,
	    'desconto' => $desconto,
	    'valor_cobranca' => $valor_cobranca
	);

	DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $id_faturamento");
	echo $insertID;
}

function ajustar_gestao_redes($id_faturamento, $cancelados, $ordenacao, $servico, $qtd_clientes, $id_contrato_plano_pessoa){

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];

	$dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
	
	if($qtd_clientes != $dados[0]['qtd_clientes']){
		
		$dados_faturamento = DBRead('', 'tb_faturamento', "WHERE id_faturamento = '".$id_faturamento."' ");

		if($dados_faturamento[0]['tipo_cobranca'] == 'cliente_ativo' || $dados_faturamento[0]['tipo_cobranca'] == 'cliente_base'){
			$qtd_efetuada = $qtd_clientes;
			$qtd_excedente = 0;

		}else if($dados_faturamento[0]['tipo_cobranca'] == 'x_cliente_base'){
			$qtd_efetuada = $qtd_clientes;
			if($qtd_efetuada - $dados_faturamento[0]['qtd_contratada'] > 0){
				$qtd_excedente = $qtd_efetuada - $dados_faturamento[0]['qtd_contratada'];
			}else{
				$qtd_excedente = 0;
			}

		}else if($dados_faturamento[0]['tipo_cobranca'] == 'ilimitado'){
			$qtd_efetuada = 0;
			$qtd_excedente = 0;

		}else{
			$qtd_efetuada = 0;
			$qtd_excedente = 0;

		}

		$valor_total_excedente = $dados_faturamento[0]['valor_excedente_contrato'] * $qtd_excedente;
		//valor da cobranca
		if($dados_faturamento[0]['tipo_cobranca'] == 'cliente_ativo' || $dados_faturamento[0]['tipo_cobranca'] == 'cliente_base'){

			$valor_total = $dados_faturamento[0]['valor_excedente_contrato'] * $qtd_clientes;
			$valor_cobranca = $valor_total + $dados_faturamento[0]['valor_plantao_total'];

			$dados_atualiza_contrato_plano_pessoa = array(
				'valor_total' => $valor_total,
				'qtd_clientes' => $qtd_clientes,
				'qtd_contratada' => $qtd_clientes,
				'id_usuario' => $id_usuario,
				'data_atualizacao' => $data_gerado
			);
			DBUpdate('', 'tb_contrato_plano_pessoa', $dados_atualiza_contrato_plano_pessoa, "id_contrato_plano_pessoa = ".$id_contrato_plano_pessoa." ");
			registraLog('Atualização do contrato através do faturamento.','a','tb_contrato_plano_pessoa', $id_contrato_plano_pessoa,"valor_total: $valor_total | qtd_clientes: $qtd_clientes | qtd_contratada: $qtd_clientes | id_usuario: $id_usuario | data_atualizacao: $data_gerado");


			$dados_historico = array(
				'id_contrato_plano_pessoa' => $dados[0]['id_contrato_plano_pessoa'],
				'id_pessoa' => $dados[0]['id_pessoa'],
				'id_plano' => $dados[0]['id_plano'],
				'valor_unitario' => $dados[0]['valor_unitario'],
				'valor_excedente' => $dados[0]['valor_excedente'],
				'valor_plantao' => $dados[0]['valor_plantao'],
				'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
				'periodo_contrato' => $dados[0]['periodo_contrato'],
				'qtd_contratada' => $dados[0]['qtd_contratada'],
				'status' => $dados[0]['status'],
				'data_status' => $dados[0]['data_status'],
				'data_atualizacao' => $dados[0]['data_atualizacao'],
				'valor_total' => $dados[0]['valor_total'],
				'indice_reajuste' => $dados[0]['indice_reajuste'],
				'dia_pagamento' => $dados[0]['dia_pagamento'],
				'obs' => $dados[0]['obs'],
				'tipo_cobranca' => $dados[0]['tipo_cobranca'],
				'valor_inicial' => $dados[0]['valor_inicial'],
				'nome_contrato' => $dados[0]['nome_contrato'],
				'realiza_cobranca' => $dados[0]['realiza_cobranca'],
				'recebe_ligacao' => $dados[0]['recebe_ligacao'],
				'desafogo' => $dados[0]['desafogo'],
				'remove_duplicados' => $dados[0]['remove_duplicados'],
				'minutos_duplicados' => $dados[0]['minutos_duplicados'],
				'id_usuario' => $dados[0]['id_usuario'],
				'data_ajuste' => $dados[0]['data_ajuste'],
				'data_final_cobranca' => $dados[0]['data_final_cobranca'],
				'qtd_clientes' => $dados[0]['qtd_clientes'],
				'id_responsavel' => $dados[0]['id_responsavel'],
				'valor_adesao' => $dados[0]['valor_adesao']
			);
		
				DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);

		}else if($dados_faturamento[0]['tipo_cobranca'] == 'x_cliente_base'){
			$valor_total = $dados_faturamento[0]['valor_total_contrato'] + $valor_total_excedente;
			$valor_cobranca = $valor_total + $dados_faturamento[0]['valor_plantao_total'];

			$dados_contrato = array(
				'qtd_clientes' => $qtd_clientes,
				'id_usuario' => $id_usuario,
				'data_atualizacao' => $data_gerado
			);
	
			DBUpdate('', 'tb_contrato_plano_pessoa', $dados_contrato, "id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
			registraLog('Alteração contrato de Gestão de Redes através do faturamento.','a','tb_contrato_plano_pessoa',$id_contrato_plano_pessoa,"qtd_clientes: $qtd_clientes | id_usuario: $id_usuario | data_atualizacao: $data_gerado ");

			$dados_historico = array(
				'id_contrato_plano_pessoa' => $dados[0]['id_contrato_plano_pessoa'],
				'id_pessoa' => $dados[0]['id_pessoa'],
				'id_plano' => $dados[0]['id_plano'],
				'valor_unitario' => $dados[0]['valor_unitario'],
				'valor_excedente' => $dados[0]['valor_excedente'],
				'valor_plantao' => $dados[0]['valor_plantao'],
				'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
				'periodo_contrato' => $dados[0]['periodo_contrato'],
				'qtd_contratada' => $dados[0]['qtd_contratada'],
				'status' => $dados[0]['status'],
				'data_status' => $dados[0]['data_status'],
				'data_atualizacao' => $dados[0]['data_atualizacao'],
				'valor_total' => $dados[0]['valor_total'],
				'indice_reajuste' => $dados[0]['indice_reajuste'],
				'dia_pagamento' => $dados[0]['dia_pagamento'],
				'obs' => $dados[0]['obs'],
				'tipo_cobranca' => $dados[0]['tipo_cobranca'],
				'valor_inicial' => $dados[0]['valor_inicial'],
				'nome_contrato' => $dados[0]['nome_contrato'],
				'realiza_cobranca' => $dados[0]['realiza_cobranca'],
				'recebe_ligacao' => $dados[0]['recebe_ligacao'],
				'desafogo' => $dados[0]['desafogo'],
				'remove_duplicados' => $dados[0]['remove_duplicados'],
				'minutos_duplicados' => $dados[0]['minutos_duplicados'],
				'id_usuario' => $dados[0]['id_usuario'],
				'data_ajuste' => $dados[0]['data_ajuste'],
				'data_final_cobranca' => $dados[0]['data_final_cobranca'],
				'qtd_clientes' => $dados[0]['qtd_clientes'],
				'id_responsavel' => $dados[0]['id_responsavel'],
				'valor_adesao' => $dados[0]['valor_adesao']
			);
		
				DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);
		}else{
			$valor_total = $dados_faturamento[0]['valor_total'] + $valor_total_excedente;
			$valor_cobranca = $valor_total + $dados_faturamento[0]['valor_plantao_total'];

			$dados_contrato = array(
				'qtd_clientes' => $qtd_clientes,
				'id_usuario' => $id_usuario,
				'data_atualizacao' => $data_gerado
			);
	
			DBUpdate('', 'tb_contrato_plano_pessoa', $dados_contrato, "id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
			registraLog('Alteração contrato de Gestão de Redes através do faturamento.','a','tb_contrato_plano_pessoa',$id_contrato_plano_pessoa,"qtd_clientes: $qtd_clientes | id_usuario: $id_usuario | data_atualizacao: $data_gerado ");

			$dados_historico = array(
				'id_contrato_plano_pessoa' => $dados[0]['id_contrato_plano_pessoa'],
				'id_pessoa' => $dados[0]['id_pessoa'],
				'id_plano' => $dados[0]['id_plano'],
				'valor_unitario' => $dados[0]['valor_unitario'],
				'valor_excedente' => $dados[0]['valor_excedente'],
				'valor_plantao' => $dados[0]['valor_plantao'],
				'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
				'periodo_contrato' => $dados[0]['periodo_contrato'],
				'qtd_contratada' => $dados[0]['qtd_contratada'],
				'status' => $dados[0]['status'],
				'data_status' => $dados[0]['data_status'],
				'data_atualizacao' => $dados[0]['data_atualizacao'],
				'valor_total' => $dados[0]['valor_total'],
				'indice_reajuste' => $dados[0]['indice_reajuste'],
				'dia_pagamento' => $dados[0]['dia_pagamento'],
				'obs' => $dados[0]['obs'],
				'tipo_cobranca' => $dados[0]['tipo_cobranca'],
				'valor_inicial' => $dados[0]['valor_inicial'],
				'nome_contrato' => $dados[0]['nome_contrato'],
				'realiza_cobranca' => $dados[0]['realiza_cobranca'],
				'recebe_ligacao' => $dados[0]['recebe_ligacao'],
				'desafogo' => $dados[0]['desafogo'],
				'remove_duplicados' => $dados[0]['remove_duplicados'],
				'minutos_duplicados' => $dados[0]['minutos_duplicados'],
				'id_usuario' => $dados[0]['id_usuario'],
				'data_ajuste' => $dados[0]['data_ajuste'],
				'data_final_cobranca' => $dados[0]['data_final_cobranca'],
				'qtd_clientes' => $dados[0]['qtd_clientes'],
				'id_responsavel' => $dados[0]['id_responsavel'],
				'valor_adesao' => $dados[0]['valor_adesao']
			);
		
				DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);
		}
		
		$dados_faturamento_ajuste = array(
		    'qtd_clientes' => $qtd_clientes,
		    'qtd_efetuada' => $qtd_efetuada,
		    'qtd_excedente' => $qtd_excedente,
		    'valor_total' => $valor_total,
		    'valor_cobranca' => $valor_cobranca,
	  		'id_usuario' => $id_usuario,
        	'data_gerado' => $data_gerado
		);

		DBUpdate('', 'tb_faturamento', $dados_faturamento_ajuste, "id_faturamento = $id_faturamento");
	    registraLog('Alteração de faturamento de Gestão de Redes.','a','tb_faturamento',$id_faturamento,"qtd_clientes: $qtd_clientes | qtd_efetuada: $qtd_efetuada | qtd_excedente: $qtd_excedente | valor_total: $valor_total | valor_cobranca: $valor_cobranca | id_usuario: $id_usuario | data_gerado: $data_gerado ");

	}

    $alert = ('Ajuste realizado com sucesso!','s');
   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&ancora=".$id_contrato_plano_pessoa);
    exit;
}

function inserir_call_monitoramento($data_inicial, $data_final, $cancelados, $ordenacao){
    
    $dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_monitoramento' AND realiza_cobranca = '1' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato, c.nome AS nome_plano, c.cod_servico");  
	
	$data_referencia = $data_inicial;

	$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_monitoramento' AND a.data_referencia = '".$data_referencia."' AND a.adesao = '0' ");

	if(!$dados_verificacao_faturamento && $dados_consulta){

		foreach($dados_consulta as $dado_consulta){

			$dados_consulta_historico = DBRead('','tb_contrato_plano_pessoa_historico',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_atualizacao <= '".$data_final." 23:59:59' ");  

			$dados_consulta_status = DBRead('','tb_contrato_plano_pessoa',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_status <= '".$data_final." 23:59:59' ");  

			if($dados_consulta_historico || $dados_consulta_status){

				$exibir = 0;

				if($dado_consulta['status_contrato'] == '1' || ($dado_consulta['data_status'] >= $data_inicial && $dado_consulta['status_contrato'] != 7 && $dado_consulta['status_contrato'] != 5)){
					$exibir = 1;
				}
				if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

					$dados_monitoramento = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");
					
					$cont_monitoramento = $dados_monitoramento[0]['cont'] ? $dados_monitoramento[0]['cont'] : 0;
						
					$qtd_efetuada = $cont_monitoramento;

					//CONTADOR EXCEDENTE
					$cont_excedente = ($qtd_efetuada) - $dado_consulta['qtd_contratada'];
					
					if($cont_excedente <= 0){
						$cont_excedente = 0;
					}

					if($dado_consulta['tipo_cobranca'] == 'unitario'){
						$desafogo_realizado = 0;
						$excedente_realizado = 0;

						$valor_excedente_realizado = 0;
						$valor_total_desafogo = 0;

						$valor_cobranca = $dado_consulta['valor_inicial'] + ($cont_excedente * $dado_consulta['valor_unitario']);

					}else{
						if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
							
							$qtd_desafogo = $dado_consulta['qtd_contratada']*($dado_consulta['desafogo']/100);
							
							//SE FOR MAIOR DO QUE 5 ELE ARREDONDA PRA CIMA, SENÃO PRA BAIXO
							$qtd_desafogo = round($qtd_desafogo);

							//CONTAGEM DESAFOGO
							if(($cont_excedente - $qtd_desafogo) > 0){
								$desafogo_realizado = $qtd_desafogo;
								$excedente_realizado = $cont_excedente - $qtd_desafogo;
							}else if(($cont_excedente - $qtd_desafogo) == 0){
								$desafogo_realizado = $qtd_desafogo;
								$excedente_realizado = 0;

							}else if(($cont_excedente - $qtd_desafogo) < 0){

								if($cont_excedente == 0){
									$desafogo_realizado = 0;
									$excedente_realizado = 0;
								}else{
									$desafogo_realizado = $cont_excedente;
									$excedente_realizado = 0;
								}
							}				
						}else{
							$desafogo_realizado = 0;
							$excedente_realizado = $cont_excedente;
						}

						$valor_excedente_realizado = $excedente_realizado * $dado_consulta['valor_excedente'];

						$valor_total_desafogo = $desafogo_realizado * $dado_consulta['valor_unitario'];

						if($cont_excedente == 0){
							$valor_cobranca = $dado_consulta['valor_total'];
						}else{
							$valor_cobranca = $dado_consulta['valor_total'] + $valor_excedente_realizado + $valor_total_desafogo;
						}
					}	

					$qtd_duplicados = 0;

					$data_gerado = getDataHora();
					$id_usuario = $_SESSION['id_usuario'];

					$dados_faturamento = array(
						'id_plano' => $dado_consulta['id_plano'],
						'id_usuario' => $id_usuario,
						'data_referencia' => $data_referencia,
						'valor_total' => $dado_consulta['valor_total'],
						'valor_cobranca' => $valor_cobranca,
						'acrescimo' => '0',
						'desconto' => '0',
						'status' => '1',
						'qtd_contratada' => $dado_consulta['qtd_contratada'],
						'qtd_efetuada' => $qtd_efetuada,
						'qtd_excedente' => $excedente_realizado,
						'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
						'qtd_desafogo' => $desafogo_realizado,
						'valor_inicial_contrato' => $dado_consulta['valor_inicial'],
						'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
						'data_gerado' => $data_gerado,
						'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
						'valor_total_contrato' => $dado_consulta['valor_total'],
						'desafogo_contrato' => $dado_consulta['desafogo'],
						'remove_duplicados_contrato' => $dado_consulta['remove_duplicados'],
						'qtd_duplicados' => $qtd_duplicados,
						'minutos_duplicados_contrato' => $dado_consulta['minutos_duplicados'],
						'qtd_monitoramento' => $qtd_efetuada
					);

					$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
			        registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: ".$dado_consulta['valor_total']." | valor_cobranca: $valor_cobranca | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | qtd_efetuada: $qtd_efetuada | qtd_excedente: $excedente_realizado | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | qtd_desafogo: $desafogo_realizado | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." | desafogo_contrato: ".$dado_consulta['desafogo']." | remove_duplicados_contrato: ".$dado_consulta['remove_duplicados']." | qtd_duplicados: $qtd_duplicados | minutos_duplicados_contrato: ".$dado_consulta['minutos_duplicados']." | qtd_monitoramento: $qtd_efetuada ");
					
					$dados_contrato = array(
						'id_faturamento' => $insertID_contrato,
						'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
						'contrato_pai' => 1
					);
					
					$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
				    registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");				    
			    }
			}			
		}
	}

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_monitoramento&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    
    exit;
}

function excluir_ajuste($tipo_ajuste, $valor_ajuste, $id_ajuste){

    $dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_faturamento_ajuste c ON a.id_faturamento = c.id_faturamento WHERE c.id_faturamento_ajuste = '".$id_ajuste."' AND a.adesao = '0' ");

	$id_faturamento = $dados_verificacao_faturamento[0]['id_faturamento'];	

	if($tipo_ajuste == 'acrescimo'){
		$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'] - $valor_ajuste;
		$desconto = $dados_verificacao_faturamento[0]['desconto'];
		$valor_cobranca = $dados_verificacao_faturamento[0]['valor_cobranca'] - $valor_ajuste;
	}else{
		$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'];
		$desconto = $dados_verificacao_faturamento[0]['desconto'] - $valor_ajuste;
		$valor_cobranca = $dados_verificacao_faturamento[0]['valor_cobranca'] + $valor_ajuste;
	}

	$dados_faturamento = array(
	    'acrescimo' => $acrescimo,
	    'desconto' => $desconto,
	    'valor_cobranca' => $valor_cobranca
	);

	DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $id_faturamento");

	$query = "DELETE FROM tb_faturamento_ajuste WHERE id_faturamento_ajuste = ".$id_ajuste;
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de faturamento ajuste.', 'e', 'tb_faturamento', $id_faturamento, '');
}

function inserir_gestao_redes($data_inicial, $data_final, $cancelados, $ordenacao){

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];
    
    $dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'gestao_redes' AND a.realiza_cobranca = '1' ORDER BY a.id_contrato_plano_pessoa ASC", "a.*, b.*, a.status AS status_contrato, c.nome AS nome_plano, c.cod_servico");  

	$data_referencia = $data_inicial;

	$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'gestao_redes' AND a.data_referencia = '".$data_referencia."'");

	if(!$dados_verificacao_faturamento && $dados_consulta){

		foreach($dados_consulta as $dado_consulta){

			$dados_consulta_historico = DBRead('','tb_contrato_plano_pessoa_historico',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_atualizacao <= '".$data_final." 23:59:59' ");  

			$dados_consulta_status = DBRead('','tb_contrato_plano_pessoa',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_status <= '".$data_final." 23:59:59' ");  

			if($dados_consulta_historico || $dados_consulta_status){

				$exibir = 0;

				if($dado_consulta['status_contrato'] == '1' || ($dado_consulta['data_status'] >= $data_inicial && $dado_consulta['status_contrato'] != 7 && $dado_consulta['status_contrato'] != 5)){
					$exibir = 1;
				}

				if($exibir != 0){
					
					//PLANTAO
					$dados_plantao = DBRead('','tb_plantao_redes',"WHERE data_referencia = '".$data_referencia."' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ", "SUM(valor_cobranca) AS valor_cobranca_plantao, COUNT(*) AS qtd_plantao");  

					if($dados_plantao && $dados_plantao[0]['qtd_plantao'] != 0){
						$tempo_plantao = $dados_plantao[0]['tempo_plantao'];
						$valor_cobranca_plantao = $dados_plantao[0]['valor_cobranca_plantao'];
						$qtd_plantao = $dados_plantao[0]['qtd_plantao'];
						
					}else{
						$tempo_plantao = 0;
						$valor_cobranca_plantao = 0;
						$qtd_plantao = 0;
					}					

					//Tipo PLANTAO
					$tipo_plantao = $dado_consulta['tipo_plantao'];

					//TIPO COBRANCA
					$qtd_clientes = $dado_consulta['qtd_clientes'];

					if($dado_consulta['tipo_cobranca'] == 'horas'){

						$dados_parametro_redes_contrato = DBRead('',"tb_parametro_redes_contrato","WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ");   

						$dados_acoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$dados_parametro_redes_contrato[0]['id_otrs']."' AND b.create_time >= '$data_inicial 00:00:00' AND b.create_time <= '$data_final 23:59:59' AND a.queue_id = 2 ","SUM(time_unit) AS tempo_total");    

						$qtd_efetuada = converteSegundosHoras(($dados_acoes[0]['tempo_total']+$tempo_plantao)*60);

						$tempo_efetuada = explode(":",$qtd_efetuada);
						$horas = $tempo_efetuada[0];
						$minutos = $tempo_efetuada[1];
						if($minutos != '00'){
							$horas ++;
						}

						$qtd_efetuada = $horas;

						if($qtd_efetuada - $dado_consulta['qtd_contratada'] > 0){
							$qtd_excedente = $qtd_efetuada - $dado_consulta['qtd_contratada'];
						}else{
							$qtd_excedente = 0;
						}

						
					}else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo' || $dado_consulta['tipo_cobranca'] == 'cliente_base'){
						$qtd_efetuada = $qtd_clientes;
						$qtd_excedente = 0;

					}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
						$qtd_efetuada = $qtd_clientes;
						if($qtd_efetuada - $dado_consulta['qtd_contratada'] > 0){
							$qtd_excedente = $qtd_efetuada - $dado_consulta['qtd_contratada'];
						}else{
							$qtd_excedente = 0;
						}

					}else if($dado_consulta['tipo_cobranca'] == 'ilimitado'){
						$qtd_efetuada = 0;
						$qtd_excedente = 0;

					}else{
						$qtd_efetuada = 0;
						$qtd_excedente = 0;

					}

					$valor_total_excedente = $dado_consulta['valor_excedente'] * $qtd_excedente;
					//valor da cobranca
					if($dado_consulta['tipo_cobranca'] == 'cliente_ativo' || $dado_consulta['tipo_cobranca'] == 'cliente_base'){
						$valor_total = $dado_consulta['valor_excedente'] * $qtd_clientes;
						$valor_cobranca = $valor_total + $valor_cobranca_plantao;

												

						if($valor_total != $dado_consulta[0]['valor_total']){
		
							$dados_historico = array(
								'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
								'id_pessoa' => $dado_consulta['id_pessoa'],
								'id_plano' => $dado_consulta['id_plano'],
								'valor_unitario' => $dado_consulta['valor_unitario'],
								'valor_excedente' => $dado_consulta['valor_excedente'],
								'valor_plantao' => $dado_consulta['valor_plantao'],
								'data_inicio_contrato' => $dado_consulta['data_inicio_contrato'],
								'periodo_contrato' => $dado_consulta['periodo_contrato'],
								'qtd_contratada' => $dado_consulta['qtd_contratada'],
								'status' => $dado_consulta['status'],
								'data_status' => $dado_consulta['data_status'],
								'data_atualizacao' => $dado_consulta['data_atualizacao'],
								'valor_total' => $dado_consulta['valor_total'],
								'indice_reajuste' => $dado_consulta['indice_reajuste'],
								'dia_pagamento' => $dado_consulta['dia_pagamento'],
								'obs' => $dado_consulta['obs'],
								'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
								'valor_inicial' => $dado_consulta['valor_inicial'],
								'nome_contrato' => $dado_consulta['nome_contrato'],
								'realiza_cobranca' => $dado_consulta['realiza_cobranca'],
								'recebe_ligacao' => $dado_consulta['recebe_ligacao'],
								'desafogo' => $dado_consulta['desafogo'],
								'remove_duplicados' => $dado_consulta['remove_duplicados'],
								'minutos_duplicados' => $dado_consulta['minutos_duplicados'],
								'id_usuario' => $dado_consulta['id_usuario'],
								'data_ajuste' => $dado_consulta['data_ajuste'],
								'data_final_cobranca' => $dado_consulta['data_final_cobranca'],
								'qtd_clientes' => $dado_consulta['qtd_clientes'],
								'id_responsavel' => $dado_consulta['id_responsavel'],
								'valor_adesao' => $dado_consulta['valor_adesao']
							);

							//DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);


							$dados_atualiza_contrato_plano_pessoa = array(
								'valor_total' => $valor_total,
								'id_usuario' => $id_usuario,
								'data_atualizacao' => $data_gerado
							);
							// DBUpdate('', 'tb_contrato_plano_pessoa', $dados_atualiza_contrato_plano_pessoa, "id_contrato_plano_pessoa = ".$id_contrato_plano_pessoa." ");
							// registraLog('Atualização do contrato através do faturamento.','a','tb_contrato_plano_pessoa', $id_contrato_plano_pessoa,"valor_total: $valor_total | id_usuario: $id_usuario | data_atualizacao: $data_gerado");
						}
					
					

					}else{
						$valor_total = $dado_consulta['valor_total'] + $valor_total_excedente;
						$valor_cobranca = $valor_total + $valor_cobranca_plantao;
					}
					
										

					
					$dados_faturamento = array(


						//'id_Contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],


						'id_plano' => $dado_consulta['id_plano'],
						'id_usuario' => $id_usuario,
						'data_referencia' => $data_referencia,
						'valor_total' => $valor_total,
						'valor_cobranca' => $valor_cobranca,
						'acrescimo' => '0',
						'desconto' => '0',
						'status' => '1',
						'qtd_contratada' => $dado_consulta['qtd_contratada'],
						'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
						'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
						'data_gerado' => $data_gerado,
						'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
						'valor_total_contrato' => $dado_consulta['valor_total'],
						'valor_plantao_contrato' => $dado_consulta['valor_plantao'],
						'qtd_plantao' => $qtd_plantao,
						'qtd_efetuada' => $qtd_efetuada,
						'qtd_excedente' => $qtd_excedente,
						'qtd_clientes' => $qtd_clientes,
						'valor_plantao_total' => $valor_cobranca_plantao,
						'tipo_plantao' => $tipo_plantao
					);

					// echo "<pre>";
					// 	var_dump($dados_faturamento);
					// echo "</pre>";

					
					
					$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
			        registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: $valor_total | valor_cobranca: $valor_cobranca | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." | valor_plantao_contrato: ".$dado_consulta['valor_plantao_contrato']." | qtd_plantao: $qtd_plantao | qtd_efetuada: $qtd_efetuada | qtd_excedente: $qtd_excedente | qtd_clientes: $qtd_clientes | valor_plantao_total: $valor_cobranca_plantao | tipo_plantao: $tipo_plantao ");
					
					$dados_contrato = array(
						'id_faturamento' => $insertID_contrato,
						'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
						'contrato_pai' => 1
					);

					$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
				    registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");

				    
			    }
			}			
		}
	}

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=gestao_redes&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    
    exit;
}

function ajustar_call_ativo($id_faturamento, $valor_total, $qtd_contratada, $qtd_efetuada, $qtd_excedente, $valor_excedente_contrato, $valor_cobranca, $valor_unitario_contrato, $cancelados, $ordenacao, $servico, $valor_total_contrato, $id_contrato_plano_pessoa){

	$data_gerado = getDataHora();
	$id_usuario = $_SESSION['id_usuario'];

	$dados_faturamento = array(
	    'valor_total' => $valor_total,
	    'qtd_contratada' => $qtd_contratada,
	    'qtd_efetuada' => $qtd_efetuada,
	    'qtd_excedente' => $qtd_excedente,
	    'valor_excedente_contrato' => $valor_excedente_contrato,
	    'valor_cobranca' => $valor_cobranca,
	    'valor_unitario_contrato' => $valor_unitario_contrato,
	    'valor_total_contrato' => $valor_total_contrato,
	    'data_gerado' => $data_gerado,
	    'id_usuario' => $id_usuario
	);
	
	DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $id_faturamento");
    registraLog('Alteração faturamento.','a','tb_faturamento',$id_faturamento,"valor_total: $valor_total | qtd_contratada: $qtd_contratada | qtd_efetuada: $qtd_efetuada | qtd_excedente: $qtd_excedente | valor_excedente_contrato: $valor_excedente_contrato | valor_cobranca: $valor_cobranca | valor_unitario_contrato: $valor_unitario_contrato | valor_total_contrato: $valor_total_contrato | data_gerado: $data_gerado | id_usuario: $id_usuario");

	$dados = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
	
	if(( ($qtd_contratada != $dados[0]['qtd_contratada']) || ($valor_excedente_contrato != $dados[0]['valor_excedente']) || ($valor_unitario_contrato != $dados[0]['valor_unitario']) || ($valor_total_contrato != $dados[0]['valor_total'])) ){
		
		$dados_historico = array(
	        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
	        'id_pessoa' => $dados[0]['id_pessoa'],
	        'id_plano' => $dados[0]['id_plano'],
	        'valor_unitario' => $dados[0]['valor_unitario'],
	        'valor_excedente' => $dados[0]['valor_excedente'],
	        'data_inicio_contrato' => $dados[0]['data_inicio_contrato'],
	        'periodo_contrato' => $dados[0]['periodo_contrato'],
	        'qtd_contratada' => $dados[0]['qtd_contratada'],
	        'status' => $dados[0]['status'],
	        'data_status' => $dados[0]['data_status'],
	        'data_atualizacao' => $dados[0]['data_atualizacao'],
	        'valor_total' => $dados[0]['valor_total'],
	        'indice_reajuste' => $dados[0]['indice_reajuste'],
	        'dia_pagamento' => $dados[0]['dia_pagamento'],
	        'obs' => $dados[0]['obs'],
	        'tipo_cobranca' => $dados[0]['tipo_cobranca'],
	        'nome_contrato' => $dados[0]['nome_contrato'],
	        'realiza_cobranca' => $dados[0]['realiza_cobranca'],
	        'recebe_ligacao' => $dados[0]['recebe_ligacao'],
	        'desafogo' => $dados[0]['desafogo'],
	        'remove_duplicados' => $dados[0]['remove_duplicados'],
	        'minutos_duplicados' => $dados[0]['minutos_duplicados'],
	        'id_usuario' => $dados[0]['id_usuario'],
	        'data_ajuste' => $dados[0]['data_ajuste'],
	        'data_final_cobranca' => $dados[0]['data_final_cobranca'],
	        'id_responsavel' => $dados[0]['id_responsavel'],
	        'valor_adesao' => $dados[0]['valor_adesao']
	    );

	    DBCreate('', 'tb_contrato_plano_pessoa_historico', $dados_historico);

		$dados_contrato = array(
		    'valor_unitario' => $valor_unitario_contrato,
		    'valor_excedente' => $valor_excedente_contrato,
		    'qtd_contratada' => $qtd_contratada,
	  		'id_usuario' => $id_usuario,
        	'data_atualizacao' => getDataHora(),
		);

		DBUpdate('', 'tb_contrato_plano_pessoa', $dados_contrato, "id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
	    registraLog('Alteração contrato através do faturamento.','a','tb_contrato_plano_pessoa',$id_contrato_plano_pessoa,"valor_unitario: $valor_unitario_contrato | valor_excedente: $valor_excedente_contrato | qtd_contratada: $qtd_contratada | valor_total: $valor_total_contrato | id_usuario: $id_usuario");
	}

    $alert = ('Ajuste realizado com sucesso!','s');
   	header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&ancora=".$id_contrato_plano_pessoa);
    exit;
}

function inserir_call_ativo($data_inicial, $data_final, $cancelados, $ordenacao){

  	$dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_ativo' AND a.realiza_cobranca = '1' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato, c.nome AS nome_plano, c.cod_servico");  

	$data_referencia = $data_inicial;

	$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_ativo' AND a.data_referencia = '".$data_referencia."' AND a.adesao = '0' ");	
				
	if(!$dados_verificacao_faturamento && $dados_consulta){

		foreach($dados_consulta as $dado_consulta){

			$dados_consulta_historico = DBRead('','tb_contrato_plano_pessoa_historico',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_atualizacao <= '".$data_final." 23:59:59' ");  

			$dados_consulta_status = DBRead('','tb_contrato_plano_pessoa',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_status <= '".$data_final." 23:59:59' ");  

			if($dados_consulta_historico || $dados_consulta_status){

				$exibir = 0;

				if($dado_consulta['status_contrato'] == '1' || ($dado_consulta['data_status'] >= $data_inicial && $dado_consulta['status_contrato'] != 7 && $dado_consulta['status_contrato'] != 5)){
					$exibir = 1;
				}

				if($exibir != 0){
					
					$valor_cobranca = $dado_consulta['valor_total'];
					
					/*echo $dado_consulta['id_contrato_plano_pessoa']." - ".$dado_consulta['tipo_cobranca']."<br>";
					echo $dado_consulta['id_contrato_plano_pessoa']." - ".$valor_cobranca."<hr>";*/
					
					$data_gerado = getDataHora();
					$id_usuario = $_SESSION['id_usuario'];

					$dados_faturamento = array(
						'id_plano' => $dado_consulta['id_plano'],
						'id_usuario' => $id_usuario,
						'data_referencia' => $data_referencia,
						'valor_total' => $dado_consulta['valor_total'],
						'valor_cobranca' => $valor_cobranca,
						'acrescimo' => '0',
						'desconto' => '0',
						'status' => '1',
						'qtd_contratada' => $dado_consulta['qtd_contratada'],
						'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
						'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
						'data_gerado' => $data_gerado,
						'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
						'valor_total_contrato' => $dado_consulta['valor_total'],
					);

					/*echo "<pre>";
						var_dump($dados_faturamento);
					echo "</pre>";*/
					
					$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
			        registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: ".$dado_consulta['valor_total']." | valor_cobranca: $valor_cobranca | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." ");
					
					$dados_contrato = array(
						'id_faturamento' => $insertID_contrato,
						'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
						'contrato_pai' => 1
					);

					$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
				    registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");

				    
			    }
			}			
		}
	}

    header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_ativo&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    
    exit;
}

?>