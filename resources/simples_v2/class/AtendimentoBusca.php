<?php
require_once(__DIR__."/System.php");
echo "
<style>
    .popover {
        max-width: 1000px;
    }
</style>";
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$canal_atendimento = addslashes($parametros['canal_atendimento']);
$sistema_gestao = addslashes($parametros['sistema_gestao']);
$sistema_chat = addslashes($parametros['sistema_chat']);
$plano = addslashes($parametros['plano']);

if($canal_atendimento != 'qualquer'){
    $inner_canal_atendimento = "INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa";
    $filtro_canal_atendimento = "AND d.atendimento_via_texto = '".($canal_atendimento == 'texto' ? '1' : '0')."'";
}else{
    $inner_canal_atendimento = "";
    $filtro_canal_atendimento = "";
}

if($sistema_gestao){
    $inner_sistema_gestao = "INNER JOIN tb_sistema_gestao_contrato e ON a.id_contrato_plano_pessoa = e.id_contrato_plano_pessoa";
    $filtro_sistema_gestao = "AND e.id_tipo_sistema_gestao = '$sistema_gestao'";
}else{
    $inner_sistema_gestao = "";
    $filtro_sistema_gestao = "";
}

if($sistema_chat){
    $inner_sistema_chat = "INNER JOIN tb_sistema_chat_contrato f ON a.id_contrato_plano_pessoa = f.id_contrato_plano_pessoa";
    $filtro_sistema_chat = "AND f.id_tipo_sistema_chat = '$sistema_chat'";
}else{
    $inner_sistema_chat = "";
    $filtro_sistema_chat = "";
}

if($plano){
    $filtro_plano = "AND a.id_plano = '$plano'";
}else{
    $filtro_plano = "";
}
$dados_pessoa = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$_SESSION['id_usuario']."' ", "b.nome");

// $dados_responsavel_atendimento_texto = DBRead('', 'tb_responsavel_atendimento',"WHERE status = 1 AND tipo = 1 AND id_usuario = '".$_SESSION['id_usuario']."' GROUP BY id_grupo_atendimento_chat", "id_grupo_atendimento_chat");

// if($dados_responsavel_atendimento_texto){
//     $grupos = '';
//     $class = "<div class='alert alert-warning alert-dismissible' role='alert' style='text-align: center' data-toggle='popover' data-html='true' data-placement='bottom' data-trigger='focus' title='' data-content=\"<i class='fa fa-info-circle' aria-hidden='true'></i> ".$dados_pessoa[0]['nome']." você é responsável por:";

// 	foreach ($dados_responsavel_atendimento_texto as $conteudo_responsavel_atendimento) {
//         $dados_grupo_atendimento_chat = DBRead('', 'tb_grupo_atendimento_chat',"WHERE id_grupo_atendimento_chat = '".$conteudo_responsavel_atendimento['id_grupo_atendimento_chat']."'");

//         foreach ($dados_grupo_atendimento_chat as $conteudo_grupo_atendimento_chat) {

//             if($grupos != ''){
//                 $grupos .= ", ".$conteudo_grupo_atendimento_chat['nome']."";
//             }else{
//                 $grupos .= "".$conteudo_grupo_atendimento_chat['nome']."";
//             }
//             $class .= '<hr><strong>Grupo:</strong> '.$conteudo_grupo_atendimento_chat['nome'].'<br>';
            
//             $dados_grupo_atendimento_chat_contrato = DBRead('', 'tb_grupo_atendimento_chat_contrato a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_grupo_atendimento_chat = '".$conteudo_grupo_atendimento_chat['id_grupo_atendimento_chat']."' ORDER BY c.nome ASC", "c.nome");
            
//             $class .= "<strong>Contrato(s):</strong> ";
//             $empresas = '';
//             foreach ($dados_grupo_atendimento_chat_contrato as $conteudo_grupo_atendimento_chat_contrato) {
//                 $empresas .= ", ".$conteudo_grupo_atendimento_chat_contrato['nome'];
//             }
//             $class .= ''.substr($empresas, 1).'';
//         }

//     }

//     if(sizeof($dados_responsavel_atendimento_texto) > 1){
//         $notificacao = "No momento você é responsável pelos atendimentos via texto dos seguintes grupos: ".$grupos."";
// 	}else{
// 		$notificacao = "No momento você é responsável pelos atendimentos via texto do seguinte grupo: ".$grupos."";
// 	}

//     $class .= '<hr>"><strong>'.$notificacao.'</strong></div>';

//     echo $class;
// }	

$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano $inner_canal_atendimento $inner_sistema_gestao $inner_sistema_chat WHERE c.cod_servico = 'call_suporte' AND (a.status = 1 OR a.status = 7) AND (b.nome LIKE '%$letra%' OR a.nome_contrato LIKE '%$letra%') $filtro_canal_atendimento $filtro_sistema_gestao $filtro_sistema_chat $filtro_plano ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.id_plano, a.id_pessoa, a.nome_contrato, b.nome, c.cor, c.cod_servico, a.status");

if(!$dados){
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if(!$letra){
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
}else{

    echo "<div class='row'>";
    
    foreach($dados as $conteudo){

        if($conteudo['status'] == 7) {
            $cor = 'opacity: 0.3;';
            $popover = "data-toggle='popover' data-html='true' data-placement='bottom' data-trigger='focus' title='' data-content='Em ativação'";
        } else {
            $cor = '';
            $popover = '';
        }
    
        echo "<div class='col-lg-3 col-md-4' style = 'padding-bottom: 15px; $cor' $popover>";
            echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
			    echo '<div class="btn-group" role="group">';
                    echo '<a href="/api/iframe?token=<?php echo $request->token ?>&view=atendimento-inicio-form&contrato='.$conteudo['id_contrato_plano_pessoa'].'" class="btn btn-default" style="border-left: 20px solid '.$conteudo['cor'].'; padding-top: 16px; padding-bottom: 16px; text-shadow: 0px 0px 0px !important; background-image: none !important; background-color: rgb(217, 217, 217) !important; color: rgb(0, 0, 0);">';
                        echo '<span style="font-size: 13px; display: inline;" class="pull-left">'.$conteudo['id_contrato_plano_pessoa'].'</span>';
                        echo '<span style="font-size: 16px;" >';
                        echo $conteudo['nome'];
                        if($conteudo['nome_contrato']){
                            echo " (".$conteudo['nome_contrato'].")";
                        }
                        echo '</span>';
                    echo '</a>';
                echo "</div>";  
            echo "</div>";
        echo "</div>";
    
    }    

    echo "</div>";

}
?>

<script>
    $(function(){
        $('[data-toggle="popover"]').popover({ trigger: "hover", container: "body" });
    });
</script>