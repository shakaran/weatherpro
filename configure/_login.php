<?php
// Secure PHP login without Database by Aitzaz Warraich https://www.facebook.com/aitzaz.warraich.official
// adapted to the template
// Users and Settings
$domain_code = 'versie28';	//Alpha Numeric and no space
$random_num_1 = 32;		//Pick a random number between 1 to 500
$random_num_2 = 520;		//Pick a random number between 500 to 1000
$random_num_3 = 3;		//Pick a random number between 1 to 3
#
$users['install'] 	= 'new';
$users['demo'] 	        = 'demo';
#
$login 		        = new login_class;
#
if (!file_exists ('./cache/name.arr') && !isset($_REQUEST['password1']) ) {
        echo_pass_set ();
        return false;
}
if (isset ($_REQUEST['password1']) ) {
        if (trim($_REQUEST['password1'])  <> trim($_REQUEST['password2']) ) {
                $error_new_password = '<b style="color: red;">Passwords must be equal</b>';
                echo_pass_set ();
                return false;
        }
        $string =  trim($_REQUEST['password1']);
        file_put_contents ('./cache/name.arr',$string);
}
if (file_exists ('./cache/name.arr') ) {
        $users['install'] = file_get_contents ('./cache/name.arr');
}
#
$pass_login 	= false;

