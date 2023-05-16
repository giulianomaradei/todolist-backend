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

<script>
  $(document).ready(function() {
      
    function start() {

      $parametro = 'api_google';
	    $dados_api_google = getConfig($parametro);
                
      var CLIENT_ID = $dados_api_google['CLIENT_ID'];
      var API_KEY = $dados_api_google['CLIENT_SECRET'];
      var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
      var SCOPES = "https://www.googleapis.com/auth/calendar";

      // 2. Initialize the JavaScript client library.
      gapi.client.init({
          clientId: CLIENT_ID,
          discoveryDocs: DISCOVERY_DOCS,
          scope: SCOPES
      }).then(function () {

        gapi.auth.checkSessionState({session_state: null}, function(isUserNotLoggedIn){
          if(isUserNotLoggedIn) {

            $('#alert-login').css('display', 'block');
            $('#conteudo-painel').css('display', 'none');
              
          }else{
            InsertEvents();
            DeleteEvents();
            $('#alert-login').css('display', 'none');
            $('#btn-sair').css('display', 'inline');
            $('#conteudo-painel').css('display', 'block');
          }
        });

      }, function(error) {
          appendPre(JSON.stringify(error, null, 2));
      });

      function InsertEvents(){
        $('#ok').on('click', function(e){
          e.preventDefault();
          
          var descricao = $('#descricao').val();

          if(descricao == ''){
            alert('Preencha o campo descrição!');
            return false;
          }

          var tipo = $('#tipo_item_timeline').val();
          var descricao_tipo = $('#tipo_item_timeline option:selected').text();

          if(tipo == ''){
            alert('Informe o tipo!');
            return false;
          }

          var marcar_reuniao = $('#agendar-tarefa').val();
          var contato_realizado = 0;

          if($('#contato_realizado').is(':checked')){
            contato_realizado = '1';
          }
          
          var data_reuniao = $('input[name="data_reuniao"]').val();
          var hora_reuniao = $('#hora_reuniao').val();
          var usuarios = $('#usuarios').val();
          var id_lead_negocio = $('#id_lead_negocio').val();
          var nome_lead_prospeccao = $('#nome_empresa_pessoa').val();
          var convidado = $('#convidado').val();

          if(id_lead_negocio == ''){
            id_lead_negocio = 0;
          }

          if(nome_lead_prospeccao == ''){
            nome_lead_prospeccao = 'Não definido';
          }

          //marcar reuniao
          if(marcar_reuniao == 'sim'){

            if(data_reuniao == ''){
              alert('Informe a Data!');
              return false;
            }

            if(hora_reuniao == ''){
              alert('Informe a Hora!');
              return false;
            }

            if(usuarios == null){
              alert('Informe os usuários envolvidos!');
              return false;
            }

            var parts = data_reuniao.split('/');
            var date_calendar = parts[2] + '-' + parts[1] + '-' + parts[0];
            date_calendar = date_calendar+'T'+hora_reuniao+':00-03:00';

            $.get({
              url: "/api/ajax?class=LeadTimeline.php",
              dataType: "json",
              method: 'GET',
              data: {
                acao: 'busca_emails',
                parametros: {                           
                    'usuarios' : usuarios                                  
                },
                token: '<?= $request->token ?>'
              },
              beforeSend: function(){
                modalAguarde(); 
              },
              success: function (data) {
                if(data != false){

                  if (convidado != '') {
                    email_convidado = {'email': convidado};
                    data.push(email_convidado);
                  }

                  var resource = {
                    "summary": descricao_tipo+" ("+nome_lead_prospeccao+")",
                    "location": "Caçapava do Sul",
                    'description': descricao,
                    "start": {
                        "dateTime": date_calendar
                    },
                    "end": {
                        "dateTime": date_calendar
                    },
                    'params': {
                        'sendNotifications': true
                    },
                    'attendees': data,
                    'reminders': {
                      'useDefault': false,
                      'overrides': [
                        {'method': 'email', 'minutes': 24*60},
                        {'method': 'popup', 'minutes': 10}
                      ]
                    },
                    'conferenceData': {
                        'createRequest': {
                            'requestId': "sample123",
                            'conferenceSolutionKey': { 
                              'type': "hangoutsMeet" 
                            },
                        },
                    },
                  };

                  var request = gapi.client.calendar.events.insert({
                    'calendarId': 'noreply@belluno.company',
                    'conferenceDataVersion': 1,
                    'resource': resource,
                    'sendNotifications': true
                  }); 

                  request.execute(function(resp) {

                    if(resp.code == 400 || resp.code == 404){
                      $('#myModal').modal('hide');
                      $('#item_timeline_form')[0].reset();
                      $('#usuarios').val(null).trigger("change");
                      $('#data_reuniao').val("");
                      $('#hora_reuniao').val("");
                      $('#div-alert-successo').css("display", "none");
                      $('#div-alert-alterado').css("display", "none");
                      $('#div-alert-excluido').css("display", "none");
                      $('#div-alert-erro').css("display", "block");
                      return false;
                    }

                    if(resp.code == 401){
                      SairContaGoogle();
                      LogarContaGoogle();
                      return false;
                    }

                    $.get({
                      url: "/api/ajax?class=LeadTimeline.php",
                      dataType: "json",
                      method: 'GET',
                      data: {
                        acao: 'persiste_BD',
                        parametros: { 
                          'tipo' : tipo,
                          'descricao' : descricao,                            
                          'marcar_reuniao' : marcar_reuniao,                            
                          'data_reuniao' : data_reuniao,                            
                          'hora_reuniao' : hora_reuniao,
                          'usuarios' : usuarios,                            
                          'id_lead_negocio' : id_lead_negocio,                         
                          'id_evento_google': resp.id,
                          'contato_realizado': contato_realizado,
                          'convidado': convidado                   
                        },
                        token: '<?= $request->token ?>'
                      },
                      success: function (data) {
                        if(data != false){  
                        
                          $('#myModal').modal('hide');
                          $('#item_timeline')[0].reset();
                          $('#usuarios').val(null).trigger("change");
                          $('#row-data-tarefa').css("display", "none");
                          $('#row-usuarios-tarefa').css("display", "none");
                          
                          $('#div-alert-successo').css("display", "block");
                          $('#div-alert-alterado').css("display", "none");
                          $('#div-alert-excluido').css("display", "none");
                          $('#div-alert-erro').css("display", "none");
                          
                          $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                              DeleteEvents();
                          });

                          var parametros = {
                            'nome': '',
                            'usuario': '',
                            'data_de': '',
                            'data_ate': '',
                            'itens_vinculos': ''
                          };

                          busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);
                          $('#modal_aguarde').modal('hide');
                        }
                        else{
                          $('#myModal').modal('hide');
                          alert('falhou');
                        }
                      }
                    });

                  });
                }
              }
            });

          }//end marcar reuniao
          else{

            $.get({
              url: "/api/ajax?class=LeadTimeline.php",
              dataType: "json",
              method: 'GET',
              data: {
                acao: 'persiste_BD',
                parametros: { 
                  'tipo' : tipo,
                  'descricao' : descricao,                            
                  'marcar_reuniao' : marcar_reuniao,                            
                  'data_reuniao' : data_reuniao,                            
                  'hora_reuniao' : hora_reuniao,
                  'usuarios' : usuarios,                            
                  'id_lead_negocio' : id_lead_negocio,
                  'contato_realizado': contato_realizado                                      
                },
                token: '<?= $request->token ?>'
              },
              beforeSend: function(){
                modalAguarde(); 
              },
              success: function (data) {
                if(data != false){

                    $('#myModal').modal('hide');
                    
                    $('#item_timeline')[0].reset();
                    $('#usuarios').val(null).trigger("change");
                    $('#div-alert-successo').css("display", "block");
                    $('#div-alert-alterado').css("display", "none");
                    $('#div-alert-excluido').css("display", "none");
                    $('#div-alert-erro').css("display", "none");

                    $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                        DeleteEvents();
                    });

                    var parametros = {
                      'nome': '',
                      'usuario': '',
                      'data_de': '',
                      'data_ate': '',
                      'itens_vinculos': ''
                    };

                    busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);

                    $('#modal_aguarde').modal('hide');
                }
                else{
                  $('#myModal').modal('hide');
                  $('#div-alert-successo').css("display", "none");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "none");
                  $('#div-alert-erro').css("display", "block");
                  $('#modal_aguarde').modal('hide');
                }
              }
            });
          }
        });
      }

      function DeleteEvents(){
        $('.excluir').on('click', function(){

          var confirm1 = confirm('Deseja excluir este item?');

          if (confirm1) {
            var item_timeline = $(this).attr('conteudo-id');

            $.ajax({
              url: "/api/ajax?class=LeadTimelineAjax.php",
              dataType: "json",
              method: 'POST',
              data: {
                acao: 'excluir',
                parametros: {                       
                  'item_timeline' : item_timeline,                            
                },
                token: '<?= $request->token ?>'
              },
              beforeSend: function(){
                modalAguarde(); 
              },
              success: function (data) {
                if(data != 'nd'){

                  $('#div-alert-successo').css("display", "none");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "block");
                  $('#div-alert-erro').css("display", "none");
                  
                  $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                    DeleteEvents();
                  });

                  var request = gapi.client.calendar.events.delete({
                    'calendarId': 'noreply@belluno.company',
                    'eventId': data
                  });

                  request.execute(function(resp) {

                    if(resp.code == 400 || resp.code == 404){
                      $('#div-alert-successo').css("display", "none");
                      $('#div-alert-alterado').css("display", "none");
                      $('#div-alert-excluido').css("display", "none");
                      $('#div-alert-erro').css("display", "block");
                      return false;
                    }

                    if(resp.code == 401){
                      SairContaGoogle();
                      LogarContaGoogle();
                      return false;
                    }

                  }); 

                  var parametros = {
                    'nome': '',
                    'usuario': '',
                    'data_de': '',
                    'data_ate': '',
                    'itens_vinculos': ''
                  };

                  busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);

                  $('#modal_aguarde').modal('hide');
                }
                else{
                  $('#div-alert-successo').css("display", "none");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "block");
                  $('#div-alert-erro').css("display", "none");

                  var parametros = {
                    'nome': '',
                    'usuario': '',
                    'data_de': '',
                    'data_ate': '',
                    'itens_vinculos': ''
                  };

                  $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                    DeleteEvents();
                  });

                  busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);

                  $('#modal_aguarde').modal('hide');
                }
              }
            });
          }
          else{
            return false;
          }
        });
      }

      $(document).on('click', '.ver-mais', function () {
          call_busca_ajax($(this).attr('data-limit'));

          $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
            DeleteEvents();
          });
      });
    };
  
    gapi.load('client:auth2', start);
  });
