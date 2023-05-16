<?php
require_once(__DIR__."/System.php");
$tipo  = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
if($tipo == 'entrada'){
    entrada();
}elseif($tipo == 'agents'){
    agents();
}elseif($tipo == 'saida'){
    saida();
}elseif($tipo == 'espera'){
    espera();
}

function agents(){
    $dados_entradas = array();
    $cont = 0;
    $retorno_saidas = troca_dados_curl("http://172.31.18.211/central_simples/retorna_saidas.php");
    $retorno_entradas = troca_dados_curl("http://172.31.18.211/central_simples/retorna_entradas.php");
    if($retorno_entradas['sucesso']){
        foreach ($retorno_entradas['dados'] as $conteudo) {
            if($conteudo['local'] == '(Outgoing Line)'){
                foreach ($retorno_entradas['dados'] as $conteudo_par) {
                    if(($conteudo['ponte'] == $conteudo_par['ponte']) && ($conteudo_par['local'] != '(Outgoing Line)')){
                        $fila = explode(",", $conteudo_par['local']);
                        $fila = $fila[0];
                        $dados_entradas[$cont]['sip'] = $conteudo['sip'];
                        $dados_entradas[$cont]['nome'] = $conteudo['nome'];
                        $dados_entradas[$cont]['fila'] = $fila;
                        $dados_entradas[$cont]['tempo'] = $conteudo['tempo'];
                        $cont++;
                        break;
                    }
                }
            }        
        }    
    }
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");
    if($retorno_agents['sucesso']){
        $dados_agents = array();
        $nomes_pausas_agents = array();
        $pausa_agents = array();
        $cont = 0;    
        foreach ($retorno_agents['dados'] as $conteudo_agents) {
            $flag = 0;
            foreach($dados_entradas as $conteudo_entradas){
                if($conteudo_entradas['sip'] == $conteudo_agents['sip']){
                    $flag++;
                    break;
                }
            }
            foreach($retorno_saidas['dados'] as $conteudo_saidas){
                if($conteudo_saidas['sip'] == $conteudo_agents['sip']){
                    $flag++;
                    break;
                }
            }
            if($flag == 0){
                $dados_agents[$cont]['agent'] = $conteudo_agents['agent'];
                $dados_agents[$cont]['sip'] = $conteudo_agents['sip'];
                $dados_agents[$cont]['nome'] = $conteudo_agents['nome'];
                $dados_agents[$cont]['pausa'] = $conteudo_agents['pausa'];
                if($conteudo_agents['pausa']){
                    $dados_pausa = DBRead('snep', 'queue_agents_pause a',"INNER JOIN tipo_pausa b ON a.tipo_pausa = b.id WHERE a.codigo = '".$conteudo_agents['agent']."' ORDER BY a.data_pause DESC LIMIT 1", "a.*, b.nome");
                    $dados_agents[$cont]['nome_pausa'] = $dados_pausa[0]['nome'];
                    $dados_agents[$cont]['data_pausa'] = $dados_pausa[0]['data_pause'];
                }else{
                    $dados_agents[$cont]['nome_pausa'] = '';
                    $dados_agents[$cont]['data_pausa'] = '';
                }
                $cont++;
            }       
                    
        }
        
        if($dados_agents){
            foreach ($dados_agents as $chave => $conteudo){
                $nomes_pausas_agents[$chave] = $conteudo['nome_pausa'];
                $pausa_agents[$chave] = $conteudo['pausa'];
            }
            array_multisort($pausa_agents, SORT_ASC, $nomes_pausas_agents, SORT_ASC, $dados_agents);
        }
              
        echo '<table class="table table-bordered" style="margin-bottom: 5px;">
                <thead style="font-size: 14px;">
                    <tr>
                    <th class="text-center" colspan="4">Atendentes ('.sizeof($dados_agents).')</th>
                    </tr>
                    <tr>
                    <th>PA</th>
                    <th>Atendente</th>
                    <th>Status</th>
                    <th>Tempo</th>
                    </tr>
                </thead>
                <tbody style="font-size: 20px;">';
        foreach ($dados_agents as $conteudo) {
            $class = 'success';
            $status = 'Disponível';
            $tempo = '';
            if($conteudo['pausa']){
                $class = 'warning';            
                $duracao = strtotime(getDataHora()) - strtotime($conteudo['data_pausa']);
                if($duracao > 300 && $conteudo['nome_pausa'] != 'Banheiro'){
                    $class = 'danger';
                }
                if($conteudo['nome_pausa'] == 'Ativo'){
                    $status = 'Realizando Ativo';
                    $class = 'info';
                }else{
                    $status = $conteudo['nome_pausa'];
                }
                if($conteudo['nome_pausa'] != 'Banheiro'){
                    $tempo = converteSegundosHoras($duracao);
                }
            }
            $sip = $conteudo['sip'];
            $nome = $conteudo['nome']; 
            echo "<tr class=\"$class\">                
                    <td>".$sip."</td> 
                    <td>".$nome."</td>                         
                    <td>".$status."</td>                          
                    <td>".$tempo."</td>                          
                </tr>";        
        }
        echo '</tbody></table>';    
    }
}

