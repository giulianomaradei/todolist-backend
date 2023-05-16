<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$cod_servico = (!empty($_POST['cod_servico'])) ? $_POST['cod_servico'] : '';
$cor = (!empty($_POST['cor'])) ? $_POST['cor'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : '0';

$permissoes = (!empty($_POST['permissoes'])) ? $_POST['permissoes'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_plano', "WHERE BINARY nome = '".addslashes($nome)."' AND cod_servico = '$cod_servico'");
    if (!$dados) {
        inserir($nome, $cod_servico, $cor, $status, $permissoes);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plano-form");
        exit;
    } 

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
       
    $dados = DBRead('', 'tb_plano', "WHERE BINARY nome = '".addslashes($nome)."' AND cod_servico = '$cod_servico' AND id_plano != '$id'");
    if (!$dados) {
        alterar($id, $nome, $cod_servico, $cor, $status, $permissoes);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plano-form&alterar=$id");
        exit;
    }
 
} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
} 

function inserir($nome, $cod_servico, $cor, $status, $permissoes){

    $data_mes_ano_hoje = new DateTime(getDataHora('data'));
    $data_mes_ano_hoje = $data_mes_ano_hoje->format('Ym');

    $versao = $data_mes_ano_hoje.'-1';

    $data_atualizacao = getDataHora();
    $id_usuario = $_SESSION['id_usuario'];

    $dados = array(
        'nome' => $nome,
        'cod_servico' => $cod_servico,
        'cor' => $cor,
        'status' => $status,
        'versao' => $versao
    );

    $insertID = DBCreate('', 'tb_plano', $dados, true);
    registraLog('Inserção de plano no sistema.','i','tb_plano',$insertID,"nome: $nome | cod_servico: $cod_servico | cor: $cor | status: $status | versao: $versao");

    foreach ($permissoes as $id_plano_procedimento) {
        $dados = array(
            'id_plano' => $insertID,
            'id_plano_procedimento' => $id_plano_procedimento
        );
        $id_plano_procedimento_plano = DBCreate('', 'tb_plano_procedimento_plano', $dados, true);
        registraLog('Inserção de permissao de procedimento.','i','tb_plano_procedimento_plano',$id_plano_procedimento_plano,"id_plano: $insertID | id_plano_procedimento: $id_plano_procedimento");

        $dados_historico = array(
            'id_plano' => $insertID,
            'versao' => $versao,
            'data_atualizacao' => $data_atualizacao,
            'id_usuario' => $id_usuario,
            'id_plano_procedimento' => $id_plano_procedimento
        );
    
        $insertIDHistorico = DBCreate('', 'tb_plano_procedimento_historico', $dados_historico, true);
        registraLog('Inserção de plano histórico no sistema.','i','tb_plano_procedimento_historico',$insertIDHistorico,"id_plano: $insertID | versao: $versao | data_atualizacao: $data_atualizacao | id_usuario: $id_usuario | id_plano_procedimento: $id_plano_procedimento");
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=plano-busca");
   
    exit;
}

function alterar($id, $nome, $cod_servico, $cor, $status, $permissoes){
    
    $dados = array(
        'nome' => $nome,
        'cod_servico' => $cod_servico,
        'cor' => $cor,
        'status' => $status
    );

    DBUpdate('', 'tb_plano', $dados, "id_plano = '$id'");
    registraLog('Alteração de plano.','a','tb_plano',$id,"nome: $nome | cod_servico: $cod_servico | cor: $cor | status: $status");
   
    $dados_plano_procedimento_plano = DBRead('', 'tb_plano_procedimento_plano', "WHERE id_plano = '$id' ", "id_plano_procedimento");
    $array1 = array();
    $cont = 0;
    foreach ($dados_plano_procedimento_plano as $conteudo_plano_procedimento_plano){
        $array1[$cont] = $conteudo_plano_procedimento_plano['id_plano_procedimento'];
        $cont++;
    }

    if(array_diff($permissoes, $array1) || array_diff($array1, $permissoes)){
        $data_mes_ano_hoje = new DateTime(getDataHora('data'));
        $data_mes_ano_hoje = $data_mes_ano_hoje->format('Ym');
    
        $dados_plano = DBRead('', 'tb_plano', "WHERE id_plano = '$id'");
        $versao_plano = $dados_plano[0]['versao'];
        $data_mes_ano_hoje_plano = explode('-', $versao_plano);
        $numero_versao = $data_mes_ano_hoje_plano[1];
        $data_mes_ano_hoje_plano = $data_mes_ano_hoje_plano[0];
    
        if($data_mes_ano_hoje == $data_mes_ano_hoje_plano){
            $numero_versao++;
        }else{
            $numero_versao = 1;
        }
        $versao = $data_mes_ano_hoje.'-'.$numero_versao;
    
        $data_atualizacao = getDataHora();
        $id_usuario = $_SESSION['id_usuario'];

        $dados = array(
            'versao' => $versao
        );
        DBUpdate('', 'tb_plano', $dados, "id_plano = '$id'");
        registraLog('Atualiza versao de plano.','a','tb_plano',$id,"versao: $versao");

        DBDelete('','tb_plano_procedimento_plano',"id_plano = '$id'"); 
        foreach ($permissoes as $id_plano_procedimento) {
            $dados = array(
                'id_plano' => $id,
                'id_plano_procedimento' => $id_plano_procedimento
            );
            $id_plano_procedimento_plano = DBCreate('', 'tb_plano_procedimento_plano', $dados, true);
            registraLog('Inserção de permissao de procedimento.','i','tb_plano_procedimento_plano',$id_plano_procedimento_plano,"id_plano: $id | id_plano_procedimento: $id_plano_procedimento");

            $dados_historico = array(
                'id_plano' => $id,
                'versao' => $versao,
                'data_atualizacao' => $data_atualizacao,
                'id_usuario' => $id_usuario,
                'id_plano_procedimento' => $id_plano_procedimento
            );
        
            $insertIDHistorico = DBCreate('', 'tb_plano_procedimento_historico', $dados_historico, true);
            registraLog('Inserção de plano histórico no sistema.','i','tb_plano_procedimento_historico',$insertIDHistorico,"id_plano: $id | versao: $versao | data_atualizacao: $data_atualizacao | id_usuario: $id_usuario | id_plano_procedimento: $id_plano_procedimento");
        }
    }

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=plano-busca");
    
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_plano WHERE id_plano = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de plano do sistema.','e','tb_plano',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }    
    header("location: /api/iframe?token=$request->token&view=plano-busca");
    exit;

}

?>
