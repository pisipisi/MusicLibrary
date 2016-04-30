<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "YOU DONT HAVE PERMISSION.";
	exit();
}
$error_arr = array();
//--------------FORUM------------------------
$config_arr = array(
	'announcement'	=>
		array(
			'name'	=>	'announcement',
			'desc'	=>	'Announcement',
			'type'	=>	'text',
		),
	'total_visit'	=>
		array(
			'name'	=>	'total_visit',
			'desc'	=>	'Total Visit',
			'type'	=>	'number',
		),
	'web_title'	=>
		array(
			'name'	=>	'web_title',
			'desc'	=>	'WEb Title',
			'type'	=>	'free',
		),
	'web_url'	=>
		array(
			'name'	=>	'web_url',
			'desc'	=>	'Web URL',
			'type'	=>	'free',
		),
	'web_email'	=>
		array(
			'name'	=>	'web_email',
			'desc'	=>	'Main Email',
			'type'	=>	'free',
		),
	'download_salt'	=>
		array(
			'name'	=>	'download_salt',
			'desc'	=>	'Download code (anything)',
			'type'	=>	'free',
		),
	'must_login_to_download'	=>
		array(
			'name'	=>	'must_login_to_download',
			'desc'	=>	'Music Login To Dowbload',
			'type'	=>	'true_false',
		),
	'must_login_to_play'	=>
		array(
			'name'	=>	'must_login_to_play',
			'desc'	=>	'Must Login To Play',
			'type'	=>	'true_false',
		),
	'media_per_page'	=>
		array(
			'name'	=>	'media_per_page',
			'desc'	=>	'Media per Page',
			'type'	=>	'free',
		),
	'intro_song'	=>
		array(
			'name'	=>	'intro_song',
			'desc'	=>	'Intro song',
			'type'	=>	'free',
		),
	'intro_song_is_local'	=>
		array(
			'name'	=>	'intro_song_is_local',
			'desc'	=>	'Local URL Intro song ?',
			'type'	=>	'true_false',
		),
);

if (isset($_POST['submit'])) {
	$list = array_keys($_POST);
	$ok = true;
	for ($i=0;$i<count($list);$i++) {
		$key = $list[$i];
		$vl = addslashes($_POST[$key]);
		if ($key == 'web_url') 
			if ($vl[strlen($vl)-1] == '/') $vl = substr($vl,0,-1);
		
		if ($key == 'submit') continue;
		if (!array_key_exists($key,$config_arr)) continue;
		$arr = $config_arr[$r['config_name']];
		if ($check[0] == 'number' && !is_numeric($vl)) { $ok = false; $error_arr[] = $key; }
		if ($ok) $mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$vl."' WHERE config_name = '".$key."'");
	}
	if ($ok) {
		echo "<meta http-equiv='refresh' content='0;url=$link'>";
		exit();
	}
}

//--------------------------------------------
//echo "<form method=post>".
//	"<table class=border cellpadding=2 cellspacing=0 width=90%>".
//	"<tr><td colspan=2 class=title align=center>Configures</td></tr>";
//--------------------------------------------
echo '      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Site Setting
            <small>Change Site Setting</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Song</a></li>
            <li class="active">Edit</li>
          </ol>
        </section>
			<!-- Main content -->
        <section class="content">';
echo '<div class="box">
                

				<form role="form" method=POST>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">';


$q = $mysql->query("SELECT * FROM ".$tb_prefix."config ORDER BY config_name ASC");
while ($r = $mysql->fetch_array($q)) {
	if (!$submit && !count($error_arr)) $vl = stripslashes($r['config_value']);
	else $vl = stripslashes($_POST[$r['config_name']]);
	if (array_key_exists($r['config_name'],$config_arr)) {
		$arr = $config_arr[$r['config_name']];
		if (in_array($r['config_name'],$error_arr)) $symbol = "<font style='color:red'>*</font> ";
		else $symbol = '';
		echo "<tr><td class=fr><b>".$arr['desc']."</b> : </td><td class=fr_2>";
		if (!$arr['type'] || $arr['type'] == 'number' || $arr['type'] == 'free') echo "<input name=".$r['config_name']." size=50 value='".$vl."'>";
		elseif ($arr['type'] == 'text') echo "<textarea cols=60 rows=10 name=".$r['config_name'].">".$vl."</textarea>";
		elseif ($arr['type'] == 'true_false')
			echo "<input type=radio name=".$r['config_name']." value=1".(($r['config_value'] == 1)?' checked':'')."> Yes <input type=radio name=".$r['config_name']." value=0".(($r['config_value'] == 0)?' checked':'')."> No";
		if ($arr['type'] == 'number' && in_array($r['config_name'],$error_arr)) echo " Input must be a number.";
		echo "</td></tr>";
	}
}

echo '</table>

                </div><!-- /.box-body -->
		<div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  </div>
              		</form>
              </div><!-- /.box -->
			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->';
//echo "<tr><td colspan=2 align=center><input class=submit name=submit type=submit value=Submit> <input type=reset class=submit value='Reset'></td></tr>";
//echo "</table></form>";
?>