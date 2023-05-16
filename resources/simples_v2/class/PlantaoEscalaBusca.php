<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

$data_referencia = $parametros['data_referencia'];

if($data_referencia){
    $filtro_data_referencia = " AND data_referencia = '".$data_referencia."' ";
}


// Informações da query
$filtros_query = "WHERE id_plantonista_redes_mes ".$filtro_data_referencia." ";

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
$dados = DBRead('', 'tb_plantonista_redes_mes', $filtros_query, "COUNT(*) AS 'num_registros'");
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

$dados = DBRead('', 'tb_plantonista_redes_mes', $filtros_query . " ORDER BY data_referencia DESC LIMIT $inicio,$maximo");

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
					echo "<th class='col-md-1'>#</th>";
					echo "<th class='col-md-3'>Referência</th>";
					echo "<th class='col-md-3'>Valor da Diária</th>";
					echo "<th class='col-md-3'>Porcentagem da Comissão</th>";

					echo "<th class=\"text-center col-md-2\">Opções</th>";

				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {


                $id_plantonista_redes_mes = $conteudo['id_plantonista_redes_mes'];
                $data_referencia = $conteudo['data_referencia'];
                $valor_diaria = $conteudo['valor_diaria'];
                $porcentagem_comissao = $conteudo['porcentagem_comissao'];
                
				$meses = array(
					"01" => "Janeiro",
					"02" => "Fevereiro",
					"03" => "Março",
					"04" => "Abril",
					"05" => "Maio",
					"06" => "Junho",
					"07" => "Julho",
					"08" => "Agosto",
					"09" => "Setembro",
					"10" => "Outubro",
					"11" => "Novembro",
					"12" => "Dezembro",
					
				);
				
				$data_referencia = explode('-', $data_referencia);
				$mes = $data_referencia[1];
				$ano = $data_referencia[0];

				echo "<tr>";
				 	echo "<td>".$id_plantonista_redes_mes."</td>";
					echo "<td>".$meses[$mes]." de ".$ano."</td>";
                    echo "<td>R$ ".converteMoeda($valor_diaria, 'moeda')."</td>";
                    echo "<td>".$porcentagem_comissao."%</td>";

					echo '
					<td class="text-center">
						<form action="/api/iframe?token=<?php echo $request->token ?>&view=plantao-escala-form" method="post">
							<input type="hidden" name="id_plantonista_redes_mes" value="'.$id_plantonista_redes_mes.'" >
							<a><button type="submit" title="Alterar" style="border:0px; background-color:transparent;"><i class="fa fa-pencil"></i></button></a>
						</form>
					</td>';


				

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