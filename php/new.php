#!/usr/bin/php -q
<?php
/* Read the message from STDIN */
$fd = fopen("php://stdin", "r");
$email = ""; // This will be the variable holding the data.
while (!feof($fd)) {
$email .= fread($fd, 1024);
}
fclose($fd);
/* Saves the data into a file */
$fdw = fopen("/home/malindot/tmp/pipemail_".time().".txt", "w+");
fwrite($fdw, $email);
fclose($fdw);
/* Script End */
?>