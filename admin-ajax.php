<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if (!defined('IN_MEDIA')) die("Hacking attempt");

if($_GET['act'] == 'get_media') {
$glist = substr($_GET['id'], 0, 1);
$gid =  substr($_GET['id'], 1);
//Play each song 
if($glist == 1 && $gid > 0) {
	$m_id = intval($gid);
	$q = $mysql->query("SELECT m_id, m_title, m_singer, m_url, m_poster FROM ".$tb_prefix."data WHERE m_id = '".$m_id."'");
	if (!$mysql->num_rows($q)) {
		die("<center><b>This song doesn't exist.</b></center>");
	}
	$r = $mysql->fetch_array($q);
	
	if(intval($r['m_singer']) > 0) {
		$q = $mysql->query("SELECT singer_name, singer_info FROM ".$tb_prefix."singer WHERE singer_id = '".$r['m_singer']."'");
		$s = $mysql->fetch_array($q);
		$singer = $s['singer_name'];
	} else {
		$singer = 'Unknown';
	}
		
	$mysql->query("UPDATE ".$tb_prefix."data SET m_viewed = m_viewed + 1, m_viewed_month = m_viewed_month + 1 WHERE m_id = '".$rz['m_id']."'");
	$json = array(array('id' => intval($_GET['id']),
						'ids' => intval($_GET['id']),
						'title' => $r['m_title'],
						'mp3' => $r['m_url'],
						'artist' => $singer,
						'poster' => $r['m_poster']
		));
		
	echo json_encode($json);
	
	//play whole album
} elseif ($glist == 2 && $gid > 0) {
	$q = $mysql->query("SELECT m_id, m_title, m_type, m_viewed, m_poster, m_url FROM ".$tb_prefix."data WHERE m_album = '".$gid."' ORDER BY m_viewed DESC");
	if ($mysql->num_rows($q)) {
		$list = array();
		while ($rz = $mysql->fetch_array($q)) {
			$mysql->query("UPDATE ".$tb_prefix."data SET m_viewed = m_viewed + 1, m_viewed_month = m_viewed_month + 1 WHERE m_id = '".$gid."'");
			array_push($list, array('id' => intval('1'.$rz['m_id']),
						'ids' => intval($_GET['id']),
						'title' => $rz['m_title'],
						'mp3' => $rz['m_url'],
						'artist' => m_get_data('SINGER',$rz['m_singer']),
						'poster' => $rz['m_poster'],
						));
		}
	}
	echo json_encode($list);
} 

elseif ($glist == 3 && $gid > 0) {
	$q = $mysql->query("SELECT playlist_contents FROM ".$tb_prefix."playlist WHERE playlist_id = '".$gid."'");
	if ($mysql->num_rows($q)) {
		$rz = $mysql->fetch_array($q);
		$list = array();
		$songs = explode(":", $rz['playlist_contents']);
		foreach ($songs as $song) {
			$gid =  substr($song, 1);
			$q = $mysql->query("SELECT m_id, m_title, m_viewed, m_url, m_poster FROM ".$tb_prefix."data WHERE m_id = '".intval($gid)."'");
			$rz = $mysql->fetch_array($q);
			array_push($list,array('id' => intval('1'.$rz['m_id']),
						'ids' => intval($_GET['id']),
						'title' => $rz['m_title'],
						'mp3' => $rz['m_url'],
						'artist' => m_get_data('SINGER',$rz['m_singer']),
						'poster' => $rz['m_poster'],
					)
					);
		}
	}
	echo json_encode($list);
}

} elseif ($_GET['act'] == 'playlist') {
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."playlist WHERE playlist_user_id = '".$_SESSION['user_id']."' ORDER BY playlist_title DESC");
	if ($mysql->num_rows($q)) {
		$list = array();
		while ($rz = $mysql->fetch_array($q)) {
			array_push($list, array(
					'id' => intval($rz['playlist_id']),
					'title' => $rz['playlist_title'],
					'thumb' => $rz['playlist_thumb'],
					'tracks' => explode(":", $rz['playlist_contents']),	
					'url'	=> 'index.php?act=showplaylist&id='.$rz['playlist_id'],
					));
		}
	}
	
	$playlist = array('status' => 1,
						'type'  => 0,
						'playlist' =>  $list,
						);
	echo json_encode($playlist);	

} elseif ($_POST['action'] == 'playlist') {
	if($_POST['type'] == 1) {
		$mysql->query("INSERT INTO ".$tb_prefix."playlist (playlist_user_id, playlist_thumb, playlist_title, playlist_contents) 
		VALUES ('".$_SESSION['user_id']."','".m_get_data('SONG',substr($_POST['id'], 1), 'm_poster')."','".$_POST['title']."','".$_POST['id']."')");
	}
	if($_POST['type'] == 2) {
		$mysql->query("DELETE FROM ".$tb_prefix."playlist WHERE playlist_id = '".$_POST['pid']."'");
	}
	
	if($_POST['type'] == 3) {
		$new_track = implode(":", $_POST['tracks']);
		$mysql->query("UPDATE ".$tb_prefix."playlist SET playlist_contents = '".$new_track."'  WHERE playlist_id = '".$_POST['pid']."'");
				
	//		$mysql->query("UPDATE ".$tb_prefix."playlist SET playlist_contents = CONCAT(playlist_contents,':".$_POST['id']."')  WHERE playlist_id = '".$_POST['pid']."'");

		}
		
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."playlist WHERE playlist_user_id = '".$_SESSION['user_id']."' ORDER BY playlist_title DESC");
	if ($mysql->num_rows($q)) {
		$list = array();
		while ($rz = $mysql->fetch_array($q)) {
			array_push($list, array(
					'id' => intval($rz['playlist_id']),
					'title' => $rz['playlist_title'],
					'thumb' => $rz['playlist_thumb'],
					'tracks' => explode(":", $rz['playlist_contents']),
					'url'	=> 'index.php?act=showplaylist&id='.$rz['playlist_id'],
			));
		}
	}
	
	$playlist = array('status' => 1,
			'type'  => 0,
			'playlist' =>  $list,
	);
	echo json_encode($playlist);

}

?>
