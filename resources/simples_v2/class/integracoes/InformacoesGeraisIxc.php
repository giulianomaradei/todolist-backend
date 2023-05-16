<?php
require_once "ixc/Assunto.php";
require_once "ixc/DepartamentoAtendimento.php";
require_once "ixc/Filial.php";
require_once "ixc/Setor.php";
require_once "ixc/Funcionarios.php";

//id da integração do ixc na base de dados do Simples
$id_integracao = 1;

//Consulta que trás os dados obrigatórios para a finalização de uma atendimento, o.s. ou ação no sistema de gestão.
$dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

$count = 0;

//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if($dados_obrigatorios){
	foreach($dados_obrigatorios as $conteudo){
		if($conteudo["chave"] == "assunto"){
			$count++;
		}
	}
}

$assunto = new Integracao\Ixc\Assunto();
$retorno_assunto = $assunto->get('su_oss_assunto.id', '', true, $id_contrato_plano_pessoa);

/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if($count != $retorno_assunto["total"]){
	DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'assunto' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
	foreach ($retorno_assunto['registros'] as $retorno) {
		$dados = array(
			"chave" => "assunto",
			"valor_id" => $retorno['id'],
			"valor_descricao" => $retorno['assunto'],
			"id_integracao" => $id_integracao,
			'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
		);
		DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
	}
}

$count = 0;

//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if($dados_obrigatorios){
	foreach($dados_obrigatorios as $conteudo){
		if($conteudo["chave"] == "departamento"){
			$count++;
		}
	}
}

$departamento = new Integracao\Ixc\DepartamentoAtendimento();
$retorno_departamento = $departamento->get('su_ticket_setor.id', '', true, $id_contrato_plano_pessoa);

/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if($count != $retorno_departamento["total"]){
	DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'departamento' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
	foreach ($retorno_departamento['registros'] as $retorno) {
		$dados = array(
			"chave" => "departamento",
			"valor_id" => $retorno['id'],
			"valor_descricao" => $retorno['setor'],
			"id_integracao" => $id_integracao,
			'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
		);
		DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
	}
}

$count = 0;

//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if($dados_obrigatorios){
	foreach($dados_obrigatorios as $conteudo){
		if($conteudo["chave"] == "filial"){
			$count++;
		}
	}
}

$filial = new Integracao\Ixc\Filial();
$retorno_filial = $filial->get('filial.id', '', '=', true, $id_contrato_plano_pessoa);

/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if($count != $retorno_filial["total"]){
	DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'filial' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
	foreach ($retorno_filial['registros'] as $retorno) {
		$dados = array(
			"chave" => "filial",
			"valor_id" => $retorno['id'],
			"valor_descricao" => $retorno['razao'],
			"id_integracao" => $id_integracao,
			'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
		);
		DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
	}
}

$count = 0;

//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if($dados_obrigatorios){
	foreach($dados_obrigatorios as $conteudo){
		if($conteudo["chave"] == "setor"){
			$count++;
		}
	}
}

$setor = new Integracao\Ixc\Setor();
$retorno_setor = $setor->get('empresa_setor.id', '', '=', true, $id_contrato_plano_pessoa);

/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if($count != $retorno_setor["total"]){
	DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'setor' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
	foreach ($retorno_setor['registros'] as $retorno) {
		$dados = array(
			"chave" => "setor",
			"valor_id" => $retorno['id'],
			"valor_descricao" => $retorno['setor'],
			"id_integracao" => $id_integracao,
			'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
		);
		DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
	}
}

$count = 0;

//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if($dados_obrigatorios){
	foreach($dados_obrigatorios as $conteudo){
		if($conteudo["chave"] == "funcionario"){
			$count++;
		}
	}
}

$tecnico = new Integracao\Ixc\Funcionarios();
$retorno_tecnico = $tecnico->get('funcionario_setor2.id', '', true, $id_contrato_plano_pessoa);

/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if($count != $retorno_tecnico["total"]){
	DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'funcionario' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
	foreach ($retorno_tecnico['registros'] as $retorno) {
		$dados = array(
			"chave" => "funcionario",
			"valor_id" => $retorno['id'],
			"valor_descricao" => $retorno['funcionario'],
			"id_integracao" => $id_integracao,
			'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
		);
		DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
	}
}