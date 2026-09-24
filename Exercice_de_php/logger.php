<?php
function write_log($username = "UNKNOWN", $message = "EMPTY"){

    $date = date("Y-m-d H:i:s");
    $fp = fopen('app.log', 'a');
    fwrite($fp, "[" . $date . "]" . "[" . $username. "] " . $message . "\n");
    fclose($fp);
}

write_log();
write_log("Meow");
write_log("Meow", "Miaouuuuuuu");

?>