<?php
require_once(__DIR__."/../class/System.php");

$id_contrato_plano_pessoa = $_GET['contrato'];

$nome_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "b.nome");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div id='alerta' role="alert"></div>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Valores default: <?= $nome_contrato[0]['nome'] ?></h3>
                </div>
                <form action="" method="POST">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Área do problema</label>
                                    <select class="form-control input-sm" name="id_area_problema" id="id_area_problema" required>
                                        <option value='0'>Selecione a área do problema</option>
                                        <?php
                                        $dados = DBRead('', 'tb_area_problema', "ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $idSelect = $conteudo['id_area_problema'];
                                                $nomeSelect = $conteudo['nome'];
                                                $selected = $id_area_problema == $idSelect ? "selected" : "";
                                                echo "<option value='$idSelect'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                        ?>
                                        <option value='-1'>Todos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Subárea do problema</label>
                                    <select class="form-control input-sm" name="id_subarea_problema" id="id_subarea_problema" required>
                                        <option value='0'></option>
                                        <?php
                                        if ($id_area_problema) {
                                            $dados = DBRead('', 'tb_subarea_problema', "WHERE id_area_problema = '$id_area_problema' ORDER BY descricao ASC");
                                            if ($dados) {
                                                foreach ($dados as $conteudo) {
                                                    $idSelect = $conteudo['id_subarea_problema'];
                                                    $descricaoSelect = $conteudo['descricao'];
                                                    $selected = $id_subarea_problema == $idSelect ? "selected" : "";
                                                    echo "<option value='$idSelect'".$selected.">$descricaoSelect</option>";
                                                }
                                            }
                                        } else {
                                            echo '<option value="0">Selecione uma área do problema antes!</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                            $id_integracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
                            $tb_integracao_campos_default = DBRead('', 'tb_integracao_campos_default', "WHERE id_integracao = '" . $id_integracao[0]['id_integracao'] . "'");

                            foreach ($tb_integracao_campos_default as $key => $conteudo) :
                                //Define um id no container da seleção de setor para posteriormente ser mostrado ou escondido dependendo da classificação
                                if ($tb_integracao_campos_default[$key]['codigo_campo'] == 'setor') {
                                    $class_setor = "id='default-setor'";
                                } else {
                                    $class_setor = '';
                                }
                                //Define um id no container da seleção de departamento para posteriormente ser mostrado ou escondido dependendo da classificação
                                if ($tb_integracao_campos_default[$key]['codigo_campo'] == 'departamento') {
                                    $class_departamento = "id='default-departamento'";
                                } else {
                                    $class_departamento = '';
                                }
                                //Define um id no container da seleção de processo para posteriormente ser mostrado ou escondido dependendo da classificação
                                if ($tb_integracao_campos_default[$key]['codigo_campo'] == 'processo') {
                                    $class_processo = "id='default-processo'";
                                } else {
                                    $class_processo = '';
                                }
                            ?>
                                <div class="col-md-4" <?= $class_setor ?> <?= $class_departamento ?> <?= $class_processo ?>>
                                    <div class="form-group">
                                        <label><?= $tb_integracao_campos_default[$key]['descricao_campo'] ?>:</label>
                                        <select class="form-control input-sm" name='<?= $tb_integracao_campos_default[$key]['name_campo'] ?>' idcampo='<?= $tb_integracao_campos_default[$key]['id_integracao_campos_default'] ?>' id='<?= $tb_integracao_campos_default[$key]['codigo_campo'] ?>'></select>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>
                        <br>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <?php
                                echo "<button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>";
                                ?>
                                <button class="btn btn-default" type="button" onclick="voltar()"><i class="fas fa-angle-left"></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel-group" id="accordionVinculos" role='tablist'>
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title text-left pull-left">Valores default cadastrados para <?= $nome_contrato[0]['nome'] ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Buscar por área do problema</label>
                                    <select class="form-control input-sm" name="id_area_problema_busca" id="id_area_problema_busca" onchange="call_busca_ajax();">
                                        <option value='0'>Todas</option>
                                        <?php
                                        $dados = DBRead('', 'tb_area_problema', "ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $idSelect = $conteudo['id_area_problema'];
                                                $nomeSelect = $conteudo['nome'];
                                                $selected = $id_area_problema == $idSelect ? "selected" : "";
                                                echo "<option value='$idSelect'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="resultado_busca"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function voltar() {
        window.history.back();
    }

    $("#setor").on("change", function() {
        //console.log("onchange setor");
        $("#departamento").val("0");
    });

    $("#departamento").on("change", function() {
        //console.log("onchange departamento");
        $("#setor").val("0");
    });

    /** Seleção de área e subárea */
    function selectAreaSubareaProblema(id_area_problema) {
        $("select[name=id_subarea_problema]").html('<option value="0">Carregando...</option>');
        $.post("/api/ajax?class=SelectAreaSubareaProblema.php", {
                area_problema: id_area_problema,
                token: '<?= $request->token ?>'
            },
            function(valor) {
                $("select[name=id_subarea_problema]").html(valor);
            }
        )
    }

    $(document).on('change', 'select[name=id_area_problema]', function() {
        selectAreaSubareaProblema($(this).val());
    });
    ////////////////////////////////

    var id_contrato_plano_pessoa = <?= $id_contrato_plano_pessoa ?>;

    busca_campos(id_contrato_plano_pessoa);

    $("#id_subarea_problema").on("change", function() {

        $("#assunto").val(0);
        $("#setor").val(0);
        $("#filial").val(0);
        $("#tecnico").val(0);
        $("#departamento").val(0);
        $("#processo").val(0);
        $("#prioridade").val(0);
        $("#origem").val(0);
        $("#classificacao").val(0);

        $("#default-departamento").hide();
        $("#default-processo").hide();
        $("#default-setor").hide();

        let id_subarea_problema = $(this).val();

        $("#classificacao").on("change", function() {

            if ($(this).val() == 1) {
                opcoes_prioridade = `<option value=''></option><option value='B'>Baixa</option><option value='N'>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>`;
                $("#prioridade").html(opcoes_prioridade);

            } else {
                opcoes_prioridade = `<option value=''></option><option value='B'>Baixa</option><option value='M'>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>`;
                $("#prioridade").html(opcoes_prioridade);
            }
        });

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoCamposDefault.php",
            dataType: "json",
            data: {
                acao: "busca_valores",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                id_subarea_problema: id_subarea_problema,
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                //console.log(data);
                $.each(data, function(i) {

                    if (data[i].codigo_campo == 'assunto') {
                        $("#assunto").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'setor') {
                        $("#setor").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'filial') {
                        $("#filial").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'tecnico') {
                        $("#tecnico").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'prioridade') {
                        //console.log(data[i].value_default);
                        sessionStorage.setItem('value_default_prioridade', data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'departamento') {
                        $("#departamento").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'processo') {
                        $("#processo").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'origem') {
                        $("#origem").val(data[i].value_default);
                    }

                    if (data[i].codigo_campo == 'classificacao') {
                        $("#classificacao").val(data[i].value_default);

                        if (data[i].value_default == 1) {
                            $("#default-departamento").hide();
                            $("#default-setor").show();
                            $("#default-processo").hide();

                            opcoes_prioridade = `<option value=''></option><option value='B'>Baixa</option><option value='N'>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>`;
                            $("#prioridade").html(opcoes_prioridade);

                        } else if(data[i].value_default == 2){
                            $("#default-departamento").show();
                            $("#default-setor").hide();
                            $("#default-processo").show();

                            opcoes_prioridade = `<option value=''></option><option value='B'>Baixa</option><option value='M'>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>`;
                            $("#prioridade").html(opcoes_prioridade);
                        }

                    }

                    if (data[i].codigo_campo == 'id_area_problema') {
                        $("#id_area_problema").val(data[i].value_default);
                    }
                });

                if (sessionStorage.getItem('value_default_prioridade')) {
                    $("#prioridade").val(sessionStorage.getItem('value_default_prioridade'));
                    sessionStorage.removeItem('value_default_prioridade');
                }

            }
        });

    });

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#ok").on("click", function(event) {

        event.preventDefault();

        var area_problema = $('#id_area_problema').val();
        var sub_area_problmea = $('#id_subarea_problema').val();

        var text_area_problema = $('#id_area_problema option:selected').text();
        var text_sub_area_problema = $('#id_subarea_problema option:selected').text();
        var text_classificacao = $('#classificacao option:selected').text();
        var text_assunto = $('#assunto option:selected').text();
        var text_setor = $('#setor option:selected').text();
        var text_filial = $('#filial option:selected').text();
        var text_tecnico = $('#tecnico option:selected').text();
        var text_departamento = $('#departamento option:selected').text();
        var text_processo = $('#processo option:selected').text();
        var text_prioridade = $('#prioridade option:selected').text();
        var text_origem = $('#origem option:selected').text();

        if (area_problema == 0 || sub_area_problmea == 0) {
            alert("Seleciona a área do problema e especifique a subarea!");
            return false;
        } else {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoCamposDefault.php",
                dataType: "json",
                data: {
                    acao: "salva_campos",
                    contrato: id_contrato_plano_pessoa,
                    classificacao: $('#classificacao').val(),
                    texto_classificacao: text_classificacao,
                    assunto: $('#assunto').val(),
                    texto_assunto: text_assunto,
                    setor: $('#setor').val(),
                    texto_setor: text_setor,
                    filial: $('#filial').val(),
                    texto_filial: text_filial,
                    tecnico: $('#tecnico').val(),
                    texto_tecnico: text_tecnico,
                    departamento: $('#departamento').val(),
                    texto_departamento: text_departamento,
                    processo: $('#processo').val(),
                    texto_processo: text_processo,
                    prioridade: $('#prioridade').val(),
                    texto_prioridade: text_prioridade,
                    origem: $('#origem').val(),
                    texto_origem: text_origem,
                    id_area_problema: $('#id_area_problema').val(),
                    texto_area_problema: text_area_problema,
                    id_subarea_problema: $('#id_subarea_problema').val(),
                    texto_sub_area_problema: text_sub_area_problema,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    
                    if (data == 1) {
                        $("#classificacao").val(0);
                        $("#assunto").val(0);
                        $("#setor").val(0);
                        $("#filial").val(0);
                        $("#tecnico").val(0);
                        $("#departamento").val(0);
                        $("#processo").val(0);
                        $("#prioridade").val(0);
                        $("#origem").val(0);
                        $("#id_area_problema").val(0);
                        $("#id_subarea_problema").val(0);
                        $("#alerta").addClass("alert alert-success").text("Configuração realizada com sucesso!");

                    } else if (data == 2){
                        $("#alerta").addClass("alert alert-danger").text("Configuração já existe! Exclua os parametros desta SUBÁREA de problema antes de cadastrar.");
                    }
                }
            });
        }
    });

    //Preenche com seus dados
    function busca_campos(id_contrato_plano_pessoa) {

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_assunto",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                id_assunto: '',
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                html = '<option class="assunto" value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option class='assunto' value='" + data.registros[i]['id'] + "'>" + data.registros[i]['id'] + " - " + data.registros[i]['assunto'] + "</option>";
                });
                $("#assunto").html(html);
            }
        });

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_setor2",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                html = '<option class="setor" value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option class='setor' value='" + data.registros[i]['id'] + "'>" + data.registros[i]['setor'] + "</option>";
                });
                $("#setor").html(html);
            }
        });

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_filial",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                id_filial: '',
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                html = '<option class="filial" value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option class='filial' value='" + data.registros[i]['id'] + "'>" + data.registros[i]['razao'] + "</option>";
                });
                $("#filial").html(html);
            }
        });

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_tecnico",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                id_tecnico: '',
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                html = '<option class="tecnico" value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option class='tecnico' value='" + data.registros[i]['id'] + "'>" + data.registros[i]['funcionario'] + "</option>";
                });
                $("#tecnico").html(html);
            }
        });

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_setor",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                id_setor: '',
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                html = '<option class="departamento" value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option class='departamento' value='" + data.registros[i]['id'] + "'>" + data.registros[i]['setor'] + "</option>";
                });
                $("#departamento").html(html);
            }
        });

        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_processo",
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                token: '<?= $request->token ?>'
            },
            success: function(data) {

                html = '<option class="processo" value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option class='processo' value='" + data.registros[i]['id'] + "'>" + data.registros[i]['descricao'] + "</option>";
                });
                $("#processo").html(html);
            }
        });

        opcoes_origem = `<option value=''></option><option value='C'>Cliente</option><option value='L'>Login</option><option value='CC'>Contrato</option><option value='M'>Manual</option>`;
        $("#origem").html(opcoes_origem);

        opcoes_classificacao = `<option value=''></option><option value='1'>Ordem de serviço</option><option value='2'>Atendimento</option>`;
        $("#classificacao").html(opcoes_classificacao);

        return true;
    }

    ///////////////////////////////////////////////////////////////
    //Verifica a classificação para mostrar ou esconder os campos de cada uma
    $("#default-departamento").hide();
    $("#default-processo").hide();
    $("#default-setor").hide();

    $("#classificacao").on("change", function() {
        if ($(this).val() == 1) {
            $("#default-departamento").hide();
            $("#default-setor").show();
            $("#default-processo").hide();
        } else {
            $("#default-departamento").show();
            $("#default-setor").hide();
            $("#default-processo").show();
        }
    });

    /* function getTextSelect(elemento) {

        var values_array = new Array();
        var options = $('#'+elemento+' option');

        var values = $.map(options ,function(option) {
            if (option.text != '') {
                values_array[option.value] = option.text;
                //values_array.push(option.text);
            }
        });

        return values_array;
    } */

    function call_busca_ajax(pagina){

        var inicia_busca = 1;

        var id_area_problema_busca = $('#id_area_problema_busca').val();

        /* var classificacao = getTextSelect('classificacao');
        var assunto = getTextSelect('assunto');
        var filial = getTextSelect('filial');
        var tecnico = getTextSelect('tecnico');
        var prioridade = getTextSelect('prioridade');
        var origem = getTextSelect('origem'); */

        var id_contrato_plano_pessoa = <?= $id_contrato_plano_pessoa ?>;

        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'id_area_problema_busca': id_area_problema_busca,
            'id_contrato_plano_pessoa': id_contrato_plano_pessoa,
            'pagina': pagina
        };

        busca_ajax('<?= $request->token ?>' , 'IntegracaoCamposDefaultBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
    //setTimeout(function() { call_busca_ajax(); }, 2000);
</script>