<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=singer&mode=edit';

$inp_arr = array(
		'singer'	=> array(
			'table'	=>	'singer_name',
			'name'	=>	'Singer Name',
			'type'	=>	'free'
		),
		'singer_img'	=> array(
			'table'	=>	'singer_img',
			'name'	=>	'Singer Picture',
			'type'	=>	'free',
	//		'can_be_empty'	=> true,
		),
		'singer_type'	=>	array(
			'table'	=>	'singer_type',
			'name'	=>	'Type',
			'type'	=>	'function::acp_singer_type::number',
		),
		'singer_info'	=>	array(
			'table'	=>	'singer_info',
			'name'	=>	'Singer Info',
			'type'	=>	'text',
	//		'can_be_empty'	=>	true,
		),
		'singer_name_ascii'	=>	array(
			'table'	=>	'singer_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
	);
##################################################
# ADD SINGER
##################################################
if ($_GET['mode'] == 'add') {
	if ($level == 2 && !$mod_permission['add_singer']) echo 'You dont have permission';
	else {
		if (isset($_POST['formsubmit'])) {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['singer_name_ascii']['value'] = strtolower(utf8_to_ascii($album));
				$sql = $form->createSQL(array('INSERT',$tb_prefix.'singer'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Done <meta http-equiv='refresh' content='0;url=$link'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
	
		$form->createForm('Add Singer',$inp_arr,$error_arr);
	}
}
##################################################
# EDIT SINGER
##################################################
if ($_GET['mode'] == 'edit') {
	if ($singer_del_id) {
		acp_check_permission('del_singer');

			$mysql->query("DELETE FROM ".$tb_prefix."singer WHERE singer_id = '".$singer_del_id."'");
			echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();


	}
	elseif ($_POST['do']) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Error');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_singer');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."singer WHERE singer_id IN (".$in_sql.")");
			echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif ($_GET['singer_id']) {
		acp_check_permission('edit_singer');
		if (!isset($_POST['formsubmit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer WHERE singer_id = '".$_GET['singer_id']."'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['singer_name_ascii']['value'] = strtolower(utf8_to_ascii($singer));

				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'singer','singer_id',$_GET['singer_id']),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "<meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Edit Singer',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_singer');
		$m_per_page = 30;
		if (!$pg) $pg = 1;
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
				"if (confirm('Are you sure?')) location='?act=singer&mode=edit&singer_del_id='+id;".
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
                      <th>Singer Name</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>';
			
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer ORDER BY singer_name ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(singer_id) FROM ".$tb_prefix."singer"));
		$tt = $tt[0];
		if ($tt) {
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['singer_id'];
				$singer = $r['singer_name'];
				$img = ($r['singer_img'])?"<img src=".$r['singer_img']." width=50 height=50>":'';
				echo "<tr>
						<td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td>
						<td><b><a href=?act=singer&mode=edit&singer_id=".$id.">".$singer."</a></b></td>
						<td class=fr_2 align=center>".$img."</td>
						<td><span class='label label-success'>Active</span></td>
						<td><a href=# onclick=check_del(".$id.")>Delete</a></td>
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
		else echo "Empty";
	}
}
?>