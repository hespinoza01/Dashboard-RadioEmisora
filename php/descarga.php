<?php

$nombrebs64 = '../../audios/'.$_POST['listnameaudiosdescarga'];

// Get real path for our folder
$rootPath = realpath($nombrebs64);

// Initialize archive object
$zip = new ZipArchive();
$zip->open($nombrebs64.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $nombrebs64.'/'.$relativePath);
        // $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

$fileName = basename($nombrebs64.".zip");
$filePath = '../../audios/'.$fileName;
if(!empty($fileName) && file_exists($filePath)){
    // Define headers
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$fileName");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");
    
    // Read the file
    readfile($filePath);
    // Por último eliminamos el archivo temporal creado
    unlink($nombrebs64.'.zip');//Destruye el archivo temporal
    exit;
}else{
    echo 'El archivo no existe.';
}


function readfile_chunked($filename, $retbytes = TRUE) { 
    $CHUNK_SIZE=1024*1024;
    $buffer = ''; 
    $cnt =0; 
    $handle = fopen($filename, 'rb'); 
    if ($handle === false) { return false; } 
    while (!feof($handle)) { 
        $buffer = fread($handle, $CHUNK_SIZE); 
        echo $buffer; 
        @ob_flush(); 
        flush(); 
        if ($retbytes) { 
            $cnt += strlen($buffer); 
        } 
    } 
    $status = fclose($handle); 
    if ($retbytes && $status) { 
        return $cnt; // return num. bytes delivered like readfile() does. 
    } 
    return $status; 
} 





?>