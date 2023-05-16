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
    $tipo = "AND tipo_monitoria = '".$parametros['tipo']."' ";
}

if($parametros['classificacao']){
    $classificacao = "AND classificacao_atendente = '".$parametros['classificacao']."' ";
}

$filtros_query = "WHERE id_monitoria_mes $mes $ano $tipo $classificacao AND status = 1 ORDER BY data_referencia DESC";

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
    echo "<th>Mês referência</th>";
    echo "<th>Canal de atendimento</th>";
    echo "<th>Classificação de atendente</th>";
    echo "<th>Total de pontos em quesitos</th>";
    echo "<th>Número de quesitos</th>";
    echo "<th class='text-center'>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_monitoria_mes'];
        $soma_total = $conteudo['soma_total_pontos_quesitos'];
        $data_referencia = $conteudo['data_referencia'];
        $tipo_monitoria = $conteudo['tipo_monitoria'];
        $classificacao = $conteudo['classificacao_atendente'];

        if ($tipo_monitoria == 1) {
            $tipo_monitoria = 'Telefone';
            
        } else if ($tipo_monitoria == 2) {
            $tipo_monitoria = 'Texto';
        } 

        if ($classificacao == 1) {
            $classificacao = 'Em treinamento';
            
        } else if ($classificacao == 2) {
            $classificacao = 'Período de experiência';

        } else if ($classificacao == 3) {
            $classificacao = 'Efetivado';

        }

        $qtd_quesitos = DBRead('', 'tb_monitoria_mes_quesito', "WHERE id_monitoria_mes = '$id'", 'COUNT(*) as cont');

        $qtd_quesitos = $qtd_quesitos[0]['cont'];

        $arrayData = explode("-",$data_referencia);

        $mes = $arrayData[1];
        $ano = $arrayData[0];
        
        $data = $arrayData[1].'/'.$arrayData[0];
        
        echo "<tr>";
        echo "<td>$data</td>";
        echo "<td>$tipo_monitoria</td>";
        echo "<td>$classificacao</td>";
        echo "<td>$soma_total</td>";
        echo "<td>$qtd_quesitos</td>";
        echo "<td class=\"text-center\">
                <a href='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-formulario-form&alterar=$id' title='Alterar'>
                    <i class='fa fa-pencil'></i>
                </a>&nbsp
                <a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-formulario-form&clonar=$id' title='Clonar'>
                    <i class='fa fa-clone' style='color:#FF8C00;'></i>
                </a>&nbsp
                <a  href=\"class/MonitoriaFormulario.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir formulário de avaliação ".addslashes($data)."?')) { return false; } else { modalAguarde(); }\">
                    <i class='fa fa-trash' style='color:#b92c28;'></i>
                </a>
            </td>";
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