<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
//$perfis = $parametros['perfis'];
$perfis = (!empty($parametros['perfis'])) ? join(',', $parametros['perfis']) : '';

$dados = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema IN ({$perfis})", 'b.id_usuario'); 

if ($dados) {
    echo json_encode($dados);
} else {
    echo json_encode(null);
}


