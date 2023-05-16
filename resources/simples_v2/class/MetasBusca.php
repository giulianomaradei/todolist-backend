<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

if($parametros['tipo']){
    $filtro_tipo = " AND tipo = '".$parametros['tipo']."'";
}

if($parametros['data_de'] || $parametros['data_ate']){
    
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);
    
    if($parametros['data_de'] && $parametros['data_ate']){

        $filtro_data = " AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_ate."' AND data_ate <= '".$data_ate."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_ate."'))";

    }else if($parametros['data_de']){

        $filtro_data = " AND (data_de <= '".$data_de."' AND data_ate >= '".$data_de."')";

    }else if($parametros['data_ate']){

        $filtro_data = " AND (data_de <= '".$data_ate."' AND data_ate <= '".$data_ate."')";

    }
}

// Informações da query
$filtros_query  = "WHERE status != 2 AND nome LIKE '%$letra%' $filtro_tipo $filtro_data ORDER BY data_de DESC";

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
$dados = DBRead('','tb_meta', $filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_meta', $filtros_query . " LIMIT $inicio,$maximo");
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
    echo "<th class=\"col-md-3\">Nome</th>";
    echo "<th class=\"col-md-3\">Tipo</th>";
    echo "<th class=\"col-md-2\">Data inicial</th>";
    echo "<th class=\"col-md-2\">Data final</th>";
    echo "<th class=\"col-md-2 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $conteudo){
        $id = $conteudo['id_meta'];
        $nome = $conteudo['nome'];
        $tipo = $conteudo['tipo'];
        $tipo_nome = array(
            '1' => 'Individual',
            '2' => 'Equipe',
            '3' => 'Geral'
        );
        $data_de = converteData($conteudo['data_de']);
        $data_ate = converteData($conteudo['data_ate']);
        
        if($conteudo['status'] == '0'){
            $classe_status = "class='warning'";
        }else{
            $classe_status = "";
        }

        $nome_lider = '';
        if($tipo == '2'){
            $dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo['lider_direto']."'");
            if($dados_lider){
                $nome_lider = " (".$dados_lider[0]['nome'].")";
            }
        }

        echo "<tr $classe_status>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=metas-form&alterar=$id'\" style='cursor: pointer'>$nome</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=metas-form&alterar=$id'\" style='cursor: pointer'>".$tipo_nome[$tipo]."$nome_lider</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=metas-form&alterar=$id'\" style='cursor: pointer'>$data_de</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=metas-form&alterar=$id'\" style='cursor: pointer'>$data_ate</td>";

        echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=metas-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=metas-form&clonar=$id'\" title='Clonar'><i class='fa fa-clone' style='color:#FF8C00;'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href=\"class/Metas.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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