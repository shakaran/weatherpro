<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'incContact.php';
$pageVersion	= '3.20 2015-08-02';
#-------------------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# SETTINGS
# -------------------------------------------------------------------------------------------------
$cfg['naam'] 	= $SITE['contactName'];		// Webmaster name
$cfg['nameFrom']= $SITE['organ'];		// webmasters / website name
$cfg['siteUrl']	= $SITE['siteUrl'];
$cfg['email'] 	= $SITE['contactEmail'];	// E-mail from
$cfg['emailto'] = $SITE['contactEmailTo'];      // E-mail to

$cfg['text'] 	= true;				// text red by errors
$cfg['input'] 	= true;				// border red by error 
$cfg['CAPTCHA'] = true;				// CAPTCHA ( true = yes)

// No changes form this point please

$formulier = true;

if(isset($_POST['wis']) && ($_SERVER['REQUEST_METHOD'] == "POST")) {
	foreach ($_POST as $key => $value) {
		unset($value);
	}
}
	
if (isset($_POST['verzenden']) && ($_SERVER['REQUEST_METHOD'] == "POST")) {
	$aFout = array();
	if($cfg['CAPTCHA']){
	        require_once('recaptchalib.php');
		$privatekey = "6LfTduASAAAAAMwUXcomZivhA0OoVZ9-Oen6sqes";
		$resp = recaptcha_check_answer ($privatekey,
                        $_SERVER["REMOTE_ADDR"],
                        $_POST["recaptcha_challenge_field"],
                        $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			$aFout[] = 'Captha code'.' '.langtransstr("incorrect");			
			$fout['text']['code'] = true;
		}
	}
	$naam           = trim($_POST['naam']);
	$email          = trim($_POST['email']);	
	$email_check    = trim($_POST['email_check']);
	$onderwerp      = trim($_POST['onderwerp']);
	$bericht        = trim($_POST['bericht']);		
	$validEmail     = true;
	if (function_exists ('filter_var') ) {
		$naam           = filter_var($naam, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);	
		$email          = filter_var($email, FILTER_SANITIZE_EMAIL);
		$email_check    = filter_var($email_check, FILTER_SANITIZE_EMAIL);
		$onderwerp      = filter_var($onderwerp, FILTER_SANITIZE_STRING);
		$bericht        = filter_var($bericht, FILTER_SANITIZE_STRING);
		$validEmail     = filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	if(empty($naam) || (strlen($naam) < 3) ){
		$aFout[]                = langtransstr("Name").' '.langtransstr("required");
		unset($naam);
		$fout['text']['naam']   = true;
		$fout['input']['naam']  = true;
	}
	if(empty($email))
	{
		$aFout[]                = langtransstr("e-mail").' '.langtransstr("required");
		$fout['text']['email']  = true;
		$fout['input']['email'] = true;
	}
	elseif (!$validEmail) {
		$aFout[]                = langtransstr("e-mail").' '.langtransstr("incorrect");
		$fout['text']['email']  = true;
		$fout['input']['email'] = true;
	}
	if ($email <> $email_check)
	{
		$aFout[]                = langtransstr("e-mail addresses should be equal");
		$fout['text']['email']  = true;
		$fout['input']['email'] = true;
	}	
	if(empty($onderwerp)) {
		$aFout[]         = langtransstr("Subject").' '.langtransstr("required");
		unset($onderwerp);
		$fout['text']['onderwerp']      = true;
		$fout['input']['onderwerp']     = true;
	}
	if(empty($bericht)) {
		$aFout[] = langtransstr("Message").' '.langtransstr("required");
		unset($bericht);
		$fout['text']['bericht']        = true;
		$fout['input']['bericht']       = true;
	}
	if(!$cfg['text'])  {
		unset($fout['text']);
	}
	if(!$cfg['input']) {
		unset($fout['input']);
	}
	if(!empty( $aFout )) {
		$errors = '<div id="errors"><ul>'.PHP_EOL;
		foreach($aFout as $sFout){
			$errors .= "	<li>".$sFout."</li>\n";
		}
		$errors .= "</ul>
		</div>";
	}
	else {  $formulier = false; 
	}
}
if ($formulier == false	) {		
        $bericht = str_replace (PHP_EOL,'<br />',$bericht);
        // Headers
        $headers  = 'From: "'.$cfg['email'].'" <'.$cfg['email'].">\r\n"; 
        $headers .= "Reply-To: \"".$naam."\" <".$email.">\n";
        $headers .= "Return-Path: Mail-Error <".$cfg['email'].">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Transfer-Encoding: 8bit\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        $message = '
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html>
        <head>
        </head>
        <body>
        <br />
        <b>';
        $message .= langtransstr("Name"); 
        $message .= ':</b> ' . $naam . '<br /><b>Email:</b> <a href="mailto:'.$email.'">'.$email.'</a><br /><br /><b>';
        $message .= langtransstr("Subject");	
        $message .= ':</b><br />'.$onderwerp.'<br /><b>';		
        $message .= langtransstr("Message");			
        $message .= ':</b><br />'.$bericht.'
        <br />
        <br />
        <br />
        --------------------------------------------------------------------------<br />
        <b>Datum:</b> '.date("d-m-Y @ H:i:s").'<br />
        <b>Site: </b> '.$cfg['nameFrom'].' at '.$cfg['siteUrl'].'<br />
        <b>IP:   </b> '.$_SERVER['REMOTE_ADDR'].'<br />
        <b>Host: </b> '.gethostbyaddr($_SERVER['REMOTE_ADDR']).'<br />
        </body>
        </html>';

	if(mail($cfg['emailto'], "Info request ".$onderwerp, $message, $headers)) {
                if(isset($_POST['stuurkopie'])) {
                        $headers  = 'From: "'.$cfg['email'].'" <'.$cfg['email'].">\r\n";
                        $headers .= "Reply-To: \"".$naam."\" <".$email.">\n";
                        $headers .= "Return-Path: Mail-Error <".$email.">\n";
                        $headers .= "MIME-Version: 1.0\n";
                        $headers .= "Content-Transfer-Encoding: 8bit\n";
                        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
                        mail($email, "Info request ".$onderwerp, $message, $headers);
                }
	        echo '<div class="blockDiv">
<h3 class="blockHead">'.langtransstr('Message sent').'</h3>
<br /><br />
<div style="width: 90%; margin: 0 auto;">'.PHP_EOL;
                echo $message;
                $message2 = "<p>";
                $message2 .= langtransstr("Your message is sent, we will react swiftly");
                $message2 .= ".<br /><br />";
                $message2 .= langtransstr("Kind regards");			
                $message2 .= ",<br /><b>".$cfg['naam']."</b></p>";
                echo $message2;
                echo '</div><br /><br /></div>'.PHP_EOL;
	}
	else {  ws_message (  'Module incContact.php ('.__LINE__.'): '.langtransstr('An error occured, please try again later'),true);
	}
        return;
}
?>
<div class="blockDiv">
<?php if (isset($mobi) && $mobi == 2) { } else { ?>
<style scoped>
.contact {width: 80%;margin:0 auto;}
.contact ul li {}
label {	float: left;width: 300px;text-align: right;padding-top: 5px;}
input, textarea {padding: 3px;margin: 3px;border: 1px solid #bac5d6;font: 10px Verdana, sans-serif;background: #fff; }
.contact img {padding: 2px;margin: 2px;	}
checkbox { padding-top: 10px;}
input.fout, textarea.fout {border: 1px solid #FF0000;}
label.fout {color: #FF0000;}
table tr {height: 5px;}
</style>
<?php }?>
<h3 class="blockHead"><?php langtrans('Comments or questions are welcome') ?></h3>
<div class="contact">   

<?php
if(isset($errors)) {
        echo $errors;
}
if (isset($mobi) && $mobi == 2) {$extraText='<br />';} else {$extraText='';}
?>  <!-- echo errors -->
<br />
<form method="post" id="contactform" action="<?php echo $SITE['pages']['incContact'].'&amp;lang='.$lang.$extraP.$skiptopText; ?>">
<label <?php if(isset($fout['text']['naam'])) { echo 'class="fout"'; } ?>><?php langtrans("Your name"); ?>:&nbsp;</label>
<?php echo $extraText; ?>
<input type="text" id="naam" name="naam" maxlength="30" <?php if(isset($fout['input']['naam'])) { echo 'class="fout"'; } ?> value="<?php if (!empty($naam)) { echo stripslashes($naam); } ?>" /><br />

<label <?php if(isset($fout['text']['email'])) { echo 'class="fout"'; } ?>><?php langtrans("Email"); ?>:&nbsp;</label>
<?php echo $extraText; ?>
<input type="text" id="email" name="email" maxlength="255" <?php if(isset($fout['input']['email'])) { echo 'class="fout"'; } ?> value="<?php if (!empty($email)) { echo stripslashes($email); } ?>" /><br />

<label <?php if(isset($fout['text']['email'])) { echo 'class="fout"'; } ?>><?php langtrans("Email, please type again"); ?>:&nbsp;</label>
<?php echo $extraText; ?>
<input type="text" id="email_check" name="email_check" maxlength="255" <?php if(isset($fout['input']['email'])) { echo 'class="fout"'; } ?> value="<?php if (!empty($email_check)) { echo stripslashes($email_check); } ?>" /><br />

<label <?php if(isset($fout['text']['onderwerp'])) { echo 'class="fout"'; } ?>><?php langtrans("Subject"); ?>:&nbsp;</label>
<?php echo $extraText; ?>
<input type="text" id="onderwerp" name="onderwerp" maxlength="40" <?php if(isset($fout['input']['onderwerp'])) { echo 'class="fout'; } ?> value="<?php if (!empty($onderwerp)) { echo stripslashes($onderwerp); } ?>" /><br />

<label <?php if(isset($fout['text']['bericht'])) { echo 'class="fout"'; } ?>><?php langtrans("Message"); ?>:&nbsp;</label>
<?php echo $extraText; ?>
<textarea id="bericht" name="bericht" <?php if(isset($fout['input']['bericht'])) { echo 'class="fout"'; } ?> style="width: 100%; height: 200px;"><?php if (!empty($bericht)) { echo stripslashes($bericht); } ?></textarea><br />
<?php echo $extraText; ?>
<label style="padding-top: 0px;" for="stuurkopie"><?php langtrans("Send me a copy"); ?>:&nbsp;</label>
<?php echo $extraText; ?>
<input type="checkbox" id="stuurkopie" name="stuurkopie" value="1" /><br />

<br />
<label></label>
<div style="text-align: left; width: 318px; overflow: hidden;">
<?php 
$script	= 'recaptchalib.php';
ws_message (  '<!-- module incContact.php ('.__LINE__.'): loading '.$script.' -->');
require_once($script); 
$publickey= '6LfTduASAAAAAOZjWB2xfxZi3JAJa5C5CLzdbMdd'; // you got this from the signup page
echo recaptcha_get_html($publickey); 
?>
</div>
<br />
<label></label>
<?php echo $extraText; ?>
<input type="submit" id="verzenden" name="verzenden" <?php echo 'value="'; langtrans('submit'); echo '"'; ?>  />
<input type="submit" id="wis" name="wis" <?php echo 'value="'; langtrans('clear fields'); echo '"'; ?> />
<br /><br />
</form>
</div>
</div>
<?php
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
