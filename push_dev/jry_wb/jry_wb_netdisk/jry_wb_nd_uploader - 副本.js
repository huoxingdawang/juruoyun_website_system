jry_wb_right_meau=null;
jry_wb_include_once_css("jry_wb_nd.css");
function jry_wb_netdisk_uploader(area)
{
	this.body=document.createElement("div");area.appendChild(this.body);
	this.uploader_body=document.createElement("div");this.body.appendChild(this.uploader_body);
	var label=document.createElement("label");this.uploader_body.appendChild(label);
	label.classList.add("jry_wb_button","jry_wb_button_size_middle","jry_wb_color_normal");
	label.innerHTML="选择文件";
	this.input=document.createElement("input");this.uploader_body.appendChild(this.input);
	label.setAttribute("for",(this.input.id="jry_wb_uploader_"+Math.random()))
	this.input.multiple="multiple";
	this.input.type="file";
	this.input.style.display="none";
	var button=document.createElement("button");this.uploader_body.appendChild(button);
	button.innerHTML="开始上传";
	button.classList.add("jry_wb_button","jry_wb_button_size_middle","jry_wb_color_ok");
	this.show_body=document.createElement("div");this.body.appendChild(this.show_body);
	this.show_body.style.overflow="hidden";
	this.show_body.style.borderSpacing="0px";
	this.input.onchange=()=>
	{
		this.get_all_file();
		this.refresh();
	}
	button.onclick=()=>
	{
		console.log(this.file_buf);
		if(this.file_buf==undefined||this.file_buf.files.length==0)
		{
			jry_wb_beautiful_alert.alert("请选择文件","");
			return;
		}
		else
		{
			jry_wb_ajax_load_data('jry_nd_upload.php?action=pre_check',(data)=>
			{
				jry_wb_loading_off();
				data=JSON.parse(data);
				if(!data.login)
				{
					jry_wb_beautiful_alert.alert("无法操作","因为"+data.reason,"window.location.href=jry_wb_message.jry_wb_host");
					return ;
				}
				if(!data.code)
				{
					if(data.reason==1)
						jry_wb_beautiful_alert.alert("您选择的文件太大了",'');
					if(data.reason==2)
						jry_wb_beautiful_alert.alert("您选择的文件中包含您不能上传的文件类型",'您只能上传后缀为'+jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_allow_type.toString()+'的文件');										
					return ;
				}
				jry_wb_beautiful_right_alert.alert('预检测正常',3000,'auto','ok');
				jry_wb_beautiful_right_alert.alert('准备启动上传进程',3000,'auto','warn');
				jry_wb_beautiful_right_alert.alert('已启动上传进程',3000,'auto','ok');
				for(var i=0,n=this.file_buf.files.length;i<n;i++)
				{
					var one=new jry_wb_netdisk_upload_file(this.file_buf.files[i]);
				}
			},[{'name':'total_size','value':this.file_buf.total_size},{'name':'all_type','value':JSON.stringify(this.file_buf.all_type)}]);
		}
	}
}
jry_wb_netdisk_uploader.prototype.refresh=function()
{
	var types=new Array();
	var size=0;
	for(var i=0,n=this.file_buf.files.length;i<n;i++)
		size+=this.file_buf.files[i].size,types.push(this.file_buf.files[this.file_buf.files.length-1].file_type=jry_wb_get_file_type(this.file_buf.files[i].name));
	console.log(types);
	this.file_buf={'files':this.file_buf.files,'total_size':size,'all_type':types.unique()}	
	this.show_body.innerHTML="";
	for(var i=0,n=this.file_buf.files.length;i<n;i++)
	{
		var button_body=document.createElement("div");this.show_body.appendChild(button_body);
		button_body.classList.add('jry_wb_netdisk_file');
		this.file_buf.files[i].button_body=button_body;
		var button=document.createElement("div");button_body.appendChild(button);
		button.classList.add('iconfont','jry_wb_netdisk_file_type');
		button.name=i;
		switch(this.file_buf.files[i].file_type)
		{
			/*多媒体*/
			case 'gif':
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'ico':
				button.classList.add('icon-filepicture','jry_wb_netdisk_file_type_pic');/*图片*/
				break;
			case 'mp3':
			case 'wav':
				button.classList.add('icon-yinpinwenjian','jry_wb_netdisk_file_type_music');/*音频*/
				break;
			case 'mp4':
			case 'flv':
				button.classList.add('icon-filevideo','jry_wb_netdisk_file_type_video');;/*视频*/
				break;
			/*常用文件*/
			case 'plain':
				button.classList.add('icon-wenjian1','jry_wb_netdisk_file_type_txt');/*文件*/
				break;
			
			case 'pdf':
				button.classList.add('icon-pdf','jry_wb_netdisk_file_type_pdf');/*PDF*/
				break;
			case 'ppt':
			case 'pptx':
				button.classList.add('icon-ppt','jry_wb_netdisk_file_type_ppt');/*PPT*/
				break;
			case 'xls':
			case 'xlsx':
				button.classList.add('icon-excel','jry_wb_netdisk_file_type_xls');/*excel*/
				break;
			case 'doc':
			case 'docx':
				button.classList.add('icon-docx','jry_wb_netdisk_file_type_doc');/*WORD*/
				break;
			case 'c'	:
			case 'cpp'	:		
			case 'h'	:		
			case 'html'	:		
			case 'php'	:		
			case 'css'	:	
			case 'js'	:
			case 'bat'	:		
				button.classList.add('icon-daimawenjian-','jry_wb_netdisk_file_type_code');/*CODE*/
				break;
			case 'rar':
			case 'zip':
				button.classList.add('icon-yasuowenjian','jry_wb_netdisk_file_type_zip');/*压缩文件*/
				break;
			case 'exe':
			case 'dll':
				button.classList.add('icon-chengxu','jry_wb_netdisk_file_type_program');/*运行程序*/
				break;					
			default:
				button.classList.add('icon-file-unknown','jry_wb_netdisk_file_type_unknow');/*???*/
				break;					
		}
		/*var button=document.createElement("div");this.file_buf.files[id].button_body.appendChild(button);
		button.classList.add('fa','jry_wb_netdisk_file_type','fa-check','jry_wb_color_ok_font');*/
		jry_wb_add_onclick(function()
		{
			if(jry_wb_right_meau!=null)
			{
				document.body.removeChild(jry_wb_right_meau);
				jry_wb_right_meau=null;
			}
		});
		jry_wb_add_onscroll(function()
		{
			if(jry_wb_right_meau!=null)
			{
				document.body.removeChild(jry_wb_right_meau);
				jry_wb_right_meau=null;
			}
		});
		button.oncontextmenu=(event)=>
		{
			var event_src=event.srcElement?event.srcElement:event.target;
			var id=parseInt(event_src.name);
			if(event.touches!=null)
				event = event.touches[0];
			else if(event.changedTouches!=null)
				event=event.changedTouches[0];
			else
				event = event;
			var scrollTop=document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
			var scrollLeft=document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
			if(jry_wb_right_meau!=null)
			{
				document.body.removeChild(jry_wb_right_meau);
				jry_wb_right_meau=null;
			}
			jry_wb_right_meau=document.createElement("div");document.body.appendChild(jry_wb_right_meau);
			jry_wb_right_meau.className='jry_wb_netdisk_right_menu';
			var y=Math.min(event.clientY,document.body.clientHeight+scrollTop-jry_wb_right_meau.offsetHeight);
			var x=Math.min(event.clientX,document.body.clientWidth+scrollLeft-jry_wb_right_meau.offsetWidth);
			jry_wb_right_meau.style.left=x;jry_wb_right_meau.style.top=y;				
			var delate=document.createElement("div");jry_wb_right_meau.appendChild(delate);
			delate.innerHTML='删除';
			delate.className='jry_wb_netdisk_right_menu_text'; 
			delate.onclick=()=>
			{
				jry_wb_beautiful_alert.check("确定将文件移出上传列表",()=>
				{
					uploader.file_buf.files.splice(id,1);
					this.refresh();
				},function()
				{
					jry_wb_beautiful_alert.alert("未执行操作","");
					setTimeout(function()
					{
						jry_wb_beautiful_alert.close();
					},800);
				});
			}
			return false;
		}
		var name=document.createElement("div");button_body.appendChild(name);
		name.innerHTML=this.file_buf.files[i].name;
		name.classList.add('jry_wb_netdisk_file_name','jry_wb_word_cut');
	}
	window.onresize();	
}
jry_wb_netdisk_uploader.prototype.get_all_file=function()
{
	var ans=new Array();
	var types=new Array();
	var size=0;
	for(var i=0,n=this.input.files.length;i<n;i++)
		ans.push(this.input.files[i]),size+=ans[i].size,ans[ans.length-1].file_type=jry_wb_get_file_type(ans[ans.length-1].name),types.push(ans[ans.length-1].type);
	return this.file_buf={'files':ans,'total_size':size,'all_type':types.unique()};
}