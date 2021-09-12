<?php
require("../class/GameProcess.php");
session_start();
if($_SESSION["game"] == null)
	exit;
if(isset($_GET["name"]) == false)
{
	echo "error";
	exit;
}
$_SESSION["game"]->SendName($_GET["name"]);
echo "no";
?>