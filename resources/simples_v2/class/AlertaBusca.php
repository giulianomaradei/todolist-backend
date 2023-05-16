<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_contrato_plano_pessoa = '';
$data = '';
$vencido = '';
$exibicao = '';
$tipo = '';


if($parametros['data_inicio'] && $parametros['data_fim']){
    $data = "AND a.data_criacao >= '".converteData($parametros['data_inicio'])." 00:00:00' AND a.data_criacao <= '".converteData($parametros['data_fim'])." 23:59:59'";
}else if($parametros['data_inicio']){
    $data = "AND a.data_criacao >= '".converteData($parametros['data_inicio'])." 00:00:00'";
}else if($parametros['data_fim']){
    $data = "AND a.data_criacao <= '".converteData($parametros['data_fim'])." 23:59:59'";
}
if($parametros['vencido'] == 'sim'){
    $vencido = "AND a.data_vencimento < '".getDataHora()."'";
}else if($parametros['vencido'] == 'nao'){
    $vencido = "AND (a.data_vencimento >= '".getDataHora()."' OR a.data_vencimento IS NULL)";
}
if($parametros['exibicao']){
    $exibicao = "AND a.exibicao = '".$parametros['exibicao']."'";
}

if($parametros['tipo']){
    if($parametros['tipo'] == 'contrato'){
        $tipo = "AND a.id_contrato_plano_pessoa IS NOT NULL";
        if($parametros['id_contrato_plano_pessoa']){
            $id_contrato_plano_pessoa = "AND b.id_contrato_plano_pessoa = '".$parametros['id_contrato_plano_pessoa']."'";
        }
    }else{
        $tipo = "AND a.id_contrato_plano_pessoa IS NULL";
    } 
}else{
    if($parametros['id_contrato_plano_pessoa']){
        $id_contrato_plano_pessoa = "AND b.id_contrato_plano_pessoa = '".$parametros['id_contrato_plano_pessoa']."'";
    }
}
// Informações da query
$filtros_query  = "LEFT JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_categoria c ON c.id_categoria = a.id_categoria WHERE 1 $id_contrato_plano_pessoa $data $vencido $exibicao $tipo ORDER BY a.data_criacao DESC";

// Maximo de registros por pagina
$maximo = 5;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}   

// Conta os resultados no total da query  
$dados = DBRead('','tb_alerta a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_alerta a',$filtros_query." LIMIT $inicio,$maximo", "a.*, b.id_contrato_plano_pessoa, c.nome AS 'nome_categoria'");
if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
    echo "Não foram encontrados registros!";
    echo "</p>";
} else { 

    foreach($dados as $conteudoAlerta){

        if($conteudoAlerta['id_contrato_plano_pessoa']){
            $empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE id_contrato_plano_pessoa = ".$conteudoAlerta['id_contrato_plano_pessoa'], "a.*, a.nome_contrato, b.nome, c.nome AS plano, c.cod_servico");

            $texto_header = $empresa[0]['nome'];

            if($empresa[0]['nome_contrato']){
                $texto_header .= " (".$empresa[0]['nome_contrato'].") ";
            }
            $texto_header .= " - " . getNomeServico($empresa[0]['cod_servico']) . " - " . $empresa[0]['plano'] . " (" . $empresa[0]['id_contrato_plano_pessoa'] . ")";
        }else{
            $texto_header = 'Alerta Geral';
        }

        

        $dados_usuario_criou = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = ".$conteudoAlerta['id_usuario_criou'], "a.*, b.nome");
        $dados_usuario_ultima_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = ".$conteudoAlerta['id_usuario_ultima_acao'], "a.*, b.nome");

        $id = $conteudoAlerta['id_alerta'];

        if($conteudoAlerta['data_vencimento'] && ($conteudoAlerta['data_vencimento'] <= getDataHora())){
            $classExibicao= 'panel panel-default';
        }else if($conteudoAlerta['data_inicio'] && ($conteudoAlerta['data_inicio'] >= getDataHora())){
            $classExibicao = 'panel panel-success';
        }else{
            if($conteudoAlerta['exibicao'] == 1){
                $classExibicao = 'panel panel-warning';            
            }elseif($conteudoAlerta['exibicao'] == 2){
                $classExibicao = 'panel panel-danger';
            }else{
                $classExibicao = 'panel panel-info';
            }
        }

        if($conteudoAlerta['exibicao'] == 1){
            $exibicao = 'Atendimento - Todo';
        }else if($conteudoAlerta['exibicao'] == 2){
            $exibicao = 'Atendimento - Somente na finalização';
        }else if($conteudoAlerta['exibicao'] == 3){
            $exibicao = 'Atendimento - Somente no início';
        }else{
            $exibicao = 'Monitoramento - Todo';
        }

        echo "<div class='$classExibicao'>";
            echo "<div class='panel-heading'>";
                echo "<div class='row'>";
                    echo "<div class='col-md-4'><strong>";
                        echo $texto_header;
                    echo "</strong></div>";
                    echo "<div class='col-md-3'>";
                        echo "<strong>Criado por:</strong> ".$dados_usuario_criou[0]['nome']." (".converteDataHora($conteudoAlerta['data_criacao']).")";
                    echo "</div>";
                    echo "<div class='col-md-4'>";
                        echo "<strong>Última ação:</strong> ".$dados_usuario_ultima_acao[0]['nome']." (".$conteudoAlerta['ultima_acao']." - ".converteDataHora($conteudoAlerta['data_ultima_acao']).")";
                    echo "</div>";
                    echo "<div class='col-md-1 text-right'>";
                        if(!$conteudoAlerta['data_vencimento'] || getDataHora() < $conteudoAlerta['data_vencimento']){
                            echo "<a style='color: green;' href=\"/api/iframe?token=".$request->token."&Alerta.php?vencido=$id\" title='Vencido' onclick=\"if (!confirm('Vencido?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-check' aria-hidden='true'></i></a>";
                        }
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "<a href=\"/api/iframe?token=".$request->token."&view=alerta-form&alterar=$id\" title='Alterar'><i class='fa fa-pencil'></i></a>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "<a href=\"/api/ajax/class=Alerta.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a>";

                    echo "</div>";
                echo "</div>";
            echo "</div>";
            echo "<div class='panel-body'>";
                echo nl2br($conteudoAlerta['conteudo']);
            echo "</div>";
            echo "<div class='panel-footer'>";
                echo "<div class='row'>";
                    echo "<div class='col-md-3'>";
                        echo "<strong>Início:</strong> ".converteDataHora($conteudoAlerta['data_inicio']);                    
                    echo "</div>";
                    echo "<div class='col-md-3'>";
                        echo "<strong>Vencimento:</strong> ".converteDataHora($conteudoAlerta['data_vencimento']);
                    echo "</div>";
                    echo "<div class='col-md-3'>";
                        echo "<strong>Exibição:</strong> ".$exibicao;
                    echo "</div>";
                    echo "<div class='col-md-3'>";
                        echo "<strong>Categoria:</strong> ".$conteudoAlerta['nome_categoria'];
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
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