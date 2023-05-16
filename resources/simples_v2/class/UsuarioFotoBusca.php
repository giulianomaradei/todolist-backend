<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.nome LIKE '%".$letra."%' AND a.status = 1 AND b.nome != 'Belluno' ORDER BY nome ASC";

// Maximo de registros por pagina
$maximo = 12;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}   

// Conta os resultados no total da query  
$dados = DBRead('','tb_usuario a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_usuario a',$filtros_query." LIMIT $inicio,$maximo");
if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if (!$letra) {
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
} else {
    echo "<div class='row'>";
    
    foreach ($dados as $conteudo) {
        $arquivo = '../inc/upload-usuario/'.$conteudo['id_usuario'].'.jpg';

        if(file_exists($arquivo)){
            $imagem = 'inc/upload-usuario/'.$conteudo['id_usuario'].'.jpg';
        }else{
            $imagem = "inc/img/avatar.png";
        }
        echo '
        <div class="col-md-3 text-center">
            <div class="form-group has-feedback">
                <div class="row text-center">
                    <img id="imagem" src="'.$imagem.'" alt="Imagem responsiva" class="img-thumbnail" style = "width:146 !important; height: 110px !important; border: none; padding:0px;">
                </div>
                <label>'.$conteudo['nome'].'</label>

            </div>
        </div> 
        ';
    }

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
        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></a></li>";
    }

    echo "</ul>";
    echo "</nav>";
}
?>