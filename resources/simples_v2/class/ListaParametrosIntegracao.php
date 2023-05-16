<?php
require_once "System.php";


$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : array();
$id_integracao = $parametros['id'];

$parametros_integracao = DBRead('', 'tb_integracao_parametro', "WHERE id_integracao = '$id_integracao'");

$html = '';
$valor = '';

foreach($parametros_integracao as $conteudo){

    $html .= "<div class='col-md-6'>";

    if($conteudo['tipo'] == 'text'){

        $required = $conteudo['obrigatorio'] == 1 ? 'required' : '';

        $html .= "
        <label>".$conteudo['nome'].":</label>
        <input type='hidden' name='id_integracao_parametro[]' value='".$conteudo['id_integracao_parametro']."' />
        <input type='text' class='form-control' $required name='".$conteudo['codigo']."' value='' />
        ";

    }
    if($conteudo['tipo'] == 'radio'){

        $required = $conteudo['obrigatorio'] == 1 ? 'required' : '';

        $opcoes = DBRead('', 'tb_integracao_valores_tipo_parametro', "WHERE id_integracao_parametro = '".$conteudo['id_integracao_parametro']."'");
        $html .= "<label>".$conteudo['nome'].":</label>";
        $html .= "<input type='hidden' $required name='id_integracao_parametro[]' value='".$conteudo['id_integracao_parametro']."' />";
        foreach($opcoes as $opcao){
            $html .= "<div class='checkbox'><label><input type='radio' name='".$conteudo['codigo']."' value='".$opcao['valor']."' /> ".$opcao['titulo']."</label></div>";
        }
    }

    $html .= "</div>";
}

echo json_encode($html);
?>