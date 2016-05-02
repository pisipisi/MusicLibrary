<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=cat&mode=edit';

$inp_arr = array(
		'name'	=> array(
			'table'	=>	'cat_name',
			'name'	=>	'Name',
			'type'	=>	'free'
		),
		'order'	=> array(
			'table'	=>	'cat_order',
			'name'	=>	'Order',
			'type'	=>	'number',
			'can_be_empty'	=>	true,
		),
		'sub'	=> array(
			'table'	=>	'sub_id',
			'name'	=>	'Category',
			'type'	=>	'function::acp_maincat::number'
		),
	);
##################################################
# ADD MEDIA CAT
##################################################
if ($_GET['mode'] == 'add') {
	acp_check_permission('add_cat');
	if (isset($_POST['formsubmit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'cat'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Done <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Add Category',$inp_arr,$error_arr);
}
##################################################
# EDIT MEDIA CAT
##################################################
if ($_GET['mode'] == 'edit') {

	acp_check_permission('edit_cat');
	$cat_id = $_GET['cat_id'];
	if ($cat_id) {
		if (!isset($_POST['formsubmit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_id = '".$cat_id."'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'cat','cat_id',$_GET['cat_id']),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				//echo '<h1>'.$sql.'</h1>';
				echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Edit Category',$inp_arr,$error_arr);
	}
	else {
		if (isset($_POST['sbm'])) {
			$z = array_keys($_POST);
			$q = $mysql->query("SELECT cat_id FROM ".$tb_prefix."cat");
			for ($i=0;$i<$mysql->num_rows($q);$i++) {
				$id = explode('o',$z[$i]);
				$od = $_POST[$z[$i]];
				$mysql->query("UPDATE ".$tb_prefix."cat SET cat_order = '$od' WHERE cat_id = '".$id[$i]."'");
				
			}
		}
		echo '      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Category Manager
            <small>Edit or Delete Category</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Simple</li>
          </ol>
        </section>
			<!-- Main content -->
        <section class="content">';
		echo "<script>function check_del(id) {".
		"if (confirm('Are you sure?')) location='?act=cat&mode=del&cat_id='+id;".
		"return false;}</script>";
		echo '<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Category Manager</h3>
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-header -->
				<form role="form" method=POST>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Order</th>
                      <th>Name</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>';
	//	echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form method=post>";
	//	echo "<tr><td align=center class=title width=5%>STT</td><td class=title style='border-right:0'>Tên thể loại</td></tr>";
		$cat_query = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE (sub_id IS NULL OR sub_id = 0) ORDER BY cat_order ASC");
		while ($cat = $mysql->fetch_array($cat_query)) {
	//		echo "<tr><td>".$cat['cat_title']."</td></tr>";
			$iz = $cat['cat_order'];
			echo "<tr><td><input  width=5% onclick=this.select() type=text name='o".$cat['cat_id']."' value=".$iz."></td>";
			echo "<td><a href='".$link."&cat_id=".$cat['cat_id']."'><b>".$cat['cat_name']."</b></a></td>";
			echo '<td><span>00/00/0000</span></td>';
			echo '<td><span class="label label-success">Active</span></td>';
			echo "<td><a href=# onclick=check_del(".$cat['cat_id'].")>Delete</a></td>
					</tr>";
			$sub_query = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE sub_id = '".$cat['cat_id']."' ORDER BY cat_order ASC");
			if ($mysql->num_rows($sub_query)) echo "";
			while ($sub = $mysql->fetch_array($sub_query)) {
				$s_o = $sub['cat_order'];
				echo "<tr>
						<td><input  width=5% onclick=this.select() type=text name='o".$sub['cat_id']."' value=".$s_o."></td>
						<td> - <a href='".$link."&cat_id=".$sub['cat_id']."'><b>".$sub['cat_name']."</b></a></td>
						<td><span>00/00/0000</span></td>
						<td><span class='label label-success'>Active</span></td>
						<td><a href=# onclick=check_del(".$sub['cat_id'].")>Delete</a></td>		
					</tr>";
			}
			if ($mysql->num_rows($sub_query)) echo "";
		}
	//	echo '<tr><td colspan="2" align="center"><input type="submit" name="sbm" class=submit value="Sửa thứ tự"></td></tr>';
	//	echo '</form></table>';
		

              echo '</table>
              		
                </div><!-- /.box-body -->
              		<div class="box-footer">
                    <button type="submit" name="sbm" class="btn btn-primary">Submit</button>
                  </div>
              		</form>
              </div><!-- /.box -->';
		
		echo '</section><!-- /.content -->
		</div><!-- /.content-wrapper -->';
	}
	
}
##################################################
# DELETE MEDIA CAT
##################################################
if ($_GET['mode'] == 'del') {
	acp_check_permission('del_cat');
	$cat_id = $_GET['cat_id'];
	if ($cat_id) {
		$mysql->query("DELETE FROM ".$tb_prefix."cat WHERE cat_id = '".$cat_id."'");
		echo "Done <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		exit();
	
	}
}
?>