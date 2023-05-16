<?php
require_once(__DIR__."/System.php");

$incluir_contas_receber_atrasadas = (!empty($_POST['incluir_contas_receber_atrasadas'])) ? $_POST['incluir_contas_receber_atrasadas'] : 0;
$considerar_contratos = (!empty($_POST['considerar_contratos'])) ? $_POST['considerar_contratos'] : 0;
$data_ate_fluxo = (!empty($_POST['data_ate_fluxo'])) ? $_POST['data_ate_fluxo'] : converteData(date('Y-m-d', strtotime("+1 years",strtotime(getDataHora()))));
$dias_descarte = (!empty($_POST['dias_descarte'])) ? $_POST['dias_descarte'] : 0;
$caixas = (!empty($_POST['caixas'])) ? $_POST['caixas'] : '';
 if (!empty($_POST['gerar'])){
    
	gerar($data_ate_fluxo, $caixas, $incluir_contas_receber_atrasadas, $dias_descarte, $considerar_contratos);
	
}elseif(isset($_GET['excluir'])){
    $id = (int) $_GET['excluir'];
    excluir($id);
}else{
    header("location: ../adm.php");
    exit;
}

function gerar($data_ate, $caixas, $incluir_contas_receber_atrasadas, $dias_descarte, $considerar_contratos){

	$data_hoje = converteDataHora(getDataHora());

	$data_hoje = explode(" ", $data_hoje);
    $data_hoje = $data_hoje[0];

    $mes_ano_hoje = explode("/", $data_hoje);
    $mes_ano_hoje = $mes_ano_hoje[1].'/'.$mes_ano_hoje[2];

    $primeiro_dia_mes_passado = new DateTime(getDataHora('data'));
    $primeiro_dia_mes_passado->modify('first day of last month');
    $primeiro_dia_mes_passado = $primeiro_dia_mes_passado->format('Y-m-d');
    
    $array_mes_ano = array();
    $array_dia_mes_ano = array();

	$array_total_pagar_mes_ano = array();
    $array_total_pagar_dia_mes_ano = array();
    $array_total_receber_mes_ano = array();
    $array_total_receber_dia_mes_ano = array(); 
    $array_saldo_mes_ano = array();
    $array_saldo_dia_mes_ano = array();  
    $array_resultado_mes_ano = array();
    $array_resultado_dia_mes_ano = array();  
    

    $dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"WHERE a.status = 1 and a.realiza_cobranca = 1", 'a.id_contrato_plano_pessoa, a.tipo_cobranca, a.valor_inicial, a.valor_total,a.dia_pagamento, a.data_final_cobranca');

    $dados_faturamento = DBRead('','tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_conta_receber c ON a.id_faturamento = c.id_faturamento WHERE a.status = 1 AND a.data_referencia = '$primeiro_dia_mes_passado' AND c.situacao != 'baixada' GROUP BY b.id_contrato_plano_pessoa", 'b.id_contrato_plano_pessoa');
    
    $dados_saldo_caixa = DBRead('', 'tb_caixa', "WHERE status = 1 AND id_caixa IN ('".join("','", $caixas)."')","SUM(saldo) as 'saldo'");

    $saldo_caixa = $dados_saldo_caixa[0]['saldo'] ? $dados_saldo_caixa[0]['saldo'] : 0;
    foreach(rangeDatas(converteData($data_hoje), converteData($data_ate)) as $data){

        $data_explode = explode("-", $data);
        $data_mes_ano = $data_explode[1].'/'.$data_explode[0];
        $data_dia_mes = $data_explode[2].'/'.$data_explode[1];

        if($data == getDataHora('data')){
            if($incluir_contas_receber_atrasadas && $dias_descarte > 0){
                $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND id_caixa IN ('".join("','", $caixas)."') AND data_vencimento >= '".date('Y-m-d', strtotime("-".$dias_descarte." days",strtotime($data)))."' AND data_vencimento <= '".$data."'");
            }else{
                $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND id_caixa IN ('".join("','", $caixas)."') AND data_vencimento = '".$data."'");
            }            
            $dados_conta_pagar = DBRead('','tb_conta_pagar',"WHERE situacao = 'aberta' AND id_caixa IN ('".join("','", $caixas)."') AND data_vencimento <= '".$data."'");
        }else{            
            $dados_conta_receber = DBRead('','tb_conta_receber',"WHERE situacao = 'aberta' AND id_caixa IN ('".join("','", $caixas)."') AND data_vencimento = '".$data."'");
            $dados_conta_pagar = DBRead('','tb_conta_pagar',"WHERE situacao = 'aberta' AND id_caixa IN ('".join("','", $caixas)."') AND data_vencimento = '".$data."'");
        }        		
        
        if (!in_array($data_mes_ano, $array_mes_ano)) { 
            $array_mes_ano[] = $data_mes_ano;
        }

        $array_dia_mes_ano[$data_mes_ano][] = $data_dia_mes;

		if($dados_conta_pagar){
			foreach ($dados_conta_pagar as $conteudo_conta_pagar) {
				$array_total_pagar_mes_ano[$data_mes_ano] += $conteudo_conta_pagar['valor'];
				$array_total_pagar_dia_mes_ano[$data_mes_ano][$data_dia_mes] += $conteudo_conta_pagar['valor'];
			}
		}else{			
			$array_total_pagar_dia_mes_ano[$data_mes_ano][$data_dia_mes] = 0.00;
        }
        
        if(!$array_total_pagar_mes_ano[$data_mes_ano]){
            $array_total_pagar_mes_ano[$data_mes_ano] = 0.00;
        }

		if($dados_conta_receber){
			foreach ($dados_conta_receber as $conteudo_conta_receber) {
                $array_total_receber_mes_ano[$data_mes_ano] += $conteudo_conta_receber['valor'];
				$array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] += $conteudo_conta_receber['valor'];
			}
		}else{
			$array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] = 0.00;
        }
        
        if(!$array_total_receber_mes_ano[$data_mes_ano]){
            $array_total_receber_mes_ano[$data_mes_ano] = 0.00;
        }

        if($dados_contratos && $considerar_contratos){
            foreach ($dados_contratos as $conteudo_contrato) {
                if(sprintf('%02d', $conteudo_contrato['dia_pagamento']) == $data_explode[2] && (!$conteudo_contrato['data_final_cobranca'] || $conteudo_contrato['data_final_cobranca'] == '' || $conteudo_contrato['data_final_cobranca'] == '0000-00-00' || $conteudo_contrato['data_final_cobranca'] >= $data)){
                    $tem_faturamento = 0;                    
                    if($dados_faturamento && $data_mes_ano == $mes_ano_hoje){
                        foreach ($dados_faturamento as $conteudo_faturamento) {
                            if($conteudo_faturamento['id_contrato_plano_pessoa'] == $conteudo_contrato['id_contrato_plano_pessoa']){
                                $tem_faturamento = 1;
                                break;
                            }
                        }
                    }
                    if($tem_faturamento == 0){
                        if($conteudo_contrato['tipo_cobranca'] == 'unitario'){
                            $array_total_receber_mes_ano[$data_mes_ano] += $conteudo_contrato['valor_inicial'];
                            $array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] += $conteudo_contrato['valor_inicial'];
                        }else{
                            $array_total_receber_mes_ano[$data_mes_ano] += $conteudo_contrato['valor_total'];
                            $array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] += $conteudo_contrato['valor_total'];
                        }    
                    }
                    
                }
            }
        }

        $array_resultado_dia_mes_ano[$data_mes_ano][$data_dia_mes] = $array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] - $array_total_pagar_dia_mes_ano[$data_mes_ano][$data_dia_mes];

        $array_saldo_dia_mes_ano[$data_mes_ano][$data_dia_mes] = $saldo_caixa + ($array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] - $array_total_pagar_dia_mes_ano[$data_mes_ano][$data_dia_mes]);

        $saldo_caixa += $array_total_receber_dia_mes_ano[$data_mes_ano][$data_dia_mes] - $array_total_pagar_dia_mes_ano[$data_mes_ano][$data_dia_mes];

    }
    
    $saldo_caixa = $dados_saldo_caixa[0]['saldo'] ? $dados_saldo_caixa[0]['saldo'] : 0;
    foreach ($array_mes_ano as $mes_ano) {
        $array_resultado_mes_ano[$mes_ano] = $array_total_receber_mes_ano[$mes_ano] - $array_total_pagar_mes_ano[$mes_ano];
        $array_saldo_mes_ano[$mes_ano] = $saldo_caixa + ($array_total_receber_mes_ano[$mes_ano] - $array_total_pagar_mes_ano[$mes_ano]);

        $saldo_caixa += $array_total_receber_mes_ano[$mes_ano] - $array_total_pagar_mes_ano[$mes_ano];
    }

    $dados = array(        
        'data_inicial' => converteData($data_hoje),
        'data_final' => converteData($data_ate),
        'data_criacao' => getDataHora(),
        'incluir_contas_receber_atrasadas' => $incluir_contas_receber_atrasadas,
        'dias_conta_receber_atrasadas' => $dias_descarte,
        'considerar_contratos' => $considerar_contratos,
        'id_usuario' => $_SESSION['id_usuario']
    );
    $id_fluxo_caixa = DBCreate('', 'tb_fluxo_caixa', $dados, true);
    registraLog('Inserção de novo fluxo de caixa.','i','tb_fluxo_caixa',$id_fluxo_caixa,"");

    foreach($caixas as $id_caixa){
        $dados = array(                    
            'id_fluxo_caixa' => $id_fluxo_caixa,
            'id_caixa' => $id_caixa
        );
        DBCreate('', 'tb_fluxo_caixa_caixa', $dados);
    }

    foreach ($array_total_pagar_mes_ano as $mes_ano => $valor) {
        $dados = array(        
            'id_fluxo_caixa' => $id_fluxo_caixa,
            'tipo_grafico' => 'mensal',
            'tipo_dado' => 'conta_pagar',
            'mes_ano' => $mes_ano,
            'valor' => $valor
        );
        DBCreate('', 'tb_fluxo_caixa_dados', $dados);
    }
    
    foreach ($array_total_pagar_dia_mes_ano as $mes_ano => $conteudo_dia_mes) {
        foreach ($conteudo_dia_mes as $dia_mes => $valor) {
            $dados = array(        
                'id_fluxo_caixa' => $id_fluxo_caixa,
                'tipo_grafico' => 'diario',
                'tipo_dado' => 'conta_pagar',
                'mes_ano' => $mes_ano,
                'dia_mes' => $dia_mes,
                'valor' => $valor
            );
            DBCreate('', 'tb_fluxo_caixa_dados', $dados);
        }        
    }
    
    foreach ($array_total_receber_mes_ano as $mes_ano => $valor) {
        $dados = array(        
            'id_fluxo_caixa' => $id_fluxo_caixa,
            'tipo_grafico' => 'mensal',
            'tipo_dado' => 'conta_receber',
            'mes_ano' => $mes_ano,
            'valor' => $valor
        );
        DBCreate('', 'tb_fluxo_caixa_dados', $dados);
    }
    
    foreach ($array_total_receber_dia_mes_ano as $mes_ano => $conteudo_dia_mes) {
        foreach ($conteudo_dia_mes as $dia_mes => $valor) {
            $dados = array(        
                'id_fluxo_caixa' => $id_fluxo_caixa,
                'tipo_grafico' => 'diario',
                'tipo_dado' => 'conta_receber',
                'mes_ano' => $mes_ano,
                'dia_mes' => $dia_mes,
                'valor' => $valor
            );
            DBCreate('', 'tb_fluxo_caixa_dados', $dados);
        }        
    }
    
    foreach ($array_saldo_mes_ano as $mes_ano => $valor) {
        $dados = array(        
            'id_fluxo_caixa' => $id_fluxo_caixa,
            'tipo_grafico' => 'mensal',
            'tipo_dado' => 'saldo',
            'mes_ano' => $mes_ano,
            'valor' => $valor
        );
        DBCreate('', 'tb_fluxo_caixa_dados', $dados);
    }
   
    foreach ($array_saldo_dia_mes_ano as $mes_ano => $conteudo_dia_mes) {
        foreach ($conteudo_dia_mes as $dia_mes => $valor) {
            $dados = array(        
                'id_fluxo_caixa' => $id_fluxo_caixa,
                'tipo_grafico' => 'diario',
                'tipo_dado' => 'saldo',
                'mes_ano' => $mes_ano,
                'dia_mes' => $dia_mes,
                'valor' => $valor
            );
            DBCreate('', 'tb_fluxo_caixa_dados', $dados);
        }        
    }
  
    foreach ($array_resultado_mes_ano as $mes_ano => $valor) {
        $dados = array(        
            'id_fluxo_caixa' => $id_fluxo_caixa,
            'tipo_grafico' => 'mensal',
            'tipo_dado' => 'resultado',
            'mes_ano' => $mes_ano,
            'valor' => $valor
        );
        DBCreate('', 'tb_fluxo_caixa_dados', $dados);
    }
  
    foreach ($array_resultado_dia_mes_ano as $mes_ano => $conteudo_dia_mes) {
        foreach ($conteudo_dia_mes as $dia_mes => $valor) {
            $dados = array(        
                'id_fluxo_caixa' => $id_fluxo_caixa,
                'tipo_grafico' => 'diario',
                'tipo_dado' => 'resultado',
                'mes_ano' => $mes_ano,
                'dia_mes' => $dia_mes,
                'valor' => $valor
            );
            DBCreate('', 'tb_fluxo_caixa_dados', $dados);
        }        
    }

    $alert = ('Fluxo de caixa gerado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=relatorio-fluxo-caixa&visualizar=$id_fluxo_caixa");
    exit;    
}

function excluir($id){
    DBDelete('','tb_fluxo_caixa',"id_fluxo_caixa = '$id'");
    registraLog('Exclusão de fluxo de caixa.','e','tb_fluxo_caixa',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=relatorio-fluxo-caixa");
    exit;
}

?>