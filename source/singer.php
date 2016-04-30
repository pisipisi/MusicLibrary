<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if (is_numeric($_GET['id'])) {
	$ok = false;
	$singer_id = $_GET['id'];
	$main = $tpl->get_tpl('singer_info');
	
	# SINGER
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer WHERE singer_id = '".$singer_id."'");
	$r = $mysql->fetch_array($q);
	$singer_info = ($r['singer_info'])?$r['singer_info']:'Empty';
	$singer_info = m_text_tidy($singer_info);
	$main = $tpl->assign_vars($main,
			array(
				'singer.NAME'	=> $r['singer_name'],
				'singer.INFO'	=> $singer_info,
				'singer.IMG'	=> m_get_data('SINGER',$singer_id,'singer_img'),
			)
	);
	
	# SONG
	$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
$q = $mysql->query("SELECT m_id, m_title, m_type, m_viewed, m_downloaded, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric FROM ".$tb_prefix."data WHERE m_singer = '".$r['singer_id']."' ORDER BY m_viewed DESC LIMIT 10");
if ($mysql->num_rows($q)) {
	while ($rz = $mysql->fetch_array($q)) {
		static $i = 0;
		$list .= $tpl->assign_vars($t['row'],
				array(
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
	$main = $tpl->assign_blocks_content($main,
			array(
					'list'	=>	$list,
			)
			);
}
else {
	$main = $tpl->unset_block($main,array('info_songs'));
}
	loadPage($main);
}
elseif (!$_GET['id']) {
	$m_per_page = 20;
	$page = $_GET['page'];
	if (!$page) $page = 1;
	$limit = ($page-1)*$m_per_page;
	
	
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer ORDER BY singer_id DESC LIMIT ".$limit.",".$m_per_page);
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(singer_id) FROM ".$tb_prefix."singer"));
	$tt = $tt[0];
	
	if ($mysql->num_rows($q)) {
		$cat_tit = 'Artists';
		$z = $tpl->get_tpl('artists');
		$t['row'] = $tpl->get_block_from_str($z,'list_row',1);
	
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$html .= $tpl->assign_vars($t['row'],
					array(
							'singer.ID' 	=> 	$r['singer_id'],
							'singer.IMG'	=>	$r['singer_img'],
							'singer.URL'	=>	'index.php?act=singers&id='.$r['singer_id'],
							'singer.NAME'	=>	$r['singer_name'],
					)
					);
			$i++;
		}
		$z = $tpl->assign_vars($z,
				array(
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
	}
}
?>