</script>

<style>
    textarea[readonly]{
      background-color: #FAFAFA !important;
    }
    .select2{
        width: 100% !important;
        border-color: gray;
    }
    .label-contato {
      display: inline;
      padding-left: 1px;
      text-indent: -15px;
    }
    .input-contato {
      width: 13px;
      height: 13px;
      padding: 0;
      margin:0;
      vertical-align: bottom;
      position: relative;
      top: -1px;
    }
</style>

<div class="container-fluid text-center" id="div-alert-successo" style="display: none;">
  <div class='alert alert-success alert-dismissible' role='alert' style='text-align: center'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button><strong>Item criado com sucesso!</strong>
  </div>
</div>

<div class="container-fluid text-center" id="div-alert-alterado" style="display: none;">
  <div class='alert alert-success alert-dismissible' role='alert' style='text-align: center'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button><strong>Item alterado com sucesso!</strong>
  </div>
</div>

<div class="container-fluid text-center" id="div-alert-excluido" style="display: none;">
  <div class='alert alert-success alert-dismissible' role='alert' style='text-align: center'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button><strong>Item excluído com sucesso!</strong>
  </div>
</div>

<div class="container-fluid text-center" id="div-alert-vinculado" style="display: none;">
  <div class='alert alert-success alert-dismissible' role='alert' style='text-align: center'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button><strong>Item vinculado com sucesso!</strong>
  </div>
