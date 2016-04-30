<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=album&mode=edit';

$inp_arr = array(
		'album'	=> array(
			'table'	=>	'album_name',
			'name'	=>	'Album Name',
			'type'	=>	'free'
		),
		'singer'	=> array(
			'table'	=>	'album_singer',
			'name'	=>	'Singer',
			'type'	=>	'function::acp_singer::number',
		),
		'new_singer'	=>	array(
			'name'	=>	'New Singer',
			'type'	=>	'function::acp_quick_add_singer_form::free',
			'desc'	=>	'',
			'can_be_empty'	=>	true,
		),
		'img'	=> array(
			'table'	=>	'album_img',
			'name'	=>	'Album Picture',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'album_info'	=>	array(
			'table'	=>	'album_info',
			'name'	=>	'Album Info',
			'type'	=>	'text',
			'can_be_empty'	=>	true,
		),
		'album_ascii'	=>	array(
			'table'	=>	'album_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
	);
##################################################
# ADD ALBUM
##################################################
if ($_GET['mode'] == 'add') {
	acp_check_permission('add_album');
	if (isset($_POST['formsubmit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			if ($new_singer && $singer_type) {
				$singer = acp_quick_add_singer($new_singer,$singer_type);
			}
			$inp_arr['album_ascii']['value'] = strtolower(utf8_to_ascii($album));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'album'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "<meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Add Album',$inp_arr,$error_arr);
}
##################################################
# EDIT ALBUM
##################################################
if ($_GET['mode'] == 'edit') {
	if ($_GET['album_del_id']) {
		acp_check_permission('del_album');
				$mysql->query("DELETE FROM ".$tb_prefix."album WHERE album_id = '".$_GET['album_del_id']."'");
				$mysql->query("UPDATE ".$tb_prefix."data SET m_album = '' WHERE m_album = '".$_GET['album_del_id']."'");
				echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();

	}
	elseif (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Error');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_album');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."album WHERE album_id IN (".$in_sql.")");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	
	elseif ($_GET['album_id']) {
		acp_check_permission('edit_album');
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."album WHERE album_id = '".$_GET['album_id']."'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['album_ascii']['value'] = strtolower(utf8_to_ascii($album));
				
				if ($new_singer && $singer_type) {
					$singer = acp_quick_add_singer($new_singer,$singer_type);
				}
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'album','album_id',$_GET['album_id']),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Edit album',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_album');
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."album ORDER BY album_name ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(album_id) FROM ".$tb_prefix."album"));
		$tt = $tt[0];
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
				"if (confirm('Are you sure?')) location='?act=album&mode=edit&album_del_id='+id;".
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
                      <th>Album Name</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>';
			
		if ($tt) {
	//		echo "ID của album cần <b>sửa</b>: <input id=album_id size=20> <input type=button onclick='window.location.href = \"".$link."&album_id=\"+document.getElementById(\"album_id\").value;' value=Sửa><br><br>";
	//		echo "ID của album cần <b>xóa</b>: <input id=album_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&album_del_id=\"+document.getElementById(\"album_del_id\").value;' value=Xóa><br><br>";
			
	//		echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
	//		echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Album</td><td class=title>Ảnh</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['album_id'];
				$album = $r['album_name'];
				$img = ($r['album_img'])?"<img src=".$r['album_img']." width=50 height=50>":'';
				echo "<tr>
						<td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td>
						<td><b><a href=?act=album&mode=edit&album_id=".$id.">".$album."</a></b></td>
						<td>".$img."</td>
						<td><span class='label label-success'>Active</span></td>
						<td><a href=# onclick=check_del(".$id.")>Delete</a></td>
					</tr>";
			}
			echo "<tr><td colspan=5>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=5 align="center">Selected albums : '.
				'<select name=selected_option><option value=del>Delete</option>'.
				'<input type="submit" name="do" class=submit value="Submit"></td></tr>';
//			echo '</form></table>';
			
		}
		else echo "<tr><td colspan=5>Dont Have any Album</td></tr>";
		echo '</table>
		
                </div><!-- /.box-body -->
				
              		</form>
              </div><!-- /.box -->
			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->';
		
	}
}
?>
