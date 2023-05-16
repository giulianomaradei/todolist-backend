<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$centro_custos_principal = addslashes($parametros['centro_custos_principal']);
$data_periodo_mes = addslashes($parametros['data_periodo_mes']);
$data_periodo_ano = addslashes($parametros['data_periodo_ano']);

if($data_periodo_mes && $data_periodo_ano){
    $filtro_data_referencia = " AND data_referencia = '".$data_periodo_ano."-".$data_periodo_mes."-01'";
}

if($centro_custos_principal){
    $filtro_centro_custos_principal = " AND id_centro_custos_principal = '".$centro_custos_principal."' ";
}

// Informações da query
//$filtros_query  = "INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos WHERE 1 = 1 AND $filtro_tipo $filtro_data ORDER BY a.id_centro_custos_rateio ASC";
$filtros_query  = "INNER JOIN tb_centro_custos b ON a.id_centro_custos_principal = b.id_centro_custos WHERE a.id_centro_custos_principal ".$filtro_centro_custos_principal." ".$filtro_data_referencia." ORDER BY a.id_centro_custos_rateio ASC";

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
$dados = DBRead('','tb_centro_custos_rateio a', $filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_centro_custos_rateio a', $filtros_query . " LIMIT $inicio,$maximo");
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
    echo "<th class=\"col-md-4\">Centro de Custos Principal</th>";
    echo "<th class=\"col-md-3\">Referência</th>";
    //echo "<th class=\"col-md-3\">Centro(s) de Custo(s) Secundário(s)</th>";
    echo "<th class=\"col-md-2 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $conteudo){
        $id = $conteudo['id_centro_custos_rateio'];
        $nome = $conteudo['nome'];
        
        $data_referencia = $conteudo['data_referencia'];

        $meses = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            
        );    

        $data_referencia = explode('-', $data_referencia);
        $mes = $data_referencia[1];
        $ano = $data_referencia[0];


        echo "<tr>";
            echo "<td>".$nome."</td>";
            echo "<td>".$meses[$mes]." de ".$ano."</td>";
            //echo "<td><span data-toggle='tooltip' title='".$texto."'>".$total."</span></td>";


            echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=rateio-mensal-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href=\"class/RateioMensal.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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

<script>
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>