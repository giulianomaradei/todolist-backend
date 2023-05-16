<?php
require_once(__DIR__."/../class/System.php");
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$nome_grupo = (!empty($_POST['nome_grupo'])) ? $_POST['nome_grupo'] : '';
$nome_proxy = (!empty($_POST['nome_proxy'])) ? $_POST['nome_proxy'] : '';
$template = (!empty($_POST['template'])) ? $_POST['template'] : 'Template ICMP Ping - Timeout';
$versao = (!empty($_POST['versao'])) ? $_POST['versao'] : '3.0';
$ext = substr($_FILES['filename']['name'], strrpos($_FILES['filename']['name'], '.'));

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-4  col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Conversor de CSV para XML (Zabbix):</h3>
                </div>
                <form enctype='multipart/form-data' id="form" action='' method='POST'>
                    <div class="panel-body" style="padding-bottom: 0;">       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome do Grupo:</label>
                                    <input name="nome_grupo" autofocus id="nome_grupo" type="text" class="form-control input-sm" value="<?= $nome_grupo; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome do Proxy:</label>
                                    <input name="nome_proxy" id="nome_proxy" type="text" class="form-control input-sm" value="<?= $nome_proxy; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Template:</label>
                                    <input name="template" id="template" type="text" class="form-control input-sm" value="<?= $template; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Versão do Zabbix:</label>
                                    <input name="versao" id="versao" type="text" class="form-control input-sm" value="<?= $versao; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>                
                        <div class="row">       
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input size='50' type='file' id="ImagemUpload" name='filename' required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-refresh"></i> Converter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>    

    <div id="resultado" class="row" style="display:none;">	
		<?php 
		if($gerar){			
            if(is_uploaded_file($_FILES['filename']['tmp_name']) && $ext == ".csv"){
                $datetime = (new DateTime(getDataHora()))->format('Y-m-d\TH:i:s\Z');
                echo "<div class='row'>";
                echo "<div class='col-md-12'>";
                echo '<textarea class="form-control" readonly id="xml-textarea" rows="15">';
                    echo '
<?xml version="1.0" encoding="UTF-8"?>
<zabbix_export>
    <version>'.$versao.'</version>
    <date>'.$datetime.'</date>
    <groups>
        <group>
            <name>'.$nome_grupo.'</name>
        </group>
    </groups>
    <hosts>';
                
                $handle = fopen($_FILES['filename']['tmp_name'], "r");

                while(($data = fgetcsv($handle, 1000, ';')) !== FALSE){
                    $data = implode(",", $data);
                    $data = str_replace(',', ';', $data);
                    $data = explode(";", $data);
                    $nome = $data[0];
                    $ip = $data[1];
                    if(!mb_detect_encoding($nome, 'UTF-8', true)){
                        $nome = utf8_encode($data[0]);
                        $ip = utf8_encode($data[1]);
                    }
                    
                    $nome = preg_replace("/[^A-Za-z0-9\.]/" ,"-",removeAcentos(str_replace(' ', '', $nome)));
                    $ip = str_replace(' ', '', $ip);

                    echo '
        <host>
            <host>'.$nome.'</host>
            <name>'.$nome.'</name>
            <description/>
            <proxy>
                <name>'.$nome_proxy.'</name>
            </proxy>
            <status>0</status>
            <ipmi_authtype>-1</ipmi_authtype>
            <ipmi_privilege>2</ipmi_privilege>
            <ipmi_username/>
            <ipmi_password/>
            <tls_connect>1</tls_connect>
            <tls_accept>1</tls_accept>
            <tls_issuer/>
            <tls_subject/>
            <tls_psk_identity/>
            <tls_psk/>
            <templates>
                <template>
                    <name>'.$template.'</name>
                </template>
            </templates>
            <groups>
                <group>
                    <name>'.$nome_grupo.'</name>
                </group>
            </groups>
            <interfaces>
                <interface>
                    <default>1</default>
                    <type>2</type>
                    <useip>1</useip>
                    <ip>'.$ip.'</ip>
                    <dns/>
                    <port>161</port>
                    <bulk>1</bulk>
                    <interface_ref>if1</interface_ref>
                </interface>
            </interfaces>
            <applications/>
            <items/>
            <discovery_rules/>
            <macros/>
            <inventory/>
        </host>';    
                }
                fclose($handle);
                echo '
    </hosts>
</zabbix_export>';
            echo '</textarea>';
            echo "</div>";
            echo "</div>";
            echo '
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <br>
                    <button class="btn btn-warning" value="1" type="button" onclick="copyXmlTextarea()"><i class="fa fa-clone"></i> Copiar</button>
                </div>
            </div>
            ';
            }
		}
		?>
	</div>
</div>

<script>
$(document).ready(function(){
    $('#aguarde').hide();
    $('#resultado').show();
    $("#gerar").prop("disabled", false);
});

function copyXmlTextarea() {
  var copyText = document.getElementById("xml-textarea");
  copyText.select();
  document.execCommand("copy");
  alert("Conteúdo copiado!");
}
$(document).on('submit', 'form', function () {
    var value = $('#ImagemUpload').val();
    var values = value.split('.'); 
    if (values.length > 0 && values[values.length - 1] != 'csv'){
      alert ('Formato inválido!');
      return false;
    }
    modalAguarde();
  });
</script>