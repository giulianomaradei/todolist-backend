<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$status = addslashes($parametros['status']);

if($status == '1'){
    $filtro_data_conclusao = ' AND a.data_conclusao IS NOT NULL';
}else if($status == '2'){
    $filtro_data_conclusao = ' AND a.data_conclusao IS NULL';
}else{
    $filtro_data_conclusao = '';
}

// Informações da query
$filtros_query  = "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_plano c ON b.id_plano = c.id_plano INNER JOIN tb_pessoa d ON b.id_pessoa = d.id_pessoa WHERE d.nome LIKE '%$letra%' $filtro_data_conclusao ORDER BY d.nome ASC";

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
$dados = DBRead('','tb_redes_ativacao a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_redes_ativacao a',$filtros_query." LIMIT $inicio, $maximo", "a.*, b.*, d.nome AS 'nome', c.nome AS 'plano', c.cod_servico, b.nome_contrato");
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
    echo "<th class=\"col-md-8\">Cliente - Contrato</th>";
    echo "<th class=\"col-md-2\">Status</th>";
    echo "<th class=\"col-md-2 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_redes_ativacao'];
        $data_conclusao = $conteudo['data_conclusao'];
        $id_contrato_plano_pessoa = $conteudo['id_contrato_plano_pessoa'];
        $plano = $conteudo['plano'];
        $nome = $conteudo['nome'];
        $servico = getNomeServico($conteudo['cod_servico']);
        $nome_contrato = $conteudo['nome_contrato'];
        
        if($data_conclusao){
            $status = 'Concluída';
            $class = '';
        }else{
            $status = 'Pendente';
            $class = ' class="warning"';
        }

        echo "<tr$class>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=ativacao-redes-form&alterar=$id'\" style='cursor: pointer'>$nome";

        if($nome_contrato){
            echo " (".$nome_contrato.")";
        }

        echo " - $servico - $plano ($id_contrato_plano_pessoa)</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=ativacao-redes-form&alterar=$id'\" style='cursor: pointer'>$status</td>";
        echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=ativacao-redes-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a></td>";
        echo "</tr$class>";
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