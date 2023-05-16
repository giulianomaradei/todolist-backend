<?php
require_once(__DIR__."/System.php");


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$setor = $parametros['setor'];
$cargo = $parametros['cargo'];
$status = $parametros['status'];
$data = $parametros['data'];

$id_usuario = $_SESSION['id_usuario'];

if ($setor) {
    $filtro_setor = "AND a.id_setor = $setor";
}

if ($cargo) {
    $filtro_cargo = "AND a.id_cargo = $cargo";
}

if ($status) {
    $filtro_status = "AND a.status = $status";
}

if ($data) {
    $data = converteData($parametros['data']);

    $data_de = $data.' 00:00:00';
    $data_ate = $data.' 23:59:59';

    $filtro_data = "AND a.data BETWEEN '$data_de' AND '$data_ate'";
}

$filtros_query = "INNER JOIN tb_setor b ON a.id_setor = b.id_setor INNER JOIN tb_cargo f ON a.id_cargo = f.id_cargo AND (a.nome LIKE '%$letra%' OR a.descricao LIKE '%$letra%') $filtro_setor $filtro_status $filtro_data $filtro_cargo AND a.status != 3 ORDER BY data DESC";

//Maximo de registros por pagina
$maximo = 10;

//Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

//Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}

// Conta os resultados no total da query  
$dados = DBRead('','tb_selecao a',$filtros_query,"COUNT(*) AS 'num_registros'");

$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO

$dados = DBRead('', 'tb_selecao a', $filtros_query." LIMIT $inicio,$maximo", 'a.*, a.descricao as descricao_selecao, b.descricao, f.descricao as descricao_cargo');

/* echo '<pre>';
var_dump($dados);
echo '</pre>'; */

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
    echo "<th>Setor</th>";
    echo "<th>Cargo</th>";
    echo "<th>Nome</th>";
    echo "<th>Descrição</th>";
    echo "<th>Status</th>";
    echo "<th>Número de vagas</th>";
    echo "<th>Data</th>";
    echo "<th class=\"text-center\">Visualizar</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_selecao'];

        if ($conteudo['status'] == 1) {
            $status = 'Em andamento';

        } else if ($conteudo['status'] == 2) {
            $status = 'Encerrada';
        }
        echo '
        <script>
            $(function(){
                $(\'[data-toggle="tooltip"]\').tooltip();
            });
        </script>
        ';

        echo "<tr>";
        echo "<td>".$conteudo['descricao']."</td>";
        echo "<td>".$conteudo['descricao_cargo']."</td>";
        echo "<td>".$conteudo['nome']."</td>";
        echo "<td><span data-toggle=\"tooltip\" title=\"".$conteudo['descricao_selecao']."\">".limitarTexto($conteudo['descricao_selecao'], 30)."</span></td>";
        echo "<td>".$status."</td>";
        echo "<td>".$conteudo['n_vagas']."</td>";
        echo "<td>".converteDataHora($conteudo['data'])."</td>";
        echo "<td class=\"text-center\" onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-informacoes&idselecao=$id'\" style='cursor: pointer'>";

        echo "<a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=selecao-informacoes&idselecao=$id' title='Visualizar'><i class='fa fa fa-eye'></i></a>";

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