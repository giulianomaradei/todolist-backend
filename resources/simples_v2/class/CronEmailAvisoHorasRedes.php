<?php
require_once(__DIR__ . "/System.php");
require_once __DIR__ .'/../inc/php-mailer/Exception.php';
require_once __DIR__ .'/../inc/php-mailer/PHPMailer.php';
require_once __DIR__ .'/../inc/php-mailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = new DateTime(getDataHora('data'));
$data->modify('first day of this month');
$data_de = $data->format('Y-m-d');
$data->modify('last day of this month');
$data_ate = $data->format('Y-m-d');

$meses = array(
    "01" => "Janeiro",
    "02" => "Fevereiro",
    "03" => "Março",
    "04" => "Abril",
    "05" => "Maio",
    "06" => "Junho",
    "07" => "Julho",
    "08" => "Agosto",
    "09" => "Setembro",
    "10" => "Outubro",
    "11" => "Novembro",
    "12" => "Dezembro",
);

$mes = $meses[substr($data_de, 5,2)];


$dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametro_redes_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_usuario e ON d.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE c.cod_servico = 'gestao_redes' AND a.tipo_cobranca = 'horas' AND a.status != 5 AND (a.status = 1 OR a.data_status >= '$data_de') ORDER BY b.nome ASC", "a.*, b.nome AS 'nome_cliente', d.*, f.nome AS 'nome_responsavel'");  

if($dados_contratos){       

   

    foreach ($dados_contratos as $conteudo_contrato) {

        $dados_tempo_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 2 AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
        $tempo_atendimento = $dados_tempo_atendimentos[0]['tempo'] ? intval($dados_tempo_atendimentos[0]['tempo']) : 0;

        $dados_tempo_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
        $tempo_plantao = $dados_tempo_plantoes[0]['tempo'] ? intval($dados_tempo_plantoes[0]['tempo']) : 0;

        $porcentagem_utilizada = sprintf("%01.2f", round(($tempo_atendimento+$tempo_plantao)*100/($conteudo_contrato['qtd_contratada']*60 == 0 ? 1 : $conteudo_contrato['qtd_contratada']*60), 2));

       
        if($porcentagem_utilizada > 100){
            $quantidade_excedida = 100;

        }else if($porcentagem_utilizada >= 90){
            $quantidade_excedida = 90;

        }else if($porcentagem_utilizada >= 75){
            $quantidade_excedida = 75;

        }else if($porcentagem_utilizada >= 50){
            $quantidade_excedida = 50;
            
        }else{
            $quantidade_excedida = 0;
        }
        
        if($quantidade_excedida){
            $dados_email_aviso = DBRead('','tb_redes_email_aviso_horas',"WHERE id_contrato_plano_pessoa = '".$conteudo_contrato['id_contrato_plano_pessoa']."' AND porcentagem = '$quantidade_excedida' AND data >= '$data_de 00:00:00' AND data <= '$data_ate 23:59:59'");
            if(!$dados_email_aviso){  

                $nome = 'Belluno - Gestão de Redes';
                $usuario = 'redes@bellunotec.com.br';
                $senha = '123456500tec';

                $mensagem = "A empresa <strong>".$conteudo_contrato['nome_cliente']."</strong> excedeu <strong>$quantidade_excedida%</strong> do plano contratado em <strong>$mes</strong>.<br>- Tempo em atendimento: ".converteSegundosHoras($tempo_atendimento*60). "<br>- Tempo em plantões: ".converteSegundosHoras($tempo_plantao*60)."<br>- Total de tempo utilizando: ".converteSegundosHoras(($tempo_atendimento+$tempo_plantao)*60)."<br>- Tempo contratado: ".converteSegundosHoras($conteudo_contrato['qtd_contratada']*3600);

                $assunto =  $conteudo_contrato['nome_cliente']."  - Atualização do tempo de suporte";

                $destino = 'suporte@bellunotec.com.br';

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP ();
                    $mail->Host = "vps.bellunotec.com.br";
                    $mail->Port = 465;
                    $mail->SMTPSecure = "ssl";
                    $mail->SMTPAuth = true;
                    $mail->Username = $usuario;
                    $mail->Password = $senha;
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    //$mail->SMTPDebug = 2;
                    $mail->CharSet = 'UTF-8';
                    $mail->AddReplyTo($usuario, $nome);
                    $mail->setFrom($usuario, $nome);
                    $mail->addAddress($destino);      
                    $mail->isHTML(true);   
                    $mail->Subject = $assunto;
                    $mail->Body = $mensagem;
                    $mail->send();  
                } catch (Exception $e) {
                    echo "Erro ao enviar e-mail. Mailer Error: {$mail->ErrorInfo}";
                }

                $dados = array(
                    'id_contrato_plano_pessoa' => $conteudo_contrato['id_contrato_plano_pessoa'],
                    'porcentagem' => $quantidade_excedida,
                    'data' => getDataHora()
                );                
                $insertID = DBCreate('', 'tb_redes_email_aviso_horas', $dados, true);
                registraLog('Inserção de pessoa email de aviso de horas de Redes.','i','tb_redes_email_aviso_horas',$insertID,"mensagem: $mensagem");
            }
        }       
    }
}
?>