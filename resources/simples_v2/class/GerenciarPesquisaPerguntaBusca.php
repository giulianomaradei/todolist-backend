<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);
$id_pesquisa = $parametros['id_pesquisa'];

// Informações da query
$filtros_query  = "INNER JOIN tb_pesquisa c ON a.id_pesquisa = c.id_pesquisa WHERE a.status != 0 AND descricao LIKE '%$letra%' AND c.status != 2 AND a.id_pesquisa = '$id_pesquisa' ORDER BY a.posicao ASC";

// Maximo de registros por pagina
$maximo = 10;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}   

// Conta os resultados no total da query  
$dados = DBRead('','tb_pergunta_pesquisa a',$filtros_query,"COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO
// 
$dados = DBRead('', 'tb_pergunta_pesquisa a',$filtros_query." LIMIT $inicio,$maximo");
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
    echo "<th class=\"col-md-7\">Pergunta/Descrição</th>";
    echo "<th class=\"col-md-1\">Posição</th>";

    echo "<th class=\"col-md-4 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $dado){
 	
        $id = $dado['id_pergunta_pesquisa'];
        $descricao = $dado['descricao'];
        $posicao = $dado['posicao'];
        $id_pesquisa = $dado['id_pesquisa'];
        echo "<tr>";
        echo "<td>$descricao</td>";
        echo "<td>$posicao</td>";
        echo "<td class=\"text-center\"><a style='color:#00008B;' href='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-pergunta-form&alterar=$id' title='Editar'><i class='fa fa-pencil'></i> Editar </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<a style='color:#8B0000;' href=\"class/PesquisaPergunta.php?excluir=$id&id_pesquisa=$id_pesquisa\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($descricao)."?')){ return false; } else { modalAguarde(); }\"><i class= 'fa fa-trash'></i> Excluir </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        echo "<a class='a_modalAguarde' style='color:#f0ad4e;' href=\"/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-pergunta-form-clonar&id_pergunta_pesquisa=$id\" title='Clonar'><i class='fa fa-clone' aria-hidden='true'></i> Clonar </a>";

        echo "</td>";
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
$fim_links = ((($pagina+$lim_links) < $pgs) ? $pagina+$lim_links : $pgs);

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