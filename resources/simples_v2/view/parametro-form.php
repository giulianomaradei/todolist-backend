<?php
require_once(__DIR__."/../class/System.php");


$ativacao = (int)$_GET['ativacao'];
if($_GET['id_contrato']){
    $id_contrato_plano_pessoa = (int)$_GET['id_contrato'];
}else{
    $id_contrato_plano_pessoa = (int)$_POST['id_contrato_plano_pessoa'];
}

if($ativacao == 1){
    $focus = "autofocus";
}

$verifica_contrato = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
if(!$verifica_contrato && $ativacao == 1){
<<<<<<< HEAD
    echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
=======
    echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
    exit;
}

if ($perfil_usuario != 13 && $perfil_usuario != 15) {
    $display_row = 'display: block;';

} else {
    $display_row = 'display: none;';
}

if(isset($_GET['alterar'])){
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_parametros', "WHERE id_parametros = $id");

    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $solicitacao_dados = $dados[0]['solicitacao_dados'];
        $solicitacao_dados_descricao = $dados[0]['solicitacao_dados_descricao'];
        $solicitacao_cpf = $dados[0]['solicitacao_cpf'];
        $ramal_retorno = $dados[0]['ramal_retorno'];
        $prefixo_telefone = $dados[0]['prefixo_telefone'];
        $enviar_email = $dados[0]['enviar_email'];
        $exibir_protocolo = $dados[0]['exibir_protocolo'];
        $id_asterisk = $dados[0]['id_asterisk'];
        $email_envio = $dados[0]['email_envio'];
        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];
        $horario_belluno = $dados[0]['horario_belluno'];
        $id_central = $dados[0]['id_tipo_central_telefonica'];
        $channel_name = $dados[0]['channel_name'];

        $retorno_valido_para = $dados[0]['retorno_valido_para'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        
        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
        
    }else{
<<<<<<< HEAD
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
=======
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
        exit;
    }

}else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $solicitacao_dados = '';
    $solicitacao_dados_descricao = '';
    $solicitacao_cpf = 0;
    $ramal_retorno = '';
    $prefixo_telefone = '';
    $enviar_email = 0;
    $enviar_ativo_email = 0;
    $exibir_protocolo = 0;
    $id_asterisk = '';
    $email_envio = '';
    $retorno_valido_para = 3;

    if($ativacao == 1){

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    }
}
?>
<div class="container-fluid">

    <?php
    if($ativacao):
        $dados_sistema_gestao_contrato_li = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa ORDER BY id_sistema_gestao_contrato desc");
        $id_sistema_gestao_contrato_li = $dados_sistema_gestao_contrato_li[0]['id_sistema_gestao_contrato'];
        echo "<input type='hidden' id='id_sistema_gestao_contrato_li' name='pular' value='$id_sistema_gestao_contrato_li' />";

        $dados_sistema_chat_contrato_li = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_sistema_chat_contrato_li = $dados_sistema_chat_contrato_li[0]['id_sistema_chat_contrato'];
        echo "<input type='hidden' id='id_sistema_chat_contrato_li' name='pular' value='$id_sistema_chat_contrato_li' />";

        $dados_informacao_geral_contrato_li = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_informacao_geral_contrato_li = $dados_informacao_geral_contrato_li[0]['id_informacao_geral_contrato'];
        echo "<input type='hidden' id='id_dados_informacao_geral_contrato_li' name='pular' value='$id_dados_informacao_geral_contrato_li' />";

        $dados_localizacao_contrato_li = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_localizacao_contrato_li = $dados_localizacao_contrato_li[0]['id_localizacao_contrato'];
        echo "<input type='hidden' id='id_dados_localizacao_contrato_li' name='pular' value='$id_dados_localizacao_contrato_li' />";

        $dados_plantonista_contrato_li = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_plantonista_contrato_li = $dados_plantonista_contrato_li[0]['id_plantonista_contrato'];
        echo "<input type='hidden' id='id_dados_plantonista_contrato_li' name='pular' value='$id_dados_plantonista_contrato_li' />";

        $dados_horario_contrato_li = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 1");
        $id_dados_horario_contrato_li = $dados_horario_contrato_li[0]['id_horario_contrato'];
        echo "<input type='hidden' id='id_dados_horario_contrato_li' name='pular' value='$id_dados_horario_contrato_li' />";

        $dados_prazo_retorno_contrato_li = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 1");
        $id_dados_prazo_retorno_contrato_li = $dados_prazo_retorno_contrato_li[0]['id_prazo_retorno_contrato'];
        echo "<input type='hidden' id='id_dados_prazo_retorno_contrato_li' name='pular' value='$id_dados_prazo_retorno_contrato_li' />";

        $dados_configuracao_roteadores_contrato_li = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_configuracao_roteadores_contrato_li = $dados_configuracao_roteadores_contrato_li[0]['id_configuracao_roteadores_contrato'];
        echo "<input type='hidden' id='id_dados_configuracao_roteadores_contrato_li' name='pular' value='$id_dados_configuracao_roteadores_contrato_li' />";

        $dados_equipamento_li = DBRead('', 'tb_catalogo_equipamento_qi_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_equipamento_li = $id_dados_equipamento_li[0]['id_catalogo_equipamento_qi_contrato'];
        echo "<input type='hidden' id='id_dados_equipamento_li' name='pular' value='$id_dados_equipamento_li' />";

        $dados_reinicio_equipamento_contrato_li = DBRead('', 'tb_reinicio_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_reinicio_equipamento_contrato_li = $dados_reinicio_equipamento_contrato_li[0]['id_reinicio_equipamento_contrato'];
        echo "<input type='hidden' id='id_dados_reinicio_equipamento_contrato_li' name='pular' value='$id_dados_reinicio_equipamento_contrato_li' />";

        $dados_equipamento_contrato_li = DBRead('', 'tb_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_equipamento_contrato_li = $dados_equipamento_contrato_li[0]['id_equipamento_contrato'];
        echo "<input type='hidden' id='id_dados_equipamento_contrato_li' name='pular' value='$id_dados_equipamento_contrato_li' />";

        $dados_sinal_equipamento_contrato_li = DBRead('', 'tb_sinal_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_sinal_equipamento_contrato_li = $dados_sinal_equipamento_contrato_li[0]['id_sinal_equipamento_contrato'];
        echo "<input type='hidden' id='id_dados_sinal_equipamento_contrato_li' name='pular' value='$id_dados_sinal_equipamento_contrato_li' />";

        $dados_velocidade_minima_encaminhar_contrato_li = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_velocidade_minima_encaminhar_contrato_li = $dados_velocidade_minima_encaminhar_contrato_li[0]['id_velocidade_minima_encaminhar_contrato'];
        echo "<input type='hidden' id='id_dados_velocidade_minima_encaminhar_contrato_li' name='pular' value='$id_dados_velocidade_minima_encaminhar_contrato_li' />";

        $dados_parametros_li = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_parametros_li = $dados_parametros_li[0]['id_parametros'];
        echo "<input type='hidden' id='id_dados_parametros_li' name='pular' value='$id_dados_parametros_li' />";

        $dados_ura_contrato_li = DBRead('', 'tb_ura_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_ura_contrato_li = $dados_ura_contrato_li[0]['id_ura_contrato'];
        echo "<input type='hidden' id='id_dados_ura_contrato_li' name='pular' value='$id_dados_ura_contrato_li' />";

        $dados_manual_contrato_li = DBRead('', 'tb_manual_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_manual_contrato_li = $dados_manual_contrato_li[0]['id_manual_contrato'];
        echo "<input type='hidden' id='id_dados_manual_contrato_li' name='pular' value='$id_dados_manual_contrato_li' />";

        $dados_plano_cliente_contrato = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_plano_cliente_contrato = $dados_plano_cliente_contrato[0]['id_plano_cliente_contrato'];
        echo "<input type='hidden' id='id_dados_plano_cliente_contrato' name='pular' value='$id_dados_plano_cliente_contrato' />";
    ?>
    <ol class="breadcrumb">
        <li class="colorir-breadcrumbs-success"><a id="li_sistema_gestao" style="cursor: pointer; color: #3c763d;">Sistema de gestão</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_sistema_chat" style="cursor: pointer; color: #3c763d;">Sistema de chat</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_informacao_geral" style="cursor: pointer; color: #3c763d;">Informações gerais e de registro</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_localizacao" style="cursor: pointer; color: #3c763d;">Localização</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_plantonista" style="cursor: pointer; color: #3c763d;">Plantonistas</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_horario" style="cursor: pointer; color: #3c763d;">Horários</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_prazo_retorno" style="cursor: pointer; color: #3c763d;">Prazos de retorno</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_conexao_cabo" style="cursor: pointer; color: #3c763d;">Conexões de cabos</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_equipamento" style="cursor: pointer; color: #3c763d;">Equipamentos</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_tempo_reinicio" style="cursor: pointer; color: #3c763d;">Tempo de reinicio de equipamentos</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_acesso_equipamento" style="cursor: pointer; color: #3c763d;">Acesso a equipamentos</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_sinal_equipamento" style="cursor: pointer; color: #3c763d;">Sinais dos equipamentos</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_velocidade_encaminhamento" style="cursor: pointer; color: #3c763d;">Velocidade mínima para encaminhamento</a></li>
        <li class="active"><a id="li_parametro" style="cursor: pointer;"><strong>Parâmetros</strong></a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_ura" style="cursor: pointer;">URA</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_manual" style="cursor: pointer;">Manual</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_plano" style="cursor: pointer;">Planos</a></li>
    </ol>
    <?php
    endif;
    ?>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> parâmetros - Call Center:</h3>
                    <?php if(isset($_GET['alterar'])){
                        if($ativacao == 1){
                            $exclui_ativacao = 1;
                        }else{
                            $exclui_ativacao = 0;
                        }
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=/api/ajax?class=Parametro.php?excluir= $id&exclui-ativacao=$exclui_ativacao&id-contrato=$id_contrato_plano_pessoa&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=Parametro.php" id="parametro_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <?php
                                    if($ativacao == 1){
                                        echo "<div class='page-header header-plano-ativacao'>";
                                            echo "<h4>$contrato</h4>";
                                        echo "</div>";
                                    }else{
                                    ?>
                                   
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    if($operacao == 'alterar'){
                                        echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato' />";
                                    }else{
                                        echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato_plano_pessoa' />";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Parâmetros:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <!--Novo formato-->

                                        <div class="row" style="<?= $display_row?>">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Solicitar CPF/CNPJ?</label>
                                                    <?php
                                                        if($tituloPainel == 'Alterar'){
                                                            $checked = '';
                                                            $dados_solicitacao_cpf = DBRead('', 'tb_parametros', "WHERE solicitacao_cpf = 1 AND id_contrato_plano_pessoa = $id_contrato");

                                                            if($dados_solicitacao_cpf){
                                                                $checked = 'checked';
                                                            }else{
                                                                $checked = '';
                                                            }
                                                        }
                                                    ?>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="solicitacao_cpf" id="solicitacao_cpf_habilitado" class="solicitacao_cpf" <?= $checked ?> value="1" />
                                                            Sim
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="solicitacao_cpf" id="solicitacao_cpf_desabilitado" class="solicitacao_cpf" value="0" />
                                                            Não
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Solicitar dados?</label>
                                                    <?php
                                                        if($tituloPainel == 'Alterar'){
                                                            $checkedSolicitacao = '';
                                                            $dados_solicitacao_dados = DBRead('', 'tb_parametros', "WHERE solicitacao_dados = 1 AND id_contrato_plano_pessoa = $id_contrato");

                                                            if($dados_solicitacao_dados){
                                                                $checkedSolicitacao = 'checked';
                                                            }else{
                                                                $checkedSolicitacao = '';
                                                            }
                                                        }
                                                    ?>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="solicitacao_dados" class="solicitacao_dados" <?= $checkedSolicitacao ?> id="habilitar" value="1" />
                                                            Sim
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="solicitacao_dados" class="solicitacao_dados" id="desabilitar" value="0" />
                                                            Não
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Descrição da solicitação de dados:</label>
                                                    <input disabled id="descricao" type="text" class="form-control solicitacao_dados_descricao input-sm" value="<?=$solicitacao_dados_descricao;?>" name="solicitacao_dados_descricao" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Exibir protocolo</label>
                                                <?php
                                                    if($tituloPainel == 'Alterar'){
                                                        $checkedProtocolo = '';
                                                        $dados_exibir_protocolo = DBRead('', 'tb_parametros', "WHERE exibir_protocolo = 1 AND id_contrato_plano_pessoa = $id_contrato");

                                                        if($dados_exibir_protocolo){
                                                            $checkedProtocolo = 'checked';
                                                        }else{
                                                            $checkedProtocolo = '';
                                                        }
                                                    }
                                                ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="exibir_protocolo" class="exibir_protocolo" <?= $checkedProtocolo ?> id="exibir_protocolo_habilitado" value="1" />
                                                        Sim
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="exibir_protocolo" class="exibir_protocolo" id="exibir_protocolo_desabilitado" value="0" />
                                                        Não
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Enviar por e-mail?</label>
                                                <?php
                                                    if($tituloPainel == 'Alterar'){
                                                        $checkedEmail = '';
                                                        $dados_envio_email = DBRead('', 'tb_parametros', "WHERE enviar_email = 1 AND id_contrato_plano_pessoa = $id_contrato");
                                                        if($dados_envio_email){
                                                            $checkedEmail = 'checked';
                                                        }else{
                                                            $checkedEmail = '';
                                                        }
                                                    }
                                                ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="enviar_email" class="enviar_email" <?= $checkedEmail ?> id="enviar_email_habilitado" value="1" />
                                                        Sim
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="enviar_email" class="enviar_email" id="enviar_email_desabilitado" value="0" />
                                                        Não
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>E-mails para envio do serviço (separar por vírgula):</label>
                                                    <input id="email_envio" type="text" class="form-control email_envio input-sm" value="<?=$email_envio;?>" name="email_envio" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="row" style="<?= $display_row?>">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>ID Asterisk (apenas numeros):</label>
                                                    <input id="id_asterisk" type="text" class="form-control id_asterisk input-sm" value="<?=$id_asterisk;?>" name="id_asterisk"  autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Central telefônica:</label>
                                                    <select name="id_tipo_central_telefonica" id="id_tipo_central_telefonica" class="form-control input-sm" required>
                                                        <option value='1'>Não definida</option>
                                                        <?php
                                                            $tipo_central = DBRead('', 'tb_tipo_central_telefonica', "WHERE id_tipo_central_telefonica != 1 ORDER BY descricao ASC");
                                                            foreach($tipo_central as $conteudo){
                                                                $idCentral = $conteudo['id_tipo_central_telefonica'];
                                                                $selected = $id_central == $idCentral ? "selected" : "";
                                                                echo "<option value='".$conteudo['id_tipo_central_telefonica']."' ".$selected.">".$conteudo['descricao']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class='col-md-3'>

                                                <?php
                                                    if($tituloPainel == 'Alterar'){
                                                        $dados_controle_fila = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato");
                                                        if($dados_controle_fila[0]['controle_fila'] == '1'){
                                                            $checkedControleFilaSim = 'checked';
                                                            $checkedControleFilaNao = '';

                                                            $disabled_channel_name = '';
                                                            $value_channel_name = $channel_name;
                                                        }else{
                                                            $checkedControleFilaSim = '';
                                                            $checkedControleFilaNao = 'checked';

                                                            $disabled_channel_name = 'disabled';
                                                            $value_channel_name = '';
                                                        }
                                                    }else{
                                                        $checkedControleFilaSim = 'checked';
                                                        $checkedControleFilaNao = '';

                                                        $disabled_channel_name = 'disabled';
                                                        $value_channel_name = '';
                                                    }

                                                    // var_dump($dados_controle_fila);
                                                ?>
                                                                                         
                                                <label style='margin-bottom: 15px;'>Participa do controle de filas:</label><br>
                                                <div class="radio-inline"> 
                                                    <input type="radio" value='1' <?= $checkedControleFilaSim ?> name='controle_fila' id='controle_fila'> Sim
                                                </div>
                                                <div class="radio-inline">
                                                    <input type="radio" value='0' <?= $checkedControleFilaNao ?> name='controle_fila' id='controle_fila'> Não
                                                </div>
                                            </div>
                                            
                                            <div class='col-md-3'>
                                                <?php
                                                    if($tituloPainel == 'Alterar'){
                                                        $dados_atendimento_via_texto = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato");
                                                        if($dados_atendimento_via_texto[0]['atendimento_via_texto'] == '1'){
                                                            $checkedAtendimentoViaTextoSim = 'checked';
                                                            $checkedAtendimentoViaTextoNao = '';

                                                            $disabled_channel_name = '';
                                                            $value_channel_name = $channel_name;
                                                        }else{
                                                            $checkedAtendimentoViaTextoSim = '';
                                                            $checkedAtendimentoViaTextoNao = 'checked';

                                                            $disabled_channel_name = 'disabled';
                                                            $value_channel_name = '';
                                                        }
                                                    }else{
                                                        $checkedAtendimentoViaTextoSim = '';
                                                        $checkedAtendimentoViaTextoNao = 'checked';

                                                        $disabled_channel_name = 'disabled';
                                                        $value_channel_name = '';
                                                    }                                                ?>
                                                
                                                <!-- Campo que indica se realizamos atendimentos via texto -->
                                                <label style='margin-bottom: 15px;'>Atendimento via texto:</label><br>
                                                <div class="radio-inline"> 
                                                    <input type="radio" value='1' <?= $checkedAtendimentoViaTextoSim ?> name='atendimento_via_texto'> Sim
                                                </div>
                                                <div class="radio-inline">
                                                    <input type="radio" value='0' <?= $checkedAtendimentoViaTextoNao ?> name='atendimento_via_texto'> Não
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Número Botmaker:</label>
                                                    <input id="channel_name" type="text" class="form-control input-sm" value="<?=$value_channel_name;?>" name="channel_name"  autocomplete="off" <?=$disabled_channel_name;?>/>
                                                </div>
                                            </div> -->

                                        </div>

                                        <div class='row' style="<?= $display_row?>">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Número de retorno:</label>
                                                    <input id="ramal" name="ramal_retorno" type="text" class="form-control input-sm" value="<?= $ramal_retorno; ?>" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Prefixo de retorno (Asterisk):</label>
                                                    <input <?=$focus?> name="prefixo_telefone" id="prefixo_telefone" type="text" class="form-control input-sm number_int" value="<?= $prefixo_telefone; ?>" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class='col-md-3'>
                                                <label style='margin-bottom: 15px;'>Retorno válido para:</label><br>
                                                <div class="checkbox-inline"> 
                                                    <input type="checkbox" class='retorno_valido_para' value='1' name='retorno_valido_para[]' <?=$retorno_valido_para == 1 || $retorno_valido_para == 3 ? 'checked' : ''?>> Telefone fixo
                                                </div>
                                                <div class="checkbox-inline">
                                                    <input type="checkbox" class='retorno_valido_para' value='2' name='retorno_valido_para[]' <?=$retorno_valido_para == 2 || $retorno_valido_para == 3 ? 'checked' : ''?>> Celular
                                                </div>
                                            </div>
                                            <div class='col-md-4'>
                                                <?php
                                                    if($tituloPainel == 'Alterar'){
                                                        $dados_envio_sistema_gestao = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato");
                                                        if($dados_envio_sistema_gestao[0]['registra_monitormento_sistema_gestao'] == '1'){
                                                            $checkedSistemaGestaoSim = 'checked';
                                                            $checkedSistemaGestaoNao = '';
                                                        }else{
                                                            $checkedSistemaGestaoSim = '';
                                                            $checkedSistemaGestaoNao = 'checked';
                                                        }
                                                    }else{
                                                        $checkedSistemaGestaoSim = '';
                                                        $checkedSistemaGestaoNao = 'checked';
                                                    }
                                                ?>
                                                <!-- Campo que indica se o atendimento irá ser salvo em um sistema de gestão, não tem relação com as integrações -->
                                                <label style='margin-bottom: 15px;'>Link do sistema de gestão no monitoramento:</label><br>
                                                <div class="radio-inline"> 
                                                    <input type="radio" value='1' <?= $checkedSistemaGestaoSim ?> name='registra_monitormento_sistema_gestao'> Sim
                                                </div>
                                                <div class="radio-inline">
                                                    <input type="radio" value='0' <?= $checkedSistemaGestaoNao ?> name='registra_monitormento_sistema_gestao'> Não
                                                </div>
                                            </div>
                                        </div>

                                        <!--Novo formato-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <?php

                                    $pagina = 'parametro-form';

                                    if($ativacao == 1){

                                        $dados_pular = DBRead('', 'tb_ura_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                        $id_dados_pular = $dados_pular[0]['id_ura_contrato'];

                                        /***************************/

                                        $dados_voltar = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                        $id_dados_voltar = $dados_voltar[0]['id_velocidade_minima_encaminhar_contrato'];

                                        echo "<input type='hidden' value='1' name='ativacao' />";
                                        
                                        echo "<input type='hidden' value='" . $pagina . "' name='pagina' />";

                                        echo "<input type='hidden' id='id_dados_voltar' name='voltar' value='$id_dados_voltar' />";
                                        echo "<button class='btn btn-primary btn-comando-ativacao' id='voltar' type='button'><i class='fa fa-arrow-left' aria-hidden='true'></i> Voltar</button>";

                                        echo "<button class='btn btn-primary btn-comando-ativacao' name='salvar' value='1' id='ok' type='submit'><i class='fa fa-arrow-right' aria-hidden='true'></i> Salvar e continuar</button>";

                                        echo "<input type='hidden' id='id_dados_pular' name='pular' value='$id_dados_pular' />";
                                        echo "<button class='btn btn-primary btn-comando-ativacao' id='pular' type='button'><i class='fa fa-share' aria-hidden='true'></i> Pular</button>";

                                    }else{
                                        echo "<button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>";
                                    }
                                    ?>
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
    $("input[name='atendimento_via_texto']").on("change", function(){
        if($(this).val() == 0){
            $('#channel_name').prop('disabled', true);
            $('#channel_name').val('');
        }else{
            $('#channel_name').prop('disabled', false);
            $('#channel_name').val('<?=$channel_name;?>');
        }
    });

    $('#habilitar').on('click', function(){
        $('#descricao').attr('required', true);
    });
    $('#desabilitar').on('click', function(){
        $('#descricao').attr('required', false);
    });
    if($('#habilitar').attr("checked")){
        $('#descricao').attr('required', true);
    };
    if($('#desabilitar').attr("checked")){
        $('#descricao').prop('required', false);
    };
    if($('#enviar_email_habilitado').attr("checked")){
        $('#email_envio').prop('required', true);
    };

    $('#enviar_email_habilitado').on('click', function(){
        $("#email_envio").prop('required', true);
    });

    $('#enviar_email_desabilitado').on('click', function(){
        $("#email_envio").prop('required', false);
    });

    $('#enviar_email_habilitado').on('click', function(){
        $("#email_envio").prop("required", true);
    });

    $("#enviar_email_desabilitado").on("click", function(){
        $("#email_envio").prop("required", false);
    });

    var idVoltar = $("#voltar").val();
    
    var idContrato = $("#id_contrato_plano_pessoa").val();
    var idDadosPular = $("#id_dados_pular").val();
    var idDadosVoltar = $("#id_dados_voltar").val();

    $('#pular').on('click', function(){
        if(idDadosPular){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=ura-form&alterar="+idDadosPular+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=ura-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#voltar').on('click', function(){

        if(idDadosVoltar){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-form&alterar="+idDadosVoltar+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-form&ativacao=1&id_contrato="+idContrato;
        }

    });

    function verificaCampo(campoHabilitado, campoDesabilitado){
        if(!$(campoHabilitado).is(":checked")){
            $(campoDesabilitado).prop("checked", true);
        }else{
            $(campoHabilitado).prop("checked", true);
        }
    }

    function habilitaCampo(inputText, inputRadio1, inputRadio2){

        if($(inputRadio2).is(":checked")){
            $(inputText).prop("disabled", true);
        }else{
            $(inputRadio1).prop("checked", true);
        }

        $(inputRadio1).on("click", function(){
            $(inputText).prop("disabled", false);
        });
        $(inputRadio2).on("click", function(){
            $(inputText).prop("disabled", true).val("");
        });
    }

    verificaCampo("#solicitacao_cpf_habilitado", "#solicitacao_cpf_desabilitado");
    verificaCampo("#enviar_email_habilitado", "#enviar_email_desabilitado");
    verificaCampo("#exibir_protocolo_habilitado", "#exibir_protocolo_desabilitado");

    $("#habilitar").on("click", function(){
        $("#descricao").prop("disabled", false);
    });

    $("#desabilitar").on("click", function(){
        $("#descricao").prop("disabled", true).val("");
    });

    if($("#habilitar").is(":checked")){
        $("#descricao").prop("disabled", false);
    }else{
        $("#desabilitar").prop("checked", true);
    }

    $("#ramal").on("keypress", function(){
        if($("#ramal").val() != ""){
            $("#ramal_obs").attr("required", true);
            $("#obs-ramal").text('*Observação - Ramal de retorno:');
        }else if($("#ramal").val() == ""){
            $("#ramal_obs").attr("required", false);
        }
    });

    if($("#ramal").val() != ""){
        $("#obs-ramal").text('*Observação - Ramal de retorno:');
        $("#ramal_obs").attr("required", true);
    }

    $("#ramal").on('focusout', function(){
        if($(this).val() == ""){
            $("#obs-ramal").text('Observação - Ramal de retorno:');
            $("#ramal_obs").attr("required", false);
        }
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
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
            if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
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
                success: function(data){
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if(busca == ""){
            $('#id_contrato_plano_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    $(document).on('submit', '#parametro_form', function(){

        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        var retorno_valido_para = $(".retorno_valido_para:checked").length;

        if (id_contrato_plano_pessoa == 0 || !id_contrato_plano_pessoa){
            alert("Deve-se selecionar uma pessoa válida!");
            return false;

        } else if (retorno_valido_para == '0'){
            alert("Deve-se marcar se o retorno é válido!");
            return false;
        }
        
        modalAguarde();
    });

    var id_sistema_gestao_contrato_li = $("#id_sistema_gestao_contrato_li").val();
    var id_sistema_chat_contrato_li = $("#id_sistema_chat_contrato_li").val();
    var id_dados_informacao_geral_contrato_li = $("#id_dados_informacao_geral_contrato_li").val();
    var id_dados_localizacao_contrato_li = $("#id_dados_localizacao_contrato_li").val();
    var id_dados_plantonista_contrato_li = $("#id_dados_plantonista_contrato_li").val();
    var id_dados_horario_contrato_li = $("#id_dados_horario_contrato_li").val();
    var id_dados_prazo_retorno_contrato_li = $("#id_dados_prazo_retorno_contrato_li").val();
    var id_dados_configuracao_roteadores_contrato_li = $("#id_dados_configuracao_roteadores_contrato_li").val();
    var id_dados_equipamento_li = $("#id_dados_equipamento_li").val();
    var id_dados_reinicio_equipamento_contrato_li = $("#id_dados_reinicio_equipamento_contrato_li").val();
    var id_dados_equipamento_contrato_li = $("#id_dados_equipamento_contrato_li").val();
    var id_dados_sinal_equipamento_contrato_li = $("#id_dados_sinal_equipamento_contrato_li").val();
    var id_dados_velocidade_minima_encaminhar_contrato_li = $("#id_dados_velocidade_minima_encaminhar_contrato_li").val();
    var id_dados_parametros_li = $("#id_dados_parametros_li").val();
    var id_dados_ura_contrato_li = $("#id_dados_ura_contrato_li").val();
    var id_dados_manual_contrato_li = $("#id_dados_manual_contrato_li").val();
    var id_dados_plano_cliente_contrato = $("#id_dados_plano_cliente_contrato").val();


    $('#li_sistema_gestao').on('click', function(){
        if(id_sistema_gestao_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-gestao-form&alterar="+id_sistema_gestao_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-gestao-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_sistema_chat').on('click', function(){
        if(id_sistema_chat_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-chat-form&alterar="+id_sistema_chat_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-chat-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_informacao_geral').on('click', function(){
        if(id_dados_informacao_geral_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-form&alterar="+id_dados_informacao_geral_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_localizacao').on('click', function(){
        if(id_dados_localizacao_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&alterar="+id_dados_localizacao_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_plantonista').on('click', function(){
        if(id_dados_plantonista_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantonista-form&alterar="+id_dados_plantonista_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantonista-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_horario').on('click', function(){
        if(id_dados_horario_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=horario-form&alterar="+id_dados_horario_contrato_li+"&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=horario-form&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }
    });

    $('#li_prazo_retorno').on('click', function(){
        if(id_dados_prazo_retorno_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=prazo-retorno-form&alterar="+id_dados_prazo_retorno_contrato_li+"&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=prazo-retorno-form&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }
    });

    $('#li_conexao_cabo').on('click', function(){
        if(id_dados_configuracao_roteadores_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-form&alterar="+id_dados_configuracao_roteadores_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_equipamento').on('click', function(){
        if(id_dados_equipamento_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=equipamento-form&alterar="+id_dados_equipamento_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_tempo_reinicio').on('click', function(){
        if(id_dados_reinicio_equipamento_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-form&alterar="+id_dados_reinicio_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_acesso_equipamento').on('click', function(){
        if(id_dados_equipamento_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=acesso-equipamento-form&alterar="+id_dados_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=acesso-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_sinal_equipamento').on('click', function(){
        if(id_dados_sinal_equipamento_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sinal-equipamento-form&alterar="+id_dados_sinal_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sinal-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_velocidade_encaminhamento').on('click', function(){
        if(id_dados_velocidade_minima_encaminhar_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-form&alterar="+id_dados_velocidade_minima_encaminhar_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_parametro').on('click', function(){
        if(id_dados_parametros_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=parametro-form&alterar="+id_dados_parametros_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=parametro-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_ura').on('click', function(){
        if(id_dados_ura_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=ura-form&alterar="+id_dados_ura_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=ura-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_manual').on('click', function(){
        if(id_dados_manual_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=manual-form&alterar="+id_dados_manual_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=manual-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_plano').on('click', function(){
        if(id_dados_plano_cliente_contrato){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plano-cliente-form&alterar="+id_dados_plano_cliente_contrato+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plano-cliente-form&ativacao=1&id_contrato="+idContrato;
        }
    });
</script>