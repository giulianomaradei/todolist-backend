<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query
$filtros_query = "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (a.nome LIKE '%$letra%' OR a.razao_social LIKE '%$letra%') AND a.status != 2 AND b.status = 1 AND (b.id_perfil_sistema = '3' OR b.id_perfil_sistema = '13' OR b.id_perfil_sistema = '15') ORDER BY a.nome ASC";

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
$dados = DBRead('', 'tb_pessoa a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_pessoa a', $filtros_query . " LIMIT $inicio,$maximo");
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
	echo "<th class=\"col-md-6\">Nome</th>";
	echo "<th class=\"col-md-5\">Líder Direto</th>";
	echo "<th class=\"col-md-1 text-center\">Opções</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($dados as $conteudo) {
		$dados_lider  = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '".$conteudo['lider_direto']."'");

		$id = $conteudo['id_usuario'];
		$nome = $conteudo['nome'];
		$lider = $dados_lider[0]['nome'];
		if(!$lider){
			$lider = "N/D";
			$classe = "class = 'warning'";
		}else{
			$classe ='';
		}
		echo "<tr $classe onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=equipe-form&alterar=$id'\" style='cursor: pointer'>";
		echo "<td>$nome";

		if($nome_contrato){
			echo " (".$nome_contrato.") ";
		}

		if($dados_vinculos){
			foreach($dados_vinculos as $conteudo){
				echo '<br><i class="fa fa-link"></i> <a href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=' . $conteudo['id_pessoa_pai'] . '">' . $conteudo['nome'] . '</a>';
			}
		}
		echo "</td>";
		echo "<td>$lider</td>";
		echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=equipe-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a></td>";
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