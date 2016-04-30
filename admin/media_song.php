<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=song&mode=edit';

$inp_arr = array(
		'title'		=> array(
			'table'	=>	'm_title',
			'name'	=>	'Song Name',
			'type'	=>	'free',
		),
		'singer'	=> array(
			'table'	=>	'm_singer',
			'name'	=>	'Singer',
			'type'	=>	'function::acp_singer::number',
		),
		'new_singer'	=>	array(
			'name'	=>	'Or Enter New Singer',
			'type'	=>	'function::acp_quick_add_singer_form::free',
			'desc'	=>	'System will automatic create ne singer in database',
			'can_be_empty'	=>	true,
		),
		'album'		=> array(
			'table'	=>	'm_album',
			'name'	=>	'Album',
			'type'	=>	'function::acp_album_list::number',
		),
		'cat'		=> array(
			'table'	=>	'm_cat',
			'name'	=>	'Category',
			'type'	=>	'function::acp_cat::number'
		),
		'type_media'	=> array(
			'table'	=>	'm_type',
			'name'	=>	'Type',
			'type'	=>	'hidden_value',
			'change_on_update'	=>	true,
		),
		'url'		=> array(
			'table'	=>	'm_url',
			'name'	=>	'Link',
			'type'	=>	'free',
		),
		'poster'		=>	array(
				'table'	=>	'm_poster',
				'name'  =>  'Poster',
				'type'	=>	'free',
		),
		'local_url'	=> array(
			'table'	=>	'm_is_local',
			'name'	=>	'Local URL',
			'type'	=>	'checkbox',
			'checked'	=>	false,
			'can_be_empty'	=>	true,
		),
		'lyric'			=> array(
			'table'		=>	'm_lyric',
			'name'		=>	'Lyric',
			'type'		=>	'text',
			'can_be_empty'	=>	1
		),
		'date'		=>	array(
			'table'	=>	'm_date',
			'type'	=>	'hidden_value',
			'value'	=>	date("Y-m-d",NOW),
		),
		'title_ascii'	=>	array(
			'table'	=>	'm_title_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
);

##################################################
# ADD MEDIA
##################################################
if ($_GET['mode'] == 'add') {
	acp_check_permission('add_media');
	
	if (isset($_POST['formsubmit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			
			$inp_arr['title_ascii']['value'] = strtolower(utf8_to_ascii($title));
			$inp_arr['type_media']['value'] = acp_type($url);

			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
			
			if ($_POST['new_singer'] && $_POST['singer_type']) {
				$singer = acp_quick_add_singer($_POST['new_singer'],$_POST['singer_type']);
			}
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'data'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Done <meta http-equiv='refresh' content='0;url=$link'>";
			//echo '<h1> Simple Tables <small>preview of simple tables'.$sql.'</small> </h1>';
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Add Media',$inp_arr,$error_arr);
}
elseif ($_GET['mode'] == 'multi_add') {
	acp_check_permission('add_media');
	include('media_multi_song.php');
}
##################################################
# EDIT MEDIA
##################################################
if ($_GET['mode'] == 'edit') {
	if ($_GET['m_del_id']) {
		acp_check_permission('del_media');
		$mysql->query("DELETE FROM ".$tb_prefix."data WHERE m_id = '".$_GET['m_del_id']."'");
		echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		exit();
		
	}
	elseif (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Lỗi');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_media');
			
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."data WHERE m_id IN (".$in_sql.")");
			echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		acp_check_permission('edit_media');
		
		if ($_POST['selected_option'] == 'multi_edit') {
			$arr = implode(',',$arr);
			header("Location: ./?act=song_multi_edit&id=".$arr);
		}
		elseif ($_POST['selected_option'] == 'normal') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."data SET m_is_broken = 0 WHERE m_id IN (".$in_sql.")");
			echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		exit();
	}
	elseif ($_GET['m_id']) {
		acp_check_permission('edit_media');
		if (!isset($_POST['formsubmit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."data WHERE m_id = '".$_GET['m_id']."'");
			if (!$mysql->num_rows($q)) {
				echo "This song doesn't exist in database.";
				exit();
			}
			$r = $mysql->fetch_array($q);
				
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
			$inp_arr['local_url']['checked'] = $local_url;
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['title_ascii']['value'] = strtolower(utf8_to_ascii($title));
				$inp_arr['type_media']['value'] = acp_type($url);
				
				if ($_POST['new_singer'] && $_POST['singer_type']) {
					$singer = acp_quick_add_singer($_POST['new_singer'],$_POST['singer_type']);
				}
				
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'data','m_id',$_GET['m_id']),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Edit Media',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_media');
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		$search = urldecode($_GET['search']);
		$extra = (($search)?"m_title_ascii LIKE '%".$search."%' ":'');
		if ($show_broken) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."data WHERE m_is_broken = 1 ".(($extra)?"AND ".$extra." ":'')."ORDER BY m_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
			$tt = m_get_tt('m_is_broken = 1 '.(($extra)?"AND ".$extra." ":''));
			echo "<a href=?act=song&mode=edit><b>Song List</b></a><br><br>";
		}
		else {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."data ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY m_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
			$tt = m_get_tt($extra);
			echo "<a href=".$link."&show_broken=1><b>Reported Song List</b></a><br><br>";
		}
		if ($mysql->num_rows($q)) {
			if ($search) {
				$link2 = preg_replace("#&search=(.*)#si","",$link);
			}
			else $link2 = $link;
			
			echo '      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Song Manager
            <small>Edit or Delete Song</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Song</a></li>
            <li class="active">Edit</li>
          </ol>
        </section>
			<!-- Main content -->
        <section class="content">';
			echo "<script>function check_del(id) {".
					"if (confirm('Are you sure?')) location='?act=song&mode=del&m_id='+id;".
					"return false;}</script>";
			echo '<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Song Manager</h3>
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" value="'.$search.'" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">';
              echo "        <button class='btn btn-sm btn-default' onclick='window.location.href = \"".$link2."&search=\"+document.getElementById(\"search\").value;'><i class='fa fa-search'></i></button>";
              echo '       </div>
                    </div>
                  </div>
                </div><!-- /.box-header -->
				<form role="form" method=POST>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Check</th>
                      <th>Song Name</th>
                      <th>Singer</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>';
			
		//	echo "ID của Media cần <b>sửa</b>: <input id=m_id size=20> <input type=button onclick='window.location.href = \"".$link."&m_id=\"+document.getElementById(\"m_id\").value;' value=Sửa><br><br>";
		//	echo "ID của Media cần <b>xóa</b>: <input id=m_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&m_del_id=\"+document.getElementById(\"m_del_id\").value;' value=Xóa><br><br>";
		//	echo "Tìm Media : <input id=search size=20 value=\"".$search."\"> <input type=button onclick='window.location.href = \"".$link2."&search=\"+document.getElementById(\"search\").value;' value=Tìm><br><br>";
		//	echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
		//	echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Tên Media</td><td class=title>Ca sĩ</td><td class=title>Lỗi</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['m_id'];
				$title = $r['m_title'];
				$singer = $r['m_singer'];
				$broken = ($r['m_is_broken'])?'<span class="label label-danger">Broken</span>':'<span class="label label-success">Active</span>';
				echo "<tr>
						<td><input type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td>
						<td><a href='$link&m_id=".$id."'><b>".$title."</b></a></td>
						<td><b><a href=?act=singer&mode=edit&singer_id=".$singer.">".m_get_data('SINGER',$singer)."</a></b></td>
						<td>".$broken."</td>
						<td><a href=# onclick=check_del(".$r['m_id'].")>Delete</a></td>
					</tr>";
			
			
			}
			echo "<tr><td colspan=5>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=5 align="center">Selected songs : '.
				'<select name=selected_option><option value=multi_edit>Edit</option><option value=del>Delete</option><option value=normal>Ignore Report</option></select>'.
				' <button type="submit" name="do" class="btn btn-primary">Submit</button>';
		//	echo '</form></table>';
			
			echo '</table>
			
                </div><!-- /.box-body -->
              		</form>
              </div><!-- /.box -->
			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->';
		}
		else echo "You don't have any song";
	}
}
?>