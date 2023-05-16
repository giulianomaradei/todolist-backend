<?php
require_once "System.php";

$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$mes = $parametros['mes'];
$ano = $parametros['ano'];
$canal_atendimento = $parametros['canal_atendimento'];
$classificacao = $parametros['classificacao'];

if ($acao == 'busca_formulario') {

    $data_referencia = $ano.'-'.$mes.'-01';
    
    $dados = DBRead('','tb_monitoria_mes', "WHERE data_referencia = '$data_referencia' AND tipo_monitoria = $canal_atendimento AND classificacao_atendente = $classificacao AND status = 1" );

    if($dados) {
        foreach ($dados as $conteudo) {
            $id = $conteudo['id_monitoria_mes'];

            if ($conteudo['tipo_monitoria'] == 1) {
                $tipo = '(via Telefone - ';

            } else if ($conteudo['tipo_monitoria'] == 2) {
                $tipo = '(via Texto - ';
            }

            if ($conteudo['classificacao_atendente'] == 1) {
                $classificacao = 'Em Treinamento)';

            } else if ($conteudo['classificacao_atendente'] == 2) {
                $classificacao = 'Período de experiência)';

            } else if ($conteudo['classificacao_atendente'] == 3) {
                $classificacao = 'Efetivado)';

            }

            $meses = array(
                '01' => 'Janeiro',
                '02' => 'Fevereiro',
                '03' => 'Março',
                '04' => 'Abril',
                '05' => 'Maio',
                '06' => 'Junho',
                '07' => 'Julho',
                '08' => 'Agosto',
                '09' => 'Setembro',
                '10' => 'Outubro',
                '11' => 'Novembro',
                '12' => 'Dezembro',
            );
            
            $nome_formulario = $meses[$mes].'/'.$ano.' '.$tipo.$classificacao;
           
            echo "<option value='".$id."'>".$nome_formulario."</option>";
        }
    } else {
        echo "<option value=''>Não foram encontrados formulários com as opções selecionadas!</option>";
    }
}
	