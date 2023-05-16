<?php
require_once(__DIR__."/../class/System.php");

$id_monitoria_mes = (!empty($_GET['id_monitoria_mes'])) ? $_GET['id_monitoria_mes'] : '';
$id_analista = (!empty($_SESSION['id_usuario'])) ? $_SESSION['id_usuario'] : '';

if ($id_monitoria_mes != '') {
    $dados = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes");

    $tipo_monitoria = $dados[0]['tipo_monitoria'];
    $sel_tipo[$tipo_monitoria] = 'selected';

    $classificacao_atendente = $dados[0]['classificacao_atendente'];
    $sel_classificacao[$classificacao_atendente] = 'selected';

} else {
    $sel_tipo[1] = 'selected';
    $sel_classificacao[3] = 'selected';
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Avaliação</h3>                   
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Buscar:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe a resposta..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Canal de atendimento:</label>
                                <select class="form-control" name="tipo" id="tipo" onChange="call_busca_ajax();">
                                    <option value="1" <?= $sel_tipo[1] ?>>Telefone</option>
                                    <option value="2" <?= $sel_tipo[2] ?>>Texto</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Classificação de atendente:</label>
                                <select class="form-control" name="classificacao" id="classificacao" onChange="call_busca_ajax();">
                                    <option value="1" <?= $sel_classificacao[1] ?>>Em treinamento</option>
                                    <option value="2" <?= $sel_classificacao[2] ?>>Periodo de experiência</option>
                                    <option value="3" <?= $sel_classificacao[3] ?>>Efetivado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Analista:</label>
                                <select class="form-control" name="analista" id="analista" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                <?php
                                    $dados_analistas = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_perfil_sistema = 14 ORDER BY nome ASC", "a.id_usuario, b.nome");

                                    if ($dados_analistas) {
                                        foreach ($dados_analistas as $conteudo_analista) {
                                            $selected = $id_analista == $conteudo_analista['id_usuario'] ? "selected" : "";
                                            echo "<option value='".$conteudo_analista['id_usuario']."' ".$selected." >" . $conteudo_analista['nome'] . "</option>";
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

<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var tipo = $('#tipo').val();
        var classificacao = $('#classificacao').val();
        var analista = $('#analista').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'tipo': tipo,
            'analista': analista,
            'classificacao': classificacao

        };
        busca_ajax('<?= $request->token ?>' , 'MonitoriaAvaliacaoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>