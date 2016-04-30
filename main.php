<?php 

if (!defined('IN_MEDIA')) die("Hacking attempt");
$html =$tpl->get_tpl('main');

//$t['row'] = $tpl->get_block_from_str($html,'list_row',1);
$q_rs = $mysql->query("SELECT m_id, m_title, m_viewed, m_singer, m_poster FROM ".$tb_prefix."data ORDER BY RAND() LIMIT 12");

if ($mysql->num_rows($q_rs)) {
	while ($rs = $mysql->fetch_array($q_rs)) {
		static $c = 0;
		if($c == 1) {
			$cleartag = '<div class="clearfix visible-xs"></div>';
			$c = 0;
		} else {
			$cleartag = '';
		}
		$random.= $tpl->assign_vars($tpl->get_block_from_str($html,'random.row',1),
				array(
						'song.ID' => $rs['m_id'],
						'song.URL'	=>	'index.php?act=song&id='.$rs['m_id'],
						'song.TITLE' => $rs['m_title'],
						'song.VIEWED' => $rs['m_viewed'],
						'song.IMG'		=> $rs['m_poster'],
						'singer.NAME'	=> m_get_data('SINGER',$rs['m_singer']),
						'singer.URL'	=> 'index.php?act=singers&id='.$rs['m_singer'],
						'cleartag'		=> $cleartag,
				)
				);
		$c++;
	}
} else {
	$html = $tpl->unset_block($html,array('random_songs'));
}

//$ns['row'] = $tpl->get_block_from_str($html,'newsong.row',1);
$q_ns = $mysql->query("SELECT m_id, m_title, m_viewed, m_singer, m_poster FROM ".$tb_prefix."data ORDER BY m_id DESC LIMIT 8");
if ($mysql->num_rows($q_ns)) {
	while ($rz = $mysql->fetch_array($q_ns)) {
		$nslist .= $tpl->assign_vars($tpl->get_block_from_str($html,'newsong.row',1),
				array(
						'song.ID' => $rz['m_id'],
						'song.URL'	=>	'index.php?act=song&id='.$rz['m_id'],
						'song.TITLE' => $rz['m_title'],
						'song.VIEWED' => $rz['m_viewed'],
						'song.IMG'		=> $rz['m_poster'],
						'singer.NAME'	=> m_get_data('SINGER',$rz['m_singer']),
						'singer.URL'	=> 'index.php?act=singers&id='.$rz['m_singer'],
					)
				);
	}

}

$q_ts = $mysql->query("SELECT m_id, m_title, m_viewed, m_singer, m_poster FROM ".$tb_prefix."data ORDER BY m_viewed DESC LIMIT 5");
if ($mysql->num_rows($q_ts)) {
	while ($rt = $mysql->fetch_array($q_ts)) {
		static $i = 1;
		$tslist .= $tpl->assign_vars($tpl->get_block_from_str($html,'topsong.row',1),
				array(
						'song.ID' => $rt['m_id'],
						'song.URL'	=>	'index.php?act=song&id='.$rt['m_id'],
						'song.TITLE' => $rt['m_title'],
						'song.VIEWED' => $rt['m_viewed'],
						'song.NUM'		=> $i,
						'song.IMG'		=> $rt['m_poster'],
						'singer.NAME'	=> m_get_data('SINGER',$rt['m_singer']),
						'singer.URL'	=> 'index.php?act=singers&id='.$rt['m_singer'],
				)
				);
		$i++;
	}

}
$html = $tpl->assign_blocks_content($html,
		array(
				'newsong'	=>	$nslist,
				'topsong'	=>  $tslist,
				'random'	=>	$random,
		)
		);


loadPage($html);
?>