<?php
require("includes/config.php");
require("includes/Database.singleton.php");
require("includes/class.table.php");
include("template/header.php");

$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sql = "SELECT * FROM ".TABLE_SAMPLE." WHERE 1";
$data = $db->fetch_array($sql);

$table = new Table('result', 'class="approved"');
$table->setHead('head',array('ID','Firstname','Lastname','Phone','Email')); 
$table->setData($data);
$table->displayTable();

include("template/footer.php");

/* end of table.php */