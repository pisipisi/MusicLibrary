<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
function box_main_menu($file_tpl = 'main_menu') {
	global $tpl;
	return $tpl->get_box($file_tpl);
}

function box_gift($file_tpl = 'gift') {
	global $tpl;
	return $tpl->get_box($file_tpl);
}

function box_user_menu($file_tpl_1 = 'user_logged',$file_tpl_2 = 'user_guest') {
	global $mysql, $isLoggedIn, $tpl;
	if ($isLoggedIn) {
		$html = $tpl->get_box($file_tpl_1);
		$html = $tpl->assign_vars($html,
			array(
				'user.NAME'	=>	m_get_data('USER',$_SESSION['user_id']),
			)
		);
	}
	else {
		$html = $tpl->get_box($file_tpl_2);
	}
	return $html;
}

function box_category_menu($file_tpl = 'category_menu') {
	global $mysql,$tb_prefix,$tpl;
	$main = $tpl->get_box($file_tpl);
	$t['parent'] = $tpl->get_block_from_str($main,'cat_list.parent',1);
	$t['sub'] = $tpl->get_block_from_str($main,'cat_list.sub',1);
	
	$q = $mysql->query("SELECT cat_id, cat_name FROM ".$tb_prefix."cat WHERE sub_id IS NULL OR sub_id = 0 ORDER BY cat_order ASC");
	while ($r = $mysql->fetch_array($q)) {
		if($_GET['id'] == $r['cat_id']) $active = 'active'; else $active = '';
		$html .= $tpl->assign_vars($t['parent'],
			array(
				'cat_parent.URL' => 'index.php?act=genres&id='.$r['cat_id'],
				'cat_parent.NAME' => $r['cat_name'],
				'cat_parent.ACTIVE' => $active,
			)
		);
		$q2 = $mysql->query("SELECT cat_id, cat_name FROM ".$tb_prefix."cat WHERE sub_id = '".$r['cat_id']."' ORDER BY cat_order ASC");
		while ($r2 = $mysql->fetch_array($q2)) {
			if($_GET['id'] == $r2['cat_id']) $sub_active = 'active'; else $sub_active = '';
			$html .= $tpl->assign_vars($t['sub'],
				array(
					'cat_sub.URL' => 'index.php?act=genres&id='.$r2['cat_id'],
					'cat_sub.NAME' => $r2['cat_name'],
					'cat_sub.ACTIVE' => $sub_active,
						
				)
			);
		}
	}
	$html = $tpl->assign_blocks_content($main,array(
		'cat_list'	=>	$html
		)
	);
	return $html;
}

function box_comment_list($dataID) {
	global $mysql,$tb_prefix,$tpl;
	$main = $tpl->get_box('comment_list');
	$glist = substr($dataID, 0, 1);
	$gid =  substr($dataID, 1);
	$result = $mysql->query("SELECT * FROM ".$tb_prefix."comment WHERE comment_media_id = '".$dataID."' ORDER BY comment_time DESC");
	
	$t = $tpl->get_block_from_str($main,'comment.row',1);
	if (!$mysql->num_rows($result)) $html = $tpl->unset_block($main,array('comment_list'));	
	while ($r = $mysql->fetch_array($result)) {
		$html .= $tpl->assign_vars($t,
				array(
						'comment.POSTER'		=>	$r['comment_poster'],
						'comment.CONTENT'	=>	$r['comment_content'],
						'comment.IMG'		=>	'{TPL_LINK}/images/a0.png',
				)
				);
		
	}
	$html = $tpl->assign_blocks_content($main,array(
			'comment'	=>	$html
	)
			);
	return $html;
}
function box_comment($songID, $file_tpl = 'comment') {
	global $mysql,$tb_prefix,$tpl, $isLoggedIn;
	$html = $tpl->get_box($file_tpl);
	$glist = substr($songID, 0, 1);
	$gid =  substr($songID, 1);
	if($isLoggedIn) {
	$html = $tpl->unset_block($html,array('guest_block'));	
	$html = $tpl->assign_vars($html,
			array(
					'user.NAME'	=>	m_get_data('USER',$_SESSION['user_id']),
					'song.ID' => $gid,
					'data.ID' => $songID,
					'data.ADDRESS' => $_SERVER['HTTP_HOST'],
			)
			);
	} else {
		$html = $tpl->unset_block($html,array('user_block'));
		$html = $tpl->assign_vars($html,
				array(
						'song.ID' => $gid,
						'data.ID' => $songID,
						'data.ADDRESS' => $_SERVER['HTTP_HOST'],
				)
				);
	}
	return $html;
}

