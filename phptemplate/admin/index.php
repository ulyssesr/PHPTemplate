<?php
session_start();

require("../includes/config.php");
require("../includes/Database.singleton.php");
require("../includes/Login.singleton.php");
require("../includes/class.account.php");
require("includes/class.admin.php");

$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$login = Login::obtain(); // object login

// make user login (requires admin status)
$login->hard(2);

$user=new Admin_Account();

if(!empty($_POST['action']) && $_POST['action']=="save") :
	$user->save(); 
elseif(!empty($_POST['action']) && !empty($_POST['operation']) && $_POST['action']=="update_status" && $_POST['operation']=="activate") :
	$user->upgrade(1);
elseif(!empty($_POST['action']) && !empty($_POST['operation']) && $_POST['action']=="update_status" && $_POST['operation']=="suspend") : 
	$user->upgrade(0);
elseif(!empty($_POST['action']) && !empty($_POST['operation']) && $_POST['action']=="update_status" && $_POST['operation']=="delete") :
	$user->delete();
endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin</title>
<meta charset="utf-8" />
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>

<?php require('includes/menu.inc.php'); ?>

<?php
if(@$_GET['action']=="new" || @$_GET['action']=="edit" || (@$_POST['action']=="save" && count($user->error)>0)){

	if(@$_POST['action']=="save"){
		$msg='Correct the following errors before continuing:<br />';
		foreach($user->error as $v) {
			$msg.='<div class="error">'.$v.'</div>';
		}

		$user->populate_post();
	}
	elseif(@$_GET['action']=="edit"){
		// returns bool if user was found
		$populate=$user->populate_id(@$_GET['user_id']);
		if($populate == false){
			$msg='User not found. Creating new user.';
		}
	}
	else{
		$msg='New user';
		$user->info['status']=1;
	}

?>

<?php if(isset($msg)&&strlen($msg)>0) echo $msg; ?>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
<input type="hidden" name="action" value="save">
<input type="hidden" name="user_id" value="<?php echo $user->info['user_id']; ?>">

<label for="edit-username">Username:</label><br />
<input type="text" maxlength="255" name="username" id="edit-username" size="20" value="<?php echo htmlentities($user->info['username']); ?>" /><br />

<label for="edit-password">Password:</label><br />
<input type="text" name="password" id="edit-password" size="20"><br />

<label for="edit-password2">Retype Password:</label><br />
<input type="text" name="password2" id="edit-password2" size="20"><br />
Passwords must match. <?php if(strlen($user->info['username'])>0) echo 'If no change, leave password fields blank.'; ?><br />

<label for="edit-email">Email:</label><br />
<input type="text" maxlength="255" name="email" id="edit-email" size="20" value="<?php echo htmlentities($user->info['email']); ?>" /><br />

<?php
	// display "created" field
	if(!empty($user->info['created'])){
?>

<label>Created:</label><br />
<?php echo date($user->date_long_format, strtotime($user->info['created'])); ?><br />

<?php
	}#-#created

	// display "accessed" field
	if(!empty($user->info['accessed'])){
?>

<label>Last Accessed:</label><br />
<?php echo date($user->date_long_format, strtotime($user->info['accessed'])); ?><br />

<?php
	}#-#accessed
?>

<!-- <input type="checkbox" name="status" value="1" id="edit-status"<?php echo ($user->info['status']=="1")?' checked="checked"':''; ?>"> <label for="edit-status">Active</label> -->
<label for="edit-status">Status:</label><br />
<?php echo $user->ric_create_selectbox("status", $user->status, $user->info['status'], 'id="edit-status"'); ?>
<input type="submit" value="Save"> <input type="button" value="Cancel" onclick="window.location='<?php echo $_SERVER['SCRIPT_NAME']; ?>';">
</form>

<?php
}#-#if(edit)
else{
?>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="get">
<fieldset style="margin-bottom:20px;">
<legend>Show only items where</legend>
Status is 

<?php
	echo $user->ric_create_selectbox("status", $user->status, (isset($_GET['status'])?(int)$_GET['status']:0), 'id="edit-status"');
?>

<input type="submit" value="Filter" />
<?php
	if(isset($_GET['status']))echo '<input type="button" value="Reset" onclick="window.location=\''.$_SERVER['SCRIPT_NAME'].'\';" />';
?>
</fieldset>
</form>


<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" name="update_form" onsubmit="return ((this.operation[this.operation.selectedIndex].value=='delete'&&!confirm('This action can not be undone.\n\nContinue?'))?false:true);">
<input type="hidden" name="action" value="update_status">
<fieldset style="margin-bottom:20px;">
<legend>Update options</legend>
	<select name="operation">
		<option value="activate">Activate</option>
		<option value="suspend">Suspend</option>
		<option value="delete">Delete</option>
	</select>
<input type="submit" value="Update" />
</fieldset>

<table>
<thead><tr>
	<th>&nbsp;</th>
	<th style="text-align:left;"><a href="<?php echo $_SERVER['SCRIPT_NAME'].'?sort='.(!empty($_GET['sort'])&&$_GET['sort']=='asc'?'desc':'asc').'&order=username'.(isset($_GET['status'])?'&status='.(int)$_GET['status']:'');?> ">Username</a></th>
	<th style="text-align:left;"><a href="<?php echo $_SERVER['SCRIPT_NAME'].'?sort='.(!empty($_GET['sort'])&&$_GET['sort']=='asc'?'desc':'asc').'&order=email'.(isset($_GET['status'])?'&status='.(int)$_GET['status']:'');?>">Email</a></th>
	<th style="text-align:left;"><a href="<?php echo $_SERVER['SCRIPT_NAME'].'?sort='.(!empty($_GET['sort'])&&$_GET['sort']=='asc'?'desc':'asc').'&order=created'.(isset($_GET['status'])?'&status='.(int)$_GET['status']:'');?>">Created</a></th>
	<th style="text-align:left;"><a href="<?php echo $_SERVER['SCRIPT_NAME'].'?sort='.(!empty($_GET['sort'])&&$_GET['sort']=='asc'?'desc':'asc').'&order=status'.(isset($_GET['status'])?'&status='.(int)$_GET['status']:'');?>">Status</a></th>
	<th style="text-align:left;"><a href="#">Actions</a></th>
</tr></thead>
<tbody>

<?php
$i=1;
foreach($user->list_all(0,100) as $key=>$v) {
?>

<tr class="<?php echo ($i++%2==0?'odd':'even'); ?>">
	<td><input type="checkbox" name="chk[]" value="<?php echo htmlentities($v['user_id']); ?>" /></td>
	<td><?php echo htmlentities($v['username']); ?></td>
	<td><?php echo htmlentities($v['email']); ?></td>
	<td><?php echo date($user->date_short_format, strtotime($v['created'])); ?></td>
	<td><?php echo $user->status[$v['status']]; ?></td>
	<td><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?action=edit&user_id=<?php echo urlencode($v['user_id']); ?>">edit</a></td>
</tr>

<?
}#-#foreach
?>

<tr><td></td><td colspan="5"><input type="button" value="Add New" onclick="window.location='<?php echo $_SERVER['SCRIPT_NAME']; ?>?action=new';"></td></tr>

</tbody>
</table>
</form>

<?php
}#-#else
?>

</body>
</html>