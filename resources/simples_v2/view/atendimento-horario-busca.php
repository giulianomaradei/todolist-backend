<?php
require_once(__DIR__."/../class/System.php");

if($perfil_usuario == '3'){
    $atendimentos_pendentes_integracao = DBRead('', "tb_integracao_atendimento_ixc", "WHERE salvo = 0 AND id_atendente_simples = '$id_usuario'","COUNT(*) AS qtd");
}else{
    $atendimentos_pendentes_integracao = DBRead('', "tb_integracao_atendimento_ixc", "WHERE salvo = 0","COUNT(*) AS qtd");
}

?>
<style>
    .panel-body{
        background-color: #f2f2f2;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <div class="panel-title text-left pull-left col-md-4" style="margin-top: 2px;">Horários de Atendimento:</div>
                        <div class="panel-title text-center col-md-4"></div>
                        <div class="panel-title text-right pull-right col-md-4"></div>
                    </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome da empresa..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>    
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Canal de Atendimento:</label>
                                <select class="form-control input-sm" name="canal_atendimento" id="canal_atendimento" onchange="call_busca_ajax();">
                                    <option value='qualquer'>Qualquer</option>                                    
                                    <option value='telefone'>Telefone</option>
                                    <option value='texto'>Texto</option>
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Plano:</label>
                                <select class="form-control input-sm" id="plano" name="plano" onChange="call_busca_ajax();">
                                    <option value="">Qualquer</option>
                                    <?php
                                    $dados_planos = DBRead('', 'tb_plano', "WHERE cod_servico = 'call_suporte' ORDER BY cod_servico ASC, nome ASC");
                                    foreach ($dados_planos as $conteudo) {
                                        $id_select = $conteudo['id_plano'];
                                        $nome_select = $conteudo['nome'];
                                        echo "<option value='$id_select'>$nome_select</option>";
                                    }                                    
                                    ?>
                                </select>
                            </div>
                        </div>

                        <?php
                            $data_hora_agora = explode(' ', converteDataHora(getDataHora()));
                            $data_agora = $data_hora_agora[0];
                            $hora_agora = $data_hora_agora[1];
                        ?>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Data:</label>
                                <input class="form-control input-sm date calendar" type="text" name="data" id="data" value="<?=$data_agora?>"  onChange="call_busca_ajax();">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Hora:</label>
                                <input class="form-control input-sm hour" type="time" name="hora" id="hora" value="<?=$hora_agora?>" onKeyUp="call_busca_ajax();">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group btn-group-justified" role="group">
                                <?php
                                    $dados_planos = DBRead('','tb_plano', "WHERE cod_servico = 'call_suporte' AND status = '1' ORDER BY nome ASC");
                                    if($dados_planos){
                                        foreach ($dados_planos as $conteudo_plano) {
                                            if($conteudo_plano['cor'] == '#ffffff'){
                                                $style_nome = 'style = "color:#000000"';
                                            }else{
                                                $style_nome = 'style = "color:#ffffff"';
                                            }
                                            echo '
                                            <div class="btn-group" role="group">
                                                <a class="btn" style="cursor: inherit; background-image: none !important; text-shadow: 0 0 0 !important; background-color: '.$conteudo_plano['cor'].' !important; font-size:13px; padding:0;">
                                                    <span '.$style_nome.'>'.$conteudo_plano['nome'].'</span>
                                                </a>
                                            </div>
                                            ';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="conteudo"></div>
                </div>
            </div>
        </div>
    </div>
</div>  
<script>
function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var canal_atendimento = $('#canal_atendimento').val();
        var plano = $('#plano').val();
        var data = $('input[name="data"]').val();
        var hora = $('input[name="hora"]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        var parametros = {
            'nome': nome,
            'canal_atendimento': canal_atendimento,
            'plano': plano,
            'data': data,
            'hora': hora
        };
        busca_ajax('<?= $request->token ?>' , 'AtendimentoHorarioBusca', 'conteudo', parametros);
    }
    call_busca_ajax();
</script>