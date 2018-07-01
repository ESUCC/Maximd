<?php

function writexport($var1,$var2) { 

    ob_start();
    var_export($var1);
    $data = ob_get_clean();
    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
    $fp = fopen("/tmp/textfile.txt", "a");
    fwrite($fp, $data2);
    fclose($fp);
}
function writevar($var1,$var2) {

    ob_start();
    var_dump($var1);
    $data = ob_get_clean();
    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
    $fp = fopen("/tmp/textfile.txt", "a");
    fwrite($fp, $data2);
    fclose($fp);
}
?>
