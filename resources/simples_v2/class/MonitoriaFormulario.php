<?php
require_once(__DIR__."/System.php");


$ano = (!empty($_POST['ano'])) ? $_POST['ano'] : '';
$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : '';
$tipo_monitoria = (!empty($_POST['tipo_monitoria'])) ? $_POST['tipo_monitoria'] : '';
$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : '';
$quesitos = (!empty($_POST['quesitos'])) ? $_POST['quesitos'] : '';
$posicao = (!empty($_POST['posicao'])) ? $_POST['posicao'] : '';
$pontos = (!empty($_POST['pontos'])) ? $_POST['pontos'] : '';
$pontos_tirar = (!empty($_POST['pontos_tirar'])) ? $_POST['pontos_tirar'] : '';
//$total_pontos = (!empty($_POST['total_pontos'])) ? $_POST['total_pontos'] : '';
//$total_pontos_tirar = (!empty($_POST['total_pontos_tirar'])) ? $_POST['total_pontos_tirar'] : '';
$porcentagem = (!empty($_POST['porcentagem'])) ? $_POST['porcentagem'] : '';

$soma_total_pontos_quesitos = 0;
foreach ($pontos as $key => $conteudo) {
    $soma_total_pontos_quesitos += $conteudo;
}

$total_pontos = $soma_total_pontos_quesitos;

$qtd_audios_monitoria_meio_turno_sn = (!empty($_POST['qtd_audios_monitoria_meio_turno_sn'])) ? $_POST['qtd_audios_monitoria_meio_turno_sn'] : '0';
$qtd_audios_monitoria_meio_turno_n1 = (!empty($_POST['qtd_audios_monitoria_meio_turno_n1'])) ? $_POST['qtd_audios_monitoria_meio_turno_n1'] : '0';
$qtd_audios_monitoria_meio_turno_n2 = (!empty($_POST['qtd_audios_monitoria_meio_turno_n2'])) ? $_POST['qtd_audios_monitoria_meio_turno_n2'] : '0';
$qtd_audios_monitoria_meio_turno_n3 = (!empty($_POST['qtd_audios_monitoria_meio_turno_n3'])) ? $_POST['qtd_audios_monitoria_meio_turno_n3'] : '0';
$qtd_audios_monitoria_meio_turno_n4 = (!empty($_POST['qtd_audios_monitoria_meio_turno_n4'])) ? $_POST['qtd_audios_monitoria_meio_turno_n4'] : '0';
$qtd_audios_monitoria_meio_turno_n5 = (!empty($_POST['qtd_audios_monitoria_meio_turno_n5'])) ? $_POST['qtd_audios_monitoria_meio_turno_n5'] : '0';

$qtd_audios_monitoria_integral_sn = (!empty($_POST['qtd_audios_monitoria_integral_sn'])) ? $_POST['qtd_audios_monitoria_integral_sn'] : '0';
$qtd_audios_monitoria_integral_n1 = (!empty($_POST['qtd_audios_monitoria_integral_n1'])) ? $_POST['qtd_audios_monitoria_integral_n1'] : '0';
$qtd_audios_monitoria_integral_n2 = (!empty($_POST['qtd_audios_monitoria_integral_n2'])) ? $_POST['qtd_audios_monitoria_integral_n2'] : '0';
$qtd_audios_monitoria_integral_n3 = (!empty($_POST['qtd_audios_monitoria_integral_n3'])) ? $_POST['qtd_audios_monitoria_integral_n3'] : '0';
$qtd_audios_monitoria_integral_n4 = (!empty($_POST['qtd_audios_monitoria_integral_n4'])) ? $_POST['qtd_audios_monitoria_integral_n4'] : '0';
$qtd_audios_monitoria_integral_n5 = (!empty($_POST['qtd_audios_monitoria_integral_n5'])) ? $_POST['qtd_audios_monitoria_integral_n5'] : '0';

$qtd_texto_monitoria_integral = (!empty($_POST['qtd_texto_monitoria_integral'])) ? $_POST['qtd_texto_monitoria_integral'] : '0';

$qtd_texto_monitoria_meio_turno = (!empty($_POST['qtd_texto_monitoria_meio_turno'])) ? $_POST['qtd_texto_monitoria_meio_turno'] : '0';

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$data_referencia = "01/".$mes."/".$ano;
$data_referencia =  convertedata($data_referencia);

