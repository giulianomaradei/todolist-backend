<?php
require_once(__DIR__."/../class/System.php");

if ($_GET['id_contrato']) {
    $id_contrato_plano_pessoa = (int)$_GET['id_contrato'];
} else {
    $id_contrato_plano_pessoa = (int)$_POST['id_contrato_plano_pessoa'];
}

if ($ativacao == 1) {
    $focus = "autofocus";
}

if (isset($_GET['alterar'])) {

    $id = (int)$_GET['alterar'];
    $id_integracao = $_GET['integracao'];
    $dados = DBRead('', 'tb_integracao_contrato', "WHERE id_integracao_contrato = $id");

    if ($dados) {
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $host = $dados[0]['host'];
        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];
        $id_integracao_contrato = $dados[0]['id_integracao_contrato'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

        if ($dados_contrato[0]['nome_contrato']) {
            $nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    } else {
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
        exit;
    }
} else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $host = '';
    $sistema = '';
    $versao = '';

}
?>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> integração:</h3>
                    <?php if (isset($_GET['alterar'])) {
                        echo "<div class=\"panel-title text-right pull-right\"><a class=\"a_modalAguarde\" href=\"/api/ajax?class=Integracao.php?excluir=$id&exclui-ativacao=$exclui_ativacao&id-contrato=$id_contrato&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')){ return false; }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a>";

                        echo "</div>";
                    }
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=Integracao.php" id="integracao_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" value="<?= $contrato ?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <?php

                                    if ($operacao == 'alterar') {
                                        echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato' />";
                                    } else {
                                        echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato_plano_pessoa' />";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Host:</label>
                                    <input type='text' class='form-control input-sm' name='host' id='host' value='<?= $host ?>' required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Sistema de integração:</label>
                                    <select name='id_integracao' id='sistema' class="form-control input-sm">
                                        <?php
                                        $sistema_integracao = DBRead('', 'tb_integracao', "ORDER BY nome ASC");
                                        if ($sistema_integracao) {
                                            echo "<option></option>";
                                            foreach ($sistema_integracao as $conteudo) {
                                                $idIntegracao = $conteudo['id_integracao'];
                                                $nomeIntegracao = $conteudo['nome'];
                                                $selected = $dados[0]['id_integracao'] == $idIntegracao ? "selected" : "";
                                                echo "<option value='" . $idIntegracao . "' class='sistema' ".$selected.">" . $nomeIntegracao . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <?php
                                include_once "class/integracoes/ixc/Usuarios.php";
                                $usuarios = new Integracao\Ixc\Usuarios();
                                
                                if ($operacao == 'alterar') {
                                    $usuarios = $usuarios->get('usuarios.id', '', true, $id_contrato);
                                } else {
                                    $usuarios = $usuarios->get('usuarios.id', '', true, $id_contrato_plano_pessoa);
                                }

                                //var_dump($usuarios);

                            ?>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Usuário integrado:</label>
                                    <select class='form-control input-sm' name='usuario_integrado' id='usuario_integrado'>
                                        <option></option>
                                        <?php
                                        $usuario_selecionado = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato."'");                                        
                                        foreach($usuarios['registros'] as $key => $usuario){
                                            $usuario_selecionado_banco = $usuario_selecionado[0]['usuario_integrado'];
                                            $usuario_selecionado_api = $usuarios['registros'][$key]['id'];
                                            $selected = $usuario_selecionado_banco == $usuario_selecionado_api ? 'selected' : '';
                                            
                                            echo "<option ".$selected." value='".$usuarios['registros'][$key]['id']."'>".$usuarios['registros'][$key]['nome']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <?php

                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Parâmetros:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='row' id='parametros'>
                                            <?php

                                            //Bloco parâmetros de integração
                                            $integracao_parametros = DBRead('', 'tb_integracao_parametro', "WHERE id_integracao = '$id_integracao'");
                                            if ($integracao_parametros) :
                                                foreach ($integracao_parametros as $key => $conteudo) : //Inicio do foreach para os parametros da integração
                                                    $valor_campos = DBRead('', 'tb_integracao_contrato_parametro', "WHERE id_integracao_parametro = '" . $conteudo['id_integracao_parametro'] . "' AND id_integracao_contrato = '" . $id_integracao_contrato . "'");
                                                    ?>
                                                    <div class="col-md-6">
                                                        <label><?= $conteudo['nome'] ?></label>
                                                        <?php
                                                        if ($conteudo['tipo'] == 'text') : //if para parametros do tipo text
                                                            ?>
                                                            <input type='hidden' class='form-control <?= $conteudo['codigo'] ?>' name='id_integracao_parametro[]' value='<?= $valor_campos[0]['id_integracao_parametro'] ?>' />
                                                            <input type='text' class='form-control <?= $conteudo['codigo'] ?>' name='<?= $conteudo['codigo'] ?>' value='<?= $valor_campos[0]['valor'] ?>' />
                                                        <?php
                                                    elseif ($conteudo['tipo'] == 'radio') : //if para parametros do tipo radio
                                                        $opcoes = DBRead('', 'tb_integracao_valores_tipo_parametro', "WHERE id_integracao_parametro = '" . $conteudo['id_integracao_parametro'] . "'");

                                                        echo "<input type='hidden' class='form-control ". $conteudo['codigo'] ."' name='id_integracao_parametro[]' value='". $valor_campos[0]['id_integracao_parametro'] ."' />";
                                                        foreach ($opcoes as $opcao) : //Inicio foreach para as opções de parametros do tipo radio
                                                            ?>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="<?= $conteudo['codigo'] ?>" name="<?= $conteudo['codigo'] ?>" value="<?= $opcao['valor'] ?>" <?= $valor_campos[0]['valor'] == $opcao['valor'] ? 'checked' : '' ?>><?= $opcao['titulo'] ?>
                                                                    </label>
                                                                </div>
                                                            <?php
                                                        endforeach;  //fim do foreach para as opções de parametros do tipo radio
                                                    endif;
                                                    ?>
                                                    </div>
                                                <?php
                                                endforeach; //fim do foreach para os parametros da integração
                                            endif; //fim dos ifs de tipo
                                            //Bloco parâmetros de integração//////////////////////////////////////////////////////////////////
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <?php
                            //Verifica recursos
                            $recursos_integracao = DBRead('', 'tb_integracao_recursos', "WHERE id_integracao = '$id_integracao' AND id_contrato_plano_pessoa = '$id_contrato'");
                            $checked_reiniciar = '';
                            $checked_diagnosticar = '';
                            $checked_desbloquear = '';
                            $checked_boleto = '';
                            $checked_login = '';
                            $checked_monitoramento = '';
                            $checked_zerar = '';
                            $checked_sinal_rx = '';
                            $checked_senha_wifi = '';
                            $checked_desbloquer_vel_reduzida = '';
                            
                            foreach($recursos_integracao as $key => $conteudo){
                                if($recursos_integracao[$key]["nome"] == 'reiniciar_conexao'){
                                    $checked_reiniciar = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'diagnosticar_conexao'){
                                    $checked_diagnosticar = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'desbloquear_contrato'){
                                    $checked_desbloquear = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'enviar_boleto'){
                                    $checked_boleto = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'acesso_login'){
                                    $checked_login = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'integracao_monitoramento'){
                                    $checked_monitoramento = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'zerar_mac'){
                                    $checked_zerar = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'sinal_rx'){
                                    $checked_sinal_rx = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'senha_wifi'){
                                    $checked_senha_wifi = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }
                                if($recursos_integracao[$key]["nome"] == 'desbloquear_vel_reduzida'){
                                    $checked_desbloquer_vel_reduzida = $recursos_integracao[$key]["ativo"] == "1" ? 'checked' : 'unchecked';
                                }

                                //Verifica campos requeridos
                                $campos_requeridos = DBRead('', 'tb_integracao_campos_requeridos', "WHERE id_integracao = '$id_integracao' AND id_contrato_plano_pessoa = '$id_contrato'");
                                $checked_processo = '';
                                foreach($campos_requeridos as $key => $conteudo){
                                    if($campos_requeridos[$key]["nome"] == 'tecnico_responsavel'){
                                        $checked_tecnico = $campos_requeridos[$key]["requerido"] == "1" ? 'checked' : 'unchecked';
                                    }
                                }
                                foreach($campos_requeridos as $key => $conteudo){
                                    if($campos_requeridos[$key]["nome"] == 'campo_processo'){
                                        $checked_processo = $campos_requeridos[$key]["requerido"] == "1" ? 'checked' : 'unchecked';
                                    }
                                }
                                
                            }
                            ?>

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Parâmetros dos recursos:</h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Reiniciar conexão:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="reiniciar_conexao" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_reiniciar == 'checked' ? 'checked' : ''?> name="reiniciar_conexao" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_reiniciar == 'unchecked' ? 'checked' : ''?> name="reiniciar_conexao" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Diagnosticar conexão:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="diagnosticar_conexao" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_diagnosticar == 'checked' ? 'checked' : ''?> name="diagnosticar_conexao" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_diagnosticar == 'unchecked' ? 'checked' : ''?> name="diagnosticar_conexao" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Desbloquear contrato:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="desbloquear_contrato" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_desbloquear == 'checked' ? 'checked' : ''?> name="desbloquear_contrato" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_desbloquear == 'unchecked' ? 'checked' : ''?> name="desbloquear_contrato" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Enviar 2ª via do boleto:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="enviar_boleto" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_boleto == 'checked' ? 'checked' : ''?> name="enviar_boleto" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_boleto == 'unchecked' ? 'checked' : ''?> name="enviar_boleto" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Acesso aos dados de login:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="acesso_login" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_login == 'checked' ? 'checked' : ''?> name="acesso_login" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_login == 'unchecked' ? 'checked' : ''?> name="acesso_login" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Integração no monitoramento:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="integracao_monitoramento" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_monitoramento == 'checked' ? 'checked' : ''?> name="integracao_monitoramento" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_monitoramento == 'unchecked' ? 'checked' : ''?> name="integracao_monitoramento" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Zerar Mac:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="zerar_mac" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_zerar == 'checked' ? 'checked' : ''?> name="zerar_mac" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_zerar == 'unchecked' ? 'checked' : ''?> name="zerar_mac" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Sinal RX:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="sinal_rx" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_sinal_rx == 'checked' ? 'checked' : ''?> name="sinal_rx" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_sinal_rx == 'unchecked' ? 'checked' : ''?> name="sinal_rx" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Rede e senha wi-fi:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="senha_wifi" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_senha_wifi == 'checked' ? 'checked' : ''?> name="senha_wifi" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_senha_wifi == 'unchecked' ? 'checked' : ''?> name="senha_wifi" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Desbloquear velocidade reduzida:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="nome[]" value="desbloquear_vel_reduzida" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_desbloquer_vel_reduzida == 'checked' ? 'checked' : ''?> name="desbloquear_vel_reduzida" value="habilitado" />
                                                        Habilitado
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_desbloquer_vel_reduzida == 'unchecked' ? 'checked' : ''?> name="desbloquear_vel_reduzida" value="desabilitado" />
                                                        Desabilitado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Parâmetros de campos obrigatórios:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Técnico responsável:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="campo[]" value="tecnico_responsavel" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_tecnico == 'checked' ? 'checked' : ''?> name="tecnico_responsavel" value="sim" />
                                                        Sim
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_tecnico == 'unchecked' ? 'checked' : ''?> name="tecnico_responsavel" value="nao" />
                                                        Não
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <label>Processo:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="campo[]" value="campo_processo" />
                                                <div class="radio">
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_processo == 'checked' ? 'checked' : ''?> name="campo_processo" value="sim" />
                                                        Sim
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" <?=$checked_processo == 'unchecked' ? 'checked' : ''?> name="campo_processo" value="nao" />
                                                        Não
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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

                                echo "<button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>";

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

    $('#sistema').on('change', function() {
        id = $(this).val();
        $.ajax({
            url: "/api/ajax?class=ListaParametrosIntegracao.php",
            method: "POST",
            dataType: "json",
            data: {
                parametros: {
                    'id': id,
                },
                token: '<?= $request->token ?>'
            },
            success: function(data){
                $('#parametros').html(data);
            }
        });
    });
    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response) {
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: {
                            'nome': $('#busca_contrato').val(),
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            focus: function(event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function(event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);
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
            return $("<li>").append("<a><strong>" + item.id_contrato_plano_pessoa + " - " + item.nome + "" + item.nome_contrato + " </strong><br>" + item.razao_social + "<br>" + item.cpf_cnpj + "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id) {
        var busca = $('#busca_contrato').val();
        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id': id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
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
    $(document).on('click', '#habilita_busca_contrato', function() {
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });
    



    <?php
    if ($integracao_parametros) {
        foreach ($integracao_parametros as $conteudo) {
            if ($conteudo['obrigatorio'] == '1' && $conteudo['codigo']) {
                echo "$('." . $conteudo['codigo'] . "').attr('required', true);";
            }
        }
    }
    ?>
    $(document).on('submit', '#integracao_form', function() {
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        if (!id_contrato_plano_pessoa || id_contrato_plano_pessoa == 0) {
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }
        modalAguarde();
    });
</script>