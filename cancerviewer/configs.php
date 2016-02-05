<?php
    $configs = $_REQUEST["configs"];
    $file = fopen("configs.txt", "w");
    fwrite($file, $configs);
    fclose($file);
?>
