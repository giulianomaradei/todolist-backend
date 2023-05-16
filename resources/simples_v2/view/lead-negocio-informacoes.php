<?php
require_once(__DIR__."/../class/System.php");
$id = (int) $_GET['lead'];

//$perfil_usuario != 8

$id_item_timeline = (int) $_GET['item-timeline'];

if ($id_item_timeline) {
?>
  <script>
    window.location.href = '#' + '<?php echo $id_item_timeline ?>';
  </script>
<?php
}

$dados_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = $id", 'id_pessoa, id_lead_status');
$itens_timeline = DBRead('', 'tb_lead_timeline', "WHERE id_lead_negocio = $id", 'id_lead_timeline');

$itens_nao_lidos = array();

if ($itens_timeline) {
  foreach ($itens_timeline as $conteudo) {
    $count = DBRead('', 'tb_lead_timeline_visualizado', "WHERE id_usuario = '$id_usuario ' AND id_lead_timeline = '" . $conteudo['id_lead_timeline'] . "'", 'count(*) as count');

    if ($count[0]['count'] == 0) {
      array_push($itens_nao_lidos, $conteudo['id_lead_timeline']);
    }
  }
}

if ($itens_timeline) {
  foreach ($itens_timeline as $conteudo) {
    $visualizado = DBRead('', 'tb_lead_timeline_visualizado', "WHERE id_lead_timeline = '" . $conteudo['id_lead_timeline'] . "' AND id_usuario = '" . $_SESSION['id_usuario'] . "'", 'COUNT(*) as cont');

    if ($visualizado[0]['cont'] == 0) {

      $dados_visualizado = array(
        'data_visualizado' => getDataHora(),
        'id_usuario' => $_SESSION['id_usuario'],
        'id_lead_timeline' => $conteudo['id_lead_timeline']
      );

      $id_topico_visualizado = DBCreate('', 'tb_lead_timeline_visualizado', $dados_visualizado, true);
    }
  }
}

$id_pessoa = $dados_negocio[0]['id_pessoa'];

$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa");
$dados_prospeccao = DBRead('', 'tb_pessoa_prospeccao a', "LEFT JOIN tb_lead_segmento b ON a.segmento = b.id_lead_segmento LEFT JOIN tb_tipo_central_telefonica c ON a.central_telefonica = c.id_tipo_central_telefonica LEFT JOIN tb_tipo_sistema_gestao d ON a.sistema_de_gestao = d.id_tipo_sistema_gestao WHERE id_pessoa = $id_pessoa", 'a.id_pessoa_prospeccao, a.quantidade_clientes, a.estrutura_tres_niveis, a.horario_mais_ligacoes, a.atendimento_fideliza_cliente, a.terceirizacao_atendimento, a.qualificacao_cliente, a.concorrencia, a.reclamacoes_redes_sociais, a.id_lead_origem, a.qtde_funcionarios_nivel_1, a.qtde_funcionarios_nivel_2, a.qtde_funcionarios_nivel_3, b.nome as segmento, c.descricao as central_telefonica, d.nome as sistema_de_gestao, a.pessoa_contato, a.exp_outra_assessoria_redes, a.exp_outra_qual, a.pq_nao_tem_mais');

