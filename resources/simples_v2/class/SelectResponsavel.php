<?php
require_once "System.php";


$id_perfil = (isset($_POST['id_perfil'])) ? $_POST['id_perfil'] : '';
$pagina = (isset($_POST['pagina'])) ? $_POST['pagina'] : '';

$id_responsavel_chamado = (isset($_POST['id_responsavel_chamado'])) ? $_POST['id_responsavel_chamado'] : '';

$dados_array = array();
$dados_retorno = array();

/*$status = "";

if($pagina == 'chamado-informacoes' || $pagina == 'chamado-form'){
	$status = " a.status = 1 AND";
}*/

$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_perfil_sistema = '".$id_perfil."' AND a.id_usuario != '".$id_responsavel_chamado."' ORDER BY b.nome ASC", "b.nome, a.id_usuario");

if($dados){
    foreach($dados as $conteudo){
        $dados_array[$conteudo['id_usuario']] = $conteudo['nome'];
    }		
}
//asort($dados_array);

foreach($dados_array as $id => $nome){
	
	$dados_retorno['dados'] .= "<option value='$id'>$nome</option>";
}

echo json_encode($dados_retorno);

?>