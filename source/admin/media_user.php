<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=user&mode=edit';

$inp_arr = array(
		'name'		=> array(
			'table'	=>	'user_name',
			'name'	=>	'Username',
			'type'	=>	'free',
		),
		'email'	=> array(
			'table'	=>	'user_email',
			'name'	=>	'Email',
			'type'	=>	'free',
		),
		'password'	=> array(
			'table'	=>	'user_password',
			'name'	=>	'Password',
			'type'	=>	'password',
			'always_empty'	=>	true,
			'update_if_true'	=>	'trim($password) != ""',
			'can_be_empty'	=>	true,
		),
		'level'	=> array(
			'table'	=>	'user_level',
			'name'	=>	'User Level',
			'type'	=>	'function::acp_user_level::number',
		),
		'sex'	=> array(
			'table'	=>	'user_sex',
			'name'	=>	'Gender',
			'type'	=>	'function::acp_user_sex::number',
		),
		'date'		=>	array(
			'table'	=>	'user_regdate',
			'type'	=>	'hidden_value',
			'value'	=>	date("Y-m-d",NOW),
			'change_on_update'	=>	true,
		),
		'playlist_id'		=>	array(
			'table'	=>	'user_playlist_id',
			'type'	=>	'hidden_value',
			'value'	=>	m_random_str(20),
			'change_on_update'	=>	false,
		),
);

##################################################
# ADD USER
##################################################
if ($_GET['mode'] == 'add') {
	acp_check_permission('add_user');
	if (isset($_POST['submit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$name = m_htmlchars(stripslashes(trim(urldecode($_POST['name']))));
			$password = md5(stripslashes($_POST['password']));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'user'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "<meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Add User',$inp_arr,$error_arr);
}
##################################################
# EDIT USER
##################################################
if ($_GET['mode'] == 'edit') {
	if ($_GET['us_del_id']) {
		acp_check_permission('del_user');
			$mysql->query("DELETE FROM ".$tb_prefix."user WHERE usre_id = ".$_GET['us_del_id']);
			echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
	}
	elseif (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Error');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_user');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."user WHERE user_id IN (".$in_sql.")");
			echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif ($_GET['us_id']) {
		acp_check_permission('edit_user');
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_id = '".$_GET['us_id']."'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = (($r[$arr['table']]));
			
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				if ($_POST['password']) $password = md5(stripslashes($_POST['password']));
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'user','user_id',$_GET['us_id']),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Edit User',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_user');
		
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		
		$search = trim(urldecode($_GET['search']));
		$extra = (($search)?"user_name LIKE '%".$search."%' ":'');
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."user ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY user_name ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->num_rows($mysql->query("SELECT user_id FROM ".$tb_prefix."user".(($extra)?" WHERE ".$extra:'')));
		
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
				"if (confirm('Are you sure?')) location='?act=user&mode=edit&us_del_id='+id;".
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
                      <th><input type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></th>
                      <th>Username</th>
                      <th>User Level</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>';
		
		
		if ($tt) {
			if ($search) {
				$link2 = preg_replace("#&search=(.*)#si","",$link);
			}
			else $link2 = $link;
			
	//		echo "ID của User cần <b>sửa</b>: <input id=us_id size=20> <input type=button onclick='window.location.href = \"".$link."&us_id=\"+document.getElementById(\"us_id\").value;' value=Sửa><br><br>";
	//		echo "ID của User cần <b>xóa</b>: <input id=us_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&us_del_id=\"+document.getElementById(\"us_del_id\").value;' value=Xóa><br><br>";
	//		echo "Tìm User : <input id=search size=20 value=\"".$search."\"> <input type=button onclick='window.location.href = \"".$link2."&search=\"+document.getElementById(\"search\").value;' value=Tìm><br><br>";
	//		echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
	//		echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Username</td><td class=title>Quyền</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['user_id'];
				$name = m_unhtmlchars($r['user_name']);
				$level = m_user_level($id);
				echo "<tr>
						<td><input type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td>
						<td><a href='$link&us_id=".$id."'><b>".$name."</b></a></td>
						<td>".$level."</td>
						<td><span class='label label-success'>Active</span></td>
						<td><a href=# onclick=check_del(".$id.")>Delete</a></td>
					</tr>";
			
			
			}
			
	//		echo "<tr><td colspan=5>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
	//		echo '<tr><td colspan=5 align="center">Selected users : '.
	//				'<select name=selected_option><option value=del>Xóa</option>
	//				 <button type="submit" name="do" class="btn btn-primary">Submit</button>';
	//		
			echo "<tr><td colspan=5>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=5 align="center">Selected users : '.
				'<select name=selected_option><option value=del>Delete</option></select>'.
				'<input type="submit" name="do" class=submit value="Submit"></td></tr>';
						echo '</table>
			
                </div><!-- /.box-body -->
              		</form>
              </div><!-- /.box -->
			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->';
		}
		else echo "Không có User nào";
	}
	
}
?>