<?php
require_once(__DIR__."/../class/System.php");

$ativacao = (int)$_GET['ativacao'];
if($_GET['id_contrato']){
    $id_contrato_plano_pessoa = (int)$_GET['id_contrato'];
}else{
    $id_contrato_plano_pessoa = (int)$_POST['id_contrato_plano_pessoa'];
}

$verifica_contrato = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
if(!$verifica_contrato && $ativacao == 1){
    echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
    exit;
}

if(isset($_GET['alterar'])){
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_informacao_geral_contrato = $id");
    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $velocidade_reduzida = $dados[0]['velocidade_reduzida'];
        $acesso_controladoras = $dados[0]['acesso_controladoras'];
        $roteadores = $dados[0]['roteadores'];
        $computadores = $dados[0]['computadores'];
        $classificacao_atendimento_sistema_gestao = $dados[0]['classificacao_atendimento_sistema_gestao'];
        $selecao_finalizacao_sistema_gestao = $dados[0]['selecao_finalizacao_sistema_gestao'];
        $bloqueados = $dados[0]['bloqueados'];
        $contratacao_servico = $dados[0]['contratacao_servico'];
        $troca_plano = $dados[0]['troca_plano'];
        $troca_endereco = $dados[0]['troca_endereco'];
        $cancelamentos = $dados[0]['cancelamentos'];
        $situacoes_adversas = $dados[0]['situacoes_adversas'];
        $informacoes_adicionais = $dados[0]['informacoes_adicionais'];
        $troca_comodo = $dados[0]['troca_comodo'];
        $segunda_via = $dados[0]['segunda_via'];
        $descontos = $dados[0]['descontos'];

        $servico_telefonia = $dados[0]['servico_telefonia'];
        $tv_assinatura = $dados[0]['tv_assinatura'];
        $servico_streaming = $dados[0]["servico_streaming"];

        $suporte_dispositivos_moveis = $dados[0]['suporte_dispositivos_moveis'];
        $posicao_os = $dados[0]['posicao_os'];
        $posicao_instalacao = $dados[0]['posicao_instalacao'];
        $tipo_os = $dados[0]['tipo_os'];
        $tipo_equipamento = $dados[0]['tipo_equipamento'];
        $monitoramento = $dados[0]['monitoramento'];
        $horarios_monitoramento = $dados[0]['horarios_monitoramento'];
        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];

        $confirmacao_cadastro_cliente = $dados[0]['confirmacao_cadastro_cliente'];
        $suporte_acesso_lento = $dados[0]['suporte_acesso_lento'];
        $nao_cliente = $dados[0]['nao_cliente'];
        
        $inativo_cancelado = $dados[0]['inativo_cancelado'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        
        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    }else{
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
        exit;
    }
    

}else{
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
    
    $roteadores = '';
    $computadores = '';
    $classificacao_atendimento_sistema_gestao = '';
    $selecao_finalizacao_sistema_gestao = '';
    $bloqueados = '';
    $contratacao_servico = '';
    $troca_plano = '';
    $troca_endereco = '';
    $cancelamentos = '';
    $situacoes_adversas = '';
    $informacoes_adicionais = '';
    $troca_comodo = '';
    $segunda_via = '';
    $descontos = '';
    $servico_telefonia = '';
    $tv_assinatura = '';
    $servico_streaming = '';
    $suporte_dispositivos_moveis = '';
    $posicao_os = '';
    $posicao_instalacao = '';
    $tipo_os = '';
    $tipo_equipamento = '';
    $monitoramento = '';
    $horarios_monitoria = '';
    
    $inativo_cancelado = '';

    if($ativacao == 1){
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

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
        <li class="active"><a id="li_informacao_geral" style="cursor: pointer;"><strong>Informações gerais e de registro</strong></a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_localizacao" style="cursor: pointer;">Localização</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_plantonista" style="cursor: pointer;">Plantonistas</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_horario" style="cursor: pointer;">Horários</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_prazo_retorno" style="cursor: pointer;">Prazos de retorno</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_conexao_cabo" style="cursor: pointer;">Conexões de cabos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_equipamento" style="cursor: pointer;">Equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_tempo_reinicio" style="cursor: pointer;">Tempo de reinicio de equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_acesso_equipamento" style="cursor: pointer;">Acesso a equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_sinal_equipamento" style="cursor: pointer;">Sinais dos equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_velocidade_encaminhamento" style="cursor: pointer;">Velocidade mínima para encaminhamento</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_parametro" style="cursor: pointer;">Parâmetros</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_ura" style="cursor: pointer;">URA</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_manual" style="cursor: pointer;">Manual</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_plano" style="cursor: pointer;">Planos</a></li>
    </ol>
    <?php
    endif;
    ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> informações gerais e de registro:</h3>
                    <?php if(isset($_GET['alterar'])){
                        if($ativacao == 1){
                            $exclui_ativacao = 1;
                        }else{
                            $exclui_ativacao = 0;
                        }
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=InformacoesGerais.php?excluir=$id&exclui-ativacao=$exclui_ativacao&id-contrato=$id_contrato_plano_pessoa&token=". $request->token ."\" onclick=\"if(!confirm('Tem certeza que deseja excluir o registro?')){ return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
                </div>
                <form method="post" action="/api/ajax?class=InformacoesGerais.php" id="informacoes_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?php
                                    if($ativacao == 1){
                                        echo "<div class='page-header header-plano-ativacao'>";
                                            echo "<h4>$contrato</h4>";
                                        echo "</div>";
                                    }else{
                                    ?>
                                    <label>*Contrato (cliente):</label>
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
                                <div class="form-group">
                                    <label for="confirmacao_cadastro_cliente">Confirmação cadastro cliente:</label>
                                    <textarea name="confirmacao_cadastro_cliente" class="form-control input-sm" id="confirmacao_cadastro_cliente"><?= $confirmacao_cadastro_cliente ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="nao_cliente">Não cliente:</label>
                                    <textarea name="nao_cliente" class="form-control input-sm" id="nao_cliente"><?= $nao_cliente ?></textarea>
                                </div>

                                 <div class="form-group">
                                    <label for="classificacao_atendimento_sistema_gestao">Classificação de atendimento no sistema de gestão:</label>
                                    <textarea name="classificacao_atendimento_sistema_gestao" class="form-control input-sm" id="classificacao_atendimento_sistema_gestao"><?= $classificacao_atendimento_sistema_gestao ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="selecao_finalizacao_sistema_gestao">Seleção de finalização no sistema de gestão:</label>
                                    <textarea name="selecao_finalizacao_sistema_gestao" class="form-control input-sm" id="selecao_finalizacao_sistema_gestao"><?= $selecao_finalizacao_sistema_gestao ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_os">Tipo de O.S.:</label>
                                    <textarea name="tipo_os" class="form-control input-sm" id="tipo_os"><?= $tipo_os ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="suporte_acesso_lento">Suporte a acesso lento / medidor de velocidade:</label>
                                    <textarea name="suporte_acesso_lento" class="form-control input-sm" id="suporte_acesso_lento"><?= $suporte_acesso_lento ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="bloqueados">Bloqueados:</label>
                                    <textarea name="bloqueados" class="form-control input-sm" id="bloqueados"><?= $bloqueados ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="segunda_via">Segunda via de boleto:</label>
                                    <textarea name="segunda_via" class="form-control input-sm" id="segunda_via"><?= $segunda_via ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="descontos">Descontos:</label>
                                    <textarea name="descontos" class="form-control input-sm" id="descontos"><?= $descontos ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="troca_endereco">Troca de endereço:</label>
                                    <textarea name="troca_endereco" class="form-control input-sm" id="troca_endereco"><?= $troca_endereco ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="troca_comodo">Troca de cômodo:</label>
                                    <textarea name="troca_comodo" class="form-control input-sm" id="troca_comodo"><?= $troca_comodo ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="cancelamentos">Cancelamentos:</label>
                                    <textarea name="cancelamentos" class="form-control input-sm" id="cancelamentos"><?= $cancelamentos ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="contratacao_servico">Contratação de serviço:</label>
                                    <textarea name="contratacao_servico" class="form-control input-sm" id="contratacao_servico"><?= $contratacao_servico ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="troca_plano">Troca de plano:</label>
                                    <textarea name="troca_plano" class="form-control input-sm" id="troca_plano"><?= $troca_plano ?></textarea>
                                </div>



                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Velocidade reduzida:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim0 = '';
                                                $checkedNao0 = '';
                                                $checkedNaoInformado0 = '';
                                                $dados_velocidade_reduzida = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");
                                                if($dados_velocidade_reduzida[0]['velocidade_reduzida_bool'] == '2'){
                                                    $checkedNaoInformado0 = 'checked';
                                                }else if($dados_velocidade_reduzida[0]['velocidade_reduzida_bool'] == '1'){
                                                    $checkedSim0 = 'checked';
                                                }else{
                                                    $checkedNao0 = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">

                                            <label>
                                                <input type="radio" <?=$checkedSim0?> name="velocidade_reduzida" value="1" id="velocidade_reduzida_sim">
                                                Sim
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNao0?> name="velocidade_reduzida" value="0" id="velocidade_reduzida_nao">
                                                Não
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNaoInformado0?> name="velocidade_reduzida" value="2" id="velocidade_reduzida_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="velocidade_reduzida">Descrição:</label>
                                            <textarea name="descricao_velocidade_reduzida" class="form-control input-sm" id="velocidade_reduzida"><?= $velocidade_reduzida ?></textarea>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Sistema informa se o cliente está logado:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim1 = '';
                                                $checkedNao1 = '';
                                                $checkedNaoInformado1 = '';
                                                $dados_acesso_controladoras = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");
                                                if($dados_acesso_controladoras[0]['acesso_controladoras_bool'] == '2'){
                                                    $checkedNaoInformado1 = 'checked';
                                                }else if($dados_acesso_controladoras[0]['acesso_controladoras_bool'] == '1'){
                                                    $checkedSim1 = 'checked';
                                                }else{
                                                    $checkedNao1 = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">

                                            <label>
                                                <input type="radio" <?=$checkedSim1?> name="acesso_controladoras" value="1" id="acesso_controladoras_sim">
                                                Sim
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNao1?> name="acesso_controladoras" value="0" id="acesso_controladoras_nao">
                                                Não
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNaoInformado1?> name="acesso_controladoras" value="2" id="acesso_controladoras_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="acesso_controladoras">Descrição:</label>
                                            <textarea name="descricao_acesso_controladoras" class="form-control input-sm" id="acesso_controladoras"><?= $acesso_controladoras ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Suporte a roteadores:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim2 = '';
                                                $checkedNao2 = '';
                                                $checkedNaoInformado2 = '';
                                                $dados_suporte_roteadores = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_suporte_roteadores[0]['roteadores_bool'] == '2'){
                                                    $checkedNaoInformado2 = 'checked';
                                                }else if($dados_suporte_roteadores[0]['roteadores_bool'] == '1'){
                                                    $checkedSim2 = 'checked';
                                                }else{
                                                    $checkedNao2 = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" <?=$checkedSim2?> name="manutencao_roteadores" value="1" id="manutencao_roteadores_sim">
                                                Sim
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNao2?> name="manutencao_roteadores" value="0" id="manutencao_roteadores_nao">
                                                Não
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNaoInformado2?> name="manutencao_roteadores" value="2" id="manutencao_roteadores_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="roteadores">Descrição:</label>
                                            <textarea name="roteadores" class="form-control input-sm" id="roteadores"><?= $roteadores ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Suporte a computadores:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim3 = '';
                                                $checkedNao3 = '';
                                                $checkedNaoInformado3 = '';
                                                $dados_suporte_computadores = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_suporte_computadores[0]['computadores_bool'] == '2'){
                                                    $checkedNaoInformado3 = 'checked';
                                                }else if($dados_suporte_computadores[0]['computadores_bool'] == '1'){
                                                    $checkedSim3 = 'checked';
                                                }else{
                                                    $checkedNao3 = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" <?=$checkedSim3?> name="manutencao_computadores" value="1" id="manutencao_computadores_sim">
                                                Sim
                                          </label>
                                          <label>
                                            <input type="radio" <?=$checkedNao3?> name="manutencao_computadores" value="0" id="manutencao_computadores_nao">
                                                Não
                                          </label>
                                          <label>
                                                <input type="radio" <?=$checkedNaoInformado3?> name="manutencao_computadores" value="2" id="manutencao_computadores_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="computadores">Descrição:</label>
                                            <textarea name="computadores" class="form-control input-sm" id="computadores"><?= $computadores ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Suporte a dispositivos móveis, SmartTV e video game:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim = '';
                                                $checkedNao = '';
                                                $checkedNaoInformado = '';
                                                $dados_suporte_dispositivos = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_suporte_dispositivos[0]['suporte_dispositivos_moveis_bool'] == '2'){
                                                    $checkedNaoInformado = 'checked';
                                                }else if($dados_suporte_dispositivos[0]['suporte_dispositivos_moveis_bool'] == '1'){
                                                    $checkedSim = 'checked';
                                                }else{
                                                    $checkedNao = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" <?=$checkedSim?> name="suporte_dispositivos" id="manutencao_dispositivos_sim" value="1">
                                                Sim
                                          </label>
                                          <label>
                                            <input type="radio" <?=$checkedNao?> name="suporte_dispositivos" id="manutencao_dispositivos_nao" value="0">
                                                Não
                                          </label>
                                          <label>
                                                <input type="radio" <?=$checkedNaoInformado?> name="suporte_dispositivos" value="2" id="manutencao_dispositivos_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="suporte_dispositivos_moveis">Descrição:</label>
                                            <textarea name="suporte_dispositivos_moveis" class="form-control input-sm" id="suporte_dispositivos_moveis"><?= $suporte_dispositivos_moveis ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Serviços de telefonia:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim = '';
                                                $checkedNao = '';
                                                $checkedNaoInformado = '';
                                                $dados_servico_telefonia = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_servico_telefonia[0]['servico_telefonia_bool'] == '2'){
                                                    $checkedNaoInformado = 'checked';
                                                }else if($dados_servico_telefonia[0]['servico_telefonia_bool'] == '1'){
                                                    $checkedSim = 'checked';
                                                }else{
                                                    $checkedNao = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" <?=$checkedSim?> name="servico_telefonia_bool" id="servico_telefonia_sim" value="1">
                                                Sim
                                          </label>
                                          <label>
                                            <input type="radio" <?=$checkedNao?> name="servico_telefonia_bool" id="servico_telefonia_nao" value="0">
                                                Não
                                          </label>
                                          <label>
                                                <input type="radio" <?=$checkedNaoInformado?> name="servico_telefonia_bool" value="2" id="servico_telefonia_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="servico_telefonia">Descrição:</label>
                                            <textarea name="servico_telefonia" class="form-control input-sm" id="servico_telefonia"><?= $servico_telefonia ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>TV por assinatura:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim = '';
                                                $checkedNao = '';
                                                $checkedNaoInformado = '';
                                                $dados_tv_assinatura = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_tv_assinatura[0]['tv_assinatura_bool'] == '2'){
                                                    $checkedNaoInformado = 'checked';
                                                }else if($dados_tv_assinatura[0]['tv_assinatura_bool'] == '1'){
                                                    $checkedSim = 'checked';
                                                }else{
                                                    $checkedNao = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" <?=$checkedSim?> name="tv_assinatura_bool" id="tv_assinatura_sim" value="1">
                                                Sim
                                          </label>
                                          <label>
                                            <input type="radio" <?=$checkedNao?> name="tv_assinatura_bool" id="tv_assinatura_nao" value="0">
                                                Não
                                          </label>
                                          <label>
                                                <input type="radio" <?=$checkedNaoInformado?> name="tv_assinatura_bool" value="2" id="tv_assinatura_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="tv_assinatura">Descrição:</label>
                                            <textarea name="tv_assinatura" class="form-control input-sm" id="tv_assinatura"><?= $tv_assinatura ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Serviço de Streaming:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim = '';
                                                $checkedNao = '';
                                                $checkedNaoInformado = '';
                                                $dados_servico_streaming = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_servico_streaming[0]['servico_streaming_bool'] == '2'){
                                                    $checkedNaoInformado = 'checked';
                                                }else if($dados_tv_assinatura[0]['servico_streaming_bool'] == '1'){
                                                    $checkedSim = 'checked';
                                                }else{
                                                    $checkedNao = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" <?=$checkedSim?> name="servico_streaming_bool" id="servico_streaming_sim" value="1">
                                                Sim
                                          </label>
                                          <label>
                                            <input type="radio" <?=$checkedNao?> name="servico_streaming_bool" id="servico_streaming_nao" value="0">
                                                Não
                                          </label>
                                          <label>
                                                <input type="radio" <?=$checkedNaoInformado?> name="servico_streaming_bool" value="2" id="servico_streaming_nao_informado">
                                                Não informado
                                            </label>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="servico_streaming">Descrição:</label>
                                            <textarea name="servico_streaming" class="form-control input-sm" id="servico_streaming"><?= $servico_streaming ?></textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="informacoes_adicionais">Informações adicionais:</label>
                                    <textarea name="informacoes_adicionais" class="form-control input-sm" id="informacoes_adicionais"><?= $informacoes_adicionais ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="situacoes_adversas">Situações adversas:</label>
                                    <textarea name="situacoes_adversas" class="form-control input-sm" id="situacoes_adversas"><?= $situacoes_adversas ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_equipamento">Tipo de equipamento:</label>
                                    <textarea name="tipo_equipamento" class="form-control input-sm" id="tipo_equipamento"><?= $tipo_equipamento ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="posicao_os">Posição de O.S.:</label>
                                    <textarea name="posicao_os" class="form-control input-sm" id="posicao_os"><?= $posicao_os ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="posicao_instalacao">Posição de instalação:</label>
                                    <textarea name="posicao_instalacao" class="form-control input-sm" id="posicao_instalacao"><?= $posicao_instalacao ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class='col-md-4'>
                                    <label>Suporte a monitoramento:</label>
                                        <?php
                                            if($tituloPainel == 'Alterar'){
                                                $checkedSim = '';
                                                $checkedNao = '';
                                                $required = '';
                                                $dados_monitoramento = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

                                                if($dados_suporte_dispositivos[0]['monitoramento'] == '1'){
                                                    $checkedSim = 'checked';
                                                    $required = 'required';
                                                }else{
                                                    $checkedNao = 'checked';
                                                }
                                            }
                                        ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" <?=$checkedSim?> name="monitoramento" id="monitoramento_sim" value="1">
                                                    Sim
                                            </label>
                                            <label>
                                                <input type="radio" <?=$checkedNao?> name="monitoramento" id="monitoramento_nao" value="0">
                                                    Não
                                            </label>
                                        </div>
                                    </div>
                                    <div class='col-md-8' style='margin-bottom: 16px;'>
                                        <label for="horarios_monitoramento">Horários de monitoramento:</label>
                                        <textarea name="horarios_monitoramento" <?=$required?> class="form-control input-sm" id="horarios_monitoria"><?= $horarios_monitoramento ?></textarea>
                                    </div>
                                    
                                </div>

                                <div class="form-group">
                                    <label>Cadastro inativo/cancelado:</label>
                                    <textarea name="inativo_cancelado" class="form-control input-sm" id="inativo_cancelado"><?= $inativo_cancelado ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>" />
                                <?php

                                $pagina = 'informacoes-gerais-form';

                                if($ativacao == 1){

                                    $dados_pular = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                    $id_dados_pular = $dados_pular[0]['id_localizacao_contrato'];


                                    /*$dados_voltar = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                    $id_dados_voltar = $dados_voltar[0]['id_sistema_gestao_contrato'];*/

                                    $dados_voltar = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa ORDER BY id_sistema_chat_contrato desc");
                                    $id_dados_voltar = $dados_voltar[0]['id_sistema_chat_contrato'];


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
<script>

    var idVoltar = $("#voltar").val();
    
    var idContrato = $("#id_contrato_plano_pessoa").val();
    var idDadosPular = $("#id_dados_pular").val();
    var idDadosVoltar = $("#id_dados_voltar").val();

    $('#pular').on('click', function(){
        if(idDadosPular){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=localizacao-form&alterar="+idDadosPular+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=localizacao-form&ativacao=1&id_contrato="+idContrato;
        }

    });
    $('#voltar').on('click', function(){

        if(idDadosVoltar){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sistema-chat-form&alterar="+idDadosVoltar+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sistema-chat-form&ativacao=1&id_contrato="+idContrato;
        }

    });

    //velocidade reduzida
    $("#velocidade_reduzida").attr("readonly", false);

    $("#velocidade_reduzida_sim").on('click', function(){
        $("#velocidade_reduzida").attr("readonly", false);
    });
    $("#velocidade_reduzida_nao").on('click', function(){
        $("#velocidade_reduzida").attr("readonly", false);
    });
    $("#velocidade_reduzida_nao_informado").on('click', function(){
        $("#velocidade_reduzida").attr("readonly", true).val("");
    });

    if($("#velocidade_reduzida_nao_informado").attr('checked')){
        $("#velocidade_reduzida").attr("readonly", true);
    }

    //acesso controladoras
    $("#acesso_controladoras").attr("readonly", false);

    $("#acesso_controladoras_sim").on('click', function(){
        $("#acesso_controladoras").attr("readonly", false);
    });
    $("#acesso_controladoras_nao").on('click', function(){
        $("#acesso_controladoras").attr("readonly", false);
    });
    $("#acesso_controladoras_nao_informado").on('click', function(){
        $("#acesso_controladoras").attr("readonly", true).val("");
    });

    if($("#acesso_controladoras_nao_informado").attr('checked')){
        $("#acesso_controladoras").attr("readonly", true);
    }

    //manutenção roteadores
    $("#roteadores").attr("readonly", false);
    $("#manutencao_roteadores_sim").on('click', function(){
        $("#roteadores").attr("readonly", false);
    });
    $("#manutencao_roteadores_nao").on('click', function(){
        $("#roteadores").attr("readonly", false);
    });
    $("#manutencao_roteadores_nao_informado").on('click', function(){
        $("#roteadores").attr("readonly", true).val("");
    });

    if($("#manutencao_roteadores_nao_informado").attr('checked')){
        $("#roteadores").attr("readonly", true);
    }

    //manutenção computadores
    $("#computadores").attr("readonly", false);
    $("#manutencao_computadores_sim").on('click', function(){
        $("#computadores").attr("readonly", false);
    });
    $("#manutencao_computadores_nao").on('click', function(){
        $("#computadores").attr("readonly", false);
    });
    $("#manutencao_computadores_nao_informado").on('click', function(){
        $("#computadores").attr("readonly", true).val("");
    });

    if($("#manutencao_computadores_nao_informado").attr('checked')){
        $("#computadores").attr("readonly", true);
    }

    //monitoria
    $("#monitoramento_sim").on("click", function(){
        $("#horarios_monitoramento").attr('required', true);
    });
    $("#monitoramento_nao").on("click", function(){
        $("#horarios_monitoramento").attr('required', false);
    });

    //manutenção dispositivos móveis
    $("#suporte_dispositivos_moveis").attr("readonly", false);
    $("#manutencao_dispositivos_sim").on('click', function(){
        $("#suporte_dispositivos_moveis").attr("readonly", false);
    });
    $("#manutencao_dispositivos_nao").on('click', function(){
        $("#suporte_dispositivos_moveis").attr("readonly", false);
    });
    $("#manutencao_dispositivos_nao_informado").on('click', function(){
        $("#suporte_dispositivos_moveis").attr("readonly", true).val("");
    });

    if($("#manutencao_dispositivos_nao_informado").attr('checked')){
        $("#suporte_dispositivos_moveis").attr("readonly", true);
    }

    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "class/ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                        }
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui){
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
                url: "class/ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    }
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

    $(document).on('submit', '#informacoes_form', function(){

        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == 0){
            alert("Deve-se selecionar um contrato válido!");
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
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sistema-gestao-form&alterar="+id_sistema_gestao_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sistema-gestao-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_sistema_chat').on('click', function(){
        if(id_sistema_chat_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sistema-chat-form&alterar="+id_sistema_chat_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sistema-chat-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_informacao_geral').on('click', function(){
        if(id_dados_informacao_geral_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=informacoes-gerais-form&alterar="+id_dados_informacao_geral_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=informacoes-gerais-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_localizacao').on('click', function(){
        if(id_dados_localizacao_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=localizacao-form&alterar="+id_dados_localizacao_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=localizacao-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_plantonista').on('click', function(){
        if(id_dados_plantonista_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=plantonista-form&alterar="+id_dados_plantonista_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=plantonista-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_horario').on('click', function(){
        if(id_dados_horario_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=horario-form&alterar="+id_dados_horario_contrato_li+"&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=horario-form&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }
    });

    $('#li_prazo_retorno').on('click', function(){
        if(id_dados_prazo_retorno_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=prazo-retorno-form&alterar="+id_dados_prazo_retorno_contrato_li+"&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=prazo-retorno-form&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }
    });

    $('#li_conexao_cabo').on('click', function(){
        if(id_dados_configuracao_roteadores_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=configuracao-roteadores-form&alterar="+id_dados_configuracao_roteadores_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=configuracao-roteadores-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_equipamento').on('click', function(){
        if(id_dados_equipamento_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=equipamento-form&alterar="+id_dados_equipamento_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_tempo_reinicio').on('click', function(){
        if(id_dados_reinicio_equipamento_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=reinicio-equipamento-form&alterar="+id_dados_reinicio_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=reinicio-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_acesso_equipamento').on('click', function(){
        if(id_dados_equipamento_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=acesso-equipamento-form&alterar="+id_dados_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=acesso-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_sinal_equipamento').on('click', function(){
        if(id_dados_sinal_equipamento_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sinal-equipamento-form&alterar="+id_dados_sinal_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=sinal-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_velocidade_encaminhamento').on('click', function(){
        if(id_dados_velocidade_minima_encaminhar_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=velocidade-minima-encaminhar-form&alterar="+id_dados_velocidade_minima_encaminhar_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=velocidade-minima-encaminhar-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_parametro').on('click', function(){
        if(id_dados_parametros_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=parametro-form&alterar="+id_dados_parametros_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=parametro-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_ura').on('click', function(){
        if(id_dados_ura_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=ura-form&alterar="+id_dados_ura_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=ura-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_manual').on('click', function(){
        if(id_dados_manual_contrato_li){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=manual-form&alterar="+id_dados_manual_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=manual-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_plano').on('click', function(){
        if(id_dados_plano_cliente_contrato){
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=plano-cliente-form&alterar="+id_dados_plano_cliente_contrato+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token="<?=$request->token?>"&view=plano-cliente-form&ativacao=1&id_contrato="+idContrato;
        }
    });
</script>