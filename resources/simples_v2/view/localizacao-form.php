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
    echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
    exit;
}

if(isset($_GET['alterar'])){
    
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_localizacao_contrato', "WHERE id_localizacao_contrato = $id");

    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";

        $id_pessoa = $dados_contrato[0]['id_pessoa'];

        $dados_localizacao = DBRead('', 'tb_localizacao', "WHERE id_localizacao_contrato = $id");
        $endereco = $dados_localizacao[0]['endereco'];
        $ponto_referencia = $dados_localizacao[0]['ponto_referencia'];
        $latitude = $dados_localizacao[0]['latitude'];
        $longitude = $dados_localizacao[0]['longitude'];

        $cidade = $dados_localizacao[0]['id_cidade'];

        $dados_cidade = DBRead('', 'tb_cidade', "WHERE id_cidade = '$cidade'");
        $estado = $dados_cidade[0]['id_estado'];

    }else{
<<<<<<< HEAD
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
=======
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
        exit;
    }

}else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $endereco = '';
    $ponto_referencia = '';
    $latitude = '';
    $longitude = '';

    if($ativacao == 1){
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

        $id_pessoa = $dados_contrato[0]['id_pessoa'];

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
        <li class="active"><a id="li_localizacao" style="cursor: pointer;"><strong>Localização</strong></a></li>
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
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> localização:</h3>
                    <?php if (isset($_GET['alterar'])) {
                        if($ativacao == 1){
                            $exclui_ativacao = 1;
                        }else{
                            $exclui_ativacao = 0;
                        }
                        echo "<div class=\"panel-title text-right pull-right\"><a class=\"a_modalAguarde\" href=\"/api/ajax?class=Localizacao.php?excluir= $id&exclui_ativacao=$exclui_ativacao&id_contrato=$id_contrato_plano_pessoa&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Localizacao.php" id="localizacao_form" onKeyDown="if (event.keyCode == '13'){ return false }" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?php
                                    if($ativacao){
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
                                <div class="input-group">
                                    <input type="text" id="endereco-input" placeholder="Digite aqui caso o sistema não localize a cidade automaticamente." class="form-control input-sm" />
                                    <span class="input-group-btn">
                                        <button id="submit" class="btn btn-success btn-sm" type="button"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>UF:</label>
                                    <select class="form-control input-sm" name="estado" id="estado" required>
                                        <option value='99'>ND</option>
                                        <option value='' disabled>----------</option>
                                        <?php
                                        $dados = DBRead('', 'tb_estado', "WHERE id_estado != '99' ORDER BY sigla ASC");
                                        if($dados){
                                            foreach($dados as $conteudo){
                                                $idSelect = $conteudo['id_estado'];
                                                $estadoSelect = $conteudo['sigla'];
                                                $selected = $estado == $idSelect ? "selected" : "";
                                                echo "<option value='$idSelect'".$selected.">$estadoSelect</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Cidade:</label>
                                    <select class="form-control input-sm" id="cidade" name="cidade" required>
                                        <?php
                                        if($estado){
                                            $dados = DBRead('', 'tb_cidade', "WHERE id_estado = '$estado' ORDER BY nome ASC");
                                            if($dados){
                                                foreach($dados as $conteudo){
                                                    $idSelectCidade = $conteudo['id_cidade'];
                                                    $cidadeSelect = $conteudo['nome'];
                                                    $selected = $cidade == $idSelectCidade ? "selected" : "";
                                                    echo "<option value='$idSelectCidade'".$selected.">$cidadeSelect</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="endereco">Endereço:</label>
                                    <input type="text" <?=$focus?> name="endereco"  class="form-control input-sm" id="endereco" value="<?=$endereco?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ponto_referencia">Ponto de referência:</label>
                                    <input type="text" name="ponto_referencia"  class="form-control input-sm" id="ponto_referencia" value="<?=$ponto_referencia?>" />
                                    <input type="hidden" name="latitude" required class="form-control input-sm" value="<?=$latitude?>" id="latitude" />
                                    <input type="hidden" name="longitude" required class="form-control input-sm" value="<?=$longitude?>" id="longitude" />
                                    <input type="hidden" name="id_pessoa" id='id_pessoa' class="form-control input-sm" value="<?=$id_pessoa?>" id="id_pessoa" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Mapas:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div id="map" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Telefones:</h3>
                                    </div>
                                    <div class="panel-body">
                                        
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class='col-md-5'>Telefone</th>
                                                        <th class="col-md-6">Observação</th>
                                                        <th class='col-md-1'>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if($operacao == "alterar"){
                                                        $dados_telefone = DBRead('', 'tb_localizacao_telefone a', "INNER JOIN tb_localizacao b ON a.id_localizacao = b.id_localizacao WHERE a.id_localizacao = b.id_localizacao AND b.id_localizacao_contrato = $id");
                                                        foreach($dados_telefone as $conteudo){
                                                            echo "<tr class='linha_telefone'>";
                                                                echo "<td><input class='form-control input-sm telefone' name='telefone[]' value='".$conteudo['telefone']."' /></td>";
                                                                echo "<td><textarea class='form-control input-sm observacao_tel' name='observacao_tel[]'>".$conteudo['observacao']."</textarea></td>";
                                                                echo "<td><button type='button' class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-telefone' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
                                $pagina = 'localizacao-form';
                                if($ativacao == 1){

                                    $dados_localizacao = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                    $id_dados_localizacao = $dados_localizacao[0]['id_localizacao_contrato'];
                                    $proximo = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND id_localizacao_contrato > $id");
                                    $id_proximo = $proximo[0]['id_localizacao_contrato'];

                                    echo "<input type='hidden' value='$id_proximo' name='id_proximo' id='id_proximo' />";

                                    if($id_proximo && $operacao != 'inserir'){
                                        echo "<input type='hidden' id='tela_localizacao_proximo' value='1' />";
                                        $id_dados_pular = $id_proximo;
                                    }else{
                                        echo "<input type='hidden' id='tela_localizacao_proximo' value='0' />";
                                        $dados_pular = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                        $id_dados_pular = $dados_pular[0]['id_plantonista_contrato'];
                                    }

                                    $voltar = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND id_localizacao_contrato < $id ORDER BY id_localizacao_contrato DESC");
                                    $id_voltar = $voltar[0]['id_localizacao_contrato'];
                                    echo "<input type='hidden' value='$id_voltar' name='id_voltar' id='id_voltar' />";

                                    if($id_voltar && $operacao != 'inserir'){
                                        echo "<input type='hidden' id='tela_localizacao_voltar' value='1' />";
                                        $dados_voltar = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                        $id_dados_voltar = $id_voltar;
                                    }else{
                                        echo "<input type='hidden' id='tela_localizacao_voltar' value='0' />";
                                        $dados_voltar = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                        $id_dados_voltar = $dados_voltar[0]['id_informacao_geral_contrato'];
                                    }

                                    echo "<input type='hidden' value='1' id='ativacao' name='ativacao' />";
                                    echo "<input type='hidden' value='" . $pagina . "' name='pagina' />";

                                    echo "<input type='hidden' id='id_dados_voltar' name='voltar' value='$id_dados_voltar' />";
                                    echo "<button class='btn btn-primary btn-comando-ativacao' id='voltar' type='button'><i class='fa fa-arrow-left' aria-hidden='true'></i> Voltar</button>";

                                    echo "<button class='btn btn-primary btn-comando-ativacao' name='salvar' value='1' id='ok' type='submit'><i class='fa fa-arrow-right' aria-hidden='true'></i> Salvar e continuar</button>";

                                    echo "<input type='hidden' id='id_dados_pular' name='pular' value='$id_dados_pular' />";
                                    echo "<button class='btn btn-primary btn-comando-ativacao' id='pular' type='button'><i class='fa fa-share' aria-hidden='true'></i> Pular</button>";

                                    echo "<button class='btn btn-primary btn-comando-ativacao' id='adicionar-localizacao' value='1' name='adicionar_localizacao' type='submit'><i class='fa fa-plus' aria-hidden='true'></i> Adicionar localização</button>";

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

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2MWfvsSfB00spwyWm-0WNSGlJvF4dajs&callback=initMap">
</script>

<script>

    function selectUfEstado(id_estado, id_cidade){        
        $("select[name=cidade]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectUfCidade.php",
            {estado: id_estado,
            token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=cidade]").html(valor);
                if(id_cidade != undefined){
                    $('#cidade').val(id_cidade);
                }
            }
        )        
    }

    $(document).on('change', 'select[name=estado]', function(){
        selectUfEstado($(this).val());
    });

    var ativacao = $('#ativacao').val();
    var pessoa = $("#id_pessoa").val();
    var operacao = $("#operacao").val();

    var idVoltar = $("#voltar").val();
    
    var idContrato = $("#id_contrato_plano_pessoa").val();

    var idDadosPular = $("#id_dados_pular").val();
    var idDadosVoltar = $("#id_dados_voltar").val();

    var tela_localizacao_proximo = $("#tela_localizacao_proximo").val();
    var proximo = $("#id_proximo").val();
    var tela_localizacao_voltar = $("#tela_localizacao_voltar").val();
    var voltar = $("#id_voltar").val();

    $('#pular').on('click', function(){

        if(tela_localizacao_proximo == 1){
            if(idDadosPular){
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&alterar="+proximo+"&ativacao=1&id_contrato="+idContrato;
            }else{
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&ativacao=1&id_contrato="+idContrato;
            }
        }else{
            if(idDadosPular){
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantonista-form&alterar="+idDadosPular+"&ativacao=1&id_contrato="+idContrato;
            }else{
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantonista-form&ativacao=1&id_contrato="+idContrato;
            }
        }
    });

    $('#voltar').on('click', function(){

        if(tela_localizacao_voltar == 1){
            if(idDadosVoltar){
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&alterar="+voltar+"&ativacao=1&id_contrato="+idContrato;
            }else{
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&ativacao=1&id_contrato="+idContrato;
            }
        }else{
            if(idDadosVoltar){
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-form&alterar="+idDadosVoltar+"&ativacao=1&id_contrato="+idContrato;
            }else{
                window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-form&ativacao=1&id_contrato="+idContrato;
            }
        }
    });

    $("#adiciona-telefone").on('click', function(){
        $("tbody").append("<tr class='linha_telefone'><td><input class='form-control input-sm telefone' name='telefone[]' /></td><td><textarea class='form-control input-sm observacao_tel' name='observacao_tel[]'></textarea></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");
    });
    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o usuário?')) {
            $(this).parent().parent().remove();
        }
    });

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
                            'atributo' : 'cliente',
                        }
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function(event, ui){
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function(event, ui){
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
                        'id' : id
                    }
                },
                success: function (data) {
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }

    function carregarDadosPessoa(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            geoCodificacao(id);
        }
    }

    if(ativacao == 1 && operacao == 1){
        geoCodificacao(pessoa);
    }

    $("#cep-input").on('click', function(){

            var address = document.getElementById('cep-input').value;
            geocoder.geocode({'address': address}, function(results, status){
              if(status === 'OK'){
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location
                });
            }else{
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    });
    
    function geoCodificacao(id){
        $.ajax({
            url: "class/PessoaAutocomplete.php",
            dataType: "json",
            data: {
                acao: 'consulta',
                parametros: {
                    'id' : id
                }
            },
            success: function(data){
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    address: data[0].cep + data[0].logradouro + data[0].numero
                }, function(results, status){
                    if(status === google.maps.GeocoderStatus.OK){
                        var myResult = results[0].geometry.location;
                        map.setCenter(myResult);
                        map.setZoom(17);
                    }else if(status === 'INVALID_REQUEST'){
                        alert('Requisição Inválida: ' + status);
                    }else if(status === 'ZERO_RESULTS'){
                        alert('Nenhum resultado encontrado para essa requisição: ' + status);
                    }else if(status === 'OVER_QUERY_LIMIT'){
                        alert('Cota de requisições ultrapassada: ' + status);
                    }else if(status === 'REQUEST_DENIED'){
                        alert('Requisição negada: ' + status);
                    }else if(status === 'UNKNOWN_ERROR'){
                        alert('Possível erro no servidor, recarregue a página e tente fazer sua pesquisa novamente: ' + status);
                    }
                });
            }
        });
    }

    var markers = [];

    function initMap(){
        var latitude = parseFloat(document.getElementById('latitude').value);
        var longitude = parseFloat(document.getElementById('longitude').value);
        if(document.getElementById('latitude').value == "" && document.getElementById('longitude').value == ""){
            var latitude = -30.514801;
            var longitude = -53.491168;
        }
        var myLatLng = {
            lat: latitude,
            lng: longitude
        };
        map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 17,
            disableDoubleClickZoom: true,
        });
        addMarker(myLatLng);
        google.maps.event.addListener(map, 'click', function(event){
            deleteMarkers();
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
            latitude = event.latLng.lat();
            longitude = event.latLng.lng();
            myLatLng = {
                lat: latitude,
                lng: longitude
            };
            addMarker(myLatLng);
        });

        var geocoder = new google.maps.Geocoder();
        $("#submit").on('click', function(){
            geocodeAddress(geocoder, map);
        });
    }

    function geocodeAddress(geocoder, resultsMap){
        var address = document.getElementById('endereco-input').value;
        geocoder.geocode({'address': address}, function(results, status){
            if(status === 'OK'){
                resultsMap.setCenter(results[0].geometry.location);
            }else if(status === 'INVALID_REQUEST'){
                alert('Requisição Inválida: ' + status);
            }else if(status === 'ZERO_RESULTS'){
                alert('Nenhum resultado encontrado para essa requisição: ' + status);
            }else if(status === 'OVER_QUERY_LIMIT'){
                alert('Cota de requisições ultrapassada: ' + status);
            }else if(status === 'REQUEST_DENIED'){
                alert('Requisição negada: ' + status);
            }else if(status === 'UNKNOWN_ERROR'){
                alert('Possível erro no servidor, recarregue a página e tente fazer sua pesquisa novamente: ' + status);
            }
        });
    }

    function addMarker(myLatLng){
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map
        });
        markers.push(marker);
    }
    function setMapOnAll(map){
        for (var i = 0; i < markers.length; i++){
            markers[i].setMap(map);
        }
    }
    function clearMarkers(){
        setMapOnAll(null);
    }
    function deleteMarkers(){
        clearMarkers();
        markers = [];
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_contrato', function (){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    $(document).on('submit', '#localizacao_form', function () {
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        var latitude = $("#latitude").val();
        var longitude = $("#longitude").val();
        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == 0){
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }
        if(!latitude || !longitude){
            alert("Deve-se selecionar um local válido!");
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