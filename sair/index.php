<?php
$root_path = "../";
session_start();
session_destroy();
header("location:".$root_path."entrar/");
?>
