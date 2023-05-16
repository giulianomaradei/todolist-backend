<?php
require_once(__DIR__."/System.php");


$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$ramal = (!empty($_POST['ramal'])) ? $_POST['ramal'] : '';
$qtd_tentativas_cliente = (!empty($_POST['qtd_tentativas_cliente'])) ? $_POST['qtd_tentativas_cliente'] : '';
$horas_entre_tentativas = (!empty($_POST['horas_entre_tentativas'])) ? $_POST['horas_entre_tentativas'] : '';
$data_criacao = getDataHora();
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$clone_pesquisa = (!empty($_POST['clone_pesquisa'])) ? $_POST['clone_pesquisa'] : '';

$clona_contato = (!empty($_POST['clona_contato'])) ? $_POST['clona_contato'] : '2';

$prazo_termino = (!empty($_POST['prazo_termino'])) ? converteData($_POST['prazo_termino']) : NULL;

if(!empty($_POST['inserir'])){
    
    if($titulo != "" && $qtd_tentativas_cliente != "" && $horas_entre_tentativas != ""){
        inserir($titulo, $status, $data_criacao, $ramal, $qtd_tentativas_cliente, $horas_entre_tentativas, $id_contrato_plano_pessoa, $observacao, $prazo_termino, $clone_pesquisa, $clona_contato);
        
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-form");
        exit;
    }
} /*else if(!empty($_POST['clonar'])){

    DBRead();
    
    clonar($descricao, $posicao, $resposta_pai, $observacao, $id_pesquisa, $id_tipo_resposta, $dados_resposta, $escala_de, $escala_ate);
}*/

function inserir($titulo, $status, $data_criacao, $ramal, $qtd_tentativas_cliente, $horas_entre_tentativas, $id_contrato_plano_pessoa, $observacao, $prazo_termino, $clone_pesquisa, $clona_contato){

        $dados = array(
            'titulo' => $titulo,
            'status' => $status,
            'data_criacao' => $data_criacao,
            'observacao' => $observacao,
            'ramal' => $ramal,
            'qtd_tentativas_cliente' => $qtd_tentativas_cliente,
            'horas_entre_tentativas' => $horas_entre_tentativas,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'prazo_termino' => $prazo_termino,
        );

        $insertID = DBCreate('', 'tb_pesquisa', $dados, true);
        
        $perguntas = DBRead('', 'tb_pergunta_pesquisa',"WHERE id_pesquisa = '".$clone_pesquisa."' AND status != 0");
        foreach ($perguntas as $pergunta) {
            $descricao = $pergunta['descricao'];
            $posicao = $pergunta['posicao'];
            $observacao_pergunta = $pergunta['observacao'];
            $id_tipo_resposta_pesquisa = $pergunta['id_tipo_resposta_pesquisa'];
            $dados_pergunta = array(
                'descricao' => $descricao,
                'posicao' => $posicao,
                'resposta_pai' => 0,
                'observacao' => $observacao_pergunta,
                'id_pesquisa' => $insertID,
                'id_tipo_resposta_pesquisa' => $id_tipo_resposta_pesquisa
            );

            $insert_pergunta = DBCreate('', 'tb_pergunta_pesquisa', $dados_pergunta, true);
            registraLog('Inserção de pergunta.','i','tb_pergunta_pesquisa',$insertID,"descricao: $descricao | posicao: $posicao | resposta_pai: 0 | observacao: $observacao |  id_pesquisa: $insertID | id_tipo_resposta_pesquisa: $id_tipo_resposta_pesquisa");
            
            $respostas = DBRead('', 'tb_resposta_pesquisa',"WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'");
                foreach ($respostas as $resposta) {
                    $dados_resposta = array(
                        'descricao' => $resposta['descricao'],
                        'posicao' => $resposta['posicao'],
                        'id_pergunta_pesquisa' => $insert_pergunta
                    );
                    
                    $insert_resposta = DBCreate('', 'tb_resposta_pesquisa', $dados_resposta);
                    registraLog('Inserção de nova resposta.','i','tb_resposta_pesquisa',$insert_resposta,"descricao: $descricao | posicao: $posicao | id_pergunta_pesquisa: $insert_pergunta");

               }
        }
        if($clona_contato == 1){
            
            $dados_contatos = DBRead('', 'tb_contatos_pesquisa',"WHERE id_pesquisa = '".$clone_pesquisa."' AND qtd_tentativas_cliente = 0 AND status_pesquisa = 0");

            foreach ($dados_contatos as $contato) {
                $data_inclusao = getDataHora();

                $nome = $contato['nome'];
                $telefone = $contato['telefone'];
                $observacao = $contato['observacao'];
                $label1 = $contato['label1'];
                $label2 = $contato['label2'];
                $label3 = $contato['label3'];
                $dado1 = $contato['dado1'];
                $dado2 = $contato['dado2'];
                $dado3 = $contato['dado3'];

                $dados = array(
                    'nome' => $nome,
                    'telefone' => $telefone,
                    'observacao' => $observacao,
                    'data_inclusao' => $data_inclusao,
                    'status_pesquisa' => 0,
                    'qtd_tentativas_cliente' => 0,
                    'label1' => $label1,
                    'label2' => $label2,
                    'label3' => $label3,
                    'dado1' => $dado1,
                    'dado2' => $dado2,
                    'dado3' => $dado3,
                    'id_pesquisa' => $insertID
                );
                
                $insertID2 = DBCreate('', 'tb_contatos_pesquisa', $dados, true);
                registraLog('Inserção de pesquisa.','i','tb_pesquisa',$insertID2,"nome: $nome | telefone: $telefone | observacao: $observacao | data_inclusao: $data_inclusao | id_pesquisa: $insertID | qtd_tentativas_cliente: 0 | label1: $label1 | label2: $label2 | label3: $label3 | dado1: $dado1 | dado2: $dado2 | dado3: $dado3");
            }
        }
        
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-busca");
    exit;
}
?>