<?php
require_once(__DIR__ . "/System.php");
//require_once(__DIR__ . "/TesteEscala.php");
$tipo  = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$data_hoje = explode('-',getDataHora('data'));
// $data_hoje = explode('-', '2021-04-03');


if ($tipo == 'grupos') {
    grupos();

} else if ($tipo == 'operadores') {
    operadores();

} else if ($tipo == 'proximos_operadores') {
    proximos_operadores();
}


function grupos(){
    
    $grupos = DBRead('','tb_grupo_atendimento_chat','WHERE status = 1 ORDER BY nome ASC'); // verificar status
    
    echo '
        <table class="table table-bordered" id="table_rgrupos" style="margin-bottom: 0;">
            <tr>';
                foreach ($grupos as $conteudo) {
                    echo '
                        <th class="text-center active" style="min-width: 420px; font-size: 20px;"> &nbsp<i class="fa fa-comment" style="color: '.$conteudo['cor'].'"></i>&nbsp'.$conteudo['nome'].'</th>
                    ';
                }
    echo '</tr>
            <tbody style="font-size: 34px;">
    ';

    if ($grupos) {
        
        $nomes = array();
        
        foreach ($grupos as $conteudo) {

            $empresas = DBRead('', 'tb_grupo_atendimento_chat_contrato a', 'INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = "'.$conteudo['id_grupo_atendimento_chat'].'" LIMIT 5 ', 'c.nome');
            
            //var_dump($empresas);

            $string = '';
            foreach ($empresas as $nome) {
                $string .= $nome['nome'].PHP_EOL;
            }

            $nomes[] = $string;
        }

        foreach ($nomes as $conteudo) {
            echo "<td class='text-center' style='font-size: 21px;'>".Nl2br($conteudo)."</td>";
        }
        echo "<tr>";

    } else {
        echo '<tr><td class="text-center warning" colspan="2" style="padding:0px;">Não há grupos de atendimento de texto!</td></tr>';
    }
    echo "
            </tbody> 
        </table>
    ";
}

