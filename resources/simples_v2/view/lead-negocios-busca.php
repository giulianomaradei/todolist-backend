<?php
    require_once(__DIR__."/../class/System.php");

    $data = new DateTime(getDataHora('data'));
    $data_agora = $data->format('Y-m-d');

    $negocios_perdidos = DBRead('', 'tb_lead_negocio_perdido', "WHERE data_lembrete <= '$data_agora' ");

    $cont_visualizacao = 0;

    if ($negocios_perdidos) {
        foreach ($negocios_perdidos as $conteudo_negocios) {
      
          $verifica_visualizacao = DBRead('', 'tb_lead_negocio_perdido_visualizado', "WHERE  id_lead_negocio_perdido = '".$conteudo_negocios['id_lead_negocio_perdido']."' AND id_usuario = $id_usuario");
      
          if (!$verifica_visualizacao) {
              $cont_visualizacao++;
          }
        }
    }
      
    if ($cont_visualizacao != 0) {
        $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i> ';
    
    } else {
        $notifica = '';
    }
?>

<style>
    .table>tbody>tr>td {
        border-top: 0px solid red !important;
    }
    .table>tbody>tr>th{
        border-top: 1px solid #e8e8e8 !important;
        border-bottom: 1px solid #e8e8e8  !important;
    }
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    ::-webkit-scrollbar-thumb {
        background: #888; 
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
    .popover{
        min-width: 380px !important;
        /* background: #e6e6e6; */
    }
    .span-popover{
        margin-bottom: 200px !important;
    }
    .col-md-2{
        padding-left: 3px;
        padding-right: 3px;
    }
    .col-md-1{
        padding-left: 3px;
        padding-right: 3px;
    }
    @media only screen and (max-width: 1400px) {
        .resultado-busca {
            height: 365px !important;
        }
    }
    @media only screen and (min-width: 1401px) {
        .resultado-busca {
            height: 650px !important;
        }
    }
</style>

<div class="container-fluid">
  <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
              <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Negócios:</h3>
              <div class="panel-title text-right pull-right">
                <a href="">
                    <div class="panel-title text-right pull-right" id="panel_buttons">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-perdido-ganho" style="color: white;">
                            <i class="fas fa-book-open"></i> Acompanhamento <?= $notifica ?>
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-conversao-busca" style="color: white;">
                            <i class="fas fa-check-double"></i> Conversões RD
                        </a>
                        <a class="btn btn-xs btn-primary" href="https://www.google.com/calendar" style="color: white;" target="_blank">
                            <i class="fa fa-calendar"></i> Google Calendário
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline" style="color: white;">
                            <i class="fa fa-bars"></i> Timeline
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&pagina-origem=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-plus"></i> Nova Empresa/Pessoa
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form" style="color: white;">
                            <i class="fa fa-plus"></i> Novo Negócio
                        </a>
                    </div>
                </a>
              </div>
          </div>
          <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row" style="margin-left: 1px; margin-right: 1px;">
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Status:</label>
                                  <select class="form-control" name="status" id="status" onchange="call_busca_ajax();">
                                    <option></option>
                                    <?php
                                        $dados_status = DBRead('', 'tb_lead_status', "WHERE exibe = 1 ORDER BY descricao ASC");
                                        foreach($dados_status as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_lead_status']?>"><?=$conteudo['descricao']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group has-feedback">
                                <label class="control-label">Estado:</label>
                                <select class="form-control" name="estado" id="estado" onchange="call_busca_ajax();">
                                    <option></option>
                                <?php
                                    $estados = DBRead('', 'tb_estado', "ORDER BY nome ASC");
                                    if($estados){
                                        foreach($estados as $conteudo){
                                            $id_estado = $conteudo['id_estado'];
                                            $nomeEstado = $conteudo['sigla'];
                                            echo "<option value='$id_estado'>$nomeEstado</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Responsavel:</label>
                                <select class="form-control" name="responsavel" id="responsavel" onchange="call_busca_ajax();">
                                    <option></option>
                                <?php
                                    $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email');
                                    if($usuarios){
                                        foreach($usuarios as $conteudo){
                                            $id_usuario = $conteudo['id_usuario'];
                                            $nomeSelect = $conteudo['nome'];
                                            $selected = $id_responsavel == $id_usuario ? "selected" : "";
                                            echo "<option value='$id_usuario'".$selected.">$nomeSelect</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group has-feedback">
                                <label class="control-label">Serviço:</label>
                                <select class="form-control" id="servico" name="servico" onchange="call_busca_ajax();">
                                    <?php
                                        $dados_plano = DBRead('', 'tb_plano', "GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
                                        if ($dados_plano) {
                                            echo "<option value=''></option>";
                                            foreach ($dados_plano as $conteudo) {
                                                $servico_select = getNomeServico($conteudo['cod_servico']);
                                                echo "<option value='".$conteudo['cod_servico']."'>$servico_select</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data de:</label>
                                <input class="form-control date calendar hasDatePicker" type="text" name="data_de" id="data_inicio" onChange="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data até:</label>
                                <input class="form-control date calendar hasDatePicker" type="text" name="data_ate" id="data_conclusao" onChange="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca" class="resultado-busca" style="overflow-x: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <i class="fa fa-circle" aria-hidden="true" style="color: #04B431"></i> Sem tarefa agendada
                    <i class="fa fa-circle" aria-hidden="true" style="color: #B40404; margin-left: 7px;"></i> Com tarefa atrasada
                    <i class="fa fa-circle" aria-hidden="true" style="color: #DBA901; margin-left: 7px;"></i> Com tarefa agendada
                </div>
            </div>
          </div>
        </div>
    </div>         
  </div>
</div>

<script>

    function call_busca_ajax(pagina){
        
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var id_lead_status = $('#status').val();
        var responsavel = $('#responsavel').val();
        var estado = $('#estado').val();
        var servico = $('#servico').val();
        var data_de = $("input[name='data_de']").val();
        var data_ate= $("input[name='data_ate']").val();
        
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }

        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'nome': nome,
            'id_lead_status': id_lead_status,
            'responsavel': responsavel,
            'servico': servico,
            'data_de': data_de,
            'data_ate': data_ate,
            'estado': estado
        };

        busca_ajax('<?= $request->token ?>' , 'LeadBuscaNegociacoes', 'resultado_busca', parametros);
    }

    call_busca_ajax();
</script>
