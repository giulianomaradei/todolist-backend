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
                        <div class="panel-title text-left pull-left col-md-3" style="margin-top: 2px;">Atendimento:</div>
                        <div class="panel-title text-left col-md-3"><a href="/api/iframe?token=<?php echo $request->token ?>&view=atendimento-horario-busca" ><button class="btn btn-info btn-sm"><i class="fa fa-clock-o"></i> Horários de Atendimento</button></a></div>
                        <div class="panel-title text-right col-md-3"><a href="/api/iframe?token=<?php echo $request->token ?>&view=integracao-atendimentos-pendentes" ><button class="btn btn-primary btn-sm"><i class="fa fa-joomla"></i> Atendimentos Pendentes (Integrações)</button></a><?php if($atendimentos_pendentes_integracao[0]['qtd']){echo ' <i id="exclamation_softphone" class="fa fa-exclamation-circle faa-flash animated" style="color: rgb(185, 44, 40);"></i>';} ?></div>
                        <div class="panel-title text-right pull-right col-md-3"><a href="/api/ajax?class=IniciarAtendimento.php" onclick="modalAguarde();"><button class="btn btn-success btn-sm"><i class="fa fa-phone"></i> Iniciar Atendimento</button></a></div>
                    </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome da empresa..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>    
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Plano:</label>
                                <select class="form-control input-sm" id="plano" name="plano"  onChange="call_busca_ajax();">
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
                            <div class="form-group has-feedback">
                                <label>Sistema de Gestão:</label>
                                <select class="form-control input-sm" name="sistema_gestao" id="sistema_gestao" onChange="call_busca_ajax();">
                                    <option value="">Qualquer</option>
                                    <?php
                                        $sistema_gestao = DBRead('', 'tb_tipo_sistema_gestao', "ORDER BY nome ASC");
                                        foreach($sistema_gestao as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_tipo_sistema_gestao']?>"><?=$conteudo['nome']?></option>
                                    <?php
                                        }
                                    ?>
                               </select>
                            </div>
                        </div>     
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Sistema de Chat:</label>
                                <select class="form-control input-sm" name="sistema_chat" id="sistema_chat" onChange="call_busca_ajax();">
                                    <option value="">Qualquer</option>
                                    <?php
                                        $sistema_chat = DBRead('', 'tb_tipo_sistema_chat', "ORDER BY nome ASC");
                                        foreach($sistema_chat as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_tipo_sistema_chat']?>"><?=$conteudo['nome']?></option>
                                    <?php
                                        }
                                    ?>
                               </select>
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
        var sistema_gestao = $('#sistema_gestao').val();
        var sistema_chat = $('#sistema_chat').val();
        var plano = $('#plano').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        var parametros = {
            'nome': nome,
            'canal_atendimento': canal_atendimento,
            'sistema_gestao': sistema_gestao,
            'sistema_chat': sistema_chat,
            'plano': plano
        };
        busca_ajax('<?= $request->token ?>' , 'AtendimentoBusca', 'conteudo', parametros);
    }
    call_busca_ajax();
</script>