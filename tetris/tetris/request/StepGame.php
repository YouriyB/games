<?php
require("../class/GameProcess.php");
session_start();
if($_SESSION["game"] == null)
	exit;
echo $_SESSION["game"]->GameStep($_GET["data"]);
?>