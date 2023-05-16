<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$id_usuario_cadastrou = addslashes($parametros['id_usuario_cadastrou']);
$id_fornecedor = addslashes($parametros['id_fornecedor']);

if($id_usuario_cadastrou){
    $filtro_id_usuario_cadastrou = "AND a.id_usuario = '".$id_usuario_cadastrou."' ";
}

if($id_fornecedor){
    $filtro_id_fornecedor = "AND b.id_fornecedor = '".$id_fornecedor."' ";
}

// Informações da query
$filtros_query  = "INNER JOIN tb_estoque_movimentacao_item b ON a.id_estoque_movimentacao = b.id_estoque_movimentacao INNER JOIN tb_estoque_item c ON b.id_estoque_item = c.id_estoque_item INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE (c.nome LIKE '%$letra%' OR c.informacao_adicional LIKE '%$letra%') AND b.tipo_movimentacao = 'entrada' AND b.status = '1' ".$filtro_id_usuario_cadastrou." ".$filtro_id_fornecedor." ";

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
$dados = DBRead('','tb_estoque_movimentacao a',$filtros_query." ORDER BY b.id_estoque_movimentacao_item DESC LIMIT $inicio,$maximo", "a.*, b.*, c.*, e.nome AS nome_usuario, b.quantidade AS quantidade_movimentacao, b.valor_unitario AS valor_unitario_movimentacao");
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
        echo "<th>Fornecedor</th>";
        echo "<th>Tipo de Entrada</th>";
        echo "<th>Quantidade</th>";
        echo "<th>Valor Unitário</th>";
        echo "<th>Realizada Por</th>";
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
        $valor_unitario_movimentacao = converteMoeda($conteudo['valor_unitario_movimentacao'], 'moeda');
        $nome_usuario = $conteudo['nome_usuario'];
        $data = converteDataHora($conteudo['data']);
        $observacao = $conteudo['observacao'];

        $id_fornecedor = $conteudo['id_fornecedor'];
        if($id_fornecedor){
            $dados_fornecedor = DBRead('','tb_pessoa', "WHERE id_pessoa = '".$id_fornecedor."' ", 'nome');
            $nome_fornecedor = $dados_fornecedor[0]['nome'];
        }else{
            $nome_fornecedor = '';
        }

        if($conteudo['tipo'] == 1){
           $tipo_entrada = 'Compra';
        }else{
            $tipo_entrada = 'Interna';
        }

        echo "<tr>";
            echo "<td style='vertical-align: middle;'>$id</td>";
            echo "<td style='vertical-align: middle;'>$nome</td>";
            echo "<td style='vertical-align: middle;'>$informacao_adicional</td>";
            echo "<td style='vertical-align: middle;'>$observacao</td>";
            echo "<td style='vertical-align: middle;'>$nome_fornecedor</td>";
            echo "<td style='vertical-align: middle;'>$tipo_entrada</td>";
            echo "<td style='vertical-align: middle;'>$quantidade_movimentacao</td>";
            echo "<td style='vertical-align: middle;'>R$ $valor_unitario_movimentacao</td>";
            echo "<td style='vertical-align: middle;'>$nome_usuario</td>";
            echo "<td style='vertical-align: middle;'>$data</td>";
            echo "<td style='vertical-align: middle;' class=\"text-center\"><a href=\"class/EstoqueMovimentacaoEntrada.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir Movimentação?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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