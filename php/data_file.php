<?php 

$log_dir = '../logs';

function logger($message){
    global $log_dir;

    if(!is_dir($log_dir)){
        mkdir($log_dir);
    }

    $message = date('Y-m-d G:i:s')." ==> ".$message.PHP_EOL;

    file_put_contents("$log_dir/errors.log", $message, FILE_APPEND);
}

function read_file($filename, $json=true){
    if(!is_file($filename)){
        logger("Read Error: $filename no existe");
        return array(
            'success'=> 'false',
            'message' => "Read Error: $filename no existe"
        );
    }

    if(!is_readable($filename)){
        logger("Read Error: $filename no puede ser leido");
        return array(
            'success'=> 'false',
            'message' => "Read Error: $filename no puede ser leido"
        );
    }

    $data = file_get_contents($filename);

    return ($json) ? json_decode($data, true) : $data;
}

function write_file($filename, $data){
    if(!is_file($filename)){
        logger("Write Error: $filename no existe");
    }else{
        if(!is_readable($filename)){
            logger("Write Error: $filename no permite escritura");
            return array(
                'success'=> 'false',
                'message' => "Write Error: $filename no permite escritura"
            );
        }
    }

    return file_put_contents($filename, $data);
}

 ?>