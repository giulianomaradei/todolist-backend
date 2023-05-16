<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
//$letra = addslashes($parametros['nome']);

$id_patrimonio_item = $parametros['id_patrimonio_item'];
$id_patrimonio_localizacao = $parametros['id_patrimonio_localizacao'];
$id_responsavel = $parametros['id_responsavel'];
$id_fornecedor = $parametros['id_fornecedor'];
$status = $parametros['status'];
$numero_patrimonio = $parametros['numero_patrimonio'];

if($id_patrimonio_item){
    $filtro_id_patrimonio_item = " AND a.id_patrimonio_item = '".$id_patrimonio_item."' ";
}

if($id_patrimonio_localizacao){
    $filtro_id_patrimonio_localizacao = " AND a.id_patrimonio_localizacao = '".$id_patrimonio_localizacao."' ";
}

if($id_responsavel){
    $filtro_id_responsavel = " AND a.id_responsavel = '".$id_responsavel."' ";
}

if($id_fornecedor){
    $filtro_id_fornecedor = " AND a.id_fornecedor = '".$id_fornecedor."' ";
}

if($status){
    $filtro_status = " AND a.status = '".$status."' ";
}

if($numero_patrimonio){
    $filtro_numero_patrimonio = " AND a.numero_patrimonio = '".$numero_patrimonio."' ";
}


// Informações da query

if($numero_patrimonio){
	$filtros_query = "INNER JOIN tb_patrimonio_item b ON a.id_patrimonio_item = b.id_patrimonio_item INNER JOIN tb_patrimonio_localizacao c ON a.id_patrimonio_localizacao = c.id_patrimonio_localizacao WHERE a.status != 6 ".$filtro_numero_patrimonio."";
} else{
	$filtros_query = "INNER JOIN tb_patrimonio_item b ON a.id_patrimonio_item = b.id_patrimonio_item INNER JOIN tb_patrimonio_localizacao c ON a.id_patrimonio_localizacao = c.id_patrimonio_localizacao WHERE a.status != 6 ".$filtro_id_patrimonio_item." ".$filtro_id_patrimonio_localizacao." ".$filtro_id_responsavel." ".$filtro_id_fornecedor." ".$filtro_status." ";
};
// Maximo de registros por pagina
$maximo = 10;

// Limite de links (antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if ($pagina == '') {
	$pagina = 1;
}
// Conta os resultados no total da query
$dados = DBRead('', 'tb_patrimonio a', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if ($inicio >= $total) {
	$inicio = 0;
	$pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDOs
//
$dados = DBRead('', 'tb_patrimonio a', $filtros_query . " ORDER BY a.numero_patrimonio DESC LIMIT $inicio,$maximo", "a.*, a.status AS status_patrimonio, b.descricao, c.nome AS nome_localizacao");
if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	} else {
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
	}
	echo "</p>";
} else {
	
	echo "<div class='table-responsive'>";
		echo "<table class='table table-hover' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>#</th>";
					echo "<th>Item</th>";
					echo "<th>Nº Patrimônio</th>";
					echo "<th>Data da Compra</th>";
					echo "<th>Valor da Compra</th>";
					echo "<th>Localização</th>";
					echo "<th>Responsável</th>";
					echo "<th>Fornecedor</th>";
					echo "<th>Status</th>";
					echo "<th>Atualizado em</th>";
					echo "<th class=\"text-center\">Opções</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {

				$id_patrimonio = $conteudo['id_patrimonio'];
				$descricao = $conteudo['descricao'];
				$data_compra = converteDataHora($conteudo['data_compra'], 'data');
				$valor_compra = converteMoeda($conteudo['valor_compra']);
				
				$nome_localizacao = $conteudo['nome_localizacao'];

				if($conteudo['id_responsavel']){
					$dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ", "b.nome");
					$nome_responsavel = $dados_responsavel[0]['nome'];
				}else{
					$nome_responsavel = 'N/D';
				}

				if($conteudo['id_fornecedor']){
					$dados_fornecedor = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$conteudo['id_fornecedor']."' ", "nome");
					$nome_fornecedor = $dados_fornecedor[0]['nome'];
				}else{
					$nome_fornecedor = 'N/D';
				}

				if($conteudo['status_patrimonio'] == 1){
					$status_patrimonio = "Em Uso";
				}else if($conteudo['status_patrimonio'] == 2){
					$status_patrimonio = "Em Estoque";
				}else if($conteudo['status_patrimonio'] == 3){
					$status_patrimonio = "Vendido";
				}else if($conteudo['status_patrimonio'] == 4){
					$status_patrimonio = "Descartado";
				}else if($conteudo['status_patrimonio'] == 5){
					$status_patrimonio = "Doado";
				}else if($conteudo['status_patrimonio'] == 7){
					$status_patrimonio = "Manutenção";
				}

				$numero_patrimonio = $conteudo['numero_patrimonio'];
				
				if($conteudo['data_atualizacao']){
					$data_atualizacao = converteDataHora($conteudo['data_atualizacao']);
				}else{
					$data_atualizacao = "N/D";
				}

				echo "<tr>";
				 	echo "<td style= 'vertical-align: middle;'>$id_patrimonio</td>";
					echo "<td style= 'vertical-align: middle;'>$descricao</td>";
					echo "<td style= 'vertical-align: middle;'>$numero_patrimonio</td>";
					echo "<td style= 'vertical-align: middle;'>$data_compra</td>";
					echo "<td style= 'vertical-align: middle;'>R$ $valor_compra</td>";
					echo "<td style= 'vertical-align: middle;'>$nome_localizacao</td>";
					echo "<td style= 'vertical-align: middle;'>$nome_responsavel</td>";
					echo "<td style= 'vertical-align: middle;'>$nome_fornecedor</td>";
					echo "<td style= 'vertical-align: middle;'>$status_patrimonio</td>";
					echo "<td style= 'vertical-align: middle;'>$data_atualizacao</td>";
					echo "<td class=\"text-center\" style= 'vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=patrimonio-form&alterar=$id_patrimonio' title='Alterar'><i class='fa fa-pencil'></i></a></td>";

		                //fim

				echo "</tr>";
			}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

// FIM DO CONTEUDO
###################################################################################

$menos = $pagina - 1;
$mais = $pagina + 1;
$pgs = ceil($total / $maximo);

// Inicio e fim dos links
$ini_links = ((($pagina - $lim_links) > 1) ? $pagina - $lim_links : 1);
$fim_links = ((($pagina + $lim_links) < $pgs) ? $pagina + $lim_links : $pgs);

if($pgs > 1 ) {

    echo "<nav style=\"text-align: center;\">";
	    echo "<ul class=\"pagination\">";

	    // Mostragem de pagina
	    if($menos > 0) {                                    
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$menos\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"1\">Pri.</a></li>";
	    }else{
	        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
	        echo "<li class=\"disabled\"><a href=\"#\">Pri.</a></li>";
	    }

	    // Listando as paginas
	    for($i = $ini_links; $i <= $fim_links; $i++) {
	        if($i != $pagina) {                                        
	            echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$i\">$i</a></li>";
	        } else {
	            echo "<li class=\"active\"><a href=\"#\">$i <span class=\"sr-only\">(current)</span></a></li>";
	        }
	    }

	    if($mais <= $pgs) {
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$pgs\">Últ.</a></li>";
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$mais\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></li>";
	    }else{
	        echo "<li class=\"disabled\"><a href=\"#\">Últ.</a></li>";
	        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></a></li>";
	    }

	    echo "</ul>";
    echo "</nav>";
}
?>