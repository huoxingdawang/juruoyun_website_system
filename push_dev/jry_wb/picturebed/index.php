<?php 
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("图床",true,true,true,array('use','usepicturebed'));
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('picturebed','active');?>	
	<?php jry_wb_print_href('mypicturebed');?>	
</div>
<script type="text/javascript">
	const BYTES_PER_CHUNK=1024*1024*5; // 每个文件切片大小定为5MB .
	var slices;
	var totalSlices;
	var stopupload=false;

	//发送请求
	function sendRequest() 
	{
		document.getElementById("box").removeChild(document.getElementById("result"));
		var buf=document.createElement("img");buf.id="result";document.getElementById("box").appendChild(buf);
		var blob = document.getElementById('file').files[0];
		var start = 0;
		var end;
		var index = 0;
		// 计算文件切片总数
		slices = Math.ceil(blob.size / BYTES_PER_CHUNK);
		totalSlices= slices;
		
		while(start < blob.size) 
		{
			end = start + BYTES_PER_CHUNK;
			if(end > blob.size) 
			{
				end = blob.size;
			}
			uploadFile(blob, index, start, end);
			start = end;
			index++;
		}
	}
var count=-1;
	//上传文件
	function uploadFile(blob, index, start, end) 
	{
		var xhr;
		var fd;
		var chunk;

		xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() 
		{
			if(xhr.readyState == 4) 
			{
				if(xhr.responseText&&(!stopupload)) 
				{
					if(xhr.responseText=='error')
						stopupload=true;
				}
				slices--;
				// 如果所有文件切片都成功发送，发送文件合并请求。
				if(slices == 0) 
					if(stopupload)
						jry_wb_beautiful_right_alert.alert('文件上传失败',2000,'auto','error');
					else
						mergeFile(blob);
			}
		};
		chunk =blob.slice(start,end);//切割文件
		//构造form数据
		fd = new FormData();
		fd.append("file", chunk);
		fd.append("name", blob.name);
		fd.append("index", index);
		xhr.open("POST", "upload.php", true);
		count++;
		if(count>=10)
		{
			var tr=document.createElement('tr');
			var father=document.getElementById("progress").parentNode;
			document.getElementById("progress").id='';
			tr.id='progress';
			father.appendChild(tr);
			count=0;
		}
		var td=document.createElement('td');document.getElementById("progress").appendChild(td);
		var progress=new jry_wb_progress_bar_round(td,5,40,'#ccc','#F1C40F',16,'#F1C40F');
		window.onresize();
		xhr.upload.onprogress=function(evt)
		{
			if(evt.lengthComputable) 
			{
				progress.update(evt.loaded/evt.total);
			}
			
		}
		//设置二进制文边界件头
		xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
		xhr.send(fd);
	}
	function mergeFile(blob) 
	{
		var td=document.createElement('td');document.getElementById("progress").appendChild(td);
		var progress=new jry_wb_progress_bar_round(td,5,40,'#ccc','#1ABC9C',16,'#1ABC9C');
		progress.update(0);
		var xhr;
		var fd;
		xhr = new XMLHttpRequest();
		fd = new FormData();
		fd.append("name", blob.name);
		fd.append("index", totalSlices);
		xhr.open("POST", "merge.php", true);
		xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
		xhr.send(fd);		
		xhr.onreadystatechange=function()
		{
			if (xhr.readyState==4 && xhr.status==200)
			{
				var data=xhr.responseText;
				var data=JSON.parse(data);
				document.getElementById('result').src=data.src+'&size='+document.body.clientWidth*0.75;
				 var timer = setInterval(function()
				 {
					if (document.getElementById('result').complete)
					{
						window.onresize();
						clearInterval(timer)
					}
				},50);	
				document.getElementById("text").innerHTML="外部引用URL:"+data.src+"<br>内部引用ID:"+data.id;
				jry_wb_beautiful_right_alert.alert('文件上传完毕',2000,'auto','ok');
				progress.update(1);
				window.onresize();
				return false;
			}
		}
	}
jry_wb_beautiful_right_alert.alert('蒟蒻云图床 支持.jpeg .jpg .bmp .gif .png 格式的图片,请善待服务姬,谢谢。',20000,'auto');
</script>

<label for="file" class="jry_wb_button jry_wb_button_size_middle jry_wb_color_normal">选择图像</label>
<h55 id="file_name">没有文件</h55>
<input multiple="multiple"  type="file" id="file" style="display:none;" onchange='document.getElementById("file_name").innerHTML=document.getElementById("file").files[0].name;document.getElementById("upload_button").style.display=""'/>
<button  onclick="sendRequest();" style="display:none;" class="jry_wb_button jry_wb_button_size_middle jry_wb_color_ok" id="upload_button">上传</button>



<table ><tr id='progress' value="0" max="100"></tr></table>
<div id='box'>
	<div id='text'></div>
	<img id='result' width="100%" style="width:100%;" onload="window.onresize();"></img>
</div>
</body>
</html>
<?php jry_wb_print_tail()?>
