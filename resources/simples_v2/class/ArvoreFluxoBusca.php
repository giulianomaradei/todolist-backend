<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query
$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa=b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%') AND (a.status = '1' OR a.status = '7') AND c.cod_servico = 'call_suporte' ORDER BY b.nome";

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
$dados = DBRead('', 'tb_contrato_plano_pessoa a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_contrato_plano_pessoa a', $filtros_query . " LIMIT $inicio,$maximo","a.*, b.id_pessoa, b.nome, b.razao_social, c.cod_servico, c.nome AS 'plano', a.nome_contrato");
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
	echo "<th class=\"col-md-10\">Contrato</th>";
	echo "<th class=\"col-md-2 text-center\">Opções</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($dados as $conteudo) {
		$id_contrato_plano_pessoa = $conteudo['id_contrato_plano_pessoa'];
        $nome_pessoa = $conteudo['nome'];
        $plano = $conteudo['plano'];
        $servico = getNomeServico($conteudo['cod_servico']);
		
		if($conteudo['nome_contrato']){
			$nome_pessoa = $nome_pessoa.' ('.$conteudo['nome_contrato'].')';
		}
		echo "<td>".$nome_pessoa. ' - ' .$servico. ' - ' .$plano.' ('.$id_contrato_plano_pessoa.")</td>";

		$arvore = DBRead('', 'tb_arvore_contrato',"WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

		if($arvore){
			echo "<td class=\"text-center\"><a $disabled href=\"class/Arvore.php?excluir_arvore=$id_contrato_plano_pessoa\" title='Excluir' onclick=\"if (!confirm('Excluir árvore " . addslashes($nome_pessoa) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
		}else{
			echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=arvore-clonar&clonar=$id_contrato_plano_pessoa' title='Clonar'><i class='fa fa-clone' style='color:#FF8C00;' ></i></a></td>";
		}
		
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