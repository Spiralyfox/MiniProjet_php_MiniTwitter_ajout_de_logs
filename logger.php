<?php 


// [Date Heure] [INFO] [IP] [NomUtilisateur] Message
function write_log($type = "INFO", $ip = "UNKNOWN", $username = "UNKNOWN", $message = "EMPTY") {

    $date = date("Y-m-d H:i:s");

    $fp = fopen('app.log', 'a');
    fwrite($fp, "[" . $date . "] " . "[" . $type . "] " . "[" . $ip . "] " . "[" . $username . "] " . $message . "\n");
    fclose($fp);
}

// Test
// write_log("a", "b", "c", "d");
?>