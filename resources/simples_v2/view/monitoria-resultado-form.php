<?php
    require_once(__DIR__."/../class/System.php");

    if (isset($_GET['alterar'])) {
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $id = (int)$_GET['alterar'];

        $dados = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_resultado = '$id' ");
        $id_usuario = $dados[0]['id_usuario'];
        $resultado = $dados[0]['resultado'];
        $disabled = 'disabled';

        $data_referencia = $dados[0]['data_referencia'];

        $arrayData = explode("-",$data_referencia);

        $mes = $arrayData[1];
        $ano = $arrayData[0]; 

    }else{
        $tituloPainel = 'Inserir';
        $operacao = 'inserir';
        $id = 1;
        $id_usuario = '';
        $resultado = '';
        $disabled = '';
        $mes_referencia = '';
        $mes = '';
        $ano = '';
    }
?>

<div class="container-fluid">
  <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
              <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> resultado:</h3>
              <div class="panel-title text-right pull-right">
              </div>
          </div>
          <form method="post" action="/api/ajax?class=Monitoria.php" id="monitoria" style="margin-bottom: 0;">
		    <input type="hidden" name="token" value="<?php echo $request->token ?>">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Atendente:</label>
                            <select class="form-control input-sm" name="id_usuario" required <?=$disabled?>>
                                <option></option>
                                <?php
                                    $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = '3' AND a.status = 1 ORDER BY b.nome ASC", "a.id_usuario, b.nome");
                                    if ($dados_usuarios) {
                                        foreach ($dados_usuarios as $conteudo_usuarios) {
                                            $selected = $id_usuario == $conteudo_usuarios['id_usuario'] ? "selected" : ""; 
                                            echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' ".$selected.">" . $conteudo_usuarios['nome'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Resultado:</label>
                            <input type="text" class="form-control input-sm number_float" name="resultado" value="<?=$resultado?>" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mês:</label>
                            <?php 
                                $sel_mes[$mes] = 'selected';
                            ?>
                            <select class="form-control input-sm" name="mes" required <?=$disabled?> >
                                <option value=""></option>
                                <option value="01" <?=$sel_mes['01']?>>Janeiro</option>
                                <option value="02" <?=$sel_mes['02']?>>Fevereiro</option>
                                <option value="03" <?=$sel_mes['03']?>>Março</option>
                                <option value="04" <?=$sel_mes['04']?>>Abril</option>
                                <option value="05" <?=$sel_mes['05']?>>Maio</option>
                                <option value="06" <?=$sel_mes['06']?>>Junho</option>
                                <option value="07" <?=$sel_mes['07']?>>Julho</option>
                                <option value="08" <?=$sel_mes['08']?>>Agosto</option>
                                <option value="09" <?=$sel_mes['09']?>>Setembro</option>
                                <option value="10" <?=$sel_mes['10']?>>Outubro</option>
                                <option value="11" <?=$sel_mes['11']?>>Novembro</option>
                                <option value="12" <?=$sel_mes['12']?>>Dezembro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ano:</label>
                            <?php 
                                $sel_ano[$ano] = 'selected';
                            ?>
                            <select class="form-control input-sm" name="ano" required <?=$disabled?>>
                                <option value="2019" <?=$sel_mes['2019']?>>2019</option>
                                <option value="2020" <?=$sel_mes['2020']?>>2020</option>
                                <option value="2021" <?=$sel_mes['2021']?>>2021</option>
                                <option value="2022" <?=$sel_mes['2022']?>>2022</option>
                                <option value="2023" <?=$sel_mes['2023']?>>2023</option>
                                <option value="2024" <?=$sel_mes['2024']?>>2024</option>
                                <option value="2025" <?=$sel_mes['2025']?>>2025</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <input type="hidden" id="operacao" value="<?=$id?>" name="<?=$operacao;?>"/>
                        <button class="btn btn-primary" name="salvar" id="ok" type="submit">
                            <i class="fa fa-floppy-o"></i> Salvar
                        </button>
                    </div>
                </div>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

