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
				if((!data.login)||(!data.code))
				{
					if(!this.stopupload)
					{
						this.fail_reason=data.reason;
						uploaded_fail_call_back();
					}
					this.stopupload=true;
					return ;
				}
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
				if((!data.login)||(!data.code))
				{
					if(data.reason==10&&this.method==1)
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
				jry_wb_beautiful_right_alert.alert('文件上传成功',2000,'auto','ok');
				this.loaded=this.total;
				uploaded_call_back(data)
			}
		}
	}
	jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=pre_check',(data)=>
	{
		jry_wb_loading_off();
		data=JSON.parse(data);
		if(!data.login)
		{
			this.stopupload=true;
			this.fail_reason=data.reason;		
			return ;
		}
		if(!data.code)
		{
			this.stopupload=true;
			this.fail_reason=data.reason;		
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
			  region:data.extern_message.region,
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