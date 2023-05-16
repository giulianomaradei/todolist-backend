<?php
require_once "System.php";

$pesquisas = DBRead('', 'tb_pesquisa', "WHERE status = 1");
$posicao = random_int($pesquisas[0]['id_pesquisa'], $pesquisas[count($pesquisas)]['id_pesquisa']);

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$resposta_pai = $parametros['resposta_pai'];
$id_pesquisa = $parametros['id_pesquisa'];
$posicao = $parametros['posicao'];

//$atributo = 'AND candidato = 0 AND cliente = 0 AND fornecedor = 0 AND funcionario = 0 AND prospeccao = 0';

// Verifica se foi solicitado uma consulta para o autocomplete
if($acao == 'proximo'){
	$posicao = $posicao + 1;
    $dados_perguntas = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = 1 LIMIT $posicao");

    //inserir no banco

    $dado = $posicao;
    $json1 = json_encode($dados_perguntas);
    //$json = $posicao;
    echo $json1;
}

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
/*if($acao == 'consulta'){
    $dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id'");
    $json = json_encode($dados);
    echo $json;
}*/