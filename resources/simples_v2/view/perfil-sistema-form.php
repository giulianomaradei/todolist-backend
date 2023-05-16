<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema = $id");
	$nome = $dados[0]['nome'];
    $status = $dados[0]['status'];
    $id_perfil_sistema_superior = $dados[0]['id_perfil_sistema_superior'];

} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
    $nome = '';   
	$status = '1';
}

$perfis = DBRead('', 'tb_perfil_sistema', "WHERE status = 1 AND id_perfil_sistema != $id ORDER BY nome ASC");

/* $resultado1 = getVinculos($id);
var_dump($resultado1);

$resultado2 = verificaVinculo($id, 3);
var_dump($resultado2);

die(); */


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> perfil do sistema:</h3>
                    <?php if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=PerfilSistema.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
                </div>
                <form method="post" action="/api/ajax?class=PerfilSistema.php" id="perfil_sistema_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Perfil superior imediato:</label>
                                    <select name="id_perfil_sistema_superior" class="form-control input-sm">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select class="form-control input-sm" name="status" required>
                                        <option value='1' <?php if ($status == 1) {echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == 0) {echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>   
                        
                         <!-- nav tabs -->
                         <ul class="nav nav-tabs">
                            <li class="aba1 active">
                                <a data-toggle="tab" href="#tab1">Permissões</a>
                            </li>
                            <li class="aba2">
                                <a data-toggle="tab" href="#tab2">Vínculos</a>
                            </li>
                        </ul>
                        <!-- end nav tabs -->

                        <div class="tab-content">
                            <!-- tab 1 Dados pessoais  -->
                            <div id="tab1" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class='table-responsive' style="max-height: 365px; overflow-y:auto;">
                                            <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-2">Permissão</th>
                                                        <th class="col-md-5">Nome da página</th>
                                                        <th class="col-md-5">Nome da view</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" id="checkTodos" name="checkTodos"></td>
                                                        <td>Todas</td>
                                                    </tr>
                                                    <?php
                                                    $dados = DBRead('', 'tb_pagina_sistema', 'ORDER BY nome_pagina ASC');
                                                    if($dados){
                                                        foreach($dados as $conteudo){
                                                            $id_pagina = $conteudo['id_pagina_sistema'];
                                                            $nome_pagina = $conteudo['nome_pagina'];
                                                            $nome_view = $conteudo['nome_view'];
                                                            $ckecked = '';
                                                            if($operacao == 'alterar'){
                                                                $dados = DBRead('', 'tb_pagina_sistema_perfil', "WHERE id_perfil_sistema = '$id' AND id_pagina_sistema = '$id_pagina'");
                                                                if($dados){
                                                                    $ckecked = 'checked';
                                                                }
                                                                echo "<tr><td><input class='teste' name=\"permissoes[]\" type=\"checkbox\" value=\"$id_pagina\" id=\"$id_pagina\" $ckecked></td> <td>$nome_pagina</td> <td>$nome_view</td></tr>";
                                                            }else{
                                                                echo "<tr><td><input class='teste' name=\"permissoes[]\" type=\"checkbox\" value=\"$id_pagina\" id=\"$id_pagina\"></td> <td>$nome_pagina</td> <td>$nome_view</td></tr>";
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

                            <div id="tab2" class="tab-pane fade in">
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
                                                        foreach($dados as $conteudo){
                                                            $id_perfil_sistema = $conteudo['id_perfil_sistema'];
                                                            $nome_perfil = $conteudo['nome'];
                                                            $ckecked = '';

                                                            if ($operacao == 'alterar') {
                                                                $dados = DBRead('', 'tb_perfil_sistema_vinculo', "WHERE id_perfil_sistema = '$id' AND id_perfil_sistema_vinculado = '$id_perfil_sistema'");
                                                                if($dados){
                                                                    $ckecked = 'checked';
                                                                }
                                                                echo "<tr><td><input name=\"perfis_vinculados[]\" type=\"checkbox\" value=\"$id_perfil_sistema\" id=\"$id_perfil_sistema\" $ckecked></td> <td>$nome_perfil</td></tr>";
                                                            } else {
                                                                echo "<tr><td><input name=\"perfis_vinculados[]\" type=\"checkbox\" value=\"$id_perfil_sistema\" id=\"$id_perfil_sistema\"></td> <td>$nome_perfil</td></tr>";
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

    $(document).on('click', '#checkTodos', function () {

        if ( $(this).is(':checked') ){
            //$('input[name=permissoes]').prop("checked", true);
            $(".teste").each(function() {
                this.checked=true;
            });
        } else {
            //$('input[name=permissoes]').prop("checked", false);
            $(".teste").each(function() {
                this.checked=false;
            });
        }
    });

    $(document).on('submit', '#perfil_sistema_form', function () {
        modalAguarde();
    });
</script>