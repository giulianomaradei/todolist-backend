<?php
require_once(__DIR__."/../class/System.php");

$id = $perfil_usuario;
$dados = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema = $id");
$nome = $dados[0]['nome'];
$status = $dados[0]['status'];
$id_perfil_sistema_superior = $dados[0]['id_perfil_sistema_superior'];

$perfis = DBRead('', 'tb_perfil_sistema', "WHERE status = 1 AND id_perfil_sistema != $id ORDER BY nome ASC");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"> Perfil do sistema: Informações</h3>
                    <?php if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=PerfilSistema.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
                </div>
                <form method="post" action="/api/ajax?class=PerfilSistema.php" id="perfil_sistema_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Perfil superior imediato:</label>
                                    <select name="id_perfil_sistema_superior" class="form-control input-sm" disabled>
                                        <option value="0"></option>
                                        <?php
                                        if($perfis):
                                            foreach($perfis as $perfil):
                                        ?>
                                                <option value="<?=$perfil['id_perfil_sistema']?>" <?=$perfil['id_perfil_sistema'] == $id_perfil_sistema_superior ? 'selected' : ''?>><?=$perfil['nome']?></option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>   

                        <div class="tab-content">
                        
                            <div id="tab2" class="tab-pane fade in active">
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class='table-responsive' style="max-height: 365px; overflow-y:auto;">
                                            <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-2">Vínculados</th>
                                                        <th class="col-md-10">Perfis</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $dados = DBRead('', 'tb_perfil_sistema', 'WHERE status = 1 ORDER BY nome ASC');
                                                    if($dados){
                                                        $cont = 0;
                                                        foreach($dados as $conteudo){
                                                            $id_perfil_sistema = $conteudo['id_perfil_sistema'];
                                                            $nome_perfil = $conteudo['nome'];
                                                            $ckecked = '';

                                                            $dados = DBRead('', 'tb_perfil_sistema_vinculo', "WHERE id_perfil_sistema = $id AND id_perfil_sistema_vinculado = '$id_perfil_sistema'");

                                                            if($dados){
                                                                $cont++;
                                                                $ckecked = 'checked';
                                                                echo "<tr><td><input name=\"perfis_vinculados[]\" type=\"checkbox\" value=\"$id_perfil_sistema\" id=\"$id_perfil_sistema\" $ckecked disabled></td> <td>$nome_perfil</td></tr>";
                                                            }
                                                    
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <?php 
                                                if ($cont == 0) {
                                                    echo "<p class='alert alert-warning' style='text-align: center'>";
                                                        echo "Seu perfil não possui vínculo(s) com nenhum outro!";
                                                    echo "</p>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>