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
// INICIO DO CONTEÚDOs
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
    $collapse = '';
	$collapse_icon = 'plus';
	
    foreach ($dados as $conteudo) {

        $id_faq = $conteudo['id_faq'];
        $pergunta = $conteudo['pergunta'];
        $resposta = $conteudo['resposta'];
        $id_faq_categoria = $conteudo['id_faq_categoria'];
        $dados_categoria = DBRead('', 'tb_faq_categoria', "WHERE id_faq_categoria = '".$id_faq_categoria."' ", "nome");
        $categoria = $dados_categoria[0]['nome'];
        
        ?>

        <style>
            .conteudo-editor img{
                max-width: 100% !important;
                max-height: 100% !important;
                height: 100% !important;
            }
          
        </style>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="form-group">
                                <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Categoria: <?=$categoria?><br><strong><?=$pergunta?></strong></h3>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="panel-title text-right pull-right">
                                    <button data-toggle="collapse" data-target="#accordionRelatorio_<?=$id_faq?>" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse_<?=$id_faq?>" class="fa fa-<?=$collapse_icon?>"></i></button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div id="accordionRelatorio_<?=$id_faq?>" class="panel-collapse collapse accordionRelatorio <?=$collapse?>">
                    <div class="panel-body">	                		
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="conteudo-editor"><?=$resposta?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            
    }
		
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

$('.accordionRelatorio').on('shown.bs.collapse', function(){
    var i_collapse_ = $(this).attr('id').split("_");
    i_collapse_ = '#i_collapse_'+i_collapse_[1];
    $(i_collapse_).removeClass("fa fa-plus").addClass("fa fa-minus");
});
$('.accordionRelatorio').on('hidden.bs.collapse', function(){
    var i_collapse_ = $(this).attr('id').split("_");
    i_collapse_ = '#i_collapse_'+i_collapse_[1];
    $(i_collapse_).removeClass("fa fa-minus").addClass("fa fa-plus");
});


$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>