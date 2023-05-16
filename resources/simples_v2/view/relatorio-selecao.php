<?php
require_once(__DIR__."/../class/System.php");

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$tipo_relatorio_get = (!empty($_GET['tiporelatorio'])) ? $_GET['tiporelatorio'] : '1';
$id_pessoa_get = (!empty($_GET['id_pessoa'])) ? $_GET['id_pessoa'] : '';

if ($tipo_relatorio_get == 2) {
    $gerar_get = 1;
} else {
    $gerar_get = 0;
}

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/" . $data_hoje[5] . $data_hoje[6] . "/" . $data_hoje[0] . $data_hoje[1] . $data_hoje[2] . $data_hoje[3];
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : $tipo_relatorio_get;
$idade = (!empty($_POST['idade'])) ? $_POST['idade'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : '';
$id_selecao = (!empty($_POST['id_selecao'])) ? $_POST['id_selecao'] : '';
$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : $id_pessoa_get;
$data_de_candidato = (!empty($_POST['data_de_candidato'])) ? $_POST['data_de_candidato'] : $primeiro_dia;
$data_ate_candidato = (!empty($_POST['data_ate_candidato'])) ? $_POST['data_ate_candidato'] : converteData(getDataHora('data'));
$setor = (!empty($_POST['setor'])) ? $_POST['setor'] : '';
$cargo = (!empty($_POST['cargo'])) ? $_POST['cargo'] : '';

if ($id_pessoa) {
    $nome_candidato = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa", 'nome');
}

if ($tipo_relatorio == 1) {
    $row_periodo_selecao = '';
    $row_selecao = '';
    $row_status = '';
    $row_periodo_candidato = 'style="display: none;"';
    $row_pessoa = 'style="display: none;"';
    $data_de_required = 'required';
    $data_ate_required = 'required';
    $data_de_candidato_required = '';
    $data_ate_candidato_required = '';
    $pessoa_required = '';
} else if ($tipo_relatorio == 2) {
    $row_periodo_selecao = 'style="display: none;"';
    $row_selecao = 'style="display: none;"';
    $row_status = 'style="display: none;"';
    $row_periodo_candidato = 'style="display: none;"';
    $row_pessoa = '';
    $data_de_required = '';
    $data_ate_required = '';
    $data_de_candidato_required = '';
    $data_ate_candidato_required = '';
    $pessoa_required = 'required';
} else if ($tipo_relatorio == 3) {
    $row_periodo_selecao = '';
    $row_selecao = '';
    $row_status = '';
    $row_periodo_candidato = 'style="display: none;"';
    $row_pessoa = 'style="display: none;"';
    $data_de_required = 'required';
    $data_ate_required = 'required';
    $data_de_candidato_required = '';
    $data_ate_candidato_required = '';
    $pessoa_required = '';
}

$gerar = (!empty($_POST['gerar'])) ? 1 : $gerar_get;

if ($gerar) {
    $collapse = '';
    $collapse_icon = 'plus';
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
}
?>

<style>
    .conteudo-editor img {
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }

    @media print {
        .noprint {
            display: none;
        }

        footer {
            page-break-after: always !important;
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            visibility: visible !important;
        }

        #row-imagem {
            display: none !important;
        }

        #row-imagem-relatorio {
            display: block !important;
        }

        .jumbotron {
            padding-top: 10px !important;
            padding-left: 20px !important;
            padding-right: 4px !important;
            padding-bottom: 0 !important;
            font-size: 13px !important;
            margin-left: -142px !important;
            margin-right: -142px !important;
            height: 100% !important;
            border: none !important;
            margin-bottom: -100px !important;
        }

        .row {
            padding-bottom: 7px !important;
        }

        .media {
            padding-bottom: 2px !important;
        }

        span {
            font-size: 12px !important;
        }

        h1 {
            font-size: 20px !important;
        }

        h4 {
            font-size: 14px !important;
        }

        #teste {
            background-color: #E6E6E6 !important;
        }

        .breadcrumb {
            background-color: #E6E6E6 !important;
        }

        #imagem {
            visibility: visible !important;
        }

        img {
            display: block;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            margin-left: 40px;
        }

        .timeline__post {
            padding-left: 50px !important;
        }

        .timeline__date {
            background-color: #337ab7 !important;
        }

        .font-print {
            color: white !important;
        }

        .foto-print {
            margin-left: 0px;
        }
    }

    .fonts-curriculo {
        font-size: 15px;
        color: black;
    }

    .hr-curriculo {
        border-top: 2px solid #BDBDBD;
    }

    .pd-curriculo {
        padding: 10px 0 10px 23px !important;
    }

    .pd-exp-prof {
        padding-left: 4px !important;
    }

    #imagem {
        -moz-background-size: 100% 100%;
        -webkit-background-size: 100% 100%;
        background-size: cover;
        height: 146px;
        width: 100%;
        resize: both;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-position: center;
    }

    #img-relatorio {
        border-radius: 80px;
        width: 146px;
        height: 146px;
        object-fit: cover;
    }

    img {
        image-orientation: from-image;
    }

    span {
        overflow-wrap: break-word;
    }
 
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
    <form method="post" id="">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">

                <div class="panel panel-default noprint">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de Seleção:</h3>
                        <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?= $collapse_icon ?>"></i></button></div>
                    </div>
                    <div id="accordionRelatorio" class="panel-collapse collapse <?= $collapse ?>">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Tipo de Relatório:</label> <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="2" <?php if ($tipo_relatorio == '2') {echo 'selected';} ?>>Candidato</option>
                                            <option value="1" <?php if ($tipo_relatorio == '1') {echo 'selected';} ?>>Seleção</option>
                                            <option value="3" <?php if ($tipo_relatorio == '3') {echo 'selected';} ?>>Seleção - Sintético</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_periodo_selecao" <?= $row_periodo_selecao ?>>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Inicial:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_de" id="de" autocomplete="off" value="<?= $data_de ?>" <?= $data_de_required ?>>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Final:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_ate" id="ate" autocomplete="off" value="<?= $data_ate ?>" <?= $data_ate_required ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Setor:</label>
                                        <select name="setor" id="setor" class="form-control input-sm">
                                            <option value="" <?= $sel_setor[''] ?>>Qualquer</option>
                                            <?php
                                            $dados_setor = DBRead('', 'tb_setor', "ORDER BY descricao ASC");
                                            foreach ($dados_setor as $conteudo_setor) {
                                                $selected = $setor == $conteudo_setor['id_setor'] ? "selected" : "";
                                            ?>
                                                <option value="<?= $conteudo_setor['id_setor'] ?>" <?= $selected ?>><?= $conteudo_setor['descricao'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Cargo:</label>
                                        <select name="cargo" id="cargo" class="form-control input-sm">
                                            <option value="">Qualquer</option>
                                            <?php
                                            $dados_cargo = DBRead('', 'tb_cargo', "WHERE id_setor = 1 ORDER BY descricao ASC");
                                            foreach ($dados_cargo as $conteudo_cargo) {
                                                $selected = $cargo == $conteudo_cargo['id_cargo'] ? "selected" : "";
                                            ?>
                                                <option value="<?= $conteudo_cargo['id_cargo'] ?>" <?= $selected ?>><?= $conteudo_cargo['descricao'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_selecao" <?= $row_selecao ?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Seleção:</label>
                                        <select name="id_selecao" id="id_selecao" class="form-control input-sm">
                                            <?php
                                            $dados = DBRead('', 'tb_selecao', "ORDER BY descricao ASC");
                                            if ($dados) {
                                                foreach ($dados as $conteudo) {
                                                    echo '<option value="' . $conteudo['id_selecao'] . '">' . $conteudo['descricao'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_status" <?= $row_status ?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status:</label>
                                        <select name="status" id="status" class="form-control input-sm">
                                            <option value="" <?= $status == "" ? "selected" : ""; ?>>Qualquer</option>
                                            <option value="2" <?= $status == "2" ? "selected" : ""; ?>>Aprovado</option>
                                            <option value="1" <?= $status == "1" ? "selected" : ""; ?>>Em seleção</option>
                                            <option value="4" <?= $status == "3" ? "selected" : ""; ?>>Não compareceu</option>
                                            <option value="5" <?= $status == "4" ? "selected" : ""; ?>>Pré-aprovado</option>
                                            <option value="3" <?= $status == "3" ? "selected" : ""; ?>>Reprovado</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Não esta sendo usado nunca??? -->

                            <div class="row" id="row_periodo_candidato" <?= $row_periodo_candidato ?>>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Inicial:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_de_candidato" id="data_de_candidato" autocomplete="off" value="<?= $data_de_candidato ?>" <?= $data_de_candidato_required ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Final:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_ate_candidato" id="data_ate_candidato" autocomplete="off" value="<?= $data_ate_candidato ?>" <?= $data_ate_candidato_required ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_pessoa" <?= $row_pessoa ?>>
                                <div class="col-md-12">
                                    <div class="form-group" id="pessoa_group" name="pessoa_group">
                                        <label>Pessoa:</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm ui-autocomplete-input" id="busca_pessoa" type="text" name="busca_pessoa" value="<?= $nome_candidato[0]['nome'] ?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" <?= $pessoa_required ?>>
                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?= $id_pessoa ?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit"><i class="fa fa-refresh"></i> Gerar</button>
                                <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <?php
        if ($gerar) {

            if ($tipo_relatorio == 1) {
                relatorio_selecao($id_selecao, $status, $data_de, $data_ate, $setor, $cargo);
            } else if ($tipo_relatorio == 2) {
                relatorio_candidato($id_pessoa, $data_de_candidato, $data_ate_candidato, $setor, $cargo);
            } else if ($tipo_relatorio == 3) {
                relatorio_selecao_tabela($id_selecao, $status, $data_de, $data_ate, $setor, $cargo);
            }
        }
        ?>
    </div>
</div>

<script>
    $('#accordionRelatorio').on('shown.bs.collapse', function() {
        $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function() {
        $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $('#tipo_relatorio').on('change', function() {
        tipo_relatorio = $(this).val();

        if (tipo_relatorio == 1) {
            $('#row_periodo_selecao').show();
            $('#row_selecao').show();
            $('#row_status').show();
            $('#row_pessoa').hide();
            $('#row_periodo_candidato').hide();
            $('input[name=data_de]').prop('required', true);
            $('input[name=data_ate]').prop('required', true);
            $('input[name=data_de_candidato]').prop('required', false);
            $('input[name=data_ate_candidato]').prop('required', false);
            $('#busca_pessoa').prop('required', false);

        } else if (tipo_relatorio == 2) {
            $('#row_periodo_selecao').hide();
            $('#row_selecao').hide();
            $('#row_status').hide();
            $('#row_pessoa').show();
            $('#row_periodo_candidato').hide();
            $('input[name=data_de_candidato]').prop('required', false);
            $('input[name=data_ate_candidato]').prop('required', false);
            $('input[name=data_de]').prop('required', false);
            $('input[name=data_ate]').prop('required', false);
            $('#busca_pessoa').prop('required', true);

        } else if (tipo_relatorio == 3) {
            $('#row_periodo_selecao').show();
            $('#row_selecao').show();
            $('#row_status').show();
            $('#row_pessoa').hide();
            $('#row_periodo_candidato').hide();
            $('input[name=data_de]').prop('required', true);
            $('input[name=data_ate]').prop('required', true);
            $('input[name=data_de_candidato]').prop('required', false);
            $('input[name=data_ate_candidato]').prop('required', false);
            $('#busca_pessoa').prop('required', false);
        }
    });

    $(document).ready(function() {
        $('#aguarde').hide();
        $('#resultado').show();
        $("#gerar").prop("disabled", false);

        var data_de = $("input[name=data_de]").val();
        var data_ate = $("input[name=data_ate]").val();

        var id_selecao = "<?= $selecao ?>";
        selectPesquisa(data_de, data_ate, id_selecao);
    });

    $('.date').on('change', function() {
        var data_de = $("input[name=data_de]").val();
        var data_ate = $("input[name=data_ate]").val();

        selectPesquisa(data_de, data_ate, '');
    });

    function selectPesquisa(data_de, data_ate, id_selecao) {

        if (data_de != "" && data_ate != "") {
            $.ajax({
                url: "/api/ajax?class=SelectSelecao.php",
                dataType: "html",
                data: {
                    acao: 'busca_selecao',
                    parametros: {
                        'data_de': data_de,
                        'data_ate': data_ate,
                        'id_selecao': id_selecao
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $("select[name=id_selecao]").empty();
                    $("select[name=id_selecao]").append(data);
                }
            });
        }
    }

    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);

    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function(request, response) {
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'consulta_candidato',
                        parametros: {
                            'nome': $('#busca_pessoa').val(),
                            'atributo': $('#atributo').val(),
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            focus: function(event, ui) {
                $("#busca_pessoa").val(ui.item.nome + " " + ui.item.nome_contrato);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function(event, ui) {
                $("#busca_pessoa").val(ui.item.nome + " " + ui.item.nome_contrato);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item) {
            if (!item.razao_social) {
                item.razao_social = '';
            }
            if (!item.cpf_cnpj) {
                item.cpf_cnpj = '';
            }
            if (!item.nome_contrato) {
                item.nome_contrato = '';
            } else {
                item.nome_contrato = ' (' + item.nome_contrato + ') ';
            }

            return $("<li>").append("<a><strong>" + item.id_pessoa + " - " + item.nome + item.nome_contrato + " </strong><br>" + item.razao_social + "<br>" + item.cpf_cnpj + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id': id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $('#id_pessoa').val(data[0].id_pessoa);
                    carrregaSelectVinculo(data[0].id_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();
        if (busca == "") {
            $('#id_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_pessoa', function() {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();;
    });

    function selectCargo(id_setor, id_cargo) {
        //$("select[name=setor]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectCargo.php", {
                setor: id_setor,
                token: '<?= $request->token ?>'
            },
            function(valor) {
                $("select[name=cargo]").html(valor);
                if (id_cargo != undefined) {
                    $('#cargo').val(id_cidade);
                }
            }
        )
    }

    $(document).on('change', 'select[name=setor]', function() {
        selectCargo($(this).val());
    });
</script>

<style>
    #imagem {
        -moz-background-size: 100% 100%;
        -webkit-background-size: 100% 100%;
        background-size: cover;
        height: 46px;
        width: 100%;
        resize: both;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-position: center;
    }

    #img-relatorio {
        border-radius: 80px;
        width: 72px;
        height: 72px;
        object-fit: cover;
    }

    .timeline {
        --uiTimelineMainColor: var(--timelineMainColor, #222);
        --uiTimelineSecondaryColor: var(--timelineSecondaryColor, #F2F2F2);

        position: relative;
        padding-top: 0rem;
        padding-bottom: 3rem;
    }

    .timeline:before {
        content: "";
        width: 4px;
        height: 100%;
        background-color: #337ab7;

        position: absolute;
        top: 0;
    }

    .timeline__group {
        position: relative;
    }

    .timeline__group:not(:first-of-type) {
        margin-top: 4rem;
    }

    .timeline__year {
        padding: .5rem 1.5rem;
        color: var(--uiTimelineSecondaryColor);
        background-color: var(--uiTimelineMainColor);

        position: absolute;
        left: 0;
        top: 0;
    }

    .timeline__box {
        position: relative;
    }

    .timeline__box:not(:last-of-type) {
        margin-bottom: 30px;
    }

    .timeline__box:before {
        content: "";
        width: 100%;
        height: 2px;
        background-color: var(--uiTimelineMainColor);

        position: absolute;
        left: 0;
        z-index: -1;
    }

    .timeline__date {
        min-width: 65px;
        position: absolute;
        left: 0;
        border-radius: 10px;
        border: 0.5px solid #BDBDBD;
        box-sizing: border-box;
        padding: .4rem 1.5rem;
        text-align: center;
        margin-left: -18px;
        background-color: var(--uiTimelineMainColor);
        color: var(--uiTimelineSecondaryColor);
    }

    .timeline__day {
        font-size: 14px;
        font-weight: 500;
        display: block;
    }

    .timeline__month {
        display: block;
        font-size: .8em;
        text-transform: uppercase;
    }

    .timeline__post {
        padding: 1.5rem 2rem;
        margin-left: 27px;
        border-radius: 2px;
        border-left: 3px solid #337ab7;
        box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);
        background-color: var(--uiTimelineSecondaryColor);
    }

    @media screen and (min-width: 641px) {
        .timeline:before {
            left: 30px;
        }

        .timeline__group {
            padding-top: 30px;
        }

        .timeline__box {
            padding-left: 80px;
        }

        .timeline__box:before {
            top: 50%;
            transform: translateY(-50%);
        }

        .timeline__date {
            top: 50%;
            margin-top: -27px;
        }
    }

    @media screen and (max-width: 640px) {
        .timeline:before {
            left: 0;
        }

        .timeline__group {
            padding-top: 40px;
        }

        .timeline__box {
            padding-left: 20px;
            padding-top: 70px;
        }

        .timeline__box:before {
            top: 90px;
        }

        .timeline__date {
            top: 0;
        }
    }

    .timeline {
        --timelineMainColor: #337ab7;
        font-size: 16px;
    }

    @media (min-width: 768px) {
        html {
            font-size: 62.5%;
        }
    }

    @media (max-width: 767px) {

        html {
            font-size: 55%;
        }
    }

    p {
        margin-top: 0;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    p:last-child {
        margin-bottom: 0;
    }

    .page {
        max-width: 100%;
        padding: 0rem 2rem 0rem;
        margin-left: auto;
        margin-right: auto;
        order: 1;
    }

    .div-span-timeline {
        font-size: 15px;
        padding-bottom: 5px;
    }

    #img-relatorio-tabela {
        border-radius: 80px;
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

    .rotate180 {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .rotate270 {
        -webkit-transform: rotate(270deg);
        -moz-transform: rotate(270deg);
        -o-transform: rotate(270deg);
        -ms-transform: rotate(270deg);
        transform: rotate(270deg);
    }

    .rotate90 {
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        transform: rotate(90deg);
    }
</style>
<?php

function relatorio_selecao_tabela($id_selecao, $status, $data_de, $data_ate, $setor, $cargo)
{

    $data_hora = converteDataHora(getDataHora());

    if ($data_de && $data_ate) {
        $periodo_amostra = "<span class=\"noprint\" style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    } elseif ($data_de) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
    } elseif ($data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
    } else {
        $periodo_amostra = "";
    }

    if ($setor) {
        $filtro_setor = "AND a.id_setor = $setor";
        $dados_setor = DBRead('', 'tb_setor', "WHERE id_setor='" . $setor . "' ");
        if ($cargo) {
            $filtro_cargo = "AND a.id_cargo = $cargo";
            $dados_cargo = DBRead('', 'tb_cargo', "WHERE id_cargo='" . $cargo . "' ");
            $legenda_setor = $dados_setor[0]['descricao'] . ', <strong>Cargo - </strong>' . $dados_cargo[0]['descricao'];
        } else {
            $legenda_setor = $dados_setor[0]['descricao'] . ', <strong>Cargo - </strong>Qualquer';
        }
    } else {
        $legenda_setor = 'Qualquer';
    }
    if ($status) {
        $filtro_status = "AND a.status = $status";
        if ($status == 1) {
            $legenda_status = 'Em seleção';
        } else if ($status == 2) {
            $legenda_status = 'Aprovado';
        } else if ($status == 3) {
            $legenda_status = 'Reprovado';
        } else if ($status == 4) {
            $legenda_status = 'Não compareceu';
        } else if ($status == 5) {
            $legenda_status = 'Pré-aprovado';
        }
    } else {
        $legenda_status = 'Qualquer';
    }

    if ($id_selecao) {
        $dados_nome_selecao = DBRead('', 'tb_selecao', "WHERE id_selecao ='" . $id_selecao . "' ");
        $legenda_selecao = $dados_nome_selecao[0]['nome'];
    } else {
        $legenda_selecao = 'Qualquer';
    }

    echo "<div class=\"col-xs-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Seleção - Sintético</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">" . $periodo_amostra . "</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Seleção - </strong>" . $legenda_selecao . "</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Setor - </strong>" . $legenda_setor . "<strong>, Status - </strong>" . $legenda_status . "</legend>";

    $dados = DBRead('', 'tb_selecao a', "INNER JOIN tb_setor b ON a.id_setor = b.id_setor INNER JOIN tb_cargo c ON a.id_cargo = c.id_cargo WHERE a.id_selecao = '$id_selecao' $filtro_setor $filtro_cargo", 'a.*, a.descricao as descricao_selecao, b.descricao as descricao_setor, c.descricao as descricao_cargo');

    if ($dados) {

        foreach ($dados as $conteudo) {

            $id_selecao = $conteudo['id_selecao'];

            $dados_candidatos = DBRead('', 'tb_selecao_candidato a', "INNER JOIN tb_pessoa b ON a.id_pessoa_candidato = b.id_pessoa WHERE id_selecao = $id_selecao $filtro_status", 'a.*, b.*, a.status as status_candidato');

            if ($dados_candidatos) {
                echo '
                <div class="row">
                    <div class="col-md-12">
                        <div class="table table-responsive table-striped">
                            <table class="table table-hover dataTable" style="margin-bottom:0;">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">Foto</th>
                                        <th class="col-md-4 ">Nome</th>
                                        <th class="col-md-2">CPF</th>
                                        <th class="col-md-1">Etapa</th>
                                        <th class="col-md-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>';
                foreach ($dados_candidatos as $conteudo_candidatos) {

                    $id = $conteudo_candidatos['id_pessoa'];
                    $nome = $conteudo_candidatos['nome'];
                    $etapa = $conteudo_candidatos['etapa'];
                    $cpf = formataCampo('cpf_cnpj', $conteudo_candidatos['cpf_cnpj']);
                    $id_cidade = $conteudo_candidatos['id_cidade'];
                    $data_atualizacao = converteDataHora($conteudo_candidatos['data_atualizacao']);

                    if ($conteudo_candidatos['status_candidato'] == 1) {
                        $status = '<span class="label label-primary" style="font-size: 12px; min-width: 110px; display: inline-block;">Em seleção</span>';
                    } else if ($conteudo_candidatos['status_candidato'] == 2) {
                        $status = '<span class="label label-success" style="font-size: 12px; min-width: 110px; display: inline-block;">Aprovado</span>';
                    } else if ($conteudo_candidatos['status_candidato'] == 3) {
                        $status = '<span class="label label-danger" style="font-size: 12px; min-width: 110px; display: inline-block;">Reprovado</span>';
                    } else if ($conteudo_candidatos['status_candidato'] == 4) {
                        $status = '<span class="label label-warning" style="font-size: 12px; min-width: 110px; display: inline-block;">Não compareceu</span>';
                    } else if ($conteudo_candidatos['status_candidato'] == 5) {
                        $status = '<span class="label label-info" style="font-size: 12px; min-width: 110px; display: inline-block;">Pré-aprovado</span>';
                    }

                    $dados_pessoais = DBRead('', 'tb_pessoa_rh_dados_pessoais', "WHERE id_pessoa = $id");
                    $foto = $dados_pessoais[0]['foto'];

                    $exif_data = exif_read_data($dados_pessoais[0]['foto']);

                    $orientation = '';
                    foreach ($exif_data as $key => $value) {
                        if (strtolower($key) == "orientation") {
                            $orientation = $value;
                        }
                    }

                    $class_rotate = '';
                    if ($orientation != '') {
                        switch ($orientation) {
                            case 3:
                                $class_rotate = 'rotate180';
                                break;

                            case 6:
                                $class_rotate = 'rotate90';
                                break;

                            case 8:
                                $class_rotate = 'rotate270';
                                break;
                        }
                    }

                    echo '
                            <tr>
                                <td><img src="' . $foto . '" class="center text-center ' . $class_rotate . '" id="img-relatorio-tabela"></td>
                                <td><span>' . $nome . '</span></td>
                                <td><span>' . $cpf . '</span></td>
                                <td><span class="label label-default">' . $etapa . '</span></td>
                                <td>' . $status . '</td>
                            </tr>';
                }
                echo '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>';
            }
        }
    } else {
        echo '<h4 class="text-center">Não foram encontrados resultados!</h4>';
    }

    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },			        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });
            });
        </script>	";
}

function relatorio_selecao($id_selecao, $status, $data_de, $data_ate, $setor, $cargo)
{

    $data_hora = converteDataHora(getDataHora());

    if ($data_de && $data_ate) {
        $periodo_amostra = "<span class=\"noprint\" style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    } elseif ($data_de) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
    } elseif ($data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
    } else {
        $periodo_amostra = "";
    }

    if ($setor) {
        $filtro_setor = "AND a.id_setor = $setor";
        $dados_setor = DBRead('', 'tb_setor', "WHERE id_setor='" . $setor . "' ");
        if ($cargo) {
            $filtro_cargo = "AND a.id_cargo = $cargo";
            $dados_cargo = DBRead('', 'tb_cargo', "WHERE id_cargo='" . $cargo . "' ");
            $legenda_setor = $dados_setor[0]['descricao'] . ', <strong>Cargo - </strong>' . $dados_cargo[0]['descricao'];
        } else {
            $legenda_setor = $dados_setor[0]['descricao'] . ', <strong>Cargo - </strong>Qualquer';
        }
    } else {
        $legenda_setor = 'Qualquer';
    }
    if ($status) {
        $filtro_status = "AND a.status = $status";
        if ($status == 1) {
            $legenda_status = 'Em seleção';
        } else if ($status == 2) {
            $legenda_status = 'Aprovado';
        } else if ($status == 3) {
            $legenda_status = 'Reprovado';
        } else if ($status == 4) {
            $legenda_status = 'Não compareceu';
        } else if ($status == 5) {
            $legenda_status = 'Pré-aprovado';
        }
    } else {
        $legenda_status = 'Qualquer';
    }

    if ($id_selecao) {
        $dados_nome_selecao = DBRead('', 'tb_selecao', "WHERE id_selecao ='" . $id_selecao . "' ");
        $legenda_selecao = $dados_nome_selecao[0]['nome'];
    } else {
        $legenda_selecao = 'Qualquer';
    }

    echo "<div class=\"col-xs-12\" style=\"padding: 0\">";
    echo "<legend class=\"noprint\" style=\"text-align:center;\"><strong>Relatório de Seleção</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend class=\"noprint\" style=\"text-align:center;\"><span style=\"font-size: 14px;\">" . $periodo_amostra . "</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Seleção - </strong>" . $legenda_selecao . "</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Setor - </strong>" . $legenda_setor . "<strong>, Status - </strong>" . $legenda_status . "</legend>";

    $dados = DBRead('', 'tb_selecao a', "INNER JOIN tb_setor b ON a.id_setor = b.id_setor INNER JOIN tb_cargo c ON a.id_cargo = c.id_cargo WHERE a.id_selecao = '$id_selecao' $filtro_setor $filtro_cargo", 'a.*, a.descricao as descricao_selecao, b.descricao as descricao_setor, c.descricao as descricao_cargo');

    if ($dados) {

        foreach ($dados as $conteudo) {

            $id_selecao = $conteudo['id_selecao'];

            $avaliadores = DBRead('', 'tb_selecao a', "INNER JOIN tb_selecao_etapa b ON a.id_selecao = b.id_selecao INNER JOIN tb_selecao_etapa_avaliador c ON b.id_selecao_etapa = c.id_selecao_etapa INNER JOIN tb_usuario d ON d.id_usuario = c.id_usuario_avaliador INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE a.id_selecao = $id_selecao", 'e.nome, d.id_usuario');

            $array_nomes = array();
            foreach ($avaliadores as $conteudo_avaliadores) {
                if (!in_array($conteudo_avaliadores['nome'], $array_nomes)) {
                    array_push($array_nomes, $conteudo_avaliadores['nome']);
                }
            }

            $envolvidos = '';
            foreach ($array_nomes as $conteudo_nomes) {
                $envolvidos .= $conteudo_nomes . ';<br>';
            }

            if ($dados[0]['status'] == 1) {
                $status = 'Em andamento';
            } else if ($dados[0]['status'] == 2) {
                $status = 'Encerrada';
            }

    ?>

            <div class="panel panel-default" style="border: 1px solid #A4A4A4;">
                <div class="panel-heading">
                    <h3 class="panel-title">Seleção:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6" style="margin-left: -5px;">
                            <br>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="td-table"><strong>Nome:</strong></td>
                                        <td><?= $conteudo['nome'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Setor:</strong></td>
                                        <td><?= $conteudo['descricao_setor'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Número de etapas:</strong></td>
                                        <td><?= $conteudo['n_etapas'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Avaliadores:</strong></td>
                                        <td><?= $envolvidos ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- end col -->

                        <div class="col-md-6">
                            <br>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="td-table"><strong>Descrição:</strong></td>
                                        <td><?= $conteudo['descricao_selecao'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Data:</strong></td>
                                        <td><?= converteDataHora($conteudo['data']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Cargo:</strong></td>
                                        <td><?= $conteudo['descricao_cargo'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Número de vagas:</strong></td>
                                        <td><?= $conteudo['n_vagas'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Status:</strong></td>
                                        <td><?= $status ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- end col -->
                    </div>
                    <hr>

                    <?php
                    $dados_candidatos = DBRead('', 'tb_selecao_candidato a', "INNER JOIN tb_pessoa b ON a.id_pessoa_candidato = b.id_pessoa WHERE id_selecao = $id_selecao $filtro_status", 'a.*, b.*, a.status as status_candidato');

                    if ($dados_candidatos) {

                        foreach ($dados_candidatos as $conteudo_candidatos) {

                            $id = $conteudo_candidatos['id_pessoa'];
                            $nome = $conteudo_candidatos['nome'];
                            $etapa = $conteudo_candidatos['etapa'];
                            $cpf = formataCampo('cpf_cnpj', $conteudo_candidatos['cpf_cnpj']);
                            $id_cidade = $conteudo_candidatos['id_cidade'];
                            $data_nascimento = converteDataHora($conteudo_candidatos['data_nascimento']);
                            $data_atualizacao = converteDataHora($conteudo_candidatos['data_atualizacao']);
                            $dados_cidade = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '$id_cidade'", 'a.nome, b.sigla');

                            $cidade = $dados_cidade[0]['nome'];
                            $estado = $dados_cidade[0]['sigla'];

                            if ($conteudo_candidatos['status_candidato'] == 1) {
                                $status = '<span class="label label-primary" style="font-size: 12px; min-width: 110px; display: inline-block;">Em seleção</span>';
                            } else if ($conteudo_candidatos['status_candidato'] == 2) {
                                $status = '<span class="label label-success" style="font-size: 12px; min-width: 110px; display: inline-block;">Aprovado</span>';
                            } else if ($conteudo_candidatos['status_candidato'] == 3) {
                                $status = '<span class="label label-danger" style="font-size: 12px; min-width: 110px; display: inline-block;">Reprovado</span>';
                            } else if ($conteudo_candidatos['status_candidato'] == 4) {
                                $status = '<span class="label label-warning" style="font-size: 12px; min-width: 110px; display: inline-block;">Não compareceu</span>';
                            } else if ($conteudo_candidatos['status_candidato'] == 5) {
                                $status = '<span class="label label-info" style="font-size: 12px; min-width: 110px; display: inline-block;">Pré-aprovado</span>';
                            }

                            $dados_pessoais = DBRead('', 'tb_pessoa_rh_dados_pessoais', "WHERE id_pessoa = $id");
                            $foto = $dados_pessoais[0]['foto'];

                            $exif_data = exif_read_data($dados_pessoais[0]['foto']);

                            $orientation = '';
                            foreach ($exif_data as $key => $value) {
                                if (strtolower($key) == "orientation") {
                                    $orientation = $value;
                                }
                            }

                            $class_rotate = '';
                            if ($orientation != '') {
                                switch ($orientation) {
                                    case 3:
                                        $class_rotate = 'rotate180';
                                        break;

                                    case 6:
                                        $class_rotate = 'rotate90';
                                        break;

                                    case 8:
                                        $class_rotate = 'rotate270';
                                        break;
                                }
                            }

                            $id_selecao_candidato = $conteudo_candidatos['id_selecao_candidato'];

                            $dados_avaliador_candidato = DBRead('', 'tb_selecao_avaliador_candidato a', "INNER JOIN tb_selecao_etapa_avaliador b ON a.id_selecao_etapa_avaliador = b.id_selecao_etapa_avaliador INNER JOIN tb_selecao_etapa c ON b.id_selecao_etapa = c.id_selecao_etapa INNER JOIN tb_usuario d ON b.id_usuario_avaliador = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE id_selecao_candidato = '" . $conteudo_candidatos['id_selecao_candidato'] . "' ", 'a.*, b.*, c.*, e.nome');

                    ?>

                            <div class="panel panel-default" style="border: 1px solid #A4A4A4;">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Candidato:</h3>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table table-responsive table-striped">
                                                <table class="table table-hover" style="font-size: 14px;">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-1">Foto</th>
                                                            <th class="col-md-3">Nome</th>
                                                            <th class="col-md-2">CPF</th>
                                                            <th class="col-md-1">Etapa</th>
                                                            <th class="col-md-1">Status</th>
                                                            <th class="col-md-2">Cidade</th>
                                                            <th class="col-md-2">Atualizado em</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <img src="<?= $foto ?>" class="center text-center foto-print <?= $class_rotate ?>" id="img-relatorio" height="52" width="52">
                                                            </td>

                                                            <td>
                                                                <span><?= $nome ?></span>
                                                            </td>
                                                            <td>
                                                                <span><?= $cpf ?></span>
                                                            </td>
                                                            <td>
                                                                <span class="label label-default"><?= $etapa ?></span>
                                                            </td>
                                                            <td>
                                                                <?= $status ?>
                                                            </td>
                                                            <td>
                                                                <span><?= $cidade ?> - <?= $estado ?></span>
                                                            </td>
                                                            <td>
                                                                <span><?= $data_atualizacao ?></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($dados_avaliador_candidato) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div style="padding-bottom: 5px;">
                                                            <span><strong>Informações do candidato na(s) etapa(s):</strong></span>
                                                        </div>

                                                        <div class="page">
                                                            <div class="timeline">
                                                                <div class="timeline__group">

                                                                    <?php
                                                                    if ($dados_avaliador_candidato) {

                                                                        foreach ($dados_avaliador_candidato as $conteudo_avaliacao) {

                                                                            $data = converteDataHora($conteudo_avaliacao['data_avaliacao']);
                                                                            $data = explode(" ", $data);
                                                                            $dia = $data[0];
                                                                            $hora = $data[1];
                                                                    ?>

                                                                            <div class="timeline__box">
                                                                                <div class="timeline__date">
                                                                                    <span class="timeline__day font-print"><?= $dia ?></span>
                                                                                    <span class="timeline__month font-print"><?= $hora ?></span>
                                                                                </div>
                                                                                <div class="timeline__post">
                                                                                    <div class="timeline__content">
                                                                                        <div class="div-span-timeline">
                                                                                            <span>
                                                                                                <strong>Etapa: </strong> <?= $conteudo_avaliacao['num_etapa'] ?>
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="div-span-timeline">
                                                                                            <span>
                                                                                                <strong>Descricao: </strong> <?= $conteudo_avaliacao['descricao'] ?>
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="div-span-timeline">
                                                                                            <span>
                                                                                                <strong>Avaliador: </strong> <?= $conteudo_avaliacao['nome'] ?>
                                                                                            </span><br>
                                                                                        </div>
                                                                                        <?php
                                                                                        if ($conteudo_avaliacao['precisa_nota'] == 1) {
                                                                                        ?>
                                                                                            <div class="div-span-timeline">
                                                                                                <span>
                                                                                                    <strong>Nota: </strong> <?= $conteudo_avaliacao['nota'] ?>
                                                                                                </span><br>
                                                                                            </div>
                                                                                        <?php
                                                                                        }

                                                                                        if ($conteudo_avaliacao['precisa_parecer'] == 1) {
                                                                                        ?>
                                                                                            <div class="div-span-timeline">
                                                                                                <span>
                                                                                                    <strong>Parecer: </strong> <?= nl2br($conteudo_avaliacao['parecer']) ?>
                                                                                                </span><br>
                                                                                            </div>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    <?php } else { ?>
                                        <div class="alert alert-info text-center" role="alert">
                                            Candidato(a) não recebeu nota ou parecer!
                                        </div>
                                    <?php } ?>
                                </div>
                            </div><br>

                    <?php
                        }
                    } else {
                        echo '<div class="alert alert-warning text-center" role="alert">
                           Não foram encontrados candidatos com o status selecionado!
                        </div>';
                    }
                    ?>
                </div>
            </div>

            <?php

        }
    } else {
        echo '<h4 class="text-center">Não foram encontrados resultados!</h4>';
    }
}

function relatorio_candidato($id_pessoa, $data_de_candidato, $data_ate_candidato)
{

    $data_hora = converteDataHora(getDataHora());
    $nome_candidato = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa", 'nome');
    $legenda_pessoa = '<span style=\"font-size: 14px;\"><strong>Candidato:</strong> ' . $nome_candidato[0]['nome'] . '</span>';

    $data_de = converteData($data_de_candidato);
    $data_ate = converteData($data_ate_candidato);

    echo "<div class=\"col-xs-12\" style=\"padding: 0\">";
    echo "<legend class=\"noprint\" style=\"text-align:center;\"><strong>Relatório de Candidato</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend class=\"noprint\" style=\"text-align:center;\"><span style=\"font-size: 14px;\">.$legenda_pessoa.</legend>";

    $dados = DBRead('', 'tb_selecao_candidato a', "INNER JOIN tb_pessoa b ON a.id_pessoa_candidato = b.id_pessoa INNER JOIN tb_selecao c ON a.id_selecao = c.id_selecao WHERE id_pessoa_candidato = $id_pessoa", 'a.*, b.*, a.status as status_candidato');

    if ($dados) {
        foreach ($dados as $conteudo_selecao) {

            $id_selecao = $conteudo_selecao['id_selecao'];
            $id_candidato = $conteudo_selecao['id_selecao_candidato'];

            $id = $conteudo_selecao['id_pessoa'];
            $nome = $conteudo_selecao['nome'];
            $etapa = $conteudo_selecao['etapa'];
            $cpf = formataCampo('cpf_cnpj', $conteudo_selecao['cpf_cnpj']);
            $id_cidade = $conteudo_selecao['id_cidade'];
            $data_nascimento = converteDataHora($conteudo_selecao['data_nascimento']);
            $data_atualizacao = converteDataHora($conteudo_selecao['data_atualizacao']);
            $dados_cidade = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '$id_cidade'", 'a.nome, b.sigla');

            $cidade = $dados_cidade[0]['nome'];
            $estado = $dados_cidade[0]['sigla'];

            if ($conteudo_selecao['status_candidato'] == 1) {
                $status_candidato = '<span class="label label-primary" style="font-size: 12px; min-width: 110px; display: inline-block;">Em seleção</span>';
            } else if ($conteudo_selecao['status_candidato'] == 2) {
                $status_candidato = '<span class="label label-success" style="font-size: 12px; min-width: 110px; display: inline-block;">Aprovado</span>';
            } else if ($conteudo_selecao['status_candidato'] == 3) {
                $status_candidato = '<span class="label label-danger" style="font-size: 12px; min-width: 110px; display: inline-block;">Reprovado</span>';
            } else if ($conteudo_selecao['status_candidato'] == 4) {
                $status_candidato = '<span class="label label-warning" style="font-size: 12px; min-width: 110px; display: inline-block;">Não compareceu</span>';
            } else if($conteudo_selecao['status_candidato'] == 5){
                $status_candidato = '<span class="label label-info" style="font-size: 12px; min-width: 110px; display: inline-block;">Pré-aprovado</span>';
            }
            
            $dados_pessoais = DBRead('', 'tb_pessoa_rh_dados_pessoais', "WHERE id_pessoa = $id");
            $foto = $dados_pessoais[0]['foto'];

            $dados_info_selecao = DBRead('', 'tb_selecao a', "INNER JOIN tb_setor b ON a.id_setor = b.id_setor INNER JOIN tb_cargo c ON a.id_cargo = c.id_cargo WHERE a.id_selecao = '$id_selecao'", 'a.*, a.descricao as descricao_selecao, b.descricao as descricao_setor, c.descricao as descricao_cargo');

            $avaliadores = DBRead('', 'tb_selecao a', "INNER JOIN tb_selecao_etapa b ON a.id_selecao = b.id_selecao INNER JOIN tb_selecao_etapa_avaliador c ON b.id_selecao_etapa = c.id_selecao_etapa INNER JOIN tb_usuario d ON d.id_usuario = c.id_usuario_avaliador INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE a.id_selecao = $id_selecao", 'e.nome, d.id_usuario');

            $array_nomes = array();
            foreach ($avaliadores as $conteudo_avaliadores) {
                if (!in_array($conteudo_avaliadores['nome'], $array_nomes)) {
                    array_push($array_nomes, $conteudo_avaliadores['nome']);
                }
            }

            $envolvidos = '';
            foreach ($array_nomes as $conteudo_nomes) {
                $envolvidos .= $conteudo_nomes . ';<br>';
            }

            foreach ($dados_info_selecao as $conteudo_info) {

                if ($conteudo_info['status'] == 1) {
                    $status = 'Em andamento';
                } else if ($conteudo_info['status'] == 2) {
                    $status = 'Encerrada';
                }

                $dados_avaliador_candidato = DBRead('', 'tb_selecao_avaliador_candidato a', "INNER JOIN tb_selecao_etapa_avaliador b ON a.id_selecao_etapa_avaliador = b.id_selecao_etapa_avaliador INNER JOIN tb_selecao_etapa c ON b.id_selecao_etapa = c.id_selecao_etapa INNER JOIN tb_usuario d ON b.id_usuario_avaliador = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE id_selecao_candidato = '" . $id_candidato . "' ", 'a.*, b.*, c.*, e.nome');

            ?>
                <div class="panel panel-default" style="border: 1px solid #A4A4A4;">
                    <div class="panel-heading">
                        <h3 class="panel-title">Seleção:</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6" style="margin-left: -5px;">
                                <br>
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="td-table"><strong>Nome:</strong></td>
                                            <td><?= $conteudo_info['nome'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Setor:</strong></td>
                                            <td><?= $conteudo_info['descricao_setor'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Número de etapas:</strong></td>
                                            <td><?= $conteudo_info['n_etapas'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Avaliadores:</strong></td>
                                            <td><?= $envolvidos ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->

                            <div class="col-md-6">
                                <br>
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="td-table"><strong>Descrição:</strong></td>
                                            <td><?= $conteudo_info['descricao'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Data:</strong></td>
                                            <td><?= converteDataHora($conteudo_info['data']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Cargo:</strong></td>
                                            <td><?= $conteudo_info['descricao_cargo'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Número de vagas:</strong></td>
                                            <td><?= $conteudo_info['n_vagas'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Status:</strong></td>
                                            <td><?= $status ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table table-responsive table-striped">
                                    <table class="table table-hover" style="font-size: 14px;">
                                        <thead>
                                            <tr>
                                                <th class="col-md-1">Foto</th>
                                                <th class="col-md-3">Nome</th>
                                                <th class="col-md-2">CPF</th>
                                                <th class="col-md-1">Etapa</th>
                                                <th class="col-md-1">Status</th>
                                                <th class="col-md-2">Cidade</th>
                                                <th class="col-md-2">Atualizado em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img src="<?= $foto ?>" class="center text-center" id="img-relatorio" height="52" width="52">
                                                </td>

                                                <td>
                                                    <span><?= $nome ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $cpf ?></span>
                                                </td>
                                                <td>
                                                    <span class="label label-default"><?= $etapa ?></span>
                                                </td>
                                                <td>
                                                    <?= $status_candidato ?>
                                                </td>
                                                <td>
                                                    <span><?= $cidade ?> - <?= $estado ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $data_atualizacao ?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if ($dados_avaliador_candidato) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="padding-bottom: 5px;">
                                                <span><strong>Informações do candidato na(s) etapa(s):</strong></span>
                                            </div>

                                            <div class="page">
                                                <div class="timeline">
                                                    <div class="timeline__group">

                                                        <?php
                                                        if ($dados_avaliador_candidato) {

                                                            foreach ($dados_avaliador_candidato as $conteudo_avaliacao) {

                                                                $data = converteDataHora($conteudo_avaliacao['data_avaliacao']);
                                                                $data = explode(" ", $data);
                                                                $dia = $data[0];
                                                                $hora = $data[1];
                                                        ?>

                                                                <div class="timeline__box">
                                                                    <div class="timeline__date">
                                                                        <span class="timeline__day"><?= $dia ?></span>
                                                                        <span class="timeline__month"><?= $hora ?></span>
                                                                    </div>
                                                                    <div class="timeline__post">
                                                                        <div class="timeline__content">
                                                                            <div class="div-span-timeline">
                                                                                <span>
                                                                                    <strong>Etapa: </strong> <?= $conteudo_avaliacao['num_etapa'] ?>
                                                                                </span>
                                                                            </div>
                                                                            <div class="div-span-timeline">
                                                                                <span>
                                                                                    <strong>Descricao: </strong> <?= $conteudo_avaliacao['descricao'] ?>
                                                                                </span>
                                                                            </div>
                                                                            <div class="div-span-timeline">
                                                                                <span>
                                                                                    <strong>Avaliador: </strong> <?= $conteudo_avaliacao['nome'] ?>
                                                                                </span><br>
                                                                            </div>
                                                                            <?php
                                                                            if ($conteudo_avaliacao['precisa_nota'] == 1) {
                                                                            ?>
                                                                                <div class="div-span-timeline">
                                                                                    <span>
                                                                                        <strong>Nota: </strong> <?= $conteudo_avaliacao['nota'] ?>
                                                                                    </span><br>
                                                                                </div>
                                                                            <?php
                                                                            }

                                                                            if ($conteudo_avaliacao['precisa_parecer'] == 1) {
                                                                            ?>
                                                                                <div class="div-span-timeline">
                                                                                    <span>
                                                                                        <strong>Parecer: </strong> <?= nl2br($conteudo_avaliacao['parecer']) ?>
                                                                                    </span><br>
                                                                                </div>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-info text-center" role="alert">
                                Candidato(a) não recebeu nota ou parecer!
                            </div>
                        <?php } ?>

                    </div>
                </div><br>
<?php
            }
        }
    } else {
        echo '<h4 class="text-center">Não foram encontrados resultados!</h4>';
    }
}

?>