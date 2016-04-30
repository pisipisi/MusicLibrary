<?php

define('IN_MEDIA',true);
date_default_timezone_set('UTC');
include('includes/config.php');
include('includes/functions.php');
include('includes/class_template.php');

$tpl = new Template;

$isLoggedIn = m_checkLogin();

if ($_GET['act'] == "register") {
	include('register.php');
	exit();
}
elseif ($_GET['act'] == "login") {
	include('login.php');
	exit();
}
elseif ($_GET['act'] == "logout") {
	include("logout.php");
	exit();
}
elseif ($_GET['act'] == "genres") {
	include("list.php");
	exit();
}
elseif (in_array($_GET['act'], array('album', 'Play_Album', 'List_Album'))) {
	include("album.php");
	exit();
}
elseif ($_GET['act'] == "showpl") {
	include("showpl.php");
	exit();
}
elseif (in_array($_GET['act'], array('get_media', 'playlist')) || $_POST['action'] == 'playlist') {
	if($_GET['act'] == 'playlist' && !$isLoggedIn) {
		include('login.php');
	} else {
		include('admin-ajax.php');
	}
	exit();
}
elseif ($_GET['act'] == "song") {
	include('song.php');
	exit();
}

elseif ($_GET['act'] == "singers") {
	include('singer.php');
	exit();
}
elseif (isset($_POST['change_info']) && $isLoggedIn) {
	include('user.php');
	exit();
}
elseif (isset($_POST['forgot']) && isset($_POST['email'])) {
	include('user.php');
	exit();
}
elseif (isset($_POST['reloadPlaylist'])) {
	include('playlist.php');
	exit();
}

$value = array();



if (($_GET['act'] == 'Download') && is_numeric($_GET['id'])) {
	if (!$isLoggedIn && m_get_config('must_login_to_download')) {
		header("Content-Disposition: attachment; filename = You_must_login.txt");
		echo "You must login to download";
		exit();
	}
	$r = $mysql->fetch_array($mysql->query("SELECT m_url, m_is_local FROM ".$tb_prefix."data WHERE m_id = '".$_GET['id']."'"));
		if ($r) {
			$mysql->query("UPDATE ".$tb_prefix."data SET m_downloaded = m_downloaded + 1, m_downloaded_month = m_downloaded_month + 1 WHERE m_id = '".$_GET['id']."'");
			$url = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
			
			header ("Content-type: octet/stream");
			header ("Content-disposition: attachment; filename=".$url.";");
			header("Content-Length: ".filesize($url));
			readfile($file);
		}
		exit();
}


$_SESSION['current_tpl'] = $_COOKIE['MEDIA_TPL'];

if (!$url) {
	//loadPage($tpl->get_tpl('main'));
	include ('main.php');
	exit();
}

?>