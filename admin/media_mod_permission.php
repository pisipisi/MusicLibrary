<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}

$mod_permission = acp_get_mod_permission();

$permission_list = array(
	'add_cat'	=>	'Add Category',
	'edit_cat'	=>	'Edit Category',
	'del_cat'	=>	'Delete Category',
	'add_media'	=>	'Add Media',
	'edit_media'	=>	'Edit Media',
	'del_media'	=>	'Delete Media',
	'add_singer'	=>	'Add Singer',
	'edit_singer'	=>	'Edit Singer',
	'del_singer'	=>	'Delete Singer',
	'add_album'	=>	'Add Album',
	'edit_album'	=>	'Edit Album',
	'del_album'	=>	'Delete Album',
	'add_user'	=>	'Add User',
	'edit_user'	=>	'Edit User',
	'del_user'	=>	'Delete User',
	'add_link'	=>	'Add Ads',
	'edit_link'	=>	'Edit Ads',
	'del_link'	=>	'Delete',
	'add_template'	=>	'Add Template',
	'edit_template'	=>	'Edit Template',
	'del_template'	=>	'Delete Template',
);

if (!isset($_POST['submit'])) {
?>

<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Mod Setting
            <small>Change Mod Setting</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Song</a></li>
            <li class="active">Edit</li>
          </ol>
        </section>
			<!-- Main content -->
        <section class="content">
	<div class="box">
                

				<form role="form" method=POST>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                  
                  
<?php
foreach ($permission_list as $name => $desc) {
?>
<tr>
	<td width=30%><?=$desc?></td>
	<td><input type=radio value=1 name=<?=$name?><?=(($mod_permission[$name])?' checked':'')?>> Yes <input type=radio value=0 name=<?=$name?><?=((!$mod_permission[$name])?' checked':'')?>> No </td>
</tr>
<?php
}
?>
</table>

                </div><!-- /.box-body -->
		<div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  </div>
              		</form>
              </div><!-- /.box -->
			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->
<?php
}
else {
	$per = '';
	foreach ($permission_list as $name => $desc) {
		$v = $_POST[$name];
		if ($v == '') $v = 0;
		$per .= $v;
	}
	$per = bindec($per);
	$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$per."' WHERE config_name = 'mod_permission'");
	echo "<meta http-equiv='refresh' content='0;url=$link'>";
}
?>