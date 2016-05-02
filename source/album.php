<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
# ALBUM INFO
if ($_GET['act'] == "album" && is_numeric($_GET['id'])) {
	if ($_GET['id'] == 0) {
		ob_end_clean();
		echo "<center><b>Album does not exist</b></center>";
		exit();
	}
	
	if($_POST['comment']) {
		$date = new DateTime();
		$mysql->query("INSERT INTO ".$tb_prefix."comment (comment_media_id, comment_poster, comment_content, comment_time) VALUES ('2".$_GET['id']."','".$_POST['name']."','".$_POST['comment']."', '".$date->format('Y-m-d H:i:s')."')" );
	}
	
	$html = $tpl->get_tpl('info');
	$t['row'] = $tpl->get_block_from_str($html,'list_row',1);
	$r = $mysql->fetch_array($mysql->query("SELECT * FROM ".$tb_prefix."album WHERE album_id = ".$_GET['id']));
	$album_img = m_get_img('Album',$r['album_img']);
	$album_page = '/index.php?act=album&id=';
	$html = $tpl->assign_vars($html,
		array(
			'info.ID'		=> $r['album_id'],
			'info.DATA' 	=> '2'.$r['album_id'],
			'info.IMG'		=> $album_img,
			'info.INFO'	=> $r['album_info'],
			'info.NAME'	=> $r['album_name'],
			'info.SURL' => urlencode(m_get_config('web_url').$album_page.$r['album_id']),
			'singer.URL'	=> '?act=singers&id='.$r['album_singer'],
			'singer.NAME'	=> m_get_data('SINGER',$r['album_singer']),
			
		)
	);
	$q = $mysql->query("SELECT m_id, m_title, m_type, m_viewed, m_downloaded, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric FROM ".$tb_prefix."data WHERE m_album = '".$_GET['id']."' ORDER BY m_viewed DESC");
	if ($mysql->num_rows($q)) {
		while ($rz = $mysql->fetch_array($q)) {
			static $i = 0;
			$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
			$lyric = ($rz['m_lyric'])?"<img src='{TPL_LINK}/img/media/ok.gif'>":'';
			switch ($rz['m_type']) {
				case 1 : $media_type = 'music'; break;
				case 2 : $media_type = 'flash'; break;
				case 3 : $media_type = 'movie'; break;
			}
			$media_type = "<img src='{TPL_LINK}/img/media/type/$media_type.gif'>";
			$list .= $tpl->assign_vars($t['row'],
				array(
					'song.CLASS' => $class,
					'song.TYPE' => $media_type,
					'song.ID' => $rz['m_id'],
					'song.URL'	=>	'index.php?act=song&id='.$rz['m_id'],
					'song.TITLE' => $rz['m_title'],
					'song.VIEWED' => $rz['m_viewed'],
					'song.DOWNLOADED' => $rz['m_downloaded'],
					'song.LYRIC' => $lyric,
				)
			);
			$i++;
		}
		$html = $tpl->assign_blocks_content($html,
			array(
				'list'	=>	$list,
			)
		);
	}
	else {
		$html = $tpl->unset_block($html,array('info_songs'));
	}
	loadPage($html);
//	$tpl->parse_tpl($html);
}
# PLAY ALBUM
elseif ($_GET['act'] == 'Play_Album' && is_numeric($_GET['id'])) {
	if (!$isLoggedIn && m_get_config('must_login_to_play')) {
		echo "<b><center>You must login to play album</center></b>";
		exit();
	}
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."album WHERE album_id = '".$_GET['id']."'");
	if (!$mysql->num_rows($q) || $_GET['id'] == 0) {
		ob_end_clean();
		echo "<center><b>Album does not exist.</b></center>";
		exit();
	}
	$r = $mysql->fetch_array($q);
	$mysql->query("UPDATE ".$tb_prefix."album SET album_viewed = album_viewed + 1 WHERE album_id = '".$_GET['id']);
	play_album($r);
}
# ALBUM LIST
elseif ($_GET['act'] == 'List_Album') {
	$m_per_page = 20;
	$page = $_GET['page'];
	if (!$page) $page = 1;
	$limit = ($page-1)*$m_per_page;
	
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."album ORDER BY album_id DESC LIMIT ".$limit.",".$m_per_page);
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(album_id) FROM ".$tb_prefix."album"));
	$tt = $tt[0];
	if ($mysql->num_rows($q)) {
		$cat_tit = 'Albums';	
		$z = $tpl->get_tpl('albums');
		$t['row'] = $tpl->get_block_from_str($z,'list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
			
			$singer = m_get_data('SINGER',$r['album_singer']);
			$album_img = m_get_img('Album',$r['album_img']);
			$html .= $tpl->assign_vars($t['row'],
				array(
					'album.CLASS'	=>	$class,
					'album.ID' 		=> '2'.$r['album_id'],
					'album.IMG'		=>	$album_img,
					'album.URL'		=>	'index.php?act=album&id='.$r['album_id'],
					'album.NAME'	=>	$r['album_name'],
					'singer.NAME'	=>	$singer,
					'singer.URL'	=>	'#Singer,'.$r['album_singer'],
					'album.VIEWED'	=>	$r['album_viewed'],
				)
			);
			$i++;
		}
		$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
		$z = $tpl->assign_vars($z,
			array(
				'CLASS' => $class,
				'CAT_TITLE' => $cat_tit,
				'TOTAL'	=> $tt,
				'VIEW_PAGES' => m_viewpages($tt,$m_per_page,$page),
			)
		);
		
		$z = $tpl->assign_blocks_content($z,array(
				'list'	=>	$html,
			)
		);
		loadPage($z);
	//	$tpl->parse_tpl($z);
	}
	else echo "<center><b>Không có dữ liệu trong mục này.</b></center";
}
?>