<?php 

function no_cache_htmltag(){
    echo "<meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate' />";
    echo "<meta http-equiv='Pragma' content='no-cache' />";
    echo "<meta http-equiv='Expires' content='0'/>";
    echo "<meta http-equiv='Last-Modified' content='0'>";
}

?>