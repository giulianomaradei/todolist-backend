<?php
require_once "System.php";


$passo_atendimento = (isset($_POST['passo_atendimento'])) ? $_POST['passo_atendimento'] : '';

$dados = DBRead('', 'tb_monitoria_quesito', " WHERE passo_atendimento = '".$passo_atendimento."' AND status = 1 ORDER BY descricao ASC");

if($dados){
    foreach($dados as $conteudo){
        $dados_array[$conteudo['id_monitoria_quesito']] = $conteudo['descricao'];
    }				
}
asort($dados_array);

foreach($dados_array as $id => $descricao){
	
    /* $dados_retorno['dados'] .= "<option value='$id' data-toggle='tooltip' data-placement='top' title='$descricao'>".limitarTexto($descricao, 120)."</option>"; */
    
    $dados_retorno['dados'] .= "<option value='$id'>$descricao</option>";
}

echo json_encode($dados_retorno);

?>
