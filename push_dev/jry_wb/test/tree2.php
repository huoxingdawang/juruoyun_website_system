<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("",false,false,true);
?>
<div>213y2t3ufdsyudfyiusdyfiusyfiusdyfuisyd<br>213y2t3ufdsyudfyiusdyfiusyfiusdyfuisyd<br>213y2t3ufdsyudfyiusdyfiusyfiusdyfuisyd<br>213y2t3ufdsyudfyiusdyfiusyfiusdyfuisyd<br>213y2t3ufdsyudfyiusdyfiusyfiusdyfuisyd<br></div>
<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_ok" onclick='tree.openall()'>展开</button>
<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_ok" onclick='tree.closeall()'>收回</button>
<div id="tree"></div>
<script>
var tree=new jry_wb_tree(document.getElementById('tree'));
var node1=tree.add(tree.root,1,1);
var node2=tree.add(node1,2,2);
var node3=tree.add(node2,3,3);
var node4=tree.add(node1,4,4);
var node5=tree.add(node4,5,5);
var node11=tree.add(node4,11,11);
var node6=tree.add(node5,6,6);
var node7=tree.add(node4,7,7);
var node8=tree.add(node7,8,8);
var node9=tree.add(node8,9,9);
var node10=tree.add(node8,10,10);
</script>
<?php jry_wb_print_tail()?>
