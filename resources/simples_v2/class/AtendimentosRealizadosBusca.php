<style>
.list-atendimento{
    font-size: 14px;
}
</style>
<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

$valor = addslashes($parametros['visualizar']);
$empresa = addslashes($parametros['empresa']);
$filtro = addslashes($parametros['filtro']);
$data = addslashes($parametros['data']);

$id_usuario = $_SESSION['id_usuario'];
$id_contrato_plano_pessoa = $parametros['id_contrato_plano_pessoa'];

$integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

// Informações da query
if($valor == 0){
    $filtros_usuario = '';
}else{
    $filtros_usuario  = "AND a.id_usuario = '".$id_usuario."'";
}

if($empresa == 0){
    $filtros_empresa = 'AND a.id_contrato_plano_pessoa = '.$id_contrato_plano_pessoa.'';
}else{
    $filtros_empresa  = "";
}

if($data == 0){
    $filtros_data = '';
}else{
    $filtros_data  = "AND a.data_fim LIKE '".converteDataHora($data)."%'";
}

// Informações da query
$filtros_query  = "WHERE (a.data_fim LIKE '%$filtro%' OR a.assinante LIKE '%$filtro%' OR c.nome LIKE '%$filtro%' OR g.nome LIKE '%$filtro%' OR a.cpf_cnpj LIKE '%$filtro%' OR a.fone1 LIKE '%$filtro%' OR a.fone2 LIKE '%$filtro%') ".$filtros_empresa." AND gravado = 1 AND falha != 2 ".$filtros_usuario." ".$filtros_data." ORDER BY a.data_fim DESC";

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

$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_situacao_atendimento d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_situacao e ON d.id_situacao = e.id_situacao INNER JOIN tb_contrato_plano_pessoa f ON f.id_contrato_plano_pessoa = a.id_contrato_plano_pessoa INNER JOIN tb_pessoa g ON g.id_pessoa = f.id_pessoa $filtros_query", " COUNT(*) AS 'num_registros'");


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

$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_situacao_atendimento d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_situacao e ON d.id_situacao = e.id_situacao INNER JOIN tb_contrato_plano_pessoa f ON f.id_contrato_plano_pessoa = a.id_contrato_plano_pessoa INNER JOIN tb_pessoa g ON g.id_pessoa = f.id_pessoa $filtros_query LIMIT $inicio,$maximo", "a.*, c.nome AS atendente, e.nome AS situacao");

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
            <div class='panel-heading clearfix bloco-atendimento-passado'>
                <div class="panel-title text-left pull-left" style='font-size:14px !important;'>
                    <span style='margin-bottom:5px;'><strong>Empresa: </strong><?=$dados_empresa[0]['nome']?> - <strong>Data:</strong> <?=converteDataHora($conteudo['data_fim'])?></span>
                    <br><?=$protocolo_exibe?>
                    <span><strong>Assinante: </strong><span class='utiliza-assinante'><?=$conteudo['assinante']?></span></span>
                </div>
                <div class="panel-title text-right pull-right" style='font-size:14px !important;'>
                    <button data-toggle='collapse' class="atendimentos_realizados btn btn-info btn-xs pull-right" data-target="#collapse<?=$conteudo['id_atendimento']?>" style="width: 102px !important;"><i class="fa fa-plus"></i> Detalhes</button><br><br>                  
                    <button class="btn btn-primary btn-xs pull-right utilizar-dados" data_nome_assinante="<?=$conteudo['assinante']?>" style="width: 102px !important;"><i class="fa fa-hand-lizard-o"></i> Utilizar dados</button>
                </div>               
            </div>
            <div id='collapse<?=$conteudo['id_atendimento']?>' class='panel-collapse collapse'>
                <div class='panel-body'>

                    <ul class="list-group">
                        <li class="list-group-item list-atendimento"><strong>Contato: </strong><span class='utiliza-contato'><?=$conteudo['contato']?></span></li>
                        <li class="list-group-item list-atendimento"><strong>Fone 1: </strong><span class='utiliza-fone1'><?=$conteudo['fone1']?></span></li>
                        <?php
                        if($conteudo['fone2']):
                        ?>
                        <li class="list-group-item list-atendimento">
                            <strong>Fone 2: </strong><span class='utiliza-fone2'><?=$conteudo['fone2']?></span>
                        </li>
                        <?php
                        endif;
                        ?>
                        <?php
                        if($conteudo['cpf_cnpj']):
                        ?>
                        <li class="list-group-item list-atendimento">
                            <strong>CPF/CNPJ: </strong><span class='utiliza-cpf_cnpj'><?=$conteudo['cpf_cnpj']?></span>
                        </li>
                        <?php
                        endif;
                        ?>
                        <?php
                        if($conteudo['dado_adicional']):
                        ?>
                        <li class="list-group-item list-atendimento"><strong><?=$conteudo['descricao_dado_adicional']?>: </strong><span class='utiliza-dado_adicional'><?=$conteudo['dado_adicional']?></span></li>
                        <?php
                        endif;
                        ?>
                        <li class="list-group-item list-atendimento">
                            <strong>Atendente: </strong><?=$conteudo['atendente']?>
                        </li>
                        <?php
                        $id_atendimento = $conteudo['id_atendimento'];
                        $fluxo_atendimento = DBREad('','tb_atendimento', "WHERE id_atendimento = '$id_atendimento'");
                        ?>

                        <li class="list-group-item list-atendimento">
                            <?php
                            echo nl2br($fluxo_atendimento[0]['os']);
                            echo "<br><br>" . $conteudo['situacao'];
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php
    endforeach;  
    ?>
    <script>
        
        $('.utilizar-dados').on('click', function(){
            $('#contato').val($(this).parent().parent().parent().find('.utiliza-contato').text());
            $('.assinante').val($(this).parent().parent().parent().find('.utiliza-assinante').text());
            $('#fone1').unmask();
            $('#fone2').unmask();
            $('#fone1').val($(this).parent().parent().parent().find('.utiliza-fone1').text());
            $('#fone2').val($(this).parent().parent().parent().find('.utiliza-fone2').text());
            $('#solicitacao').val($(this).parent().parent().parent().find('.utiliza-dado_adicional').text());
            if($(this).parent().parent().parent().find('.utiliza-cpf_cnpj').text().length > 11){
                $('#tipo').val('cnpj');
                $('#label_cpf_cnpj').text('*CNPJ:');
                $('#cpf_cnpj').unmask();
                $('#cpf_cnpj').val($(this).parent().parent().parent().find('.utiliza-cpf_cnpj').text());
                $('#cpf_cnpj').mask('00.000.000/0000-00', {reverse: true, placeholder: '00.000.000/0000-00'});
            }else{
                $('#tipo').val('cpf');
                $('#label_cpf_cnpj').text('*CPF:');
                $('#cpf_cnpj').unmask();
                $('#cpf_cnpj').val($(this).parent().parent().parent().find('.utiliza-cpf_cnpj').text());
                $('#cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});
            }
            <?php
            if($integra){
            ?>  
                sessionStorage.setItem("id_assinante", '');
                $("#container-info-assinante").html('');
                $("#assinante").val('');
                $('.assinante').focus();
            <?php
            }
            ?>
        });
    </script>  

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