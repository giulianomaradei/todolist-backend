<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$status = ($parametros['status']) ? $parametros['status'] : '0';
$id_responsavel = ($parametros['id_responsavel']) ? $parametros['id_responsavel'] : '0';
$id_tecnico = ($parametros['id_tecnico']) ? $parametros['id_tecnico'] : '0';
$cod_servico = ($parametros['cod_servico']) ? $parametros['cod_servico'] : 0;
$filtro = '';

if($status != 'z'){
    $filtro .= " AND a.status ='$status'";
}

if($id_responsavel != 'z'){
    $filtro .= " AND a.id_responsavel ='$id_responsavel'";
} 

if($id_tecnico != 'z'){
    $filtro .= " AND a.id_responsavel_tecnico ='$id_tecnico'";
}

if($cod_servico){
    $filtro .= " AND c.cod_servico = '$cod_servico'";
}

// Informações da query

    $filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%' OR c.nome LIKE '%$letra%' OR c.cod_servico LIKE '%$letra%' OR a.nome_contrato LIKE '%$letra%') $filtro ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC";

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
$dados = DBRead('','tb_contrato_plano_pessoa a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_contrato_plano_pessoa a',$filtros_query." LIMIT $inicio,$maximo","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico, c.cor");
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
    echo "<th class=\"col-md-2\">Cliente</th>";
    echo "<th class=\"col-md-2\">Plano</th>";
    echo "<th class=\"col-md-1\">Início</th>";    
    echo "<th class=\"col-md-1\">Atualização</th>";
    echo "<th class=\"col-md-2\">Status</th>";
    echo "<th class=\"col-md-1\">Responsável pelo Relacionamento</th>";
    echo "<th class=\"col-md-1\">Responsável Técnico</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id_contrato_plano_pessoa'];
        $nome_pessoa = $conteudo['nome_pessoa'];
        $razao_social = $conteudo['razao_social'];
        $nome_plano = $conteudo['nome_plano'];
        $cod_servico = $conteudo['cod_servico'];
        $servico = getNomeServico($cod_servico);
        $data_inicio_contrato = converteData($conteudo['data_inicio_contrato']);
        $data_atualizacao = converteDataHora($conteudo['data_atualizacao']);
        $status = getNomeStatusPlano($conteudo['status']).' desde: '.converteData($conteudo['data_status']);
        $nome_contrato = $conteudo['nome_contrato'];
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ","b.nome");
        $nome_responsavel = $dados_responsavel[0]['nome'];

        $dados_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel_tecnico']."' ","b.nome");
        $nome_tecnico = $dados_tecnico[0]['nome'];
        
        echo "<tr>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$id</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'><strong>$nome_pessoa";

        if($nome_contrato){
            echo " (".$nome_contrato.") ";
        }

        echo "</strong><br>$razao_social</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$servico - $nome_plano</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$data_inicio_contrato</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$data_atualizacao</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$status</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$nome_responsavel</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'>$nome_tecnico</td>";
        echo "<td class=\"text-center\" onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id'\" style='cursor: pointer; vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a></td>";
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