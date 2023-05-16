<?php
require_once "System.php";

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
    
    $dados = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_plano c ON a.id_plano = c.id_plano WHERE (b.nome LIKE '%$nome%' OR c.cod_servico LIKE '%$nome%') ", 'a.id_lead_negocio, a.id_pessoa, c.cod_servico AS servico, a.valor_contrato, a.data_inicio, a.id_usuario_responsavel, b.nome');
	
	foreach ($dados as $chave => $conteudo) {
		$dados[$chave]['servico'] = getNomeServico($dados[$chave]['servico']);
	}
	$json = json_encode($dados);
	echo $json;
}

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
if ($acao == 'consulta') {
    
    $dados = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_lead_negocio = '$id'", 'a.id_lead_negocio, a.id_pessoa, c.cod_servico AS servico, a.valor_contrato, a.data_conclusao, a.id_usuario_responsavel, b.nome');

	$json = json_encode($dados);
	echo $json;
}
