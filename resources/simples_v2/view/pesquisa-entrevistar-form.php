<?php
require_once(__DIR__."/../class/System.php");

$id_pesquisa = $_GET['id_pesquisa'];
$adicionar = $_GET['adicionar'];
$id_usuario = $_SESSION['id_usuario'];


$nome_get = $_GET['nome'];
$telefone_get = $_GET['telefone'];
$id_native = $_GET['id_native'];

$dado_adicional1_get = $_GET['dado_adicional1'];
$dado_adicional2_get = $_GET['dado_adicional2'];
$dado_adicional3_get = $_GET['dado_adicional3'];

$id_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pesquisa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa WHERE b.id_pesquisa = '" . $id_pesquisa . "' LIMIT 1", "a.*,b.*, b.prazo_termino as prazo_termino_pesquisa");

$titulo = $id_contrato[0]['titulo'];
$ramal_retorno = $id_contrato[0]['ramal'];
$obs_pesquisa = $id_contrato[0]['observacao'];
$qtd_tentativas_pesquisa = $id_contrato[0]['qtd_tentativas_cliente'];
$id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
$horas_entre_tentativas = $id_contrato[0]['horas_entre_tentativas'];

$prazo_termino = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = '" . $id_pesquisa . "' LIMIT 1");

if($prazo_termino[0]['prazo_termino'] != null){
    $prazo_termino = $prazo_termino[0]['prazo_termino'];
}


$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '" . $id_contrato . "'", "b.nome, c.nome AS cidade, d.nome AS estado, d.sigla, e.cor, e.nome AS nome_plano, a.nome_contrato");

if ($dados_contrato[0]['nome_contrato']) {
    $nome_contrato = $dados_contrato[0]['nome'] . ' (' . $dados_contrato[0]['nome_contrato'] . ')';
} else {
    $nome_contrato = $dados_contrato[0]['nome'];
}

$data_agora = getDataHora();

$data_horas_entre_tentativas = new DateTime(getDataHora());
$data_horas_entre_tentativas->modify('- '.$horas_entre_tentativas.' hours');
$data_horas_entre_tentativas = $data_horas_entre_tentativas->format('Y-m-d H:i:s');

$data_hora_intervalo_15_segundos = new DateTime(getDataHora());
$data_hora_intervalo_15_segundos->modify('- 15 seconds');
$data_hora_intervalo_15_segundos = $data_hora_intervalo_15_segundos->format('Y-m-d H:i:s');

$clientes = DBRead('', 'tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa WHERE a.id_pesquisa = '" . $id_pesquisa . "' AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_data_contato_pesquisa WHERE data_atualizacao >= ('$data_hora_intervalo_15_segundos')) AND a.data_ultimo_contato < '".$data_horas_entre_tentativas."' AND a.status_pesquisa = 0 ORDER BY a.data_ultimo_contato ASC");

// $clientes = DBRead('', 'tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa WHERE a.id_pesquisa = '" . $id_pesquisa . "' AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_data_contato_pesquisa WHERE data_atualizacao >= ('$data_agora' - INTERVAL 15 SECOND)) AND a.data_ultimo_contato < DATE_ADD('" . $data_agora . "', INTERVAL - b.horas_entre_tentativas HOUR) AND a.status_pesquisa = 0 ORDER BY a.data_ultimo_contato ASC");

$dados_perguntas = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = $id_pesquisa AND status != 0 ORDER BY posicao ASC");
if (!$dados_perguntas) {
    //verifica se não existem perguntas, caso não exista permissao = 1 e anula os botões
    $permissao = 1;
}

?>
<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - Pesquisa';
    });
