<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");

	if ($_GET['id'] == 0) {
		ob_end_clean();
		echo "<center><b>Song does not exist</b></center>";
		exit();
	}
	
	if($_POST['comment']) {
		$date = new DateTime();		
		$mysql->query("INSERT INTO ".$tb_prefix."comment (comment_media_id, comment_poster, comment_content, comment_time) VALUES ('1".$_GET['id']."','".$_POST['name']."','".$_POST['comment']."', '".$date->format('Y-m-d H:i:s')."')" );		
	}
$html = $tpl->get_tpl('info');
$t['row'] = $tpl->get_block_from_str($html,'list_row',1);
$r = $mysql->fetch_array($mysql->query("SELECT * FROM ".$tb_prefix."data WHERE m_id = ".$_GET['id']));
$song_page = '/index.php?act=song&id=';
$html = $tpl->assign_vars($html,
		array(
				'info.ID'		=> $r['m_id'],
				'info.DATA'		=> '1'.$r['m_id'],
				'info.IMG'		=> $r['m_poster'],
				'info.INFO'	=> $r['m_lyric'],
				'info.NAME'	=> $r['m_title'],
				'info.SURL' => urlencode(m_get_config('web_url').$song_page.$r['album_id']),
				'singer.URL'	=> '?act=singers&id='.$r['m_singer'],
				'singer.NAME'	=> m_get_data('SINGER',$r['m_singer']),
					
		)
		);

$q = $mysql->query("SELECT m_id, m_title, m_type, m_viewed, m_downloaded, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric FROM ".$tb_prefix."data WHERE m_singer = '".$r['m_singer']."' ORDER BY m_viewed DESC LIMIT 10");
if ($mysql->num_rows($q)) {
	while ($rz = $mysql->fetch_array($q)) {
		static $i = 0;
		$media_type = "<img src='{TPL_LINK}/img/media/type/$media_type.gif'>";
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


?>