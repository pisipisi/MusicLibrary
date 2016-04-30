<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
class HTMLForm {
	var $error_color = array(
		'empty'		=>	'#FCB222',
		'number'	=>	'#7EBA01',
		'>0'		=>	'#47A2CB',
		'>=0'		=>	'#585CFE',
		'url'		=>	'#202020',
	);
	function createSQL($config_arr,$inp_arr) {
		if ($config_arr[0] == 'INSERT') {
			foreach ($inp_arr as $key=>$arr) {
				if (!$arr['table']) continue;
				$s1 .= '`'.$arr['table'].'`,';
				if ($arr['type'] == 'hidden_value')	$s2 .= '\"'.$arr['value'].'\",';
				else $s2 .= '\'$'.$key.'\',';
			}
			$s1 = substr($s1,0,-1);
			$s2 = substr($s2,0,-1);
			$sql = "INSERT INTO ".$config_arr[1]." (".$s1.") VALUES (".$s2.")";
		}
		elseif ($config_arr[0] == 'UPDATE') {
			foreach ($inp_arr as $key=>$arr) {
				global $$key;
				if (!$arr['table']) continue;
				if ($arr['update_if_true'] && !eval('return ('.$arr['update_if_true'].');')) continue;
				
				if ($arr['type'] == 'hidden_value' && !$arr['change_on_update']) continue;
				if ($arr['type'] == 'hidden_value')	$s1 .= $arr['table'].' = \''.$arr['value'].'\', ';
				else $s1 .= $arr['table'].' = \"$'.$key.'\", ';
			}
			$s1 = substr($s1,0,-2);
			if ($config_arr[2] && $config_arr[3]) $sql = "UPDATE ".$config_arr[1]." SET ".$s1." WHERE ".$config_arr[2]." = '".$config_arr[3]."'";
			else $sql = "UPDATE ".$config_arr[1]." SET ".$s1."";
		}
		return $sql;
	}
	function createTableArray($inp_arr,$field_arr) {
		$keys = array_keys($inp_arr);
		$tb_arr = array();
		for ($i=0;$i<count($keys);$i++)
			$tb_arr[$keys[$i]] = $field_arr[$i];
		return $tb_arr;
	}
	function getWarnString($error_arr) {
		if (!$error_arr) return;
		if (in_array('empty',$error_arr)) $warn = "<b style='color:".$this->error_color['empty']."'>*</b> : Please enter data<br>";
		if (in_array('number',$error_arr)) $warn .= "<b style='color:".$this->error_color['number']."'>*</b> : Must be a number<br>";
		if (in_array('>0',$error_arr)) $warn .= "<b style='color:".$this->error_color['>0']."'>*</b> : Data must be greater 0<br>";
		if (in_array('>=0',$error_arr)) $warn .= "<b style='color:".$this->error_color['>=0']."'>*</b> : Data must be greater or equal 0<br>";
		if (in_array('url',$error_arr)) $warn .= "<b style='color:".$this->error_color['url']."'>*</b> : Data must be URL<br>";
		return substr($warn,0,-4);
	}
	function checkForm($inp_arr) {
		
		foreach ($inp_arr as $key=>$arr) {
			if ($arr['type'] == 'hidden_value') continue;
			global $$key;
		}
		foreach ($inp_arr as $key=>$arr) {
			if (!$$key && $arr['can_be_empty']) continue;
			if ($arr['type'] == 'hidden_value') continue;
			if ($arr['check_if_true'] && !eval('return ('.$arr['check_if_true'].');')) continue;
			/*
			//File upload
			$target_dir = "../uploads/";
			$target_file = $target_dir . basename($_FILES[$key]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$check = getimagesize($_FILES[$key]["tmp_name"]);
			 if($check !== false) {
				$error_arr[$key] = "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				$error_arr[$key] = "File is not an image.";
				$uploadOk = 0;
			}
			if($imageFileType != "mp3" && $imageFileType != "wav" && $imageFileType != "wma") {
						$error_arr[$key] = "Sorry, only mp3, wav,wma files are allowed.";
						$uploadOk = 0;
					}
		// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$error_arr[$key] = "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES[$key]["tmp_name"], $target_file)) {
				//echo "The file ". basename( $_FILES[$key]["name"]). " has been uploaded.";
				} else {
				$error_arr[$key] = "Sorry, there was an error uploading your file.";
					}
			}
			if ($arr['type'] == 'file') {
				$$key = $target_file;
			} else {
				$$key = htmlspecialchars($_POST[$key]);
			}	*/
			$$key = htmlspecialchars($_POST[$key]);
			if ($$key == '' && !$arr['can_be_empty']) $error_arr[$key] = 'empty';
			if (preg_match("/^function::*::*/",$arr['type'])) { $z = explode('::',$arr['type']); $type = $z[1]; }
			else $type = $arr['type'];
			if (!$error_arr[$key]) {
				if ($type == 'number' && !is_numeric($$key)) $error_arr[$key] = 'number';
				elseif ($type == 'number' && $arr['>0'] && $$key <= 0 ) $error_arr[$key] = '>0';
				elseif ($type == 'number' && $arr['>=0'] && $$key < 0 ) $error_arr[$key] = '>=0';
				elseif ($type == 'url' && !ereg("[http|mms|ftp|rtsp]://[a-z0-9_-]+\.[a-z0-9_-]+",$$key)) $error_arr[$key] = 'url';
			}
		}
		return $error_arr;
	}
	function createForm($title,$inp_arr,$error_arr) {
		global $warn;
		echo '<!-- =============================================== -->
		
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
		<h1>
		Form Management
		<small>Add or Edit</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Layout</a></li>
		<li class="active">Fixed</li>
		</ol>
		</section>
		
		<!-- Main content -->
		<section class="content">';
		if ($warn) echo '<div class="callout callout-info">'.
		'<h4>Tip!</h4>'.
		'<p>'.$warn.'</p>'.
		'</div>';
		
		echo '<!-- Default box -->'.
		'<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">'.$title.'</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method=POST enctype="multipart/form-data">
                  <div class="box-body">';
		
	
		
		foreach($inp_arr as $key=>$arr) {
			if ($arr['type'] == 'hidden_value') continue;
			global $$key;
			if ($arr['always_empty']) $$key = '';
			if (preg_match("/^function::*::*/",$arr['type'])) {
				$ex_arr = explode('::',$arr['type']);
				$str = $ex_arr[1]($$key);
				$type = 'function';
			}
			else $type = $arr['type'];
		//	echo "<tr><td class=fr width=30%><b>".$arr['name']."</b>".(($arr['desc'])?"<br>".$arr['desc']:'')."</td><td class=fr_2>";
			
			echo '<div class="line line-dashed b-b line-lg pull-in"></div>';
			echo '<div class="form-group">
                      <label for="InputName">'.$arr['name'].'</label>';
			if ($error_arr[$key]) {
				echo ' ';
				switch ($error_arr[$key]) {
					case 'empty'	:	echo "<b style='color:".$this->error_color['empty']."'>*</b>";	break;
					case 'number'	:	echo "<b style='color:".$this->error_color['number']."'>*</b>";	break;
					case '>0'		:	echo "<b style='color:".$this->error_color['>0']."'>*</b>";		break;
					case '>=0'		:	echo "<b style='color:".$this->error_color['>=0']."'>*</b>";	break;
					case 'url'		:	echo "<b style='color:".$this->error_color['url']."'>*</b>";	break;
				}
			}
			$value = ($$key != '')?m_unhtmlchars(stripslashes($$key)):'';
			switch ($type) {
				case 'number' : echo "<input type=text name=".$key." class='form-control' value=\"".$value."\">"; break;
				case 'free' : echo "<input type=text name=".$key." class='form-control' value=\"".$value."\">"; break;
				case 'password' : echo "<input type=password name=".$key." class='form-control' value=\"".$value."\">"; break;
				case 'url' : echo "<input type=text name=".$key." class='form-control' value=\"".$value."\">"; break;
				case 'function' : echo $str; break;
				case 'file' : echo "<input type=file name=".$key." class='form-control' value=\"".$value."\">"; break;
				case 'text' : echo "<textarea rows=8 class='form-control' name=".$key.">".$value."</textarea>"; break;
				case 'checkbox'	:	echo "<input value=1".(($arr['checked'])?' checked':'')." type=checkbox name=".$key.">"; break;
			}
			
			echo "</div>";
		}
		
         echo ' </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" name="formsubmit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div><!-- /.box -->
				


		
		</section><!-- /.content -->
		</div><!-- /.content-wrapper -->';
	}
}
?>