function box_album($type = 'New', $number = 10, $apr = 1, $file_tpl = 'new_album') {
	global $mysql,$tb_prefix,$tpl;
	if ($type == 'New') {
		$result = $mysql->query("SELECT album_id, album_name, album_singer, album_img FROM ".$tb_prefix."album ORDER BY album_id DESC LIMIT $number");
		$block = 'new_album';
	}
	$main = $tpl->get_box($file_tpl);
	
	$t['link'] = $tpl->get_block_from_str($main,$block.'.row',1);
	$t['begin_tag'] = $tpl->get_block_from_str($main,$block.'.begin_tag',1);
	$t['end_tag'] = $tpl->get_block_from_str($main,$block.'.end_tag',1);
	
	if (!$mysql->num_rows($result)) $html = "Empty";
	$i = 0;
	while ($r = $mysql->fetch_array($result)) {
		$album_img = m_get_img('Album',$r['album_img']);
		if ($t['begin_tag'] && fmod($i,$apr) == 0) $html .= $t['begin_tag'];
		$html .= $tpl->assign_vars($t['link'],
			array(
				'album.URL'		=>	'#Album,'.$r['album_id'],
				'album.NAME'	=>	$r['album_name'],
				'album.IMG'		=>	$album_img,
				'singer.URL'	=>	'#Singer,'.$r['album_singer'],
				'singer.NAME'	=>	m_get_data('SINGER',$r['album_singer']),
			)
		);
		if ($t['end_tag'] && fmod($i,$apr) == $apr - 1) $html .= $t['end_tag'];
		$i++;
	}
	if ($t['end_tag'] && fmod($i,$apr) != $apr - 1) $html .= $t['end_tag'];
	
	$html = $tpl->assign_blocks_content($main,array(
		'new_album'	=>	$html
		)
	);
	return $html;
}

function box_stats($file_tpl = 'stats') {
	global $mysql,$tb_prefix,$tpl;
	$html = $tpl->get_box($file_tpl);
	$r = $mysql->fetch_array($mysql->query("SELECT SUM(m.m_viewed) views, COUNT(m.m_id) songs, SUM(m.m_downloaded) downloads FROM ".$tb_prefix."data m"));
	extract($r);
	$r = $mysql->fetch_array($mysql->query("SELECT COUNT(singer_id) singers FROM ".$tb_prefix."singer"));
	extract($r);
	$r = $mysql->fetch_array($mysql->query("SELECT COUNT(user_id) users FROM ".$tb_prefix."user"));
	extract($r);
	$r = $mysql->fetch_array($mysql->query("SELECT COUNT(album_id) albums FROM ".$tb_prefix."album"));
	extract($r);
	$html = $tpl->assign_vars($html,
		array(
			'stat.SINGERS'	=>	$singers,
			'stat.SONGS'	=>	$songs,
			'stat.ALBUMS'	=>	$albums,
			'stat.USERS'	=>	$users,
			'stat.VIEWS'	=>	max(0,$views),
			'stat.DOWNLOADS'	=>	max(0,$downloads),
			'stat.COUNTER'	=>	m_counter(),
		)
	);
	return $html;
}

function box_tpl_list($file_tpl = 'tpl_list') {
	global $mysql,$tpl,$tb_prefix;
	$list = "<select name=tpl_name>";
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."tpl ORDER BY 'tpl_order' ASC");
	while ($r = $mysql->fetch_array($q)) {
		$list .= "<option value='".$r['tpl_sname']."'".(($_SESSION['current_tpl']==$r['tpl_sname'])?' selected':'').">".$r['tpl_fname']."</option>";
	}
	$list .= "</select>";
	$html = $tpl->get_box($file_tpl);
	$html = $tpl->assign_vars($html,
		array(
			'TPL_LIST' => $list,
		)
	);
	return $html;
}

