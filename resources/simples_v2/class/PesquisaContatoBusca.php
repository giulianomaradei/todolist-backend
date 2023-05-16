<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['contato']);
$id_pesquisa = $parametros['id_pesquisa'];

// Informações da query
$filtros_query  = "WHERE nome LIKE '%$letra%' AND id_pesquisa = '$id_pesquisa'";
// tem que colocar ORDER BY nome ASC

//INNER JOIN tb_contatos_pesquisa b ON a.id_pesquisa = b.id_pesquisa
//WHERE a.id_pesquisa = 1 AND b.nome LIKE '%$letra%' ORDER BY b.nome ASC" ;

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
$dados = DBRead('','tb_contatos_pesquisa',$filtros_query,"COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO

$dados_pesquisa = DBRead('', 'tb_pesquisa',"WHERE id_pesquisa = '$id_pesquisa'");

$dados = DBRead('', 'tb_contatos_pesquisa',$filtros_query." LIMIT $inicio,$maximo");

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
    echo "<th class=\"col-md-5\">Nome</th>";
    echo "<th class=\"col-md-2\">Telefone</th>";
    
    if($dados_pesquisa[0]['dado1']){
         echo "<th class=\"col-md-2\">".$dados_pesquisa[0]['dado1']."</th>";
    }
    if($dados_pesquisa[0]['dado2']){
        echo "<th class=\"col-md-2\">".$dados_pesquisa[0]['dado2']."</th>";
    }
    if($dados_pesquisa[0]['dado3']){
        echo "<th class=\"col-md-2\">".$dados_pesquisa[0]['dado3']."</th>";
    }
    echo "<th class=\"col-md-2\">Situação</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_contatos_pesquisa'];
        $nome = $conteudo['nome'];
        $fone = $conteudo['telefone'];
        $status = $conteudo['status_pesquisa'];
        $tentativas = $conteudo['qtd_tentativas_cliente'];

        if($status != 0){
            $situacao = "Entrevistado";
            $opcoes = "<td></td>";
        }else if($status == 0 && $tentativas != 0){
            $situacao = "Tentativa(s) realizada(s)";
            $opcoes = "<td></td>";
        }else{
            $situacao = "Em aberto";
            $opcoes = "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-contato-form&alterar=$id&id_pesquisa=$id_pesquisa' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/PesquisaContato.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
        }
        echo "<tr>";    
        echo "<td>$nome</td>";
        echo "<td class='phone'>$fone</td>";
        if($dados_pesquisa[0]['dado1']){
             echo "<td>".$conteudo['dado1']."</td>";
        }
        if($dados_pesquisa[0]['dado2']){
            echo "<td>".$conteudo['dado2']."</td>";        
        }
        if($dados_pesquisa[0]['dado3']){
            echo "<td>".$conteudo['dado3']."</td>";        
        }        echo "<td>$situacao</td>";
        echo "$opcoes";
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

