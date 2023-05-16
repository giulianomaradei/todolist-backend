<?php
require_once(__DIR__."/System.php");


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

if($parametros['mes']){
    $mes = "AND data_referencia LIKE '%-".$parametros['mes']."-01' ";
}

if($parametros['ano']){
    $ano = "AND data_referencia LIKE '".$parametros['ano']."%-01' ";
}

if($parametros['tipo']){
    $tipo = "AND tipo_monitoria = ".$parametros['tipo'];
}

if($parametros['classificacao']){
    $classificacao = "AND classificacao_atendente = ".$parametros['classificacao'];
}

$filtros_query = "WHERE status = 1 $mes $ano $tipo $classificacao AND status = 1 ORDER BY data_referencia DESC";

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
$dados = DBRead('','tb_monitoria_mes',$filtros_query,"COUNT(*) AS 'num_registros'");

if($dados){
    $total = sizeof($dados);
}

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO

$dados = DBRead('', 'tb_monitoria_mes', $filtros_query." LIMIT $inicio,$maximo");

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
    echo "<th class='col-md-3'>Mês referência</th>";
    echo "<th class='col-md-3'>Canal de atendimento</th>";
    echo "<th class='col-md-3'>Classificação de atendentea</th>";
    echo "<th class='col-md-3 text-center'>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_monitoria_mes'];
        $data_referencia = $conteudo['data_referencia'];
        $tipo_monitoria = $conteudo['tipo_monitoria'];
        $classificacao_atendente = $conteudo['classificacao_atendente'];

        $verifica = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE data_referencia = '$data_referencia' AND id_monitoria_mes = $id");

        $arrayData = explode("-",$data_referencia);

        $mes = $arrayData[1];
        $ano = $arrayData[0];
        
        $data = $arrayData[1].'/'.$arrayData[0];

        $dateTime = new DateTime($data_referencia);
        $dateTime->modify('first day of next month'); // deixar next month
        $dia_liberar = $dateTime->format("Y-m-d");

        $hoje = getDataHora();
        $hoje = explode(' ', $hoje);

        if ($hoje[0] >= $dia_liberar) {
            $disabled = true;

        } else {
            $disabled = false;
        }

        if ($tipo_monitoria == 1) {
            $legenda_tipo = 'Telefone';

        } else if ($tipo_monitoria == 2) {
            $legenda_tipo = 'Texto';
        }

        if ($classificacao_atendente  == 1) {
            $legenda_classificacao = 'Em Treinamento';

        } else if ($classificacao_atendente  == 2) {
            $legenda_classificacao = 'Período de Experiência';

        } else if ($classificacao_atendente == 3) {
            $legenda_classificacao = 'Efetivado';
        }

        //$disabled = true;

        if($verifica){
             $btn = "<a class='btn btn-success btn-sm' title='Gerar plano de ação' disabled style='min-width: 150px;'>
                    <i class='fa fa-check'></i> Gerado!
                </a>&nbsp
            </td>";
        }else{
            
            if($disabled == true){
                $btn = "<a class='btn btn-primary btn-sm' href='class/MonitoriaPlanoAcao.php?gerar=$id' title='Gerar plano de ação' style='min-width: 150px;'>
                        <i class='fa fa-refresh'></i> Gerar plano de ação
                    </a>&nbsp
                </td>";
            }else{
                $btn = "<a class='btn btn-default' title='Ainda não é possível gerar plano de ação' style='min-width: 150px;' disabled>
                        <i class='fa fa-refresh'></i> Indisponível
                    </a>&nbsp
                </td>";
            }
        }

        echo "<tr>";
        echo "<td>$data</td>";
        echo "<td>$legenda_tipo</td>";
        echo "<td>$legenda_classificacao</td>";
        echo "<td class='text-center'>$btn</td>";
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