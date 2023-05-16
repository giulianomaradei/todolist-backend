<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$segmento = addslashes($parametros['segmento']);

if($parametros['id_lead_status']){
    $id_status = "AND a.id_lead_status = '".$parametros['id_lead_status']."'";
}

if($parametros['data']){
    $data = converteData($parametros['data']);

    $data1 = $data.' 00:00:00';
    $data2 = $data.' 23:59:59';

    $data = "AND a.data_inicio BETWEEN '$data1' AND '$data2'";
}

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_lead_status c ON a.id_lead_status= c.id_lead_status WHERE a.excluido = 1 AND (b.nome LIKE '%$letra%') $id_status $data";
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
$dados = DBRead('','tb_lead_negocio a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_lead_negocio a',$filtros_query." LIMIT $inicio, $maximo");
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
    echo "<th>Título</th>";
    echo "<th>Empresa/Pessoa</th>";
    echo "<th>Data de início</th>";
    echo "<th>Valor</th>";
    echo "<th>Status</th>";
    echo "<th class='text-center'>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_lead_negocio'];
        $titulo = $conteudo['titulo'];
        $nome = $conteudo['nome'];
        $data_inicio = converteData($conteudo['data_inicio']);
        $valor_total = converteMoeda($conteudo['valor_total']);
        $status = $conteudo['descricao'];
        $responsavel = $conteudo['id_usuario_responsavel'];

        echo "<tr>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$titulo";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$nome";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$data_inicio";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>R$ $valor_total";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$status";

        echo "<td class=\"text-center\">";
        if($responsavel == $_SESSION['id_usuario']){
            echo "<a href=\"class/LeadNegocio.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir negócio - ".addslashes($titulo)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a>";
        }
        echo "</td></tr>";
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