<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '';
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 1;
$lider_direto = (!empty($_POST['lider_direto'])) ? $_POST['lider_direto'] : 0;
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;

if($tipo != '2'){
    $lider_direto = 0;
}

$tempo_medio_atendimento = (!empty($_POST['tempo_medio_atendimento'])) ? $_POST['tempo_medio_atendimento'] : 0;
$status_tempo_medio_atendimento = (!empty($_POST['status_tempo_medio_atendimento'])) ? $_POST['status_tempo_medio_atendimento'] : 2;
$nota_media = (!empty($_POST['nota_media'])) ? $_POST['nota_media'] : 0;
$status_nota_media = (!empty($_POST['status_nota_media'])) ? $_POST['status_nota_media'] : 2;
$porcentagem_ligacao_nota = (!empty($_POST['porcentagem_ligacao_nota'])) ? $_POST['porcentagem_ligacao_nota'] : 0;
$status_porcentagem_ligacao_nota = (!empty($_POST['status_porcentagem_ligacao_nota'])) ? $_POST['status_porcentagem_ligacao_nota'] : 2;
$erros_reclamacoes = (!empty($_POST['erros_reclamacoes'])) ? $_POST['erros_reclamacoes'] : 0;
$status_erros_reclamacoes = (!empty($_POST['status_erros_reclamacoes'])) ? $_POST['status_erros_reclamacoes'] : 2;
$faltas_justificadas = (!empty($_POST['faltas_justificadas'])) ? $_POST['faltas_justificadas'] : 0;
$status_faltas_justificadas = (!empty($_POST['status_faltas_justificadas'])) ? $_POST['status_faltas_justificadas'] : 2;
$absenteismo = (!empty($_POST['absenteismo'])) ? $_POST['absenteismo'] : 0.00;
$status_absenteismo = (!empty($_POST['status_absenteismo'])) ? $_POST['status_absenteismo'] : 2;
$pausa_registro = (!empty($_POST['pausa_registro'])) ? $_POST['pausa_registro'] : 0;
$status_pausa_registro = (!empty($_POST['status_pausa_registro'])) ? $_POST['status_pausa_registro'] : 2;
$monitoria = (!empty($_POST['monitoria'])) ? $_POST['monitoria'] : 0;
$status_monitoria = (!empty($_POST['status_monitoria'])) ? $_POST['status_monitoria'] : 2;
$resolucao = (!empty($_POST['resolucao'])) ? $_POST['resolucao'] : 0;
$status_resolucao = (!empty($_POST['status_resolucao'])) ? $_POST['status_resolucao'] : 2;
$atendimentos_hora = (!empty($_POST['atendimentos_hora'])) ? $_POST['atendimentos_hora'] : 0;
$status_atendimentos_hora = (!empty($_POST['status_atendimentos_hora'])) ? $_POST['status_atendimentos_hora'] : 2;





$total_atendimentos = (!empty($_POST['total_atendimentos'])) ? $_POST['total_atendimentos'] : 0;
$status_total_atendimentos = (!empty($_POST['status_total_atendimentos'])) ? $_POST['status_total_atendimentos'] : 2;
$qtd_ajudas = (!empty($_POST['qtd_ajudas'])) ? $_POST['qtd_ajudas'] : 0;
$status_qtd_ajudas = (!empty($_POST['status_qtd_ajudas'])) ? $_POST['status_qtd_ajudas'] : 2;
$total_atendimentos_meio_turno = (!empty($_POST['total_atendimentos_meio_turno'])) ? $_POST['total_atendimentos_meio_turno'] : 0;
$status_total_atendimentos_meio_turno = (!empty($_POST['status_total_atendimentos_meio_turno'])) ? $_POST['status_total_atendimentos_meio_turno'] : 2;
$qtd_ajudas_meio_turno = (!empty($_POST['qtd_ajudas_meio_turno'])) ? $_POST['qtd_ajudas_meio_turno'] : 0;
$status_qtd_ajudas_meio_turno = (!empty($_POST['status_qtd_ajudas_meio_turno'])) ? $_POST['status_qtd_ajudas_meio_turno'] : 2;

//Se diferente de individual não considera essa parte

if($tipo != 1){
    
    $qtd_ajudas = 0;
    $status_qtd_ajudas = 2;
    $total_atendimentos_meio_turno = 0;
    $status_total_atendimentos_meio_turno = 2;
    $qtd_ajudas_meio_turno = 0;
    $status_qtd_ajudas_meio_turno = 2;
}


