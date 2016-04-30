<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "You dont have permission.";
	exit();
}
if ($_GET['mode'] == 'change') {
	/*
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."config WHERE config_name IN ('server_address','server_username','server_password','server_port','server_folder')");
	while ($r = $mysql->fetch_array($q)) {
		$$r['config_name'] = $r['config_value'];
	}
	if (!$server_port) $server_port = 21;
	$connect_id = ftp_connect($server_address,$server_port);
	$login_result = ftp_login($connect_id, $server_username, $server_password);
	ftp_pasv($connect_id, true);
	if (!$connect_id || !$login_result) {
		echo "Kết nối với <b>".$server_address."</b> thất bại.".
		exit();
	}
	$old_dir = $server_folder;
	$new_dir = m_random_str(10);
	if ($old_dir) {
		if (ftp_rename($connect_id, $old_dir, $new_dir)) {
			echo "Sửa tên thư mục cũ <b>".$old_dir."</b> thành tên mới là <b>".$new_dir."</b><br>";
			$result= mysql_query("UPDATE ".$tb_prefix."config SET config_value = '".$new_dir."' WHERE config_name ='server_folder'");
			echo "<meta http-equiv='Refresh' content='3; URL=?act=server'>";
		}
		else {
			echo "Lỗi khi sửa tên thư mục <b>".$old_dir."</b>";
		}
	}
	else {
		if (ftp_mkdir($connect_id, $new_dir)) {
			echo "Đã tạo thư mục <b>".$new_dir."</b><br>";
			$result= mysql_query("UPDATE ".$tb_prefix."config SET config_value = '".$new_dir."' WHERE config_name ='server_folder'");
			echo "<meta http-equiv='Refresh' content='3; URL=?act=server'>";
		}
		else {
			echo "Lỗi khi tạo thư mục <b>".$new_dir."</b>";
		}
	}
	ftp_close($connect_id);
	*/
}
else {
	if (!isset($_POST['submit'])) {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."config WHERE config_name IN ('server_address','server_username','server_password','server_port','server_folder','server_url')");
		while ($r = $mysql->fetch_array($q)) {
			$$r['config_name'] = $r['config_value'];
		}
	?>
	
	<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Config Sever Media
            <small>Change Sever Media Setting</small>
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
                  
		<?php /*
		<tr><td class=fr width="30%">Tên server || IP server :</td><td class=fr_2><input name="s_address" size="32" value="<?=$server_address?>"></td></tr>
		<tr><td class=fr>Username FTP :</td><td class=fr_2><input name="s_username" size="32" value="<?=$server_username?>"></td></tr>
		<tr><td class=fr>Password FTP :</td><td class=fr_2><input type="password" name="s_password" size="32" value="<?=$server_password?>"></td></tr>
		<tr><td class=fr>Port FTP<br>( thường là 21 ):</td><td class=fr_2><input name="s_port" size="32" value="<?=$server_port?>"></td></tr>
		<tr><td class=fr>Folder chứa nhạc :</td><td class=fr_2><b><?=$server_folder?></b> ---> <a href="<?=$link?>&mode=change"><b>Đổi thư mục</b></a></td></tr>*/ ?>
		<tr>
			<td>Server Folder</td>
			<td><input name="s_folder" value="<?=$server_folder?>"></td>
		</tr>
		<tr>
			<td>Server URL :</td>
			<td><input name="s_url" value="<?=$server_url?>"></td>
		</tr>
		<tr><td colspan=2>
		Link format example : http://domain.com/folder/file.wma<br>
		You must upload file.wma to folder<br>
		Complete link : <b><?=$server_url?>/<?=$server_folder?>/file.wma</b>
		</td></tr>
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
		/*
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_address."' WHERE config_name = 'server_address'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_username."' WHERE config_name = 'server_username'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_password."' WHERE config_name = 'server_password'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_port."' WHERE config_name = 'server_port'");
		*/
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_url."' WHERE config_name = 'server_url'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_folder."' WHERE config_name = 'server_folder'");
		echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=$link'>";
	}
}
?>