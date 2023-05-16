<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$liderados = busca_liderados($_SESSION['id_usuario']);
$dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '".$_SESSION['id_usuario']."'");
if($dados_usuario[0]['id_perfil_sistema'] != '20' && $dados_usuario[0]['id_perfil_sistema'] != '12'){
    $filtro_permissao = " AND a.id_usuario_ocorrencia IN ('".join("','", $liderados)."')";
}else{
    $filtro_permissao = '';
}

// Informações da query
$filtros_query  = "INNER JOIN tb_usuario b ON a.id_usuario_ocorrencia = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_ocorrencia_tipo d ON a.id_ocorrencia_tipo = d.id_ocorrencia_tipo WHERE a.status = 1 $filtro_permissao AND c.nome LIKE '%$letra%' ORDER BY a.data ASC";

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
$dados = DBRead('','tb_ocorrencia a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_ocorrencia a',$filtros_query." LIMIT $inicio,$maximo", 'a.*, c.nome, d.descricao');
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
    echo "<th class=\"col-md-2\">Data</th>";
    echo "<th class=\"col-md-4\">Funcionário</th>";
    echo "<th class=\"col-md-3\">Tipo</th>";
    echo "<th class=\"col-md-2\">Classificação</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_ocorrencia'];
        $nome_usuario_ocorrencia = $conteudo['nome'];
        $tipo = $conteudo['descricao'];
        $data = converteData($conteudo['data']);
        if($conteudo['classificacao'] == '1'){
            $classificacao = 'Positivo';
        }elseif($conteudo['classificacao'] == '2'){
            $classificacao = 'Neutro';
        }else{
            $classificacao = 'Negativo';
        }
        echo "<tr>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=ocorrencia-form&alterar=$id'\" style='cursor: pointer'>$data</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=ocorrencia-form&alterar=$id'\" style='cursor: pointer'>$nome_usuario_ocorrencia</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=ocorrencia-form&alterar=$id'\" style='cursor: pointer'>$tipo</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=ocorrencia-form&alterar=$id'\" style='cursor: pointer'>$classificacao</td>";
        echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=ocorrencia-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/Ocorrencia.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome_usuario_ocorrencia."(".$data).")"."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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