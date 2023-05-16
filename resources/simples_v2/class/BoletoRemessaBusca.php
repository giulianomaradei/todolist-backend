<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);
    $filtro_data = "AND data_gerado BETWEEN '$data_de' AND '$data_ate'";

}else if($parametros['data_de'] && !$parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $filtro_data = "AND data_gerado >= '$data_de'";

}else if(!$parametros['data_de'] && $parametros['data_ate']){
    $data_ate = converteData($parametros['data_ate']);
    $filtro_data = "AND data_gerado <= '$data_ate'";
}

// Informações da query
$filtros_query  = " WHERE nome_arquivo LIKE '%".$letra."%' OR id_remessa_bancaria = '".$letra."' ".$filtro_data." ORDER BY id_remessa_bancaria DESC";

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
$dados = DBRead('','tb_remessa_bancaria',$filtros_query,"COUNT(*) AS 'num_registros'");
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

$dados = DBRead('', 'tb_remessa_bancaria',$filtros_query." LIMIT $inicio,$maximo");

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
    echo "
    		<th class='col-md-1'>#</th>
            <th class='col-md-2'>Nome do Arquivo</th>
            <th class='col-md-2'>Data da Remessa</th>
            <th class='col-md-2'>Gerado Por</th>
            <th class='col-md-2'>Tipo de Remessa</th>
            <th class='col-md-1 text-center'>Download da Remessa</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $conteudo){

        $id_remessa_bancaria = $conteudo['id_remessa_bancaria'];
        $nome_arquivo = $conteudo['nome_arquivo'];
        $data_gerado = convertedata($conteudo['data_gerado']);

        $dados_pessoa = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario']."' ");
        $nome_usuario = $dados_pessoa[0]['nome'];
        
        $codigo_remessa = $conteudo['dados'];
        
        $tipo_remessa = ucfirst($conteudo['tipo']);
        if($tipo_remessa == 'Alteracao_vencimento'){
            $tipo_remessa = 'Alteração de Vencimento';
        }
        echo 
        	"<tr>
        		<td>".$id_remessa_bancaria."</td> 
        		<td>".$nome_arquivo."</td>
        		<td>".$data_gerado."</td>
                <td>".$nome_usuario."</td>
                <td>".$tipo_remessa."</td>
        		<td class='text-center'><a  href=\"class/Boleto.php?visualizar_remessa=$id_remessa_bancaria\" title='Download'><i class='fa fa-download'></i></a></td>
        	</tr>"
        ;
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