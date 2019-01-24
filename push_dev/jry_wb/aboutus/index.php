<?php 
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("关于我们",false,true,true);
?>
<script>
	jry_wb_add_load(function()
	{
		document.getElementById('buttom_message').style.display='none';
	});
</script>
<?php jry_wb_print_tail();?>