</script>
<div class="container-fluid">

    <style>
        @media(min-width: 970px) {
            .panel-quadro {
                float: right !important;
            }

            .panel-pesquisa {
                float: left !important;
            }
        }
    </style>

    <div class="row">

        <div class="">
            <div class="col-md-5 panel-quadro">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="btn-group btn-group-justified" role="group" aria-label="...">
                            <div class="btn-group" role="group">
                                <a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-quadro-informativo&contrato=<?= $id_contrato ?>" target="_blank" class="btn btn-primary"><i class="fa fa-info" aria-hidden="true"> </i> Quadro Informativo</a>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-manual&contrato=<?= $id_contrato ?>" target="_blank" class="btn btn-primary"><i class="fa fa-file-text-o" aria-hidden="true"> </i> Manual</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

                echo '<div class="panel panel-info">
                            <div class="panel-heading">Informações:</div>
                            <div class="panel-body">';
                echo $obs_pesquisa;
                echo '</div>
                        </div>';
                ?>
            </div>
        </div>

        <div class="col-md-7 panel-pesquisa">

            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?php echo "<strong>" . $nome_contrato . ":</strong> " . $titulo ?></h3>
                    <div class="pull-right">
                        <?php
                        $solicitacao = DBRead('', 'tb_solicitacao_ajuda', "WHERE atendente = '" . $_SESSION['id_usuario'] . "' AND data_encerramento IS NULL");
                        if ($solicitacao) {
                            echo "<a href='#' id='solicita_ajuda' class='btn-xs btn btn-danger disabled' role='button'><i class='fa fa-exclamation' aria-hidden='true'></i> Ajuda solicitada</a>";
                        } else {
                            echo "<a href='#' id='solicita_ajuda' class='btn-xs btn btn-info' role='button'><i class='fa fa-question' aria-hidden='true'></i> Solicitar ajuda</a>";
                        }
                        ?>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=PesquisaEntrevista.php" id="acesso_equipamento_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="id_pesquisa" value="<?= $id_pesquisa ?>" />
                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario ?>" />
                    <input type="hidden" name="ramal_retorno" id="ramal_retorno" value="<?= $ramal_retorno ?>" />
                    <input type="hidden" name="id_native" value="<?= $id_native ?>" />
                    <?php

                    $data_de_hoje = getDataHora();

                    if (($clientes || $adicionar)) {
                        $dados_adicionais = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = '$id_pesquisa'");

                        $dado_adicional1 = $dados_adicionais[0]['dado1'];
                        $dado_adicional2 = $dados_adicionais[0]['dado2'];
                        $dado_adicional3 = $dados_adicionais[0]['dado3'];
                        /*
                            Caso chegue na tela de entrevistas através do adicionar
                        */
                        if ($adicionar == 1) {
                            echo '<div class="panel-body" style="padding-bottom: 0;">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                 
                                                    <div class="row">';

                            echo "<div class='col-md-6'>";
                            echo '<div class="form-group">';
                            echo "<input type='hidden' class='form-control' value='$adicionar' id='adicionar' />";
                            echo "<label>*Contato: </label>";
                            if ($permissao == 1) {
                                echo "<input type='text' name='contato' class='form-control input-sm' id='contato' value='$nome_get' readonly/>";
                            } else {
                                echo "<input type='text' name='contato' class='form-control input-sm' id='contato' value='$nome_get' required/>";
                            }

                            echo "</div>";
                            echo "</div>";
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Telefone:</label>
                                    <div class="input-group input-group-sm">
                                        <?php if ($permissao == 1) {

                                            echo '<input id="fone" name="telefone" placeholder="" class="form-control input-sm phone" value="' . $telefone_get . '" type="text" readonly>
                                                            <span class="input-group-btn">';
                                            echo '<button href="#" id="softphone" class="btn btn-primary" title="Ligar" disabled><span class="glyphicon glyphicon-earphone"></span>&nbsp;</button>';
                                        } else {
                                            echo '<input id="fone" name="telefone" placeholder="" class="form-control input-sm phone" value="' . $telefone_get . '" type="text" required>
                                                            <span class="input-group-btn">';
                                            echo '<a href="#" id="softphone" class="btn btn-primary" title="Ligar"><span class="glyphicon glyphicon-earphone"></span>&nbsp;</a>';
                                        } ?>

                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php

                            if ($dado_adicional1) :
                            ?>
                                <div class='col-md-4'>
                                    <div class="form-group">
                                        <label><?= $dado_adicional1 ?>:</label>
                                        <input type='text' name='dado_adicional1' class='form-control input-sm' value='<?= $dado_adicional1_get ?>' />
                                    </div>
                                </div>
                            <?php
                            endif;
                            if ($dado_adicional2) :
                            ?>
                                <div class='col-md-4'>
                                    <div class="form-group">
                                        <label><?= $dado_adicional2 ?>:</label>
                                        <input type='text' name='dado_adicional2' class='form-control input-sm' value='<?= $dado_adicional2_get ?>' />
                                    </div>
                                </div>
                            <?php
                            endif;
                            if ($dado_adicional3) :
                            ?>
                                <div class='col-md-4'>
                                    <div class="form-group">
                                        <label><?= $dado_adicional3 ?>:</label>
                                        <input type='text' name='dado_adicional3' class='form-control input-sm' value='<?= $dado_adicional3_get ?>' />
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <script>
                                $("#softphone").on('click', function() {
                                    ligar_softphone($('#ramal_retorno').val() + $('#fone').val());
                                });
                            </script>

                            <?php
                        } else {

                            $agendado = 0;
                            $agenda_passada = 0;

                            $agendamentos = DBRead('', 'tb_agendamento_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE b.id_pesquisa = '" . $id_pesquisa . "' AND a.status_agendamento = 0 AND b.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_data_contato_pesquisa WHERE data_atualizacao >= ('$data_hora_intervalo_15_segundos')) ORDER BY a.data_hora DESC", "a.*, b.*, a.telefone AS telefone1, b.telefone AS telefone2");
                            if ($agendamentos) {
                                foreach ($agendamentos as $agendamento) {

                                    $data_agendamento = $agendamento['data_hora'];
                                    /*
                                            Se existir agendamento
                                        */
                                    if ($agendamento) {

                                        if ($data_agendamento <= getDataHora()) {
                                            $agenda_passada = 1;
                                            $id_contatos_pesquisa = $agendamento['id_contatos_pesquisa'];

                                            $nome = $agendamento['nome'];
                                            if ($agendamento['telefone1']) {
                                                $telefone = $agendamento['telefone1'];
                                            } else {
                                                $telefone = $agendamento['telefone2'];
                                            }
                                            $obs_falha = $agendamento['obs_falha'];

                                        }
                                        if ($agenda_passada == 0) {
                                            $clientes = DBRead('', 'tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa WHERE a.id_pesquisa = '" . $id_pesquisa . "' AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_agendamento_pesquisa WHERE status_agendamento = 0) AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_data_contato_pesquisa WHERE data_atualizacao >= ('$data_hora_intervalo_15_segundos')) AND a.data_ultimo_contato < a.data_ultimo_contato < '".$data_horas_entre_tentativas."' AND a.status_pesquisa = 0 ORDER BY a.data_ultimo_contato ASC LIMIT 1");

                                           
                                            //var_dump($clientes);
                                            if ($clientes) {
                                                $id_contatos_pesquisa = $clientes[0]['id_contatos_pesquisa'];
                                                $nome = $clientes[0]['nome'];
                                                $telefone = $clientes[0]['telefone'];
                                            }
                                        }
                                    }
                                }
                            }
                            if (!$clientes && $agenda_passada == 0) {
                                echo '<div class="panel-body" style="padding-bottom: 0;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-danger panel-danger">
                                                                        <div class="panel-heading text-center pull-center">
                                                                            <h3>Não foram encontratos contatos,</h3>
                                                                            <h3>recarregue a página daqui a alguns segundos</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                            }
                            /*
                                        Verifica se o cara não ta agendado e adicionar não existe, ou seja ta liberado
                                    */
                            if ($agenda_passada == 0 && $adicionar == 0) {

                                $id_contatos_pesquisa = $clientes[0]['id_contatos_pesquisa'];

                                $registro_agenda = DBRead('', 'tb_agendamento_pesquisa', "WHERE id_contatos_pesquisa = '" . $id_contatos_pesquisa . "' AND telefone");
                                /*
                                        Registro agenda serve apenas para pegar o telefone novo, que esta na tabela agendamentos
                                    */
                                if ($registro_agenda) {
                                    $nome = $clientes[0]['nome'];
                                    $telefone = $registro_agenda[0]['telefone'];
                                    echo "<input type='hidden' name='id_contatos_pesquisa' id='id_contatos_pesquisa' value='" . $clientes[0]['id_contatos_pesquisa'] . "' />";
                                } else {
                                    $nome = $clientes[0]['nome'];
                                    $telefone = $clientes[0]['telefone'];
                                    echo "<input type='hidden' name='id_contatos_pesquisa' id='id_contatos_pesquisa' value='" . $clientes[0]['id_contatos_pesquisa'] . "' />";
                                }
                            }
                        }
                        /*
                                Caso exista contato, entra aqui e faz a pesquisa (Aqui que mostra as questões)
                            */
                        if ($id_contatos_pesquisa || $adicionar) {
                            if ($id_contatos_pesquisa) {
                                $dados_contatos = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '" . $id_contatos_pesquisa . "'");
                                $label1 = $dados_contatos[0]['label1'];
                                $label2 = $dados_contatos[0]['label2'];
                                $label3 = $dados_contatos[0]['label3'];
                                $dado1 = $dados_contatos[0]['dado1'];
                                $dado2 = $dados_contatos[0]['dado2'];
                                $dado3 = $dados_contatos[0]['dado3'];
                            ?>
                                <div class="panel-body" style="padding-bottom: 0;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class='col-md-6'>
                                                            <div class="form-group">
                                                                <label>Contato: </label>
                                                                <input type='text' class='form-control input-sm' value='<?= $nome ?>' readonly />
                                                            </div>
                                                        </div>
                                                        <div class='col-md-6'>
                                                            <div class="form-group">
                                                                <label>Telefone: </label>
                                                                <div class='input-group input-group-sm'>
                                                                    <input id='fone' type='text' class='form-control phone' value='<?= $telefone ?>' readonly />
                                                                    <span class='input-group-btn'>
                                                                        <?php if ($permissao == 1) {
                                                                            echo '<button class="btn btn-primary" title="Ligar" disabled><span class="glyphicon glyphicon-earphone"></span>&nbsp;</button>';
                                                                        } else {
                                                                            echo "<a href=\"#\"  onclick=\"ligar_softphone('" . $ramal_retorno . $telefone . "')\" class=\"btn btn-primary\" title=\"Ligar\"><span class=\"glyphicon glyphicon-earphone\"></span>&nbsp;</a>";
                                                                        } ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php
                                                    echo "<input type='hidden' name='id_contatos_pesquisa' id='id_contatos_pesquisa' value='" . $id_contatos_pesquisa . "'>";
                                                }
                                                    ?>
                                                    <?php
                                                    if ($dado_adicional1) {
                                                        if ($dado1) :
                                                            if ($label1) {
                                                                ?>
                                                                <div class='col-md-4'>
                                                                    <label><?= $label1 ?>:</label>
                                                                    <input type='text' readonly class='form-control input-sm' value='<?= $dado1 ?>' />
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class='col-md-4'>
                                                                    <label><?= $dado_adicional1 ?>:</label>
                                                                    <input type='text' readonly class='form-control input-sm' value='<?= $dado1 ?>' />
                                                                </div>
                                                            <?php
                                                            }
                                                        endif;
                                                    }
                                                    if ($dado_adicional2) {
                                                        if ($dado2) :
                                                            if ($label2) {
                                                            ?>
                                                                <div class='col-md-4'>
                                                                    <label><?= $label2 ?>:</label>
                                                                    <input type='text' readonly class='form-control input-sm' value='<?= $dado2 ?>' />
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class='col-md-4'>
                                                                    <label><?= $dado_adicional2 ?>:</label>
                                                                    <input type='text' readonly class='form-control input-sm' value='<?= $dado2 ?>' />
                                                                </div>
                                                            <?php
                                                            }
                                                        endif;
                                                    }
                                                    if ($dado_adicional3) {
                                                        if ($dado3) :
                                                            if ($label3) {
                                                            ?>
                                                                <div class='col-md-4'>
                                                                    <label><?= $label3 ?>:</label>
                                                                    <input type='text' readonly class='form-control input-sm' value='<?= $dado3 ?>' />
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class='col-md-4'>
                                                                    <label><?= $dado_adicional3 ?>:</label>
                                                                    <input type='text' readonly class='form-control input-sm' value='<?= $dado3 ?>' />
                                                                </div>
                                                    <?php
                                                            }
                                                        endif;
                                                    }
                                                    ?>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    /*
                            Mensagem caso o contato esteja agendado e atrasado
                        */
                                    if ($agenda_passada == 1) {
                                        echo "
                                        <div class='panel panel-danger panel-danger'>
                                            <div class='panel-heading text-center pull-center perguntas'>";

                                                $data_agendamento = converteDataHora($data_agendamento);
                                                echo "<h3 text-center>Este cliente estava agendado para dia " . $data_agendamento[0] . "" . $data_agendamento[1] . "" . $data_agendamento[2] . "" . $data_agendamento[3] . "" . $data_agendamento[4] . "" . $data_agendamento[5] . "" . $data_agendamento[6] . "" . $data_agendamento[7] . "" . $data_agendamento[8] . "" . $data_agendamento[9] . " às " . $data_agendamento[10] . "" . $data_agendamento[11] . "" . $data_agendamento[12] . "" . $data_agendamento[13] . "" . $data_agendamento[14] . "" . $data_agendamento[15] . ".</h3>";
                                            echo "
                                            </div>
                                            <input type='hidden' name='id_agenda' id='id_agenda' value='' />
                                        </div>";
                                        ?>
                                        
                                        <?php if($obs_falha && $obs_falha != ''){?>

                                            <div class='panel panel-info'>
                                                <div class='panel-heading text-center pull-center'>
                                                    <h4 text-center>Observação: <?= $obs_falha ?></h4>               
                                                </div>
                                            </div>

                                        <?php } ?>
                                    <?php 
                                    }

                                    /*
                          Questões da pesquisa
                        */
                                    $cont=0;
                                    if ($dados_perguntas) {
                                        foreach ($dados_perguntas as $dado) {
                                            echo "<input type='hidden' name='id_pergunta_pesquisa[]' value='" . $dado['id_pergunta_pesquisa'] . "' />";
                                            echo "<input type='hidden' name='pergunta[]' value='" . $dado['descricao'] . "' />";
                                            echo "<input type='hidden' name='resposta_pai' class='resposta_pai' value='" . $dado['resposta_pai'] . "' />";

                                            $tipo = $dado['id_tipo_resposta_pesquisa'];

                                            echo "<div class='panel panel-default panel panel-pergunta " . $dado['resposta_pai'] . "'>";
                                            echo "<div class='panel-heading perguntas'>";
                                            echo "<strong>* " . $dado['descricao'] . '</strong>';
                                            echo "</div>";

                                            echo "<div class='panel-body'>";
                                            $dados_resposta = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '" . $dado['id_pergunta_pesquisa'] . "'");

                                            if ($tipo == 1) {
                                                echo "<textarea class='form-control respostas'  name='pergunta_" . $dado['id_pergunta_pesquisa'] . "[]'></textarea>";
                                            }

                                            foreach ($dados_resposta as $dado_resposta) {

                                                if ($tipo == 2) {
                                                    echo "<div class='radio'>";
                                                    echo "<label>";

                                                    echo "<input type='radio' clicado='false' class='respostas' value='" . $dado_resposta['descricao'] . "' id='" . $dado_resposta['id_resposta_pesquisa'] . "' name='pergunta_" . $dado['id_pergunta_pesquisa'] . "[]'>";

                                                    echo "<input type='hidden' class='id_resposta_pai' value='" . $dado_resposta['id_resposta_pesquisa'] . "' />";

                                                    echo $dado_resposta['descricao'];
                                                    echo "</label>";
                                                    echo "</div>";
                                                }
                                                if ($tipo == 3) {
                                                    echo "<div class='checkbox'>";
                                                    echo "<label>";

                                                    echo "<input type='checkbox' class='resposta_checkbox' name='pergunta_" . $dado['id_pergunta_pesquisa'] . "[]' checkbox_resposta value='" . $dado_resposta['descricao'] . "' id='" . $dado_resposta['id_resposta_pesquisa'] . "' >";

                                                    echo "<input type='hidden' name='resposta[]' class='checkbox_hidden' />";

                                                    echo "<input type='hidden' class='id_resposta_pai' value='" . $dado_resposta['id_resposta_pesquisa'] . "' />";
                                                    echo $dado_resposta['descricao'];
                                                    echo "</label>";
                                                    echo "</div>";
                                                }
                                                if ($tipo == 4) {
                                                    echo "<div class='radio'>";
                                                    echo "<label>";

                                                    echo "<input type='radio' clicado='false' class='respostas' value='" . $dado_resposta['descricao'] . "' id='" . $dado_resposta['id_resposta_pesquisa'] . "' name='pergunta_" . $dado['id_pergunta_pesquisa'] . "[]'>";

                                                    echo "<input type='hidden' class='id_resposta_pai' value='" . $dado_resposta['id_resposta_pesquisa'] . "' />";

                                                    echo $dado_resposta['descricao'];
                                                    echo "</label>";
                                                    echo "</div>";
                                                }
                                            }
                                            if ($dado['observacao']) {
                                                echo "<strong>" . nl2br($dado['observacao']) . "</strong>";
                                            }
                                            echo "<hr>";
                                               echo "<button name='botao_observacao' data-toggle='collapse' aria-expanded='false' data-target='#accordionRelatorio_".$cont."' value='".$cont."' class='btn btn-xs btn-info' type='button' title='Adicionar observação'><i id='i_collapse_".$cont."' class='fa fa-plus' botao='fa-plus'></i></button>";
                                                echo "<div class='panel-body collapse' id='accordionRelatorio_".$cont."'>";
                                                    echo "<label>";
                                                    echo "Adicionar observação:";
                                                    echo "</label>";
                                                    echo "<textarea class='form-control'  name='observacao_" . $dado['id_pergunta_pesquisa'] . "[]'></textarea>";
                                                echo "</div>"; 
                                            echo "</div>";
                                            echo "</div>";
                                            $cont++;
                                        }
                                    } else {
                                        echo '
                                        <div class="panel-body" style="padding-bottom: 0;">
                                            <div class="row">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-warning">
                                                                        <div class="panel-heading text-center pull-center">
                                                                            <h3>Não foram cadastradas perguntas!</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>';
                                    }


                                    //___________________VERIFICA SE O CONTATO PODE SER AGENDANDO OU NÃO
                                    $valor_agendamento = 7;
                                    $flag_mostra_agendamento = 1;
                                    if ($id_contatos_pesquisa) {
                                        $dados_qtd_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = $id_contatos_pesquisa");
                                        if (($qtd_tentativas_pesquisa - 1) == $dados_qtd_contato[0]['qtd_tentativas_cliente']) {
                                            $flag_mostra_agendamento = 0;
                                            $valor_agendamento = 10;
                                        }
                                    }

                                    ?>

                                    <div class="row" id="row_conteudo_pesquisa">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body" id="conteudo_pesquisa">

                                                    <div class="collapse" id="opcoes-falha">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="chamou_cair" value="1">
                                                                            Chamou até cair
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="numero_nao_existe" value="2">
                                                                            Número não existe
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                    <label>
                                                                        <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="numero_errado" value="3">
                                                                            Número errado
                                                                    </label>
                                                                    </div>
                                                                </div> -->
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="caixa_postal" value="4">
                                                                            Caixa postal/fora de área
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="numero_ocupado" value="5">
                                                                            Número ocupado
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="ligacao_caiu" value="6">
                                                                            Ligação caiu
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha esconde_nao_ligar" name="falha" id="interferencia" value="9">
                                                                            Interferência
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="radio_opcoes_falha esconde_nao_ligar" name="falha" id="ligar_tarde" value="<?=$valor_agendamento?>">
                                                                            <span style='color:orange'>Ligar mais tarde</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="esconde_agendamento radio_opcoes_falha" name="falha" id="nao_ligar_novamente" value="8">
                                                                            <span style='color: red'>Não ligar novamente</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="nao_ligar">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <div class='row'>
                                                                    <div class='col-md-12'>
                                                                        <label>Observação (Motivo):</label>
                                                                        <textarea class='campos-nao-ligar form-control input-sm' name='obs_nao_ligar'></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    

                                                    <div id="agendar">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <?php
                                                                
                                                                if ($flag_mostra_agendamento) {
                                                                ?>
                                                                    <div class='row'>
                                                                        <div class='col-md-4'>
                                                                            <div class="form-group">
                                                                            <label>*Data:</label>
                                                                            <input class='campos-agendar form-control date calendar input-sm' name='data_agendar' id= 'data_agendar' type='text' />
                                                                            </div>
                                                                        </div>
                                                                        <div class='col-md-4'>
                                                                            <div class="form-group">
                                                                            <label>*Hora:</label>
                                                                            <input id="campo-hora" class='campos-agendar form-control input-sm' name='hora_agendar' type='time' />
                                                                            </div>
                                                                        </div>
                                                                        <div class='col-md-4'>
                                                                            <div class="form-group">
                                                                            <label>Telefone (opcional):</label>
                                                                            <input id="campo-telefone" class="form-control input-sm phone" name='telefone_agendar' type='text' />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class='row' id="div_obs_nao_ligar">
                                                                        <div class='col-md-12'>
                                                                            <div class="form-group">
                                                                            <label>Observação (opcional):</label>
                                                                            <textarea class='form-control input-sm' name='obs_ligar_mais_tarde'><?=$clientes[0]['obs_falha']?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                } else {
                                                                    echo '<div class="alert alert-warning text-center" style="margin:0;">Contato solicitou para ligar em outro horário, porém é a última tentativa! Apenas clique em salvar.</div>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-footer">
                                    <div class="row">
                                        <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                            <input type="hidden" id="operacao" value="inserir" name="operacao" />
                                            <input type='hidden' value="<?= $adicionar ?>" name='adicionar' />
                                            <?php if ($permissao == 1) { ?>
                                                <button class='btn btn-primary' name='salvar' id='ok' type='submit' disabled><i class='fa fa-floppy-o'></i> Salvar</button>
                                                <button class='button_falha btn btn-warning' type='button' data-toggle='collapse' data-target='#opcoes-falha' aria-expanded='false' aria-controls='opcoes-falha' disabled><i class='fa fa-phone-square' aria-hidden='true'></i> Ligação falhou</button>
                                            <?php } else { ?>
                                                <button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>
                                                <button class='button_falha btn btn-warning' type='button' data-toggle='collapse' data-target='#opcoes-falha' aria-expanded='false' aria-controls='opcoes-falha'><i class='fa fa-phone-square' aria-hidden='true'></i> Ligação falhou</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                </form>
            </div>
        </div>





<?php

                        }

                        /*
  Fim do if cliente ou adicionar
*/
                    } else {
                        echo '<div class="panel-body" style="padding-bottom: 0;">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-danger panel-danger">
                                        <div class="panel-heading text-center pull-center">
                                            <h3>Não foram encontratos contatos,</h3>
                                            <h3>recarregue a página daqui a alguns segundos</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
                    }
?>
    </div>
</div>
</div>
<script>

    $('#data_agendar').on('change', function() {

        if('<?=$prazo_termino?>' != null){
            var prazo_termino = '<?=$prazo_termino?>';
            prazo_termino = prazo_termino.split("-");
            prazo_termino = prazo_termino[0]+''+prazo_termino[1]+''+prazo_termino[2];

            var agendamento = $(this).val();
            agendamento = agendamento.split("/");
            agendamento = agendamento[2]+''+agendamento[1]+''+agendamento[0];

            var prazo_termino_convertido = '<?=$prazo_termino?>';
            prazo_termino_convertido = prazo_termino_convertido.split("-");
            prazo_termino_convertido = prazo_termino_convertido[2]+'/'+prazo_termino_convertido[1]+'/'+prazo_termino_convertido[0];
        
            if(agendamento > prazo_termino){
                $(this).val('');
                alert('O prazo de término da pesquisa é '+prazo_termino_convertido+', os agendamentos não podem serem feitos após esta data!');

            }
       }
    
       
    });

    $('button[name=botao_observacao]').on('click', function() {
       var id_div = 'accordionRelatorio_'+$(this).val();

       var id_collapse = 'i_collapse_'+$(this).val();
       var botao_collapse = $('#i_collapse_'+$(this).val()).attr('botao');

       if(botao_collapse == 'fa-plus'){
        $('#i_collapse_'+$(this).val()).attr('botao','fa-minus');
        $('#i_collapse_'+$(this).val()).removeClass(botao_collapse);
        $('#i_collapse_'+$(this).val()).addClass('fa-minus');
       }else{
        $('#i_collapse_'+$(this).val()).attr('botao','fa-plus');
        $('#i_collapse_'+$(this).val()).removeClass(botao_collapse);
        $('#i_collapse_'+$(this).val()).addClass('fa-plus');
       }       
    });

    $(document).ready(function() {
        $('#row_conteudo_pesquisa').hide();
    });

    $('.respostas').prop('required', true);

    $('.button_falha').on('click', function() {
        $('.respostas').prop('required', false);
        $('.radio_opcoes_falha').prop('required', true);

        $('#ok').prop('name', 'falhou');
        $('#operacao').prop('value', 'falhou');

        $('.button_falha').html("<i class='fa fa-times' aria-hidden='true'></i> Cancelar falha").removeClass('btn-warning').addClass('btn-danger');

        $('#row_conteudo_pesquisa').show();

    });

    $('#ligar_tarde').on('click', function() {
        
        if($(this).val() == 7){
            $('#operacao').prop('value', 'agendar');
            $('.campos-agendar').prop('required', true);
            $('#ok').prop('name', 'agendar');
        }
    });

    $('#agendar').hide();

    $('#ligar_tarde').on('click', function() {
        $('#nao_ligar').fadeOut();
        $('#agendar').fadeIn();
        $('#div_obs_nao_ligar').fadeIn();
    });

    $('.esconde_agendamento').on('click', function() {
        $('#agendar').fadeOut();
        $('#ok').prop('name', 'falhou');
        $('#operacao').prop('value', 'falhou');
        $('.campos-agendar').prop('required', false);
        $('#div_obs_nao_ligar').fadeOut();
    });

    //___________________________________________________

    $('#nao_ligar').on('click', function() {
        $('#ok').prop('name', 'nao_ligar');
        $('#operacao').prop('value', 'falhou');
    });

    $('#nao_ligar').hide();

    $('#nao_ligar_novamente').on('click', function() {
        $('#nao_ligar').fadeIn();
    });

    $('.esconde_nao_ligar').on('click', function() {
        $('#nao_ligar').fadeOut();
        $('#ok').prop('name', 'falhou');
        $('#operacao').prop('value', 'falhou');

        if ($("#ligar_tarde").is(':checked')) {
            if($("#ligar_tarde").val() == 7){
                $('#ok').prop('name', 'agendar');
                $('#operacao').prop('value', 'agendar');
            }
        }
    });

    //___________________________________________________

    $('#opcoes-falha').on('hide.bs.collapse', function() {
        $('#agendar').fadeOut();
        $('#nao_ligar').fadeOut();
        $('#chamou_cair').prop('checked', true);
        $('.campos-agendar').prop('required', false);
        $('.respostas').prop('required', true);

        $('#ok').prop('name', 'salvar');
        $('#operacao').prop('value', 'inserir');

        $('.button_falha').html("<i class='fa fa-phone-square' aria-hidden='true'></i> Ligação falhou").removeClass('btn-danger').addClass('btn-warning');

        $('#row_conteudo_pesquisa').hide();

    });

    //AJAX
    function call_busca_ajax(pagina) {
        var inicia_busca = 1;
        var id_contatos_pesquisa = $('#id_contatos_pesquisa').val();
        var id_usuario = $('#id_usuario').val();
        var parametros = {
            'id_contatos_pesquisa': id_contatos_pesquisa,
            'id_usuario': id_usuario
        };
        busca_ajax('class/AtualizaTempoPesquisa', 'resultado_busca', parametros);
    }

    call_busca_ajax();
    setInterval(function() {
        call_busca_ajax();
    }, 10000);
    //FIM AJAX

    $(document).on('click', '#solicita_ajuda', function() {
        if (confirm('Deseja realmente solicitar ajuda?')) {
            var id_contrato_plano_pessoa = <?php echo $id_contrato; ?>;
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=SolicitaAjuda.php",
                dataType: "json",
                data: {
                    id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $("#solicita_ajuda").html("<i class='fa fa-exclamation' aria-hidden='true'></i> Ajuda solicitada").removeClass("btn-info").addClass("btn-danger").addClass("disabled");
                }
            });
        }
    });

    var verifica_ajuda = function() {
        $.ajax({
            cache: false,
            type: "POST",
            data: {
                verificar: '1',
                token: '<?= $request->token ?>'
            },
            url: '/api/ajax?class=SolicitaAjuda.php',
            success: function(data) {
                if (data == '0') {
                    $("#solicita_ajuda").html("<i class='fa fa-question' aria-hidden='true'></i> Solicitar ajuda").removeClass("btn-danger").addClass("btn-info").removeClass("disabled");
                }
            }
        });
        setTimeout(function() {
            verifica_ajuda();
        }, 5000);
    };
    verifica_ajuda();

    $(document).on('submit', '#acesso_equipamento_form', function() {

        $('.radio_resposta').prop('type', 'checkbox');
        $('.radio_resposta').prop('name', 'resposta[]');

        modalAguarde();
    });
</script>