<?php
require("../class/GameProcess.php");
session_start();
if($_SESSION["game"] == null)
	exit;
if(file_exists("../score.sc") == false)
	exit;
$file = fopen("../score.sc", "r");
$result = fgets($file);
fclose($file);
echo $result;
?>