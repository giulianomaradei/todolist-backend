<?php
require_once(__DIR__."/System.php");


$operacao = (!empty($_POST['operacao'])) ? $_POST['operacao'] : '';

$data_referencia = new DateTime(getDataHora());
$data_referencia->modify('first day of last month');
$data_referencia = $data_referencia->format('Y-m-d');

$data_de = $data_referencia;

$data_ate = new DateTime(getDataHora());
$data_ate->modify('last day of last month');
$data_ate = $data_ate->format('Y-m-d');

if ($operacao == 'gerar' || $operacao == 'reprocessar') {

    //verificação para não gerar duas vezes e se for reprocessar exclui os dados
    if($operacao == 'gerar'){
        $dados_comissoes = DBRead('','tb_plantao_redes_comissao',"WHERE data_referencia = '$data_referencia'");
        if($dados_comissoes){            
	        $alert = ('Não é possível gerar as comissões duas vezes, você precisa utilizar a opção "Reprocessar"!', 'd');
            header("location: /api/iframe?token=$request->token&view=plantao-comissoes");
            exit;
        }
    }else{
        DBDelete('', 'tb_plantao_redes_comissao', "data_referencia = '$data_referencia'");
    }

    $dados_plantonista_redes_mes = DBRead('','tb_plantonista_redes_mes', "WHERE data_referencia = '$data_referencia'");
    $valor_diaria = $dados_plantonista_redes_mes[0]['valor_diaria'];
    $porcentagem_comissao = $dados_plantonista_redes_mes[0]['porcentagem_comissao'];

    $dados_comissoes = array();
    $dados_plantonista_redes_mes_dia = DBRead('','tb_plantonista_redes_mes_dia a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data >= '$data_de' AND a.data <= '$data_ate' GROUP BY a.id_usuario","a.id_usuario, c.nome, COUNT(*) AS 'dias_plantao'");
    if($dados_plantonista_redes_mes_dia){
        foreach ($dados_plantonista_redes_mes_dia as $conteudo_plantonista_redes_mes_dia) {
            $dados_comissoes[$conteudo_plantonista_redes_mes_dia['id_usuario']]['nome_plantonista'] = $conteudo_plantonista_redes_mes_dia['nome'];
            $dados_comissoes[$conteudo_plantonista_redes_mes_dia['id_usuario']]['dias_plantao'] = $conteudo_plantonista_redes_mes_dia['dias_plantao'];
            $dados_comissoes[$conteudo_plantonista_redes_mes_dia['id_usuario']]['porcentagem_comissao_atendimentos'] = $porcentagem_comissao;
            $dados_comissoes[$conteudo_plantonista_redes_mes_dia['id_usuario']]['valor_diaria'] = $valor_diaria;
            $dados_comissoes[$conteudo_plantonista_redes_mes_dia['id_usuario']]['valor_total_atendimentos'] = 0;
        }
    }
    
    $dados_usuarios = DBRead('',"tb_usuario a", "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_otrs");
    $parametros_usuarios = array();
    if($dados_usuarios){
        foreach ($dados_usuarios as $conteudo_usuario) {
            $dados_plantonista_redes_mes_dia = DBRead('','tb_plantonista_redes_mes_dia',"WHERE id_usuario = '".$conteudo_usuario['id_usuario']."' AND data >= '$data_de' AND data <= '$data_ate'","COUNT(*) AS 'dias_plantao'");
            $parametros_usuarios[$conteudo_usuario['id_otrs']]['nome'] = $conteudo_usuario['nome'];
            $parametros_usuarios[$conteudo_usuario['id_otrs']]['id_usuario'] = $conteudo_usuario['id_usuario'];
            $parametros_usuarios[$conteudo_usuario['id_otrs']]['dias_plantao'] = $dados_plantonista_redes_mes_dia[0]['dias_plantao'];
        }
    }

    $dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametro_redes_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_usuario e ON d.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE c.cod_servico = 'gestao_redes' ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, b.nome, d.id_otrs, a.valor_plantao, a.tipo_plantao");
    $parametros_contratos = array();
    if($dados_contratos){
        foreach ($dados_contratos as $conteudo_contrato) {
            $parametros_contratos[$conteudo_contrato['id_otrs']]['nome'] = $conteudo_contrato['nome'];
            $parametros_contratos[$conteudo_contrato['id_otrs']]['id_contrato_plano_pessoa'] = $conteudo_contrato['id_contrato_plano_pessoa'];
            $parametros_contratos[$conteudo_contrato['id_otrs']]['valor_plantao'] = $conteudo_contrato['valor_plantao'];
            $parametros_contratos[$conteudo_contrato['id_otrs']]['tipo_plantao'] = $conteudo_contrato['tipo_plantao'];
        }
    }
      
    
    //usar o GROUP BY a.tn para agrupar por ticket number e juntar os tempos de articles do mesmo ticket
    $dados_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59' GROUP BY a.tn","a.tn, a.title, a.create_time, a.customer_id, SUM(c.time_unit) AS 'tempo_total', a.responsible_user_id");  
	
    if($dados_plantoes){
        $tempo_total = 0;
      		
        foreach($dados_plantoes as $conteudo_plantao){
            $tempo_chamado_otrs = intval($conteudo_plantao['tempo_total']); 
        
            if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '1'){
                $tempo_conta = $tempo_chamado_otrs;
                if($tempo_conta > 30){
                    $somatorio_tempo = $tempo_conta/30;
                    $resto_tempo = $tempo_conta%30;
                    if($resto_tempo != 0){
                        $tempo_conta = ((int)$somatorio_tempo*30)+30;
                    }
                }else{
                    $tempo_conta = 30;
                }

            }else if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '2'){
                $tempo_conta = $tempo_chamado_otrs;
                if($tempo_conta > 60){
                    $somatorio_tempo = $tempo_conta/60;
                    $resto_tempo = $tempo_conta%60;
                    if($resto_tempo != 0){
                        $tempo_conta = ((int)$somatorio_tempo*60)+60;
                    }
                }else{
                    $tempo_conta = 60;
                }

            }else if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '3'){
                $tempo_conta = $tempo_chamado_otrs;
                if($tempo_conta > 60){
                    $somatorio_tempo = $tempo_conta/60;
                    $resto_tempo = $tempo_conta%60;
                    if($resto_tempo != 0){
                        $tempo_conta = ((int)$somatorio_tempo*60)+$resto_tempo;
                    }
                }else{
                    $tempo_conta = 60;
                }
            }else{
                $tempo_conta = 0;
            }

            if($tempo_conta != 0){
                if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '1'){
                    $qtd_plantao = $tempo_conta / 30;
                }else if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '2'){
                    $qtd_plantao = $tempo_conta / 60;
                }else if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '3'){
                    $qtd_plantao = $tempo_conta / 60;
                }
            }else{
                $qtd_plantao = 0;
            }

            if($parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'] == '3'){
                $valor_plantao_cobranca = $tempo_conta*($parametros_contratos[$conteudo_plantao['customer_id']]['valor_plantao']/60);

            }else{
                $valor_plantao_cobranca = $qtd_plantao * $parametros_contratos[$conteudo_plantao['customer_id']]['valor_plantao'];
            }

            $dados_comissoes[$parametros_usuarios[$conteudo_plantao['responsible_user_id']]['id_usuario']]['nome_plantonista'] = $parametros_usuarios[$conteudo_plantao['responsible_user_id']]['nome'];
            $dados_comissoes[$parametros_usuarios[$conteudo_plantao['responsible_user_id']]['id_usuario']]['valor_total_atendimentos'] += $valor_plantao_cobranca;
            $dados_comissoes[$parametros_usuarios[$conteudo_plantao['responsible_user_id']]['id_usuario']]['porcentagem_comissao_atendimentos'] = $porcentagem_comissao;
            $dados_comissoes[$parametros_usuarios[$conteudo_plantao['responsible_user_id']]['id_usuario']]['dias_plantao'] = $parametros_usuarios[$conteudo_plantao['responsible_user_id']]['dias_plantao'];
            $dados_comissoes[$parametros_usuarios[$conteudo_plantao['responsible_user_id']]['id_usuario']]['valor_diaria'] = $valor_diaria;

            $array_plantao = array(
                'tn' => $conteudo_plantao['tn'],
                'id_contrato_plano_pessoa' => $parametros_contratos[$conteudo_plantao['customer_id']]['id_contrato_plano_pessoa'],
                'nome_cliente' => $parametros_contratos[$conteudo_plantao['customer_id']]['nome'],
                'assunto' => $conteudo_plantao['title'],
                'data' => $conteudo_plantao['create_time'],
                'tempo' => $tempo_chamado_otrs,
                'tipo_plantao' => $parametros_contratos[$conteudo_plantao['customer_id']]['tipo_plantao'],
                'valor_unt' => $parametros_contratos[$conteudo_plantao['customer_id']]['valor_plantao'],
                'valor_cobranca' => $valor_plantao_cobranca,
                'data_referencia' => $data_referencia,
            );

            $dados_comissoes[$parametros_usuarios[$conteudo_plantao['responsible_user_id']]['id_usuario']]['plantoes'][] = $array_plantao;   

        }        

        
        
        if($dados_comissoes){  
            $link = DBConnect('');
            DBBegin($link);          
            foreach($dados_comissoes as $id_plantonista => $conteudo_comissoes){

                $valor_total_comissao = $conteudo_comissoes['valor_total_atendimentos'] * ($conteudo_comissoes['porcentagem_comissao_atendimentos'] / 100);
                $valor_total_comissao_dias_plantao = $conteudo_comissoes['dias_plantao'] *  $conteudo_comissoes['valor_diaria'];
                $valor_total_receber = $valor_total_comissao + $valor_total_comissao_dias_plantao;

                $dados = array(
                    'data_referencia' => $data_referencia,
                    'valor_total_atendimentos' => $conteudo_comissoes['valor_total_atendimentos'],
                    'porcentagem_comissao_atendimentos' => $conteudo_comissoes['porcentagem_comissao_atendimentos'],
                    'dias_plantao' => $conteudo_comissoes['dias_plantao'],
                    'valor_diaria' => $conteudo_comissoes['valor_diaria'],
                    'id_usuario' => $id_plantonista
                );

                $id_plantao_redes_comissao = DBCreateTransaction($link, 'tb_plantao_redes_comissao', $dados, true);

                if($conteudo_comissoes['plantoes']){
                    foreach($conteudo_comissoes['plantoes'] as $conteudo_plantao){                        
                        $dados = array(
                            'id_usuario' => $id_plantonista,
                            'tn' => $conteudo_plantao['tn'],
                            'assunto' => $conteudo_plantao['assunto'],
                            'data' => $conteudo_plantao['data'],
                            'tempo' => $conteudo_plantao['tempo'],
                            'id_contrato_plano_pessoa' => $conteudo_plantao['id_contrato_plano_pessoa'],
                            'tipo_plantao' => $conteudo_plantao['tipo_plantao'],
                            'valor_cobranca' => $conteudo_plantao['valor_cobranca'],
                            'data_referencia' => $data_referencia,
                            'id_plantao_redes_comissao' => $id_plantao_redes_comissao,
                            'valor_unt' => $conteudo_plantao['valor_unt'],
                            
                        );
        
                        DBCreateTransaction($link, 'tb_plantao_redes', $dados);
                    }
                }    
            }
            DBCommit($link);
        }
        header("location: /api/iframe?token=$request->token&view=plantao-comissoes");
        exit;
	}else{
        $alert = ('Não houveram plantões!', 'd');
        header("location: /api/iframe?token=$request->token&view=plantao-comissoes");
        exit;
    }	
    
}else{
    header("location: ../adm.php");
    exit;
}

?>