function entrada(){
    $dados_entradas = array();
    $cont = 0;
    $retorno_entradas = troca_dados_curl("http://172.31.18.211/central_simples/retorna_entradas.php");
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");
    if($retorno_entradas['sucesso']){
        if($retorno_entradas['dados']){
            foreach ($retorno_entradas['dados'] as $chave => $conteudo_entradas){
                $tempo_entradas[$chave] = $conteudo_entradas['tempo'];
            }
            array_multisort($tempo_entradas, SORT_DESC, $retorno_entradas['dados']);
        }
        
        foreach ($retorno_entradas['dados'] as $conteudo_entradas) {
            if($conteudo_entradas['local'] == '(Outgoing Line)'){
                foreach ($retorno_entradas['dados'] as $conteudo_par) {
                    if(($conteudo_entradas['ponte'] == $conteudo_par['ponte']) ){
                        $prefix = explode("-", $conteudo_par['callerid']);
                        $prefix = $prefix[0];
                        $dados_entradas[$cont]['sip'] = $conteudo_entradas['sip'];
                        $dados_entradas[$cont]['prefix'] = $prefix;
                        $dados_entradas[$cont]['tempo'] = $conteudo_entradas['tempo'];
                        foreach ($retorno_agents['dados'] as $conteudo_agents) {
                            if($conteudo_agents['sip'] == $conteudo_entradas['sip']){
                                $dados_entradas[$cont]['agent'] = $conteudo_agents['agent'];
                                $dados_entradas[$cont]['nome'] = $conteudo_agents['nome'];
                                break;
                            }else{
                                $dados_entradas[$cont]['agent'] = '';
                                $dados_entradas[$cont]['nome'] = '';
                            }
                        }
                        
                        $cont++;
                        break;
                    }
                }
            }        
        }

        echo '<table class="table table-bordered" style="margin-bottom: 5px;">
                <thead style="font-size: 14px;">
                    <tr>
                      <th class="text-center" colspan="5">Em receptivo ('.sizeof($dados_entradas).')</th>
                    </tr>
                    <tr>
                      <th>PA</th>
                      <th>Atendente</th>
                      <th>Empresa</th>
                      <th>Tempo</th>
                    </tr>
                </thead>
                <tbody style="font-size: 20px;">';
        foreach ($dados_entradas as $conteudo) {   
            $cor = pega_cor_tempo($conteudo['tempo']);
            $sip = $conteudo['sip'];
            $nome = $conteudo['nome'];
            $prefix = $conteudo['prefix'];
            $dados_emp = DBRead('','tb_parametros a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_asterisk = '$prefix'",'c.nome, b.nome_contrato, b.id_contrato_plano_pessoa, b.id_plano');
            if($dados_emp){
                if($dados_emp[0]['nome_contrato']){
                    $nome_empresa = $dados_emp[0]['nome']." (".$dados_emp[0]['nome_contrato'].")";
                }else{
                    $nome_empresa = $dados_emp[0]['nome'];
                }      
                $id_contrato_plano_pessoa = $dados_emp[0]['id_contrato_plano_pessoa'];      
                $id_plano = $dados_emp[0]['id_plano'];      
            }else{
                $nome_empresa = '';
                $id_contrato_plano_pessoa = '';
                $id_plano = '';
            }    
            
            if($nome && $nome != ''){
                echo "
                <tr $cor>
                    <td>".$sip."</td>
                    <td>".$nome."</td>
                    <td>".$nome_empresa."</td>
                    <td>".gmdate("H:i:s", $conteudo['tempo'])."</td>          
                </tr>";
            }
            // AJUDAS AUTOMÁTICAS
            // pausa_automatica($conteudo['agent'], $conteudo['tempo'], $id_contrato_plano_pessoa, $id_plano);
            
        }
        echo '</tbody></table>'; 
    }   
}

function saida(){
    $retorno_saidas = troca_dados_curl("http://172.31.18.211/central_simples/retorna_saidas.php");
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");
    if($retorno_saidas['sucesso']){
        if($retorno_saidas['dados']){
            foreach ($retorno_saidas['dados'] as $chave => $conteudo){
                $tempo_saidas[$chave] = $conteudo['tempo'];
            }
            array_multisort($tempo_saidas, SORT_DESC, $retorno_saidas['dados']);
        }
       
        echo '<table class="table table-bordered" style="margin-bottom: 5px;">
                <thead style="font-size: 14px;">
                    <tr>
                    <th class="text-center" colspan="5">Em ativo ('.sizeof($retorno_saidas['dados']).')</th>
                    </tr>
                    <tr>
                    <th>PA</th>
                    <th>Atendente</th>
                    <th>Destino</th>
                    <th>Tempo</th>
                    </tr>
                </thead>
                <tbody style="font-size: 20px;">';
        foreach ($retorno_saidas['dados'] as $conteudo) {
            
            $cor = pega_cor_tempo($conteudo['tempo']);
            $sip = $conteudo['sip'];

            foreach ($retorno_agents['dados'] as $conteudo_agents) {
                if($conteudo_agents['sip'] == $conteudo['sip']){
                    $nome = $conteudo_agents['nome'];
                    break;
                }else{
                    $nome = '';
                }
            }        
            echo "<tr $cor>
            <td>".$sip."</td>
            <td>".$nome."</td>
            <td>".$conteudo['destino']."</td>
            <td>".gmdate("H:i:s", $conteudo['tempo'])."</td>          
            </tr>";
        }
        echo '</tbody></table>';
    }
}

function espera(){
    $retorno_espera = troca_dados_curl("http://172.31.18.211/central_simples/retorna_espera.php");
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");
    if($retorno_espera['sucesso']){
        if($retorno_espera['dados']){
            foreach ($retorno_espera['dados'] as $chave => $conteudo){
                $tempo[$chave] = $conteudo['tempo'];
            }
            array_multisort($tempo, SORT_DESC, $retorno_espera['dados']);
        }
        
        
        echo '<table class="table table-bordered" style="margin-bottom: 5px;">
                <thead style="font-size: 14px;">
                    <tr>
                    <th class="text-center" colspan="6">Em espera ('.sizeof($retorno_espera['dados']).')</th>
                    </tr>
                    <tr>
                    <th>PA</th>
                    <th>Atendente</th>
                    <th>Empresa</th>
                    <th>Posição</th>
                    <th>Grupo</th>
                    <th>Tempo</th>
                    </tr>
                </thead>
                <tbody style="font-size: 20px;">';
        foreach ($retorno_espera['dados'] as $conteudo) {
            $cor = pega_cor_tempo($conteudo['tempo']);
            $prefix = explode('-', $conteudo['callerid']);
            $prefix = $prefix[0];
            foreach ($retorno_agents['dados'] as $conteudo_agents) {               
                if($conteudo_agents['sip'] == $conteudo['destino']){       
                    $nome = $conteudo_agents['nome'];
                    $sip = $conteudo_agents['sip'];
                    break;
                }else{
                    $nome = '';
                    $sip = '';
                }
            }
            $dados_emp = DBRead('','tb_parametros a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_asterisk = '$prefix'",'c.nome, b.nome_contrato');
            if($dados_emp){
                if($dados_emp[0]['nome_contrato']){
                    $nome_empresa = $dados_emp[0]['nome']." (".$dados_emp[0]['nome_contrato'].")";
                }else{
                    $nome_empresa = $dados_emp[0]['nome'];
                }                
            }else{
                $nome_empresa = '';
            }
            if(strtolower($conteudo['fila']) == 'callatendimentonormal'){
                $prioridade = 'B';
            }else if(strtolower($conteudo['fila']) == 'callatendimentobaixa'){
                $prioridade = 'A';
            }else if(strtolower($conteudo['fila']) == 'callatendimentoalta'){
                $prioridade = 'C';
            }else if(strtolower($conteudo['fila']) == 'callatendimentovip'){
                $prioridade = 'V';
            }else if(strtolower($conteudo['fila']) == 'callatendimentonotificacaoparada'){
                $prioridade = 'X';
            }else if(strtolower($conteudo['fila']) == 'callatendimentonormalexp'){
                $prioridade = 'B (EXP)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentobaixaexp'){
                $prioridade = 'A (EXP)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentoaltaexp'){
                $prioridade = 'C (EXP)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentovipexp'){
                $prioridade = 'V (EXP)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentonotificacaoparadaexp'){
                $prioridade = 'X (EXP)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentonormalext'){
                $prioridade = 'B (EXT)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentobaixaext'){
                $prioridade = 'A (EXT)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentoaltaext'){
                $prioridade = 'C (EXT)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentovipext'){
                $prioridade = 'V (EXT)';
            }else if(strtolower($conteudo['fila']) == 'callatendimentonotificacaoparadaext'){
                $prioridade = 'X (EXT)';
            }else{
                $prioridade = 'N/D';
            }
            echo "<tr $cor>
                    <td>".$sip."</td>
                    <td>".$nome."</td>
                    <td>".$nome_empresa."</td>
                    <td>".$conteudo['posicao']."</td>
                    <td>".$prioridade."</td>
                    <td>".gmdate("H:i:s", $conteudo['tempo'])."</td>          
                </tr>";        
        }
        echo '</tbody></table>';
    }
}

function pega_cor_tempo($tempo) {    
    if ($tempo < 300) { // menor que 5 minutos
        return 'class="success"';
    } else if ($tempo >= 300 && $tempo < 600) { // entre 5 e 10 minutos
        return 'class="warning"';
    } else if ($tempo >= 600 && $tempo < 900) { // entre 10 e 15 minutos
        return 'class="danger"';
    } else if ($tempo >= 900 && $tempo < 1200) { // entre 15 e 20 minutos
        return 'style="background-color:red; color:white;"';        
    } else if ($tempo >= 1200) { // maior que 20 minutos
        return 'style="background-color:black; color:white;"';
    }
}

// AJUDAS AUTOMÁTICAS
// function pausa_automatica($agent, $segundos_chamada, $id_contrato_plano_pessoa, $id_plano){
//     $segundos_ajuda = 900; //15 minutos
//     if($agent && $id_contrato_plano_pessoa && $id_plano && $segundos_chamada > $segundos_ajuda){
//         $data_agora = getDataHora();
//         $data_ajuda = date('Y-m-d H:i:s', strtotime("-$segundos_ajuda seconds",strtotime($data_agora)));    

//         $dados_usuario = DBRead('', 'tb_usuario', "WHERE id_asterisk = '$agent'");

//         if($dados_usuario){
//             $id_usuario = $dados_usuario[0]['id_usuario'];

//             $dados_verifica_ajuda = DBRead('','tb_solicitacao_ajuda', "WHERE atendente = '$id_usuario' AND ((data_inicio >= '$data_ajuda' AND data_inicio <= '$data_agora') OR (data_encerramento IS NULL))");

//             if(!$dados_verifica_ajuda){

//                 $dados = array(
//                     'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
//                     'id_atendimento' => 0,
//                     'data_inicio' => $data_agora,
//                     'atendente' => $id_usuario,
//                     'id_plano' => $id_plano,
//                     'ajuda_automatica' => 1
//                 );

//                 $insertID = DBCreate('', 'tb_solicitacao_ajuda', $dados, true);
//                 registraLog('Solicitação de ajuda automática criada.','i','tb_solicitacao_ajuda', $insertID, "id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_inicio: $data_agora | atendente: $id_usuario | id_plano: $id_plano | ajuda_automatica: 1");
//             }
//         }        
//     }
// }
?>