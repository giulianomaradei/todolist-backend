<?php
require_once(__DIR__."/System.php");


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$usuario = $parametros['usuario'];

$data = '';
$id_usuario_busca = '';
$id_lead_negocio = '';
 
if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND a.data BETWEEN '$data_de' AND '$data_ate'";
}

if($parametros['usuario']){
    $id_usuario_busca = "AND a.id_usuario = '".$parametros['usuario']."'";
}

$tarefa = '';
$status_finalizado = '';

if ($parametros['itens_vinculos'] == '0') {
  
  $id_lead_negocio = "AND a.id_lead_negocio = 0";

} else if ($parametros['itens_vinculos'] == '1') {

  $id_lead_negocio = "AND a.id_lead_negocio != 0";

} 

if ($parametros['tarefas'] == '2' && !$parametros['usuario']) {

  $tarefa .= 'INNER JOIN tb_lead_reuniao g ON a.id_lead_timeline = g.id_lead_timeline';

  if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND g.data BETWEEN '$data_de' AND '$data_ate'";
  }
} else if ($parametros['tarefas'] == '2' && $parametros['usuario']) {

  $tarefa .= 'INNER JOIN tb_lead_reuniao g ON a.id_lead_timeline = g.id_lead_timeline INNER JOIN tb_lead_usuario_reuniao h ON g.id_lead_reuniao = h.id_lead_reuniao';

  $id_usuario_busca = '';
  $usuario_tarefa = "AND h.id_usuario = '".$parametros['usuario']."'";
  
  if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND g.data BETWEEN '$data_de' AND '$data_ate'";
  }

} else if ($parametros['tarefas'] == '3' && $parametros['usuario']) {

  $tarefa .= ' INNER JOIN tb_lead_reuniao g ON a.id_lead_timeline = g.id_lead_timeline INNER JOIN tb_lead_usuario_reuniao h ON g.id_lead_reuniao = h.id_lead_reuniao';

  $id_usuario_busca = '';
  $usuario_tarefa = "AND h.id_usuario = '".$parametros['usuario']."'";

  $status_finalizado = "AND a.finalizado IS NULL ";
  
  if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND g.data BETWEEN '$data_de' AND '$data_ate'";
  }

} else if ($parametros['tarefas'] == '3' && !$parametros['usuario']) {

  $tarefa .= 'INNER JOIN tb_lead_reuniao g ON a.id_lead_timeline = g.id_lead_timeline';

  $status_finalizado = "AND a.finalizado IS NULL ";

  if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND g.data BETWEEN '$data_de' AND '$data_ate'";
  }

} else if ($parametros['tarefas'] == '4' && !$parametros['usuario']){

  $tarefa .= ' INNER JOIN tb_lead_reuniao g ON a.id_lead_timeline = g.id_lead_timeline';

  $status_finalizado = "AND a.finalizado != '' ";

  if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND g.data BETWEEN '$data_de' AND '$data_ate'";
  }

} else if ($parametros['tarefas'] == '4' && $parametros['usuario']){

  $tarefa .= 'INNER JOIN tb_lead_reuniao g ON a.id_lead_timeline = g.id_lead_timeline INNER JOIN tb_lead_usuario_reuniao h ON g.id_lead_reuniao = h.id_lead_reuniao';

  $id_usuario_busca = '';
  $usuario_tarefa = "AND h.id_usuario = '".$parametros['usuario']."'";

  $status_finalizado = "AND a.finalizado != '' ";
  
  if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $data = "AND g.data BETWEEN '$data_de' AND '$data_ate'";
  }

}

if($parametros['limit']){
  $limit = $parametros['limit'];
}else if($parametros['usuario'] || $parametros['nome'] || ($parametros['data_de'] && $parametros['data_ate'] )){
  $limit = 3;
}else{
  $limit = 3;
}

// Informações da query
$filtros_query  = "LEFT JOIN tb_lead_negocio b ON a.id_lead_negocio = b.id_lead_negocio LEFT JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa LEFT JOIN tb_plano f ON b.id_plano = f.id_plano $tarefa WHERE (a.descricao LIKE '%$letra%' OR c.nome LIKE '%$letra%') $usuario_tarefa $id_lead_negocio $id_usuario_busca $status_finalizado $data ORDER BY a.data DESC LIMIT $limit";

