<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");
	$conn=jry_wb_connect_database();
	if(($file=fopen('jry_nd.fast_save_message','r'))==false)
	{
		$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_NETDISK.'area ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$fastdata['area']=$st->fetchAll()[0]['lasttime'];
		$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_NETDISK.'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$fastdata['group']=$st->fetchAll()[0]['lasttime'];
		$file=fopen('jry_nd.fast_save_message','w');
		fwrite($file,json_encode($fastdata));
		fclose($file);
		$fastdata['new']=true;
	}
	else
	{
		$fastdata=json_decode(fread($file,filesize('jry_nd.fast_save_message')));
		fclose($file);
	}
	$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_NETDISK.'share WHERE share_id=? AND `key`=? LIMIT 1;');
	$st->bindValue(1,$_GET['share_id']);
	$st->bindValue(2,$_GET['key']==''?'':$_GET['key']);
	$st->execute();
	$share=$st->fetchAll();
	$n=count($share);
	if($n==0)
	{
		include('../../404.php');		
		exit();
	}
	$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_NETDISK.'file_list WHERE file_id=? AND id=? AND trust=1 AND `delete`=0 LIMIT 1');
	$st->bindValue(1,$share[0]['file_id']);
	$st->bindValue(2,$share[0]['id']);
	$st->execute();
	$root=$st->fetchAll();		
	if(count($root)==0)
	{
		include('../../404.php');
		exit();
	}
	if(!$root[0]['isdir'])
	{
		header("Location:jry_nd_do_file.php?action=".$_GET['action'].'&file_id='.$root[0]['file_id'].'&share_id='.$_GET['share_id'].'&fast='.$_GET['fast'].'&key='.$_GET['key']);
		exit();
	}
	else
	{
		$dir_length=strlen($root[0]['dir'].$root[0]['name']);
		$ans=[];
		function get_filse($father)
		{
			global $ans;
			global $conn;
			global $dir_length;
			global $share;
			global $root;
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_NETDISK.'file_list WHERE father=? AND id=? AND `delete`=0');
			$st->bindValue(1,$father);
			$st->bindValue(2,$share[0]['id']);
			$st->execute();
			$data=$st->fetchAll();
			foreach($data as $one)
			{
				$ans[]=array(	'file_id'=>$one['file_id'],
								'id'=>$one['id'],
								'father'=>$one['father'],
								'name'=>$one['name'],
								'type'=>$one['type'],
								'area'=>$one['area'],
								'size'=>$one['size'],
								'isdir'=>$one['isdir'],
								'trust'=>$one['trust'],
								'lasttime'=>$one['lasttime']);
				if($one['isdir']==1)
					get_filse($one['file_id']);
			}
		}
		get_filse($root[0]['file_id']);
	}
	jry_wb_print_head("网盘分享",false,true,true);
?>
<div class='jry_wb_top_toolbar'></div>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'netdisk/index'); ?>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'netdisk/file'); ?>
<div class='jry_wb_top_toolbar'></div>
<script>
	jry_wb_include_once_script('jry_wb_nd_index.js.php',function(){jry_wb_netdisk_init(document.getElementById('body'));});
	var jry_nd_fast_save_message=JSON.parse('<?php  echo json_encode($data);?>');
</script>
<script>
	jry_wb_add_onload(function()
	{
		document.getElementsByClassName('jry_wb_top_toolbar')[0].style.display='none';
		document.getElementById('buttom_message').style.display='none';
	});
	var jry_wb_netdisk_first_time_use=false;
	var jry_nd_share_mode_flag=true;
	var jry_nd_share_mode_id=<?php  echo $share[0]['id']?>;
	var share_id=<?php  echo $_GET['share_id']?>;
	var key='<?php  echo $_GET['key']?>';
	var jry_nd_share_mode_allow_fast=<?php echo $share[0]['fastdownload'];?>;
	var jry_nd_fast_save_message=JSON.parse('<?php  echo json_encode($fastdata);?>');
	var jry_nd_file_list=JSON.parse('<?php  echo json_encode($ans);?>');
</script>
<div class="jry_wb_netdisk_body" id="body">

</div>
<?php jry_wb_print_tail();?>