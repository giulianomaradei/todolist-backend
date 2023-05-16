<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query
$filtros_query  = "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_plano c ON b.id_plano = c.id_plano INNER JOIN tb_pessoa d ON b.id_pessoa = d.id_pessoa";
// $filtros_query  = "WHERE nome LIKE '%".$letra."%' ".$filtro_exibicao." ORDER BY nome ASC";


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
$dados = DBRead('','tb_acrescimo_desconto a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_acrescimo_desconto a',$filtros_query." LIMIT $inicio,$maximo", "a.*, b.*, d.nome AS 'nome', c.nome AS 'plano', c.cod_servico, b.nome_contrato");
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
        echo "<th class=\"col-md-4\">Contrato</th>";
        echo "<th class=\"col-md-2 \">Tipo</th>";
        echo "<th class=\"col-md-1 \">Valor</th>";
        echo "<th class=\"col-md-2 \">Referência</th>";
        echo "<th class=\"col-md-2 text-center\">Fatutamento Gerado</th>";
        echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_acrescimo_desconto'];
        $id_contrato_plano_pessoa = $conteudo['id_contrato_plano_pessoa'];
        $plano = $conteudo['plano'];
        $nome = $conteudo['nome'];
        $servico = getNomeServico($conteudo['cod_servico']);
        $nome_contrato = $conteudo['nome_contrato'];
        $valor = converteMoeda($conteudo['valor']);

        $contrato = $nome;
        if($nome_contrato){
            $contrato .= " (".$nome_contrato.")";
        }
        $contrato .= " - $servico - $plano ($id_contrato_plano_pessoa)";
        if($conteudo['tipo'] == 'acrescimo'){
            $tipo = "Acréscimo";
        }else{
            $tipo = "Desconto";
        }

        $dados_meses = array(
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

        $data_referencia = explode("-", $conteudo['data_referencia']);
        $mes_referencia = $data_referencia[1];
        $ano_referencia = $data_referencia[0];

        if($conteudo['id_faturamento']){
            $faturamemto_check = "<i class='fas fa-check' style='color:#5cb85c;' title='Sim'></i>";
        }else{
            $faturamemto_check = "<i class='fas fa-times' style='color:#d9534f;' title='Não'></i>";
        }

        echo "<tr>";    
            echo "<td style='vertical-align: middle;'>$contrato</td>";
            echo "<td style='vertical-align: middle;'>".$tipo."</td>";
            echo "<td style='vertical-align: middle;'>R$ ".$valor."</td>";
            echo "<td style='vertical-align: middle;'>".$dados_meses[$mes_referencia]."/".$ano_referencia."</td>";
            echo "<td class='text-center' style='vertical-align: middle;'>".$faturamemto_check."</td>";
            if($conteudo['id_faturamento']){
                echo "<td class='text-center' style='vertical-align: middle;'></td>";

            }else{
                echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=acrescimo-desconto-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/AcrescimoDesconto.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";

            }
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