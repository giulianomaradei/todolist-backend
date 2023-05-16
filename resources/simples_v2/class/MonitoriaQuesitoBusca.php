<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

if($parametros['passo']){
    $filtro_passo = 'AND passo_atendimento = "'.$parametros['passo'].'" ';
}

// Informações da query
$filtros_query  = "WHERE (descricao LIKE '%$letra%' OR plano_acao LIKE '%$letra%') $filtro_passo AND status != 2 ORDER BY passo_atendimento, descricao ASC";

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
$dados = DBRead('','tb_monitoria_quesito',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_monitoria_quesito',$filtros_query." LIMIT $inicio,$maximo");
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
    echo "<th>Passo do atendimento</th>";
    echo "<th>Descrição</th>";
    echo "<th>Plano de ação</th>";
    echo "<th>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($dados as $conteudo) {
        
        $id = $conteudo['id_monitoria_quesito'];
        $descricao = $conteudo['descricao'];
        $plano_acao = $conteudo['plano_acao'];
        $passo_atendimento = $conteudo['passo_atendimento'];
        $status = $conteudo['status'];

        $class_tr = '';
        if($status == 1){
            $legenda_status = 'Ativo';
        }else if($status == 0){
            $legenda_status = 'Inativo';
            $class_tr = 'warning';
        }

        echo "<tr class='$class_tr'>";
        echo "<td class='text-center' onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-quesito-form&alterar=$id'\" style='cursor: pointer'>$passo_atendimento</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-quesito-form&alterar=$id'\" style='cursor: pointer'>$descricao</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-quesito-form&alterar=$id'\" style='cursor: pointer'>$plano_acao</td>";
        
        if($status == 1){
             echo "<td><a href='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-quesito-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/MonitoriaQuesito.php?inativar=$id\" title='Desativar quesito' onclick=\"if (!confirm('Excluir ".addslashes($descricao)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-download' style='color:#FFA500;'></i></a></td>";
        }else{
            echo "<td><a href='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-quesito-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/MonitoriaQuesito.php?ativar=$id\" title='Ativar quesito' onclick=\"if (!confirm('Reativar este quesito?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-upload' style='color: #228B22;'></i></a></td>";
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