if(!empty($_POST['inserir'])) {

    $verifica = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$data_referencia' AND status = 1 AND tipo_monitoria = $tipo_monitoria AND classificacao_atendente = $classificacao");

    if($verifica){
        
        $alert = ('Já existe um formulário de monitoria cadastrado com este mês de referência, tipo de monitoria e classificação de atendente!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-form");
        exit; 

    }else{

        inserir($data_referencia, $quesitos, $posicao, $pontos, $pontos_tirar, $total_pontos,$qtd_audios_monitoria_meio_turno_sn, $qtd_audios_monitoria_meio_turno_n1, $qtd_audios_monitoria_meio_turno_n2, $qtd_audios_monitoria_meio_turno_n3, $qtd_audios_monitoria_meio_turno_n4, $qtd_audios_monitoria_meio_turno_n5,
        $qtd_audios_monitoria_integral_sn, $qtd_audios_monitoria_integral_n1, $qtd_audios_monitoria_integral_n2, $qtd_audios_monitoria_integral_n3, $qtd_audios_monitoria_integral_n4, $qtd_audios_monitoria_integral_n5, $porcentagem, $tipo_monitoria, $classificacao, $qtd_texto_monitoria_meio_turno, $qtd_texto_monitoria_integral);
    }

}else if(!empty($_POST['alterar'])) {

    $id = (int)$_POST['alterar'];
    
    $verifica = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$data_referencia' AND id_monitoria_mes != '$id' AND status = 1 AND tipo_monitoria = $tipo_monitoria AND classificacao_atendente = $classificacao");

    $verifica_quesitos = DBRead('', 'tb_monitoria_mes_quesito', "WHERE id_monitoria_mes = '$id' ", 'id_monitoria_mes_quesito');

    foreach($verifica_quesitos as $conteudo){
        $check = DBRead('', 'tb_monitoria_avaliacao_audio_mes', "WHERE id_monitoria_mes_quesito = '".$conteudo['id_monitoria_mes_quesito']."' ");

        if($check){
            $alert = ('Formulário já utilizado para avaliação, nao é possível altera-lo mais!','d');
            header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
            exit;
        }
    }

    if($verifica){
        
        $alert = ('Já existe um formulário de monitoria cadastrado com este mês de referência, tipo de monitoria e classificação de atendente!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-form");
        exit; 

    }else{
        
        alterar($id, $data_referencia, $quesitos, $posicao, $pontos, $pontos_tirar, $total_pontos,$qtd_audios_monitoria_meio_turno_sn, $qtd_audios_monitoria_meio_turno_n1, $qtd_audios_monitoria_meio_turno_n2, $qtd_audios_monitoria_meio_turno_n3, $qtd_audios_monitoria_meio_turno_n4, $qtd_audios_monitoria_meio_turno_n5,
        $qtd_audios_monitoria_integral_sn, $qtd_audios_monitoria_integral_n1, $qtd_audios_monitoria_integral_n2, $qtd_audios_monitoria_integral_n3, $qtd_audios_monitoria_integral_n4, $qtd_audios_monitoria_integral_n5, $porcentagem, $tipo_monitoria, $classificacao, $qtd_texto_monitoria_meio_turno, $qtd_texto_monitoria_integral);
    }

}else if(!empty($_POST['clonar'])) {

    $id = (int)$_POST['clonar'];

    $verifica = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$data_referencia' AND status = 1 AND tipo_monitoria = $tipo_monitoria AND classificacao_atendente = $classificacao");

    if($verifica){
        
        $alert = ('Já existe um formulário de monitoria cadastrado com este mês de referência, tipo de monitoria e classificação de atendente!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-form&clonar=$id");
        exit; 

    }else{

        inserir($data_referencia, $quesitos, $posicao, $pontos, $pontos_tirar, $total_pontos,$qtd_audios_monitoria_meio_turno_sn, $qtd_audios_monitoria_meio_turno_n1, $qtd_audios_monitoria_meio_turno_n2, $qtd_audios_monitoria_meio_turno_n3, $qtd_audios_monitoria_meio_turno_n4, $qtd_audios_monitoria_meio_turno_n5,
        $qtd_audios_monitoria_integral_sn, $qtd_audios_monitoria_integral_n1, $qtd_audios_monitoria_integral_n2, $qtd_audios_monitoria_integral_n3, $qtd_audios_monitoria_integral_n4, $qtd_audios_monitoria_integral_n5, $porcentagem, $tipo_monitoria, $classificacao, $qtd_texto_monitoria_meio_turno, $qtd_texto_monitoria_integral);
    }

}else if(isset($_GET['excluir'])) {
    
    $id = (int) $_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($data_referencia, $quesitos, $posicao, $pontos, $pontos_tirar, $total_pontos,$qtd_audios_monitoria_meio_turno_sn, $qtd_audios_monitoria_meio_turno_n1, $qtd_audios_monitoria_meio_turno_n2,$qtd_audios_monitoria_meio_turno_n3, $qtd_audios_monitoria_meio_turno_n4, $qtd_audios_monitoria_meio_turno_n5,
$qtd_audios_monitoria_integral_sn, $qtd_audios_monitoria_integral_n1, $qtd_audios_monitoria_integral_n2, $qtd_audios_monitoria_integral_n3, $qtd_audios_monitoria_integral_n4, $qtd_audios_monitoria_integral_n5, $porcentagem, $tipo_monitoria, $classificacao, $qtd_texto_monitoria_meio_turno, $qtd_texto_monitoria_integral){

    if($data_referencia !='' &&  $quesitos !='' && $posicao !='' && $pontos !='' &&  $pontos_tirar !='' && $total_pontos != 0 && $porcentagem !='' && $tipo_monitoria !='' && $classificacao !=''){

        $link = DBConnect('');
        DBBegin($link);

        $dados = array(
            'data_referencia' =>$data_referencia,
            'soma_total_pontos_quesitos' => $total_pontos,
            'qtd_audios_monitoria_meio_turno_sn' => $qtd_audios_monitoria_meio_turno_sn, 
            'qtd_audios_monitoria_meio_turno_n1' => $qtd_audios_monitoria_meio_turno_n1, 'qtd_audios_monitoria_meio_turno_n2' => $qtd_audios_monitoria_meio_turno_n2, 
            'qtd_audios_monitoria_meio_turno_n3' => $qtd_audios_monitoria_meio_turno_n3, 'qtd_audios_monitoria_meio_turno_n4' => $qtd_audios_monitoria_meio_turno_n4, 'qtd_audios_monitoria_meio_turno_n5' => $qtd_audios_monitoria_meio_turno_n5,
            'qtd_audios_monitoria_integral_sn' => $qtd_audios_monitoria_integral_sn, 'qtd_audios_monitoria_integral_n1' => $qtd_audios_monitoria_integral_n1, 'qtd_audios_monitoria_integral_n2' => $qtd_audios_monitoria_integral_n2, 'qtd_audios_monitoria_integral_n3' => $qtd_audios_monitoria_integral_n3, 'qtd_audios_monitoria_integral_n4' =>$qtd_audios_monitoria_integral_n4, 'qtd_audios_monitoria_integral_n5' => $qtd_audios_monitoria_integral_n5,
            'tipo_monitoria' => $tipo_monitoria,
            'classificacao_atendente' => $classificacao,
            'qtd_texto_monitoria_meio_turno' => $qtd_texto_monitoria_meio_turno,
            'qtd_texto_monitoria_integral' => $qtd_texto_monitoria_integral
        );

        $id_monitoria_mes = DBCreateTransaction($link, 'tb_monitoria_mes', $dados, true);
        registraLogTransaction($link,'Inserção de monitoria mes.','i','tb_monitoria_mes',$id_monitoria_mes,"data_referencia: $data_referencia | soma_total_pontos_quesitos: $total_pontos | qtd_audios_monitoria_meio_turno_sn: $qtd_audios_monitoria_meio_turno_sn | qtd_audios_monitoria_meio_turno_n1: $qtd_audios_monitoria_meio_turno_n1 |qtd_audios_monitoria_meio_turno_n2: $qtd_audios_monitoria_meio_turno_n2 |qtd_audios_monitoria_meio_turno_n3: $qtd_audios_monitoria_meio_turno_n3 |qtd_audios_monitoria_meio_turno_n4: $qtd_audios_monitoria_meio_turno_n4 |qtd_audios_monitoria_meio_turno_n5: $qtd_audios_monitoria_meio_turno_n5 | 
        qtd_audios_monitoria_integral_sn: $qtd_audios_monitoria_integral_sn | qtd_audios_monitoria_integral_n1: $qtd_audios_monitoria_integral_n1 | qtd_audios_monitoria_integral_n2: $qtd_audios_monitoria_integral_n2 |qtd_audios_monitoria_integral_n3: $qtd_audios_monitoria_integral_n3 |qtd_audios_monitoria_integral_n4: $qtd_audios_monitoria_integral_n4 |qtd_audios_monitoria_integral_n5: $qtd_audios_monitoria_integral_n5 | tipo_monitoria: $tipo_monitoria | classificacao_atendente: $classificacao | qtd_texto_monitoria_meio_turno: $qtd_texto_monitoria_meio_turno | qtd_texto_monitoria_integral: $qtd_texto_monitoria_integral");

        $tam = sizeof($quesitos);

        for($i=0; $i < $tam; $i++){

            $id_monitoria_quesito = $quesitos[$i];
            $pontos_valor = $pontos[$i];
            $pontos_valor_tirar = $pontos_tirar[$i];
            $posicao_quesito = $posicao[$i];
            $porcentagem_plano_acao = $porcentagem[$i];

            $dados = array(
                'id_monitoria_quesito' => $id_monitoria_quesito,
                'id_monitoria_mes' => $id_monitoria_mes,
                'pontos_valor' => $pontos_valor,
                'pontos_tirar' => $pontos_valor_tirar,
                'posicao' => $posicao_quesito,
                'porcentagem_plano_acao' =>  $porcentagem_plano_acao
            );
            
            $InsertID = DBCreateTransaction($link, 'tb_monitoria_mes_quesito', $dados, true);
            registraLogTransaction($link,'Inserção de monitoria mes quesito.','i','tb_monitoria_mes_quesito',$InsertID,"id_monitoria_quesito: $id_monitoria_quesito | id_monitoria_mes: $id_monitoria_mes | pontos_valor: $pontos_valor | pontos_tirar: $pontos_valor_tirar | posicao: $posicao_quesito | porcentagem_plano_acao: $porcentagem_plano_acao");
        }

        DBCommit($link);
        
        $alert = ('Formulário de monitoria inserido com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
        exit; 
        
    }else{
        
        $alert = ('Não foi possível inserir formulário de monitoria !','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
   	    exit;
    } 
}

function alterar($id, $data_referencia, $quesitos, $posicao, $pontos, $pontos_tirar, $total_pontos,$qtd_audios_monitoria_meio_turno_sn, $qtd_audios_monitoria_meio_turno_n1, $qtd_audios_monitoria_meio_turno_n2,$qtd_audios_monitoria_meio_turno_n3, $qtd_audios_monitoria_meio_turno_n4,$qtd_audios_monitoria_meio_turno_n5, $qtd_audios_monitoria_integral_sn, $qtd_audios_monitoria_integral_n1, $qtd_audios_monitoria_integral_n2, $qtd_audios_monitoria_integral_n3, $qtd_audios_monitoria_integral_n4, $qtd_audios_monitoria_integral_n5, $porcentagem, $tipo_monitoria, $classificacao, $qtd_texto_monitoria_meio_turno, $qtd_texto_monitoria_integral){

    if($id != '' && $data_referencia !='' &&  $quesitos !='' && $posicao !='' && $pontos !='' &&  $pontos_tirar !='' && $total_pontos != 0 && $porcentagem !='' && $tipo_monitoria !='' && $classificacao !=''){

        $link = DBConnect('');
        DBBegin($link);
        $dados = array(
            'data_referencia' =>$data_referencia,
            'soma_total_pontos_quesitos' => $total_pontos,
            'qtd_audios_monitoria_meio_turno_sn' => $qtd_audios_monitoria_meio_turno_sn, 
            'qtd_audios_monitoria_meio_turno_n1' => $qtd_audios_monitoria_meio_turno_n1, 'qtd_audios_monitoria_meio_turno_n2' => $qtd_audios_monitoria_meio_turno_n2, 
            'qtd_audios_monitoria_meio_turno_n3' => $qtd_audios_monitoria_meio_turno_n3, 'qtd_audios_monitoria_meio_turno_n4' => $qtd_audios_monitoria_meio_turno_n4, 'qtd_audios_monitoria_meio_turno_n5' => $qtd_audios_monitoria_meio_turno_n5,
            'qtd_audios_monitoria_integral_sn' => $qtd_audios_monitoria_integral_sn, 'qtd_audios_monitoria_integral_n1' => $qtd_audios_monitoria_integral_n1, 'qtd_audios_monitoria_integral_n2' => $qtd_audios_monitoria_integral_n2, 'qtd_audios_monitoria_integral_n3' => $qtd_audios_monitoria_integral_n3, 'qtd_audios_monitoria_integral_n4' =>$qtd_audios_monitoria_integral_n4, 'qtd_audios_monitoria_integral_n5' => $qtd_audios_monitoria_integral_n5,
            'tipo_monitoria' => $tipo_monitoria,
            'classificacao_atendente' => $classificacao,
            'qtd_texto_monitoria_meio_turno' => $qtd_texto_monitoria_meio_turno,
            'qtd_texto_monitoria_integral' => $qtd_texto_monitoria_integral

        );

        DBUpdateTransaction($link,'tb_monitoria_mes', $dados, "id_monitoria_mes = '$id' ");
        registraLogTransaction($link,'Alteração de monitoria mes.','a','tb_monitoria_mes',$id,"data_referencia: $data_referencia | soma_total_pontos_quesitos: $total_pontos | qtd_audios_monitoria_meio_turno_sn: $qtd_audios_monitoria_meio_turno_sn | qtd_audios_monitoria_meio_turno_n1: $qtd_audios_monitoria_meio_turno_n1 |qtd_audios_monitoria_meio_turno_n2: $qtd_audios_monitoria_meio_turno_n2 |qtd_audios_monitoria_meio_turno_n3: $qtd_audios_monitoria_meio_turno_n3 |qtd_audios_monitoria_meio_turno_n4: $qtd_audios_monitoria_meio_turno_n4 |qtd_audios_monitoria_meio_turno_n5: $qtd_audios_monitoria_meio_turno_n5 | 
        qtd_audios_monitoria_integral_sn: $qtd_audios_monitoria_integral_sn | qtd_audios_monitoria_integral_n1: $qtd_audios_monitoria_integral_n1 | qtd_audios_monitoria_integral_n2: $qtd_audios_monitoria_integral_n2 |qtd_audios_monitoria_integral_n3: $qtd_audios_monitoria_integral_n3 |qtd_audios_monitoria_integral_n4: $qtd_audios_monitoria_integral_n4 |qtd_audios_monitoria_integral_n5: $qtd_audios_monitoria_integral_n5 | tipo_monitoria: $tipo_monitoria | classificacao_atendente: $classificacao | qtd_texto_monitoria_meio_turno: $qtd_texto_monitoria_meio_turno | qtd_texto_monitoria_integral: $qtd_texto_monitoria_integral");
        
        DBDeleteTransaction($link, 'tb_monitoria_mes_quesito', "id_monitoria_mes = '$id' ");

        $tam = sizeof($quesitos);

        for($i=0; $i < $tam; $i++){

            $id_monitoria_quesito = $quesitos[$i];
            $pontos_valor = $pontos[$i];
            $pontos_valor_tirar = $pontos_tirar[$i];
            $posicao_quesito = $posicao[$i];
            $porcentagem_plano_acao = $porcentagem[$i];

            $dados = array(
                'id_monitoria_quesito' => $id_monitoria_quesito,
                'id_monitoria_mes' => $id,
                'pontos_valor' => $pontos_valor,
                'pontos_tirar' => $pontos_valor_tirar,
                'posicao' => $posicao_quesito,
                'porcentagem_plano_acao' =>  $porcentagem_plano_acao
            );

            $InsertID = DBCreateTransaction($link, 'tb_monitoria_mes_quesito', $dados, true);
            registraLogTransaction($link,'Inserção de monitoria mes quesito.','i','tb_monitoria_mes_quesito',$InsertID,"id_monitoria_quesito: $id_monitoria_quesito | id_monitoria_mes: $id | pontos_valor: $pontos_valor | pontos_tirar: $pontos_valor_tirar | posicao: $posicao_quesito | porcentagem_plano_acao: $porcentagem_plano_acao");
        }

        DBCommit($link);

        $alert = ('Formulário da monitoria alterado com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
        exit;

    }else{

        $alert = ('Não foi possível alterar o formulário monitoria mês!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
   	    exit;
    }
}

function excluir($id){
    
    if($id !=''){
        
        $dados = array(
            'status' => '2'
        );
        
        DBUpdate('', 'tb_monitoria_mes', $dados, "id_monitoria_mes = '$id'");
        registraLog('Exclusão de formulário da monitoria.','e','tb_monitoria_mes', $id,"status: 2");

        $alert = ('Formulário da monitoria excluído com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
        exit;

    }else{

        $alert = ('Não foi possível excluir o formulário!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-formulario-busca");
   	    exit;
    }
}