function box_announcement($file_tpl = 'announcement') {
	global $mysql, $tpl;
	$html = $tpl->get_box($file_tpl);
	$contents = stripslashes(m_get_config('announcement'));
	$contents = m_text_tidy($contents);
	if (!$contents) return '';
	$html = $tpl->assign_vars($html,
		array(
			'ANNOUNCEMENT'	=>	$contents,
		)
	);
	return $html;
}

function box_singer_list($type, $file_tpl = NULL) {
	global $mysql,$tb_prefix,$tpl;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer WHERE singer_type = '".$type."' ORDER BY singer_name ASC");
	switch ($type) {
		case 1 :
			if (!$file_tpl) $file_tpl = 'singer_vn';
			$block = 'vnsinger';
			$unknownID = -1;
		break;
		case 2 :
			if (!$file_tpl) $file_tpl = 'singer_fr';
			$block = 'frsinger';
			$unknownID = -2;
		break;
	}
	
	$main = $tpl->get_box($file_tpl);
	$t['link'] = $tpl->get_block_from_str($main,$block.'.row',1);
	
	$html = $tpl->assign_vars($t['link'],
		array(
			'singer.NAME' => 'Chưa biết',
			'singer.URL'	=>	'#Singer,'.$unknownID,
		)
	);
	while ($r = $mysql->fetch_array($q)) {
		$html .= $tpl->assign_vars($t['link'],
			array(
				'singer.NAME' => $r['singer_name'],
				'singer.URL'	=>	'#Singer,'.$r['singer_id'],
			)
		);
	}
	$html = $tpl->assign_blocks_content($main,array(
			$block	=>	$html
		)
	);
	return $html;
}

function box_top_media($type,$number = 10) {
	global $mysql,$tb_prefix,$tpl;
	if ($type == 'Download_Month') {
		$result = $mysql->query("SELECT m_id, m_title, m_singer, m_poster FROM ".$tb_prefix."data WHERE m_downloaded_month > 0 ORDER BY m_downloaded DESC LIMIT ".$number);
		$name = 'Top Download';
	}
	elseif ($type == 'Download') {
		$result = $mysql->query("SELECT m_id, m_title, m_singer, m_poster FROM ".$tb_prefix."data WHERE m_downloaded > 0 ORDER BY m_downloaded DESC LIMIT ".$number);
		$name = 'Top Download';

	}
	elseif ($type == 'Play_Month') {
		$result = $mysql->query("SELECT m_id, m_title, m_singer, m_poster FROM ".$tb_prefix."data WHERE m_viewed_month > 0 ORDER BY m_viewed DESC LIMIT ".$number);
		$name = 'Top Play';

	}
	elseif ($type == 'Play') {
		$result = $mysql->query("SELECT m_id, m_title, m_singer, m_poster FROM ".$tb_prefix."data WHERE m_viewed > 0 ORDER BY m_viewed DESC LIMIT ".$number);
		$name = 'Top Play';
	}
	elseif ($type == 'Newest') {
		$result = $mysql->query("SELECT m_id, m_title, m_singer, m_poster FROM ".$tb_prefix."data ORDER BY m_id DESC LIMIT ".$number);
		$name = 'Newest';

	}
	$main = $tpl->get_box('top5');
	$t['link'] = $tpl->get_block_from_str($main,'topfive.row',1);
	$n = 0;
	if (!$mysql->num_rows($result))  { $html = "Empty"; }
	else {
		while ($r = $mysql->fetch_array($result)) {
			if(intval($r['m_singer']) > 0) {
				$q = $mysql->query("SELECT singer_id, singer_name, singer_info FROM ".$tb_prefix."singer WHERE singer_id = '".$r['m_singer']."'");
				$s = $mysql->fetch_array($q);
				$singer = $s['singer_name'];
			} else {
				$singer = 'Unknown';
			}
			$html .= $tpl->assign_vars($t['link'],
				array(
					'song.ID' 		=> $r['m_id'],
					'song.TITLE' 	=> $r['m_title'],
					'song.URL'		=>	'index.php?act=song&id='.$r['m_id'],
					'singer.NAME'	=> $singer,
					'singer.ID'		=> $s['singer_id'],
					'song.IMG'		=> $r['m_poster'],
				)
			);
		}
	}
	$main = $tpl->assign_vars($main,
		array(
			'top.MONTH'	=> m_get_config('current_month'),
			'box.Name' 		=> $name,
		)
	);
	$main = $tpl->assign_blocks_content($main,array(
		'topfive'	=>	$html
		)
	);
	return $main;
}

