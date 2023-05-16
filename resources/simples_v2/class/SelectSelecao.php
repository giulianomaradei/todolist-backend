<?php
require_once "System.php";

$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$data_de = addslashes($parametros['data_de']);
$data_ate = addslashes($parametros['data_ate']);
$id_selecao = addslashes($parametros['id_selecao']);


if ($acao == 'busca_selecao') {

	$dados = DBRead('','tb_selecao', "WHERE data BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' AND status = 1 ORDER BY data ASC", "id_selecao, descricao, nome");

    if($dados) {
        foreach ($dados as $conteudo) {
            $id = $conteudo['id_selecao'];
            $nome_selecao = $conteudo['nome'];
            if($id == $id_selecao){
                $selected = 'selected';
            }else{
                $selected = '';
            }
            echo "<option value='".$id."' ".$selected.">".$nome_selecao."</option>";

        }
    } else {
        echo "<option value=''>Não há registro de seleções neste periodo! </option>";
    }
}
	