###################################################################################
// INICIO DO CONTEÚDO // 

?>

<style>
    textarea[readonly]{
      background-color: #FAFAFA !important;
    }
    .hr-timeline{
      border: 1px solid #DCDCDC;
      margin-top: 0px;
    }
    .timeline {
      list-style: none;
      padding: 10px 0 20px;
      position: relative;
    }
    .timeline:before {
      top: 0;
      bottom: 0;
      position: absolute;
      content: " ";
      width: 3px;
      background-color: #eeeeee;
      left: 5%;
      margin-left: -1.5px;
      height: 100%;
    }
    .timeline > li {
      margin-bottom: 20px;
      position: relative;
    }
    .timeline > li:before,
    .timeline > li:after {
      content: " ";
      display: table;
    }
    .timeline > li:after {
      clear: both;
    }
    .timeline > li:before,
    .timeline > li:after {
      content: " ";
      display: table;
    }
    .timeline > li:after {
      clear: both;
    }
    .timeline > li > .timeline-panel {
      width: 83%;
      float: left;
      border: 1px solid #d4d4d4;
      border-radius: 2px;
      padding: 20px;
      position: relative;
      -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }
    .timeline > li > .timeline-panel:before {
      position: absolute;
      top: 26px;
      right: -15px;
      display: inline-block;
      border-top: 15px solid transparent;
      border-left: 15px solid #ccc;
      border-right: 0 solid #ccc;
      border-bottom: 15px solid transparent;
      content: " ";
    }
    .timeline > li > .timeline-panel:after {
      position: absolute;
      top: 27px;
      right: -14px;
      display: inline-block;
      border-top: 14px solid transparent;
      border-left: 14px solid #fff;
      border-right: 0 solid #fff;
      border-bottom: 14px solid transparent;
      content: " ";
    }
    .timeline > li > .timeline-badge {
      color: #fff;
      width: 50px;
      height: 50px;
      line-height: 50px;
      font-size: 1.4em;
      text-align: center;
      position: absolute;
      top: 16px;
      left: 5%;
      margin-left: -25px;
      background-color: #999999;
      z-index: 100;
      border-top-right-radius: 50%;
      border-top-left-radius: 50%;
      border-bottom-right-radius: 50%;
      border-bottom-left-radius: 50%;
    }
    .timeline > li.timeline-inverted > .timeline-panel {
      float: left;
      margin-left: 110px;
    }
    .timeline > li.timeline-inverted > .timeline-panel:before {
      border-left-width: 0;
      border-right-width: 15px;
      left: -15px;
      right: auto;
    }
    .timeline > li.timeline-inverted > .timeline-panel:after {
      border-left-width: 0;
      border-right-width: 14px;
      left: -14px;
      right: auto;
    }
    .timeline-badge.primary {
      background-color: #2e6da4 !important;
    }
    .timeline-badge.success {
      background-color: #3f903f !important;
    }
    .timeline-badge.warning {
      background-color: #f0ad4e !important;
    }
    .timeline-badge.danger {
      background-color: #d9534f !important;
    }
    .timeline-badge.info {
      background-color: #5bc0de !important;
    }
    .timeline-title {
      margin-top: 0;
      color: inherit;
    }
    .timeline-body > p,
    .timeline-body > ul {
      margin-bottom: 0;
    }
    .timeline-body > p + p {
      margin-top: 5px;
    }

    @media only screen and (max-width: 1115px) {
        .timeline > li.timeline-inverted > .timeline-panel {
          margin-left: 130px;
        }
    }
    @media only screen and (min-width: 1615px) {
        .timeline > li.timeline-inverted > .timeline-panel {
            margin-left: 140px;
        }
    }
    @media only screen and (max-width: 1400px) {
        .ver-mais {
            margin-left: 3px;
        }
    }
    @media only screen and (min-width: 1401px) {
        .ver-mais {
            padding-left: 0px !important;
            margin-left: -15px !important;
        }
    }
    .popover{
        max-width: 100%; 
    }
</style>

<?php

