<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$id_pessoa_pai = $parametros['id_pai'];

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa WHERE a.id_pessoa_pai = '$id_pessoa_pai' AND b.status != '2' AND b.nome LIKE '%$letra%' ORDER BY b.nome ASC";

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
$dados = DBRead('','tb_vinculo_pessoa a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_vinculo_pessoa a',$filtros_query." LIMIT $inicio,$maximo");
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
    echo "<th class=\"col-md-1\">#</th>";
    echo "<th class=\"col-md-3\">Nome</th>";
    echo "<th class=\"col-md-2\">Tipo(s)</th>";
    echo "<th class=\"col-md-2\">Fone(1)</th>";
    echo "<th class=\"col-md-2\">E-Mail(1)</th>";
    echo "<th class=\"col-md-1\">Atualizado em</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $conteudo){
        $id = $conteudo['id_vinculo_pessoa'];
        $id_pessoa_filho = $conteudo['id_pessoa'];
        $nome = $conteudo['nome'];
        $fone1 = formataCampo('fone',$conteudo['fone1']);
        $email1 = $conteudo['email1'];
        $id_pessoa_filho = $conteudo['id_pessoa_filho'];
        $data_atualizacao = converteDataHora($conteudo['data_atualizacao']);
        $dados_vinculo = DBRead('','tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_tipo b ON a.id_vinculo_tipo = b.id_vinculo_tipo WHERE a.id_vinculo_pessoa = '$id' ORDER BY b.nome");
        $tipo = '';
        if($dados_vinculo){
            foreach($dados_vinculo as $conteudo){
                $tipo .= $conteudo['nome']." | ";
            }
            $tipo = substr($tipo, 0, -3);
        }
        $usuario = $_SESSION['id_usuario'];
        echo "<tr>";    
        echo "<td>$id_pessoa_filho</td>";
        echo "<td>$nome</td>";
        echo "<td>$tipo</td>";
        echo "<td>$fone1</td>";
        echo "<td>$email1</td>";
        echo "<td>$data_atualizacao</td>";
        echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=$id_pessoa_filho' title='Visualizar cadastro' onclick=\"if (!confirm('Se houve alterações não salvas no formulário de pessoa, serão perdidas ao recarregar a página.')) { return false; } else { modalAguarde(); }\"><i class='fa fa-share-square-o'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/api/iframe?token=<?php echo $request->token ?>&view=vinculo-pessoa-form&alterar=$id' title='Alterar vínculo' onclick=\"if (!confirm('Se houve alterações não salvas no formulário de pessoa, serão perdidas ao recarregar a página.')) { return false; } else { modalAguarde(); }\"><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/VinculoPessoa.php?excluir=$id&pessoa=$id_pessoa_filho\" title='Excluir vínculo' onclick=\"if (!confirm('Excluir vínculo com ".addslashes($nome)."? (Se houve alterações não salvas no formulário de pessoa, serão perdidas ao recarregar a página.)')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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