</div>

<div class="container-fluid text-center" id="div-alert-erro" style="display: none;">
  <div class='alert alert-danger alert-dismissible' role='alert' style='text-align: center'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button><strong>Ops! Algo de errado aconteceu!</strong>
  </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Timeline |</h3> &nbsp<a class="btn btn-primary btn-xs" id="btn-sair" onclick="SairContaGoogle();" style="display: none;"> <i class="fa fa-google"></i> | Sair</a>
                    <div class="panel-title text-right pull-right">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-perdido-ganho" style="color: white;">
                            <i class="fas fa-book-open"></i> Acompanhamento <?= $notifica ?>          
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-conversao-busca" style="color: white;">
                            <i class="fas fa-check-double"></i> Conversões RD
                        </a>
                        <a class="btn btn-xs btn-primary" href="https://www.google.com/calendar" style="color: white;" target="_blank">
                          <i class="fa fa-calendar"></i> Google Calendário
                      </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&pagina-origem=lead-timeline" style="color: white;">
                            <i class="fa fa-plus"></i> Nova Empresa/Pessoa
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form" style="color: white;">
                            <i class="fa fa-plus"></i> Novo Negócio
                        </a>
                        <a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal"  style="color: white;">
                            <i class="fa fa-plus"></i> Novo item timeline
                        </a>

                    </div>
                </div>
                <div class="panel-body">
                    <p class="alert alert-info" id="alert-login" style="text-align: center; z-index: 20; position: relative; margin-left: 10px; margin-right: 10px; margin-top: 10px; display: none;"> É necessário fazer login na sua conta Google! <br> 
                        <a class="btn btn-primary btn-xs" onclick="LogarContaGoogle();" style="margin-top: 10px;"> 
                          <i class="fa fa-google"></i> | Login
                      </a>
                    </p>
                    <div id="conteudo-painel">
                      <div class="row">
                          <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Busca:</label>
                                  <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Digite a palavra chave..." autocomplete="off" autofocus>
                                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                              </div>
                          </div>
                          <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Usuário:</label>
                                  <select class="form-control" id="usuariobusca" name="usuariobusca" onChange="call_busca_ajax();">
                                      <option value=""></option>
                                  <?php
                                      $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email');
                                      
                                      if($usuarios){
                                        foreach($usuarios as $conteudo){
                                          $id_usuario = $conteudo['id_usuario'];
                                          $nomeSelect = $conteudo['nome'];
                                          echo "<option value='$id_usuario'>$nomeSelect</option>";
                                        }
                                      }
                                  ?>    
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Data de:</label>
                                  <input class="form-control date calendar hasDatePicker" type="text" name="data_de" id="data_de" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                              </div>
                          </div>
                          <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Data até:</label>
                                  <input class="form-control date calendar hasDatePicker" type="text" name="data_ate" id="data_ate" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                              </div>
                          </div>
                          <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Itens timeline:</label>
                                  <select class="form-control" id="itens_vinculos" onChange="call_busca_ajax();" autofocus>
                                      <option value="">Todos</option>
                                      <option value="0">Sem vínculo</option>
                                      <option value="1">Com vínculo</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Tarefas:</label>
                                  <select class="form-control" id="tarefas" onChange="call_busca_ajax();" autofocus>
                                      <option value="">Todos</option>
                                      <option value="2">Com tarefas</option>
                                      <option value="3">Com tarefas em aberto</option>
                                      <option value="4">Com tarefas finalizadas</option>
                                  </select>
                              </div>
                          </div>
                      </div>
                      <hr>
                      <div class="row" >
                          <div class="col-md-12">
                              <div id="resultado_busca"></div>
                          </div>
                      </div>
                      <div id="refresh"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Novo item na timeline</h4>
        </div>
        
        <input type="hidden" id="nome_empresa_pessoa" value="">
        
        <form method="post" action="/api/ajax?class=Lead.php" id="item_timeline" class='form-modal' style="margin-bottom: 0;">
		      <input type="hidden" name="token" value="<?php echo $request->token ?>">
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <label>Negócio:</label>
                        <div class="input-group">
                            <input class="form-control input-sm ui-autocomplete-input" id="busca_negocio" type="text" name="busca_negocio" value="<?= $contrato ?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="">

                            <div class="input-group-btn">
                                <button class="btn btn-info btn-sm" id="habilita_busca_negocio" name="habilita_busca_negocio" type="button" title="Clique para selecionar o Negócio" style="height: 30px;"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <input type="hidden" name="id_lead_negocio" id="id_lead_negocio" value="">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                        <label>Tipo:</label>
                        <select class="form-control" id="tipo_item_timeline" >
                          <option></option>
                          <?php
                            $dados_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline', "WHERE exibe = 1 ORDER BY nome ASC");
                            foreach($dados_tipo_item_timeline as $conteudo_tipo){
                          ?>
                            <option value="<?=$conteudo_tipo['id_lead_tipo_item_timeline']?>"><?=$conteudo_tipo['nome']?></option>
                          <?php
                            }
                          ?>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-bottom: 7px; display: none;" id="row-contato-realizado">
                    <div class="col-md-12">
                        <div class="form-group">
                          <input class="input-contato" type="checkbox" value="1" name="contato_realizado" id="contato_realizado">
                          <label class="label-contato" >Contato realizado com sucesso!</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrição:</label>
                            <textarea class="form-control" rows="10" name="descricao" id="descricao" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Agendar tarefa?</label>
                      <select class="form-control" id="agendar-tarefa" name="agendar-tarefa">
                        <option value="nao">Não</option>
                        <option value="sim">Sim</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row" id="row-data-tarefa" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Data</label>
                        <input type="text" class="form-control date calendar hasDatePicker" name="data_reuniao" value="" id="data_reuniao" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label>Hora:</label>
                            <input type="time" class="form-control" id="hora_reuniao" value="" name="hora_reuniao" autocomplete="off">
                            <input type="hidden" id="getdatahora" value="<?=getdatahora();?>">
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row" id="row-usuarios-tarefa" style="display: none;">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Usuários:</label>
                        <select class="js-example-basic-multiple" id="usuarios" name="usuarios[]" multiple="multiple">

                        <?php
                            $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome", 'b.id_usuario, a.nome, b.email');
                            if($usuarios){
                                foreach($usuarios as $conteudo){
                                    $id_usuario = $conteudo['id_usuario'];
                                    $nomeSelect = $conteudo['nome'];
                                    echo "<option value='$id_usuario'>$nomeSelect</option>";
                                }
                            }
                        ?>
                              
                        </select>
                      </div>
                    </div>
                </div>
                <div class="row" id="row-convidado" style="display: none;">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Email do convidado:</label>
                      <input type="text" class="form-control" name="convidado" id="convidado"/>
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" name="inserir_item_timeline" value="<?= $id ?>" id="ok" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal editar -->
<div class="modal fade" id="myModal_editar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar informações</h4>
      </div>
        <div id="conteudo">
        </div>    
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal vincular -->
<div class="modal fade" id="myModal_vincular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Vincular item timeline</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  <label>Negócio:</label>
                  <div class="input-group">
                      <input class="form-control input-sm ui-autocomplete-input" id="busca_negocio2" type="text" name="busca_negocio2" value="<?= $contrato ?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="">

                      <div class="input-group-btn">
                          <button class="btn btn-info btn-sm" id="habilita_busca_negocio2" name="habilita_busca_negocio2" type="button" title="Clique para selecionar o Negócio" style="height: 30px;"><i class="fa fa-search"></i></button>
                      </div>
                  </div>
                  <input type="hidden" name="id_lead_negocio2" id="id_lead_negocio2" value="">
                </div>
            </div>
        </div>
        <input type="hidden" id="id_lead_timeline_vincular">
        <input type="hidden" id="id_negocio_vincular">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" id="ok_vincular" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script src="https://apis.google.com/js/api.js"></script>
