<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$select_categoria = addslashes($parametros['select_categoria']);

if($select_categoria){
   $filtro_categoria = "AND id_faq_categoria = '".$select_categoria."' ";
}

// Informações da query
$filtros_query = "WHERE pergunta like '%".$letra."%' ".$filtro_categoria." ";

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
$dados = DBRead('', 'tb_faq', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if ($inicio >= $total) {
	$inicio = 0;
	$pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDOS
//

$dados = DBRead('', 'tb_faq', $filtros_query . " ORDER BY id_faq DESC LIMIT $inicio,$maximo");
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
					echo "<th class='col-md-4'>Pergunta</th>";
					echo "<th class='col-md-4'>Resposta</th>";
					echo "<th class='col-md-2'>Categoria</th>";
					
					echo "<th class='text-center  class='col-md-1'>Opções</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {

				$id_faq = $conteudo['id_faq'];
				$pergunta = $conteudo['pergunta'];
				$resposta = $conteudo['resposta'];
				$id_faq_categoria = $conteudo['id_faq_categoria'];

				$dados_categoria = DBRead('', 'tb_faq_categoria', "WHERE id_faq_categoria = '".$id_faq_categoria."' ", "nome");
				$categoria = $dados_categoria[0]['nome'];
				echo "<tr>";
                    echo "<td style='vertical-align: middle;'>$id_faq</td>";
                     
					echo "<td style='vertical-align: middle;'><span data-toggle='tooltip' data-placement='right' title='".$pergunta."'>".limitarTexto($pergunta, 75)."</span></td>";
					echo "<td class='conteudo-editor' style='vertical-align: middle;'><span data-toggle='tooltip' data-placement='left' title='".$resposta."'>".limitarTexto($resposta, 75)."</span></td>";
					//echo "<td>".limitarTexto($pergunta, 75)."</td>";
					//echo "<td>".limitarTexto($resposta, 75)."</td>";
					
					echo "<td style='vertical-align: middle;'>".$categoria."</td>";
                    echo "<td style='vertical-align: middle;' class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=faq-form&alterar=$id_faq' title='Alterar'><i class='fa fa-pencil'></i></a></td>";
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

<script>
$(function () {
    $('[data-toggle="tooltip"]').css("max-heigth", "1000px !important");

    $('[data-toggle="tooltip"]').tooltip({html:true});     
    

})
</script>