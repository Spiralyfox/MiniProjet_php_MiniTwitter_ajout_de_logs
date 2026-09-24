<?php
include "logger.php";

if (isset($_GET['username']) && isset($_GET['message'])) {
    write_log($_GET['username'], $_GET['message']);
}
?>

<form method="GET" action="index.php">
    <label>Username : <input type="text" name="username"></label><br>
    <label>Message : <input type="text" name="message"></label><br>
    <input type="submit" value="Envoyer">
</form>
