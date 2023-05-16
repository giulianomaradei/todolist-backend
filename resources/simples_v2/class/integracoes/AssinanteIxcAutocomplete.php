<?php
include "../System.php";
include "ixc/Cliente.php";

$cliente = new Integracao\Ixc\Cliente();

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$nome = addslashes($parametros['nome']);
$campo = "cliente.razao";

//Se as informações do input vierem em formato numerico, é trocado a string de pesquisa na API de cliente.razao para cliente.cnpj_cpf para possibilitar pesquisar o cliente das duas maneiras
if(filter_var($nome, FILTER_SANITIZE_NUMBER_INT)){
    $campo = "cliente.cnpj_cpf";
}

$id = addslashes($parametros['id']);
$id_contrato_plano_pessoa = addslashes($parametros['id_contrato_plano_pessoa']);

// Verifica se foi solicitado uma consulta para o autocomplete
if($acao == 'autocomplete'){
    $retorno = $cliente->get($campo, $nome, 'L', '1', '20000', 'cliente.razao', 'desc', false, $id_contrato_plano_pessoa);
    echo $retorno;
}

if($acao == 'consulta'){
    $retorno = $cliente->get('cliente.id', $id, '=', '1', '20000', 'cliente.razao', 'desc', false, $id_contrato_plano_pessoa);
    echo $retorno;
}

if($acao == 'busca'){
    $retorno = $cliente->get('cliente.razao', $nome, 'L', '1', '20000', 'cliente.razao', 'desc', false, $id_contrato_plano_pessoa);
    echo $retorno;
}