if(!empty($_POST['inserir'])){
    
    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);
    
    if($tipo == 2){
        $dados = DBRead('', 'tb_meta', "WHERE nome = '$nome' AND status != '2' AND tipo = '".$tipo."' AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."')) AND lider_direto = '".$lider_direto."'");
    }else{
        $dados = DBRead('', 'tb_meta', "WHERE nome = '$nome' AND status != '2' AND tipo = '".$tipo."' AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."'))");
    }

    if(!$dados){

        inserir($nome, $tempo_medio_atendimento, $nota_media, $porcentagem_ligacao_nota, $erros_reclamacoes, $tipo, $status, $data_de, $data_ate, $absenteismo, $faltas_justificadas, $status_tempo_medio_atendimento, $status_nota_media, $status_porcentagem_ligacao_nota, $status_erros_reclamacoes, $status_faltas_justificadas, $status_absenteismo, $status_pausa_registro, $pausa_registro, $lider_direto, $monitoria, $status_monitoria, $resolucao, $status_resolucao, $atendimentos_hora, $status_atendimentos_hora, $total_atendimentos, $status_total_atendimentos, $qtd_ajudas, $status_qtd_ajudas, $total_atendimentos_meio_turno, $status_total_atendimentos_meio_turno, $qtd_ajudas_meio_turno, $status_qtd_ajudas_meio_turno);

    }else{
        
        $alert = ('Erro ao inserir item! Conflito de datas.','d');
        header("location: /api/iframe?token=$request->token&view=metas-form");
        exit;
    }
    
}else if(!empty($_POST['alterar'])){
    
    $id = (int)$_POST['alterar'];
    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    if($tipo == 2){
        $dados = DBRead('', 'tb_meta', "WHERE id_meta != ".$id." AND nome = '$nome' AND status != '2'  AND tipo = '".$tipo."' AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_ate."' AND data_ate <= '".$data_ate."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_ate."')) AND lider_direto = '".$lider_direto."'");
    }else{
        $dados = DBRead('', 'tb_meta', "WHERE id_meta != ".$id." AND nome = '$nome' AND status != '2'  AND tipo = '".$tipo."' AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_ate."' AND data_ate <= '".$data_ate."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_ate."'))");
    }
   
    if(!$dados){

        alterar($id, $nome, $tempo_medio_atendimento, $nota_media, $porcentagem_ligacao_nota, $erros_reclamacoes, $tipo, $status, $data_de, $data_ate, $absenteismo, $faltas_justificadas, $status_tempo_medio_atendimento, $status_nota_media, $status_porcentagem_ligacao_nota, $status_erros_reclamacoes, $status_faltas_justificadas, $status_absenteismo, $status_pausa_registro, $pausa_registro, $lider_direto, $monitoria, $status_monitoria, $resolucao, $status_resolucao, $atendimentos_hora, $status_atendimentos_hora, $total_atendimentos, $status_total_atendimentos, $qtd_ajudas, $status_qtd_ajudas, $total_atendimentos_meio_turno, $status_total_atendimentos_meio_turno, $qtd_ajudas_meio_turno, $status_qtd_ajudas_meio_turno);

    }else{

        $alert = ('Erro ao alterar item!','d');
        header("location: /api/iframe?token=$request->token&view=metas-form&alterar=$id");
        exit;
    }

}else if(!empty($_POST['clonar'])){
    
    $id = (int)$_POST['clonar'];
    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    if($tipo == 2){
        $dados = DBRead('', 'tb_meta', "WHERE nome = '$nome' AND status != '2' AND tipo = '".$tipo."' AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."')) AND lider_direto = '".$lider_direto."'");
    }else{
        $dados = DBRead('', 'tb_meta', "WHERE nome = '$nome' AND status != '2' AND tipo = '".$tipo."' AND ((data_de <= '".$data_de."' AND data_ate >= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."') OR (data_de >= '".$data_de."' AND data_ate <= '".$data_de."'))");
    }

    if(!$dados){
        inserir($nome, $tempo_medio_atendimento, $nota_media, $porcentagem_ligacao_nota, $erros_reclamacoes, $tipo, $status, $data_de, $data_ate, $absenteismo, $faltas_justificadas, $status_tempo_medio_atendimento, $status_nota_media, $status_porcentagem_ligacao_nota, $status_erros_reclamacoes, $status_faltas_justificadas, $status_absenteismo, $status_pausa_registro, $pausa_registro, $lider_direto, $monitoria, $status_monitoria, $resolucao, $status_resolucao, $atendimentos_hora, $status_atendimentos_hora, $total_atendimentos, $status_total_atendimentos, $qtd_ajudas, $status_qtd_ajudas, $total_atendimentos_meio_turno, $status_total_atendimentos_meio_turno, $qtd_ajudas_meio_turno, $status_qtd_ajudas_meio_turno);
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=metas-form&clonar=$id");
        exit;
    }    

} else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $tempo_medio_atendimento, $nota_media, $porcentagem_ligacao_nota, $erros_reclamacoes, $tipo, $status, $data_de, $data_ate, $absenteismo, $faltas_justificadas, $status_tempo_medio_atendimento, $status_nota_media, $status_porcentagem_ligacao_nota, $status_erros_reclamacoes, $status_faltas_justificadas, $status_absenteismo, $status_pausa_registro, $pausa_registro, $lider_direto, $monitoria, $status_monitoria, $resolucao, $status_resolucao, $atendimentos_hora, $status_atendimentos_hora, $total_atendimentos, $status_total_atendimentos, $qtd_ajudas, $status_qtd_ajudas, $total_atendimentos_meio_turno, $status_total_atendimentos_meio_turno, $qtd_ajudas_meio_turno, $status_qtd_ajudas_meio_turno){    

    $dados = array(
        'nome' => $nome,
        'tempo_medio_atendimento' => $tempo_medio_atendimento,
        'nota_media' => $nota_media,
        'porcentagem_ligacao_nota' => $porcentagem_ligacao_nota,
        'erros_reclamacoes' => $erros_reclamacoes,
        'data_de' => $data_de,
        'data_ate' => $data_ate,
        'tipo' => $tipo,
        'status' => $status,
        'absenteismo' => $absenteismo,
        'faltas_justificadas' => $faltas_justificadas,
        'status_tempo_medio_atendimento' => $status_tempo_medio_atendimento,
        'status_nota_media' => $status_nota_media,
        'status_porcentagem_ligacao_nota' => $status_porcentagem_ligacao_nota,
        'status_erros_reclamacoes' => $status_erros_reclamacoes,
        'status_faltas_justificadas' => $status_faltas_justificadas,
        'status_absenteismo' => $status_absenteismo,
        'status_pausa_registro' => $status_pausa_registro,
        'pausa_registro' => $pausa_registro,
        'lider_direto' => $lider_direto,
        'monitoria' => $monitoria,
        'status_monitoria' => $status_monitoria,
        'resolucao' => $resolucao,
        'status_resolucao' => $status_resolucao,
        'atendimentos_hora' => $atendimentos_hora,
        'status_atendimentos_hora' => $status_atendimentos_hora,



        'total_atendimentos' => $total_atendimentos,
        'status_total_atendimentos' => $status_total_atendimentos,
        'qtd_ajudas' => $qtd_ajudas,
        'status_qtd_ajudas' => $status_qtd_ajudas,
        'total_atendimentos_meio_turno' => $total_atendimentos_meio_turno,
        'status_total_atendimentos_meio_turno' => $status_total_atendimentos_meio_turno,
        'qtd_ajudas_meio_turno' => $qtd_ajudas_meio_turno,
        'status_qtd_ajudas_meio_turno' => $status_qtd_ajudas_meio_turno
    );

    $isertID = DBCreate('', 'tb_meta', $dados, true);

    registraLog('Inserção de Metas.','i','tb_metas_individuais',$isertID,"nome: $nome | tempo_medio_atendimento: $tempo_medio_atendimento | nota_media: $nota_media | porcentagem_ligacao_nota: $porcentagem_ligacao_nota | erros_reclamacoes: $erros_reclamacoes | data_de: $data_de | data_ate: $data_ate | status: $status | tipo: $tipo | absenteismo: $absenteismo | faltas_justificadas: $faltas_justificadas | status_tempo_medio_atendimento: $status_tempo_medio_atendimento | status_nota_media: $status_nota_media | status_porcentagem_ligacao_nota: $status_porcentagem_ligacao_nota | status_erros_reclamacoes: $status_erros_reclamacoes | status_faltas_justificadas: $status_faltas_justificadas | status_absenteismo: $status_absenteismo | status_pausa_registro: $status_pausa_registro | pausa_registro: $pausa_registro | monitoria: $monitoria | status_monitoria: $status_monitoria | resolucao: $resolucao | status_resolucao: $status_resolucao | atendimentos_hora: $atendimentos_hora | status_atendimentos_hora: $status_atendimentos_hora | lider_direto: $lider_direto | total_atendimentos: $total_atendimentos | status_total_atendimentos: $status_total_atendimentos | qtd_ajudas: $qtd_ajudas | status_qtd_ajudas: $status_qtd_ajudas | total_atendimentos_meio_turno: $total_atendimentos_meio_turno | status_total_atendimentos_meio_turno: $status_total_atendimentos_meio_turno | qtd_ajudas_meio_turno: $qtd_ajudas_meio_turno | status_qtd_ajudas_meio_turno: $status_qtd_ajudas_meio_turno");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=metas-busca");
    exit;
}

function alterar($id, $nome, $tempo_medio_atendimento, $nota_media, $porcentagem_ligacao_nota, $erros_reclamacoes, $tipo, $status, $data_de, $data_ate, $absenteismo, $faltas_justificadas, $status_tempo_medio_atendimento, $status_nota_media, $status_porcentagem_ligacao_nota, $status_erros_reclamacoes, $status_faltas_justificadas, $status_absenteismo, $status_pausa_registro, $pausa_registro, $lider_direto, $monitoria, $status_monitoria, $resolucao, $status_resolucao, $atendimentos_hora, $status_atendimentos_hora, $total_atendimentos, $status_total_atendimentos, $qtd_ajudas, $status_qtd_ajudas, $total_atendimentos_meio_turno, $status_total_atendimentos_meio_turno, $qtd_ajudas_meio_turno, $status_qtd_ajudas_meio_turno){

    $dados = array(
        'nome' => $nome,
        'tempo_medio_atendimento' => $tempo_medio_atendimento,
        'nota_media' => $nota_media,
        'porcentagem_ligacao_nota' => $porcentagem_ligacao_nota,
        'erros_reclamacoes' => $erros_reclamacoes,
        'data_de' => $data_de,
        'data_ate' => $data_ate,
        'tipo' => $tipo,
        'status' => $status,
        'absenteismo' => $absenteismo,
        'faltas_justificadas' => $faltas_justificadas,
        'status_tempo_medio_atendimento' => $status_tempo_medio_atendimento,
        'status_nota_media' => $status_nota_media,
        'status_porcentagem_ligacao_nota' => $status_porcentagem_ligacao_nota,
        'status_erros_reclamacoes' => $status_erros_reclamacoes,
        'status_faltas_justificadas' => $status_faltas_justificadas,
        'status_absenteismo' => $status_absenteismo,
        'status_pausa_registro' => $status_pausa_registro,
        'pausa_registro' => $pausa_registro,
        'lider_direto' => $lider_direto,
        'monitoria' => $monitoria,
        'status_monitoria' => $status_monitoria,
        'resolucao' => $resolucao,
        'status_resolucao' => $status_resolucao,
        'atendimentos_hora' => $atendimentos_hora,
        'status_atendimentos_hora' => $status_atendimentos_hora,



        'total_atendimentos' => $total_atendimentos,
        'status_total_atendimentos' => $status_total_atendimentos,
        'qtd_ajudas' => $qtd_ajudas,
        'status_qtd_ajudas' => $status_qtd_ajudas,
        'total_atendimentos_meio_turno' => $total_atendimentos_meio_turno,
        'status_total_atendimentos_meio_turno' => $status_total_atendimentos_meio_turno,
        'qtd_ajudas_meio_turno' => $qtd_ajudas_meio_turno,
        'status_qtd_ajudas_meio_turno' => $status_qtd_ajudas_meio_turno
    );

    DBUpdate('', 'tb_meta', $dados, "id_meta = '$id'");

    registraLog('Alteração de metas.','a','tb_meta',$id,"nome: $nome | tempo_medio_atendimento: $tempo_medio_atendimento | nota_media: $nota_media | porcentagem_ligacao_nota: $porcentagem_ligacao_nota | erros_reclamacoes: $erros_reclamacoes | status: $status | tipo: $tipo | absenteismo: $absenteismo | faltas_justificadas: $faltas_justificadas | status_tempo_medio_atendimento: $status_tempo_medio_atendimento | status_nota_media: $status_nota_media | status_porcentagem_ligacao_nota: $status_porcentagem_ligacao_nota | status_erros_reclamacoes: $status_erros_reclamacoes | status_faltas_justificadas: $status_faltas_justificadas | status_absenteismo: $status_absenteismo |  status_pausa_registro: $status_pausa_registro | pausa_registro: $pausa_registro | monitoria: $monitoria | status_monitoria: $status_monitoria | resolucao: $resolucao | status_resolucao: $status_resolucao | atendimentos_hora: $atendimentos_hora | status_atendimentos_hora: $status_atendimentos_hora | lider_direto: $lider_direto | total_atendimentos: $total_atendimentos | status_total_atendimentos: $status_total_atendimentos | qtd_ajudas: $qtd_ajudas | status_qtd_ajudas: $status_qtd_ajudas | total_atendimentos_meio_turno: $total_atendimentos_meio_turno | status_total_atendimentos_meio_turno: $status_total_atendimentos_meio_turno | qtd_ajudas_meio_turno: $qtd_ajudas_meio_turno | status_qtd_ajudas_meio_turno: $status_qtd_ajudas_meio_turno");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=metas-busca");
    exit;
}

function excluir($id){

    $dados = array(
        'status' => '2'
    );

    DBUpdate('', 'tb_meta', $dados, "id_meta = '$id'");

    registraLog('Exclusão de metas.','a','tb_meta',$id,"id_meta: $id | status: 2");
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=metas-busca");
    exit;
}
?>
