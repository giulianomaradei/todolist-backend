<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar opção';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $id_inicio = (isset($_GET['id_inicio'])) ? $_GET['id_inicio'] : 1;
    $nivel_limite = (isset($_GET['nivel_limite'])) ? $_GET['nivel_limite'] : 0;
    $contrato_select = (isset($_GET['contrato_select'])) ? $_GET['contrato_select'] : '';
    $dados = DBRead('','tb_arvore a',"INNER JOIN tb_resposta b ON a.id_resposta = b.id_resposta INNER JOIN tb_pergunta c ON a.id_pergunta = c.id_pergunta INNER JOIN tb_texto_os d ON a.id_texto_os = d.id_texto_os WHERE a.id_arvore = '$id'", "a.*, b.nome AS 'nome_resposta', c.nome AS 'nome_pergunta', d.nome AS 'nome_texto_os'");
    $nome_pergunta_title = $dados[0]['nome_pergunta'];
    $nome_pergunta = limitarTexto($nome_pergunta_title, 50);
    $nome_resposta_title = $dados[0]['nome_resposta'];
    $nome_resposta = limitarTexto($nome_resposta_title, 50);
    $resposta_pergunta = '('.$id.') <span title="Opção / Resposta: '.$nome_resposta_title.'"><strong>'.$nome_resposta.'</strong></span> - <span title="Instrução / Pergunta: '.$nome_pergunta_title.'">'.$nome_pergunta.'</span>';
    $id_resposta = $dados[0]['id_resposta'];
    $nome_resposta = $dados[0]['nome_resposta'];
    $id_texto_os = $dados[0]['id_texto_os'];
    $nome_texto_os = $dados[0]['nome_texto_os'];
    $id_pergunta = $dados[0]['id_pergunta'];
    $nome_pergunta = $dados[0]['nome_pergunta'];
    $id_subarea_problema = $dados[0]['id_subarea_problema'];
    $dados_subarea_problema = DBRead('', 'tb_subarea_problema', "WHERE id_subarea_problema = '$id_subarea_problema'");
    $id_area_problema = $dados_subarea_problema[0]['id_area_problema'];
    $complemento = $dados[0]['complemento'];
    $anotacao_padrao = $dados[0]['anotacao_padrao'];
    $resolvido = $dados[0]['resolvido'];
    
    $tag = $dados[0]['tag'];
    
    $itens_qi = explode('|',$dados[0]['quadro_informativo']);
    foreach ($itens_qi as $item) {
        $itens_qi_select[$item] = 'checked';
    }

}else if(isset($_GET['inserir'])){
    $tituloPainel = 'Inserir opção dentro de';
    $operacao = 'inserir';
    $id = (int)$_GET['inserir'];
    $id_inicio = (isset($_GET['id_inicio'])) ? $_GET['id_inicio'] : 1;
    $nivel_limite = (isset($_GET['nivel_limite'])) ? $_GET['nivel_limite'] : 1;
    $contrato_select = (isset($_GET['contrato_select'])) ? $_GET['contrato_select'] : '';
    $dados = DBRead('','tb_arvore a',"INNER JOIN tb_resposta b ON a.id_resposta = b.id_resposta INNER JOIN tb_pergunta c ON a.id_pergunta = c.id_pergunta WHERE a.id_arvore = '$id'", "b.nome AS 'nome_resposta', c.nome AS 'nome_pergunta'");
    $nome_pergunta_title = $dados[0]['nome_pergunta'];
    $nome_pergunta = limitarTexto($nome_pergunta_title, 50);
    $nome_resposta_title = $dados[0]['nome_resposta'];
    $nome_resposta = limitarTexto($nome_resposta_title, 50);
    $resposta_pergunta = '('.$id.') <span title="Opção / Resposta: '.$nome_resposta_title.'"><strong>'.$nome_resposta.'</strong></span> - <span title="Instrução / Pergunta: '.$nome_pergunta_title.'">'.$nome_pergunta.'</span>';
    $id_resposta = '';
    $nome_resposta = '';
    $id_texto_os = '';
    $nome_texto_os = '';
    $id_pergunta = '';
    $nome_pergunta = '';
    $id_subarea_problema = '';
    $id_area_problema = '';
    $complemento = '';
    $anotacao_padrao = '';
    $resolvido = '';
    
    $tag = '';
}