function operadores(){

    $data_hoje = getDataHora();
    $data_convertida = converteDataHora($data_hoje);
    
    $operadores = DBRead('','tb_usuario a','INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_grupo_atendimento_chat_operador c ON a.id_usuario = c.id_usuario ORDER BY b.nome ASC', "DISTINCT a.id_usuario, b.nome");
    
    echo '
        <table class="table table-bordered" id="table_operadores" style="margin-bottom: 0;"> 
            <tr>
                <th class="text-center success">Operadores em escala no momento (Última atualização: '.$data_convertida.')</th>
            </tr>
            <tbody style="font-size: 34px;">
    ';

    if ($operadores) {

        $array_cores = array();
        $cont = 0;
        
        foreach ($operadores as $usuario) {

            $dados = DBRead('', 'tb_grupo_atendimento_chat_operador a', "INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$usuario['id_usuario']."' AND b.status = 1 ");

            $array_cores[$usuario['id_usuario']]['nome'][] = $usuario['nome'];
            $array_cores[$usuario['id_usuario']]['id_usuario'][] = $usuario['id_usuario'];
            
            if ($dados) {

                if(sizeof($dados) == 1) {
                    $array_cores[$usuario['id_usuario']]['cor'][] = $dados[0]['cor'];
    
                } else if (sizeof($dados) >= 2) {
                    foreach ($dados as $conteudo) {
                        $array_cores[$usuario['id_usuario']]['cor'][] = $conteudo['cor'];
                    }
                }

                $cont++;
            }
        }

        //var_dump($array_cores);

        $cont = 0;
        foreach ($array_cores as $conteudo) {

            //if verificar escala na funcao

            //var_dump($conteudo['id_usuario'][0], $data_hoje);
            
            $result = verificaEscala($conteudo['id_usuario'][0], $data_hoje);

            //var_dump($result);

            if ($result == true) {
                $cont++;
                $icone = '';
                foreach ($conteudo['cor'] as $c1) {
                    $icone.=  '<i class="fa fa-comment" style="font-size: 23px; color: '.$c1.'"></i>&nbsp';
                }
                
                foreach ($conteudo['nome'] as $c2) {
                    //var_dump($c);
                    echo '
                    <tr>
                        <td> &nbsp</i>&nbsp  <span style="font-size: 23px"><i class="fa fa-circle" style="color: green; font-size: 10px;"></i> &nbsp'.$c2.'</span>  <span class="pull-right">'.$icone.'</span> </td>
                    </tr>
                    ';
                }
            }
        }

        if ($cont == 0) {
            echo '<tr><td class="text-center warning" colspan="2" style="padding:0px; font-size: 27px;">Não há operadores em escala no momento!</td></tr>';
        }

    } else {
        echo '<tr><td class="text-center warning" colspan="2" style="padding:0px; font-size: 27px;">Não há operadores vinculados aos grupos de atendimento de texto!</td></tr>';
    }
    echo "
            </tbody> 
        </table>
    ";
}

function proximos_operadores(){

    $data_hoje = getDataHora(); // horario + 15 min
    $time = new DateTime($data_hoje);
    $time->add(new DateInterval('PT' . 15 . 'M'));
    $data_proxima = $time->format('Y-m-d H:i');

    $data_convertida = converteDataHora($data_proxima);
    
    $operadores = DBRead('','tb_usuario a','INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_grupo_atendimento_chat_operador c ON a.id_usuario = c.id_usuario ORDER BY b.nome ASC', "DISTINCT a.id_usuario, b.nome");
    
    echo '
        <table class="table table-bordered" id="table_proximos_operadores" style="margin-bottom: 0;"> 
            <tr>
                <th class="text-center info">Próximo(s) na escala em 15 minutos ('.$data_convertida.')</th>
            </tr>
            <tbody style="font-size: 34px;">
    ';

    if ($operadores) {

        $array_cores = array();
        $cont = 0;
        
        foreach ($operadores as $usuario) {

            $dados = DBRead('', 'tb_grupo_atendimento_chat_operador a', "INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$usuario['id_usuario']."' AND b.status = 1 ");

            $array_cores[$usuario['id_usuario']]['nome'][] = $usuario['nome'];
            $array_cores[$usuario['id_usuario']]['id_usuario'][] = $usuario['id_usuario'];
            
            if ($dados) {

                if(sizeof($dados) == 1) {
                    $array_cores[$usuario['id_usuario']]['cor'][] = $dados[0]['cor'];
    
                } else if (sizeof($dados) >= 2) {
                    foreach ($dados as $conteudo) {
                        $array_cores[$usuario['id_usuario']]['cor'][] = $conteudo['cor'];
                    }
                }

                $cont++;
            }
        }

        //var_dump($array_cores);

        $cont = 0;
        foreach ($array_cores as $conteudo) {

            //if verificar escala na funcao
            $result = verificaEscala($conteudo['id_usuario'][0], $data_proxima);

            if ($result == true) {
                $cont++;
                $icone = '';
                foreach ($conteudo['cor'] as $c1) {
                    $icone.=  '<i class="fa fa-comment" style="font-size: 23px; color: '.$c1.'"></i>&nbsp';
                }
                
                foreach ($conteudo['nome'] as $c2) {
                    //var_dump($c);
                    echo '
                    <tr>
                        <td> &nbsp</i>&nbsp <span style="font-size: 23px">'.$c2.'</span>  <span class="pull-right">'.$icone.'</span> </td>
                    </tr>
                    ';
                }
            }
        }

        if ($cont == 0) {
            echo '<tr><td class="text-center warning" colspan="2" style="padding:0px; font-size: 27px;">Não há operadores em escala no horário das '.$data_convertida.'!</td></tr>';
        }

    } else {
        echo '<tr><td class="text-center warning" colspan="2" style="padding:0px; font-size: 27px;">Não há operadores vinculados aos grupos de atendimento de texto!</td></tr>';
    }
    echo "
            </tbody> 
        </table>
    ";
}


?>