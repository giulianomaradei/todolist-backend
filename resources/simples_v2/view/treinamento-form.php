<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int) $_GET['alterar'];

    $dados_treinamento = DBRead('', 'tb_treinamento', "WHERE id_treinamento = $id");
    $nome = $dados_treinamento[0]['nome'];
    $data_inicio = converteData($dados_treinamento[0]['data_inicio']);
    $data_fim = converteData($dados_treinamento[0]['data_fim']);
    $objetivo = $dados_treinamento[0]['objetivo'];
    $carga_horaria = $dados_treinamento[0]['carga_horaria'];
    $avaliar_em = converteData($dados_treinamento[0]['avaliar_em']);
    $descricao = $dados_treinamento[0]['descricao'];

    $dados_perfil_sistema = DBRead('', 'tb_treinamento_perfil_sistema', "WHERE id_treinamento = $id", 'id_perfil_sistema');
    $dados_responsaveis = DBRead('', 'tb_treinamento_responsavel', "WHERE id_treinamento = $id", 'id_usuario');
    $dados_participantes = DBRead('', 'tb_treinamento_participante', "WHERE id_treinamento = $id", 'id_usuario');

} else {

    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $data_treinamento = '';
    $objetivo = '';
    $carga_horaria = '';
    $avaliar_em = '';
    $descricao = '';
}

$usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND b.id_pessoa != 2 ORDER BY b.nome ASC", "b.nome, a.id_usuario");

?>

