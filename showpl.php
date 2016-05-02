<?php 
if (!defined('IN_MEDIA')) die("Hacking attempt");
		if ($_GET['id'] == 0) {
			ob_end_clean();
			echo "<center><b>Playlist does not exist</b></center>";
			exit();
		}
		$html = $tpl->get_tpl('playlist_info');
		$t['row'] = $tpl->get_block_from_str($html,'list_row',1);
		$r = $mysql->fetch_array($mysql->query("SELECT * FROM ".$tb_prefix."playlist WHERE playlist_id = ".$_GET['id']));
		$songs = explode(":", $r['playlist_contents']);
		$html = $tpl->assign_vars($html,
				array(
						'playlist.ID'		=> $r['playlist_id'],
						'playlist.DATA'		=> '3'.$r['playlist_id'],
						'playlist.IMG'		=> $r['playlist_thumb'],
						'playlist.NAME'		=> $r['playlist_title'],
							
				)
				);
		if($songs) {
			foreach ($songs as $song) {
				$gid =  substr($song, 1);
				$q = $mysql->query("SELECT m_id, m_title, m_type, m_viewed, m_downloaded FROM ".$tb_prefix."data WHERE m_id = '".intval($gid)."'");
				$rz = $mysql->fetch_array($q);
				$list .= $tpl->assign_vars($t['row'],
					array(
							'song.ID' => $rz['m_id'],
							'song.URL'	=>	'index.php?act=song&id='.$rz['m_id'],
							'song.TITLE' => $rz['m_title'],
							'song.VIEWED' => $rz['m_viewed'],
							'song.DOWNLOADED' => $rz['m_downloaded'],
					)
					);
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
	


?>