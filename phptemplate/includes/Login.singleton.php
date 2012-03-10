<?php

class Login {

	// settings
	private $prefix = "login_";
	private $cookie_duration = 21;  // days "remember me" cookies

	public $status = array("0"=>"Suspended", "1"=>"Active", "2"=>"Administrator");

	// info array
	public $info=array();

	// store the single instance of Class
	private static $instance;

# constructor
private function __construct(){

	if(isset($_COOKIE[$this->prefix.'username']) && isset($_COOKIE[$this->prefix.'password'])){
		$_SESSION[$this->prefix.'username'] = $_COOKIE[$this->prefix.'username'];
		$_SESSION[$this->prefix.'password'] = $_COOKIE[$this->prefix.'password'];
	}

	//if login
	if(isset($_POST['go']) && $_POST['go'] == "login"){
		$this->set();//exits if incorrect
	}
	//if forced prompt
	elseif(isset($_GET['go']) && $_GET['go'] == "prompt"){
		$this->clear();
		$this->prompt();//exits
	}
	//if logout
	elseif(isset($_GET['go']) && $_GET['go'] == "logout"){
		$this->clear();
		$msg='<h4 class="msg">Logout complete</h4>';
		$this->prompt($msg);//exits
	}

}

# singleton declaration
public static function obtain(){

	if (!self::$instance){ 
		self::$instance = new self(); 
	} 

	return self::$instance;
}

# check hard login
public function hard($status=1) {

	//if no sessions set, prompt
	if(!isset($_SESSION[$this->prefix.'password']) || !isset($_SESSION[$this->prefix.'username'])){
		$this->prompt();//exits
	}
	// if not valid, force login
	elseif($this->check($_SESSION[$this->prefix.'username'], $_SESSION[$this->prefix.'password']) == false){
		$this->clear();
		$msg='<h4 class="msg">Incorrect password or username</h4>';
		$this->prompt($msg);//exits
	}
	// don't meet status level
	elseif($this->info['status'] < $status){
		$this->clear();
		$msg='<h4 class="msg">Incorrect permission to access this page</h4>';
		$this->prompt($msg);//exits
	}

	return true;
}

# checks soft login
public function soft() {

	if(!isset($_SESSION[$this->prefix.'password']) || !isset($_SESSION[$this->prefix.'username'])){
		return false;
	}

	return $this->check($_SESSION[$this->prefix.'username'], $_SESSION[$this->prefix.'password']);
}


# sets login info
private function set(){
	
		$db=Database::obtain();
		// if valid 
		if($this->check(@$_POST['username'], null, @$_POST['password']) == false){

			$msg='<h3 class="msg">Incorrect username or password.</h3>';
			$this->clear();
			$this->prompt($msg);//exits
		}
		//if "remember me" is checked
		if(isset($_POST['remember'])){
			setcookie($this->prefix."username", $this->info['username'], time()+($this->cookie_duration*86400));// (d*24h*60m*60s)
			setcookie($this->prefix."password", $this->info['password'], time()+($this->cookie_duration*86400));// (d*24h*60m*60s)
		}
		//set session
		$_SESSION[$this->prefix.'username'] = $this->info['username'];
		$_SESSION[$this->prefix.'password'] = $this->info['password'];

}

# clears all cookies
function clear(){

	@session_unset();
	@session_destroy();
	
	// destroy cookie by setting time in past
	setcookie($this->prefix."username", "blanked", time()-3600);
	setcookie($this->prefix."password", "blanked", time()-3600);

	$this->info=array();
}

# sets $this->info array to username data
public function set_user($username){

	$db=Database::obtain();
	$sql = "SELECT user_id, username, password, salt, email, created, accessed, status FROM `" .TABLE_USERS. "` WHERE username = '".$db->escape($username)."'";
	$this->info=$db->query_first($sql);

}

# grab user info
private function check($username, $password, $plain_password=null){

	// assigns $this->info values
	$this->set_user($username);

	// if plain password, hash it
	if($plain_password!=null && isset($this->info['salt'])) $password=md5($plain_password.$this->info['salt']);
	// no user by that name, or bad password
	if(!isset($this->info['username']) || $password != $this->info['password']){
		$this->clear();
		return false;
	}
	//clean login
	return true;

}

# login form
public function prompt($msg=''){
?>
<html>
<head>
<title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
<input type="hidden" name="go" value="login">
<?php echo $msg; ?>
<label for="user">Username:</label><input type="text" name="username" id="user"><br/>
<label for="pass">Password:</label><input type="password" name="password" id="pass"><br/>
<input type="checkbox" name="remember" id="remember"><label for="remember">Remember me</label><br/>
<input type="submit" value="Login">
</form>

</body>
</html>

<?php
	//don't run the rest of the page
	exit;
}

}
?>
