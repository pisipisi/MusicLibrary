<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if ($isLoggedIn) {
	echo "<center><b>You are already login</b></center>";
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
	exit();
}

$warn = 'Sign up to find interesting thing';
$registered = FALSE;
if (isset($_POST['register'])) {

	$name = m_htmlchars(stripslashes(trim(urldecode($_POST['name']))));
	$pwd = md5(stripslashes(urldecode($_POST['pwd'])));
	$email = stripslashes(trim(urldecode($_POST['email'])));
	$sex = ($_POST['sex'])?$_POST['sex']:1;
	
	if ($mysql->num_rows($mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_name = '".$name."'"))) 
		$warn = "Username already picked";
	
	if (!m_check_email($email)) 
		$warn .= "Please enter correct email format";
	elseif ($mysql->num_rows($mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_email = '".$email."'"))) 
		$warn = "Your email existed in database";
	else {
		$playlist_id = m_random_str(20);
		$mysql->query("INSERT INTO ".$tb_prefix."user (user_name,user_password,user_email,user_sex,user_regdate,user_playlist_id) VALUES ('".$name."','".$pwd."','".$email."','".$sex."',NOW(),'".$playlist_id."')");
		$registered = TRUE;
	}

}
if(!$registered) {
$main = $tpl->get_tpl('register');
$main = $tpl->assign_blocks_content($main,array(
		'warn'		=>	$warn,
)
		);
$tpl->parse_tpl($main);
} else {
	echo "<meta http-equiv='refresh' content='0;url=index.php?act=login'>";
}
?>