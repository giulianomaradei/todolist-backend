<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['descricao']);
$id_chamado = $_SESSION['id_chamado_busca_time_line'];
$resultado = array();
$dados = DBRead('', 'tb_chamado_acao a',"INNER JOIN tb_usuario b ON a.id_usuario_acao = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '$id_chamado' AND (a.descricao LIKE '%$letra%' OR c.nome LIKE '%$letra%')","a.id_chamado_acao");
if($dados){
    foreach ($dados as $conteudo) {
        $resultado[] = 'timeline_item_'.$conteudo['id_chamado_acao'];
        $cont++;
    }
}
echo json_encode($resultado);
?>