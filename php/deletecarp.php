<?php

require_once 'data.php';

$directorio =  AUDIOS_RUTA.$_POST['listcarp'];

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir) or die("Error on delete file '$dir'");
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir) or die("Error on delete dir '$dir'");;
}

deleteDirectory($directorio);

header("Location: conversor.php");
die();

?>