$dados = DBRead('', 'tb_lead_timeline a', $filtros_query, 'a.id_lead_timeline, a.id_usuario, a.data, a.descricao as descricao_timeline, a.id_lead_negocio, a.finalizado, a.id_usuario_finalizou, a.id_lead_tipo_item_timeline, b.tipo_negocio, b.data_inicio, b.data_conclusao, b.valor_contrato, b.valor_adesao, b.descricao, c.nome as nome_lead, e.nome, f.cod_servico, f.nome as nome_plano, a.convidado');

$texto = '';

if (!$dados) {

  $texto = 'Não há registros!';
   /*  echo "<p class='alert alert-warning' style='text-align: center'>";
    if (!$letra) {
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>"; */
} else {

    $qtd = sizeof($dados);

    $cont = 1;

    $itens_nao_lidos = array();
    $id_usuario = $_SESSION['id_usuario'];

    foreach($dados as $conteudo_itens){

      $count = DBRead('', 'tb_lead_timeline_visualizado', "WHERE id_usuario = '$id_usuario ' AND id_lead_timeline = '".$conteudo_itens['id_lead_timeline']."'", 'count(*) as count');

      if($count[0]['count'] == 0){
          array_push($itens_nao_lidos, $conteudo_itens['id_lead_timeline']);
      }
    }

    foreach($dados as $conteudo){

        $id = $conteudo['id_lead_negocio'];
        $descricao = $conteudo['descricao'];
        $valor_contrato = converteMoeda($conteudo['valor_contrato']);
        $valor_adesao = converteMoeda($conteudo['valor_adesao']);
        $responsavel = $conteudo['nome'];
        $nome_lead = $conteudo['nome_lead'];
        $descricao = $conteudo['descricao'];
        $data_inicio = converteData($conteudo['data_inicio']);
        $data_conclusao = converteData($conteudo['data_conclusao']);
        $tipo_negocio = $conteudo['tipo_negocio'];
        $convidado = $conteudo['convidado'];

        if($id == 0){
          $id = 'N/D';
        }

        if($nome_lead == NULL){
          $nome_lead = 'Item sem vínculo!';
          $sem_vinculo = '<span>Item sem vínculo! <i class="fa fa-warning" style="color: #d9534f;"></i></span>';
          
        }else{
          $sem_vinculo = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'>$nome_lead</a>";
        }

        if($conteudo['cod_servico'] == ''){
            $servico = 'N/D';
        }else{
            $servico = getNomeServico($conteudo['cod_servico']);
        }

        if($conteudo['nome_plano'] == ''){
            $nome_plano = 'N/D';
        }else{
            $nome_plano =  $conteudo['nome_plano'];
        }

        if($data_inicio == ''){
          $data_inicio = 'N/D';
        }

        if($data_conclusao == ''){
          $data_conclusao = 'N/D';
        }

        if($tipo_negocio == ''){
          $tipo_negocio = 'N/D';
        }

        if ($conteudo['id_lead_tipo_item_timeline'] == 1) {
          $badge = 'glyphicon glyphicon-envelope';
          $cor = '#265a88';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 2) {
          $badge = 'glyphicon-earphone';
          $cor = '#5bc0de';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 3) {
          $badge = 'glyphicon glyphicon-usd';
          $cor = '#20B2AA';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 4) {
          $badge = 'glyphicon glyphicon-pushpin';
          $cor = '#9370DB';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 5) {
          $badge = 'glyphicon glyphicon-map-marker';
          $cor = '#EE8262';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 6) {
          $badge = 'glyphicon glyphicon-briefcase';
          $cor = '#59ba1f';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 7) {
          $badge = 'glyphicon glyphicon-file';
          $cor = '#151515';

        } else if ($conteudo['id_lead_tipo_item_timeline'] == 8) {
          $badge = 'glyphicon glyphicon-comment';
          $cor = '#00FFBF';
        }

        $reuniao = DBRead('', 'tb_lead_reuniao', "WHERE id_lead_timeline = '".$conteudo['id_lead_timeline']."' ");
                                   
        if($reuniao){
            $participantes = DBRead('', 'tb_lead_usuario_reuniao a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_lead_reuniao = '".$reuniao[0]['id_lead_reuniao']."' ", 'b.id_perfil_sistema, c.nome, a.id_usuario');
        }

        $nomes = '';
        if($participantes){
            foreach($participantes as $p){
                $nomes .= $p['nome'].' - ';
            }
        }
        
        $size = strlen($nomes);
        $nomes = substr($nomes,0, $size-2);

        if(in_array($conteudo['id_lead_timeline'], $itens_nao_lidos)){

          if($conteudo['id_usuario'] != $id_usuario){
            $notifica = '<i id="i_exclamation_'.$conteudo['id_lead_timeline'].'" class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i>';
          }
          else{
            $notifica = "";
          }
        }

    ?>  
        <div class="row-timeline" style="padding-bottom: 0 !important;">
            <ul class="timeline" style="padding-bottom: 5px !important;">
                <li class="timeline-inverted">
                    <div class="timeline-badge" style="background-color:  <?=$cor?>;">
                        <i class="glyphicon <?=$badge?>" style="font-size: 19px; margin-top: 15px !important;"></i>
                    </div>
                    <div class="timeline-panel">
                      <div class="timeline-heading" id="<?=$cont?>">
                      
                          <span class="timeline-title" style="font-size: 16px;" data-toggle="info" data-html="true" data-placement="right" data-trigger="focus" title="" data-content="<i class='fa fa-info-circle' aria-hidden='true'></i> Detalhes do Negócio #<?php echo $id?>
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
                          <br><hr> Descrição: <strong><?php echo nl2br($descricao)?></strong>">
                              <strong> <?=$notifica?> 
                                <?=$sem_vinculo?>
                              </strong>
                          </span>

                          <br><br>

                          <span class="timeline-title" style="font-size: 14px;">
                              <strong>Feito por:</strong> <?= $conteudo['nome'] ?>
                          </span>

                          <span class="timeline-title pull-right" style="font-size: 14px;">
                              <strong>Data:</strong> <?= converteDataHora($conteudo['data']) ?>
                          </span>
                      </div>

                      <hr>
                      <div class="timeline-body">
                          <?= nl2br($conteudo['descricao_timeline']) ?>
                      </div>
                      <hr>

                      <?php
                          if($reuniao){

                            if ($conteudo['convidado'] !='') {
                              $convidado = $conteudo['convidado'];

                            } else {
                              $convidado = 'ND';
                            }
                      ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><strong>Reunião: </strong><?= converteDataHora($reuniao[0]['data']) ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span class="pull-right"><strong>Participantes: </strong><?= $nomes ?></span> 
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <span><strong>Convidado: </strong><?= $convidado ?></span>
                                </div>
                            </div>
                      <?php
                          }
                      ?>
                      <br>

                        <?php 

                            $finalizado = 'default';
                            $finalizado_icon = 'fa-square-o';
                            $finalizado_text = 'Finalizar';

                            if($conteudo['finalizado'] != ''){
                              $finalizado = 'success';
                              $finalizado_icon = 'fa-check-square';
                              $finalizado_text = 'Finalizado';

                              $usuario_finalizou = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '".$conteudo['id_usuario_finalizou']."' ", 'a.nome');

                              $finalizado_por = '<strong>Finalizado por: </strong>'.$usuario_finalizou[0]['nome'].'<br> <strong>Data: </strong>'.converteDataHora($conteudo['finalizado']);
                            }
                          
                            if($reuniao){ 
                              
                              $rem = strtotime($reuniao[0]['data']) - time();
                              $day = floor($rem / 86400);
                              $hr  = floor(($rem % 86400) / 3600);
                              $min = floor(($rem % 3600) / 60);
                              $sec = ($rem % 60);
                             
                              if($day){
                                if($day == 1){
                                  $legenda = '<span class="label label-default">Falta '.$day.' dia e '.$hr.' horas!</span>';
                                }if($day > 1){
                                  $legenda = '<span class="label label-default">Faltam '.$day.' dias e '.$hr.' horas!</span>';
                                }
                                if($day < 1){
                                  $legenda = '<span class="label label-danger">Prazo encerrado!</span>';
                                }
                              }else if($hr){
                                if($hr > 1){
                                  $legenda = '<span class="label label-warning">Faltam '.$hr.' horas!</span>';
                                }
                                if($hr == 1){
                                  $legenda = '<span class="label label-warning">Falta '.$hr.' hora!</span>';
                                }
                              }else if($min){
                                 if($min > 1){
                                   $legenda = '<span class="label" style="background-color: #D2691E">Faltam '.$min.' minutos!</span>';
                                 }
                                 if($min == 1){
                                   $legenda = '<span class="label" style="background-color: #D2691E">Falta '.$min.' minuto!</span>';
                                 }
                              }else if($sec){
                                if($sec > 1){
                                   $legenda = '<span class="label" style="background-color: #D2691E">Faltam '.$sec.' segundos!</span>';
                                 }
                                 if($sec == 1){
                                   $legenda = '<span class="label" style="background-color: #D2691E">Falta '.$sec.' segundo!</span>';
                                 }
                              }else{
                                $legenda = '<span class="label label-danger">Prazo encerrado!</span>';
                              }
                        ?>

                        <span style="color: #333; font-size: 14px;">
                          <strong>Prazo:</strong> <?=$legenda?> 
                        </span>

                          <?php
                            }  //end if prazo

                            /* if($conteudo['id_lead_tipo_item_timeline'] == 6){
                              $display_buttons = 'none';
                            }
                            else{
                              $display_buttons = 'block';
                            } */
                          ?>

                        <small class="text-muted pull-right" style="display: <?=$display_buttons?>">

                        <?php

                          if($participantes){

                            foreach($participantes as $conteudo_participantes){
                              
                              $id_usuario = $_SESSION['id_usuario'];
                              $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
                              $perfil_usuario = $dados[0]['id_perfil_sistema'];
                              
                              if($id_usuario == $conteudo_participantes['id_usuario'] || $id_usuario == $conteudo['id_usuario'] || $perfil_usuario == 2 || $perfil_usuario == 22){
                                  $btn_finalizado = true;
                              }else{
                                  $btn_finalizado = false;
                              }
                            }

                          }
                          
                          //if($btn_finalizado == true){

                        ?>
                              <button class="btn btn-xs btn-<?=$finalizado?>" conteudo-id="<?= $conteudo['id_lead_timeline']?>" idfinalizar="<?= $conteudo['id_lead_timeline']?>" onclick="finalizar(this)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="focus" title="" data-content="<?=$finalizado_por?>">
                                  <i class="fa <?=$finalizado_icon?>"></i> <span><?=$finalizado_text?></span>
                            </button>

                        <?php 
                          //} 
                          
                          if($id == 0){
                        ?>  

                           <button class="btn btn-info btn-xs" attr-id="<?= $conteudo['id_lead_timeline']?>" onclick="abrirModalVincular(this.getAttribute('attr-id'))">
                              <i class="fa fa-link"></i> Vincular
                           </button>

                        <?php } ?>

                            <button class="btn btn-xs btn-primary editar" conteudo-id="<?= $conteudo['id_lead_timeline']?>" data-toggle="modal" id="<?= $conteudo['id_lead_timeline']?>" onclick="preencheModal(this.id)">
                                      <i class="fa fa-pencil"></i> Editar
                            </button>
                            <button class="btn btn-xs btn-danger excluir" conteudo-id="<?= $conteudo['id_lead_timeline']?>">
                                <i class="fa fa-trash"></i> Excluir
                            </button>
                        </small>
                      </div>
                </li>
                <?php
                    $cont++;
                  }//end foreach
                ?>
            </ul>
        </div>

    <?php
    }

    if($limit > $qtd){
      $disabled = 'disabled';
      if($texto == ''){
        $texto = 'Não há mais registros!';
      }
      
    }else{
      $disabled = '';
      $texto = '<i class="fa fa-plus"></i> Ver mais';
    }
?>
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <button class="form-control btn btn-primary ver-mais" data-limit="<?=$limit+3?>" <?=$disabled?>>
            <?=$texto?>
          </button>
        </div>
        <div class="col-md-1">
        </div>
      </div>  
        
<script>
  //$("html, body").scrollTop($(document).height());

    $(document).ready(function(){
      window.location.href = '#<?php echo $limit-4 ?>';
    });

    $('[data-toggle="info"]').popover({ trigger: "hover", container: "body" });

    $('[data-toggle="popover"]').popover({ trigger: "hover", container: "body" });
    
</script>