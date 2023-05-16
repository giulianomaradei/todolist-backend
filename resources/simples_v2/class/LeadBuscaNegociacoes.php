<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$style='';
if($parametros['responsavel'] || $parametros['data_inicio'] || $parametros['data_conclusao'] || $parametros['servico'] || $parametros['nome']){
    $style ='background: #d4edda;';
}

$posicao_popover = '';
$col = '12';
if($parametros['id_lead_status']){
    $id_lead_status = "AND id_lead_status = '".$parametros['id_lead_status']."'";
    $col = '2';
    $posicao_popover = 'right';
}

if($parametros['responsavel']){
    $id_usuario_responsavel = "AND a.id_usuario_responsavel = '".$parametros['responsavel']."' ";
}

/* if($parametros['data_inicio']){
    $data = converteData($parametros['data_inicio']);

    $data1 = $data.' 00:00:00';
    $data2 = $data.' 23:59:59';

    $data_inicio = "AND a.data_inicio BETWEEN '$data1' AND '$data2'";
}

if($parametros['data_conclusao']){
    $data = converteData($parametros['data_conclusao']);

    $data3 = $data.' 00:00:00';
    $data4 = $data.' 23:59:59';

    $data_conclusao = "AND a.data_conclusao BETWEEN '$data3' AND '$data4'";
} */

if ($parametros['data_de'] != '' && $parametros['data_ate'] == '') {
    $data = converteData($parametros['data_de']);

    $data_de = $data.' 00:00:00';

    $filtro_data = "AND a.data_inicio >= '$data_de'";

} else if ($parametros['data_de'] == '' && $parametros['data_ate'] != '') {
    $data = converteData($parametros['data_ate']);

    $data_ate = $data.' 23:59:59';

    $filtro_data = "AND a.data_inicio <= '$data_ate'";

} else if ($parametros['data_de'] != '' && $parametros['data_de'] != '') {
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND (a.data_inicio >= '$data_de' AND a.data_inicio <= '$data_ate')";
}

if($parametros['servico']){
    $cod_servico = "AND f.cod_servico = '".$parametros['servico']."' ";
}

if($parametros['estado']){
    $estado = "AND g.id_estado = '".$parametros['estado']."' ";
}

