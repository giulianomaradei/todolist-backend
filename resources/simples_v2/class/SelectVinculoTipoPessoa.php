<?php
require_once(__DIR__."/System.php");

$id_pessoa_pai = $_POST['id_pessoa_pai'];

if($id_pessoa_pai){
    $dados = DBRead('','tb_vinculo_tipo_pessoa a', "INNER JOIN tb_vinculo_pessoa b ON a.id_vinculo_pessoa = b.id_vinculo_pessoa INNER JOIN tb_vinculo_tipo c ON a.id_vinculo_tipo = c.id_vinculo_tipo WHERE b.id_pessoa_pai = '$id_pessoa_pai' GROUP BY c.id_vinculo_tipo ORDER BY c.nome ASC", "c.*");
}else {
	$dados = DBRead('', 'tb_vinculo_tipo', "ORDER BY nome ASC");    
}

if($dados) {
	echo '
		<option value="todos">Todos</option>
		<option value="nenhum">Nenhum</option>								  
		<option value="" disabled>------------</option>
	';	
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_vinculo_tipo'];
        $nome = $conteudo['nome'];
        echo "<option value='$id'>$nome</option>";
    }
}else{
	echo  '<option value="nenhum">Nenhuma pessoa vinculada</option>';
}
 
?>