$login->domain_code 	= $domain_code;
$login->users 		= $users;
$login->num_1 		= $random_num_1;
$login->num_2 		= $random_num_2;
$login->num_3 		= $random_num_3;
# check environment
if (!$login->verify_settings()) {		//Verify
	echo '<strong>Invalid Admin Settings for Login Script</strong><br />Check your settings and retry logging in';	
	exit();
}
# check logout
if (isset ($_REQUEST['logout']) ) {
	setcookie($domain_code.'_uid', 'none',0);
	setcookie($domain_code.'_cid', 'none',0);
	$username = '';
}
# check Logged In
elseif (isset($_COOKIE[$domain_code.'_uid']) && $_COOKIE[$domain_code.'_uid'] <> 'none' && isset($_COOKIE[$domain_code.'_cid']) && $_COOKIE[$domain_code.'_cid']<> 'none') {
	$key_uid 	= $login->cleanse_input($_COOKIE[$domain_code.'_uid']);
	$key_cid 	= $login->cleanse_input($_COOKIE[$domain_code.'_cid']);
	if ($login->verify_login($key_uid, $key_cid)) {	
		$username = $login->username;
		unset ($login);
		return true;
	}
	$login->error_message = 'Login has expired';
}
# Verify Logged In Credentials
if (isset($_POST['login'])) {		//Trying To Login
	$login_user = $login->cleanse_input($_POST['username']);	//Verify Login
	$login_pass = $login->cleanse_input($_POST['password']);
	if ($login->check_login($login_user, $login_pass)) {		//Check Login
		$login->encryption_key($login_user);			//Encode
		$username = $login->username;
		return true;
	} 
	$login->error_message = 'Invalid username or password';	
} 
//Start Class
class login_class {
	//ENCRYPTION CENTER
	var $domain_code = '';
#	var $today_ts = '';
#	var $today_m = '';
	var $error_message = NULL;
	var $users = '';
	var $num_1 = '';
	var $num_2 = '';
	var $num_3 = '';
	var $username = '';
	function verify_settings () {
		$verified = TRUE;
		//Num 1 between 1 - 500
		if 	($this->num_1<1   || $this->num_1>500) 	$verified = FALSE;
		elseif 	($this->num_2<500 || $this->num_2>1000) $verified = FALSE;
		elseif 	($this->num_3<1   || $this->num_3>5) 	$verified = FALSE;
		foreach ($this->users as $user => $pass) {
			//Usernames
			preg_match('/([^A-Za-z0-9-_\s\s+])/', $user, $user_result_{$user});	
			if (!empty($user_result_{$user})) $verified = FALSE;
		}
		return $verified;
	} // eo verify_settings
	function encryption_key ($user) {
		$key_uid = $this->user_encryption($user); 		//Encryption Key One
		$key_cid = $this->code_encryption($key_uid);		//Encrption Key Two
		setcookie($this->domain_code.'_uid', $key_uid, time() + (86400 * 5));	//Set Keys
		setcookie($this->domain_code.'_cid', $key_cid, time() + (86400 * 5));
	}
	function user_encryption ($user) {
		return md5($user);	//Array of Characters
	} // eo 
	function code_encryption ($key_cid, $encrypt = 1) {
		if ($encrypt == 1) {
			$key_code = preg_replace('/([^0-9+])/', '', $key_cid);	
			switch ($this->num_3) {
				case 1:
					$key_code = floor((($key_code + $this->num_2 + (($this->num_1 * 2) * $this->num_2)) / $this->num_1) / $this->num_2);
					break;
				case 2:
					$key_code = ceil(((($this->num_2 + $this->num_1) * $this->num_1 + $key_code + $this->num_2 - (10 * $this->num_1)) / ($this->num_1 * 50))/100000000);
					break;
				case 3:
					$key_code = floor((((($key_code - $this->num_2 + (($this->num_1 * 3) * $this->num_2)) + $this->num_1) / $this->num_2))/100000000);
					break;
			}
			$key_code = substr($key_code, 0, 10);
			return $key_code;
		}
	} // eo 
	function check_login ($username, $password) {
		//Check Login
		foreach ($this->users as $user => $pass) {
			if ($username == $user && $password == $pass) {
				$this->username = $username;
				return TRUE;
			}
		}
		return FALSE;
	} // eo 
	function verify_login ($key_uid, $key_cid) {
		//Check Login
		if ($key_cid = $this->code_encryption($key_uid)) {
			//Validate Username Is True
			foreach ($this->users as $username => $password) {
				if ($key_uid == $this->user_encryption($username)) {
					$this->username = $username;
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
	function error_login () {
		if (isset($this->error_message)) {echo '<span style="font-weight: bold; color: red;">'.$this->error_message.'</span><br /><br />';}
	} // eo 
	function cleanse_input($input) {
		$input = trim($input);		//Trim
		if (get_magic_quotes_gpc()==1) {
		} else {
			$input = addslashes($input);	//Escape Codes
		}
		$input = htmlentities($input);	//If Html Entities
		return $input;
	} // eo 
} // eo  CLASS
?>
<div style="width: 220px; padding: 10px; border: 3px solid #FFF; margin: 100px auto 10px auto; background-color: #D6D6D6;">
    <form action="" method="post">
	<?php $login->error_login(); ?>
	<span style="lfont-size: 18px; font-weight: bold; color: #848484;">Username</span>
	<br />
	<input name="username"  type="text"  value="install"	style="lwidth: 210px; padding: 5px; margin: 2px 0 2px 0; border: 1px solid #39F;  font-size: 16px;" />
	<br />
	<span style="lfont-size: 18px; font-weight: bold; color: #848484;">Password</span>
	<br />
	<input name="password"  type="password" style="lwidth: 210px; padding: 5px; margin: 2px 0 2px 0; border: 1px solid #39F;  font-size: 16px;" />
	<br />
	<br />
	<div align="right">
		<button name="login" type="submit"/>&nbsp;login&nbsp;</button>
	</div>
	
    </form>
</div>
<?php
return false;
#
function echo_pass_set () {
        global $error_new_password;
        if (isset ($error_new_password) ) {$string = $error_new_password.'<br />';} else {$string = '';}
        echo '
<div style="width: 220px; padding: 10px; border: 3px solid #FFF; margin: 100px auto 10px auto; background-color: #D6D6D6;">
<form action="" method="post">'.$string.'
<span style="lfont-size: 18px; font-weight: bold; color: #848484;">Password</span>
<br />
<input name="password1"  type="text" 	style="lwidth: 210px; padding: 5px; margin: 2px 0 2px 0; border: 1px solid #39F;  font-size: 16px;" />
<br />
<span style="lfont-size: 18px; font-weight: bold; color: #848484;">Retype Password</span>
<br />
<input name="password2"  type="password" style="lwidth: 210px; padding: 5px; margin: 2px 0 2px 0; border: 1px solid #39F;  font-size: 16px;" />
<br />
<br />
<div align="right"><button name="enter" type="submit"/>&nbsp;enter&nbsp;</button></div>
</form>
</div>
';
}

