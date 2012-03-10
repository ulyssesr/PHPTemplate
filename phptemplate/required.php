<?php
session_start(); 
require("includes/config.php");
require("includes/Database.singleton.php");
require("includes/Login.singleton.php");
include("template/header.php");

$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$login = Login::obtain();
$login->hard();
?>
<h4>You are logged in!</h4>

<a href="<?php echo $_SERVER['SCRIPT_NAME'];?>?go=logout">Logout</a>

<?php

include("template/footer.php");

/* end of required.php */