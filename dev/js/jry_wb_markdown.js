function jry_wb_markdown(area,id,time,text,notitle)
{
	console.time('jry_wb_markdown');
	if(notitle==null)
		notitle=false;
	this.area=area;
	this.tocs=new Array();
	this.toc_count=new Array();
	this.tocs_tree=new Array();
	this.last=0;
	this.autonum=false;
	this.toc_count[0]=this.toc_count[1]=this.toc_count[2]=this.toc_count[3]=this.toc_count[4]=this.toc_count[5]=this.toc_count[6]=this.toc_count[7]=this.toc_count[8]=0;
	this.text=jry_wb_ajax_get_text(text);
	this.area.innerHTML='';
	this.toc=function()
	{
		var toc=document.createElement("ul");this.area.appendChild(toc);
		this.tocs.push(toc);
		toc.id='toc_'+this.tocs.length;
		toc.className="md_toc_ul";
		if(this.tocs.length!=1)
			this.tocs[this.tocs.length-1].innerHTML=this.tocs[this.tocs.length-2].innerHTML;
		else
			this.tocs_tree[0]=toc;
	}
	this.showtitle=function(count,str)
	{
		for(var i=count;i<=8;i++)
			this.toc_count[i]=0;
		var a=document.createElement("a");this.area.appendChild(a);
		var rand=Math.random();
		a.name=rand;
		var h=document.createElement('div');this.area.appendChild(h);
		h.className='md_h_'+count;
		if(count-this.last>1)
		{
			for(var i=this.last;i<count;i++)
			{
				if(this.tocs_tree[i-1]!=null)
				{
					var li=document.createElement("li");this.tocs_tree[i-1].appendChild(li);
					li.className='md_toc_li';
					var ul=document.createElement("ul");li.appendChild(ul);
					ul.className='md_toc_ul'
					this.tocs_tree[i]=ul;
					this.toc_count[i-1]=1;
				}
			}		
		}
		this.last=count;
		if(this.autonum)
		{
			this.toc_count[count-1]++;
			var buf='';
			for(var i=0,n=count-1;i<n;i++)
				buf+=this.toc_count[i]+'.';
			str=buf+this.toc_count[count-1]+':'+str; 
		}	
		h.innerHTML=str;
		if(this.tocs.length!=0)
		{
			var li=document.createElement("li");this.tocs_tree[count-1].appendChild(li);
			var a2=document.createElement("a");li.appendChild(a2);
			a2.href='#'+rand;
			a2.innerHTML=str;
			a2.className="md_a";
			var ul=document.createElement("ul");li.appendChild(ul);
			this.tocs_tree[count]=ul;
		}
		
		for(var k=1,nnn=this.tocs.length;k<nnn;k++)
			this.tocs[k].innerHTML=this.tocs[k-1].innerHTML;
	}
	this.showcode=function(language_,str,area)
	{
		var code=document.createElement("div");area.appendChild(code);
		//code.onresize=function(){code.style.width=area.style.width};
		if(language_.includes("html_show"))
		{
			code.style.backgroundColor="#666666";
			code.innerHTML=str;
			return ;
		}
		code.id=language_;
		code.className="code";		
		var cut=/(\t|{|}|;|\(|\)|=|\+|~|!| |\||%|^|&|\*|-|\[|\]|:|'|"|\/|<|>|\?|,|\.|[0-9])/; 
		var important=['float','int','long','return','switch','if','void','double','#define','for','main','#include','using','namespace'];
		var fuhao = /[{};\(\)=\+~!\|%^&\*-\[\]:'"\/<>\?,\.]/;
		var num=/[0-9]/
		var data=str.split(/\n/g);
		for(var i=0,n=data.length;i<n;i++)
		{
			var line=data[i].split(cut);
			var code_one=document.createElement("div");code.appendChild(code_one);
			for(var j=0,nn=line.length;j<nn;j++)
			{
				if(line[j]=='')
					continue;
				else if(line[j]==' ')
					code_one.innerHTML+='&nbsp;'
				else if(num.test(line[j]))
				{
					var high=document.createElement("span");code_one.appendChild(high);
					high.className="number";
					high.innerHTML=line[j];
				}
				else if(important.indexOf(line[j])!=-1)
				{
					var high=document.createElement("span");code_one.appendChild(high);
					high.className="important";
					high.innerHTML=line[j]; 
				}
				else if(fuhao.test(line[j]))
				{
					var high=document.createElement("span");code_one.appendChild(high);
					high.className="fuhao"; 
					line[j]=line[j].replace(/&/g,"&amp;");
					line[j]=line[j].replace(/</g,"&lt;");
					line[j]=line[j].replace(/>/g,"&gt;");
					line[j]=line[j].replace(/"/g,"&quot;");
					line[j]=line[j].replace(/'/g,"&apos;");
					line[j]=line[j].replace(/￠/g,"&cent;");
					line[j]=line[j].replace(/£/g,"&pound;");
					line[j]=line[j].replace(/¥/g,"&yen;");
					line[j]=line[j].replace(/€/g,"&euro;");
					line[j]=line[j].replace(/§/g,"&sect;");
					line[j]=line[j].replace(/©/g,"&copy;");
					line[j]=line[j].replace(/®/g,"&reg;");
					line[j]=line[j].replace(/™/g,"&trade;");
					line[j]=line[j].replace(/×/g,"&times;");
					line[j]=line[j].replace(/÷/g,"&divide;");						
					high.innerHTML=line[j];
				}
				else
				{
					line[j]=line[j].replace(/\t/g,'<span style="white-space:pre">	</span>');
					var high=document.createElement("span");code_one.appendChild(high);
					high.className="normal";
					high.innerHTML=line[j];
				}
			}			
		}
	}	
	
	var data=this.text.split(/\n/g);
	var text=document.createElement("div");this.area.appendChild(text);
	text.className='md_normal';
	this.area.onresize=()=>{text.style.width=this.area.clientWidth};
	this.area.onresize();
	var highlight_flag=false;
	var highlight_buf=null;
	var highlight_color='';
	var delete_flag=false;
	var delete_buf=null;
	var flat_flag=false;
	var flat_buf=null;
	var strong_flag=false;
	var strong_buf=null;
	var cite_flag=false;	
	var cite_bug=null;	
	var text_new_flag=false;
	var latex_flag=false;
	var i=0;
	var md_title='';
	if(data[i].includes('title:')&&!notitle)
	{
		var div=document.createElement("div");text.appendChild(div);
		div.innerHTML=md_title=data[i].substring(6,data[i].length).replace(/ /g,"&nbsp;");
		div.align="center";
		div.className="md_title";
		div=null;
		var div=document.createElement("div");text.appendChild(div);
		div.align="right";
		div.className="md_time_and_user";
		div.innerHTML="by";
		jry_wb_get_and_show_user(div,id,null,null,true);
		div=null;
		var div=document.createElement("div");text.appendChild(div);
		div.innerHTML="at&nbsp;"+time;
		div.align="right";
		div.className="md_time_and_user";
		var HR=document.createElement("HR");text.appendChild(HR);
		HR.className='md_hr'
		jry_wb_add_onresize(function(){var all=HR;var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width;}else{all.style.width=width-2;}});
		i++;
	}
	if(data[i].includes('autonum:'))
	{
		if(data[i].substring(8,data[i].length)=='true')
			this.autonum=true;
		i++;
	}	
	for(var n=data.length;i<n;i++)
	{
		for(var j=0,nn=data[i].length;j<nn;j++)
		{
			switch(data[i][j])
			{
				case '[' :
					var code=data[i].substring(j,data[i].length).match(/\[(\S*)\]/)[1];
					if(code.length==0)
					{
						text.innerHTML+='[';
						break;						
					}
					j+=code.length+1;
					if(code=='toc')
					{
						this.toc();
						var text=document.createElement("div");this.area.appendChild(text);
						text.className='md_normal';
						text_new_flag=true;
					}else if(code[0]=='@')
					{
						var id=parseInt(code.substring(1,code.length));
						if(!isNaN(id))
							jry_wb_get_and_show_user(text,id,'200px',null,true);
					}
					else if(code[0]=='h'&&code[1]=='t'&&code[2]=='t'&&code[3]=='p')
					{
						var a=document.createElement("a");text.appendChild(a);
						a.href=code;
						a.innerHTML='网页链接';
						a.className='md_link';
						a.setAttribute("target","_blank");
					}
					else if(data[i][j+1]=='(')
					{
						var code2=data[i].substring(j,data[i].length).match(/\((\S*)\)/)[1];
						if(code2.length==0)
						{
							text.innerHTML+='(';
							break;						
						}
						j+=code2.length+2; 
						if(code2[0]=='h'&&code2[1]=='t'&&code2[2]=='t'&&code2[3]=='p') 
						{
							var a=document.createElement("a");text.appendChild(a);
							a.href=code2;
							a.innerHTML=code;
							a.className='md_link';
							a.setAttribute("target","_blank");
						}
					}
					break;	
				case '!':
					var code=data[i].substring(j,data[i].length).match(/\[(\S*)\]/);
					var url=data[i].substring(j,data[i].length).match(/\((\S*)\)/);
					if(code==null||url==null)
					{
						text.innerHTML+="!";
						break;
					}
					code=code[1];url=url[1];
					j+=code.length+url.length+4; 
					var div=document.createElement("div");text.appendChild(div);
					div.align="center";
					var img=document.createElement("img");div.appendChild(img);
					if((url[0]=='h'&&url[1]=='t'&&url[2]=='t'&&url[3]=='p')||url[0]=='.'||url[0]=='/')
						img.src=url;
					else
						img.src=jry_wb_message.jry_wb_host+'jry_wb_netdisk/index_share.php?action=open&fast=1&share_id='+url;
					img.className='md_img';
					img.setAttribute('onclick','jry_wb_beautiful_alert.openpicture("'+code+'",(document.body.clientWidth-100),(document.body.clientHeight-100),"'+img.src+'");');
					div=null;
					var div=document.createElement("div");text.appendChild(div);
					div.align="center";
					div.innerHTML=code;
					div.className="md_media_font";
					div=null;
					break;
				case '%':
					var code=data[i].substring(j,data[i].length).match(/\[(\S*)\]/);
					var url=data[i].substring(j,data[i].length).match(/\((\S*)\)/);
					if(code==null||url==null)
					{
						text.innerHTML+="%";
						break;
					}
					code=code[1];url=url[1];
					j+=code.length+url.length+4; 
					var div=document.createElement("div");text.appendChild(div);
					div.align="center";
					var img=document.createElement("video");div.appendChild(img);
					img.src=url;
					img.style.width="100%";
					img.controls="controls";
					img.innerHTML="您的浏览器不支持视频播放";
					div=null;
					var div=document.createElement("div");text.appendChild(div);
					div.align="center";
					div.innerHTML=code;
					div.className="md_media_font";
					div=null;
					break;
				case '#':
					if(j!=0)
					{
						text.innerHTML+="#"
						break;
					}
					var count=0;
					for(;data[i][j]=='#';j++)
						count++;
					this.showtitle(count,data[i].slice(count));
					var text=document.createElement("div");this.area.appendChild(text);
					text.className='md_normal';
					text_new_flag=true;
					j=nn;
					break;
				case '-':
					j++;
					if(data[i][j]=='['&&(((data[i][j+1]=='x'||data[i][j+1]=='X')&&data[i][j+2]==']')||data[i][j+1]==']'))
					{
						if(data[i][j+1]=='x'||data[i][j+1]=='X') 
						{ 
							var b=document.createElement("b");text.appendChild(b);b.classList.add('md_finish','jry_wb_icon','jry_wb_icon_duigoux');
							j+=2;
						}
						else
						{
							var b=document.createElement("b");text.appendChild(b);b.classList.add('md_unfinish','jry_wb_icon','jry_wb_icon_hr');							
							j+=1;
						}
					}
					else
						text.innerHTML+="-";
					break;
				case '_':
					if(data[i][j+1]=='_')
					{
						j++;
						if(!flat_flag)
						{
							flat_flag=true;
							flat_buf=text;
							text=document.createElement("em");flat_buf.appendChild(text);
						}
						else
						{
							flat_flag=false;
							text=flat_buf;
						}
					}
					else
						text.innerHTML+="_";
					break;
					break;
				case '*':
					if(data[i][j+1]=='*')
					{
						j++;
						if(!strong_flag)
						{
							strong_flag=true;
							strong_buf=text;
							text=document.createElement("strong");strong_buf.appendChild(text);
						}
						else
						{
							strong_flag=false;
							text=strong_buf;
						}
					}
					else
						text.innerHTML+="*";
					break;
				case '~':
					if(data[i][j+1]=='~')
					{
						j++;
						if(!delete_flag)
						{
							delete_flag=true;
							delete_buf=text;
							text=document.createElement("span");delete_buf.appendChild(text);
							text.className='line_m';
						}
						else
						{
							delete_flag=false;
							text=delete_buf;
						}				
					}
					else
					{
						var code=data[i].substring(j,data[i].length).match(/\[(\S*)\]/);
						var url=data[i].substring(j,data[i].length).match(/\((\S*)\)/);
						if(code==null||url==null)
						{
							text.innerHTML+="~";
							break;
						}
						code=code[1];url=url[1];
						j+=code.length+url.length+4; 
						var div=document.createElement("div");text.appendChild(div);
						div.align="center";
						var img=document.createElement("audio");div.appendChild(img);
						img.src=url;
						img.style.width="400px";
						img.controls="controls";
						img.innerHTML="您的浏览器不支持音频播放";
						div=null;
						var div=document.createElement("div");text.appendChild(div);
						div.align="center";
						div.innerHTML=code;
						div.className="md_media_font";
						div=null;
						break;
					}	
					break;
				case '=':
					if(data[i][j+1]=='=')
					{
						j++;
						if(data[i][j+1]=='#')
						{
							j++;j++;
							highlight_color=data[i].substring(j,j+6);
							j+=5; 
						}
						if(!highlight_flag)
						{
							highlight_flag=true;
							highlight_buf=text;
							text=document.createElement("span");highlight_buf.appendChild(text);
							text.style.backgroundColor=highlight_color;
						}
						else
						{
							highlight_flag=false;
							text=highlight_buf;						
						}
					}
					else
						text.innerHTML+="=";
					break;
				case '>':
					if(j!=0)
					{
						text.innerHTML+='&gt;';
						break;
					}
					cite_flag=true;
					cite_buf=text;
					text=document.createElement("cite");cite_buf.appendChild(text);
					text.className="md_cite";
					break;
				case '$':
					text.innerHTML+='$';
					latex_flag=true;
					break;
				case '`':
					if(data[i][j+1]=='`'&&data[i][j+2]=='`')
					{
						j++;j++;
						if(data[i].slice(j).includes('```'))//同一行有
						{
							var old=j+1;
							var language=data[i].substring(j+1,data[i].length);
							j+=language.length;
							j+=data[i].slice(j).indexOf('```');
							this.showcode(language,data[i].substring(old,j-1),text);
						}
						else//同一行没有
						{
							var language=data[i].substring(j+1,data[i].length);
							var code='';
							for(i++;i<n;i++)
							{
								var find=data[i].indexOf('```');
								if(find!=-1)//找到啦
								{
									j=find;
									if(code!='')
										code+='\n'+data[i].substring(0,j);
									else
										code+=data[i].substring(0,j);								
									this.showcode(language,code,text);
									break;
								}
								else
									if(code!='')
										code+='\n'+data[i];
									else
										code+=data[i];
							}
						}
					} 
					break;
				default:
					if(data[i][j]!=undefined)
						text.innerHTML+=data[i][j].replace(/ /g,"&nbsp;");
			}
		}
		if(cite_flag)
		{
			cite_flag=false;
			text=cite_buf;
		}
		if(!text_new_flag)
			text.innerHTML+="<br>";
		text_new_flag=false;
	}
	/*var flag=false;for(var scripts=document.getElementsByTagName("script"),length=scripts.length,i=0;i<length;i++){if(scripts[i].src=="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML")flag=true}
	if((!flag)&&latex_flag)
	{
		var myscript = document.createElement('script');document.body.appendChild(myscript);
		myscript.src = "https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML";
		myscript.type = 'text/javascript';
		myscript.defer = true;
	}*/
	for(var a=area.getElementsByTagName("video"),n=a.length,i=0;i<n;i++)new jry_wb_beautiful_video(a[i]);
	for(var a=area.getElementsByTagName("audio"),n=a.length,i=0;i<n;i++){var a=new jry_wb_beautiful_music(a[i],null,false,true);a.set_background_picture('','')};
//	for(var all=area.getElementsByTagName("script"),i=0,n=all.length;i<n;i++){if(all[i].className=="jry_blog_auto_run_script"){window.eval(all[i].innerHTML);console.log("run js")}}; 
	console.timeEnd('jry_wb_markdown');
	return {'title':md_title};
}