?>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?php echo $tituloPainel.': '.$resposta_pergunta; ?></h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Arvore.php?excluir=$id&id_inicio=$id_inicio&nivel_limite=$nivel_limite&contrato_select=$contrato_select&token=". $request->token ."\" onclick=\"if (!confirm('Excluir um passo da árvore pode é ireversível, tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Arvore.php" id="arvore_opcao_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Opção / Resposta:</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_resposta" type="text" name="busca_resposta"  value="<?=$nome_resposta?>" autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_resposta" name="habilita_busca_resposta" type="button" title="Clique para editar" style="height: 30px;"><i class="fa fa-pencil"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_resposta" id="id_resposta" value="<?=$id_resposta?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Texto OS:</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_texto_os" type="text" name="busca_texto_os"  value="<?=$nome_texto_os?>" autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_texto_os" name="habilita_busca_texto_os" type="button" title="Clique para editar" style="height: 30px;"><i class="fa fa-pencil"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_texto_os" id="id_texto_os" value="<?=$id_texto_os?>">
                                </div>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Instrução / Pergunta:</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_pergunta" type="text" name="busca_pergunta"  value="<?=$nome_pergunta?>" autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_pergunta" name="habilita_busca_pergunta" type="button" title="Clique para editar" style="height: 30px;"><i class="fa fa-pencil"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_pergunta" id="id_pergunta" value="<?=$id_pergunta?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>*Área do problema:</label>
                                    <select class="form-control input-sm" name="id_area_problema" id="id_area_problema" required>
                                        <option value=""></option>                                        
                                        <?php
                                        $dados = DBRead('', 'tb_area_problema', "ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $idSelect = $conteudo['id_area_problema'];
                                                $nomeSelect = $conteudo['nome'];
                                          		$selected = $id_area_problema == $idSelect ? "selected" : "";
                                                echo "<option value='$idSelect'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>*Subárea do problema:</label>
                                    <select class="form-control input-sm" name="id_subarea_problema" id="id_subarea_problema" required>
                                        <?php
                                        if($id_area_problema){
                                            $dados = DBRead('', 'tb_subarea_problema', "WHERE id_area_problema = '$id_area_problema' ORDER BY descricao ASC");
                                            if($dados){
                                                foreach($dados as $conteudo){
                                                    $idSelect = $conteudo['id_subarea_problema'];
                                                    $descricaoSelect = $conteudo['descricao'];
                                                    $selected = $id_subarea_problema == $idSelect ? "selected" : "";
                                                    echo "<option value='$idSelect'".$selected.">$descricaoSelect</option>";
                                                }
                                            }
                                        }else{
                                            echo '<option value="">Selecione uma área do problema antes!</option>';
                                        }
                                        ?>                                       
                                    </select>
                                </div>
                            </div>  
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>*Resolvido:</label>
                                    <select class="form-control input-sm" name="resolvido" required>
                                        <option value=""></option>                                        
                                        <option value='1' <?php if ($resolvido == '1') {echo 'selected';}?>>Sim</option>
                                        <option value='2' <?php if ($resolvido == '2') {echo 'selected';}?>>Não</option>
                                        <option value='3' <?php if ($resolvido == '3') {echo 'selected';}?>>Diagnosticado</option>
                                    </select>
                                </div>
                            </div>                                                  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class='table-responsive' style="max-height: 250px; overflow-y:auto;">
                                            <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-1"></th>
                                                        <th class="col-md-7">Contrato</th>
                                                        <th class="col-md-1"></th>
                                                        <th class="col-md-3">OS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" id="checkTodosContratos" class="checkTodos" name="checkTodosContratos"></td>
                                                        <td>Todas</td>
                                                        <td><input type="checkbox" id="checkTodosExibeOS" class="checkTodos" name="checkTodosExibeOS"></td>
                                                        <td>Todos</td>
                                                    </tr>
                                                    <?php
                                                    $dados = DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' AND (a.status = '1' OR a.status = '7')  ORDER BY b.nome ASC", "a.*, b.id_pessoa, b.nome, c.cod_servico, c.nome AS 'plano'");
                                                    if($dados){
                                                        foreach($dados as $conteudo){
                                                            $id_contrato_plano_pessoa = $conteudo['id_contrato_plano_pessoa'];
                                                            $nome_pessoa = $conteudo['nome'];
                                                            $plano = $conteudo['plano'];
                                                            $servico = getNomeServico($conteudo['cod_servico']);
                                                            $ckecked_opcao = '';
                                                            $ckecked_texto_os = '';
                                                            if($operacao == 'alterar'){
                                                                $dados_opcao = DBRead('', 'tb_arvore_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND id_arvore = '$id'");
                                                                if($dados_opcao){
                                                                    $ckecked_opcao = 'checked';
                                                                    if($dados_opcao[0]['exibe_texto_os']){
                                                                        $ckecked_texto_os = 'checked';
                                                                    }
                                                                }
                                                                echo '
                                                                    <tr>
                                                                        <td><input type="checkbox" class="checkTodosContratos" name="contrato[]" value="'.$id_contrato_plano_pessoa.'" '.$ckecked_opcao.'></td>

                                                                        <td>'.$nome_pessoa. ' - ' .$servico. ' - ' .$plano.' ('.$id_contrato_plano_pessoa.')</td>

                                                                        <td><input type="checkbox" class="checkTodosExibeOS" name="exibe_texto_os[]" value="'.$id_contrato_plano_pessoa.'" '.$ckecked_texto_os.'></td>
                                                                        <td>Exibe Texto OS</td>
                                                                    </tr>
                                                                ';
                                                            } else {
                                                                echo '
                                                                    <tr>
                                                                        <td><input type="checkbox" class="checkTodosContratos" name="contrato[]" value="'.$id_contrato_plano_pessoa.'" '.$ckecked_opcao.'></td>

                                                                        <td>'.$nome_pessoa. ' - ' .$servico. ' - ' .$plano.' ('.$id_contrato_plano_pessoa.')</td>
                                                                        
                                                                        <td><input type="checkbox" class="checkTodosExibeOS" name="exibe_texto_os[]" value="'.$id_contrato_plano_pessoa.'" '.$ckecked_texto_os.'></td>
                                                                        <td>Exibe Texto OS</td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Anotação padrão:</label>
                                    <textarea id="anotacao_padrao" name="anotacao_padrao" class="form-control input-sm" rows="5"><?=$anotacao_padrao?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Complemento:</label>
                                    <textarea id="complemento" class="ckeditor" name="complemento"><?=$complemento?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <h3 class="panel-title text-left pull-left">Informações quadro informativo:</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend>Informações</legend>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="acessos_equipamentos" <?=$itens_qi_select['acessos_equipamentos']?> type="checkbox" />Acessos a equipamentos
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="conexoes_cabos" <?=$itens_qi_select['conexoes_cabos']?> type="checkbox" />Conexões de cabos
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="localizacoes" <?=$itens_qi_select['localizacoes']?> type="checkbox" />Localizações
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="numero_retorno" <?=$itens_qi_select['numero_retorno']?> type="checkbox" />Número de retorno
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="plantonistas" <?=$itens_qi_select['plantonistas']?> type="checkbox" />Plantonistas
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="prazos_retorno" <?=$itens_qi_select['prazos_retorno']?> type="checkbox" />Prazos de retorno
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="sinais_equipamentos" <?=$itens_qi_select['sinais_equipamentos']?> type="checkbox" />Sinais dos equipamentos
                                                    </label>
                                                </div> 
                                            </div>

                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="sistemas_gestao" <?=$itens_qi_select['sistemas_gestao']?> type="checkbox" />Sistemas de gestão
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="tempo_reinicio_equipamentos" <?=$itens_qi_select['tempo_reinicio_equipamentos']?> type="checkbox" />Tempo de reinício dos equipamentos
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="uras" <?=$itens_qi_select['uras']?> type="checkbox" />URAs
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="velocidade_minimas_encaminhamento" <?=$itens_qi_select['velocidade_minimas_encaminhamento']?> type="checkbox" />Velocidades mínimas para encaminhamento
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend>Horários</legend>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_atendimento_dedicado_tecnicos" <?=$itens_qi_select['horario_atendimento_dedicado_tecnicos']?> type="checkbox" />Horários de atendimento dedicado dos técnicos de campo
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_atendimento_domestico_tecnicos" <?=$itens_qi_select['horario_atendimento_domestico_tecnicos']?> type="checkbox" />Horários de atendimento doméstico dos técnicos de campo
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_retorno_telefonico" <?=$itens_qi_select['horario_retorno_telefonico']?> type="checkbox" />Horários de retorno telefônico do provedor.
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_atendimento_empresarial_tecnicos" <?=$itens_qi_select['horario_atendimento_empresarial_tecnicos']?> type="checkbox" />Horários de atendimento empresarial dos técnicos de campo
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_monitoramento" <?=$itens_qi_select['horario_monitoramento']?> type="checkbox" />Horários de monitoramento
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_empresa_aberta" <?=$itens_qi_select['horario_empresa_aberta']?> type="checkbox" />Horários que a empresa está aberta
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="informacoes_qi[]" value="horario_atendimento_texto" <?=$itens_qi_select['horario_atendimento_texto']?> type="checkbox" />Horários de atendimento via texto
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                    
                                        <fieldset>
                                            <legend>Informações gerais e de registro:</legend>
                                                    <div class="col-md-4">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="acesso_controladoras" <?=$itens_qi_select['acesso_controladoras']?> type="checkbox" />Sistema informa se o cliente está logado
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="bloqueados" <?=$itens_qi_select['bloqueados']?> type="checkbox" />Bloqueados
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="inativo_cancelado" <?=$itens_qi_select['inativo_cancelado']?> type="checkbox" />Cadastro inativo/cancelado
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="cancelamento" <?=$itens_qi_select['cancelamento']?> type="checkbox" />Cancelamento
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="confirmacao_cadastro_cliente" <?=$itens_qi_select['confirmacao_cadastro_cliente']?> type="checkbox" />Confirmação cadastro cliente
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="contratacao_servico" <?=$itens_qi_select['contratacao_servico']?> type="checkbox" />Contratação de serviço
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="classificacao_atendimento_sistema_gestao" <?=$itens_qi_select['classificacao_atendimento_sistema_gestao']?> type="checkbox" />Classificação de atendimento no sistema de gestão
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="descontos" <?=$itens_qi_select['descontos']?> type="checkbox" />Descontos
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="informacoes_adicionais" <?=$itens_qi_select['informacoes_adicionais']?> type="checkbox" />Informações adicionais
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="nao_cliente" <?=$itens_qi_select['nao_cliente']?> type="checkbox" />Não cliente
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="selecao_finalizacao_sistema" <?=$itens_qi_select['selecao_finalizacao_sistema']?> type="checkbox" />Seleção de finalização no sistema de gestão
                                                            </label>
                                                        </div>

                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="servico_telefonia" <?=$itens_qi_select['servico_telefonia']?> type="checkbox" />Serviços de telefonia
                                                            </label>
                                                        </div>

                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="tv_assinatura" <?=$itens_qi_select['tv_assinatura']?> type="checkbox" />TV por assinatura
                                                            </label>
                                                        </div>

                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="servico_streaming" <?=$itens_qi_select['servico_streaming']?> type="checkbox" />Serviço Streaming
                                                            </label>
                                                        </div>

                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="segunda_via_boleto" <?=$itens_qi_select['segunda_via_boleto']?> type="checkbox" />Segunda via de boleto
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="situacao" <?=$itens_qi_select['situacao']?> type="checkbox" />Situações adversas
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="suporte_acesso_lento" <?=$itens_qi_select['suporte_acesso_lento']?> type="checkbox" />Suporte a acesso lento/medidor de velocidade
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="suporte_computadores" <?=$itens_qi_select['suporte_computadores']?> type="checkbox" />Suporte a computadores
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="suporte_dispositivos_moveis" <?=$itens_qi_select['suporte_dispositivos_moveis']?> type="checkbox" />Suporte a dispositivos móveis, smartTV e video game
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="suporte_roteadores" <?=$itens_qi_select['suporte_roteadores']?> type="checkbox" />Suporte a roteadores
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="posicao_os" <?=$itens_qi_select['posicao_os']?> type="checkbox" />Posição de O.S.
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="posicao_instalacao" <?=$itens_qi_select['posicao_instalacao']?> type="checkbox" />Posição de instalação
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="tipo_equipamento" <?=$itens_qi_select['tipo_equipamento']?> type="checkbox" />Tipo de equipamento
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="tipo_os" <?=$itens_qi_select['tipo_os']?> type="checkbox" />Tipo de O.S.
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="troca_comodo" <?=$itens_qi_select['troca_comodo']?> type="checkbox" />Troca de cômodo
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="troca_endereco" <?=$itens_qi_select['troca_endereco']?> type="checkbox" />Troca de endereço
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="troca_plano" <?=$itens_qi_select['troca_plano']?> type="checkbox" />Troca de plano
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="planos" <?=$itens_qi_select['planos']?> type="checkbox" />Planos
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="informacoes_qi[]" value="velocidade_reduzida" <?=$itens_qi_select['velocidade_reduzida']?> type="checkbox" />Velocidade reduzida
                                                            </label>
                                                        </div>
                                                    </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observação (Tag):</label>
                                    <textarea id="tag" name="tag" class="form-control input-sm" rows="5"><?=$tag?></textarea>
                                </div>
                            </div>              
                        </div>

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <input type="hidden" value="<?= $id_inicio; ?>" name="id_inicio"/>
                                <input type="hidden" value="<?= $nivel_limite; ?>" name="nivel_limite"/>
                                <input type="hidden" value="<?= $contrato_select; ?>" name="contrato_select"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>     
<script>

    CKEDITOR.replace('complemento', {
        height: 300
    });

    // Atribui evento e função para limpeza dos campos
    $('#busca_resposta').on('input', limpaCamposResposta());

    // Dispara o Autocomplete da resposta a partir do segundo caracter
    $("#busca_resposta").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=RespostaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: {
                            'nome' : $('#busca_resposta').val()
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_resposta").val(ui.item.nome);
                carregarDadosResposta(ui.item.id_resposta);
                return false;
            },
            select: function (event, ui) {
                $("#busca_resposta").val(ui.item.nome);
                $('#busca_resposta').attr("readonly", true);
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

        return $("<li>").append("<a><strong>"+item.id_resposta+" - "+ item.nome + " </strong></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosResposta(id){
        var busca = $('#busca_resposta').val();

        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=RespostaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_resposta').val(data[0].id_resposta);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposResposta(){
        var busca = $('#busca_resposta').val();

        if(busca == ""){
            $('#id_resposta').val('');
        }
    }

    $(document).on('keydown', '#busca_resposta', function(e){
        if(e.wich != 13 && e.keyCode != 13 && e.wich != 40 && e.keyCode != 40 && e.keyCode != 39 && e.wich != 39 && e.keyCode != 38 && e.wich != 38 && e.keyCode != 37 && e.wich != 37){
            $('#id_resposta').val('');
        }
        
    });

    $(document).on('click', '#habilita_busca_resposta', function () {
        $('#id_resposta').val('');
        $('#busca_resposta').val('');
        $('#busca_resposta').attr("readonly", false);
        $('#busca_resposta').focus();
    });


    // Atribui evento e função para limpeza dos campos
    $('#busca_pergunta').on('input', limpaCamposPergunta());

    // Dispara o Autocomplete da pergunta a partir do segundo caracter
    $("#busca_pergunta").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=PerguntaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_pergunta').val()
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pergunta").val(ui.item.nome);
                carregarDadosPergunta(ui.item.id_pergunta);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pergunta").val(ui.item.nome);
                $('#busca_pergunta').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }

        return $("<li>").append("<a><strong>"+item.id_pergunta+" - "+ item.nome + " </strong></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPergunta(id) {
        var busca = $('#busca_pergunta').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=PerguntaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: { 
                        'id' : id,                            
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_pergunta').val(data[0].id_pergunta);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPergunta() {
        var busca = $('#busca_pergunta').val();

        if (busca == "") {
            $('#id_pergunta').val('');
        }
    }  
   
    $(document).on('keydown', '#busca_pergunta', function (e) {
        if(e.wich != 13 && e.keyCode != 13 && e.wich != 40 && e.keyCode != 40 && e.keyCode != 39 && e.wich != 39 && e.keyCode != 38 && e.wich != 38 && e.keyCode != 37 && e.wich != 37){
            $('#id_pergunta').val('');
        }
        
    });

    $(document).on('click', '#habilita_busca_pergunta', function () {
        $('#id_pergunta').val('');
        $('#busca_pergunta').val('');
        $('#busca_pergunta').attr("readonly", false);
        $('#busca_pergunta').focus();
    });


    // Atribui evento e função para limpeza dos campos
    $('#busca_texto_os').on('input', limpaCamposTextoOS());

    // Dispara o Autocomplete da texto_os a partir do segundo caracter
    $("#busca_texto_os").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=TextoOSAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_texto_os').val()
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_texto_os").val(ui.item.nome);
                carregarDadosTextoOS(ui.item.id_texto_os);
                return false;
            },
            select: function (event, ui) {
                $("#busca_texto_os").val(ui.item.nome);
                $('#busca_texto_os').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }

        return $("<li>").append("<a><strong>"+item.id_texto_os+" - "+ item.nome + " </strong></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosTextoOS(id) {
        var busca = $('#busca_texto_os').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=TextoOSAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_texto_os').val(data[0].id_texto_os);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposTextoOS() {
        var busca = $('#busca_texto_os').val();

        if (busca == "") {
            $('#id_texto_os').val('');
        }
    }
   
    $(document).on('keydown', '#busca_texto_os', function (e) {
        if(e.wich != 13 && e.keyCode != 13 && e.wich != 40 && e.keyCode != 40 && e.keyCode != 39 && e.wich != 39 && e.keyCode != 38 && e.wich != 38 && e.keyCode != 37 && e.wich != 37){
            $('#id_texto_os').val('');
        }
        
    });

    $(document).on('click', '#habilita_busca_texto_os', function () {
        $('#id_texto_os').val('');
        $('#busca_texto_os').val('');
        $('#busca_texto_os').attr("readonly", false);
        $('#busca_texto_os').focus();
    });


    $(document).on('click', '.checkTodos', function () {
        var class_check = $(this).attr('id');
        if ( $(this).is(':checked') ){            
            $('.'+class_check).prop("checked", true);
        }else{
            $('.'+class_check).prop("checked", false);
        }
    });

    $(document).on('click', '.checkTodosExibeOS', function () {
        var class_check = $(this).attr('id');
        if ( $(this).is(':checked') ){            
            $('.'+class_check).prop("checked", true);
        }else{
            $('.'+class_check).prop("checked", false);
        }
    });

    function selectAreaSubareaProblema(id_area_problema){        
        $("select[name=id_subarea_problema]").html('<option value="">Carregando...</option>');
        $.post("class/SelectAreaSubareaProblema.php",
            {area_problema: id_area_problema},
            function(valor){
                $("select[name=id_subarea_problema]").html(valor);
            }
        )        
    }

    $(document).on('change', 'select[name=id_area_problema]', function(){
        selectAreaSubareaProblema($(this).val());
    });

    $(document).on('submit', '#arvore_opcao_form', function () {
        if($('#busca_resposta').val() == ''){
            alert('Preencha o campo "Opção / Resposta"!');
            return false;
        }
        if($('#busca_texto_os').val() == ''){
            alert('Preencha o campo "Texto OS"!');
            return false;
        }
        if($('#busca_pergunta').val() == ''){
            alert('Preencha o campo "Instrução / Pergunta"!');
            return false;
        }
        modalAguarde();
    });
</script>