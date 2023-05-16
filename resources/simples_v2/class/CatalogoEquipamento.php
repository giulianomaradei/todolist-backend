<?php
require_once(__DIR__."/System.php");

$id_catalogo_equipamento_marca = (!empty($_POST['id_catalogo_equipamento_marca'])) ? $_POST['id_catalogo_equipamento_marca'] : '';
$modelo = (!empty($_POST['modelo'])) ? $_POST['modelo'] : '';
$led = (!empty($_POST['led'])) ? $_POST['led'] : '';
$porta = (!empty($_POST['porta'])) ? $_POST['porta'] : '';

if (!empty($_POST['inserir'])) {
    $modelo = strtoupper(trim($modelo));

    $dados = DBRead('', 'tb_catalogo_equipamento', "WHERE BINARY modelo = '".addslashes($modelo)."' AND id_catalogo_equipamento_marca = '".$id_catalogo_equipamento_marca."' ");
    if (!$dados) {
        inserir($id_catalogo_equipamento_marca, $modelo, $led, $porta);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
    $modelo = strtoupper(trim($modelo));
   
    $dados = DBRead('', 'tb_catalogo_equipamento', "WHERE (BINARY modelo = '".addslashes($modelo)."' AND id_catalogo_equipamento != '$id') OR (BINARY modelo = '".addslashes($modelo)."' AND id_catalogo_equipamento_marca = '".$id_catalogo_equipamento_marca."' AND id_catalogo_equipamento != '$id') ");
    if (!$dados) {
        alterar($id, $id_catalogo_equipamento_marca, $modelo, $led, $porta);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_catalogo_equipamento_marca, $modelo, $led, $porta){

    if((isset($_FILES['foto_led']['name']) && $_FILES['foto_led']['error'] == 0) && (isset($_FILES['foto_porta']['name']) && $_FILES['foto_porta']['error'] == 0)) {
                
        if (($_FILES[ 'foto_led' ][ 'size' ] > 5242880) && ($_FILES[ 'foto_porta' ][ 'size' ] > 5242880) ) {
            if(($_FILES[ 'foto_led' ][ 'size' ] > 5242880) && ($_FILES[ 'foto_porta' ][ 'size' ] > 5242880)){
                $alert = ('Tamanho das imagens dos leds e das portas excedem o tamanho limite!', 'd', 'AVISO!');
            }else if($_FILES[ 'foto_led' ][ 'size' ] > 5242880){
                $alert = ('Tamanho da imagem dos leds excede o tamanho limite!', 'd', 'AVISO!');
            }else if($_FILES[ 'foto_porta' ][ 'size' ] > 5242880){
                $alert = ('Tamanho da imagem das portas excede o tamanho limite!', 'd', 'AVISO!');
            }

            header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form");
            exit;

        }else{
            $arquivo_tmp = $_FILES['foto_led']['tmp_name'];
            $nome = $_FILES['foto_led']['name'];
            $nome_arquivo = explode('/tmp/', $arquivo_tmp);
            $nome_arquivo = $nome_arquivo[1];
        
            // Pega a extensão
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
            // Somente imagens, .jpg;.jpeg;.gif;.png

            //Porta
            $arquivo_tmp_porta = $_FILES['foto_porta']['tmp_name'];
            $nome_porta = $_FILES['foto_porta']['name'];
            $nome_arquivo_porta = explode('/tmp/', $arquivo_tmp_porta);
            $nome_arquivo_porta = $nome_arquivo_porta[1];
        
            // Pega a extensão
            $extensao_porta = pathinfo($nome_porta, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao_porta = strtolower($extensao_porta);
            // Somente imagens, .jpg;.jpeg;.gif;.png

            if ( (strstr('.jpg;.jpeg;.png', $extensao)) && (strstr('.jpg;.jpeg;.png', $extensao_porta))) {
        
                // Concatena a pasta com o nome
                $destino = '../inc/upload-catalogo-equipamento/'.$nome_arquivo.'.jpg';
                $destino_porta = '../inc/upload-catalogo-equipamento/'.$nome_arquivo_porta.'.jpg';
                //$foto_caminho = 'inc/upload-usuario/'.$novoNome;
                
                // unlink('../inc/upload-catalogo-equipamento/'.$arquivo_tmp.'.jpg');
    
                // echo 'destino: '.$destino.'<br>';
                // echo 'arquivo_tmp: '. $arquivo_tmp.'<br>';
                // echo 'nome: '. $nome_arquivo.'<br>';

                // echo 'destino: '.$destino.'<br>';
                // echo 'arquivo_tmp_porta: '. $arquivo_tmp_porta.'<br>';
                // echo 'nome_arquivo_porta: '. $nome_arquivo_porta.'<br>';

                // die();
                //tenta mover o arquivo para o destino
                if( (@move_uploaded_file ($arquivo_tmp, $destino)) && (@move_uploaded_file ($arquivo_tmp_porta, $destino_porta)) ){
                    // header("location: /api/iframe?token=$request->token&view=usuario-busca");
                    // exit;  

                    $dados = array(
                        'id_catalogo_equipamento_marca' => $id_catalogo_equipamento_marca,
                        'led' => $led,
                        'modelo' => $modelo,
                        'foto_led' => $nome_arquivo,
                        'porta' => $porta,
                        'foto_porta' => $nome_arquivo_porta
                    );
                
                    $insertID = DBCreate('', 'tb_catalogo_equipamento', $dados, true);
                    registraLog('Inserção de nova catalogo de equipamento.','i','tb_catalogo_equipamento',$insertID,"id_catalogo_equipamento_marca: $id_catalogo_equipamento_marca | led: $led | modelo: $modelo | foto_led: $nome_arquivo | porta: $porta | foto_porta: $nome_arquivo_porta");
                    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';                    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-busca");
                    exit;

                }else{
                    $alert = ('Erro ao salvar a imagem!', 'd', 'AVISO!');
                    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form");
    
                    exit;
                }
            }else{
                if ((strstr('.jpg;.jpeg;.png', $extensao)) && (strstr('.jpg;.jpeg;.png', $extensao_porta))) {
                    $alert = ('Você poderá enviar apenas imagens "*.jpg;*.jpeg;*.png" na foto dos leds e na das portas!', 'd', 'AVISO!');
                }else if(strstr('.jpg;.jpeg;.png', $extensao)){
                    $alert = ('Você poderá enviar apenas imagens "*.jpg;*.jpeg;*.png" na foto dos leds!', 'd', 'AVISO!');
                }else if(strstr('.jpg;.jpeg;.png', $extensao_porta)){
                    $alert = ('Você poderá enviar apenas imagens "*.jpg;*.jpeg;*.png" na foto na das portas!', 'd', 'AVISO!');
                }
                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form");
                exit;
            }
        }
    }else{
        $alert = ('Você deve adicionar a imagem!', 'd', 'AVISO!');
        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form");
        exit;
    }

    
}

function alterar($id, $id_catalogo_equipamento_marca, $modelo, $led, $porta){
            
    if (($_FILES[ 'foto_led' ][ 'size' ] > 5242880) && ($_FILES[ 'foto_porta' ][ 'size' ] > 5242880) ) {
        if(($_FILES[ 'foto_led' ][ 'size' ] > 5242880) && ($_FILES[ 'foto_porta' ][ 'size' ] > 5242880)){
            $alert = ('Tamanho das imagens dos leds e das portas excedem o tamanho limite!', 'd', 'AVISO!');
        }else if($_FILES[ 'foto_led' ][ 'size' ] > 5242880){
            $alert = ('Tamanho da imagem dos leds excede o tamanho limite!', 'd', 'AVISO!');
        }else if($_FILES[ 'foto_porta' ][ 'size' ] > 5242880){
            $alert = ('Tamanho da imagem das portas excede o tamanho limite!', 'd', 'AVISO!');
        }        
        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form&alterar=$id");
        exit;
    }else{
        $arquivo_tmp = $_FILES['foto_led']['tmp_name'];
        $nome = $_FILES['foto_led']['name'];
        $nome_arquivo = explode('/tmp/', $arquivo_tmp);
        $nome_arquivo = $nome_arquivo[1];
        $extensao = pathinfo($nome, PATHINFO_EXTENSION);
        $extensao = strtolower($extensao);

        //Porta
        $arquivo_tmp_porta = $_FILES['foto_porta']['tmp_name'];
        $nome_porta = $_FILES['foto_porta']['name'];
        $nome_arquivo_porta = explode('/tmp/', $arquivo_tmp_porta);
        $nome_arquivo_porta = $nome_arquivo_porta[1];
        $extensao_porta = pathinfo($nome_porta, PATHINFO_EXTENSION);
        $extensao_porta = strtolower($extensao_porta);

        if ( (strstr('.jpg;.jpeg;.png', $extensao)) && (strstr('.jpg;.jpeg;.png', $extensao_porta))) {
    
            $destino = '../inc/upload-catalogo-equipamento/'.$nome_arquivo.'.jpg';
            $destino_porta = '../inc/upload-catalogo-equipamento/'.$nome_arquivo_porta.'.jpg';

            if( (@move_uploaded_file ($arquivo_tmp, $destino)) && (@move_uploaded_file ($arquivo_tmp_porta, $destino_porta)) ){

                $dados_catalogo_equipamento_foto = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = '$id'");
                $dados_catalogo_equipamento_foto =  $dados_catalogo_equipamento_foto[0]['foto_led'];
                $dados_catalogo_equipamento_foto_porta =  $dados_catalogo_equipamento_foto[0]['foto_porta'];
                unlink('../inc/upload-catalogo-equipamento/'.$dados_catalogo_equipamento_foto.'.jpg');
                unlink('../inc/upload-catalogo-equipamento/'.$dados_catalogo_equipamento_foto_porta.'.jpg');
            
                $dados = array(
                    'id_catalogo_equipamento_marca' => $id_catalogo_equipamento_marca,
                    'led' => $led,
                    'modelo' => $modelo,
                    'foto_led' => $nome_arquivo,
                    'porta' => $porta,
                    'foto_porta' => $nome_arquivo_porta
                );

                DBUpdate('', 'tb_catalogo_equipamento', $dados, "id_catalogo_equipamento = $id");
                registraLog('Alteração de catalogo de equipamento.','a','tb_catalogo_equipamento',$id,"id_catalogo_equipamento_marca: $id_catalogo_equipamento_marca | modelo: $modelo | led: $led | foto: $nome_arquivo | porta: $porta | foto_porta: $nome_arquivo_porta");
                $alert = ('Item alterado com sucesso!');
    $alert_type = 's';                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-busca");
                exit;
            }else{
                $alert = ('Erro ao salvar a imagem!', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form&alterar=$id");
                exit;
            }
        }else if (strstr('.jpg;.jpeg;.png', $extensao)) {
            $destino = '../inc/upload-catalogo-equipamento/'.$nome_arquivo.'.jpg';

            if(@move_uploaded_file ($arquivo_tmp, $destino)){

                $dados_catalogo_equipamento_foto = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = '$id'");
                $dados_catalogo_equipamento_foto =  $dados_catalogo_equipamento_foto[0]['foto_led'];
                unlink('../inc/upload-catalogo-equipamento/'.$dados_catalogo_equipamento_foto.'.jpg');
            
                $dados = array(
                    'id_catalogo_equipamento_marca' => $id_catalogo_equipamento_marca,
                    'led' => $led,
                    'modelo' => $modelo,
                    'foto_led' => $nome_arquivo,
                    'porta' => $porta
                );

                DBUpdate('', 'tb_catalogo_equipamento', $dados, "id_catalogo_equipamento = $id");
                registraLog('Alteração de catalogo de equipamento.','a','tb_catalogo_equipamento',$id,"id_catalogo_equipamento_marca: $id_catalogo_equipamento_marca | modelo: $modelo | led: $led | foto: $nome_arquivo | porta: $porta");
                $alert = ('Item alterado com sucesso!');
    $alert_type = 's';                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-busca");
                exit;
            }else{
                $alert = ('Erro ao salvar a imagem!', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form&alterar=$id");

                exit;
            }

        }else if (strstr('.jpg;.jpeg;.png', $extensao_porta)) {
    
            $destino_porta = '../inc/upload-catalogo-equipamento/'.$nome_arquivo_porta.'.jpg';

            if(@move_uploaded_file ($arquivo_tmp_porta, $destino_porta)){

                $dados_catalogo_equipamento_foto = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = '$id'");
                $dados_catalogo_equipamento_foto_porta =  $dados_catalogo_equipamento_foto[0]['foto_porta'];
                unlink('../inc/upload-catalogo-equipamento/'.$dados_catalogo_equipamento_foto_porta.'.jpg');
            
                $dados = array(
                    'id_catalogo_equipamento_marca' => $id_catalogo_equipamento_marca,
                    'led' => $led,
                    'modelo' => $modelo,
                    'porta' => $porta,
                    'foto_porta' => $nome_arquivo_porta
                );

                DBUpdate('', 'tb_catalogo_equipamento', $dados, "id_catalogo_equipamento = $id");
                registraLog('Alteração de catalogo de equipamento.','a','tb_catalogo_equipamento',$id,"id_catalogo_equipamento_marca: $id_catalogo_equipamento_marca | modelo: $modelo | led: $led | porta: $porta | foto_porta: $nome_arquivo_porta");
                $alert = ('Item alterado com sucesso!');
    $alert_type = 's';                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-busca");
                exit;
            }else{
                $alert = ('Erro ao salvar a imagem!', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form&alterar=$id");
                exit;
            }
        

        }else{
            if( ($_FILES['foto_led']) && ($_FILES['foto_porta']) ){
                $dados = array(
                    'id_catalogo_equipamento_marca' => $id_catalogo_equipamento_marca,
                    'led' => $led,
                    'modelo' => $modelo,
                    'porta' => $porta
                );

                DBUpdate('', 'tb_catalogo_equipamento', $dados, "id_catalogo_equipamento = $id");
                registraLog('Alteração de catalogo de equipamento.','a','tb_catalogo_equipamento',$id,"id_catalogo_equipamento_marca: $id_catalogo_equipamento_marca | modelo: $modelo | led: $led | porta: $porta");
                $alert = ('Item alterado com sucesso!');
    $alert_type = 's';                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-busca");
                exit;
            }else{
                $alert = ('Você poderá enviar apenas imagens "*.jpg;*.jpeg;*.png"!', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-form&alterar=$id");
                exit;
            }
        }
    }
    
}

function excluir($id){
    
    $dados_catalogo_equipamento_foto = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = '$id'");
    $dados_catalogo_equipamento_foto =  $dados_catalogo_equipamento_foto[0]['foto_led'];
    $dados_catalogo_equipamento_foto_porta =  $dados_catalogo_equipamento_foto[0]['foto_pora'];
    
    $query = "DELETE FROM tb_catalogo_equipamento WHERE id_catalogo_equipamento = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de catalogo de equipamento.','e','tb_catalogo_equipamento',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        unlink('../inc/upload-catalogo-equipamento/'.$dados_catalogo_equipamento_foto.'.jpg');
        if($dados_catalogo_equipamento_foto_porta){
            unlink('../inc/upload-catalogo-equipamento/'.$dados_catalogo_equipamento_foto_porta.'.jpg');
        }
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-busca");
    exit;
}

?>