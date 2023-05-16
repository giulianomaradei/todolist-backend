<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_pagina_sistema', "WHERE id_pagina_sistema = $id");
    $nome_view = $dados[0]['nome_view'];
    $menu = $dados[0]['menu'];
    $nome_pagina = $dados[0]['nome_pagina'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome_view = '';
    $menu = '';
    $nome_pagina = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> página do sistema:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=PaginaSistema.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=PaginaSistema.php" id="pagina_sistema_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome da página:</label>
                                    <input name="nome_pagina" autofocus type="text" class="form-control input-sm" value="<?= $nome_pagina; ?>" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome da view:</label>
                                    <input name="nome_view" type="text" class="form-control input-sm" value="<?= $nome_view; ?>" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Menu:</label>
                                    <select class="form-control input-sm" name="menu">
                                        <option value=''></option>
                                        <?php
                                        $sel_menu[$menu] = 'selected';
                                        echo "<option value='cadastros'".$sel_menu['cadastros'].">Cadastros</option>";    
                                        echo "<option value='chamados'".$sel_menu['chamados'].">Chamados</option>";
                                        echo "<option value='call-center'".$sel_menu['call-center'].">Call Center</option>";
                                        echo "<option value='comercial'".$sel_menu['comercial'].">Comercial</option>";
                                        echo "<option value='financeiro'".$sel_menu['financeiro'].">Financeiro</option>";
                                        echo "<option value='infinity'".$sel_menu['infinity'].">Infinity</option>";
                                        // echo "<option value='redes'".$sel_menu['redes'].">Redes</option>";
                                        echo "<option value='relatorios'".$sel_menu['relatorios'].">Relatórios</option>";
                                        echo "<option value='ti'".$sel_menu['ti'].">TI</option>";
                                        echo "<option value='rh'".$sel_menu['rh'].">RH</option>";
                                        echo "<option value='sistema'".$sel_menu['sistema'].">Sistema</option>";
                                        echo "<option value='topicos'".$sel_menu['topicos'].">Tópicos</option>";
                                        echo "<option value='usuario'".$sel_menu['usuario'].">Usuário</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
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
    $(document).on('submit', '#pagina_sistema_form', function () {
        modalAguarde();
    });
</script>