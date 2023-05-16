<?php
require_once(__DIR__."/../class/System.php");

$required_categoria = 'required';
$div_alerta_monitoramento = "";
$id_alerta_painel = '';
$plano = '';
$alerta_painel = '';
$justificativa_reprovacao = '';

if (isset($_GET['alterar'])){
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_alerta', "WHERE id_alerta = $id");
    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
    $data_criacao = converteDataHora($dados[0]['data_criacao']);
    $data_ultima_acao = converteDataHora($dados[0]['data_ultima_acao']);
    $ultima_acao = $dados[0]['ultima_acao'];

    $data_inicio = converteData(substr($dados[0]['data_inicio'], 0, 10));
    $hora_inicio = substr($dados[0]['data_inicio'], 11, 5);

    $data_vencimento = converteData(substr($dados[0]['data_vencimento'], 0, 10));
    $hora_vencimento = substr($dados[0]['data_vencimento'], 11, 5);

    $id_categoria = $dados[0]['id_categoria'];
    $exibicao = $dados[0]['exibicao'];
    $conteudo = $dados[0]['conteudo'];

    $dados_usuario_criou = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = ".$dados[0]['id_usuario_criou'], "a.*, b.nome");
    $nome_usuario_criou = $dados_usuario_criou[0]['nome'];
    $dados_usuario_ultima_acao = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = ".$dados[0]['id_usuario_ultima_acao'], "a.*, b.nome");
    $nome_usuario_ultima_acao = $dados_usuario_ultima_acao[0]['nome'];

    if($id_contrato_plano_pessoa){
        $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        $plano = $dados[0]['nome_pessoa'] . " - " . getNomeServico($dados[0]['cod_servico']) . " - " . $dados[0]['plano'];
        $botao_disabled = '';
    }else{
        $plano = '';
        $input_checked = 'checked';
        $botao_disabled = 'disabled';
    }

    if($dados[0]['id_alerta_painel']){
        $alerta_geral = '';
        $tamanho_col_contrato = 'class="col-md-12"';
    }else{
        $alerta_geral = '<div class="col-md-4">
        <label>&nbsp;</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <input type="checkbox" id="check_alerta_geral" '.$input_checked.'>
                </span>
                <input type="text" class="form-control" value="Alerta Geral" readonly>
            </div>
        </div>';
        $tamanho_col_contrato = 'class="col-md-8"';
    }

    $botao_name_id = 'id="habilita_busca_contrato" name="habilita_busca_contrato"';
    $disabled = '';

    $botao_salvar = '<button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>';

} else if (isset($_GET['visualizar'])) {
    $tituloPainel = 'Visualizar';
    $operacao = 'visualizar';
    $id_alerta_painel = (int)$_GET['visualizar'];
    $id = '1';

    $dados = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = $id_alerta_painel");
    $dados_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = $id_alerta_painel");

    $justificativa_reprovacao = "";
    if ($dados_painel[0]['status'] == 2) {
        $status = "Aprovado";
        $data_resposta = converteDataHora($dados_painel[0]['data_resposta']);

    } else if ($dados_painel[0]['status'] == 3) {
        $status = "Vencido";
        $data_resposta = converteDataHora($dados_painel[0]['data_resposta']);
        $justificativa_reprovacao = '';

    }else if ($dados_painel[0]['status'] == 5) {
        $status = "Descartado";
        $data_resposta = converteDataHora($dados_painel[0]['data_resposta']);
        $justificativa_reprovacao = $dados_painel[0]['justificativa'];
    }

    if($dados){
        $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];

        $data_inicio = converteData(substr($dados[0]['data_inicio'], 0, 10));
        $hora_inicio = substr($dados[0]['data_inicio'], 11, 5);

        $data_vencimento = converteData(substr($dados[0]['data_vencimento'], 0, 10));
        $hora_vencimento = substr($dados[0]['data_vencimento'], 11, 5);

        $id_categoria = $dados[0]['id_categoria'];
        $exibicao = $dados[0]['exibicao'];

        if($status != 'Descartado'){
            $alerta_painel = $dados_painel[0]['descricao'];
            $conteudo = $dados[0]['conteudo'];
        }else{
            $conteudo = $dados_painel[0]['descricao'];
        }

    }else{
        $id_contrato_plano_pessoa = $dados_painel[0]['id_contrato_plano_pessoa'];
        $conteudo = $dados_painel[0]['descricao'];

    }

    $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
    $plano = $dados[0]['nome_pessoa'] . " - " . getNomeServico($dados[0]['cod_servico']) . " - " . $dados[0]['plano'];

    $botao_disabled = 'disabled';
    $botao_name_id = '';
    $disabled = 'disabled';

    $botao_salvar = '';

    $alerta_geral = '';
    $tamanho_col_contrato = 'class="col-md-12"';

}else if(isset($_GET['avaliar'])){
    $tituloPainel = 'Avaliar';
    $operacao = 'inserir';
    $id = '1';
    $id_alerta_painel = (int)$_GET['avaliar'];
    $dados_alerta_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = $id_alerta_painel");

    $id_contrato_plano_pessoa = $dados_alerta_painel[0]['id_contrato_plano_pessoa'];

    $dados_monitoramento = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano c ON a.id_plano = c.id_plano LEFT JOIN tb_informacao_geral_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND (d.monitoramento = '1' OR c.cod_servico = 'call_monitoramento')");

    if($dados_monitoramento){
        $div_alerta_monitoramento = '<div class="row"><div class="col-md-12"><div class="alert alert-info text-center">Cliente possui monitoramento!</div></div></div>';
    }

    $conteudo = $dados_alerta_painel[0]['descricao'];

    $exibicao = 1;
    $data_inicio = converteData(substr($dados_alerta_painel[0]['data_inicio'], 0, 10));
    $hora_inicio = substr($dados_alerta_painel[0]['data_inicio'], 11, 5);

    $data_vencimento = converteData(substr($dados_alerta_painel[0]['data_fim'], 0, 10));
    $hora_vencimento = substr($dados_alerta_painel[0]['data_fim'], 11, 5);

    $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
    $plano = $dados[0]['nome_pessoa'] . " - " . getNomeServico($dados[0]['cod_servico']) . " - " . $dados[0]['plano'];

    $botao_disabled = 'disabled';
    $botao_name_id = '';
    $disabled = '';

    $dados = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = $id_alerta_painel");
    if($dados){
        $texto_botao = "Alterar";
    }else{
        $texto_botao = "Inserir";
    }
    $botao_salvar = '<div id="panel_buttons" class="col-md-12" style="text-align: center">
                        <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fas fa-thumbs-up"></i> '.$texto_botao.' Alerta</button>
                        <button class="btn btn-warning" name="reprovar" id="reprovar" type="button" data-toggle="modal" data-target="#modal"><i class="fas fa-thumbs-down"></i> Descartar</button>
                    </div>';

    $alerta_geral = '';
    $tamanho_col_contrato = 'class="col-md-12"';

}else if (isset($_GET['cancelar'])){
    $required_categoria = '';
    $tituloPainel = 'Cancelar';
    $operacao = 'cancelar';
    $id_alerta_painel = (int)$_GET['cancelar'];
    $id = '1';

    $dados = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = $id_alerta_painel");
    $dados_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = $id_alerta_painel");

    $justificativa_reprovacao = "";
    if($dados_painel[0]['status'] == 2){
        $status = "Aprovado";
        $data_resposta = converteDataHora($dados_painel[0]['data_resposta']);
    }else if($dados_painel[0]['status'] == 3){
        $status = "Vencido";
        $data_resposta = converteDataHora($dados_painel[0]['data_resposta']);
        $justificativa_reprovacao = '';
    }else if($dados_painel[0]['status'] == 5){
        $status = "Descartado";
        $data_resposta = converteDataHora($dados_painel[0]['data_resposta']);
        $justificativa_reprovacao = $dados_painel[0]['justificativa'];
    }

    if($dados){
        $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];

        $data_inicio = converteData(substr($dados[0]['data_inicio'], 0, 10));
        $hora_inicio = substr($dados[0]['data_inicio'], 11, 5);

        $data_vencimento = converteData(substr($dados[0]['data_vencimento'], 0, 10));
        $hora_vencimento = substr($dados[0]['data_vencimento'], 11, 5);

        $id_categoria = $dados[0]['id_categoria'];
        $exibicao = $dados[0]['exibicao'];

        if($status != 'Descartado'){
            $alerta_painel = $dados_painel[0]['descricao'];
            $conteudo = $dados[0]['conteudo'];
        }else{
            $conteudo = $dados_painel[0]['descricao'];
        }

    }else{
        $id_contrato_plano_pessoa = $dados_painel[0]['id_contrato_plano_pessoa'];
        $conteudo = $dados_painel[0]['descricao'];

        $data_inicio = converteData(substr($dados_painel[0]['data_inicio'], 0, 10));
        $hora_inicio = substr($dados_painel[0]['data_inicio'], 11, 5);

        $data_vencimento = converteData(substr($dados_painel[0]['data_fim'], 0, 10));
        $hora_vencimento = substr($dados_painel[0]['data_fim'], 11, 5);

        $id_categoria = $dados_painel[0]['id_categoria'];
        $exibicao = $dados_painel[0]['id_situacao'];

    }

    $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
    $plano = $dados[0]['nome_pessoa'] . " - " . getNomeServico($dados[0]['cod_servico']) . " - " . $dados[0]['plano'];

    $botao_disabled = 'disabled';
    $botao_name_id = '';
    $disabled = 'disabled';

    $botao_salvar = '<div id="panel_buttons" class="col-md-12" style="text-align: center">
                        <button class="btn btn-danger" name="cancelar" id="cancelar" type="submit" value = 1><i class="fas fa-times"></i> Cancelar</button>
                    </div>';
    $alerta_geral = '';
    $tamanho_col_contrato = 'class="col-md-12"';
}else{
        $tituloPainel = 'Inserir';
        $operacao = 'inserir';
        $id = 1;
        $id_contrato_plano_pessoa = 0;
        $data_criacao = '';
        $data_inicio = '';
        $hora_inicio = '';
        $data_vencimento = '';
        $hora_vencimento = '';
        $id_categoria = 1;
        $conteudo = '
    NOTIFICAÇÃO DE PARADA

    - MOTIVO:
    - NOTIFICAR:
    - PERTENCEM A CIDADE:
    - FINALIZAÇÃO:
        ';
        $exibicao = 1;

        $botao_disabled = '';
        $botao_name_id = 'id="habilita_busca_contrato" name="habilita_busca_contrato"';
        $disabled = '';

        $botao_salvar = '<button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>';
        $alerta_geral = '<div class="col-md-4">
        <label>&nbsp;</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <input type="checkbox" id="check_alerta_geral">
                </span>
                <input type="text" class="form-control" value="Alerta Geral" readonly>
            </div>
        </div>';
        $tamanho_col_contrato = 'class="col-md-8"';

}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title text-left pull-left col-md-2"><?= $tituloPainel ?> alerta:</h3>

                    <?php if(isset($_GET['alterar'])){ 
                        echo "<h3 class=\"panel-title text-center col-md-4\"><strong>Criado por:</strong> $nome_usuario_criou ($data_criacao)</h3><h3 class=\"panel-title text-center col-md-4\"><strong>Última ação:</strong> $nome_usuario_ultima_acao ($ultima_acao - $data_ultima_acao)</h3><div class=\"panel-title text-right pull-right col-md-2\"><a  href=\"/api/ajax?class=Alerta.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 

                    } ?>

                    <?php if(isset($_GET['visualizar'])){
                        echo "<h3 class=\"panel-title text-center col-md-6\">$status ($data_resposta)</h3>";
                    } ?>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=Alerta.php" id="alerta_form" style="margin-bottom: 0;">
                    <input type="hidden" name="id_alerta_painel" id="id_alerta_painel" value="<?=$id_alerta_painel;?>" />
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body">
                        <?=$div_alerta_monitoramento?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <div <?=$tamanho_col_contrato?>>
                                        <div class="form-group">
                                            <label>*Contrato (cliente):</label>
                                            <div class="input-group">
                                                <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" value="<?=$plano?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info btn-sm" <?= $botao_name_id ?> type="button" title="Clique para selecionar o contrato" style="height: 30px;" <?= $botao_disabled ?> ><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
                                        </div>
                                    </div>

                                    <?= $alerta_geral;?>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>*Exibição:</label>
                                            <select class="form-control input-sm" name="exibicao" <?= $disabled ?>>
                                                
                                                <option value='1' <?= $exibicao == 1 ? "selected" : "" ?>>Atendimento - Todo</option>";
                                                <option value='2' <?= $exibicao == 2 ? "selected" : "" ?>>Atendimento - Somente na finalização</option>";
                                                <option value='3' <?= $exibicao == 3 ? "selected" : "" ?>>Atendimento - Somente no início</option>";
                                                <option value='4' <?= $exibicao == 4 ? "selected" : "" ?>>Monitoramento - Todo</option>";
                                                <option value='5' <?= $exibicao == 5 ? "selected" : "" ?>>Atendimento - Todo | Monitoramento - Todo</option>";
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>*Categoria:</label>
                                            <select class="form-control input-sm" id="id_categoria" name="id_categoria" <?= $required_categoria ?> <?= $disabled ?>>
                                                <option value=""></option>
                                                <?php
                                                    $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_alerta = 1 ORDER BY nome ASC");
                                                    if($dados_categoria){
                                                        foreach($dados_categoria as $categoria){
                                                            $idCategoria = $categoria['id_categoria'];
                                                            $nomeSelect = $categoria['nome'];
                                                            $selected = $id_categoria == $idCategoria ? "selected" : "";
                                                            echo "<option value='$idCategoria'".$selected.">$nomeSelect</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Data Início:</label>
                                            <input name="data_inicio" type="text" class="form-control input-sm date calendar" value="<?= $data_inicio; ?>" autocomplete="off" <?= $disabled ?> />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Hora Início:</label>
                                            <input name="hora_inicio" type="time" class="form-control input-sm" value="<?= $hora_inicio; ?>" autocomplete="off" <?= $disabled ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Data Vencimento:</label>
                                            <input name="data_vencimento" type="text" class="form-control input-sm date calendar" value="<?= $data_vencimento; ?>" autocomplete="off" <?= $disabled ?> />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Hora Vencimento:</label>
                                            <input name="hora_vencimento" type="time" class="form-control input-sm" value="<?= $hora_vencimento; ?>" autocomplete="off" <?= $disabled ?> />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label>*Conteúdo</label>
                                <textarea required class="form-control" style="height: 240px;" name="conteudo" id="conteudo" <?= $disabled ?>><?= $conteudo ?></textarea>
                            </div>
                        </div>
                        <?php if($alerta_painel){ ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Conteúdo Proposto:</label>
                                <textarea class="form-control" style="height: 240px;" <?= $disabled ?>><?= $alerta_painel ?></textarea>
                            </div>
                        </div>
                        <?php }

                        if($justificativa_reprovacao){ ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Justificativa de Descarte</label>
                                <textarea class="form-control" style="height: 240px;" <?= $disabled ?>><?= $justificativa_reprovacao ?></textarea>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <?=$botao_salvar?>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Justificar a Reprovação</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" >
                                                <label>*Justificativa:</label>
                                                <textarea name="justificativa" id="justificativa" class="form-control input-sm" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <div class="row">
                                        <div class="col-md-12" style="text-align: center">
                                            <button class="btn btn-primary" id="reprovar_modal" value="2" type="submit">
                                                <i class="fa fa-check"></i> Inserir Justificativa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).on('click', '#check_alerta_geral', function () {

        if($(this).is(':checked')){
            $('#busca_contrato').attr('required', false);
            $('#id_contrato_plano_pessoa').val('');
            $('#busca_contrato').val('');
            $('#habilita_busca_contrato').attr('disabled', true);
            $('#busca_contrato').attr("readonly", true);
        }else{
            $('#busca_contrato').attr('required', true);

            var id_contrato_plano_pessoa = '<?=$id_contrato_plano_pessoa?>';
            $('#id_contrato_plano_pessoa').val(id_contrato_plano_pessoa);

            var plano = '<?=$plano?>';
            $('#busca_contrato').val(plano);

            $('#habilita_busca_contrato').attr('disabled', false);




        }
    });

    $(document).on('click', '#reprovar_modal', function () {

        $("#operacao").attr('name', 'reprovar');

        $('#conteudo').prop('required',false);
        $('#id_categoria').prop('required',false);

        var justificativa = $('#justificativa').val();

        if(!justificativa || justificativa == ''){
            alert("Insira a justificativa!");
            return false;
        }
    });

    $(document).on('click', '#ok', function () {

        $('#conteudo').prop('required',true);
        $('#id_categoria').prop('required',true);

        var conteudo = $('#conteudo').val();
        var id_categoria = $('#id_categoria').val();

        if(!conteudo || conteudo == ''){
            alert("Insira o conteudo!");
            return false;
        }

        if(!id_categoria || id_categoria == ''){
            alert("Selecione a categoria!");
            return false;
        }
    });

    $(document).on('submit', '#alerta_form', function (){
        modalAguarde();
    });

    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: {
                            'nome' : $('#busca_contrato').val(),
                            'cod_servico' : 'call_suporte',
                            'pagina' : 'alerta'
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);
                alert('Hora no cliente selecionado: '+ui.item.hora_contrato);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            } if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') ';
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id) {
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato() {
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function () {
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    $(document).ready(function(){
        $('.table_paginas tr').click(function(event){
            if (event.target.type !== 'checkbox'){
                $(':checkbox', this).trigger('click');
            }
        });
    });
</script>