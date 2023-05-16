<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$id_rd_conversao = $parametros['id_rd_conversao'];

// Informações da query
$filtros_query = "WHERE (nome LIKE '%$letra%' OR razao_social LIKE '%$letra%') AND status != 2 ORDER BY nome ASC";

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
$dados = DBRead('', 'tb_pessoa', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_pessoa', $filtros_query . " LIMIT $inicio,$maximo");
if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	} else {
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"!";
	}
	echo "</p>";
} else {
	echo "<div class='table-responsive'>";
	echo "<table class='table table-hover' style='font-size: 14px;'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th class=\"col-md-1\">#</th>";
	echo "<th class=\"col-md-2\">Nome</th>";
	echo "<th class=\"col-md-3\">Razão Social</th>";
	echo "<th class=\"col-md-2\">Cidade</th>";
	echo "<th class=\"col-md-1 text-center\">Opções</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($dados as $conteudo) {
		$id = $conteudo['id_pessoa'];
		$nome = $conteudo['nome'];
		$nome_contrato = $conteudo['nome_contrato'];
		$razao_social = $conteudo['razao_social'];
		$id_cidade = $conteudo['id_cidade'];
		$data_atualizacao = converteDataHora($conteudo['data_atualizacao']);
		$status = $conteudo['status'];
		$dados_cidade = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '$id_cidade'", 'a.nome, b.sigla');
		$cidade = $dados_cidade[0]['nome'];
		$estado = $dados_cidade[0]['sigla'];

		if ($status == '0') {
			echo "<tr class='warning'>";
		} else {
			echo "<tr>";
		}
		echo "<td>$id";
		echo "<td><a href='/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=$id' target='_blank'>$nome</a>";

		if($nome_contrato){
			echo " (".$nome_contrato.") ";
		}

		echo "</td>";
		echo "<td>$razao_social</td>";
		echo "<td>$cidade - $estado</td>";
		$disabled = "";
		if($id == 2){
			$disabled = "style='pointer-events:none; opacity: 0.4;'";
		}
		echo "<td class=\"text-center\">
				<a href='/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=$id' target='_blank'><i class='fa fa-eye'></i></a>
				&nbsp&nbsp
				<a href='/api/iframe?token=<?php echo $request->token ?>&view=vinculo-pessoa-form&vincular=$id&id_rd_conversao=$id_rd_conversao' title='Criar Pessoa e vincular a esta'>
					<button class='btn btn-xs btn-primary'>
						<i class='fa fa-address-card-o'></i>
						<i class='fa fa-link'></i>
					</button>
				</a>
			</td>";
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