<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);
$categoria = "";
$filtroVisualizado = '';

if($parametros['id_categoria']){
    $categoria = "AND a.id_categoria = '".$parametros['id_categoria']."'";
}

if($parametros['id_usuario']){
    $id_usuario = "AND a.id_usuario = '".$parametros['id_usuario']."'";
}

if($parametros['visualizado'] == '1'){
    $filtroCurtidas = "AND a.id_topico IN (SELECT id_topico FROM tb_likes WHERE id_usuario = '".$_SESSION['id_usuario']."')";
}else if($parametros['visualizado'] == '2'){
    $filtroCurtidas = "AND a.id_topico NOT IN (SELECT id_topico FROM tb_likes WHERE id_usuario = '".$_SESSION['id_usuario']."')";
}else{
    $filtroCurtidas = '';
}

$dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '".$_SESSION['id_usuario']."'");
$perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];

// Informações da query
$filtros_query  = "INNER JOIN tb_perfil_topico b ON a.id_topico = b.id_topico WHERE (b.id_perfil_sistema = '$perfil_usuario' OR a.id_usuario = '".$_SESSION['id_usuario']."') AND a.titulo LIKE '%$letra%' $id_usuario $categoria AND a.status != 2 AND a.id_pai = 0 ".$filtroCurtidas." GROUP BY b.id_topico ORDER BY a.data_criacao DESC";

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
$dados = DBRead('', 'tb_topico a', $filtros_query,'a.*, b.id_topico');
if($dados){
    $total = sizeof($dados);
}else{
    $total = 0;
}

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO
// 
$dados = DBRead('', 'tb_topico a', $filtros_query." LIMIT $inicio,$maximo",'a.*, b.id_topico');
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
        echo "<th class=\"col-md-3\">Título</th>";
        echo "<th class=\"col-md-3\">Categoria</th>";
        echo "<th class=\"col-md-3\">Autor</th>";
        echo "<th class=\"col-md-2\">Data</th>";
        echo "<th class=\"col-md-1 text-center\">Visualizar</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($dados as $conteudoTopico) {

        $dados_visualizado = DBRead('', 'tb_topico_visualizado', "WHERE id_topico = '".$conteudoTopico['id_topico']."' AND id_usuario = '".$_SESSION['id_usuario']."'");

        if(!$dados_visualizado){
            $conteudoTopicoVizualizado = 'n';
        }else{
            $conteudoTopicoVizualizado = 's';
        }
        if($conteudoTopicoVizualizado == $filtroVisualizado || $filtroVisualizado == ''){
            
            $dados_topico = DBRead('', 'tb_topico', "WHERE id_topico = '".$conteudoTopico['id_topico']."'");

            $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados_topico[0]['id_usuario']."'");

            $visualizado = DBRead('', 'tb_topico_visualizado', "WHERE id_topico = '".$dados_topico[0]['id_topico']."' AND id_usuario = '".$_SESSION['id_usuario']."'");

            $curtida = DBRead('', 'tb_likes', "WHERE id_topico = '".$dados_topico[0]['id_topico']."' AND id_usuario = '".$_SESSION['id_usuario']."'");

            $classe_visualizado = "";
            if(!$visualizado){
                $classe_visualizado = "warning";
            }
            if($curtida){
                $classe_visualizado = "success";
            }

            $dados_categoria = DBRead('', 'tb_categoria', "WHERE id_categoria = '".$dados_topico[0]['id_categoria']."'");

            $id = $dados_topico[0]['id_topico'];
            $titulo = $dados_topico[0]['titulo'];
            $conteudo = converteDataHora($dados_topico[0]['data_criacao']);
            $autor = $dados_usuario[0]['nome'];
            echo "<tr class='$classe_visualizado' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=topico-exibe&id=$id'\" style='cursor: pointer'>";
            echo "<td>$titulo</td>";
            echo "<td>".$dados_categoria[0]['nome']."</td>";
            echo "<td>$autor</td>";
            echo "<td>$conteudo</td>";
            echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=topico-exibe&id=$id' title='Visualizar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
            echo "</tr>";

        }
        
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