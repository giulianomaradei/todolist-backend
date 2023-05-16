<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query
//$filtros_query  = "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_plano c ON b.id_plano = c.id_plano INNER JOIN tb_pessoa d ON b.id_pessoa = d.id_pessoa WHERE d.nome LIKE '%$letra%' ORDER BY d.nome ASC";
$filtros_query  = "INNER JOIN tb_integracao b ON a.id_integracao = b.id_integracao WHERE b.nome LIKE '%$letra%' ORDER BY b.nome ASC";

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
$dados = DBRead('','tb_integracao_parametro a',$filtros_query,"COUNT(*) AS 'num_registros'", "a.id_integracao_parametro, a.codigo, a.nome AS nome_parametro, a.tipo, b.nome AS nome_sistema");
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
//$dados = DBRead('', 'tb_integracao',$filtros_query." LIMIT $inicio,$maximo", "a.*, b.*, d.nome AS 'nome', c.nome AS 'plano', c.cod_servico, b.nome_contrato");
$dados = DBRead('', 'tb_integracao_parametro a',$filtros_query." LIMIT $inicio,$maximo", "a.id_integracao_parametro, a.codigo, a.nome AS nome_parametro, a.tipo, b.nome AS nome_sistema");

if(!$dados){
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if(!$letra){
        echo "Não foram encontrados registros!";
    }else{
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
}else{
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover' style='font-size: 14px;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th class=\"col-md-5\">Sistema de gestão</th>";
    echo "<th class=\"col-md-6\">Nome do parâmetro</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $conteudo){

        $id = $conteudo['id_integracao_parametro'];
        $nome_sistema = $conteudo['nome_sistema'];
        $nome_parametro = $conteudo['nome_parametro'];

        echo "<tr>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=parametros-integracao-form&alterar=$id'\" style='cursor: pointer'>$nome_sistema";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=parametros-integracao-form&alterar=$id'\" style='cursor: pointer'>$nome_parametro";

        echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=parametros-integracao-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a></td>";
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
    }

    // Listando as paginas
    for($i = $ini_links; $i <= $fim_links; $i++) {
        if($i != $pagina) {                                        
            echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$i\">$i</a></li>";
        }else {
            echo "<li class=\"active\"><a href=\"#\">$i <span class=\"sr-only\">(current)</span></a></li>";
        }
    }

    if($mais <= $pgs) {
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$pgs\">Últ.</a></li>";
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$mais\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></li>";
    }else{
        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></a></li>";
    }

    echo "</ul>";
    echo "</nav>";
}
?>