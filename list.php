<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
$m_per_page = m_get_config('media_per_page');


$fields = "m_id, m_title, m_singer, m_type, m_poster, m_viewed, m_downloaded, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric";
$q = '';
$genre_id = $_GET['id'];
$page = $_GET['page'];
if (!$page) $page = 1;
$limit = ($page-1)*$m_per_page;
if ($_GET['act'] == 'genres') {
	if($genre_id) {
		
		$check = $mysql->fetch_array($mysql->query("SELECT sub_id FROM ".$tb_prefix."cat WHERE cat_id = '".$genre_id."'"));
		if (!is_null($check['sub_id']) && $check['sub_id'] != 0) {
			$q = "SELECT ".$fields." FROM ".$tb_prefix."data WHERE m_cat = '".$genre_id."' ORDER BY m_id DESC LIMIT ".$limit.",$m_per_page";
			$tt = m_get_tt("m_cat = '".$_GET['id']."'");
		}
		else {
			$list_q = $mysql->query("SELECT cat_id FROM ".$tb_prefix."cat WHERE sub_id = '".$genre_id."'");
			$in_sql = "'".$genre_id."',";
			while ($list_r = $mysql->fetch_array($list_q)) $in_sql .= "'".$list_r['cat_id']."',";
			$in_sql = substr($in_sql,0,-1);
	//		if (!$in_sql) $in_sql = $genre_id;
			$q = "SELECT ".$fields." FROM ".$tb_prefix."data WHERE m_cat IN ($in_sql) ORDER BY m_id DESC LIMIT ".$limit.",$m_per_page";
			$tt = m_get_tt("m_cat IN (".$in_sql.")");
		}
	} else {
		$q = "SELECT ".$fields." FROM ".$tb_prefix."data ORDER BY m_id DESC LIMIT ".$limit.",$m_per_page";
		$tt = m_get_tt();
	}
}
elseif ($_GET['act'] == "Top_Download" || $_GET['act'] == "Top_Play") {
	
	if ($_GET['act'] == 'Top_Download') $order = 'm_downloaded';
	elseif ($_GET['act'] == 'Top_Play') $order = 'm_viewed';
	
	$q = "SELECT ".$fields." FROM ".$tb_prefix."data ORDER BY ".$order." DESC LIMIT ".$limit.",$m_per_page";
	$tt = m_get_tt();
}
elseif ($_GET['act'] == 'Home') {	
	$q = "SELECT ".$fields." FROM ".$tb_prefix."data ORDER BY m_id DESC LIMIT ".$limit.",$m_per_page";
	$tt = m_get_tt();
}
if ($q) $q = $mysql->query($q);
if ($mysql->num_rows($q)) {
	if ($value[0] == 'List') {
		$cat_tit = $mysql->fetch_array($mysql->query("SELECT cat_name FROM ".$tb_prefix."cat WHERE cat_id = '$value[1]'"));
		$cat_tit = $cat_tit['cat_name'];
	}
	elseif ($_GET['act'] == 'Home') $cat_tit = 'Song list';
	
	$main = $tpl->get_tpl('list');
	$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
	
	$html = '';
	while ($r = $mysql->fetch_array($q)) {
		static $i = 0;
		$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
		
		$singer = m_get_data('SINGER',$r['m_singer']);
		$html .= $tpl->assign_vars($t['row'],
			array(
				'song.ID' => $r['m_id'],
				'song.URL' => '?act=song&id='.$r['m_id'],
				'song.TITLE' => $r['m_title'],
				'song.POSTER' => $r['m_poster'],
				'song.VIEWED' => $r['m_viewed'],
				'song.DOWNLOADED' => $r['m_downloaded'],
				'singer.NAME' => $singer,
				'singer.URL' => '?act=singers&id='.$r['m_singer'],
			)
		);
		$i++;
	}
	$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
	$main = $tpl->assign_vars($main,
		array(
			'CLASS' => $class,
			'CAT_TITLE' => $cat_tit,
			'TOTAL'	=> $tt,
			'VIEW_PAGES' => m_viewpages($tt,$m_per_page,$page),
		)
	);
	
	$main = $tpl->assign_blocks_content($main,array(
			'list'	=>	$html,
		)
	);
	loadPage($main);
//	$tpl->parse_tpl($main);
}
else echo "<center><b>This Genres hasn't had any song yet.</b></center>";

?>