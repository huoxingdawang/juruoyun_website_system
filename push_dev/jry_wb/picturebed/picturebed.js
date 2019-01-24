function picturebed_run(mode)
{
	var jry_wb_right_meau=null;
	jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'picturebed/do_mypicturebed.php?action=get&mode='+mode,function(data)
	{
		jry_wb_include_once_css(jry_wb_message.jry_wb_host+'picturebed/pictruebed.css');
		data=JSON.parse(data);
		jry_wb_loading_off();
		if(data.login==false)
		{
			jry_wb_beautiful_alert.alert("没有登录","因为"+buf.reasion,"window.location.href='"+returnaddr+"'");
			return ;	
		}
		var main=document.getElementById('show');
		main.className="picturebed_all";
		jry_wb_add_onclick(function(){if(jry_wb_right_meau!=null){document.body.removeChild(jry_wb_right_meau);jry_wb_right_meau=null;}});
		for(var i=0,n=data.length;i<n;i++) 
		{
			var one=document.createElement("div");main.appendChild(one);
			one.className='picturebed_one';one.name=one.id=data[i].pictureid;
			var touched=false;
			one.addEventListener("touchstart",function(event){touched=true;event.preventDefault();start=new Date();setTimeout(function(){if((new Date()-start>750)&&touched){one.oncontextmenu(event);}else setTimeout(arguments.callee, 10);}, 10);},false);
			one.addEventListener("touchend",function(event){touched=false;event.preventDefault();}, false);
			one.oncontextmenu=function(event){
				var id=parseInt(event.srcElement?event.srcElement.name:event.target.name);
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
				jry_wb_right_meau.className='picturebed_right';
				var delate=document.createElement("div");jry_wb_right_meau.appendChild(delate);
				delate.innerHTML='删除';
				delate.className='picturebed_right_font'; 
				delate.onclick=function(){jry_wb_beautiful_alert.check("确定删除图片",function(){jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'picturebed/do_mypicturebed.php?action=delate&mode='+mode+'&pictureid='+id,function(data){data=JSON.parse(data);jry_wb_loading_off();if(data.state){jry_wb_beautiful_right_alert.alert('删除'+id,2000,'auto','ok');document.getElementById('show').removeChild(document.getElementById(id));}else{jry_wb_beautiful_right_alert.alert('因为'+data.reason+'删除'+id+'失败',5000,'auto','error');}});},function(){jry_wb_beautiful_right_alert.alert('没有执行操作',2000,'auto','warn');})};
				var download=document.createElement("div");jry_wb_right_meau.appendChild(download);
				download.innerHTML='下载';
				download.className='picturebed_right_font';
				download.onclick=function(){window.open(jry_wb_message.jry_wb_host+'picturebed/get_picturebed.php?action=download&pictureid='+id);jry_wb_beautiful_right_alert.alert('下载成功',2000,'auto','ok');};					
				var open=document.createElement("div");jry_wb_right_meau.appendChild(open);
				open.innerHTML='新窗口打开';
				open.className='picturebed_right_font';
				open.onclick=function(){window.open(jry_wb_message.jry_wb_host+'picturebed/get_picturebed.php?pictureid='+id);jry_wb_beautiful_right_alert.alert('新窗口打开成功',2000,'auto','ok');};					
			 
				var copyurl=document.createElement("div");jry_wb_right_meau.appendChild(copyurl);
				copyurl.innerHTML='复制链接地址';
				copyurl.className='picturebed_right_font';
				copyurl.onclick=function(){jry_wb_copy_to_clipboard('http://juruoyun.top/mywork/picturebed/get_picturebed.php?pictureid='+id);jry_wb_beautiful_right_alert.alert('复制链接地址成功',2000,'auto','ok');};
				var copyid=document.createElement("div");jry_wb_right_meau.appendChild(copyid);
				copyid.innerHTML='复制图片编号';
				copyid.className='picturebed_right_font';
				copyid.onclick=function(){jry_wb_copy_to_clipboard(id);jry_wb_beautiful_right_alert.alert('复制图片编号成功',2000,'auto','ok');};														
				var copydownload=document.createElement("div");jry_wb_right_meau.appendChild(copydownload);
				copydownload.innerHTML='复制下载地址';
				copydownload.className='picturebed_right_font';
				copydownload.onclick=function(){jry_wb_copy_to_clipboard('http://juruoyun.top/mywork/picturebed/get_picturebed.php?action=download&pictureid='+id);jry_wb_beautiful_right_alert.alert('复制下载地址成功',2000,'auto','ok');};							
				var y=Math.min(event.clientY,document.body.clientHeight+scrollTop-jry_wb_right_meau.offsetHeight);
				var x=Math.min(event.clientX,document.body.clientWidth+scrollLeft-jry_wb_right_meau.offsetWidth);
				jry_wb_right_meau.style.left=x;jry_wb_right_meau.style.top=y;
				/*console.log({id:id,x:x,y:y});*/
				return false;
			}
			var src_=jry_wb_message.jry_wb_host+'picturebed/get_picturebed.php?pictureid='+data[i].pictureid;
			var div=document.createElement("div");one.appendChild(div);
			div.align="center";div.className="picturebed_img_div";div.name=data[i].pictureid;
			jry_wb_beautiful_right_alert.alert('加载图片中，请稍等',1000,'auto','warn');
			var img=document.createElement("img");div.appendChild(img);
			img.src=src_+'&size=300&small=1';
			img.className='picturebed_img';img.name=data[i].pictureid;
			img.setAttribute('onclick','jry_wb_beautiful_alert.openpicture("'+data[i].pictureid+'",(document.body.clientWidth-50),(document.body.clientHeight-100),"'+src_+'");');
			img.onload=function()
			{
				jry_wb_beautiful_right_alert.alert('加载图片'+this.name+'完毕',500,'auto','ok');
				jry_wb_loading_off();
			};
			div=null;
			var div=document.createElement("div");one.appendChild(div);
			div.align="center";div.name=data[i].pictureid;
			div.innerHTML='ID:'+data[i].pictureid+'&nbsp;BY:';
			div.className="picturebed_font";
			jry_wb_get_and_show_user(div,data[i].id,'300px','',true);
			div=null;					
		}
		window.onresize();
	});
}
picturebed_run(mode);