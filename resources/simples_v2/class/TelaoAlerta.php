<?php
require_once(__DIR__ . "/System.php");

    $data_hoje = getDataHora(); // horario + 15 min
    
    $dados_alerta = DBRead('','tb_alerta a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE data_vencimento IS NULL or data_vencimento >= '".$data_hoje."' ORDER BY c.nome ASC ", "a.*, c.nome");

    echo '
        <table class="table table-striped" id="d" style="margin-bottom: 0;"> 
            <tr>
                <th class="text-center info" colspan="4"><h2><strong>Alertas ativos ('.converteDataHora($data_hoje).')</h2></strong></th>
            </tr>
            <tbody style="font-size: 34px; margin-top: 10px;">
    ';

    if ($dados_alerta) {
        echo '
                    <tr>
                        <td> &nbsp Contrato</td>
                        <td> &nbsp Categoria</td>
                        <td> &nbsp Data de Início</td>
                        <td> &nbsp Data de Finalização</td>
                    </tr>
                    ';
        
        foreach ($dados_alerta as $conteudo_alerta) {            
            if($conteudo_alerta['data_vencimento']){
                $data_vencimento = converteDataHora($conteudo_alerta['data_vencimento']);
            }else{
                $data_vencimento = 'N/D';
            }
            $dados_categoria = DBRead('','tb_categoria',"WHERE id_categoria = '".$conteudo_alerta['id_categoria']."'", "nome");
            $categoria = $dados_categoria[0]['nome'];
            echo    '
                    <tr>
                        <td> &nbsp</i>&nbsp <span style="font-size: 23px">'.$conteudo_alerta['nome'].'</span></td>
                        <td> &nbsp</i>&nbsp <span style="font-size: 23px">'.$categoria.'</span></td>
                        <td> &nbsp</i>&nbsp <span style="font-size: 23px">'.converteDataHora($conteudo_alerta['data_inicio']).'</span></td>
                        <td> &nbsp</i>&nbsp <span style="font-size: 23px">'.$data_vencimento.'</span></td>
                    </tr>
                    ';
        }

    } else {
        echo '<tr><td class="text-center warning" colspan="2" style="padding:0px; font-size: 27px;">Não há operadores vinculados aos grupos de atendimento de texto!</td></tr>';
    }
    echo "
            </tbody> 
        </table>
    ";


?>