<style>
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
    }
</style>
<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_erro_atendimento', "WHERE id_erro_atendimento = $id");
    $id_tipo_erro = $dados[0]['id_tipo_erro'];
    $assinante = $dados[0]['assinante'];
    $protocolo = $dados[0]['protocolo'];
    $descricao_cliente = $dados[0]['descricao_cliente'];
    $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
    $id_usuario = $dados[0]['id_usuario'];
    $id_usuario_cadastrou = $dados[0]['id_usuario_cadastrou'];
    if($dados[0]['origem'] == 1){
        $origem = "Cliente";
    }else{
        $origem = "Belluno";
    }

}else if(isset($_GET['inserir_justificativa'])){

    $tituloPainel = 'Inserir Justificativa';
    $operacao = 'inserir_justificativa';
    $id = (int)$_GET['inserir_justificativa'];

    $dados = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_erro_atendimento_lider b ON a.id_erro_atendimento = b.id_erro_atendimento WHERE a.id_erro_atendimento = '$id' AND a.id_usuario = '".$_SESSION['id_usuario']."' AND ((justificativa = '' OR justificativa IS NULL) OR (precaucao_futura= '' OR precaucao_futura IS NULL) OR (parecer= '' OR parecer IS NULL))");

    if($dados){
        $id_tipo_erro = $dados[0]['id_tipo_erro'];
        $assinante = $dados[0]['assinante'];
        $protocolo = $dados[0]['protocolo'];
        $descricao_cliente = $dados[0]['descricao_cliente'];
        $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
        $id_usuario = $dados[0]['id_usuario'];
        $id_usuario_cadastrou = $dados[0]['id_usuario_cadastrou'];
        $parecer = $dados[0]['parecer'];
        $data_erro = $dados[0]['data_erro'].' '.$dados[0]['hora_erro'];
        if($dados[0]['origem'] == 1){
            $origem = "Cliente";
        }else{
            $origem = "Belluno";
        }

        $justificativa = $dados[0]['justificativa'];
        $precaucao_futura = $dados[0]['precaucao_futura'];

    }else{
        echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não tem permissão de acesso!</div></div>";
        exit;
    }
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $justificativa = '';
    $precaucao_futura = '';
    $data_justificativa = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> - Reclamação/Erro:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ErroAtendimento.php" id="erro_atendimento_justificativa_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">

                        <div class="row">
                            <div class="col-md-12">

                                    <?php
                                    if($dados){
                                        $cliente = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

                                        $supervisor = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = $id_usuario_cadastrou");

                                        $tipo = DBRead('', 'tb_tipo_erro', "WHERE id_tipo_erro = '".$id_tipo_erro."'");
                                    }
                                    
                                    ?>

                                    <table class="table" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th class = "col-md-3">Cliente</th>
                                                <th class = "col-md-2">Protocolo</th>
                                                <th class = "col-md-2">Tipo</th>
                                                <th class = "col-md-3">Criado por</th>
                                                <th class = "col-md-2">Origem</th>
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
                                                <td><?= $supervisor[0]['nome'] ?></td>
                                                <td><?= $origem ?></td>
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
                                                <th>Descrição do cliente</th>
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
                                <div class="alert alert-warning" role="alert" style="padding: 0">
                                    <table class="table" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th>Parecer do Líder</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="conteudo-editor">
                                                        <?= $parecer ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*O que me levou a cometer o equívoco</label>
                                    <div class="conteudo-editor">
                                        <?php
                                            if($parecer){
                                                if($justificativa){
                                                    echo '<textarea name="" id="" required class="form-control" disabled>'.$justificativa.'</textarea>';
                                                }else{
                                                    echo '<textarea name="justificativa" id="justificativa" required class="form-control">'.$justificativa.'</textarea>';
                                                }
                                            }else{
                                                echo '<textarea name="" id="" required class="form-control" disabled>'.$justificativa.'</textarea>';
                                            }
                                            
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*O que farei para não cometer o equívoco novamente</label>
                                    <div class="conteudo-editor">
                                        <?php
                                            if($parecer){
                                                if($precaucao_futura){
                                                    echo '<textarea name="" id="" required class="form-control" disabled>'.$precaucao_futura.'</textarea>';
                                                }else{
                                                    echo '<textarea name="precaucao_futura" id="precaucao" required class="form-control">'.$precaucao_futura.'</textarea>';
                                                }
                                            }else{
                                                echo '<textarea name="" id="" required class="form-control" disabled>'.$precaucao_futura.'</textarea>';
                                            }
                                            
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <?php
                                    if($parecer){
                                        if(isset($_GET['inserir_justificativa']) && $precaucao_futura){
                                            //echo '<button class="btn btn-primary" name="" id="" disabled><i class="fa fa-floppy-o"></i> Salvar</button>';
                                        }else{
                                            echo '<button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>';
                                        }
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
    $(document).on('submit', '#erro_atendimento_justificativa_form', function(){
        var justificativa = $("#justificativa").val();
        var precaucao = $("#precaucao").val();

        if(!justificativa){
            alert("Deve-se descrever uma justificativa!");
            return false;
        }
        if(!precaucao){
            alert("Deve-se descrever uma precaução!");
            return false;
        }
        modalAguarde();
    });
</script>