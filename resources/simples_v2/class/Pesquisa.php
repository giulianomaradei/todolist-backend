<?php
require_once(__DIR__."/System.php");

//titulo da pesquisa Ex: Pesquisa eleitoral.
$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : '';
/*
   $status
0 - Concluido;
1 - Ativo;
2 - Excluido.
3 - Pausado.
4 - Pausado automaticamente.
5 - Liberado.

*/
$observacao_status = (!empty($_POST['observacao_status'])) ? $_POST['observacao_status'] : '';

$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
//3 Dados opcionais ex: além de nome e telefone do cliente, pode haver a necessidade de saber o nome do técnico que fez a instalação.
$dado1 = (!empty($_POST['dado1'])) ? $_POST['dado1'] : '';
$dado2 = (!empty($_POST['dado2'])) ? $_POST['dado2'] : '';
$dado3 = (!empty($_POST['dado3'])) ? $_POST['dado3'] : '';
//Ramal da ligação para o cliente.
$ramal = (!empty($_POST['ramal'])) ? $_POST['ramal'] : '';
/*
   $qtd_tentativas_cliente
Configuração de quantidade de tentativas de entrevista por contato.
*/
$qtd_tentativas_cliente = (!empty($_POST['qtd_tentativas_cliente'])) ? $_POST['qtd_tentativas_cliente'] : '';
/*
   $horas_entre_tentativas
Horas entre as tentativas de cada contato
*/
$horas_entre_tentativas = (!empty($_POST['horas_entre_tentativas'])) ? $_POST['horas_entre_tentativas'] : '';
/*
    $data_criacao
Data de criação (horário de Brasilia) da pesquisa
*/
$data_criacao = getDataHora();
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$prazo_termino = (!empty($_POST['prazo_termino'])) ? converteData($_POST['prazo_termino']) : NULL;

if(!empty($_POST['inserir'])){

    /*Campos $titulo, $qtd_tentativas_cliente e $horas_entre_tentativas obrigatórios.*/
    if($titulo != "" && $qtd_tentativas_cliente != "" && $horas_entre_tentativas != ""){
        inserir($titulo, $status, $data_criacao, $ramal, $qtd_tentativas_cliente, $horas_entre_tentativas, $id_contrato_plano_pessoa, $observacao, $dado1, $dado2, $dado3, $observacao_status, $prazo_termino);
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    /*Campos $titulo, $qtd_tentativas_cliente e $horas_entre_tentativas obrigatórios.*/
    if($titulo != "" && $qtd_tentativas_cliente != "" && $horas_entre_tentativas != ""){
        alterar($id, $titulo, $status, $ramal, $qtd_tentativas_cliente, $horas_entre_tentativas, $id_contrato_plano_pessoa, $observacao, $dado1, $dado2, $dado3, $observacao_status, $prazo_termino);
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-form&alterar=$id");
        exit;
    }
    

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($titulo, $status, $data_criacao, $ramal, $qtd_tentativas_cliente, $horas_entre_tentativas, $id_contrato_plano_pessoa, $observacao, $dado1, $dado2, $dado3, $observacao_status, $prazo_termino){

    $dados = array(
        'titulo' => $titulo, 
        'status' => $status,
        'data_criacao' => $data_criacao,
        'observacao' => $observacao,
        'dado1' => $dado1,
        'dado2' => $dado2,
        'dado3' => $dado3,
        'ramal' => $ramal,
        'qtd_tentativas_cliente' => $qtd_tentativas_cliente,
        'horas_entre_tentativas' => $horas_entre_tentativas,
        'observacao_status' => $observacao_status,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'prazo_termino' => $prazo_termino
    );

    $insertID = DBCreate('', 'tb_pesquisa', $dados, true);
    registraLog('Inserção de pesquisa.','i','tb_pesquisa',$insertID,"titulo: $titulo | status: $status | data_criacao: $data_criacao | observacao: $observacao | dado1: $dado1 | dado2: $dado2 | dado3: $dado3 | ramal: $ramal | qtd_tentativas_cliente: $qtd_tentativas_cliente | horas_entre_tentativas: $horas_entre_tentativas | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | prazo_termino: $prazo_termino");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-busca");
    exit;
}

function alterar($id, $titulo, $status, $ramal, $qtd_tentativas_cliente, $horas_entre_tentativas, $id_contrato_plano_pessoa, $observacao, $dado1, $dado2, $dado3, $observacao_status, $prazo_termino){

    $dados_antigos = DBRead('','tb_pesquisa', "WHERE id_pesquisa = $id");

    if($dados_antigos[0]['qtd_tentativas_cliente'] > $qtd_tentativas_cliente){
        $alert = ('A quantidade de tentativas não pode ser menor que a antiga!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-form&alterar=$id");
        exit;
    }elseif($dados_antigos[0]['qtd_tentativas_cliente'] < $qtd_tentativas_cliente){
        $dados = array(
            'status_pesquisa' => '0'
        );
        DBUpdate('', 'tb_contatos_pesquisa', $dados, "id_pesquisa = $id AND status_pesquisa = 2");
    }

    $dados = array(
        'titulo' => $titulo,
        'status' => $status,
        'observacao' => $observacao,
        'dado1' => $dado1,
        'dado2' => $dado2,
        'dado3' => $dado3,
        'ramal' => $ramal,
        'qtd_tentativas_cliente' => $qtd_tentativas_cliente,
        'horas_entre_tentativas' => $horas_entre_tentativas,
        'observacao_status' => $observacao_status,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'prazo_termino' => $prazo_termino
    );
    DBUpdate('', 'tb_pesquisa', $dados, "id_pesquisa = $id");
    registraLog('Alteração de pesquisa.','a','tb_pesquisa',$id,"titulo: $titulo | status: $status | observacao: $observacao | dado1: $dado1 | dado2: $dado2 | dado3: $dado3 | ramal: $ramal | qtd_tentativas_cliente: $qtd_tentativas_cliente | horas_entre_tentativas: $horas_entre_tentativas | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | prazo_termino: $prazo_termino");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-busca");
    exit;
}

function excluir($id){
    $dados = array(
        'status' => '2',
    );
    $dados_contato = array(
        'status_pesquisa' => '4',
    );
    
    DBUpdate('', 'tb_pesquisa', $dados, "id_pesquisa = $id");
    registraLog('Exclusão de pesquisa.', 'e', 'tb_pesquisa', $id, '');
    DBUpdate('', 'tb_contatos_pesquisa', $dados_contato, "id_pesquisa = $id AND status_pesquisa = '0'");
    registraLog('Exclusão de contatos pesquisa.', 'e', 'tb_contatos_pesquisa', $id, '');
    $alert = ('Item excluído com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-busca");
    exit;
}

?>
