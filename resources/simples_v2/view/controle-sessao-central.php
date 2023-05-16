<?php
require_once(__DIR__."/../class/System.php");

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/date-euro.js"></script>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Controle de sessão:</h3>
                <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=controle-sessao-central"><button class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i> Atualizar</button></a></div>
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-12">
                    <?php
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
                        $nomes_agents = array();
                        $ligacao_agents = array();
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
                            $dados_agents[$cont]['em_ligacao'] = 0; 
                        }else{
                            $dados_agents[$cont]['em_ligacao'] = 1; 
                        }       
                        $dados_agents[$cont]['agent'] = $conteudo_agents['agent'];
                        $dados_agents[$cont]['sip'] = $conteudo_agents['sip'];
                        $dados_agents[$cont]['nome'] = $conteudo_agents['nome'];
                        $dados_agents[$cont]['pausa'] = $conteudo_agents['pausa'];                                 
                        $cont++;
                        }
                        
                        if($dados_agents){
                            foreach ($dados_agents as $chave => $conteudo){
                                $nomes_agents[$chave] = $conteudo['nome'];
                            }
                            array_multisort($nomes_agents, SORT_ASC, $dados_agents);
                        }
                        echo '<div class="text-center"><strong>Atendentes logados ('.$cont.')</strong></div>';
                        echo '<table class="table table-hover dataTable" >
                                <thead>
                                    <th class="col-md-2">PA</th>
                                    <th class="col-md-6">Atendente</th>
                                    <th class="col-md-2">Status</th>
                                    <th class="col-md-2 text-center">Opção</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        foreach ($dados_agents as $conteudo) {
                        $agent = $conteudo['agent'];
                        $sip = $conteudo['sip'];
                        $nome = $conteudo['nome']; 
                        $class = 'success';
                        $status = 'Disponível';
                        if($conteudo['pausa']){
                            $class = 'warning'; 
                            $status = 'Pausa';
                        }
                        if($conteudo['em_ligacao']){
                            $class = 'info'; 
                            $status = 'Em ligação';
                            $opcao = '<a href="class/AtendenteCentral.php?desligar_chamada='.$agent.'" onclick="if (!confirm(\'Tem certeza que deseja desligar a chamada de '.$conteudo['nome'].'?\')) { return false; } else { modalAguarde(); }"><button class="btn btn-xs btn-warning"><i class="fas fa-phone-slash"></i> Desligar chamada</button></a>';
                        }else{
                            $opcao = '<a href="class/AtendenteCentral.php?logoff_forcado='.$agent.'" onclick="if (!confirm(\'Tem certeza que deseja deslogar '.$conteudo['nome'].'?\')) { return false; } else { modalAguarde(); }"><button class="btn btn-xs btn-danger"><i class="fa fa-sign-out"></i> Deslogar</button></a>';
                        } 
                        echo "<tr class=\"$class\">                
                                <td>".substr($sip, -3)."</td> 
                                <td>$nome</td>                         
                                <td>$status</td>                          
                                <td class=\"text-center\">$opcao</td>                          
                            </tr>";        
                        }
                        echo '</tbody></table>';    
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
            },
            aaSorting: [[1, 'asc']],
            "searching": false,
            "paging":   false,
            "info":     false
        });
    });
</script>