?>              
    <?php 
        $resultado_negocios = array();
        $qtd_total_geral_negocios = 0;
        $valor_total_geral_negocios = 0;
        $valor_total_geral_adesoes = 0;
        $qtd_total_status = array();
        $valor_total_status = array();

        $filtro_negocio = '';
        
        $dados_status = DBRead('', 'tb_lead_status', "WHERE exibe = 1 $id_lead_status ORDER BY posicao ASC");
        
        if($dados_status){
            foreach($dados_status as $conteudo_status){
                $dados_negocios = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_usuario b ON a.id_usuario_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_lead_status d ON a.id_lead_status = d.id_lead_status INNER JOIN tb_pessoa e ON a.id_pessoa = e.id_pessoa LEFT JOIN tb_plano f ON a.id_plano = f.id_plano INNER JOIN tb_cidade g ON e.id_cidade = g.id_cidade WHERE a.id_lead_status = '".$conteudo_status['id_lead_status']."' AND (e.nome LIKE '%$letra%')  $id_usuario_responsavel $filtro_data $cod_servico $estado AND a.excluido = 1 AND a.andamento = 0",'a.id_lead_negocio, a.id_plano, a.descricao AS negocio_descricao, a.valor_contrato, a.valor_adesao, a.data_inicio, a.data_conclusao, a.tipo_negocio, c.nome AS nome_responsavel, d.descricao AS status_descricao, e.nome AS nome_lead, f.cod_servico, f.nome AS nome_plano, a.id_lead_status');
                $aux = 0;

                if($dados_negocios){
                    foreach($dados_negocios as $conteudo_negocios){

                        $valor_total_status[$conteudo_negocios['id_lead_status']] += $conteudo_negocios['valor_contrato'];
                        $qtd_total_status[$conteudo_negocios['id_lead_status']] += 1;

                        $dados_timeline = DBRead('', 'tb_lead_timeline a', "INNER JOIN tb_lead_reuniao b ON a.id_lead_timeline = b.id_lead_timeline WHERE a.id_lead_negocio = '".$conteudo_negocios['id_lead_negocio']."'", 'a.id_lead_timeline, a.finalizado, b.data');

                        //verde
                        $resultado = '#04B431';

                        if($dados_timeline){                            
                            foreach($dados_timeline as $conteudo_timeline){                        
                                $data_agora = getDataHora();
                                $data_tarefa = $conteudo_timeline['data'];

                                if(!$conteudo_timeline['finalizado'] && $data_tarefa >= $data_agora){
                                    if($resultado != '#B40404'){
                                        $resultado = '#DBA901'; //amarelho
                                    }
                                }

                                if(!$conteudo_timeline['finalizado'] && $data_tarefa < $data_agora){
                                    $resultado = '#B40404'; //vermelho
                                }
                            }
                        }
                        
                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['tarefas'] = $resultado;

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['id_lead_negocio'] = $conteudo_negocios['id_lead_negocio'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['negocio_descricao'] = $conteudo_negocios['negocio_descricao'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['valor_contrato'] = $conteudo_negocios['valor_contrato'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['valor_adesao'] = $conteudo_negocios['valor_adesao'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['data_inicio'] = $conteudo_negocios['data_inicio'];
                        
                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['data_conclusao'] = $conteudo_negocios['data_conclusao'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['nome_responsavel'] = $conteudo_negocios['nome_responsavel'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['status_descricao'] = $conteudo_negocios['status_descricao'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['nome_lead'] = $conteudo_negocios['nome_lead'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['cod_servico'] = $conteudo_negocios['cod_servico'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['nome_plano'] = $conteudo_negocios['nome_plano'];

                        $resultado_negocios[$conteudo_status['id_lead_status']][$aux]['tipo_negocio'] = $conteudo_negocios['tipo_negocio'];

                        $qtd_total_geral_negocios++;
                        $valor_total_geral_negocios += ($conteudo_negocios['valor_contrato']);
                        $valor_total_geral_adesoes += ($conteudo_negocios['valor_adesao']);
                        $aux++;
                    }
                }
            }
        }

        /*echo '<pre>';
        var_dump($resultado_negocios);
        echo '</pre>'; */
    ?>

    <style>
        /* th:hover{
            color: white;
            background-color: #265a88;
        } */
        .td-table-hover:hover{
            color: white !important;
            background-color: #265a88 !important;
            
        }
        .popover{
            color: black !important;
            border-top: 2px solid #D8D8D8;
            border-left: 2px solid #D8D8D8;
            border-right: 2px solid #D8D8D8;
            border-bottom: 2px solid #D8D8D8;
            z-index: 9999 !important;
        }
        .fa-question-circle:hover{
            color: white !important;
        }
        .btn-exluir-negocio:hover{
             color: #CD0000 !important;
        }
        .btn-sim{
            margin-left: 130px !important;
        }
        .btn-nao{
            margin-left: 10px !important;
        }
    </style>

    <div class="row" style="margin-bottom: 20px; margin-top: 15px; ">
        <div class="col-md-12" style="margin-left: 3px; margin-right: 3px;">
            <span style="font-size: 40px;">
                <i class="fa fa-briefcase" aria-hidden="true"></i> <?=$qtd_total_geral_negocios?>
            </span><small> | Negócios</small>
            <span style="font-size: 40px; margin-left:20px;">
                <strong style="font-family: fantasy;">R$</strong> <?= converteMoeda($valor_total_geral_negocios)?>
            </span><small> | Em contratos</small>
            <span style="font-size: 40px; margin-left:20px;">
                <strong style="font-family: fantasy;">R$</strong> <?= converteMoeda($valor_total_geral_adesoes)?>
            </span><small> | Em adesões</small>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-<?=$col?>" style="padding: 2 2 2 2;">
            <div class="table" style="height: 420px !important;">
                <table class="table">
                    <tr style="background-color: #e8e8e8;">

                        <?php 
                            if($dados_status){

                                foreach($dados_status as $conteudo_status){

                                    if($valor_total_status[$conteudo_status['id_lead_status']]){
                                        $valor_status = $valor_total_status[$conteudo_status['id_lead_status']];
                                    }else{
                                        $valor_status = 0;
                                    }

                                    if($qtd_total_status[$conteudo_status['id_lead_status']]){
                                        $qtd_status = $qtd_total_status[$conteudo_status['id_lead_status']];
                                    }else{
                                        $qtd_status = 0;
                                    }                                        

                                    if($qtd_total_status[$conteudo_status['id_lead_status']] == 1){
                                        $legenda = 'negócio';
                                    }else{
                                        $legenda = 'negócios';
                                    }

                                    $legenda_status = '';
                                    if ($conteudo_status['legenda'] != '') {
                                        $legenda_status = 'data-toggle="popover" data-html="true" data-placement="top" data-trigger="focus" title="" data-content="'.$conteudo_status['legenda'].'"';
                                    }
                        ?>      
                                    <th class="text-center" style="min-width: 240px;" <?=$legenda_status?>><?=$conteudo_status['descricao']?>
                                
                                        <br>
                                        <span style="font-size: 12px; font-weight:300; ">
                                            Total: R$: <?=converteMoeda($valor_status)?> <br> (<?=$qtd_status?> <?=$legenda?>)
                                        </span>
                                    </th>
                        <?php
                                }
                            }
                        ?>

                    </tr>

                    <tr>
                    <?php
                        if($dados_status){

                            $cont = 0;
                            foreach($dados_status as $conteudo_status){
                            $cont++;

                            if($cont > 5){
                                $posicao_popover = 'left';
                            }else{
                                $posicao_popover = 'right';
                            }
                    ?>
                            <td style="padding: 0 0 0 0;">
                                <div class="table" style="overflow: auto; height: 340px !important; ">
                                    <table class="table table-condensed" style="border-top: none !important; border-right: 0.2px solid #e8e8e8; border-left: 0.5px solid #e8e8e8; background-color: #FAFAFA;">
                    <?php
                                        if($resultado_negocios[$conteudo_status['id_lead_status']]){
                                            foreach($resultado_negocios[$conteudo_status['id_lead_status']] as $conteudo_negocios){
                                            
                                            $id = $conteudo_negocios['id_lead_negocio'];
                                            $nome_lead = $conteudo_negocios['nome_lead'];
                                            $valor_contrato = converteMoeda($conteudo_negocios['valor_contrato']);
                                            $valor_adesao = converteMoeda($conteudo_negocios['valor_adesao']);
                                            $data_inicio = converteData($conteudo_negocios['data_inicio']);
                                            $responsavel = $conteudo_negocios['nome_responsavel'];
                                            $data_conclusao = converteData($conteudo_negocios['data_conclusao']);
                                            $descricao = $conteudo_negocios['negocio_descricao'];

                                            if($conteudo_negocios['cod_servico'] == ''){
                                                $servico = 'N/D';
                                            }else{
                                                $servico = getNomeServico($conteudo_negocios['cod_servico']);
                                            }

                                            if($conteudo_negocios['nome_plano'] == ''){
                                                $nome_plano = 'N/D';
                                            }else{
                                                $nome_plano = $conteudo_negocios['nome_plano'];
                                            }
                                            
                                            $tipo_negocio = $conteudo_negocios['tipo_negocio'];
                                            $cor_tarefa = $conteudo_negocios['tarefas'];
                                            $total = $conteudo_negocios['valor_contrato']; 

                    ?>
                                        <tr class="td-table-hover" style="border-bottom: 1px solid #e8e8e8 !important; cursor: pointer;">
                                            <td onclick="window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=<?=$id?>'"  class="tex-center col-md-12" data-toggle="popover" data-html="true" data-placement="<?=$posicao_popover?>" data-trigger="focus" title="" data-content="<i class='fa fa-info-circle' aria-hidden='true'></i> Detalhes do Negócio #<?php echo $id?>
                                                <hr>
                                                Empresa/Pessoa: <strong><?php echo $nome_lead?></strong>
                                                <br>
                                                Tipo de negócio: <strong><?php echo $tipo_negocio?></strong>
                                                <br>
                                                Data de início: <strong><?php echo $data_inicio?></strong>
                                                <hr>
                                                Responsável: <strong><?php echo $responsavel?></strong>
                                                <br>
                                                Valor contrato: <strong>R$ <?php echo $valor_contrato?></strong>
                                                <br>
                                                Valor adesão: <strong>R$ <?php echo $valor_adesao?></strong>
                                                <br> Serviço: <strong><?php echo $servico?></strong>
                                                <br> Plano: <strong><?php echo $nome_plano?></strong>
                                                <br> Data de conclusão: <strong><?php echo $data_conclusao?></strong>
                                                <br><hr> Descrição: <strong><?php echo nl2br(limitarTexto($descricao, 400))?></strong>">
                                                <i class="fa fa-circle" aria-hidden="true" style="font-size: 7px; color: <?=$cor_tarefa?>; margin-left: 3px; margin-right: 3px;">
                                                </i> 
                                                <span style="font-size: 14px; margin-left: 1px;">
                                                    <?=$nome_lead?> <br>
                                                </span>
                                                <span class="money" style="font-size: 14px; margin-left: 15px;">
                                                    R$ <?=$valor_contrato?>
                                                </span>
                                            
                                                <!--  <a class="" tabindex="0" role="button" style="cursor:pointer;" >
                                                <i class="fa fa-trash" style="color: #B40404; font-size: 13px;"></i>
                                                </a>  -->
                                            </td>
                                            <td>
                                                <!-- <i class="fa fa-trash btn-exluir-negocio" style="color: #8B8989; font-size: 14px;" title="Tem certeza que deseja <strong>excluir</strong> este negócio?" data-toggle="excluir" data-html="true" data-placement="bottom" ></i> -->
                                            </td>
                                        </tr>
                    <?php
                                        }
                                        }else{
                                            echo "<tr class='' style='border-bottom: 1px solid #e8e8e8 !important; background-color: #FBF8EF'>
                                                    <td style='vertical-align: middle;'>
                                                        <span style='display: block; padding: 10px 10px 10px 10px !important;'>
                                                            <i class='fa fa-warning' style='margin-left: 42px; color: gray'>
                                                            </i> Não há negócios<br>
                                                        </span>
                                                    </td>
                                                </tr>";
                                            /* echo "<i class='fa fa-warning text-center' style='margin-left: 30px; margin-top: 30px !important;'></i> Não há negócios"; */
                                        }
                    ?>
                                    </table>
                                </div>
                            </td>
                    <?php
                            }
                        }
                    ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('[data-toggle="popover"]').popover({ trigger: "hover", container: "body" });
            
            var popupElement = "<a class='btn-sim btn btn-xs btn-success' href='class/LeadNegocio.php?excluir=<?php echo $id ?>' style='color: #ccc;' data-ls-popover='hide'>Sim </a><a class='btn-nao btn btn-xs btn-danger' style='color: #ccc;'>Não</a>";

            $('[data-toggle="excluir"]').popover({
                animation: true,
                content: popupElement,
                html: true
            });
        });

        $(document).on('click', ".btn-nao", function(){
            var teste = $(this).parent().parent();
            teste.popover('hide');
        });   
    </script>

