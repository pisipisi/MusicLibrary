<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
$menu_arr = array(
	'cat'	=>	array(
		'Categories',
		array(
			'edit'	=>	array('Edit','act=cat&mode=edit'),
			'add'	=>	array('Add','act=cat&mode=add'),
		),
	),
	'media'	=>	array(
		'Song',
		array(
			'edit'	=>	array('Edit','act=song&mode=edit'),
			'edit_broken'	=>	array('Report','act=song&mode=edit&show_broken=1'),
			'add'	=>	array('Add','act=song&mode=add'),
			'add_multi'	=>	array('Multi Add','act=song&mode=multi_add'),
		),
	),
	'singer'	=>	array(
		'Singer',
		array(
			'edit'	=>	array('Edit','act=singer&mode=edit'),
			'add'	=>	array('Add','act=singer&mode=add'),
		),
	),
	'album'	=>	array(
		'Album',
		array(
			'edit'	=>	array('Edit','act=album&mode=edit'),
			'add'	=>	array('Add','act=album&mode=add'),
		),
	),
	'user'	=>	array(
		'User',
		array(
			'edit'	=>	array('Edit','act=user&mode=edit'),
			'add'	=>	array('Add','act=user&mode=add'),
		),
	),
	'link'	=>	array(
		'Ads',
		array(
			'edit'	=>	array('Edit','act=ads&mode=edit'),
			'add'	=>	array('Add','act=ads&mode=add'),
		),
	),
	'template'	=>	array(
		'Template',
		array(
			'edit'	=>	array('Edit','act=tpl&mode=edit'),
			'add'	=>	array('Add','act=tpl&mode=add'),
		),
	),
	'config'	=>	array(
		'Setting',
		array(
			'set_mod_permission'	=>	array('Moderation','act=mod_permission'),
			'config'	=>	array('Site Setting','act=config'),
			'config_server'	=>	array('Config Server','act=server'),
			'backup_data'	=>	array('Backup','act=backup'),
		),
	)
);
if ($level == 2) {

	unset($menu_arr['config']);
	foreach ($menu_arr as $key => $v) {
		if (!$mod_permission['add_'.$key]) unset($menu_arr[$key][1]['add']);
		if (!$mod_permission['edit_'.$key]) unset($menu_arr[$key][1]['edit']);
		
		if ($key == 'media' && !$mod_permission['edit_'.$key]) unset($menu_arr[$key][1]['edit_broken']);
		if ($key == 'media' && !$mod_permission['add_'.$key]) unset($menu_arr[$key][1]['add_multi']);
		
		if (!$menu_arr[$key][1]) unset($menu_arr[$key]);
	}
}
foreach ($menu_arr as $key => $arr) {
	echo '<li class="treeview">
              <a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>'.$arr[0].'</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">';

	foreach ($arr[1] as $m_key => $m_val) {
		echo "<li><a href=\"?".$m_val[1]."\">".$m_val[0]."</a></li>";
	}
	echo " </ul>
            </li>";
}

?>