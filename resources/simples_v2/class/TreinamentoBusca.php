<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$setor = $parametros['setor'];
$objetivo = $parametros['objetivo'];
$avaliacao = $parametros['avaliacao'];

if ($setor) {
    $filtro_setor = "AND b.id_perfil_sistema = $setor";
}

if ($objetivo) {
    $filtro_objetivo = "AND a.objetivo = $objetivo";
}

if ($parametros['data_de'] && $parametros['data_ate']) {

    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND (a.data_inicio BETWEEN '$data_de' AND '$data_ate')";

} else if ($parametros['data_de'] && !$parametros['data_ate']) {

    $data_de = converteData($parametros['data_de']);

    $data_de = $data_de.' 00:00:00';

    $filtro_data = "AND a.data_inicio >= '$data_de'";

} else if (!$parametros['data_de'] && $parametros['data_ate']) {

    $data_ate = converteData($parametros['data_ate']);

    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND a.data_inicio <= '$data_ate'";
}

if ($avaliacao == 1) {
    $filtro_avaliacao = "AND ((SELECT count(*) FROM tb_treinamento_participante c WHERE c.id_treinamento = a.id_treinamento) != (SELECT count(*) FROM tb_treinamento_participante d INNER JOIN tb_treinamento_avaliacao e ON d.id_treinamento_participante = e.id_treinamento_participante WHERE d.id_treinamento = a.id_treinamento))";

} else if ($avaliacao == 2) {
    $filtro_avaliacao = "AND ((SELECT count(*) FROM tb_treinamento_participante c WHERE c.id_treinamento = a.id_treinamento) = (SELECT count(*) FROM tb_treinamento_participante d INNER JOIN tb_treinamento_avaliacao e ON d.id_treinamento_participante = e.id_treinamento_participante WHERE d.id_treinamento = a.id_treinamento))";
}

// Informações da query
$filtros_query = "INNER JOIN tb_treinamento_perfil_sistema b ON a.id_treinamento = b.id_treinamento WHERE (a.nome LIKE '%$letra%' OR a.descricao LIKE '%$letra%') $filtro_setor $filtro_objetivo $filtro_data $filtro_avaliacao AND a.status = 1 GROUP BY a.id_treinamento ORDER BY a.data_inicio DESC";

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
$dados = DBRead('', 'tb_treinamento a',$filtros_query,"COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO 
$dados = DBRead('', 'tb_treinamento a', $filtros_query . " LIMIT $inicio,$maximo", 'a.*');
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
    echo "<th>Nome</th>";
    echo "<th>Perfis</th>";
    echo "<th>Descrição</th>";
    echo "<th>Data de início</th>";
    echo "<th>Objetivo</th>";
    echo "<th>Carga horaria</th>";
    echo "<th>Avaliar em</th>";
    echo "<th class='col-md-2 text-center'>Ações</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_treinamento'];
        $nome = $conteudo['nome'];
        $descricao = $conteudo['descricao'];
        $data = converteDataHora($conteudo['data_inicio']);
        $objetivo = $conteudo['objetivo'];
        $carga_horaria = converteSegundosHoras($conteudo['carga_horaria'] * 60);
        $avaliar_em = converteData($conteudo['avaliar_em']);

        $setores = DBRead('', 'tb_treinamento_perfil_sistema a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_treinamento = $id ", 'b.nome');
        
        $nomes_setores = '';
        
        if($setores){
            foreach($setores as $s){
                $nomes_setores .= $s['nome'].", ";
            }
        }

        $nomes_setores = substr($nomes_setores, 0, strlen($nomes_setores) - 2);

        if ($objetivo == 1) {
            $legenda = 'Qualificação';
        } else if ($objetivo == 2){
            $legenda = 'Reciclagem';
        } else {
            $legenda = 'N/D';
        }

        echo "<td>$nome</td>";
        echo "<td>$nomes_setores</td>";
        echo "<td>".limitarTexto($descricao, 40)."</td>";
        echo "<td>$data</td>";
        echo "<td>$legenda</td>";
        echo "<td>$carga_horaria</td>";
        echo "<td>$avaliar_em</td>";

        echo "<td class=\"text-center\">
                <a href='/api/iframe?token=<?php echo $request->token ?>&view=treinamento-informacoes&idtreinamento=$id' title='visualizar'>
                    <i class='fa fa-eye'></i>
                </a>
                &nbsp;
                <a href='/api/iframe?token=<?php echo $request->token ?>&view=treinamento-form&alterar=$id' title='Alterar'>
                    <i class='fa fa-pencil'></i>
                </a>
                &nbsp;
                <a href=\"class/Treinamento.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\">
                    <i class='fa fa-trash' style='color:#b92c28;'></i>
                </a>
            </td>";
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
