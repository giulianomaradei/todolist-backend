<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$tb_alterada = addslashes($parametros['tb_alterada']);
$id_tb_alterada = addslashes($parametros['id_tb_alterada']);
$tipo_operacao = addslashes($parametros['tipo_operacao']);
$campo_alterado = addslashes($parametros['campo_alterado']);
$valor_campo_alterado = addslashes($parametros['valor_campo_alterado']);
$data = addslashes($parametros['data']);
$sistema = addslashes($parametros['sistema']);
$limitador = addslashes($parametros['limitador']);
$ordenacao = addslashes($parametros['ordenacao']);

$filtros_query = 'WHERE id_log ';

if($tb_alterada){
    $filtros_query .= "AND tb_alterada = '".$tb_alterada."' ";
}

if($id_tb_alterada){
    $filtros_query .= "AND id_tb_alterada = '".$id_tb_alterada."' ";
}

if($tipo_operacao){
    $filtros_query .= "AND tipo_operacao = '".$tipo_operacao."' ";
}else{
    $filtros_query .= "AND tipo_operacao != 'email' ";
}

if($campo_alterado && $valor_campo_alterado){
    $filtros_query .= "AND dados_tb_alterada like '%".$campo_alterado.": ".$valor_campo_alterado."%' ";
}

if($data){
    $filtros_query .= "AND data like '".converteData($data)."%' ";
}

if($sistema){
    $filtros_query .= "AND sistema = '".$sistema."' ";
}

$filtros_query .= "ORDER BY id_log ".$ordenacao." ";

if(!$limitador){
    $filtros_query .= "LIMIT 100 ";
}else{
    $filtros_query .= "LIMIT ".$limitador." ";
}

echo '
<div class="row">
    <div class="col-md-12">
        <div class="form-group has-feedback">
            <label>Consulta:</label>
            <textarea class="form-control" rows="2" disabled style="cursor:context-menu;">'.$filtros_query.'</textarea>
        </div>
    </div>
</div>';


$dados = DBRead('', 'tb_log',$filtros_query);
if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
        echo "Não foram encontrados registros!";
    echo "</p>";
} else {
    echo "<div class='table-responsive' style='border-radius: 15px; border: 1px solid #ddd;'>";
        echo "<table class='table table-bordered table-hover dataTable'>";
            echo "<thead style='background-color: #eee;'>";
                echo "<tr>";
                    echo "<th class=\"col-md-1\" style='vertical-align: middle;'>ID Log</th>";
                    echo "<th class=\"col-md-2\" style='vertical-align: middle;'>Usuário</th>";
                    echo "<th class=\"col-md-2\" style='vertical-align: middle;'>Operação</th>";
                    echo "<th class=\"col-md-1\" style='vertical-align: middle;'>Tipo de Operação</th>";
                    echo "<th class=\"col-md-1\" style='vertical-align: middle;'>Tabela Alterada</th>";
                    echo "<th class=\"col-md-1\" style='vertical-align: middle;'>ID Tabela Alterada</th>";
                    echo "<th class=\"col-md-3\" style='vertical-align: middle;'>Dados Tabela Alterada</th>";
                    echo "<th class=\"col-md-2\" style='vertical-align: middle;'>Data Hora</th>";
                    echo "<th class=\"col-md-2\" style='vertical-align: middle;'>IP Origem</th>";
                    echo "<th class=\"col-md-2\" style='vertical-align: middle;'>Sistema</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($dados as $conteudo) {
                $id_log = $conteudo['id_log'];

                $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario']."' ","a.id_usuario, b.nome");
                $usuario = "(".$dados_usuario[0]['id_usuario'].") ".$dados_usuario[0]['nome'];

                $operacao = $conteudo['operacao'];  

                $tipo_operacao = $conteudo['tipo_operacao'];
                if($conteudo['tipo_operacao'] == 'i'){
                    $tipo_operacao = 'Inserção';
                }else if($conteudo['tipo_operacao'] == 'a'){
                    $tipo_operacao = 'Alteração';
                }else if($conteudo['tipo_operacao'] == 'e'){
                    $tipo_operacao = 'Exclusão';
                }else if($conteudo['tipo_operacao'] == 'la'){
                    $tipo_operacao = 'Login Aceito';
                }else if($conteudo['tipo_operacao'] == 'ln'){
                    $tipo_operacao = 'Login Negado';
                }else if($conteudo['tipo_operacao'] == 'loa'){
                    $tipo_operacao = 'Logout Aceito';
                }else if($conteudo['tipo_operacao'] == 'rel'){
                    $tipo_operacao = 'Relatório';
                }else if($conteudo['tipo_operacao'] == 'email'){
                    $tipo_operacao = 'Envio de E-mail           ';
                }

                $tb_alterada = $conteudo['tb_alterada'];
                $id_tb_alterada = $conteudo['id_tb_alterada'];
                $dados_tb_alterada = $conteudo['dados_tb_alterada'];
                $data = converteDataHora($conteudo['data']);
                $ip_origem = $conteudo['ip_origem'];

                $sistema = $conteudo['sistema'];
                if($conteudo['sistema'] == 'simples'){
                    $sistema = 'Simples V2';
                }else if($conteudo['sistema'] == 'painel'){
                    $sistema = 'Painel do Cliente';
                }else if($conteudo['sistema'] == 'painel_rh'){
                    $sistema = 'Painel RH';
                }
            
                echo "<tr>";    
                    echo "<td style='vertical-align: middle;'>".$id_log."</td>";
                    echo "<td style='vertical-align: middle;'>".$usuario."</td>";
                    echo "<td style='vertical-align: middle;'>".$operacao."</td>";
                    echo "<td style='vertical-align: middle;'>".$tipo_operacao."</td>";
                    echo "<td style='vertical-align: middle;'>".$tb_alterada."</td>";
                    echo "<td style='vertical-align: middle;'>".$id_tb_alterada."</td>";
                    echo "<td style='vertical-align: middle;'>".nl2br($dados_tb_alterada)."</td>";
                    echo "<td style='vertical-align: middle;'>".$data."</td>";
                    echo "<td style='vertical-align: middle;'>".$ip_origem."</td>";
                    echo "<td style='vertical-align: middle;'>".$sistema."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";
    echo "</div>";

    echo "<script>
            $(document).ready(function(){
                var table = $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },
                    columnDefs: [
                        { type: 'chinese-string', targets: 0 },
                    ],				        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });
            });
        </script>			
        ";
}

?>