$dados_vinculos = DBRead('', 'tb_vinculo_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa WHERE a.id_pessoa_pai = '$id_pessoa' AND b.status != '2' ORDER BY b.nome ASC", 'b.id_pessoa, b.nome, b.fone1, b.email1');

$status = $dados_negocio[0]['id_lead_status'];

$origem = DBRead('', 'tb_lead_origem', "WHERE id_lead_origem = '" . $dados_prospeccao[0]['id_lead_origem'] . "'");

$dados_tipo_equipamento = DBRead('', 'tb_pessoa_prospeccao_tipo_equipamento a', "INNER JOIN tb_tipo_equipamento b ON a.id_tipo_equipamento = b.id_tipo_equipamento WHERE id_pessoa_prospeccao = '" . $dados_prospeccao[0]['id_pessoa_prospeccao'] . "' ", 'descricao');

$equipamento = '';
if ($dados_tipo_equipamento) {
  foreach ($dados_tipo_equipamento as $conteudo) {
    $equipamento = $equipamento . $conteudo['descricao'] . '; ';
  }
  $equipamento = substr($equipamento, 0, -2);
}

$dados_tipo_equipamento_redes = DBRead('', 'tb_pessoa_prospeccao_tipo_equipamento_redes a', "INNER JOIN tb_tipo_equipamento_redes b ON a.id_tipo_equipamento_redes = b.id_tipo_equipamento_redes WHERE id_pessoa_prospeccao = '" . $dados_prospeccao[0]['id_pessoa_prospeccao'] . "' ", 'b.id_tipo_equipamento_redes,descricao, outro');

$equipamento_redes = '';
$outro_equipamento = '';
if ($dados_tipo_equipamento_redes) {
  foreach ($dados_tipo_equipamento_redes as $conteudo) {
    if ($conteudo['id_tipo_equipamento_redes'] == 5) {
      $equipamento_redes = $equipamento_redes . ' ' . $conteudo['descricao'] . ';';
      $outro_equipamento = $outro_equipamento . ' ' . $conteudo['outro'] . ';';
    } else {
      $equipamento_redes = $equipamento_redes . ' ' . $conteudo['descricao'] . ';';
    }
  }
  $equipamento_redes = substr($equipamento_redes, 0, -1);
  $outro_equipamento = substr($outro_equipamento, 0, -1);
}

$segmento = $dados_prospeccao[0]['segmento'];
$quantidade_clientes = $dados_prospeccao[0]['quantidade_clientes'];
$estrutura_tres_niveis = $dados_prospeccao[0]['estrutura_tres_niveis'];

if ($estrutura_tres_niveis == 1) {
  $estrutura_tres_niveis = 'Sim';
}
if ($estrutura_tres_niveis == 2) {
  $estrutura_tres_niveis = 'Não';
}

$terceirizacao_atendimento = $dados_prospeccao[0]['terceirizacao_atendimento'];

if ($terceirizacao_atendimento == 1) {
  $terceirizacao_atendimento = 'Redução de custo';
}
if ($terceirizacao_atendimento == 2) {
  $terceirizacao_atendimento = 'Melhorar Qualidade';
}
if ($terceirizacao_atendimento == 3) {
  $terceirizacao_atendimento = 'Outros';
}
if ($terceirizacao_atendimento == 4) {
  $terceirizacao_atendimento = 'Falta de pessoas qualificadas para fazer internamente';
}
if ($terceirizacao_atendimento == 5) {
  $terceirizacao_atendimento = 'Falta de tempo para gerir equipe interna';
}
if ($terceirizacao_atendimento == 6) {
  $terceirizacao_atendimento = 'É mais barato terceirizar';
}

$concorrencia = $dados_prospeccao[0]['concorrencia'];

if ($concorrencia == '') {
  $concorrencia = 'N/D';
}
if ($concorrencia == '1') {
  $concorrencia = 'Igual';
}
if ($concorrencia == '2') {
  $concorrencia = 'Melhor';
}
if ($concorrencia == '3') {
  $concorrencia = 'Não sei';
}
if ($concorrencia == '4') {
  $concorrencia = 'Pior';
}

$qtde_funcionarios_nivel_1 = $dados_prospeccao[0]['qtde_funcionarios_nivel_1'];
$qtde_funcionarios_nivel_2 = $dados_prospeccao[0]['qtde_funcionarios_nivel_2'];
$qtde_funcionarios_nivel_3 = $dados_prospeccao[0]['qtde_funcionarios_nivel_3'];
$central_telefonica = $dados_prospeccao[0]['central_telefonica'];
$sistema_de_gestao = $dados_prospeccao[0]['sistema_de_gestao'];
$acesso_internet = $dados_prospeccao[0]['acesso_internet'];
$horario_mais_ligacoes = $dados_prospeccao[0]['horario_mais_ligacoes'];
$atendimento_fideliza_cliente = $dados_prospeccao[0]['atendimento_fideliza_cliente'];
$qualificacao_cliente = $dados_prospeccao[0]['qualificacao_cliente'];
$reclamacoes_redes_sociais = $dados_prospeccao[0]['reclamacoes_redes_sociais'];
$pessoa_contato = $dados_prospeccao[0]['pessoa_contato'];
$exp_outra_assessoria_redes = $dados_prospeccao[0]['exp_outra_assessoria_redes'];

if ($exp_outra_assessoria_redes == 1) {
  $exp_outra_assessoria_redes = 'Sim';
} else if ($exp_outra_assessoria_redes == 2) {
  $exp_outra_assessoria_redes = 'Não';
} else {
  $exp_outra_assessoria_redes = 'N/D';
}

$exp_outra_qual = $dados_prospeccao[0]['exp_outra_qual'];
$pq_nao_tem_mais = $dados_prospeccao[0]['pq_nao_tem_mais'];

$data = new DateTime(getDataHora('data'));
$data_agora = $data->format('Y-m-d');

$negocios_perdidos = DBRead('', 'tb_lead_negocio_perdido', "WHERE id_lead_negocio != $id AND data_lembrete <= '$data_agora' ");

$cont_visualizacao = 0;

if ($negocios_perdidos) {
  foreach ($negocios_perdidos as $conteudo_negocios) {

    $verifica_visualizacao = DBRead('', 'tb_lead_negocio_perdido_visualizado', "WHERE id_lead_negocio_perdido = '".$conteudo_negocios['id_lead_negocio_perdido']."' AND id_usuario = $id_usuario");

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

$visualizar_negocio_perdido = DBRead('', 'tb_lead_negocio_perdido', "WHERE id_lead_negocio = $id AND data_lembrete IS NOT NULL ORDER BY id_lead_negocio_perdido");

if ($visualizar_negocio_perdido) {

  foreach ($visualizar_negocio_perdido as $conteudo) {
    $obs = $conteudo['observacao'];
    $data_lembrete = $conteudo['data_lembrete'];
    $motivo = $conteudo['id_lead_motivo_perda'];
    $id_lead_negocio_perdido = $conteudo['id_lead_negocio_perdido'];
    $id_usuario = $_SESSION['id_usuario'];

    if ($conteudo['data_lembrete'] <= $data_agora) {

        $verifica_visualizacao = DBRead('', 'tb_lead_negocio_perdido_visualizado', "WHERE  id_lead_negocio_perdido = $id_lead_negocio_perdido AND id_usuario = $id_usuario");

        if (!$verifica_visualizacao) {

          $data_hora = getDataHora();

          $dados = array(
              'id_lead_negocio_perdido' => $id_lead_negocio_perdido,
              'id_usuario' => $id_usuario,
              'data_visualizacao' => $data_hora
          );
      
          $insertID = DBCreate('', 'tb_lead_negocio_perdido_visualizado', $dados, true);
          registraLog('Inserção de lead negocio perdido visualizado.', 'i', 'tb_lead_negocio_perdido_visualizado', $insertID, "id_lead_negocio_perdido: $id_lead_negocio_perdido | id_usuario: $id_usuario | data_visualizacao: $data_visualizacao");
        } 
    }
  }
}

?>

<script src="https://apis.google.com/js/api.js"></script>
<script src="https://apis.google.com/js/platform.js"></script>
<script async>
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
      }).then(function() {

        gapi.auth.checkSessionState({
          session_state: null
        }, function(isUserNotLoggedIn) {
          if (isUserNotLoggedIn) {

            $('#alert-login').css('display', 'block');
            $('#conteudo-painel').css('display', 'none');

          } else {
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

      function InsertEvents() {

        $('#ok').on('click', function() {
          modalAguarde();
          var descricao = $('#descricao').val();

          if (descricao == '') {
            alert('Preencha o campo descrição!');
            return false;
          }

          var tipo = $('#tipo_item_timeline').val();
          var descricao_tipo = $('#tipo_item_timeline option:selected').text();

          if (tipo == '') {
            alert('Informe o tipo!');
            return false;
          }

          var marcar_reuniao = $('#agendar-tarefa').val();
          var contato_realizado = 0;

          if ($('#contato_realizado').is(':checked')) {
            contato_realizado = '1';
          }

          var data_reuniao = $('input[name="data_reuniao"]').val();
          var hora_reuniao = $('#hora_reuniao').val();
          var usuarios = $('#usuarios').val();
          var id_lead_negocio = <?php echo $id ?>;
          var nome_lead_prospeccao = $('#nome-lead-prospeccao').text();
          var convidado = $('#convidado').val();

          if (marcar_reuniao == 'sim') {

            if (data_reuniao == '') {
              alert('Informe a Data!');
              return false;
            }

            if (hora_reuniao == '') {
              alert('Informe a Hora!');
              return false;
            }

            if (usuarios == null) {
              alert('Informe os usuários envolvidos!');
              return false;
            }

            var parts = data_reuniao.split('/');
            var date_calendar = parts[2] + '-' + parts[1] + '-' + parts[0];
            date_calendar = date_calendar + 'T' + hora_reuniao + ':00-03:00';

            $.get({
              url: "/api/ajax?class=LeadTimeline.php",
              dataType: "json",
              method: 'GET',
              data: {
                acao: 'busca_emails',
                parametros: {
                  'usuarios': usuarios
                },
                token: '<?= $request->token ?>'
              },
              beforeSend: function() {
                modalAguarde();
              },
              success: function(data) {

                if (data != false) {

                  if (convidado != '') {
                    email_convidado = {'email': convidado};
                    data.push(email_convidado);
                  }

                  var resource = {
                    "summary": descricao_tipo + " (" + nome_lead_prospeccao + ")",
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
                    "organizer": {
                      "email": "noreply@belluno.company",
                    },
                    'attendees': data,
                    'reminders': {
                      'useDefault': false,
                      'overrides': [{
                          'method': 'email',
                          'minutes': 24 * 60
                        },
                        {
                          'method': 'popup',
                          'minutes': 10
                        }
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

                    if (resp.code == 400 || resp.code == 404) {
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

                    if (resp.code == 401) {
                      SairContaGoogle();
                      LogarContaGoogle();
                      return false;
                    }

                    $.get({
                      url: "class/LeadTimeline.php",
                      dataType: "json",
                      method: 'GET',
                      data: {
                        acao: 'persiste_BD',
                        parametros: {
                          'tipo': tipo,
                          'descricao': descricao,
                          'marcar_reuniao': marcar_reuniao,
                          'data_reuniao': data_reuniao,
                          'hora_reuniao': hora_reuniao,
                          'usuarios': usuarios,
                          'id_lead_negocio': id_lead_negocio,
                          'id_evento_google': resp.id,
                          'contato_realizado': contato_realizado,
                          'convidado': convidado
                        }
                      },
                      success: function(data) {
                        if (data != false) {

                          $('#myModal').modal('hide');
                          $('#item_timeline_form')[0].reset();
                          $('#usuarios').val(null).trigger("change");
                          $('#data_reuniao').val("");
                          $('#hora_reuniao').val("");
                          $('#row-data-tarefa').css("display", "none");
                          $('#row-usuarios-tarefa').css("display", "none");
                          $('#div-alert-successo').css("display", "block");
                          $('#div-alert-alterado').css("display", "none");
                          $('#div-alert-excluido').css("display", "none");
                          $('#div-alert-erro').css("display", "none");
                          $("#row-negocio").load(location.href + " #row-negocio>");
                          $("#row-timeline").load(location.href + " #row-timeline>", function(responseText, textStatus, XMLHttpRequest) {
                            DeleteEvents();
                          });
                          $('#modal_aguarde').modal('hide');
                        } else {
                          $('#div-alert-successo').css("display", "none");
                          $('#div-alert-alterado').css("display", "none");
                          $('#div-alert-excluido').css("display", "none");
                          $('#div-alert-erro').css("display", "block");
                        }
                      }
                    });

                  });
                }
              }
            });
          } //end marcar reuniao
          else {
            $.get({
              url: "/api/ajax?class=LeadTimeline.php",
              dataType: "json",
              method: 'GET',
              data: {
                acao: 'persiste_BD',
                parametros: {
                  'tipo': tipo,
                  'descricao': descricao,
                  'marcar_reuniao': marcar_reuniao,
                  'data_reuniao': data_reuniao,
                  'hora_reuniao': hora_reuniao,
                  'usuarios': usuarios,
                  'id_lead_negocio': id_lead_negocio,
                  'contato_realizado': contato_realizado,
                  'convidado': convidado
                },
                token: '<?= $request->token ?>'
              },
              beforeSend: function() {
                modalAguarde();
              },
              success: function(data) {
                if (data != false) {
                  $('#myModal').modal('hide');
                  $('#item_timeline_form')[0].reset();
                  $('#usuarios').val(null).trigger("change");
                  $('#div-alert-successo').css("display", "block");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "none");
                  $('#div-alert-erro').css("display", "none");
                  $("#row-negocio").load(location.href + " #row-negocio>");
                  $("#row-timeline").load(location.href + " #row-timeline>", function(responseText, textStatus, XMLHttpRequest) {

                    DeleteEvents();
                  });
                  $('#modal_aguarde').modal('hide');
                } else {
                  $('#myModal').modal('hide');
                  $('#div-alert-successo').css("display", "none");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "none");
                  $('#div-alert-erro').css("display", "block");
                  $('#modal_aguarde').modal('hide');
                }
              }
            });
          } //end nao marcar reuniao
        });
      }

      function DeleteEvents() {
        $('.excluir').on('click', function() {

          var confirm1 = confirm('Deseja excluir este item?');

          if (confirm1) {
            var item_timeline = $(this).attr('conteudo-id');

            $.ajax({
              url: "class/LeadTimelineAjax.php",
              dataType: "json",
              method: 'POST',
              data: {
                acao: 'excluir',
                parametros: {
                  'item_timeline': item_timeline,
                }
              },
              beforeSend: function() {
                modalAguarde();
              },
              success: function(data) {
                console.log(data);
                if (data != 'nd') {

                  $('#div-alert-successo').css("display", "none");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "block");
                  $('#div-alert-erro').css("display", "none");

                  $("#row-timeline").load(location.href + " #row-timeline>", function(responseText, textStatus, XMLHttpRequest) {
                    DeleteEvents();
                  });

                  var request = gapi.client.calendar.events.delete({
                    'calendarId': 'noreply@belluno.company',
                    'eventId': data
                  });

                  request.execute(function(resp) {

                    if (resp.code == 400 || resp.code == 404) {
                      $('#div-alert-successo').css("display", "none");
                      $('#div-alert-alterado').css("display", "none");
                      $('#div-alert-excluido').css("display", "none");
                      $('#div-alert-erro').css("display", "block");
                      return false;
                    }

                    if (resp.code == 401) {
                      SairContaGoogle();
                      LogarContaGoogle();
                      return false;
                    }

                  });
                  $('#modal_aguarde').modal('hide');
                } else {
                  $('#div-alert-successo').css("display", "none");
                  $('#div-alert-alterado').css("display", "none");
                  $('#div-alert-excluido').css("display", "block");
                  $('#div-alert-erro').css("display", "none");

                  $("#row-negocio").load(location.href + " #row-negocio>");
                  $("#row-timeline").load(location.href + " #row-timeline>", function(responseText, textStatus, XMLHttpRequest) {
                    DeleteEvents();
                  });
                  $('#modal_aguarde').modal('hide');
                }
              }
            });

          } else {
            return false;
          }
        });
      }
    };

    gapi.load('client:auth2', start);
  });
</script>

<style>
  .select2 {
    width: 100% !important;
    border-color: gray;
  }

  textarea[readonly] {
    background-color: #FAFAFA !important;
  }

  .hr-timeline {
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

  .timeline>li {
    margin-bottom: 20px;
    position: relative;
  }

  .timeline>li:before,
  .timeline>li:after {
    content: " ";
    display: table;
  }

  .timeline>li:after {
    clear: both;
  }

  .timeline>li:before,
  .timeline>li:after {
    content: " ";
    display: table;
  }

  .timeline>li:after {
    clear: both;
  }

  .timeline>li>.timeline-panel {
    width: 87%;
    float: left;
    border: 1px solid #d4d4d4;
    border-radius: 2px;
    padding: 20px;
    position: relative;
    -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
  }

  .timeline>li>.timeline-panel:before {
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

  .timeline>li>.timeline-panel:after {
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

  .timeline>li>.timeline-badge {
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

  .timeline>li.timeline-inverted>.timeline-panel {
    float: left;
    margin-left: 100px;
  }

  .timeline>li.timeline-inverted>.timeline-panel:before {
    border-left-width: 0;
    border-right-width: 15px;
    left: -15px;
    right: auto;
  }

  .timeline>li.timeline-inverted>.timeline-panel:after {
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

  .timeline-body>p,
  .timeline-body>ul {
    margin-bottom: 0;
  }

  .timeline-body>p+p {
    margin-top: 5px;
  }

  .tab-link {
    color: #333333;
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
    margin: 0;
    vertical-align: bottom;
    position: relative;
    top: -1px;
  }

  .popover {
    max-width: 100%;
  }

  @media only screen and (max-width: 1400px) {
    .timeline>li.timeline-inverted>.timeline-panel {
      margin-left: 86px !important;
    }

    .timeline>li>.timeline-panel {
      width: 84%;
    }
  }

  @media only screen and (min-width: 1401px) {
    .timeline>li.timeline-inverted>.timeline-panel {
      margin-left: 98px !important;
    }

    .timeline>li>.timeline-panel {
      width: 87%;
    }
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

<div class="container-fluid text-center" id="div-alert-erro" style="display: none;">
  <div class='alert alert-danger alert-dismissible' role='alert' style='text-align: center'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button><strong>Ops! Algo de errado aconteceu!</strong>
  </div>
</div>

<link href='inc/ckeditor/css/select2.min.css' />
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Negócio: #<?= $id ?> | &nbsp </h3>
          <a class="btn btn-primary btn-xs" id="btn-sair" onclick="SairContaGoogle();" style="display: none;"> <i class="fa fa-google"></i> | Sair</a>
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
            <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline" style="color: white;">
              <i class="fa fa-bars"></i> Timeline
            </a>
            <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&pagina-origem=lead-negocios-busca" style="color: white;">
              <i class="fa fa-plus"></i> Nova Empresa/Pessoa
            </a>
            <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form" style="color: white;">
              <i class="fa fa-plus"></i> Novo Negócio
            </a>
            <?php
            if ($dados_negocio[0]['id_usuario_responsavel'] == $_SESSION['id_usuario']) {
              echo "<a class='btn btn-xs btn-danger' href=\"class/LeadNegocio.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir negócio?')) { return false; } else { modalAguarde(); }\" style='color: white;'><i class='fa fa-trash' style=''></i> Excluir</a>";
            }
            ?>
          </div>
        </div>
        <div class="panel-body">

          <p class="alert alert-info" id="alert-login" style="text-align: center; z-index: 20; position: relative; margin-left: 10px; margin-right: 10px; margin-top: 10px; display: none;"> É necessário fazer login na sua conta Google! <br>
            <a class="btn btn-primary btn-xs" onclick="LogarContaGoogle();" style="margin-top: 10px;">
              <i class="fa fa-google"></i> | Login
            </a>
          </p>

          <div id="conteudo-painel">

            <?php if ($visualizar_negocio_perdido) { ?>
              <div class="alert alert-warning text-center" role="alert">
                <span> Este negócio possui um lembrete para 
                  <strong><?= converteData($data_lembrete) ?> </strong>
                  <br>
                  <strong>OBS:</strong> <?= $obs ?>
                  <br>
                  <button type="button" class="btn btn-xs btn-primary" id="alterar_lembrete">
                    <i class="fa fa-bell"></i> Alterar lembrete
                  </button>
                  <button type="button" class="btn btn-xs btn-danger" id="<?=$id_lead_negocio_perdido?>"  onclick="if (!confirm('Excluir?')) { return false; } else { excluirLembrete(this.id) }">
                    <i class="fa fa-trash" ></i> Excluir lembrete
                  </button>
              </span>
              </div>
            <?php } ?>

            <div class="col-md-12">
              <div class="row">
                <div class="panel panel-default">
                  <div class="panel-heading clearfix">
                    <h5 class="panel-title text-left pull-left" style="margin-top: 2px;">Status</h5>
                    <div class="panel-title text-right pull-right"></div>
                  </div>

                  <?php
                    if ($status == 15) {
                      $sel_status[$status] = 'btn-danger';

                    } else {
                      $sel_status[$status] = 'btn-success';
                    }
                    
                  ?>

                  <div class="panel-body text-center">
                    <div class="btn-group" role="group" aria-label="...">

                      <?php
                      $dados_status = DBRead('', 'tb_lead_status', "WHERE exibe = 1 ORDER BY posicao ASC");

                      foreach ($dados_status as $conteudo_status) {
                      ?>

                        <button type="button" class="btn btn-sm <?= $sel_status[$conteudo_status['id_lead_status']] ?> verifica" data-id="<?= $conteudo_status['id_lead_status'] ?>"><?= $conteudo_status['descricao'] ?></button>

                      <?php
                      }
                      ?>
                    </div>
                    <br>
                  </div>
                </div><!-- end row -->
              </div><!-- end row -->
            </div>

            <div class="col-md-5" style="padding: 0px 25px 0px 20px">
              <div class="row" id="row-informacoes-contato">
                <div class="panel panel-default">
                  <div class="panel-heading clearfix">
                    <h5 class="panel-title text-left pull-left" style="margin-top: 2px;">Informações</h5>
                    <div class="panel-title text-right pull-right">
                      <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=<?= $id_pessoa ?>&pagina-origem=negocio-informacoes&id-negocio=<?= $id ?>" style="color: white;">
                        <i class="fa fa-pencil"></i> Editar Empresa/Pessoa
                      </a>
                      <a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form&alterar=<?= $id ?>&pagina-origem=negocio-informacoes">
                        <button class="btn btn-xs btn-primary">
                          <i class="fa fa-pencil"></i> Editar Negócio
                        </button>
                      </a>
                    </div>
                  </div>
                  <div class="panel-body" style="height: 550px; overflow-y: scroll;">

                    <!-- nav tabs -->
                    <ul class="nav nav-tabs">
                      <li class="aba3 active">
                        <a class="tab-link" data-toggle="tab" href="#tab3"><i class="fa fa-usd"></i> Negócio</a>
                      </li>
                      <li class="aba1">
                        <a class="tab-link" data-toggle="tab" href="#tab1"><i class="fa fa-info-circle"></i> Empresa/Pessoa</a>
                      </li>
                      <li class="aba2">
                        <a class="tab-link" data-toggle="tab" href="#tab2"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Dados prospecção</a>
                      </li>
                      <li class="aba4">
                        <a class="tab-link" data-toggle="tab" href="#tab4"><i class="fa fa-link" aria-hidden="true"></i> Vínculos</a>
                      </li>
                    </ul>
                    <!-- end nav tabs -->

                    <br>

                    <div class="tab-content">

                      <!-- tab 3 Informações da Empresa/Pessoa  -->
                      <div id="tab3" class="tab-pane fade in active">
                        <div class="row" id="row-negocio">
                          <div class="col-md-12">
                            <?php
                            $dados_negocio = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_usuario b ON a.id_usuario_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa LEFT JOIN tb_plano d ON a.id_plano = d.id_plano WHERE id_lead_negocio = $id", 'a.id_lead_negocio, a.id_pessoa, a.id_lead_status, a.descricao, a.valor_contrato, a.valor_adesao, a.data_inicio, a.data_conclusao, a.andamento, a.tipo_negocio, a.id_plano, a.id_usuario_responsavel, a.id_pessoa_fechado_com, a.obs_ganhou, c.nome AS responsavel, d.cod_servico, d.nome AS nome_plano, a.valor_reducao, a.valor_aumento');

                            if ($dados_negocio[0]['id_pessoa_fechado_com'] != '') {
                              $fechado_com = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '" . $dados_negocio[0]['id_pessoa_fechado_com'] . "' ", 'nome');
                              $fechado_com = $fechado_com[0]['nome'];
                            } else {
                              $fechado_com = 'N/D';
                            }
                            ?>

                            <table class="table table-striped">
                              <tbody><br>
                                <tr>
                                  <td class="td-table"><strong>Empresa/Pessoa:</strong></td>
                                  <td id="nome-lead-prospeccao"><?= $dados[0]['nome'] ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Tipo de negócio:</strong></td>
                                  <td><?= $dados_negocio[0]['tipo_negocio'] ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Data de início:</strong></td>
                                  <td><?= converteData($dados_negocio[0]['data_inicio']) ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Responsável:</strong></td>
                                  <td><?= $dados_negocio[0]['responsavel'] ?></td>
                                </tr>
                                <?php if ($perfil_usuario != 8) { ?>
                                <tr>
                                  <td class="td-table"><strong>Valor contrato:</strong></td>
                                  <td>R$ <?= converteMoeda($dados_negocio[0]['valor_contrato']) ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Valor adesão:</strong></td>
                                  <td>R$ <?= converteMoeda($dados_negocio[0]['valor_adesao']) ?></td>
                                </tr>
                                <?php if ($dados_negocio[0]['tipo_negocio'] == 'Upgrade') { ?>
                                <tr>
                                  <td class="td-table"><strong>Valor aumento:</strong></td>
                                  <td>R$ <?= converteMoeda($dados_negocio[0]['valor_aumento']) ?></td>
                                </tr>
                                <?php } else if ($dados_negocio[0]['tipo_negocio'] == 'Downgrade') { ?>
                                <tr>
                                  <td class="td-table"><strong>Valor redução:</strong></td>
                                  <td>R$ <?= converteMoeda($dados_negocio[0]['valor_reducao']) ?></td>
                                </tr>
                                <?php } ?>
                                <?php } ?>

                                <?php

                                if ($dados_negocio[0]['cod_servico'] == '') {
                                  $servico = 'N/D';
                                } else {
                                  if ($dados_negocio[0]['cod_servico'] == 'call_suporte') {
                                    $servico = 'Call Center - Suporte';
                                  }
                                  if ($dados_negocio[0]['cod_servico'] == 'call_ativo') {
                                    $servico = 'Call Center - Ativo';
                                  }
                                  if ($dados_negocio[0]['cod_servico'] == 'gestao_redes') {
                                    $servico = 'Gestão de Redes';
                                  }
                                }

                                if ($dados_negocio[0]['nome_plano'] == '') {
                                  $nome_plano = 'N/D';
                                } else {
                                  $nome_plano = $dados_negocio[0]['nome_plano'];
                                }
                                ?>
                                <tr>
                                  <td class="td-table"><strong>Serviço:</strong></td>
                                  <td><?= $servico ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Nome do plano:</strong></td>
                                  <td><?= $nome_plano ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Data conclusão:</strong></td>
                                  <td><?= converteData($dados_negocio[0]['data_conclusao']) ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Negócio fechado com:</strong></td>
                                  <td><?= $fechado_com ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Descrição:</strong></td>
                                  <td><?= $dados_negocio[0]['descricao'] ?></td>
                                </tr>
                                <tr>
                                  <td class="td-table"><strong>Observações:</strong></td>
                                  <td><?= $dados_negocio[0]['obs_ganhou'] ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div><!-- end col-md-12 -->
                        </div><!-- end row -->

                        <br>

                        <?php
                        if ($dados_negocio[0]['andamento'] == 0) {
                          $class_andamento = 'background-color: #708090; color: white;';
                        } else {
                          $class_andamento = '';
                        }

                        if ($dados_negocio[0]['andamento'] == 1) {
                          $class_ganhou = 'btn-success';
                        } else {
                          $class_ganhou = '';
                        }

                        if ($dados_negocio[0]['andamento'] == 2) {
                          $class_perdeu = 'btn-danger';
                        } else {
                          $class_perdeu = '';
                        }
                        ?>

                        <div class="row">
                          <div class="col-md-12 text-center">
                            <div class="btn-group" id="perdeu-ganhou" role="group" aria-label="...">
                              <button type="button" class="btn-andamento btn btn-sm <?= $class_perdeu ?>" data-value="2" style="min-width: 150px;">
                                <i class="fa fa-thumbs-o-down"></i> Perdeu
                              </button>
                              <button type="button" class="btn-andamento btn btn-sm" data-value="0" style="min-width: 150px; <?= $class_andamento ?>">
                                <i class="fa fa-exchange"></i> Em andamento
                              </button>
                              <button type="button" class="btn-andamento btn btn-sm <?= $class_ganhou ?>" data-value="1" style="min-width: 150px;">
                                <i class="fa fa-thumbs-o-up"></i> Ganhou
                              </button>
                            </div>
                          </div>
                        </div>
                      </div><!-- end tab 3 -->

                      <!-- tab 1 Informações da Empresa/Pessoa  -->
                      <div id="tab1" class="tab-pane fade">
                        <div class="row">
                          <div class="col-md-12">
                            <table class="table table-striped">
                              <tbody><br>
                                <tr>
                                  <td class="td-table"><strong>Nome:</strong></td>
                                  <td id="nome-lead-prospeccao"><?= $dados[0]['nome'] ?></td>
                                </tr>
                                <?php

                                $data_nascimento = converteData($dados[0]['data_nascimento']);

                                if ($data_nascimento != '' && $data_nascimento != '00/00/0000') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Data de nascimento:</strong></td>
                                    <td><?= $data_nascimento ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['fone1']) {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Telefone 1:</strong></td>
                                    <td class="phone"><?= $dados[0]['fone1'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['fone2'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Telefone 2:</strong></td>
                                    <td class="phone"><?= $dados[0]['fone2'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['fone3'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Telefone 3:</strong></td>
                                    <td class="phone"><?= $dados[0]['fone3'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['email1'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Email 1:</strong></td>
                                    <td><?= $dados[0]['email1'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['email2'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Email 2:</strong></td>
                                    <td><?= $dados[0]['email2'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['skype'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Skype:</strong></td>
                                    <td><?= $dados[0]['skype'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['facebook'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Facebook:</strong></td>
                                    <td><?= $dados[0]['facebook'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['site'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Site:</strong></td>
                                    <td><?= $dados[0]['site'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['obs_interna'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Observações:</strong></td>
                                    <td><?= $dados[0]['obs_interna'] ?></td>
                                  </tr>
                                <?php
                                }

                                if ($dados[0]['id_cidade'] != '') {
                                  $cidade_estado = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE id_cidade = '" . $dados[0]['id_cidade'] . "' ", "a.nome as cidade, b.nome as estado");
                                }

                                if ($dados[0]['logradouro'] != '') {
                                ?>
                                  <tr>
                                    <td class="td-table"><strong>Endereço :</strong></td>
                                    <td><?= $dados[0]['logradouro'] ?>, <?= $dados[0]['numero'] ?>, <?= $dados[0]['bairro'] ?> - <?= $cidade_estado[0]['cidade'] ?> - <?= $cidade_estado[0]['estado'] ?></td>
                                  </tr>
                                <?php
                                }
                                ?>

                              </tbody>
                            </table>
                          </div><!-- end col-md-12 -->
                        </div><!-- end row -->
                      </div><!-- end tab 1 -->

                      <!-- tab 2 Dados prospecção -->
                      <div id="tab2" class="tab-pane fade">

                        <?php
                        if (!$dados_prospeccao) {
                        ?>
                          <p class="alert alert-info" style="text-align: center">Não há informações!</p>
                        <?php
                        } else {
                        ?>

                          <div class="row">
                            <div class="col-md-12">
                              <table class="table table-striped">
                                <tbody><br>
                                  <?php if ($origem[0]['descricao'] != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Origem:</strong></td>
                                      <td><?= $origem[0]['descricao'] ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($segmento != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Segmento:</strong></td>
                                      <td><?= $segmento ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($quantidade_clientes != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Quantidade de clientes:</strong></td>
                                      <td><?= $quantidade_clientes ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($estrutura_tres_niveis != 0) { ?>
                                    <tr>
                                      <td class="td-table"><strong>Sabe o que é a estrutura em três níveis?</strong></td>
                                      <td><?= $estrutura_tres_niveis ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($qtde_funcionarios_nivel_1 != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Quantos funcionários no nível 1?</strong></td>
                                      <td><?= $qtde_funcionarios_nivel_1 ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($qtde_funcionarios_nivel_2 != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Quantos funcionários no nível 2?</strong></td>
                                      <td><?= $qtde_funcionarios_nivel_2 ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($qtde_funcionarios_nivel_3 != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Quantos funcionários no nível 3?</strong></td>
                                      <td><?= $qtde_funcionarios_nivel_3 ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($central_telefonica != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Central Telefônica?</strong></td>
                                      <td><?= $central_telefonica ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($sistema_de_gestao != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Sistema de gestão?</strong></td>
                                      <td><?= $sistema_de_gestao ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($equipamento != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Meios de acesso a internet</strong></td>
                                      <td><?= $equipamento ?></td>
                                    </tr>
                                  <?php } ?>

                                  <?php if ($acesso_internet != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Quais os meios de acesso à internet?</strong></td>
                                      <td><?= $acesso_internet ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($horario_mais_ligacoes != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Horário que tem mais ligações?</strong></td>
                                      <td><?= $horario_mais_ligacoes ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($atendimento_fideliza_cliente != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>De 0 a 10, o quanto acredita que um atendimento de qualidade fideliza o cliente?</strong></td>
                                      <td><?= $atendimento_fideliza_cliente ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($terceirizacao_atendimento != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Por que você procura a terceirização do atendimento?</strong></td>
                                      <td><?= $terceirizacao_atendimento ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($qualificacao_cliente != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>De 0 a 10, quanto acredita que os clientes do seu provedor estão satisfeitos?</strong></td>
                                      <td><?= $qualificacao_cliente ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($concorrencia != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Como compara o atendimento do seu provedor em relação a concorrência?</strong></td>
                                      <td><?= $concorrencia ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($reclamacoes_redes_sociais != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>De 0 a 10, quanto a empresa recebe reclamações nas Redes Sociais?</strong></td>
                                      <td><?= $reclamacoes_redes_sociais ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($equipamento_redes != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Com qual equipamento trabalha?</strong></td>
                                      <td><?= $equipamento_redes ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($outro_equipamento != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Qual outro?</strong></td>
                                      <td><?= $outro_equipamento ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($pessoa_contato != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Quem é o contato?</strong></td>
                                      <td><?= $pessoa_contato ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($exp_outra_assessoria_redes != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Já teve experiência com outra assessoria em redes?</strong></td>
                                      <td><?= $exp_outra_assessoria_redes ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($exp_outra_qual != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Qual outra?</strong></td>
                                      <td><?= $exp_outra_qual ?></td>
                                    </tr>
                                  <?php } ?>
                                  <?php if ($pq_nao_tem_mais != '') { ?>
                                    <tr>
                                      <td class="td-table"><strong>Por que não tem mais?</strong></td>
                                      <td><?= $pq_nao_tem_mais ?></td>
                                    </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div><!-- end col-md-12 -->
                          </div><!-- end row -->

                        <?php
                        }
                        ?>
                      </div><!-- end tab 2 -->

                      <!-- tab 4 vinculos -->
                      <div id="tab4" class="tab-pane fade">
                        <div class="list-group">
                          <?php
                          if ($dados_vinculos) {
                            foreach ($dados_vinculos as $conteudo) {
                          ?>  
                              <div class="list-group-item">
                                <div style="margin-top: 7px !important; margin-bottom: 7px !important;">
                                  <span class="list-group-item-heading" style="font-size: 14px;">
                                    <strong><?= $conteudo['nome'] ?></strong>
                                  </span>
                                  <span><a href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=<?= $conteudo['id_pessoa'] ?>&pagina-origem=negocio-informacoes&id-negocio=<?= $id ?>"><i class="fa fa-eye pull-right"></i></a></span>
                                  <br>
                                  - Telefone: <span class="phone"> <?= $conteudo['fone1'] ?></span>
                                  <br>
                                  - Email: <span class="email"> <?= $conteudo['email1'] ?></span>
                                  <br>
                                </div>
                              </div>
                          <?php
                            }
                          } else {
                            echo '<p class="alert alert-info" style="text-align: center">Não foram encontrados vínculos !</p>';
                          }
                          ?>
                        </div>
                      </div><!-- end tab 4 -->

                    </div>
                  </div>
                </div><!-- end panel -->
              </div><!-- end row -->

              <!-- end row -->
            </div><!-- end col-md-6 -->

            <div class="col-md-7" style="padding: 0px 20px 0px 25px">
              <div class="row" id="row-timeline">
                <div class="panel panel-default">
                  <div class="panel-heading clearfix">
                    <h5 class="panel-title text-left pull-left" style="margin-top: 2px;">Timeline</h5>
                    <div class="panel-title text-right pull-right">
                      <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-plus"></i> Novo item timeline
                      </button>
                    </div>
                  </div>
                  <div class="panel-body" style="height: 550px; overflow-y: scroll;">

                    <div class="row timeline">
                      <ul class="timeline">

                        <?php
                        $dados = DBRead('', 'tb_lead_timeline a', "INNER JOIN tb_lead_negocio b ON a.id_lead_negocio = b.id_lead_negocio INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE a.id_lead_negocio = '$id' ORDER BY a.data DESC", 'a.id_lead_timeline, a.data, a.descricao as descricao_timeline, a.id_lead_negocio, a.finalizado,a.id_lead_tipo_item_timeline, a.id_usuario, a.id_usuario_finalizou, c.nome as nome_lead, e.nome, a.convidado');

                        if ($dados) {

                          $quantidade = sizeof($dados);

                          foreach ($dados as $conteudo) {

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

                            $reuniao = DBRead('', 'tb_lead_reuniao', "WHERE id_lead_timeline = '" . $conteudo['id_lead_timeline'] . "' ");

                            if ($reuniao) {
                              $participantes = DBRead('', 'tb_lead_usuario_reuniao a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_lead_reuniao = '" . $reuniao[0]['id_lead_reuniao'] . "'", 'b.id_perfil_sistema, c.nome, a.id_usuario');
                            }

                            $nomes = '';
                            if ($participantes) {
                              foreach ($participantes as $p) {
                                $nomes .= $p['nome'] . ' - ';
                              }
                            }

                            $nomes = substr_replace($nomes, "", -2);
                        ?>

                            <li class="timeline-inverted">
                              <div class="timeline-badge" style="background-color: <?= $cor ?>;">
                                <i class="glyphicon <?= $badge ?>" style="font-size: 18px; margin-top: 15px !important;"></i>
                              </div>

                              <div class="timeline-panel" id="<?= $conteudo['id_lead_timeline'] ?>">
                                <div class="timeline-heading">

                                  <?php
                                  if (in_array($conteudo['id_lead_timeline'], $itens_nao_lidos)) {
                                    if ($conteudo['id_usuario'] != $id_usuario) {
                                      $notifica = '<i id="i_exclamation_' . $conteudo['id_lead_timeline'] . '" class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i>';
                                    }
                                  } else {
                                    $notifica = "";
                                  }
                                  ?>

                                  <span class="timeline-title" style="font-size: 16px;">
                                    <strong><?= $notifica ?> <?= $conteudo['nome_lead'] ?></strong>
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
                                if ($reuniao) {

                                  if ($conteudo['convidado'] !='') {
                                    $convidado = $conteudo['convidado'];
      
                                  } else {
                                    $convidado = 'ND';
                                  }
                                ?>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <span><strong>Reunião: </strong>
                                            <?= converteDataHora($reuniao[0]['data']) ?>
                                          </span>
                                      </div>
                                      <div class="col-md-6">
                                          <span class="pull-right">
                                            <strong>Participantes: </strong>
                                            <?= $nomes ?>
                                          </span>
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
                                $finalizado_por = '';

                                if ($conteudo['finalizado'] != '') {
                                  $finalizado = 'success';
                                  $finalizado_icon = 'fa-check-square';
                                  $finalizado_text = 'Finalizado';

                                  $usuario_finalizou = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $conteudo['id_usuario_finalizou'] . "' ", 'a.nome');

                                  $finalizado_por = '<strong>Finalizado por: </strong>' . $usuario_finalizou[0]['nome'] . '<br> <strong>Data: </strong>' . converteDataHora($conteudo['finalizado']);
                                }

                                if ($reuniao) {

                                  $rem = strtotime($reuniao[0]['data']) - time();
                                  $day = floor($rem / 86400);
                                  $hr  = floor(($rem % 86400) / 3600);
                                  $min = floor(($rem % 3600) / 60);

                                  if ($day) {
                                    if ($day == 1) {
                                      $legenda = '<span class="label label-default">Falta ' . $day . ' dia e ' . $hr . ' horas!</span>';
                                    }
                                    if ($day > 1) {
                                      $legenda = '<span class="label label-default">Faltam ' . $day . ' dias e ' . $hr . ' horas!</span>';
                                    }
                                    if ($day < 1) {
                                      $legenda = '<span class="label label-danger">Prazo encerrado!</span>';
                                    }
                                  } else if ($hr) {
                                    if ($hr > 1) {
                                      $legenda = '<span class="label label-warning">Faltam ' . $hr . ' horas!</span>';
                                    }
                                    if ($hr == 1) {
                                      $legenda = '<span class="label label-warning">Falta ' . $hr . ' hora!</span>';
                                    }
                                  } else {
                                    if ($min > 1) {
                                      $legenda = '<span class="label" style="background-color: #D2691E">Faltam ' . $min . ' minutos!</span>';
                                    }
                                    if ($min == 1) {
                                      $legenda = '<span class="label" style="background-color: #D2691E">Falta ' . $min . ' minuto!</span>';
                                    }
                                  }
                                ?>

                                  <span style="color: #333; font-size: 14px;">
                                    <strong>Prazo:</strong> <?= $legenda ?>
                                  </span>

                                <?php
                                } //end if prazo

                                /* if($conteudo['id_lead_tipo_item_timeline'] == 6){
                                          $display_buttons = 'none';
                                        }
                                        else{
                                          $display_buttons = 'block';
                                        } */
                                ?>

                                <small class="text-muted pull-right" style="display: <?= $display_buttons ?>">

                                  <?php

                                  if ($participantes) {

                                    foreach ($participantes as $conteudo_participantes) {

                                      if ($id_usuario == $conteudo_participantes['id_usuario'] || $id_usuario == $conteudo['id_usuario'] || $perfil_usuario == 2 || $perfil_usuario == 22) {
                                        $btn_finalizado = true;
                                      } else {
                                        $btn_finalizado = false;
                                      }
                                    }
                                  }

                                  //if($btn_finalizado == true){
                                  ?>
                                  <button class="btn btn-xs btn-<?= $finalizado ?>" conteudo-id="<?= $conteudo['id_lead_timeline'] ?>" idfinalizar="<?= $conteudo['id_lead_timeline'] ?>" onclick="finalizar(this)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="focus" title="" data-content="<?= $finalizado_por ?>">
                                    <i class="fa <?= $finalizado_icon ?>"></i> <span><?= $finalizado_text ?></span>
                                  </button>

                                  <?php
                                  //} 
                                  ?>

                                  <button class="btn btn-xs btn-primary editar" conteudo-id="<?= $conteudo['id_lead_timeline'] ?>" data-toggle="modal" id="<?= $conteudo['id_lead_timeline'] ?>" onclick="preencheModal(this.id)">
                                    <i class="fa fa-pencil"></i> Editar
                                  </button>
                                  <button class="btn btn-xs btn-danger excluir" conteudo-id="<?= $conteudo['id_lead_timeline'] ?>">
                                    <i class="fa fa-trash"></i> Excluir
                                  </button>
                                </small>
                              </div>
                            </li>

                          <?php
                          } //end foreach

                        } //end if
                        else {

                          echo '<p class="alert alert-info" style="text-align: center; z-index: 20; position: relative; margin-left: 10px; margin-right: 10px; margin-top: -10px;">Não foram encontrados registros!</p>';

                          ?>
                          <style>
                            .timeline:before {
                              display: none;
                            }
                          </style>
                        <?php

                        } //end else
                        ?>

                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- end col-md-6 -->

          </div>

        </div><!-- end panel body-->

      </div><!-- end panel default-->
    </div><!-- end col-md-12 -->
  </div><!-- end row -->
</div>

<!-- Modal Novo item na timeline -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Novo item na timeline</h4>
      </div>

      <form method="post" action="/api/ajax?class=Lead.php" id="item_timeline_form" class='form-modal' style="margin-bottom: 0;">
		    <input type="hidden" name="token" value="<?php echo $request->token ?>">

        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Tipo:</label>
                <select class="form-control" id="tipo_item_timeline">
                  <option></option>
                  <?php
                  $dados_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline', "WHERE exibe = 1 ORDER BY nome ASC");

                  foreach ($dados_tipo_item_timeline as $conteudo_tipo) {
                  ?>
                    <option value="<?= $conteudo_tipo['id_lead_tipo_item_timeline'] ?>"><?= $conteudo_tipo['nome'] ?></option>
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
                <label class="label-contato">Contato realizado com sucesso!</label>
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
                <!-- <span class="input-group-addon">
                        <input type="checkbox" name="marcar_reuniao" id="marcar_reuniao" value="1">
                    </span> -->
                <input type="text" class="form-control date calendar hasDatePicker" name="data_reuniao" value="" id="data_reuniao" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="form-group">
                  <label>Hora:</label>
                  <input type="time" class="form-control" id="hora_reuniao" value="" name="hora_reuniao" autocomplete="off">
                  <input type="hidden" id="getdatahora" value="<?= getdatahora(); ?>">
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
                  if ($usuarios) {
                    foreach ($usuarios as $conteudo) {
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
      <!-- <div class="modal-body">
          <div class="row">
              <div class="col-md-12">
                  
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" name="inserir_item_timeline" value="<?= $id ?>" id="ok" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div> -->
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal Negocios perdido -->
<div class="modal fade" id="negocio_perdido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Negócio perdido</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Motivo</label>
              <select class="form-control" name="perda_motivo" id="perda_motivo" required>
                <?php
                $motivos = DBRead('', 'tb_lead_motivo_perda', "ORDER BY descricao ASC");
                foreach ($motivos as $conteudo) {
                ?>
                  <option value="<?= $conteudo['id_lead_motivo_perda'] ?>"><?= $conteudo['descricao'] ?></option>

                <?php
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Observação:</label>
              <textarea class="form-control" rows="4" name="obs_negocio_perdido" id="obs_negocio_perdido" required></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Lembrar no dia:</label>
              <input type="text" class="form-control date calendar hasDatePicker" name="lembrete_negocio_perdido" value="" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Data de conclusão do negócio:</label>
              <input type="text" class="form-control date calendar hasDatePicker" name="data_conclusao_perdeu" value="<?= converteData($data_agora) ?>" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" name="inserir_negocio_perdido" value="<?= $id ?>" id="inserir_negocio_perdido" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal Negocios ganho -->
<div class="modal fade" id="negocio_ganho" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Negócio ganho</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Pessoa com quem o negócio foi fechado:</label>
              <select class="form-control" name="fechado_com" id="fechado_com" required>
                <option value="NULL">N/D</option>

                <?php foreach ($dados_vinculos as $vinculos) { ?>
                  <option value="<?= $vinculos['id_pessoa'] ?>"><?= $vinculos['nome'] ?></option>

                <?php } ?>

              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Data de conclusão do negócio:</label>
              <input type="text" class="form-control date calendar hasDatePicker" name="data_conclusao_ganhou" value="<?= converteData($data_agora) ?>" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Observação:</label>
              <textarea class="form-control" rows="4" name="obs_negocio_ganho" id="obs_negocio_ganho" required=""></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" name="inserir_negocio_ganho" value="<?= $id ?>" id="inserir_negocio_ganho" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal alterar lembrete -->
<div class="modal fade" id="modal_alterar_lembrete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Negócio perdido</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Motivo</label>
              <select class="form-control" name="altera_motivo" id="altera_motivo" required>
                <?php
                $motivos = DBRead('', 'tb_lead_motivo_perda', "ORDER BY descricao ASC");
                foreach ($motivos as $conteudo) {
                  $selected = $motivo == $conteudo['id_lead_motivo_perda'] ? "selected" : "";
                ?>
                  <option value="<?= $conteudo['id_lead_motivo_perda'] ?>" <?=$selected?>><?= $conteudo['descricao'] ?></option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Observação:</label>
              <textarea class="form-control" rows="4" name="altera_obs" id="altera_obs" required><?= $obs ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Lembrar no dia:</label>
              <input type="text" class="form-control date calendar hasDatePicker" name="altera_data" value="<?= converteData($data_lembrete) ?>" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" name="alterar_lembrete_submit" value="<?= $id ?>" id="alterar_lembrete_submit" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script src="https://apis.google.com/js/api.js"></script>
<script async>
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
  });

  $('#tipo_item_timeline').on('change', function() {
    if ($(this).val() == 2 || $(this).val() == 4) {
      $('#row-contato-realizado').css('display', 'block');
    } else {
      $('#row-contato-realizado').css('display', 'none');
    }
  });

  $('#agendar-tarefa').on('change', function() {
    if ($(this).val() == 'sim') {
      $('#row-data-tarefa').css('display', 'block');
      $('#row-usuarios-tarefa').css('display', 'block');
      $('#row-convidado').css('display', 'block');
      $('#data_reuniao').addClass('date calendar hasDatePicker');
    } else {
      $('#row-data-tarefa').css('display', 'none');
      $('#row-usuarios-tarefa').css('display', 'none');
      $('#row-convidado').css('display', 'none');
      $('#data_reuniao').removeClass('date calendar hasDatePicker');
    }
  });

  $('.verifica').on('click', function() {

    btn = $(this);
    var id_status = $(this).attr('data-id');
    var text = $(this).text();
    var id_lead_negocio = '<?php echo $id ?>';
    var data_lembrete = $('#existe_data_lembrete').val();

    if (text == 'Negociações Pausadas') {
      $('#negociacao_pausada').modal('show');

      $('#inserir_negociacao_pausa').on('click', function() {

        var d = new Date();

        var data = (d.getDate() < 10 ? '0' : '') + d.getDate() + '/' + (d.getMonth() < 10 ? '0' : '') + (parseInt(d.getMonth()) + parseInt(1)) + '/' + d.getFullYear();

        if (data_lembrete != '' && data_lembrete === undefined) {

          var motivo = $('#pausa_motivo').val();
          var observacao = $('#pausa_observacao').val();
          var lembrete = $('input[name="pausa_lembrete"]').val();

          if (lembrete == '' || observacao == '') {

            alert('Verifique se os campos "Observação" e a "Lembrar no dia" estão preenchidos');

            return false;

          } else {
            modalAguarde();

            $.ajax({
              url: "/api/ajax?class=LeadTimelineAjax.php",
              dataType: "json",
              method: 'POST',
              data: {
                acao: 'negociacao_pausada',
                parametros: {
                  'id_status': id_status,
                  'id_lead_negocio': id_lead_negocio,
                  'id_lead_pausa_motivo': motivo,
                  'observacao': observacao,
                  'lembrete': lembrete
                },
                token: '<?= $request->token ?>'
              },
              success: function(data) {
                if (data == 1) {
                  $('.verifica').removeClass('btn-success');
                  btn.addClass('btn-success');
                  $('#negociacao_pausada').modal('hide');
                  $("#teste").load(location.href + " #teste>");
                } else {
                  alert('Falhou');
                }

                modalAguarde(false);
              }
            });
          }

        } else {
          if (!confirm('Deseja substituir o lembrete?')) {
            return false;
          } else {
            var motivo = $('#pausa_motivo').val();
            var observacao = $('#pausa_observacao').val();
            var lembrete = $('input[name="pausa_lembrete"]').val();

            if (lembrete == '' || observacao == '') {

              alert('Verifique se os campos "Observação" e a "Lembrar no dia" estão preenchidos');

              return false;

            } else {
              $.ajax({
                url: "/api/ajax?class=LeadTimelineAjax.php",
                dataType: "json",
                method: 'POST',
                data: {
                  acao: 'negociacao_pausada',
                  parametros: {
                    'id_status': id_status,
                    'id_lead_negocio': id_lead_negocio,
                    'id_lead_pausa_motivo': motivo,
                    'observacao': observacao,
                    'lembrete': lembrete
                  },
                  token: '<?= $request->token ?>'
                },
                success: function(data) {
                  if (data == 1) {
                    $('.verifica').removeClass('btn-success');
                    btn.addClass('btn-success');
                    $('#negociacao_pausada').modal('hide');
                    $("#teste").load(location.href + " #teste>");
                  } else {
                    alert('Falhou');
                  }
                  modalAguarde(false);
                }
              });
            }
          }
        }

      });
    } else {
      if(!confirm('Alterar status do negócio para '+text+'?')) {
        return false;
      }
      $.ajax({
        url: "/api/ajax?class=LeadTimelineAjax.php",
        dataType: "json",
        method: 'POST',
        data: {
          acao: 'troca_status',
          parametros: {
            'id_status': id_status,
            'id_lead_negocio': id_lead_negocio
          },
          token: '<?= $request->token ?>'
        },
        success: function(data) {
          if (data == 1) {
            $('.verifica').removeClass('btn-success');
            $('.verifica').removeClass('btn-danger');

            if (id_status == 15) {
              btn.addClass('btn-danger');

            } else {
              btn.addClass('btn-success');
            }
            
          } else {
            alert('Falhou');
          }
          modalAguarde(false);
        }
      });
    }
  });

  $('.btn-andamento').on('click', function(e) {

    btn = $(this);
    var andamento = $(this).attr('data-value');

    if (andamento == 2) {
      $('#negocio_perdido').modal('show');

      $('#inserir_negocio_perdido').on('click', function() {

        $('#inserir_negocio_perdido').prop('disabled', true);

        var motivo_perda = $('#perda_motivo').val();
        var obs_perda = $('#obs_negocio_perdido').val();
        var data_lembrete = $('input[name="lembrete_negocio_perdido"]').val();
        var data_conclusao = $('input[name="data_conclusao_perdeu"]').val();

        if (data_conclusao == '') {
          alert('Informe a data de conclusão!');
          return false;
          $('#inserir_negocio_perdido').prop('disabled', false);
        }

        modalAguarde();
        
        $.ajax({
          url: "/api/ajax?class=LeadTimelineAjax.php",
          dataType: "json",
          method: 'POST',
          data: {
            acao: 'negocio-andamento',
            parametros: {
              'andamento': andamento,
              'id_lead_negocio': '<?php echo $id ?>',
              'id_lead_motivo_perda': motivo_perda,
              'observacao': obs_perda,
              'data_lembrete': data_lembrete,
              'data_conclusao': data_conclusao
            },
            token: '<?= $request->token ?>'
          },
          success: function(data) {
            if (data == 1) {

              $('.btn-andamento').removeClass('btn-success');
              $('.btn-andamento').removeClass('btn-danger');
              $('.btn-andamento').css('background-color', '#ddd');
              $('.btn-andamento').css('color', '#333');

              btn.css('background-color', '#c12e2a');
              btn.css('color', 'white');

              $("#row-negocio").load(location.href + " #row-negocio>");

            } else {
              alert('Não foi possivel alterar');
            }

            e.preventDefault();

            $('#negocio_perdido').modal('hide');
            $('#inserir_negocio_perdido').prop('disabled', false);

            modalAguarde(false);
          }
        });
      });
    } else if (andamento == 1) {
      $('#negocio_ganho').modal('show');

      $('#inserir_negocio_ganho').on('click', function() {

        $('#inserir_negocio_ganho').prop('disabled', true);
        
        var fechado_com = $('#fechado_com').val();
        var data_conclusao = $('input[name="data_conclusao_ganhou"]').val();
        var obs_ganhou = $('#obs_negocio_ganho').val();

        if (data_conclusao == '') {
          alert('Informe a data de conclusão!');
          return false;
          $('#inserir_negocio_ganho').prop('disabled', false);
        }

        modalAguarde();

        $.ajax({
          url: "/api/ajax?class=LeadTimelineAjax.php",
          dataType: "json",
          method: 'POST',
          data: {
            acao: 'negocio-andamento',
            parametros: {
              'andamento': andamento,
              'id_lead_negocio': '<?php echo $id ?>',
              'fechado_com': fechado_com,
              'data_conclusao': data_conclusao,
              'obs_ganhou': obs_ganhou
            },
            token: '<?= $request->token ?>'
          },
          success: function(data) {
            if (data == 1) {

              $('.btn-andamento').removeClass('btn-success');
              $('.btn-andamento').removeClass('btn-danger');
              $('.btn-andamento').css('background-color', '#ddd');
              $('.btn-andamento').css('color', '#333');

              btn.css('background-color', '#398439');
              btn.css('color', 'white');

              $("#row-negocio").load(location.href + " #row-negocio>");
              

            } else if (data == 2) {

              alert('Para ganhar um negócio é necessário definir "Serviço" e "Plano"');

            } else {

              alert('Não foi possivel alterar');

            }

            e.preventDefault();

            $('#negocio_ganho').modal('hide');
            $('#inserir_negocio_ganho').prop('disabled', false);

            modalAguarde(false);
          }
        });

      });
    } else {
      $.ajax({
        url: "/api/ajax?class=LeadTimelineAjax.php",
        dataType: "json",
        method: 'POST',
        data: {
          acao: 'negocio-andamento',
          parametros: {
            'andamento': andamento,
            'id_lead_negocio': '<?php echo $id ?>'
          },
          token: '<?= $request->token ?>'
        },
        success: function(data) {
          if (data == 1) {

            $('.btn-andamento').removeClass('btn-success');
            $('.btn-andamento').removeClass('btn-danger');
            $('.btn-andamento').css('background-color', '#ddd');
            $('.btn-andamento').css('color', '#333');

            if (andamento == 0) {
              btn.css('background-color', '#708090');
              btn.css('color', 'white');
            }

            $("#row-negocio").load(location.href + " #row-negocio>");

          } else {
            alert('Não foi possivel alterar');
          }

          modalAguarde(false);

          e.preventDefault();
        }
      });
    }
  });

  $('[data-toggle="popover"]').popover({
    trigger: "hover",
    container: "body"
  });

  $('#alterar_lembrete').on('click', function(e) {

    $('#modal_alterar_lembrete').modal('show'); 

    $('#alterar_lembrete_submit').on('click', function() {

      modalAguarde();

      var motivo_perda = $('#altera_motivo').val();
      var obs_perda = $('#altera_obs').val();
      var data_lembrete = $('input[name="altera_data"]').val();

      $.ajax({
        url: "/api/ajax?class=LeadTimelineAjax.php",
        dataType: "json",
        method: 'POST',
        data: {
          acao: 'editar_lembrete',
          parametros: {
            'id_lead_negocio_perdido': '<?php echo $id_lead_negocio_perdido ?>',
            'id_lead_motivo_perda': motivo_perda,
            'observacao': obs_perda,
            'data_lembrete': data_lembrete
          },
          token: '<?= $request->token ?>'
        },
        success: function(data) {
          if (data == 1) {
            location.reload();
            alert('Lembrete alterado com sucesso!');

          } else {
            alert('Não foi possivel alterar');
          }

          e.preventDefault();
          $('#modal_alterar_lembrete').modal('hide');

          modalAguarde(false);
        }
      });
          
    });
  });

  function preencheModal(id) {
    $.ajax({
      url: "/api/ajax?class=LeadTimelineAjax.php",
      dataType: "html",
      method: 'POST',
      data: {
        acao: 'editar',
        parametros: {
          'item_timeline': id
        },
        token: '<?= $request->token ?>'
      },
      success: function(data) {
        $('#conteudo').html(data);
        $('#myModal_editar').modal('show');
      }
    });
  }

  function finalizar(tag) {
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
          'item_timeline': id
        },
        token: '<?= $request->token ?>'
      },
      success: function(data) {
        if (data != 0) {
          $(tag).removeClass('btn-default').addClass('btn-success');
          $(tag_i).removeClass('fa-square-o').addClass('fa-check-square');
          $(tag_span).text('Finalizado');
          $(tag).attr('data-content', '<strong>Finalizado por: </strong>' + data.nome_usuario + '<br><strong>Data: </strong>' + data.data);
          $(tag).popover('show');
        }
        if (data == 0) {
          $(tag).popover('hide');
          $(tag).removeClass('btn-success').addClass('btn-default');
          $(tag_i).removeClass('fa-check-square').addClass('fa-square-o');
          $(tag_span).text('Finalizar');
          $(tag).attr('data-content', '');
        }
      }
    });
  }

  function LogarContaGoogle() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function() {});
    auth2.disconnect();

    const authInstance = window.gapi.auth2.getAuthInstance();

    authInstance.grantOfflineAccess().then((res) => {
      console.log(res);
      //this.data.refreshToken = res.code;
      modalAguarde();
      location.reload();
    });
  }

  function SairContaGoogle() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function() {});
    auth2.disconnect();

    var myWindow = window.open('https://mail.google.com/mail/u/0/?logout&hl=en/', 'Google', "status=no, height=500, width=500, resizable=yes, left=40%, top=0%, screenX=2000, screenY=0,toolbar=no, menubar=no, scrollbars=no, location=no, directories=no");

    //var myWindow = window.open("https://mail.google.com/mail/u/0/?logout&hl=en/", "Google", "width=500, position=fixed, height=500, top=50%, left=50%");

    var close = setInterval(function() {
      myWindow.close();
      location.reload();
    }, 4000);
  }

  function excluirLembrete(id) {

    $.ajax({
      url: "/api/ajax?class=LeadTimelineAjax.php",
      dataType: "json",
      method: 'POST',
      data: {
        acao: 'excluir_lembrete',
        parametros: {
          'id_lead_negocio_perdido': id
        },
        token: '<?= $request->token ?>'
      },
      success: function(data) {
        if (data == 1) {
          alert('Lembrete excluído com sucesso!');
          location.reload();

        } else {
          alert('Não foi possivel excluir o lembrete');
        }
      }
    });
  }
</script>