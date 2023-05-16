<?php
require_once(__DIR__."/../class/System.php");
require_once __DIR__."/../class/AtendimentoIntegracao.php";

$operacao = 'inserir';
$id = 1;
$id_contrato_plano_pessoa = 0;
$data_criacao = '';
$hora_criacao = '';
$data_inicio = '';
$hora_inicio = '';
$data_vencimento = '';
$hora_vencimento = '';
$id_categoria = 1;
$conteudo = '';
$exibicao = 1;

?>
<style>
.popover{
    width: 1000px !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix" role="tab" id="headingOne">
                        <div class="row">
                            <h3 class="panel-title text-left pull-left col-md-6">Atendimentos Pendentes (integrações):</h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        
                            <?php
                            $atendimentos_pendentes = DBRead('', "tb_integracao_atendimento_ixc a", "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE salvo = 0");
                            
                            if($atendimentos_pendentes):
                                echo '<ul class="list-group">';
                                foreach($atendimentos_pendentes as $retorno):

                                    $atendente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '".$retorno['id_atendente_simples']."'", "a.nome");
                            ?>
                                    <li class="list-group-item" id="opcao-<?=$retorno['id_integracao_atendimento_ixc']?>">

                                        <?php
                                        if(($perfil_usuario == "3" && $retorno['id_atendente_simples'] == $_SESSION['id_usuario']) || $perfil_usuario != "3"):
                                        ?>

                                        <input type="hidden" name="id_integracao_atendimento_ixc" id="id_integracao_atendimento_ixc-<?=$retorno['id_integracao_atendimento_ixc']?>" class="id_integracao_atendimento_ixc" value="<?=$retorno['id_integracao_atendimento_ixc']?>" />
                                        <input type="hidden" name="titulo" class="titulo" id="titulo-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['titulo'] ?>" />
                                        <input type="hidden" name="assinante" class="nome-assinante" id="nome-assinante-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['nome_assinante'] ?>" />
                                        <input type="hidden" name="id_cliente_integracao" class="id-cliente" id="cliente-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['id_cliente'] ?>" />
                                        <input type="hidden" class="origem-endereco" id="origem-endereco-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['origem_endereco'] ?>" />
                                        <input type="hidden" class="prioridade" id="prioridade-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['prioridade'] ?>" />
                                        <input type="hidden" class="id-ticket-origem" id="id-ticket-origem-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['id-ticket-origem'] ?>" />
                                        <input type="hidden" class="mensagem" id="mensagem-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['mensagem'] ?>" />
                                        <input type="hidden" class="su-status" id="su-status-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['su_status'] ?>" />
                                        <input type="hidden" class="status" id="status-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['status'] ?>" />
                                        <input type="hidden" name="cadastro_os" class="cadastro-os" id="cadastro-os-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['cadastroOS'] ?>" />
                                        <input type="hidden" name="id_contrato_plano_pessoa" class="id-contrato-plano-pessoa" id="id-contrato-plano-pessoa-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['id_contrato_plano_pessoa'] ?>" />
                                        <input type="hidden" class="id-assunto" id="id-assunto-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['id_assunto'] ?>" />
                                        <input type="hidden" class="id-ticket-setor" id="id-ticket-setor-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['id_ticket_setor'] ?>" />
                                        <input type="hidden" class="setor" id="setor-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['setor'] ?>" />
                                        <input type="hidden" name="data_inicio" class="data-inicio" id="data-inicio-<?= $retorno['id_integracao_atendimento_ixc'] ?>" value="<?= $retorno['data_inicio'] ?> 00:00:00" />
                                        <input type="hidden" name="operacao" value="alterar" />
                                        <input type="hidden" name="id_atendimento" value="<?= $retorno['id_atendimento_belluno'] ?>" />

                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Cliente:</label> <?= $retorno['nome'] ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Atendente:</label> <?= $atendente[0]['nome'] ?>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Sistema:</label> IXCSoft
                                            </div>
                                            <div class="col-md-3">
                                                <label>Hora do atendimento:</label> <?= converteDataHora($retorno['data_final']) ?>
                                            </div>
                                            <div class="col-md-1">
                                                <!--<button class="btn btn-primary btn-xs pull-right verificar" id="<?=$retorno['id_integracao_atendimento_ixc']?>" data_assinante="<?=$retorno['nome_assinante']?>" type="button" data_cadastro_os="<?= $retorno['cadastroOS'] ?>" aria-expanded="false" aria-controls="modal-atendimento-pendente-<?=$retorno['id_integracao_atendimento_ixc']?>">Verificar</button>-->
                                                <a href="/api/iframe?token=<?php echo $request->token ?>&view=integracao-atendimentos-pendentes-verificacao&id_integracao_atendimento_ixc=<?= $retorno['id_integracao_atendimento_ixc'] ?>&nome_provedor=<?= $retorno['nome'] ?>" class="btn btn-primary btn-xs pull-right verificar">Verificar</a>
                                            </div>
                                        </div>

                                        <?php
                                        endif;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        ?>

                                        <div class="row">
                                            <div class="panel-collapse collapse" id="modal-atendimento-pendente-<?=$retorno['id_integracao_atendimento_ixc']?>" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="well">
                                                    <div class="quadro-sistema-gestao row">
                                                        <?php
                                                        $id_contrato_plano_pessoa = $retorno['id_contrato_plano_pessoa'];

                                                        $dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
                                                        $retorno_assunto = array();
                                                        $retorno_departamento = array();
                                                        $retorno_filial = array();
                                                        $retorno_setor = array();
                                                        $retorno_tecnico = array();
                                                        if($dados_obrigatorios){
                                                            foreach($dados_obrigatorios as $key => $conteudo){
                                                                if($conteudo['chave'] == "assunto"){
                                                                    $retorno_assunto['registros'][$key]['id'] = $conteudo['valor_id'];
                                                                    $retorno_assunto['registros'][$key]['assunto'] = $conteudo['valor_descricao'];
                                                                }
                                                                if($conteudo['chave'] == "departamento"){
                                                                        $retorno_departamento['registros'][$key]["id"] = $conteudo['valor_id'];
                                                                        $retorno_departamento['registros'][$key]["setor"] = $conteudo['valor_descricao'];
                                                                }
                                                                if($conteudo['chave'] == "filial"){
                                                                    $retorno_filial['registros'][$key]["id"] = $conteudo['valor_id'];
                                                                    $retorno_filial['registros'][$key]["razao"] = $conteudo['valor_descricao'];
                                                                }
                                                                if($conteudo['chave'] == "setor"){
                                                                    $retorno_setor['registros'][$key]["id"] = $conteudo['valor_id'];
                                                                    $retorno_setor['registros'][$key]["setor"] = $conteudo['valor_descricao'];
                                                                }
                                                                if($conteudo['chave'] == "funcionario"){
                                                                    $retorno_tecnico['registros'][$key]["id"] = $conteudo['valor_id'];
                                                                    $retorno_tecnico['registros'][$key]["funcionario"] = $conteudo['valor_descricao'];
                                                                }
                                                            }
                                                            $retorno_assunto = json_encode($retorno_assunto);
                                                            $retorno_departamento = json_encode($retorno_departamento);
                                                            $retorno_filial = json_encode($retorno_filial);
                                                            $retorno_setor = json_encode($retorno_setor);
                                                            $retorno_tecnico = json_encode($retorno_tecnico);
                                                        }

                                                        $finalizacao_sistema_gestao = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "classificacao_atendimento_sistema_gestao, selecao_finalizacao_sistema_gestao");

                                                        echo "<div class='col-md-6' style='padding-right: 5px;'>";
                                                            echo "<div class='bloco-final-atendimento'>";
                                                                echo "<h4>Classificação de atendimento no sistema de gestão:</h4>";
                                                                echo "<p class='text-warning'>".nl2br($finalizacao_sistema_gestao[0]['classificacao_atendimento_sistema_gestao'])."</p>";
                                                            echo "</div>";
                                                        echo "</div>";
                                                        echo "<div class='col-md-6' style='padding-left: 5px;'>";
                                                            echo "<div class='bloco-final-atendimento'>";
                                                                echo "<h4>Seleção de finalização no sistema de gestão:</h4>";
                                                                echo "<p class='text-warning'>".nl2br($finalizacao_sistema_gestao[0]['selecao_finalizacao_sistema_gestao'])."</p>";
                                                            echo "</div>";
                                                        echo "</div>";
                                                        ?>
                                                    </div>
                                                    
                                                    <?php
                                                    //if($retorno['cadastroOS'] != 'acao_os'):
                                                    ?>
                                                    <div class="campos-atendimento">
                                                        
                                                        <div class="row" id="parametros-integracao">
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Assinante:</label>
                                                                <input type="text" class="form-control" id="mostra-assinante-<?=$retorno['id_integracao_atendimento_ixc']?>" disabled />
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Assunto:</label>
                                                                <select class="form-control select_assunto" name='id_assunto' id='assunto-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>'>
                                                                    <option></option>
                                                                    <?php
                                                                    if($dados_obrigatorios){
                                                                        foreach($dados_obrigatorios as $conteudo){
                                                                            if($conteudo['chave'] == "assunto"){
                                                                                $selected = '';
                                                                                if($conteudo['valor_id'] == $retorno['id_assunto']){
                                                                                    $selected = 'selected';
                                                                                }
                                                                                echo "<option $selected value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Origem do endereço:</label>
                                                                <select class="form-control" name="origem_endereco" id="origem-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>">
                                                                    <option value=""></option>
                                                                    <option id="cliente" <?= $retorno['origem_endereco'] == "C" ? 'selected' : "" ?> value="C">Cliente</option>
                                                                    <option id="login" <?= $retorno['origem_endereco'] == "L" ? 'selected' : "" ?> value="L">Login</option>
                                                                    <option id="contrato" <?= $retorno['origem_endereco'] == "CC" ? 'selected' : "" ?> value="CC">Contrato</option>
                                                                    <option id="manual" <?= $retorno['origem_endereco'] == "M" ? 'selected' : "" ?> value="M">Manual</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Prioridade:</label>
                                                                <select class="form-control" name="prioridade" id="prioridade-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>">
                                                                    <option  value=""></option>
                                                                    <option <?= $retorno['prioridade'] == "B" ? 'selected' : "" ?> value="B">Baixa</option>
                                                                    <option <?= $retorno['prioridade'] == "N" ? 'selected' : "" ?> value="N">Normal</option>
                                                                    <option <?= $retorno['prioridade'] == "A" ? 'selected' : "" ?> value="A">Alta</option>
                                                                    <option <?= $retorno['prioridade'] == "C" ? 'selected' : "" ?> value="C">Crítica</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Setor:</label>
                                                                <select class="form-control setor" id="setor-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>" name="id_setor">
                                                                    <option></option>
                                                                    <?php
                                                                    if($dados_obrigatorios){
                                                                        foreach($dados_obrigatorios as $conteudo){
                                                                            if($conteudo['chave'] == "setor"){
                                                                                $selected = '';
                                                                                if($conteudo['valor_id'] == $retorno['setor']){
                                                                                    $selected = 'selected';
                                                                                }
                                                                                echo "<option $selected value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Filial:</label>
                                                                <select class="form-control select_filial" name="id_filial" id="filial-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>">
                                                                    <option></option>
                                                                    <?php
                                                                    if($dados_obrigatorios){
                                                                        foreach($dados_obrigatorios as $conteudo){
                                                                            if($conteudo['chave'] == "filial"){
                                                                                $selected = '';
                                                                                if($conteudo['valor_id'] == $retorno['id_filial']){
                                                                                    $selected = 'selected';
                                                                                }
                                                                                echo "<option $selected value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px;">
                                                                <label>Técnico responsável:</label>
                                                                <select class="form-control select_tecnico" name="tecnico_responsavel" id="tecnico-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>">
                                                                    <option></option>
                                                                    <?php
                                                                     if($dados_obrigatorios){
                                                                        foreach($dados_obrigatorios as $conteudo){
                                                                            if($conteudo['chave'] == "funcionario"){
                                                                                $selected = '';
                                                                                if($conteudo['valor_id'] == $retorno['id_tecnico']){
                                                                                    $selected = 'selected';
                                                                                }
                                                                                echo "<option $selected value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px; display: block;" id="bloco-contrato-ixc">
                                                                <label>Contrato:</label>
                                                                <select class="form-control select_contrato" name="id_contrato" id="select_contrato-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>"></select>
                                                            </div>
                                                            <div class="col-md-4" style="margin-top: 10px; display: block;" id="bloco-login">
                                                                <label>Login:</label>
                                                                <select class="form-control select_login" name="id_login" id="select_login-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>"></select>
                                                            </div>
                                                        </div>

                                                        <div id="bloco-evento-integracao" style="display: none;">
                                                            <ul class="list-group" style="padding-bottom: 15px;">
                                                                <div class="col-md-12" style="margin-bottom: 10px;">
                                                                    <label for="evento">Evento:</label>
                                                                    <select class="form-control evento" id="evento-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>" name="evento">
                                                                        
                                                                    </select>
                                                                </div>

                                                                <div id="bloco-evento-integracao-atendimentos"></div>
                                                                <div id="bloco-evento-integracao-os"></div>
                                                            </ul>
                                                        </div>

                                                        <!--fim do teste-->
                                                    </div>
                                                    <?php
                                                    //elseif($retorno['cadastroOS'] == 'acao_os'):
                                                        
                                                    //endif;
                                                    ?>

                                                    <div class="row" style="padding-top:10px;">
                                                        <div class="col-md-6">
                                                            <label>Situação:</label>
                                                            <select style="margin-bottom: 10px;" name="situacao" class="form-control clipboard_select" id="select-situacao-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>" required="">
                                                                <option value="">Selecione uma situação para o atendimento!</option>
                                                                <option <?= $retorno['situacao'] == "4" ? 'selected' : "" ?> value="4">ATENDIMENTO ENCAMINHADO AO SETOR RESPONSÁVEL.</option>
                                                                <option <?= $retorno['situacao'] == "3" ? 'selected' : "" ?> value="3">ATENDIMENTO ENCERRADO.</option>
                                                                <option <?= $retorno['situacao'] == "7" ? 'selected' : "" ?> value="7">ATENDIMENTO VINCULADO A OS JÁ EXISTENTE.</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>*Classificação:</label>
                                                            <select name="classificacao" class="form-control" id="select-classificacao-<?=$retorno['id_integracao_atendimento_ixc']?>">
                                                                <option></option>
                                                                <option value="1">Ordem de serviço</option>
                                                                <option value="2">Atendimento</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Fluxo:</label>
                                                            <textarea class="form-control textarea-mensagem" id="mensagem-form-<?= $retorno['id_integracao_atendimento_ixc'] ?>" rows="5" name="os"><?=$retorno['mensagem']?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <button type="button" style="margin-top: 15px; margin-right: 15px;" id="enviar-<?= $retorno['id_integracao_atendimento_ixc'] ?>" class="btn btn-success btn-sm pull-right enviar">Enviar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                            <?php      
                                endforeach;
                                echo "</ul>";
                            else:
                            ?>
                                <div class="alert alert-info text-center" style="margin-bottom:0;">Não há atendimentos pendentes!</div>
                            <?php
                            endif;
                            ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>

    $(document).ready(function(){
        var situacao = $(".clipboard_select").val();
    });

    <?php
    $parametros = DBRead('', 'tb_integracao_valores_tipo_parametro a', "INNER JOIN tb_integracao_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato_parametro c ON b.id_integracao_parametro = c.id_integracao_parametro INNER JOIN tb_integracao_contrato d ON c.id_integracao_contrato = d.id_integracao_contrato WHERE d.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

    $salvaEmOS = "'nao'";

    foreach($parametros as $parametro){
        if($parametro['codigo'] == "cadastroAtendimentoVinculado" && $parametro['valor'] == "os"){
            $salvaEmOS = "'sim'";
        }
    }
    ?>

    salvaEmOS = <?=$salvaEmOS?>;

    $(".clipboard_select").on('change', function(){
        situacao = $(this).val();
        //Opção 7 - ATENDIMENTO VINCULADO A OS JÁ EXISTENTE
        if(situacao == 7 && salvaEmOS == 'sim'){
            $("#bloco-evento-integracao").css("display", "block");
            $("#parametros-integracao").css("display", "none");
            abreBlocoEventoIntegracao();
        }else if($(this).val() == 4 || $(this).val() == 3 || ($(this).val() == 7 && salvaEmOS == 'nao')){
            $("#bloco-evento-integracao").css("display", "none");
            $("#parametros-integracao").css("display", "block");
        }else{
            $('#novo-atendimento').css("display", "none");
            $('#atendimento-ja-existente').css("display", "none");
        }
    });

    $(".verificar").on("click", function(){
        var cadastro_os = $(this).attr("data_cadastro_os");
        id = $(this).attr('id');
        $("#mostra-assinante-"+id).val($("#nome-assinante-"+id).val());
        assunto_check = $("#id-assunto-"+id).val();
        $(".select_login").html('');

        $("#enviar-"+id).on("click", function(){
            $.ajax({
                type: "POST",
                url: "/api/ajax?class=IntegracaoAtendimentosPendentes.php",
                dataType: "json",
                data: {
                    acao: "envia-atendimento-pendente",
                    id_cliente: $("#cliente-"+id).val(),
                    classificacao: $("#select-classificacao-"+id).val(),
                    nome_assinante: $("#nome-assinante-"+id).val(),
                    titulo: $("#titulo-"+id).val(),
                    descricao_assunto: $("#assunto-form-"+id).val(),
                    origem_endereco: $("#origem-form-"+id).val(),
                    departamento: $("#setor-form-"+id).val(),
                    id_login: $("#select_login-form-"+id).val(),
                    prioridade: $("#prioridade-form-"+id).val(),
                    mensagem: $("#mensagem-form-"+id).val(),
                    id_filial: $("#filial-form-"+id).val(),
                    situacao: $("#select-situacao-form-"+id).val(),
                    id_integracao_atendimento_ixc: $("#id_integracao_atendimento_ixc-"+id).val(),
                    id_contrato: $("#select_contrato-form-"+id).val(),
                    id_contrato_plano_pessoa: $("#id-contrato-plano-pessoa-"+id).val(),
                    evento: $("#evento-form-"+id).val(),
                    tecnico_responsavel: $("#tecnico-form-"+id).val(),
                    id_atendimento_sistema_gestao: $("input[name='id_atendimento_sistema_gestao']:checked").val(),
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    console.log(data);
                }
            });
            $("li#opcao-"+id).remove();
        });
        
        //busca todos os campos obrigatório para o.s. ou atendimento
        $.ajax({
            type: "POST",
            url: "/api/ajax?class=IntegracaoAtendimentosPendentes.php",
            dataType: "json",
            data: {
                id_contrato_plano_pessoa: $("#id-contrato-plano-pessoa-"+id).val(),
                acao: "obtem-dados-obrigatorios",
                token: '<?= $request->token ?>'
            },
            success: function(data){
                var assunto = '<option></option>';
                var setor = '<option></option>';

                var filial = '<option></option>';
                var funcionario = '<option></option>';

                $.each(data, function(i){
                    if(data[i].chave == 'assunto'){
                        assunto += "<option value='"+data[i].valor_id+"'>"+data[i].valor_descricao+"</option>";
                    }

                    //verifica se é cadastrado na aba o.s. do ixc para setar departamento ou se é na aba atendimento para setar setor
                    if(cadastro_os == 'os'){
                        if(data[i].chave == 'departamento'){
                            setor += "<option value='"+data[i].valor_id+"'>"+data[i].valor_descricao+"</option>";
                        }
                    }else if(cadastro_os == 'atendimento'){
                        if(data[i].chave == 'setor'){
                            setor += "<option value='"+data[i].valor_id+"'>"+data[i].valor_descricao+"</option>";
                        }
                    }

                    if(data[i].chave == 'filial'){
                        filial += "<option value='"+data[i].valor_id+"'>"+data[i].valor_descricao+"</option>";
                    }
                    if(data[i].chave == 'funcionario'){
                        funcionario += "<option value='"+data[i].valor_id+"'>"+data[i].valor_descricao+"</option>";
                    }
                });

            },
        });

        //usa o assinante para posteriormente buscar o seu(s) cotrato(s)
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=integracoes/AssinanteIxcAutocomplete.php",
            dataType: "json",
            data: {
                acao: 'autocomplete',
                parametros: {
                    nome : $(this).attr("data_assinante"),
                    id_contrato_plano_pessoa: $("#id-contrato-plano-pessoa-"+id).val()
                },
                token: '<?= $request->token ?>'
            },
            success: function(data){
                id_assinante = data.registros[0].id;
            },
            complete: function(){
                $.ajax({
                    type: "GET",
                    url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                    dataType: "json",
                    data: {
                        acao: "busca_contrato_cliente_assinante",
                        id_contrato_plano_pessoa: $("#id-contrato-plano-pessoa-"+id).val(),
                        id_assinante: id_assinante,
                        token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        var contrato = '<option value="0"></option>';
                        $.each(data.registros, function(i){
                            contrato += "<option value='"+data.registros[i]['id']+"'>"+data.registros[i]['contrato']+"</option>";
                        });
                        $(".select_contrato").html(contrato);
                    }
                });
            }
        });

        //utiliza o select de contrato para buscar todos os logins vinculados a aquele contrato
        $(".select_contrato").on("change", function(){
            var id_contrato = $(this).val();
            $(".select_login").html('');
            if(id_contrato != ''){
                $.ajax({
                    type: "GET",
                    url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                    dataType: "json",
                    data: {
                        acao: "busca_login",
                        id_contrato_plano_pessoa: $("#id-contrato-plano-pessoa-"+id).val(),
                        id_contrato: id_contrato,
                        token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        if(data.registros){
                            var login = '<option></option>';
                            $.each(data.registros, function(i){
                                login += "<option value='"+data.registros[i].id+"'>"+data.registros[i].login+"</option>";
                            });
                            $(".select_login").html(login);
                        }
                    }
                });
            }else{
                $(".select_login").html('');
            }
        });

    });
</script>