<script>
  $(document).ready(function(){
    $('.js-example-basic-multiple').select2();
  });

  // Atribui evento e função para limpeza dos campos
  $('#busca_negocio').on('input', limpaCamposNegocio);

  // Dispara o Autocomplete da pessoa a partir do segundo caracter
  $("#busca_negocio").autocomplete({
    minLength: 2,
    source: function (request, response) {
      $.ajax({
        url: "/api/ajax?class=NegocioAutoComplete.php",
        dataType: "json",
        data: {
          acao: 'autocomplete',
          parametros: { 
              'nome' : $('#busca_negocio').val(),
          },
          token: '<?= $request->token ?>'
        },
        success: function (data) {
            response(data);
        }
      });
    },
    focus: function(event, ui){

      if(ui.item.servico !== undefined){
        ui.item.servico = 'N/D';
      }

      var valor_total = floatMoeda(ui.item.valor_contrato);

      $("#busca_negocio").val(ui.item.id_lead_negocio +" - " + ui.item.nome +" - " + ui.item.servico + " - Valor: R$ " + valor_total);
      carregarDadosNegocio(ui.item.id_lead_negocio);
      return false;
    },
    select: function (event, ui) {
      var valor_total = floatMoeda(ui.item.valor_contrato);

      if(ui.item.servico !== undefined){
        ui.item.servico = 'N/D';
      }

      $("#busca_negocio").val(ui.item.nome + " - " + ui.item.servico + " - Valor: R$ " + valor_total);
      $("#nome_empresa_pessoa").val(ui.item.nome);
      
      $('#busca_negocio').attr("readonly", true);
      return false;
    }
  })
  .autocomplete("instance")._renderItem = function(ul, item){

    ul.css({"z-index": "10000"});

    if(!item.nome){
      item.nome = '';
    }

    if(!item.servico){
      item.servico = 'Serviço: N/D';
    }

    var valor_total = floatMoeda(item.valor_contrato);
    var data_inicio = new Date(item.data_inicio);
    var d = data_inicio.getDate();
    var m =  data_inicio.getMonth();
    m += 1;  // JavaScript months are 0-11
    if(m < 10){
      m = '0'+m;
    }
    var y = data_inicio.getFullYear();

    data_inicio = d + "/" + m + "/" + y;

    return $("<li>").append("<a><strong>" + item.id_lead_negocio + " - " + item.nome + "</strong><br>" + item.servico + "<br> Valor: R$ " + valor_total +  "<br> Data de inicio: " + data_inicio + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
  };

  // Função para carregar os dados da consulta nos respectivos campos
  function carregarDadosNegocio(id){
    var busca = $('#busca_negocio').val();
    if(busca != "" && busca.length >= 2){
      $.ajax({
        url: "/api/ajax?class=NegocioAutoComplete.php",
        dataType: "json",
        data: {
          acao: 'consulta',
          parametros: {
            'id' : id,
          },
          token: '<?= $request->token ?>'
        },
        success: function(data){
          $('#id_lead_negocio').val(data[0].id_lead_negocio);
        }
      });
    }
  }

  // Função para limpar os campos caso a busca esteja vazia
  function limpaCamposNegocio(){
      var busca = $('#busca_negocio').val();
      if (busca == ""){
        $('#id_lead_negocio').val('');
      }
  }

  $(document).on('click', '#habilita_busca_negocio', function (){
    $('#id_lead_negocio').val('');
    $('#busca_negocio').val('');
    $('#busca_negocio').attr("readonly", false);
    $('#busca_negocio').focus();
  });

  // Atribui evento e função para limpeza dos campos
  $('#busca_negocio2').on('input', limpaCamposNegocio2);

  // Dispara o Autocomplete da pessoa a partir do segundo caracter
  $("#busca_negocio2").autocomplete({
    minLength: 2,
    source: function (request, response) {
      $.ajax({
        url: "/api/ajax?class=NegocioAutoComplete.php",
        dataType: "json",
        data: {
          acao: 'autocomplete',
          parametros: { 
            'nome' : $('#busca_negocio2').val(),
          },
          token: '<?= $request->token ?>'
        },
        success: function (data) {
          response(data);
        }
      });
    },
    focus: function(event, ui){

      if(ui.item.servico !== undefined){
        ui.item.servico = 'N/D';
      }

      var valor_total = floatMoeda(ui.item.valor_contrato);

      $("#busca_negocio2").val(ui.item.id_lead_negocio +" - " + ui.item.nome +" - " + ui.item.servico + " - Valor: R$ " + valor_total);
      carregarDadosNegocio2(ui.item.id_lead_negocio);
      return false;
    },
    select: function (event, ui) {
      var valor_total = floatMoeda(ui.item.valor_contrato);

      if(ui.item.servico !== undefined){
        ui.item.servico = 'N/D';
      }

      $("#busca_negocio2").val(ui.item.nome + " - " + ui.item.servico + " - Valor: R$ " + valor_total);
      
      $('#busca_negocio2').attr("readonly", true);
      return false;
    }
  })
  .autocomplete("instance")._renderItem = function(ul, item){

    ul.css({"z-index": "10000"});

    if(!item.nome){
        item.nome = '';
    }

    if(!item.servico){
        item.servico = 'Serviço: N/D';
    }

    var valor_total = floatMoeda(item.valor_contrato);
    var data_inicio = new Date(item.data_inicio);
    var d = data_inicio.getDate();
    var m =  data_inicio.getMonth();
    m += 1;  // JavaScript months are 0-11
    if(m < 10){
      m = '0'+m;
    }
    var y = data_inicio.getFullYear();

    data_inicio = d + "/" + m + "/" + y;

    return $("<li>").append("<a><strong>" + item.id_lead_negocio + " - " + item.nome + "</strong><br>" + item.servico + "<br> Valor: R$ " + valor_total +  "<br> Data de inicio: " + data_inicio + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
  };

  // Função para carregar os dados da consulta nos respectivos campos
  function carregarDadosNegocio2(id){
    var busca = $('#busca_negocio2').val();
    if(busca != "" && busca.length >= 2){
      $.ajax({
        url: "/api/ajax?class=NegocioAutoComplete.php",
        dataType: "json",
        data: {
          acao: 'consulta',
          parametros: {
            'id' : id,
          },
          token: '<?= $request->token ?>'
        },
        success: function(data){
          $('#id_negocio_vincular').val(data[0].id_lead_negocio);
        }
      });
    }
  }

  // Função para limpar os campos caso a busca esteja vazia
  function limpaCamposNegocio2(){
    var busca = $('#busca_negocio2').val();
    if (busca == ""){
      $('#id_lead_negocio2').val('');
    }
  }

  $(document).on('click', '#habilita_busca_negocio2', function (){
    $('#id_lead_negocio2').val('');
    $('#busca_negocio2').val('');
    $('#busca_negocio2').attr("readonly", false);
    $('#busca_negocio2').focus();
  });

  function call_busca_ajax(limit){
    
    var inicia_busca = 1;
    var nome = $('#nome').val();
    var usuario = $('#usuariobusca').val();
    var data_de = $('input[name="data_de"]').val();
    var data_ate = $('input[name="data_ate"]').val();
    var itens_vinculos = $('#itens_vinculos').val();
    var tarefas = $('#tarefas').val();

    if(nome.length < inicia_busca && nome.length >= 1){
      return false;
    }

    var parametros = {
      'limit': limit,
      'nome': nome,
      'usuario': usuario,
      'data_de': data_de,
      'data_ate': data_ate,
      'itens_vinculos': itens_vinculos,
      'tarefas': tarefas
    };
    
    busca_ajax('<?= $request->token ?>' , 'LeadTimelineBusca', 'resultado_busca', parametros);
  }
  
  call_busca_ajax();

  $('#tipo_item_timeline').on('change', function(){
    if($(this).val() == 2 || $(this).val() == 4){
      $('#row-contato-realizado').css('display', 'block');
    }else{
      $('#row-contato-realizado').css('display', 'none');
    }
  });

  $('#agendar-tarefa').on('change', function(){
    if($(this).val() == 'sim'){
      $('#row-data-tarefa').css('display', 'block');
      $('#row-usuarios-tarefa').css('display', 'block');
      $('#row-convidado').css('display', 'block');
      $('#data_reuniao').addClass('date calendar hasDatePicker');
    }else{
      $('#row-data-tarefa').css('display', 'none');
      $('#row-usuarios-tarefa').css('display', 'none');
      $('#row-convidado').css('display', 'none');
      $('#data_reuniao').removeClass('date calendar hasDatePicker');
    }
  }); 

  function preencheModal(id){

    $.ajax({
      url: "/api/ajax?class=LeadTimelineAjax.php",
      dataType: "html",
      method: 'POST',
      data: {
        acao: 'editar',
        parametros: {                       
          'item_timeline' : id,                          
        },
        token: '<?= $request->token ?>'
      },
      success: function(data){
        $('#conteudo').html(data);
        $('#myModal_editar').modal('show');
      }
    });
  }

  function finalizar(tag){
    var id = $(tag).attr('idfinalizar');
    var tag_i = tag.children[0];
    var tag_span = tag.children[1];

    $.ajax({
      url: "/api/ajax?class=LeadTimelineAjax.php",
      dataType: "JSON",
      method: 'POST',
      data: {
        acao: 'finalizar',
        parametros: {                       
          'item_timeline' : id                            
        },
        token: '<?= $request->token ?>'
      },
      success: function(data){
        if(data != 0){
          $(tag).removeClass('btn-default').addClass('btn-success');
          $(tag_i).removeClass('fa-square-o').addClass('fa-check-square');
          $(tag_span).text('Finalizado');
          $(tag).attr('data-content', '<strong>Finalizado por: </strong>' + data.nome_usuario + '<br><strong>Data: </strong>' + data.data);
          $(tag).popover('show');
        }
        if(data == 0){
          $(tag).popover('hide');
          $(tag).removeClass('btn-success').addClass('btn-default');
          $(tag_i).removeClass('fa-check-square').addClass('fa-square-o');
          $(tag_span).text('Finalizar');
          $(tag).attr('data-content', '');
          
        }
      }
    });
  }

  function abrirModalVincular(id){

    $('#myModal_vincular').modal('show');

    $('#ok_vincular').on('click', function(){

      var id_negocio_vincular = $('#id_negocio_vincular').val();

      if(id =='' || id_negocio_vincular ==''){
        alert('Selecione um negócio');
        return false;
      }

      $.ajax({
        url: "/api/ajax?class=LeadTimelineAjax.php",
        dataType: "JSON",
        method: 'POST',
        data: {
          acao: 'vincular',
          parametros: {                       
            'id_lead_timeline' : id,
            'id_lead_negocio': id_negocio_vincular                  
          },
          token: '<?= $request->token ?>'
        },
        success: function(data){
          if(data == 1){
            $('#div-alert-successo').css("display", "none");
            $('#div-alert-alterado').css("display", "none");
            $('#div-alert-excluido').css("display", "none");
            $('#div-alert-vinculado').css("display", "block");
            $('#div-alert-erro').css("display", "none");

            location.reload();
          }
          if(data == 0){
            $('#div-alert-successo').css("display", "none");
            $('#div-alert-alterado').css("display", "none");
            $('#div-alert-excluido').css("display", "none");
            $('#div-alert-vinculado').css("display", "none");
            $('#div-alert-erro').css("display", "block");
            $('#myModal_vincular').modal('hide');
          }
        }
      })

    });
  }

  $("#busca_negocio").css("z-index", "3500");

  function LogarContaGoogle(){
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    });
    auth2.disconnect();

    const authInstance = window.gapi.auth2.getAuthInstance();

    authInstance.grantOfflineAccess().then((res) => {
      //this.data.refreshToken = res.code;
      modalAguarde();
      location.reload();
    });
  }

  function SairContaGoogle(){
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    });
    auth2.disconnect();

    var myWindow = window.open('https://mail.google.com/mail/u/0/?logout&hl=en/','Google', "status=no, height=500, width=500, resizable=yes, left=40%, top=0%, screenX=2000, screenY=0,toolbar=no, menubar=no, scrollbars=no, location=no, directories=no");

    //var myWindow = window.open("https://mail.google.com/mail/u/0/?logout&hl=en/", "Google", "width=500, position=fixed, height=500, top=50%, left=50%");

    var close = setInterval(function(){
      myWindow.close();
      location.reload();
    }, 4000);
  }
</script>