<style>
    .some-container .tooltip .tooltip-inner {
        width: 37em;
        max-width: 100%;
        white-space: pre-line;
    }

    .select2 {
        width: 100% !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> treinamento:</h3>
                    <?php if (isset($_GET['alterar'])) {
                        echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Topico.php?excluir= $id&token=". $request->token ."\" onclick=\"if(!confirm('Tem certeza que deseja excluir o registro?')){ return false; } else { modalAguarde(); }\"></a></div>";
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=Treinamento.php" id="treinamento_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class='col-md-6'>
                                <div class="form-group some-container">
                                    <label>Nome:</label>
                                    <input type="text" class="form-control input-sm" name="nome" id="nome" value="<?= $nome ?>" required>
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class="form-group">
                                    <label for="avaliar_em">*Data início:</label>
                                    <input class="form-control input-sm date calendar hasDatepicker" id="data_inicio" name="data_inicio" value="<?= $data_inicio ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class="form-group">
                                    <label for="avaliar_em">*Data fim:</label>
                                    <input class="form-control input-sm date calendar hasDatepicker" id="data_fim" name="data_fim" value="<?= $data_fim ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-md-4'>
                                <div class="form-group">
                                    <label>*Objetivo do treinamento:</label>
                                    <select class="form-control input-sm" name="objetivo" id="objetivo">
                                        <option value="" <?=$objetivo == "" ? "selected" : "";?>>Selecione</option>
                                        <option value="1" <?=$objetivo == "1" ? "selected" : "";?>>Qualificação</option>
                                        <option value="2" <?=$objetivo == "2" ? "selected" : "";?>>Reciclagem</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class="form-group">
                                    <label for="tempo">*Carga horária (minutos):</label>
                                    <input class="form-control input-sm number_int" id="carga_horaria" name="carga_horaria" value="<?= $carga_horaria ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class="form-group">
                                    <label for="avaliar_em">*Avaliar em:</label>
                                    <input class="form-control input-sm date calendar hasDatepicker" id="avaliar_em" name="avaliar_em" value="<?= $avaliar_em ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label>*Selecionar perfil:</label><br />
                                    <select class="js-example-basic-multiple chamado_perfil" id="perfil_sistema" name="perfil_sistema[]" multiple="multiple">
                                        <?php
                                        $dados_perfil = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema != 19 AND status = 1 ORDER BY nome ASC");
                                        if ($dados_perfil) {
                                            foreach ($dados_perfil as $conteudo) {
                                                echo "<option value='" . $conteudo['id_perfil_sistema'] . "'>" . $conteudo['nome'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if ($operacao != 'alterar') { ?>

                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Responsável(eis) pelo treinamento:</label>
                                    <select class="js-example-basic-multiple input-sm" id="responsaveis" name="responsaveis[]" multiple="multiple" required>
                                        <?php
                                        if ($usuarios) {
                                            foreach ($usuarios as $usuarios1) {
                                                $idUsuario = $usuarios1['id_usuario'];
                                                $nome = $usuarios1['nome'];
                                                echo "<option value='$idUsuario'>$nome</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <div class='table-responsive' style="max-height: 520px; overflow-y:auto;">

                                        <label>*Participante(s):</label>
                                        <label class='pull-right' style='margin: 0 !important;'>Todos dos perfis selecionados&darr;</label>

                                        <div class='input-group'>
                                            <select class="js-example-basic-multiple input-sm" id="participantes" name="participantes[]" multiple="multiple" required>
                                                <?php
                                                if ($usuarios) {
                                                    foreach ($usuarios as $usuarios2) {
                                                        $idUsuario = $usuarios2['id_usuario'];
                                                        $nome = $usuarios2['nome'];
                                                        echo "<option value='$idUsuario'>$nome</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-addon">
                                                <input type="checkbox" class='input-sm' id="todos_participantes">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php } ?>

                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Temas abordados/Descrição:</label>
                                    <textarea required name="descricao" id="descricao" class="form-control ckeditor conteudo" rows="5"><?= $descricao ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });

    $(document).on('submit', '#treinamento_form', function() {

        var nome = $('#nome').val();
        var data_inicio = $('#data_inicio').val();
        var data_fim = $('#data_fim').val();
        var objtivo = $('#objetivo').val();
        var carga_horaria = $('#carga_horaria').val();
        var avaliar_em = $('#avaliar_em').val();
        var perfil_sistema = $('#perfil_sistema').val();
        var responsaveis = $('#responsaveis').val();
        var participantes = $('#participantes').val();
        var descricao = $('#descricao').val();

        if (nome == '') {
            alert('Informe o nome do treinamento!');
            return false;
        }

        if (data_inicio == '') {
            alert('Informe a data de início do treinamento!');
            return false;
        }

        if (data_fim == '') {
            alert('Informe a data do fim do treinamento!');
            return false;
        }

        if (objtivo == '') {
            alert('Informe o objetivo do treinamento!');
            return false;
        }

        if (carga_horaria == '') {
            alert('Informe a carga_horaria do treinamento!');
            return false;
        }

        if (avaliar_em == '') {
            alert('Informe a data da avaliação do treinamento!');
            return false;
        }

        if (perfil_sistema == '') {
            alert('Informe os perfis envolvidos no treinamento!');
            return false;
        }

        if (responsaveis == '') {
            alert('Informe os responsáveis pelo treinamento!');
            return false;
        }

        if (participantes == '') {
            alert('Informe os participantes do treinamento!');
            return false;
        }

        if (descricao == '') {
            alert('Informe a descrição do treinamento!');
            return false;
        }

        modalAguarde();
    });

    $('#todos_participantes').on('click', function() {

        var perfil_sistema = $('#perfil_sistema').val();

        if ($('#todos_participantes').is(":checked") && perfil_sistema != null) {

            $.ajax({
                url: "/api/ajax?class=BuscaUsuarioPerfil.php",
                type: "POST",
                dataType: "json",
                data: {
                    parametros: {
                        'perfis': perfil_sistema,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data) {

                    if (data != null) {

                        var i;
                        var perfis = [];
                        for (i = 0; i < data.length; i++) {
                            perfis.push(data[i].id_usuario);
                        }

                        function insereParticipante2(data) {

                            console.log(data);

                            select2participante = $('#participantes').select2();
                            //verifica quais estão marcados
                            dadosParticipanteJson = data;

                            if (dadosParticipanteJson != null) {
                                dadosParticipanteArray = [];
                                dadosParticipanteJson.forEach(function(i) {
                                    dadosParticipanteArray.push(i.id_usuario);
                                });
                                select2participante.val(dadosParticipanteArray).trigger("load");
                                select2participante.trigger("change");
                            }
                        }

                        insereParticipante2(data);

                    } else {
                        alert('erro!');
                    }
                }
            });

        } else {
            $('#todos_participantes').prop("checked", false);
        }

    });

    $('#perfil_sistema').on('change', function() {
        $('#todos_participantes').prop("checked", false);
    });

    function inserePerfilSistema() {
        select2setor = $('#perfil_sistema').select2();
        //verifica quais estão marcados
        dadosSetorJson = <?php echo json_encode($dados_perfil_sistema) ?>;

        if (dadosSetorJson != null) {
            dadosSetorArray = [];
            dadosSetorJson.forEach(function(i) {
                dadosSetorArray.push(i.id_perfil_sistema);
            });
            select2setor.val(dadosSetorArray).trigger("load");
        }
    }

    function insereResponsavel() {
        select2responsavel = $('#responsaveis').select2();
        //verifica quais estão marcados
        dadosResponsavelJson = <?php echo json_encode($dados_responsaveis) ?>;

        if (dadosResponsavelJson != null) {
            dadosResponsavelArray = [];
            dadosResponsavelJson.forEach(function(i) {
                dadosResponsavelArray.push(i.id_usuario);
            });
            select2responsavel.val(dadosResponsavelArray).trigger("load");
        }
    }

    function insereParticipante() {
        select2participante = $('#participantes').select2();
        //verifica quais estão marcados
        dadosParticipanteJson = <?php echo json_encode($dados_participantes) ?>;

        if (dadosParticipanteJson != null) {
            dadosParticipanteArray = [];
            dadosParticipanteJson.forEach(function(i) {
                dadosParticipanteArray.push(i.id_usuario);
            });
            select2participante.val(dadosParticipanteArray).trigger("load");
        }
    }

    inserePerfilSistema();
    insereResponsavel();
    insereParticipante();
</script>