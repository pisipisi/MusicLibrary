<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if (!$isLoggedIn) {
	echo "<center><b>You have not login </b></center>";
	echo "<meta http-equiv='refresh' content='0;url=index.php?act=login'>";
	exit();
}
else {
	$mysql->query("UPDATE ".$tb_prefix."user SET user_lastvisit = '".NOW."', user_identifier = '', user_timeout = '', user_ip = '', user_online = 0 WHERE user_id = '".$_SESSION['user_id']."'");
	m_setcookie('INFO', '', false);
	session_destroy();
	header('Location: index.php');
	exit;

	
}
?>