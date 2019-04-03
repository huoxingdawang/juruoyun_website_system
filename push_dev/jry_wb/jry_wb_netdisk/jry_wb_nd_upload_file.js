const jry_wb_netdisk_uploader_max_size_pre_chunk=1024*1024*5;
const jry_wb_netdisk_uploader_max_pices_pre_time=5;
function jry_wb_netdisk_upload_file(file,father,uploaded_call_back,uploaded_fail_call_back)
{
	var start = 0;
	var end;
	var index = 0;
	var slices=Math.ceil(file.size/jry_wb_netdisk_uploader_max_size_pre_chunk);
	var totalslices=slices;
	this.fail_reason=0;
	this.stopupload=false;
	this.progress=[];
	this.loaded=0;
	this.name=file.name;
	this.total=file.size/1024;
	var arr=file.name.split('.');var type=arr[arr.length-1];arr.pop();var name=arr.join('.');delete arr;
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
				var data=JSON.parse(xhr.responseText);
				if((!data.code))
				{
					if(!this.stopupload)
					{
						if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else if(data.reason==200001)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+type+"是不允许的文件类型",5000,"auto","error");
						else if(data.reason==200004)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"分片上传数据发送错误",5000,"auto","error");
						this.fail_reason=data.reason;
						uploaded_fail_call_back();
					}
					this.stopupload=true;
					return ;
				}
				slices--;
				if(slices==0) 
					if(this.stopupload)
						jry_wb_beautiful_right_alert.alert('文件上传失败',5000,'auto','error');
					else
						this.merge_file(blob);
			}
		};
		chunk=blob.slice(start,end);
		fd=new FormData();
		fd.append("file",chunk);
		fd.append("index",index);
		fd.append("name",name);
		fd.append("father",father);
		fd.append("type",type);
		fd.append("size",Math.ceil(file.size/1024));
		fd.append("file_id",this.file_id);
		xhr.open("POST",jry_wb_netdisk_do_file+'?action=upload',true);
		this.progress.push({
			'loaded':0,
			'total':0
		});
		var cnt=this.progress.length-1;
		xhr.upload.onprogress=(event)=>
		{
			if(event.lengthComputable)
				this.progress[cnt].loaded=event.loaded,this.progress[cnt].total=event.total;
			this.loaded=0;
			for(var i=0;i<this.progress.length;i++)
				this.loaded+=this.progress[i].loaded;
			this.loaded=this.loaded/1024;
		}
		xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
		xhr.send(fd);		
	}
	var merge_timer=null;
	var cnt=0;
	this.merge_file=function(blob) 
	{
		var xhr;
		var fd;
		xhr = new XMLHttpRequest();
		fd = new FormData();
		fd.append("index",totalslices);
		fd.append("name",name);
		fd.append("father",father);
		fd.append("type",type);
		fd.append("size",Math.ceil(file.size/1024));
		fd.append("file_id",this.file_id);		
		xhr.open("POST",jry_wb_netdisk_do_file+'?action=merge',true);
		xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
		xhr.send(fd);
		xhr.onreadystatechange=()=>
		{
			if (xhr.readyState==4)
			{
				var data=JSON.parse(xhr.responseText);
				if(!data.code)
				{
					if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else if(data.reason==200004)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"分片上传数据发送错误",5000,"auto","error");
					else if(data.reason==200007)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"文件尺寸相差过大",5000,"auto","error");
					else if(data.reason==220000)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"OSSSDK未知错误",5000,"auto","error");
					else if(data.reason==220001)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"OSS连接错误",5000,"auto","error");
					else if(data.reason==220002&&this.method==1)
					{
						merge_timer=setTimeout(()=>
						{
							if(cnt>50)
							{
								clearTimeout(merge_timer),merge_timer=null;
								this.stopupload=true;
								this.fail_reason=data.reason;
								uploaded_fail_call_back();
							}
							this.merge_file();
							cnt++;
						},500);
						return;
					}
					if(merge_timer!=null)
						clearTimeout(merge_timer),merge_timer=null;
					if(!this.stopupload)
					{
						this.fail_reason=data.reason;
						uploaded_fail_call_back();
					}
					this.stopupload=true;
					return ;
				}
				jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+'上传成功',2000,'auto','ok');
				this.loaded=this.total;
				uploaded_call_back(data)
			}
		}
	}
	jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=pre_check',(data)=>
	{
		jry_wb_loading_off();
		data=JSON.parse(data);
		if(!data.code)
		{
			if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
			else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
			else if(data.reason==200000)	jry_wb_beautiful_right_alert.alert("错误的存储区",5000,"auto","error");
			else if(data.reason==200001)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+type+"是不允许的文件类型",5000,"auto","error");
			else if(data.reason==200002)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"用户空间不足",5000,"auto","error");
			else if(data.reason==200003)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"当前存储区域不足",5000,"auto","error");
			else if(data.reason==200005)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"文件重复",5000,"auto","error");
			else if(data.reason==200006)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"父目录不存在",5000,"auto","error");
			else if(data.reason==220000)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"OSSSDK未知错误",5000,"auto","error");
			else if(data.reason==220001)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"OSS连接错误",5000,"auto","error");
			else if(data.reason==220003)	jry_wb_beautiful_right_alert.alert(name+(type==''?'':('.'+type))+"上传失败，因为:"+"STS签名错误",5000,"auto","error");
			this.stopupload=true;
			this.fail_reason=data.reason;
			uploaded_fail_call_back();			
			return ;
		}		
		this.file_id=data.file_id;
		this.method=data.method;
		this.extern_message=data.extern_message;
		if(this.method==0)
		{
			while(start<file.size) 
			{
				end=Math.min(start+jry_wb_netdisk_uploader_max_size_pre_chunk,file.size);
				this.upload_file(file,index,start,end);
				start = end;
				index++;
			}
		}
		else if(this.method==1)/*阿里云OSS*/
		{
			var ossconfig={
			  region:'oss-'+data.extern_message.region,
			  bucket:data.extern_message.bucket,
			  accessKeyId:data.extern_message.response.Credentials.AccessKeyId,
			  accessKeySecret:data.extern_message.response.Credentials.AccessKeySecret,
			  stsToken:data.extern_message.response.Credentials.SecurityToken,
			}
			var client=new OSS(ossconfig);
			var result= client.multipartUpload(data.extern_message.name,file,
			{ 
				progress: (p, checkpoint)=>
				{
					this.loaded=this.total*p;
					if(p==1)
					{
						setTimeout(()=>{this.merge_file();},500);
					}
				}
			});
		}
	},[{'name':'father','value':father},{'name':'name','value':name},{'name':'type','value':type},{'name':'size','value':Math.ceil(file.size/1024)}]);
}