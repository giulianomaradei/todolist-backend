<?php
function retornaAlerta($msg, $typ){
    if($typ == 's'){
        $typ = 'alert-success';
    }else if($typ == 'i'){
        $typ = 'alert-info';
    }else if($typ == 'w'){
        $typ = 'alert-warning';
    }else{
        $typ = 'alert-danger';
    }
    return "<div class='container-fluid text-center'><div class='alert $typ'><strong>$msg</strong></div></div>";
    
}