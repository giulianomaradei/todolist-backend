<?php
require_once(__DIR__."/System.php");

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$nome = addslashes($parametros['nome']);
$id = addslashes($parametros['id']);

$pagina = addslashes($parametros['pagina']);

if($parametros['cod_servico'] && $parametros['cod_servico'] != 'qualquer'){	
	$cod_servico = "AND c.cod_servico = '".$parametros['cod_servico']."'";
	
}else{
	$cod_servico = '';
}

// Verifica se foi solicitado uma consulta para o autocomplete
if ($acao == 'autocomplete') {
	
    if ($pagina == 'chamado-form') {
		
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado'");

	} else if ($pagina == 'lead-timeline') {
		$dados = DBRead('', 'tb_pessoa', "WHERE status != 2 AND nome LIKE '%$nome%' AND prospeccao = 1 ORDER BY nome ASC");

		$json = json_encode($dados);
		echo $json;
		exit();

	} else if ($pagina == 'lead-negocio-form') {
		$dados = DBRead('', 'tb_pessoa a', "WHERE a.status != 2 AND (a.id_pessoa IN (SELECT id_pessoa FROM tb_contrato_plano_pessoa) OR a.id_pessoa IN (SELECT id_pessoa FROM tb_pessoa_prospeccao)) AND (a.nome LIKE '%$nome%')", "a.id_pessoa, a.nome, a.razao_social, a.cpf_cnpj");

		$json = json_encode($dados);
		echo $json;

		exit();

	} else if ($pagina == 'gerenciar-pesquisa-form') {

		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico AND a.status = 1 OR a.status = 5", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado'");

	} else if ($pagina == 'gerenciar-pesquisa-form-clonar') {

		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico AND a.status = 1", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado'");
		

	} else if ($pagina == 'conta_receber') {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE a.status != 3 AND (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado', a.reter_cofins, a.reter_csll, a.reter_ir, a.reter_pis");

	} else if ($pagina == 'alerta') {
		$cod_servico = "AND (c.cod_servico = 'call_suporte' OR c.cod_servico = 'call_monitoramento') ";
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado', a.reter_cofins, a.reter_csll, a.reter_ir, a.reter_pis");
		
	}else if ($pagina == 'acrescimo_desconto') {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE a.status != 3 AND (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') AND c.cod_servico != 'gestao_redes'", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado', a.reter_cofins, a.reter_csll, a.reter_ir, a.reter_pis");
	
	
	} else {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado', a.reter_cofins, a.reter_csll, a.reter_ir, a.reter_pis");
	}

	foreach ($dados as $chave => $conteudo) {
		$dados[$chave]['servico'] = getNomeServico($dados[$chave]['servico']);
		$dados[$chave]['hora_contrato'] = substr(getDataHora('hora',$dados[$chave]['sigla_estado']),0,5);
	}
	$json = json_encode($dados);
	echo $json;
}

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
if ($acao == 'consulta') {
	$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '$id'", "a.id_contrato_plano_pessoa, a.nome_contrato,  a.id_pessoa, b.nome, b.cpf_cnpj, b.razao_social, c.nome AS 'plano'");
	$json = json_encode($dados);
	echo $json;
}

if($acao == 'consulta_lead'){
	$dados = DBRead('', 'tb_pessoa', "WHERE nome LIKE '%$nome%' AND id_pessoa = '$id' ORDER BY nome ASC");

	$json = json_encode($dados);
	echo $json;
}

if ($acao == 'autocompleteresponsavelcontrato') {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado INNER JOIN tb_usuario f ON a.id_responsavel = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa INNER JOIN tb_usuario h ON a.id_responsavel_tecnico = h.id_usuario INNER JOIN tb_pessoa i ON h.id_pessoa = i.id_pessoa  WHERE (b.nome LIKE '%$nome%' OR b.razao_social LIKE '%$nome%' OR b.cpf_cnpj LIKE '%$nome%' OR a.nome_contrato LIKE '%$nome%') $cod_servico", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', e.sigla AS 'sigla_estado', g.nome AS 'nome_responsavel', i.nome AS 'nome_responsavel_tecnico'");

	foreach ($dados as $chave => $conteudo) {
		$dados[$chave]['servico'] = getNomeServico($dados[$chave]['servico']);
		$dados[$chave]['hora_contrato'] = substr(getDataHora('hora',$dados[$chave]['sigla_estado']),0,5);
	}
	$json = json_encode($dados);
	echo $json;
}
