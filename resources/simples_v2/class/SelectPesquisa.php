<?php
require_once "System.php";

$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$data_de = addslashes($parametros['data_de']);
$data_ate = addslashes($parametros['data_ate']);
$id_pesquisa = addslashes($parametros['id_pesquisa']);


if ($acao == 'busca_pesquisa') {

	 $dados = DBRead('','tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_ultimo_contato BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' AND b.status != 2 GROUP BY a.id_pesquisa ORDER BY nome ASC", "a.id_pesquisa, b.titulo, d.nome");

        if($dados) {
            foreach ($dados as $conteudo) {
                $id = $conteudo['id_pesquisa'];
                $nome_titulo = $conteudo['nome']." - ".$conteudo['titulo'];
                if($id == $id_pesquisa){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
                echo "<option value='".$id."' ".$selected.">".$nome_titulo."</option>";

            }
        }
	
}else if ($acao == 'busca_pesquisa_faturamento') {

    $dados = DBRead('','tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_ultimo_contato BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' AND b.status != 2 GROUP BY a.id_pesquisa ORDER BY d.nome ASC", "a.id_pesquisa, b.titulo, d.nome");
    echo "<option value=''>Todas</option>";
    if($dados) {
           foreach ($dados as $conteudo) {
               $id = $conteudo['id_pesquisa'];
               $nome_titulo = $conteudo['nome']." - ".$conteudo['titulo'];
               if($id == $id_pesquisa){
                   $selected = 'selected';
               }else{
                   $selected = '';
               }
               echo "<option value='".$id."' ".$selected.">".$nome_titulo."</option>";

           }
       }
   
}
	