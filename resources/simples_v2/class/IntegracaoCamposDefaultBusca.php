<?php

require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$id_contrato_plano_pessoa = $parametros['id_contrato_plano_pessoa'];
$id_area_problema_busca = $parametros['id_area_problema_busca'];

if ($id_area_problema_busca ) {
    $filtro_area_problema = "AND c.id_area_problema = $id_area_problema_busca";
}

// Informações da query
$filtros_query  = "LEFT JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa $filtro_area_problema GROUP BY a.id_subarea_problema, a.id_contrato_plano_pessoa, b.descricao, c.nome ORDER BY c.nome, b.descricao ASC";

// Maximo de registros por pagina
$maximo = 500;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}   

// Conta os resultados no total da query  
$dados = DBRead('','tb_integracao_valores_default a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_integracao_valores_default a',$filtros_query." LIMIT $inicio,$maximo", "a.id_subarea_problema, a.id_contrato_plano_pessoa, b.descricao, c.nome");
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
        echo "<th>Área do problema</th>";
        echo "<th>Subarea do problema</th>";
        echo "<th class=\"text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_integracao_valores_default'];
        $nome_area_problema = $conteudo['nome'];
        $descricao_sub_area = $conteudo['descricao'];
        $id_contrato_plano_pessoa = $conteudo['id_contrato_plano_pessoa'];
        $id_subarea_problema = $conteudo['id_subarea_problema'];

        $valor = $array_valores[$cod_campo][$value_default];

        if ($nome_area_problema == '') {
            $nome_area_problema = "N/D";
        }

        if ($descricao_sub_area == '') {
            $descricao_sub_area = "N/D";
        }

        echo "<tr>";    
            echo "<td>".$nome_area_problema."</td>";
            echo "<td>".$descricao_sub_area."</td>";

            echo "<td class=\"text-center\"><a href=\"/api/iframe?token=<?php echo $request->token ?>&view=integracao-campos-default-informacoes&id_contrato_plano_pessoa=$id_contrato_plano_pessoa&id_sub_area_problema=$id_subarea_problema\" title='Visualizar'><i class='fa fa-eye'></i></a></td>";

            //echo "<td class=\"text-center\"><a href=\"class/IntegracaoCamposDefault.php?acao=exclui_campo&id_integracao_valores_default=$id&id_contrato_plano_pessoa=$id_contrato_plano_pessoa\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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