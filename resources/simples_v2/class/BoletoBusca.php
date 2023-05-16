<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$situacao = addslashes($parametros['situacao']);
$faturamento = addslashes($parametros['faturamento']);
$select_data = addslashes($parametros['select_data']);

if($select_data == 'emissao'){
	$variavel_busca = "a.titulo_data_emissao";
	$order_by = "ORDER BY a.titulo_data_emissao DESC";
}else if($select_data == 'vencimento'){
	$variavel_busca = "a.titulo_data_vencimento";
	$order_by = "ORDER BY a.titulo_data_vencimento DESC";
}else if($select_data == 'pagamento'){
	$variavel_busca = "a.pagamento_data";
	$order_by = "ORDER BY a.pagamento_data DESC";
}else{
	$variavel_busca = "a.data_sincronizacao";
	$order_by = "ORDER BY a.data_sincronizacao DESC";
}

if($situacao){
    $filtro_situacao = " AND a.situacao = '".$situacao."' ";
}

if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND (".$variavel_busca." BETWEEN '$data_de' AND '$data_ate')";

}else if($parametros['data_de'] && !$parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);

    $data_de = $data_de.' 00:00:00';

    $filtro_data = "AND ".$variavel_busca." >= '$data_de'";

}else if(!$parametros['data_de'] && $parametros['data_ate']){

    $data_ate = converteData($parametros['data_ate']);

    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND ".$variavel_busca." <= '$data_ate'";
}

if($faturamento){
	if($faturamento == 1){
		$filtro_faturamento = ' AND a.id_faturamento IS NOT NULL';
	}else{
		$filtro_faturamento = ' AND a.id_faturamento IS NULL';
	}
}else{
	$filtro_faturamento = '';
}

// Informações da query
$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%' OR a.titulo_nosso_numero LIKE '%$letra%' OR a.titulo_numero_documento LIKE '%$letra%') ".$filtro_data." ".$filtro_faturamento." ".$filtro_situacao." ";

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
$dados = DBRead('', 'tb_boleto a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_boleto a', $filtros_query . " ".$order_by." LIMIT $inicio,$maximo","a.*, b.*");
if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	} else {
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
	}
	echo "</p>";
} else {
    echo "<p class='text-center'><strong>Total de registros:</strong> $total</p><hr>";
	echo "<div class='table-responsive'>";
		echo "<table class='table table-hover' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class=\"col-md-1\">#</th>";
					echo "<th class=\"col-md-4\">Cliente</th>";
					echo "<th class=\"col-md-1\">Valor</th>";					
					echo "<th class=\"col-md-1\">Data de Emissão</th>";
					echo "<th class=\"col-md-1\">Data de Vencimento</th>";
					echo "<th class=\"col-md-1\">Data de Pagamento</th>";
					echo "<th class=\"col-md-1\">Data de Sincronização</th>";
					echo "<th class=\"col-md-1\">Situação</th>";
					echo "<th class=\"col-md-1 text-center\">Opções</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {

				$id = $conteudo['id_boleto'];
				$nome = $conteudo['razao_social'];
                $titulo_data_vencimento = converteDataHora($conteudo['titulo_data_vencimento']);                
                $titulo_valor = converteMoeda($conteudo['titulo_valor']);
                $titulo_data_emissao = converteData($conteudo['titulo_data_emissao']);
                $situacao = $conteudo['situacao'];
                
                if($conteudo['pagamento_data']){
                	$pagamento_data = explode(' ', $conteudo['pagamento_data']);
                	$pagamento_data = converteData($pagamento_data[0]);

                }else{
                	$pagamento_data = '-';
                }

                $data_sincronizacao = converteDataHora($conteudo['data_sincronizacao']);
                
                echo "<tr>";	
				 	echo "<td>$id</td>";
					echo "<td>$nome</td>";
					echo "<td>R$ $titulo_valor</td>";
					echo "<td>$titulo_data_emissao</td>";
					echo "<td>$titulo_data_vencimento</td>";
					echo "<td>$pagamento_data</td>";
					echo "<td>$data_sincronizacao</td>";
					echo "<td>$situacao</td>";
					echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=boleto-visualizar&visualizar=$id' title='Visualizar'><i class='fa fa-eye'></i></a></td>";
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