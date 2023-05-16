<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$numero = addslashes($parametros['numero']);
$id = addslashes($parametros['id']);

$status = addslashes($parametros['status']);

if($numero){
    // Informações da query
    $filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.numero LIKE '%$numero%' ORDER BY a.id_nfs DESC";
}

if($id){
    // Informações da query
    $filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_nfs = '$id' ORDER BY a.id_nfs DESC";
}

if($status){
    // Informações da query
    $filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '".$status."'  ORDER BY a.id_nfs DESC";
}

if(!$numero && !$id & !$status){
    // Informações da query
    $filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa ORDER BY a.id_nfs DESC";
}

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
$dados = DBRead('', 'tb_nfs a', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if ($inicio >= $total) {
	$inicio = 0;
	$pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO
//
$dados = DBRead('', 'tb_nfs a', $filtros_query . " LIMIT $inicio,$maximo","a.*, b.*, a.status AS status_nota, a.numero AS numero_nota");
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
					echo "<th class=\"col-md-1\">#</th>";
					echo "<th class=\"col-md-4\">Número da Nota</th>";
					echo "<th class=\"col-md-4\">Cliente</th>";
					echo "<th class=\"col-md-1\">Status</th>";
					echo "<th class=\"col-md-1\">Data Criação</th>";
					echo "<th class=\"col-md-1 text-center\">Opções</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {

				$id = $conteudo['id_nfs'];
				$nome = $conteudo['razao_social'];

				if($conteudo['data_criacao']){
					$data = converteDataHora($conteudo['data_criacao']);
				}else{
					$data = "Não emitida ainda";
				}

				if($conteudo['status_nota'] == 'nao enviado'){
					$status_nota = 'Não Enviada';
				}else if($conteudo['status_nota'] == 'negada'){
					$status_nota = '<span style="color:#B22222;">'.ucfirst($conteudo['status_nota']).'</span>';
				}else{
					$status_nota = ucfirst($conteudo['status_nota']);
				}

				$texto_confirmacao = $nome.' ('.$data.')';
				
				if($conteudo['numero_nota']){
					$numero_nota = $conteudo['numero_nota'];
				}else{
					$numero_nota = "N/D";
				}
               
				echo "<tr>";
					echo "<td>$id</td>";
					echo "<td>$numero_nota</td>";
					echo "<td>$nome</td>";
					echo "<td>$status_nota</td>";
					echo "<td>$data</td>";
					
                    echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=sistema-nfs-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a></td>";

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