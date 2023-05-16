<?php
require_once "System.php";

$id = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
/* $acao = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_selecao = $parametros['id_selecao']; */

$dados = DBRead('', 'tb_selecao_avaliador_candidato a', "INNER JOIN tb_selecao_etapa_avaliador b ON a.id_selecao_etapa_avaliador = b.id_selecao_etapa_avaliador INNER JOIN tb_selecao_etapa c ON b.id_selecao_etapa = c.id_selecao_etapa WHERE a.id_selecao_avaliador_candidato = $id");

/* echo '<pre>';
var_dump($dados);
echo '</pre>'; */

if ($dados[0]['precisa_nota'] == 1) {
    echo '<div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Nota:</label>
                    <input type="text" name="nota" id="nota" class="form-control number_float" value="'.$dados[0]['nota'].'">
                </div>
            </div>
          </div><br>';
}

if ($dados[0]['precisa_parecer'] == 1) {
    echo '<div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Parecer:</label>
                    <textarea class="form-control" name="parecer" id="parecer" rows="4">'.$dados[0]['parecer'].'</textarea>
                </div>
            </div>
          </div>';
}
