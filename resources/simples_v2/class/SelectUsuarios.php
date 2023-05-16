<?php
require_once "System.php";


$ids_perfis = (isset($_POST['ids_perfis'])) ? $_POST['ids_perfis'] : '';
$visibilidade = (isset($_POST['visibilidade'])) ? $_POST['visibilidade'] : '';
$pagina = (isset($_POST['pagina'])) ? $_POST['pagina'] : '';

$dados_array = array();
$dados_retorno = array();

$status = "";

if($pagina == 'chamado-informacoes' || $pagina == 'chamado-form'){
	$status = " a.status = 1 AND";
}

foreach($ids_perfis[0] as $conteudo){

	if($visibilidade == 1){
		$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE $status a.id_perfil_sistema = '".$conteudo."' ORDER BY b.nome ASC", "b.nome, a.id_usuario");
	}
	if($visibilidade == 2){
		$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE $status a.id_usuario = '".$conteudo."' ORDER BY b.nome ASC", "b.nome, a.id_usuario");
	}
	
	if($dados){
		foreach($dados as $conteudo){
			$dados_array[$conteudo['id_usuario']] = $conteudo['nome'];
		}		
	}
}
asort($dados_array);

foreach($dados_array as $id => $nome){
	
	$dados_retorno['dados'] .= "<option value='$id'>$nome</option>";
}

echo json_encode($dados_retorno);

?>