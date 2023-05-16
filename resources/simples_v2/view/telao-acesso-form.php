
<?php
require_once(__DIR__."/../class/System.php");

$operacao = 'alterar';
$id = 1;

$usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY nome ASC", "b.nome, b.id_pessoa, a.id_usuario");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> Acesso aos Telões:</h3>
                    <?php if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=TelaoAcesso.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
                </div>
                <form method="post" action="/api/ajax?class=TelaoAcesso.php" id="telao-acesso-form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">

                        <!-- nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="aba1 active">
                                <a data-toggle="tab" href="#tab1">Telão de <strong>Atendimento</strong></a>
                            </li>
                            <li class="aba2">
                                <a data-toggle="tab" href="#tab2">Telão de <strong>Monitoramento</strong></a>
                            </li>
                        </ul>
                        <!-- end nav tabs -->

                        <div class="tab-content">

                            <!-- tab 1  -->
                            <div id="tab1" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        <div class='table-responsive' style="max-height: 495px; overflow-y:auto;">
                                            <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-2">Permissão</th>
                                                        <th class="col-md-10">Usuário</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if($usuarios){
                                                        foreach($usuarios as $conteudo){

                                                            $id_usuario = $conteudo['id_usuario'];
                                                            $nome = $conteudo['nome'];
                                                            $ckecked_atendimento = '';

                                                            $dados_atendimento = DBRead('', 'tb_telao_acesso_atendimento', "WHERE id_usuario = $id_usuario");
                                                            if ($operacao == 'alterar') {
                                                                if($dados_atendimento){
                                                                    $ckecked_atendimento = 'checked';
                                                                }
                                                                echo "<tr><td><input name=\"usuarios_atendimento[]\" type=\"checkbox\" value=\"$id_usuario\" id=\"$id_usuario\" $ckecked_atendimento></td> <td>$nome</td></tr>";

                                                            } else {
                                                                echo "<tr><td><input name=\"usuarios_atendimento[]\" type=\"checkbox\" value=\"$id_usuario\" id=\"$id_usuario\"></td> <td>$nome</td></tr>";
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
                            
                            <!-- tab 2  -->
                            <div id="tab2" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        <div class='table-responsive' style="max-height: 495px; overflow-y:auto;">
                                            <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-2">Permissão</th>
                                                        <th class="col-md-10">Usuário</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if($usuarios){
                                                        foreach($usuarios as $conteudo){

                                                            $id_usuario = $conteudo['id_usuario'];
                                                            $nome = $conteudo['nome'];
                                                            $ckecked_monitoramento = '';

                                                            $dados_monitoramento = DBRead('', 'tb_telao_acesso_monitoramento', "WHERE id_usuario = $id_usuario");
                                                            if ($operacao == 'alterar') {
                                                                if($dados_monitoramento){
                                                                    $ckecked_monitoramento = 'checked';
                                                                }
                                                                echo "<tr><td><input name=\"usuarios_monitoramento[]\" type=\"checkbox\" value=\"$id_usuario\" id=\"$id_usuario\" $ckecked_monitoramento></td> <td>$nome</td></tr>";

                                                            } else {
                                                                echo "<tr><td><input name=\"usuarios_monitoramento[]\" type=\"checkbox\" value=\"$id_usuario\" id=\"$id_usuario\"></td> <td>$nome</td></tr>";
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

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
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

    $(document).on('submit', '#telao-acesso-form', function () {
        modalAguarde();
    });
</script>