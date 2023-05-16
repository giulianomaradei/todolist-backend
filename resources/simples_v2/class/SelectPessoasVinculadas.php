<?php
require_once "System.php";


$id_pessoa_pai = (isset($_GET['id_pessoa_pai'])) ? $_GET['id_pessoa_pai'] : '';

$dados = DBRead('', 'tb_vinculo_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa
WHERE a.id_pessoa_pai = '$id_pessoa_pai' AND status = 1 ORDER BY b.nome ASC", "a.id_pessoa_filho, b.nome");

foreach($dados as $conteudo){

    $id = $conteudo['id_pessoa_filho'];
    $nome = $conteudo['nome'];
	
	$dados_retorno .= "<option value='$id'>$nome</option>";
}

echo json_encode($dados_retorno);

?>