function box_topsinger($number = 5) {
	global $mysql, $tpl, $tb_prefix;
	$main = $tpl->get_box('topsinger');
	$result = $mysql->query("SELECT m_singer, SUM(m_viewed) as sum_viewed FROM ".$tb_prefix."data GROUP BY m_singer ORDER BY sum_viewed DESC LIMIT ".$number);
	$t['row'] = $tpl->get_block_from_str($main,'topfive.row',1);
	
	if (!$mysql->num_rows($result))  { $html = "Empty"; }
	else {
		while ($r = $mysql->fetch_array($result)) {
			if(intval($r['m_singer']) > 0) {
				$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer WHERE singer_id = '".$r['m_singer']."'");
				$s = $mysql->fetch_array($q);
				$singer = $s['singer_name'];
			} else {
				$singer = 'Unknown';
			}
			$html .= $tpl->assign_vars($t['row'],
					array(
							'singer.ID' 		=> $s['singer_id'],
							'singer.URL'		=>	'index.php?act=singers&id='.$s['singer_id'],
							'singer.NAME'		=> $singer,
							'singer.IMG'		=> $s['singer_img'],
					)
					);
		}
	}
	$main = $tpl->assign_blocks_content($main,array(
			'topfive'	=>	$html
	)
			);
	return $main;
}

function box_playlist($reload = false, $file_tpl = 'playlist') {
	global $mysql, $tpl, $isLoggedIn, $tb_prefix, $add_id, $remove_id;
	$html = $tpl->get_box($file_tpl);
	if ($isLoggedIn) {
		$t['row'] = $tpl->get_block_from_str($html,'playlist.row',1);
		$content = '';
//		$playlist_id = m_get_data('USER',$_SESSION['user_id'],'user_playlist_id');
//		$playlist = m_get_data('PLAYLIST',$playlist_id);
//		$playlist = trim($playlist,',');
//		if ($playlist) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."playlist WHERE playlist_user_id = '".$_SESSION['user_id']."'");
			
			if ($mysql->num_rows($q)) {
				while ($r = $mysql->fetch_array($q)) {			
				$content .= $tpl->assign_vars($t['row'],
					array(
						'playlist.ID'		=>	$r['playlist_id'],
						'playlist.URL'		=>	'index.php?act=showpl&id='.$r['playlist_id'],
						'playlist.TITLE'	=>	$r['playlist_title'],
					)
				);
			}
		} else {
			$content = "<li><span>-</span></li>";
		}
		if ($reload) {
			return $content;
		} else {
			$html = $tpl->unset_block($html,array('guest_block'));
			
			$html = $tpl->assign_blocks_content($html,
				array(
					'playlist'	=>	$content,
				)
			);
		}
	}
	else {
		$html = $tpl->unset_block($html,array('user_block'));
	}
	return $html;
}

function box_ads($file_tpl = 'ads') {
	global $mysql,$tb_prefix,$tpl;
	$result = $mysql->query("SELECT * FROM ".$tb_prefix."ads ORDER BY ads_count DESC");
	$main = $tpl->get_box($file_tpl);
	$t['ads'] = $tpl->get_block_from_str($main,'ads.row',1);
	
	if (!$mysql->num_rows($result)) $html = "Empty";
	while ($r = $mysql->fetch_array($result)) {
		$html .= $tpl->assign_vars($t['ads'],
			array(
				'ads.IMG'	=>	$r['ads_img'],
				'ads.URL'	=>	$r['ads_url'],
				'ads.WEB'	=>	$r['ads_web'],
			)
		);
	}
	
	$html = $tpl->assign_blocks_content($main,array(
		'ads'	=>	$html
		)
	);
	
	return $html;
}
?>