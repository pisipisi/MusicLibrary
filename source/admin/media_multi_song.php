<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
$total_links = 20;
if (!isset($_POST['mulsubmit'])) {
?>
<script>
var total = <?=$total_links?>;
function check_local(status){
	for(i=1;i<=total;i++)
		document.getElementById("local_url_"+i).checked=status;
}
</script>

<!-- =============================================== -->
		
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
		<h1>
		Song Management
		<small>Add or Edit</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Song</a></li>
		<li class="active">Multiple Add</li>
		</ol>
		</section>
		
		<!-- Main content -->
		<section class="content">
		
		<!-- Default box -->
		<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add Multiple Song</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method=POST>
                  <div class="box-body">
                  	<div class="form-group">
                  		<label for="InputName">Singer</label>
						<?=acp_singer()?>
					</div>
					<div class="form-group">
						<label>New Singer</label>
						<select class="form-control" name=singer_type>
							<option value=1>US Singer</option>
							<option value=2>Internatiol Singer</option>
						</select>
					</div>
					<div class="form-group">
                  		<label for="InputName">Album</label>
						<?=acp_album_list()?>
					</div>
					<div class="form-group">
                  		<label for="InputName">Type</label>
						<?=acp_cat()?>
					</div>
					
<?php
for ($i=1;$i<=$total_links;$i++) {
?>
<div class="form-group">
<label>Song <?=$i?>'s name</label>
<input class="form-control" type=text name="title[<?=$i?>]" value="">
</div>
<div class="form-group">
<label>Song <?=$i?>'s Link</label>
<input type=text class="form-control" name="url[<?=$i?>]" value="">
<div class="checkbox">
                        <label>
                          <input type="checkbox" value=1 class=checkbox id=local_url_<?=$i?> name="local_url[<?=$i?>]">
                          Local URL
                        </label>
                      </div>

</div>
<?php
}
?>
		</div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" name="mulsubmit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div><!-- /.box -->
				


		
		</section><!-- /.content -->
		</div><!-- /.content-wrapper -->				
					

<?php
}
else {
	if ($_POST['new_singer'] && $_POST['singer_type']) {
		$singer = acp_quick_add_singer($_POST['new_singer'],$_POST['singer_type']);
		}
	
	$t_singer = $_POST['singer'];
	$t_album = $_POST['album'];
	$t_cat = $_POST['cat'];
	for ($i=0;$i<=$total_links;$i++) {
		$t_url = stripslashes($_POST['url'][$i]);
		$t_type = acp_type($t_url);
		$t_title = $_POST['title'][$i];
		$t_title_ascii = strtolower(utf8_to_ascii($t_title));
		$t_local = $_POST['local_url'][$i];
		if ($t_url && $t_title) {
			$mysql->query("INSERT INTO ".$tb_prefix."data (m_singer,m_album,m_cat,m_url,m_type,m_title,m_title_ascii,m_is_local,m_poster) VALUES ('".$t_singer."','".$t_album."','".$t_cat."','".$t_url."','".$t_type."','".$t_title."','".$t_title_ascii."','".$t_local."','".$_SESSION['admin_id']."')");
		}
	}
	echo "Done <meta http-equiv='refresh' content='0;url=$link'>";
}
?>