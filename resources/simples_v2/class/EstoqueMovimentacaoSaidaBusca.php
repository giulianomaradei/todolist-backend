<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$id_setor = addslashes($parametros['id_setor']);
$id_usuario_cadastrou = addslashes($parametros['id_usuario_cadastrou']);
$id_solicitante = addslashes($parametros['id_solicitante']);
$id_localizacao = addslashes($parametros['id_localizacao']);

if($id_setor){
    $filtro_id_setor = "AND b.id_setor = '".$id_setor."' ";
}

if($id_usuario_cadastrou){
    $filtro_id_usuario_cadastrou = "AND a.id_usuario = '".$id_usuario_cadastrou."' ";
}

if($id_solicitante){
    $filtro_id_solicitante = "AND b.id_solicitante = '".$id_solicitante."' ";
}

if($id_localizacao){
    $filtro_id_localizacao = "AND b.id_estoque_localizacao = '".$id_localizacao."' ";
}


// Informações da query
$filtros_query  = "INNER JOIN tb_estoque_movimentacao_item b ON a.id_estoque_movimentacao = b.id_estoque_movimentacao INNER JOIN tb_estoque_item c ON b.id_estoque_item = c.id_estoque_item INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE (c.nome LIKE '%$letra%' OR c.informacao_adicional LIKE '%$letra%') AND b.tipo_movimentacao = 'saida' AND b.status = '1' ".$filtro_id_setor." ".$filtro_id_usuario_cadastrou." ".$filtro_id_solicitante." ".$filtro_id_localizacao." ";

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
$dados = DBRead('','tb_estoque_movimentacao a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('','tb_estoque_movimentacao a',$filtros_query." ORDER BY b.id_estoque_movimentacao_item DESC LIMIT $inicio,$maximo", "a.*, b.*, c.*, e.nome AS nome_usuario, b.quantidade AS quantidade_movimentacao");
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
    echo "<tr style='vertical-align: middle;'>";
        echo "<th>#</th>";
        echo "<th>Item</th>";
        echo "<th>Informação Adicional</th>";
        echo "<th>Observação</th>";
        echo "<th>Setor Responsável</th>";
        echo "<th>Quantidade</th>";
        echo "<th>Solicitante</th>";
        echo "<th>Realizada Por</th>";
        echo "<th>Localização</th>";
        echo "<th>Data</th>";
        echo "<th class=\"text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_estoque_movimentacao_item'];
        $nome = $conteudo['nome'];
        $informacao_adicional = $conteudo['informacao_adicional'];
        $quantidade_movimentacao = $conteudo['quantidade_movimentacao'];
        $nome_usuario = $conteudo['nome_usuario'];
        $data = converteDataHora($conteudo['data']);
        $observacao = $conteudo['observacao'];

        $dados_solicitante = DBRead('','tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_solicitante']."' ", "b.nome");
        $nome_solicitante = $dados_solicitante[0]['nome'];

        $localizacao = $conteudo['id_estoque_localizacao'];

        if($conteudo['id_estoque_localizacao']){
            $dados_localizacao = DBRead('','tb_estoque_localizacao', "WHERE id_estoque_localizacao = '".$conteudo['id_estoque_localizacao']."' ", "nome");
            $localizacao = $dados_localizacao[0]['nome'];
        }else{
            $localizacao = '';
        }

        if($conteudo['id_setor']){
            $dados_setor = DBRead('','tb_setor', "WHERE id_setor = '".$conteudo['id_setor']."' ", "descricao");
            $nome_setor = $dados_setor[0]['descricao'];
        }else{
            $nome_setor = '';
        }

        echo "<tr>";
            echo "<td style='vertical-align: middle;'>$id</td>";
            echo "<td style='vertical-align: middle;'>$nome</td>";
            echo "<td style='vertical-align: middle;'>$informacao_adicional</td>";
            echo "<td style='vertical-align: middle;'>$observacao</td>";
            echo "<td style='vertical-align: middle;'>$nome_setor</td>";
            echo "<td style='vertical-align: middle;'>$quantidade_movimentacao</td>";
            echo "<td style='vertical-align: middle;'>$nome_solicitante</td>";
            echo "<td style='vertical-align: middle;'>$nome_usuario</td>";
            echo "<td style='vertical-align: middle;'>$localizacao</td>";
            echo "<td style='vertical-align: middle;'>$data</td>";
            echo "<td style='vertical-align: middle;' class=\"text-center\"><a href=\"class/EstoqueMovimentacaoSaida.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir Movimentação?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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