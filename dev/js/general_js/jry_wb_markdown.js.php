<?php if(false){ ?><script><?php } ?>
function jry_wb_markdown(area,id,time,text,notitle)
{
	this.notitle=true;
	if(notitle==null)
		this.notitle=false;
	this.father=area;
	this.area=document.createElement('div');
	this.father.appendChild(this.area);
	this.catalog=document.createElement('ul');
	this.autonum=false;
	this.catalogdoc=[];
	this.lastcatalog=null;
	this.lasttext=null;
	this.title='';
	this.color_flag=false;
	this.color_flag=false;
	this.delete_flag=false;	
	this.strong_flag=false;
	this.id=id;
	var allow_html=['audio','div','span','a','b','p','img'];
	var disallow_attribute=['onclick','onmouseover','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmousewheel','ondrag','ondrop','onfocus'];
	function test(text,i,word)
	{
		for(var j=0;j<word.length&&(i+j)<text.length;j++)
			if(word[j]!=text[i+j])
				return false;
		return word.length;
	}
	this.fresh=(time,text)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>console.time('jry_wb_markdown');<?php } ?>
		var start=new Date();
		this.father.removeChild(this.area);
<?php if(JRY_WB_DEBUG_MODE){ ?>if(typeof console.timeLog!='undefined')console.timeLog('jry_wb_markdown');<?php } ?>
		this.area.innerHTML='';
		this.catalog.innerHTML='';
		this.autonum=false;
		this.catalogdoc=[];
		this.lastcatalog=null;
		this.lasttext=null;
		this.title='';
		this.color_flag=false;
		this.delete_flag=false;
		this.em_flag=false;
		this.strong_flag=false;
		if(typeof jry_wb_ajax_get_text=="function")
			text=jry_wb_ajax_get_text(text);
		else
			text=(text);
		if(text==this.text)
			return;
		this.text=text;
		var buf=document.createElement('div');
		var title_flag=false;
		var title_flag_enable=true;
		for(var i=0,n=this.text.length;i<n;i++)
		{
			if(this.text[i]=='-'&&this.text[i+1]=='-'&&this.text[i+2]=='-')
			{
				if(title_flag)
				{
					title_flag=false;
					if(!this.notitle)
					{
						var div=document.createElement("div");this.area.appendChild(div);
						div.align="right";
						div.classList.add('md_time_and_user');				
						div.innerHTML="by";
						jry_wb_get_and_show_user(div,this.id,null,null,true);
						div=null;
						var div=document.createElement("div");this.area.appendChild(div);
						div.innerHTML="at&nbsp;"+time;
						div.align="right";
						div.classList.add('md_time_and_user');				
						var hr=document.createElement("HR");this.area.appendChild(hr);
						hr.classList.add('md_hr');	
					}					
				}
				else if(title_flag_enable)
					title_flag=true;
				i+=2;
				continue;
			}
			else if(this.text[i]!=' '||this.text[i]!='\n'||this.text[i]!='\t')
			{
				title_flag_enable=false;
			}
			if(title_flag)
			{
				if(j=test(this.text,i,'title:'))
				{
					i+=j;
					var title=document.createElement("div");
					if(!this.notitle)
						this.area.appendChild(title);
					title.classList.add('md_title');
					title.align="center";	
					for(;i<n&&this.text[i]!='\n'&&this.text[i]!='-';i++)
						title.innerHTML+=this.text[i];
					this.title=title.innerHTML;
				}
				else if(j=test(this.text,i,'autonum:'))
				{
					i+=j;
					var buf='';
					for(;i<n&&this.text[i]!='\n'&&this.text[i]!='-';i++)
						buf+=this.text[i];
					if(buf=='true')
						this.autonum=true;
					else if(buf=='false')
						this.autonum=false;
				}
			}
			else
			{
				if(this.text[i]=='#'&&(i==0||this.text[i-1]=='\n'))
				{
					for(var j=0;(i+j)<this.text.length&&this.text[i+j]=='#';j++);
					i+=j;
					var buf='';
					for(;i<n&&this.text[i]!='\n';i++)
						buf+=this.text[i];
					var a=document.createElement("a");this.area.appendChild(a);
					var a2=a.cloneNode();
					a.classList.add('md_h_'+j);
					a2.classList.add('md_a');
					a.innerHTML=a2.innerHTML=buf;
					a2.href='#'+(a.name=JSON.stringify({'word':buf}));
					var br=document.createElement("br");this.area.appendChild(br);
					var li=document.createElement("li");
					li.appendChild(a2);
					li.setAttribute('h',j);
					var ul=document.createElement("ul");li.appendChild(ul);
					if(this.lastcatalog==null)
					{
						if(this.catalog.children.length<2)
							this.catalog.appendChild(li);
						else
							this.catalog.children[1].appendChild(li);
						li.setAttribute('num',1);
						li.setAttribute('str','1.');
						if(this.autonum)
							a.innerHTML=a2.innerHTML='1.'+a.innerHTML;							
					}
					else if(parseInt(this.lastcatalog.getAttribute('h'))>=j)
					{
						var get=(a,j)=>
						{
							if(a==null)
								return this.catalog;
							else if(parseInt(a.getAttribute('h'))<j)
								return a;
							else
								return get(a.parentNode,j);
						};
						var b=get(this.lastcatalog,j);
						var num;
						if(b==this.catalog&&b.children==0)
							li.setAttribute('num',num=(1));
						else if(b==this.catalog)
							li.setAttribute('num',num=(parseInt(b.children[b.children.length-1].getAttribute('num'))+1));
						else if(b.children.length<2)
							li.setAttribute('num',num=(1));
						else if(b.children[1].children.length==0)
							li.setAttribute('num',num=(1));
						else
							li.setAttribute('num',num=(parseInt(b.children[1].children[b.children[1].children.length-1].getAttribute('num'))+1));
						var str;
						li.setAttribute('str',(str=b.getAttribute('str')+num+'.'));
						if(this.autonum)
							a.innerHTML=a2.innerHTML=str+a.innerHTML;
						if(b==this.catalog)
							b.appendChild(li);
						else
							b.children[1].appendChild(li);						
					}
					else
					{
						var get=(a,j)=>
						{
							if(parseInt(a.getAttribute('h'))<=j||a.children.length==0)
								return a;
							else
								return get(a.children[a.children.length-1],j);
						};
						var b=get(this.lastcatalog,j);
						li.setAttribute('num',1);
						var str;
						li.setAttribute('str',(str=b.getAttribute('str')+1+'.'));
						if(this.autonum)
							a.innerHTML=a2.innerHTML=str+a.innerHTML;						
						b.children[1].appendChild(li);
					}
					this.lastcatalog=li;
					this.lasttext=null;
				}
				else if(this.text[i]=='<')
				{
					var flag=false;
					for(var j=0;j<allow_html.length;j++)
						if((k=test(this.text,i+1,allow_html[j])))
						{
							var art='';
							for(var a=0;a+k+i+1<n;a++)
								if(this.text[a+k+i+1]=='>')
								{
									var buf='';
									for(var b=0;a+k+i+b+2<n&&a<20000&&(!(c=test(this.text,a+k+i+b+2,'</'+allow_html[j]+'>')));b++)
										buf+=this.text[a+k+i+b+2];
									if(c)
									{
										i+=a+b+k+c+2-1;
										flag=true;
										this.lasttext=null;
										var dom=document.createElement(allow_html[j]);
										art=art.split(' ');
										for(var d=0;d<art.length;d++)
										{
											var buff=art[d].split('=');
											if(buff[0]!=undefined&&buff[1]!=undefined)
												dom.setAttribute(buff[0],buff[1].slice(1,-1));
										}
										dom.innerHTML=buf;
										function ju(d)
										{
											if(d.tagName=='SCRIPT')
												setTimeout(function(){eval(d.innerHTML)},500);
											for(var e=0;e<disallow_attribute.length;e++)
												d.removeAttribute(disallow_attribute[e]);
											for(var i=0;i<d.children.length;i++)
												if(!allow_html.includes(d.children[i].tagName.toLowerCase()))
													d.removeChild(d.children[i]);
												else
													ju(d.children[i]);
										}
										ju(dom);
										this.area.appendChild(dom);
										break;
									}										
								}
								else
									art+=this.text[a+k+i+1];
							
							break;
						}
					if(!flag)
					{
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						this.lasttext.innerHTML+=this.text[i];
					}						
				}
				else if(this.text[i]=='\n')
				{
					if(this.lasttext==null)
						this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
					var br=document.createElement("br");this.lasttext.appendChild(br);
				}
				else if(this.text[i]=='>'&&(i==0||this.text[i-1]=='\n'))
				{
					i++;
					if(this.lasttext==null)
						this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');					
					var cite=document.createElement("cite");this.lasttext.appendChild(cite);
					cite.classList.add('md_cite');
					for(;i<n&&this.text[i]!='\n';i++)
						cite.innerHTML+=this.text[i];
					var br=document.createElement("br");this.lasttext.appendChild(br);
				}
				else if(j=test(this.text,i,'[toc]'))
				{
					i+=j;
					var buf=document.createElement('ul');this.area.appendChild(buf);
					buf.classList.add('md_toc_ul');
					this.catalogdoc.push(buf);
					buf.innerHTML=this.catalog.innerHTML;
					this.lasttext=null;
				}				
				else if(this.text[i]=='[')
				{
					var word='';
					var href='';
					var failed=false;
					for(var j=0;(i+j+1)<n&&this.text[i+j+1]!=']'&&j<128;j++)
						word+=this.text[i+j+1];
					j+=2;
					if(j<128)
					{
						if(this.text[i+j]=='(')
						{
							for(var k=0;(i+j+k+1)<n&&this.text[i+j+k+1]!=')'&&k<256;k++)
								href+=this.text[i+j+k+1];
							if(k<256&&(test(href,0,'http://')||test(href,0,'https://')))
							{
								i+=j;
								i+=k;
								i+=1;
								if(this.lasttext==null)
									this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');					
								var a=document.createElement("a");this.lasttext.appendChild(a);
								a.href=href;
								a.classList.add('md_link');
								a.setAttribute('target','_blank');
								a.innerHTML=word;	
							}
							else
								failed=true;
						}
						else
							failed=true;
					}
					else
						failed=true;
					if(failed)
					{
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						this.lasttext.innerHTML+=this.text[i];
					}
				}			
				else if(j=test(this.text,i,'=='))
				{
					i+=j;
					if(this.color_flag)
					{
						i-=1;
						this.color_flag=false;
						this.lasttext=this.lasttext.parentNode;
					}
					else
					{
						this.color_flag=true;
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						var buf=document.createElement("span");this.lasttext.appendChild(buf);this.lasttext=buf;
						var buf='';
						if(this.text[i]=='#')
						{
							for(var j=0;i<n&&this.text[i]!=' '&&j<7;i++,j++)
								buf+=this.text[i];
							i-=1;
						}
						else
							buf='#66ccff';
						this.lasttext.style.color=buf;
					}
				}
				else if(j=test(this.text,i,'~~'))
				{
					i+=(j-1);
					if(this.delete_flag)
					{
						this.delete_flag=false;
						this.lasttext=this.lasttext.parentNode;
					}
					else
					{
						this.delete_flag=true;
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');						
						var buf=document.createElement("span");this.lasttext.appendChild(buf);this.lasttext=buf;
						buf.classList.add('line_m');
					}
				}
				else if(j=test(this.text,i,'__'))
				{
					i+=(j-1);
					if(this.em_flag)
					{
						this.em_flag=false;
						this.lasttext=this.lasttext.parentNode;
					}
					else
					{
						this.em_flag=true;
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');						
						var buf=document.createElement("em");this.lasttext.appendChild(buf);this.lasttext=buf;
					}
				}
				else if(j=test(this.text,i,'**'))
				{
					i+=(j-1);
					if(this.strong_flag)
					{
						this.strong_flag=false;
						this.lasttext=this.lasttext.parentNode;
					}
					else
					{
						this.strong_flag=true;
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');						
						var buf=document.createElement("strong");this.lasttext.appendChild(buf);this.lasttext=buf;
					}
				}
				else if(j=test(this.text,i,'[@'))
				{
					var id='';
					for(var j=0;(i+j+2)<n&&this.text[i+j+2]!=']'&&j<128;j++)
						id+=this.text[i+j+2];
					if(!isNaN(id=parseInt(id)))
					{
						i+=(j+2);
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');						
						jry_wb_get_and_show_user(this.lasttext,id,'200px',null,true);
					}
					else
					{
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						this.lasttext.innerHTML+='[@';
						i+=1;
					}
				}
				else if(j=test(this.text,i,'!['))
				{
					var word='';
					var href='';
					var failed=false;
					for(var j=0;(i+j+2)<n&&this.text[i+j+2]!=']'&&j<128;j++)
						word+=this.text[i+j+2];
					j+=2;
					if(j<128)
					{
						if(this.text[i+j+1]=='(')
						{
							for(var k=0;(i+j+k+2)<n&&this.text[i+j+k+2]!=')'&&k<256;k++)
								href+=this.text[i+j+k+2];
							if(k<256&&((buf=href.split(',')).length>=2)&&!isNaN(parseInt(buf[0]))&&!isNaN(parseInt(buf[1])))
								href='http://dev.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id='+buf[0]+'&file_id='+buf[1]+(buf[2]==undefined?'':'&fast='+buf[2]);
							if(k<256&&(test(href,0,'http://')||test(href,0,'https://')))
							{
								i+=j;
								i+=k;
								i+=2;
								var div=document.createElement("div");this.area.appendChild(div);
								div.align="center";
								var img=document.createElement("img");div.appendChild(img);
								img.src=href;
								img.classList.add('md_img');
								img.setAttribute('onclick','jry_wb_beautiful_alert.openpicture("'+word+'",(document.body.clientWidth-100),(document.body.clientHeight-100),"'+href+'");');
								var br=document.createElement("br");div.appendChild(br);
								var a=document.createElement("a");div.appendChild(a);
								a.innerHTML=word;
								a.href=href;
								a.className="md_media_font";
								this.lasttext=null
							}
							else
								failed=true;
						}
						else
							failed=true;
					}
					else
						failed=true;
					if(failed)
					{
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						this.lasttext.innerHTML+=this.text[i];
					}
				}
				else if(j=test(this.text,i,'%['))
				{
					var word='';
					var href='';
					var failed=false;
					for(var j=0;(i+j+2)<n&&this.text[i+j+2]!=']'&&j<128;j++)
						word+=this.text[i+j+2];
					j+=2;
					if(j<128)
					{
						if(this.text[i+j+1]=='(')
						{
							for(var k=0;(i+j+k+2)<n&&this.text[i+j+k+2]!=')'&&k<256;k++)
								href+=this.text[i+j+k+2];
							if(k<256&&(test(href,0,'http://')||test(href,0,'https://')))
							{
								i+=j;
								i+=k;
								i+=2;
								var div=document.createElement("div");this.area.appendChild(div);
								div.align="center";
								var video=document.createElement("video");div.appendChild(video);
								video.src=href;
								video.setAttribute('onclick','jry_wb_beautiful_alert.openvideo("'+word+'",(document.body.clientWidth-100),(document.body.clientHeight-100),"'+href+'",function(){return true;},function(){return true;});');
								new jry_wb_beautiful_video(video);
								var br=document.createElement("br");div.appendChild(br);
								var a=document.createElement("a");div.appendChild(a);
								a.innerHTML=word;
								a.href=href;
								a.className="md_media_font";
								this.lasttext=null
							}
							else
								failed=true;
						}
						else
							failed=true;
					}
					else
						failed=true;
					if(failed)
					{
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						this.lasttext.innerHTML+=this.text[i];
					}					
				}
				else if(j=test(this.text,i,'~['))
				{
					var word='';
					var href='';
					var failed=false;
					for(var j=0;(i+j+2)<n&&this.text[i+j+2]!=']'&&j<128;j++)
						word+=this.text[i+j+2];
					j+=2;
					if(j<128)
					{
						if(this.text[i+j+1]=='(')
						{
							for(var k=0;(i+j+k+2)<n&&this.text[i+j+k+2]!=')'&&k<256;k++)
								href+=this.text[i+j+k+2];
							if(k<256&&(test(href,0,'http://')||test(href,0,'https://')))
							{
								i+=j;
								i+=k;
								i+=2;
								var div=document.createElement("div");this.area.appendChild(div);
								div.align="center";
								var audio=document.createElement("audio");div.appendChild(audio);
								audio.src=href;
								(new jry_wb_beautiful_music(audio,null,false,true)).set_background_picture('','');
								var br=document.createElement("br");div.appendChild(br);
								var a=document.createElement("a");div.appendChild(a);
								a.innerHTML=word;
								a.href=href;
								a.className="md_media_font";
								this.lasttext=null
							}
							else
								failed=true;
						}
						else
							failed=true;
					}
					else
						failed=true;
					if(failed)
					{
						if(this.lasttext==null)
							this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
						this.lasttext.innerHTML+=this.text[i];
					}					
				}
				else if(j=test(this.text,i,'```'))
				{
					i+=j;
					if((j=jry_wb_highlight(this.area,this.text,i))!==false)
						i=j;
					this.lasttext=null;
				}				
				else if((j=test(this.text,i,'-[x]'))||(k=test(this.text,i,'-[]')))
				{
					if(this.lasttext==null)
						this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
					var b=document.createElement("b");this.lasttext.appendChild(b);
					b.classList.add('jry_wb_icon');
					if(j==false)
						j=k,b.classList.add('md_unfinish','jry_wb_icon_hr');
					else
						b.classList.add('md_finish','jry_wb_icon_duigoux');
					i+=(j-1);
				}
				else if((j=test(this.text,i,'[http://'))||(k=test(this.text,i,'[https://')))
				{
					var href='http';
					if(j==false)
						href+='s',j=k;
					href+='://';
					i+=j;
					for(;i<n&&this.text[i]!=']';i++)
						href+=this.text[i];
					if(this.lasttext==null)
						this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');					
					var a=document.createElement("a");this.lasttext.appendChild(a);
					a.href=href;
					a.classList.add('md_link');
					a.setAttribute('target','_blank');
					a.innerHTML='网页链接';
				}
				else
				{
					if(this.lasttext==null)
						this.lasttext=document.createElement("span"),this.area.appendChild(this.lasttext),this.lasttext.classList.add('md_normal');
					this.lasttext.innerHTML+=this.text[i];
				}
			}
		}
		for(var i=0;i<this.catalogdoc.length;i++)
			this.catalogdoc[i].innerHTML=this.catalog.innerHTML;
<?php if(JRY_WB_DEBUG_MODE){ ?>if(typeof console.timeLog!='undefined')console.timeLog('jry_wb_markdown');<?php } ?>
		this.father.appendChild(this.area);		
<?php if(JRY_WB_DEBUG_MODE){ ?>console.timeEnd('jry_wb_markdown');<?php } ?>
		this.time=(new Date()-start);
	};
	this.fresh(time,text);
}
<?php if(false){ ?></script><?php } ?>