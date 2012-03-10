<?php
require("includes/config.php");
require("includes/Database.singleton.php");
require("includes/class.form.php");
include("template/header.php");

$forms = new Forms();
$forms->openForm(basename(__FILE__),'post','','login');
$forms->openFieldSet('Login'); 
$forms->label('Username: ','<p>'); 
$forms->textField('username','','placeholder="Enter username"','','</p>');
$forms->label('Password: ','<p>'); 
$forms->textField('login','','placeholder="Enter password"','','</p>',true);
$forms->label('Message: ','<p>'); 
$forms->textArea('body','','50','5','placeholder="Type message here ... "','','</p>');
$forms->submitButton('Login','Login','class="login"','<p>','</p>'); 
$forms->closeFieldSet();
$forms->closeForm(); 

include("template/footer.php");

/* end of form.php */