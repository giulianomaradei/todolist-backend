<style>
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
    }
</style>
<?php
require_once(__DIR__."/../class/System.php");
    
    $id_usuario_sessao = $_SESSION['id_usuario'];
    $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
    $perfil_usuario = $dados[0]['id_perfil_sistema'];

    $tituloPainel = 'Visualização';
    $operacao = 'alterar';
    $id = (int)$_GET['visualizar'];
    $dados = DBRead('', 'tb_erro_atendimento', "WHERE id_erro_atendimento = '".$id."'");
    
    $id_tipo_erro = $dados[0]['id_tipo_erro'];
    $assinante = $dados[0]['assinante'];
    $data_erro = converteData($dados[0]['data_erro']);
    $hora_erro = $dados[0]['hora_erro'];
    $hora_erro = explode(":", $hora_erro);
    $hora_erro = $hora_erro[0].":".$hora_erro[1];
    $data_cadastrado = converteDataHora($dados[0]['data_cadastrado']);
    $data_justificativa = converteDataHora($dados[0]['data_justificativa']);
    $protocolo = $dados[0]['protocolo'];
    $justificativa = $dados[0]['justificativa'];
    $precaucao_futura = $dados[0]['precaucao_futura'];
    $descricao_cliente = $dados[0]['descricao_cliente'];
    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
    $id_usuario = $dados[0]['id_usuario'];

    $justificativa = $dados[0]['justificativa'];
    $precaucao_futura = $dados[0]['precaucao_futura'];
    
    if($dados[0]['origem'] == 1){
        $origem = "Cliente";
    }else{
        $origem = "Belluno";
    }
    
    $dados_parecer = DBRead('', 'tb_erro_atendimento_lider', "WHERE id_erro_atendimento = '".$id."'");
    $parecer = $dados_parecer[0]['parecer'];
    $dados_nome = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '$id_usuario'");

    $id_usuario_cadastrou = $dados[0]['id_usuario_cadastrou'];

    $dados_liderado = DBRead('', 'tb_usuario a', "INNER JOIN tb_erro_atendimento b ON a.id_usuario = b.id_usuario WHERE b.id_erro_atendimento = '".$id."' AND lider_direto = '".$id_usuario_sessao."' ");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Visualização - Reclamação/Erro:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ErroLido.php" id="erro_atendimento_cadastro_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
				    <input type="hidden" value="<?= $id ?>" name="id_erro_atendimento"/>

                    <div class="panel-body" style="padding-bottom: 0;">

                        <div class="row">
                            <div class="col-md-12">

                                    <?php
                                    $cliente = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

                                   $supervisor = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '$id_usuario_cadastrou'");

                                   $tipo = DBRead('', 'tb_tipo_erro', "WHERE id_tipo_erro = '".$id_tipo_erro."'");
                                    ?>

                                    <table class="table" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th class = "col-md-3">Cliente</th>
                                                <th class = "col-md-3">Protocolo</th>
                                                <th class = "col-md-3">Tipo</th>
                                                <th class = "col-md-3">Data da ocorrência</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= $cliente[0]['nome'] ?></td>
                                                <?php
                                                	if($protocolo){
                                                		 echo '<td>'.$protocolo.'</td>';
                                                	}else{
                                                		 echo '<td>N/D</td>';
                                                	}
                                                ?>
                                               
                                                <td><?= $tipo[0]['nome'] ?></td>
                                                <td><?= $data_erro." ".$hora_erro ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th class = "col-md-3">Liderado</th>
                                                <th class = "col-md-3">Criado por</th>
                                                <th class = "col-md-3">Origem</th>
                                                <th class = "col-md-3">Data do cadastro</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= $dados_nome[0]['nome'] ?></td>
                                                <td><?= $supervisor[0]['nome'] ?></td>
                                                <td><?= $origem ?></td>
                                                <td><?= $data_cadastrado ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="margin: 0;">
                                    <thead>
                                        <tr>
                                            <th class = "col-md-12">Descrição da reclamação/erro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="conteudo-editor">
                                                    <?= $descricao_cliente ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>&nbsp;&nbsp;Parecer</label>
                                    <div class="conteudo-editor">
                                       <?php if($parecer || !$dados_liderado){
                                                echo '<textarea name="parecer" id="parecer" required class="form-control" disabled>'.$parecer.'</textarea>';
                                            }else{
                                                echo '<textarea name="parecer" id="parecer" required class="form-control">'.$parecer.'</textarea>';
                                            }
                                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Justificativa:</label>
                                    <div class="conteudo-editor">
                                        <textarea name="" id="" required class="form-control" disabled><?=$justificativa?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Precaução Futura</label>
                                    <div class="conteudo-editor">
                                            <textarea name="" id="" required class="form-control" disabled><?=$precaucao_futura?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
   
                    </div>
                    <?php 
                    if($dados_liderado){
                        if(!$parecer){

                            echo '<div class="panel-footer">
                                <div class="row">
                                    <div class="col-md-12" style="text-align: center">
                                    <input type="hidden" id="operacao" value="1" name="inserir"/>
                                        <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                                    </div>
                                </div>
                            </div>';
                            
                        }
                    }
                    ?>
               </form>
            </div>
        </div>
    </div>
</div>     
