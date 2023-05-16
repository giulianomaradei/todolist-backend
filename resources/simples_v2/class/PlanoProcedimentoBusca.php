<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query
$filtros_query  = "WHERE nome LIKE '%$letra%' ORDER BY id_plano_procedimento DESC";

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
$dados = DBRead('','tb_plano_procedimento',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_plano_procedimento',$filtros_query." LIMIT $inicio,$maximo");
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
    echo "<th class=\"col-md-2\">Nome</th>";
    echo "<th class=\"col-md-2\">Serviço</th>";
    echo "<th class=\"col-md-3\">Descrição</th>";
    echo "<th class=\"col-md-3\">Pré-Requisito</th>";
    echo "<th class=\"col-md-1\">Status</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($dados as $conteudo){
        $id = $conteudo['id_plano_procedimento'];
        $servico = getNomeServico($conteudo['cod_servico']);
        $nome = $conteudo['nome'];
        $descricao = $conteudo['descricao'];
        $pre_requisito = $conteudo['pre_requisito'];
        if($conteudo['status'] == '0'){
            $status = 'Inativo';
            echo "<tr class='warning'>";
        }else{
            $status = 'Ativo';
            echo "<tr>";
        }
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'><span data-toggle=\"tooltip\" title=\"".limitarTexto($nome, 100)."\">".limitarTexto($nome, 30)."</span></td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$servico</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'><span data-toggle=\"tooltip\" title=\"".limitarTexto($descricao, 100)."\">".limitarTexto($descricao, 30)."</span></td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'><span data-toggle=\"tooltip\" title=\"".limitarTexto($pre_requisito, 100)."\">".limitarTexto($pre_requisito, 30)."</span></td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$status</td>";

        $dados_plano_procedimento_plano = DBRead('', 'tb_plano_procedimento_plano',"WHERE id_plano_procedimento = '".$id."' LIMIT 1");
        $dados_plano_procedimento_historico = DBRead('', 'tb_plano_procedimento_historico',"WHERE id_plano_procedimento = '".$id."' LIMIT 1");

        if($dados_plano_procedimento_plano || $dados_plano_procedimento_historico){
            echo "<td class=\"text-center\" style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        }else{
            echo "<td class=\"text-center\" style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=plano-procedimento-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/PlanoProcedimento.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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
<script>
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>