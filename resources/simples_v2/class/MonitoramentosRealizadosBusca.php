<style>
.list-atendimento{
    font-size: 14px;
}
</style>
<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

$valor = addslashes($parametros['visualizar']);
$filtro = addslashes($parametros['filtro']);
$empresa = addslashes($parametros['empresa']);
$data = addslashes($parametros['data']);
$id_contrato_plano_pessoa = $parametros['id_contrato_plano_pessoa'];

// Informações da query

if($empresa == 0){
    $filtros_empresa = 'AND a.id_contrato_plano_pessoa = '.$id_contrato_plano_pessoa.'';
}else{
    $filtros_empresa  = "";
}

if($data == 0){
    $filtros_data = '';
}else{
    $filtros_data  = "AND a.data_registro LIKE '".converteDataHora($data)."%'";
}

// Informações da query
$filtros_query  = "WHERE (a.data_registro LIKE '%$filtro%' OR c.nome LIKE '%$filtro%' OR a.nome_tecnico LIKE '%$filtro%' OR a.telefone LIKE '%$filtro%') ".$filtros_empresa." ".$filtros_data." ORDER BY a.data_registro DESC";

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

$dados = DBRead('', 'tb_monitoramento_queda a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario 
INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa $filtros_query", "COUNT(*) AS 'num_registros'");

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

$dados = DBRead('', 'tb_monitoramento_queda a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario 
INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa $filtros_query LIMIT $inicio,$maximo", "a.id_monitoramento_queda, a.data_queda, a.data_registro, a.nome_tecnico, a.telefone, a.status_contato, a.informacao, a.id_contrato_plano_pessoa, c.nome");


if(!$dados): ?>

    <p class='alert alert-warning' style='text-align: center'>
<?php
    if(!$letra){
        echo "Não foram encontrados registros!";
    }else{
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";

else: ?>

    <div class='panel-group'>

    <?php

    foreach ($dados as $conteudo) : 

        $dados_empresa = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$conteudo['id_contrato_plano_pessoa']."'","b.nome");

        $dados_protocolo = DBRead('','tb_parametros',"WHERE id_contrato_plano_pessoa = '".$conteudo['id_contrato_plano_pessoa']."' AND exibir_protocolo = '1'");
        if($dados_protocolo){
            $protocolo_exibe = "<span  style='margin-bottom:5px;'><strong>Protocolo:</strong> ".$conteudo['protocolo']."</span><br>"; 
        }else{
            $protocolo_exibe = '';
        }
        
        ?>
        <div class='panel panel-default'>
            <div class='panel-heading clearfix'>
                <div class="panel-title text-left pull-left" style='font-size:14px !important;'>
                    <span style='margin-bottom:5px;'><strong>Empresa: </strong><?=$dados_empresa[0]['nome']?> - <strong>Data:</strong> <?=converteDataHora($conteudo['data_registro'])?></span><br>
                </div>
                <div class="panel-title text-right pull-right" style='font-size:14px !important;'>
                    <button data-toggle='collapse' class="atendimentos_realizados btn btn-info btn-xs pull-right" data-target="#collapse<?=$conteudo['id_monitoramento_queda']?>"><i class="fa fa-plus"></i> Detalhes</button>
                </div>               
            </div>
            <div id='collapse<?=$conteudo['id_monitoramento_queda']?>' class='panel-collapse collapse'>
                <div class='panel-body'>

                    <ul class="list-group">
                        <li class="list-group-item list-atendimento">
                            <strong>Data da queda: </strong><?=converteDataHora($conteudo['data_queda'])?>
                        </li>

                        <?php 
                            $status = array(
                                '1' => 'Com sucesso',
                                '2' => 'Sem sucesso'
                            );
                        ?>

                        <li class="list-group-item list-atendimento">
                            <strong>Contato com o técnico: </strong><?=$status[$conteudo['status_contato']]?>
                        </li>

                        <?php
                        if($conteudo['status_contato'] == '1'):
                        ?>
                        <li class="list-group-item list-atendimento">
                            <strong>Nome do técnico: </strong><?=$conteudo['nome_tecnico']?>
                        </li>

                        <li class="list-group-item list-atendimento">
                            <strong>Telefone: </strong><?=$conteudo['telefone']?>
                        </li>
                        <?php
                        endif;
                        ?>

                        <li class="list-group-item list-atendimento">
                            <strong>Informações adicionais: </strong><br><?=nl2br($conteudo['informacao'])?>
                        </li>

                        <?php
                        $id_monitoramento = $conteudo['id_monitoramento_queda'];
                        $pops = DBREad('','tb_pop_queda', "WHERE id_monitoramento_queda = '$id_monitoramento'");
                        ?>

                        <li class="list-group-item list-atendimento">
                            <strong>POP(s): </strong><br>
                            <?php
                                foreach($pops as $p){
                                    echo $p['nome']."<br>";
                                }
                            ?>
                        </li>
        
                        <li class="list-group-item list-atendimento">
                            <strong>Atendente: </strong><?=$conteudo['nome']?>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>
        
    </div>
<?php
endif;

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