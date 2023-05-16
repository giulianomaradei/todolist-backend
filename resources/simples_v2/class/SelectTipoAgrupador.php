<?php
require_once(__DIR__."/System.php");

$tipo = $_POST['tipo'];

    
$dados = DBRead('','tb_natureza_financeira_agrupador', "WHERE tipo = '$tipo' ORDER BY nome ASC");
if($dados) {
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_natureza_financeira_agrupador'];
        $tipo_nome = $conteudo['nome'];

        echo "<option value='".$id."'>".$tipo_nome."</option>";
    }
}else{
        echo "<option value=''>Não existem agrupadores para este tipo!</option>";
}

?>