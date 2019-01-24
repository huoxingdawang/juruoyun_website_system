const jry_wb_netdisk_uploader_max_size_pre_chunk=1024*1024*5;
const jry_wb_netdisk_uploader_max_pices_pre_time=5;
const jry_wb_netdisk_uploader_address=jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_upload.php';
function jry_wb_netdisk_upload_file(file,dir)
{
	var start = 0;
	var end;
	var index = 0;
	var slices=Math.ceil(file.size/jry_wb_netdisk_uploader_max_size_pre_chunk);
	var totalslices=slices;
	this.stopupload=false;
	this.progress=[];
	this.upload_file=function(blob,index,start,end)
	{
		if(this.stopupload)
			return ;
		var xhr;
		var fd;
		var chunk;
		xhr = new XMLHttpRequest();
		xhr.onreadystatechange=()=> 
		{
			if(xhr.readyState==4) 
			{
				if(xhr.responseText&&(!this.stopupload)) 
					if(xhr.responseText==-1)
						this.stopupload=true;
				slices--;
				if(slices == 0) 
					if(this.stopupload)
						jry_wb_beautiful_right_alert.alert('文件上传失败',2000,'auto','error');
					else
						this.merge_file(blob);
			}
		};
		chunk=blob.slice(start,end);
		fd=new FormData();
		fd.append("file",chunk);
		fd.append("name",blob.name);
		fd.append("index",index);
		xhr.open("POST",jry_wb_netdisk_uploader_address+'?action=upload',true);
		this.progress.push({
			'progress':0
		});
		var i=this.progress.length-1;
		xhr.upload.onprogress=(event)=>
		{
			if(event.lengthComputable)
				this.progress[i].progress=event.loaded/event.total;
		}
		xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
		xhr.send(fd);		
	}
	this.merge_file=function(blob) 
	{
		var xhr;
		var fd;
		xhr = new XMLHttpRequest();
		fd = new FormData();
		fd.append("name",blob.name);
		fd.append("index",totalslices);
		fd.append("dir",dir);
		xhr.open("POST",jry_wb_netdisk_uploader_address+'?action=merge',true);
		xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
		xhr.send(fd);		
		xhr.onreadystatechange=function(data)
		{
			data=JSON.parse(data);
			if (xhr.readyState==4&&xhr.status==200)
			{
				console.log(data);
			}
		}
	}	
	while(start<file.size) 
	{
		end=Math.min(start+jry_wb_netdisk_uploader_max_size_pre_chunk,file.size);
		this.upload_file(file,index,start,end);
		start = end;
		index++;
	}
}