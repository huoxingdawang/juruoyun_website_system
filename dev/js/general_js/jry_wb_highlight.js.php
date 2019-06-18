<?php if(false){ ?><script><?php } ?>
var jry_wb_highlight_buf=[];
function jry_wb_highlight(area,code,start)
{
<?php if(JRY_WB_DEBUG_MODE){ ?>console.time('jry_wb_highlight');<?php } ?>	
	var dom=document.createElement("div");
	dom.classList.add('jry_wb_highlight');
	dom.style.maxHeight='390px';
	dom.style.position='relative';
	var tools_bar=document.createElement("div");dom.appendChild(tools_bar);
	tools_bar.classList.add('jry_wb_highlight_tools');
	var copy=document.createElement("span");tools_bar.appendChild(copy);
	copy.classList.add('jry_wb_icon_fuzhi','jry_wb_icon');
	var down=document.createElement("span");tools_bar.appendChild(down);
	down.classList.add('jry_wb_icon_xuanzeqizhankai','jry_wb_icon');
	var down_flag=false;
	down.onclick=function()
	{
		if(down_flag==false)
		{
			down.classList.add('jry_wb_icon_xuanzeqishouqi');
			down.classList.remove('jry_wb_icon_xuanzeqizhankai');
			dom.style.maxHeight='';
			down_flag=true;
		}
		else
		{
			down.classList.add('jry_wb_icon_xuanzeqizhankai');
			down.classList.remove('jry_wb_icon_xuanzeqishouqi');
			dom.style.maxHeight='390px';	
			down_flag=false;
		}
	};
	var code_dom=document.createElement("div");
	code_dom.classList.add('jry_wb_highlight_code');
	jry_wb_add_onresize(()=>
	{
		tools_bar.style.width=code_dom.clientWidth;
	});
	var language='';
	function test(text,i,word)
	{
		for(var j=0;j<word.length&&(i+j)<text.length;j++)
			if(word[j]!=text[i+j])
				return false;
		return word.length;
	}
	for(var i=start,n=code.length;i<n&&code[i]!='\n'&&code[i]!=' ';i++)
		language+=code[i];
	i++;
	language=language.toLowerCase();
	if(language=='c++')
		language='cpp';
	var important=[],operator=[],str=[],comment=[],preprocessor=[],constant=[];
	if(jry_wb_highlight_buf[language]==undefined)
	{
		if(language=='c')
			important	=['asm','auto','break','case','char','const','continue','default','define','do','double','else','enum','extern','float','for','goto','if','inline','int','long','register','return','short','signed','sizeof','static','struct','switch','true','typedef','union','unsigned','void','volatile','while'],
			constant	=['NULL'],
			operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','$','%','^','&','*','-','=','+','~',';',':'],
			comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
			preprocessor=[{'start':'#','end':'\n'}],
			str			=['"',"'"];
		else if(language=='cpp')
			important	=['asm','auto','bool','break','case','catch','char','class','const','const_cast','continue','default','define','delete','do','double','dynamic_cast','else','enum','explicit','export','extern','false','float','for','friend','goto','if','inline','int','long','mutable','namespace','new','operator','private','protected','public','register','reinterpret_cast','return','short','signed','sizeof','static','static_cast','struct','switch','template','this','throw','try','typedef','typeid','typename','union','unsigned','using','virtual','void','volatile','wchar_t','while'],
			constant	=['NULL','false','true'],
			operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','$','%','^','&','*','-','=','+','~',';',':'],
			comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
			preprocessor=[{'start':'#','end':'\n'}],
			str			=['"',"'"];
		else if(language=='javascript'||language=='js')
			important	=['abstract','arguments','boolean','break','byte','case','catch','char','class','const','continue','debugger','default','delete','do','double','else','enum','eval','export','extends','false','final','finally','float','for','function','goto','if','implements','import','in','instanceof','int','interface','let','long','native','new','null','package','private','protected','public','return','short','static','super','switch','synchronized','this','throw','throws','transient','true','try','typeof','var','void','volatile','while','with','yield'],
			constant	=['Array','Date','Infinity','Math','NaN','Number','Object','String','eval','false','function','isFinite','isNaN','length','name','null','prototype','true','undefined'],
			operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','$','%','^','&','*','-','=','+','~',';',':'],
			comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
			preprocessor=[{'start':'#','end':'\n'}],				
			str			=['"',"'"];
		else if(language=='markdown'||language=='md')
			operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~',';',':'],
			str			=['"',"'"];
		else if(language=='bash')
			important	=['sudo','apt-get','apt','install','ps','grep','cd','ls','netplan','apply','chmod'],
			constant	=['openssh-server','apache2','php7.2'];
		jry_wb_highlight_buf[language]=new jry_wb_trie();
		for(var j=0,n=important.length;j<n;j++)
			jry_wb_highlight_buf[language].add(important[j]				,{'word':important[j]		,'type':'important'});
		for(var j=0,n=constant.length;j<n;j++)
			jry_wb_highlight_buf[language].add(constant[j]				,{'word':constant[j]		,'type':'constant'});
		for(var j=0,n=operator.length;j<n;j++)
			jry_wb_highlight_buf[language].add(operator[j]				,{'word':operator[j]		,'type':'operator'});
		for(var j=0,n=comment.length;j<n;j++)
			jry_wb_highlight_buf[language].add(comment[j].start			,{'data':comment[j]			,'type':'comment'});
		for(var j=0,n=preprocessor.length;j<n;j++)
			jry_wb_highlight_buf[language].add(preprocessor[j].start	,{'data':preprocessor[j]	,'type':'preprocessor'});	
		for(var j=0,n=str.length;j<n;j++)
			jry_wb_highlight_buf[language].add(str[j]					,{'word':str[j]				,'type':'str'});
	}
	var trie=jry_wb_highlight_buf[language];
	var lastword_dom=null;
	var nwt=(/[0-9a-zA-Z_]/i);
	var wt=(/[a-zA-Z_]/i);
	for(n=code.length;i<n;i++)
	{
		if(code[i]=='`'&&code[i+1]=='`'&&code[i+2]=='`')
		{
			<?php if(JRY_WB_DEBUG_MODE){ ?>console.timeEnd('jry_wb_highlight');<?php } ?>
			dom.appendChild(code_dom);
			area.appendChild(dom);
			new jry_wb_beautiful_scroll(dom,undefined,undefined,true);
			copy.onclick=function()
			{
				jry_wb_copy_to_clipboard(code.slice(start+language.length+1,i));
				jry_wb_beautiful_right_alert.alert('复制成功',2000,'auto','ok')
			};			
			return i+2;
		}
		if(code[i]=='\n')
			lastword_dom=null,code_dom.appendChild(document.createElement('br'));
		else if(code[i]=='\t')
		{
			lastword_dom=null;
			var span=document.createElement('span');code_dom.appendChild(span);
			span.innerHTML='&emsp;&emsp;';			
		}
		else if(code[i]==' ')
		{
			lastword_dom=null;
			var span=document.createElement('span');code_dom.appendChild(span);
			span.innerHTML='&ensp;';			
		}
		else
		{
			var flag=false;
			var result=trie.serch(code,i);
			if(result!=null)
			{
				if(result.word!=undefined)
					var wl=result.word.length;
				else
					var wl=result.data.start.length;
				if(	((result.type=='important'||result.type=='constant')	&&(!nwt.test(code[i-1]))&&(!nwt.test(code[i+wl])))||
					((result.type=='operator')								&&true)	)
				{
					i+=wl-1;
					var span=document.createElement('span');code_dom.appendChild(span);
					span.innerHTML=result.word;
					span.classList.add(result.type);
					flag=true;
					lastword_dom=null;
				}
				else if(result.type=='preprocessor')
				{
					i+=wl;
					var span=document.createElement('span');code_dom.appendChild(span);
					span.innerHTML+=result.data.start;
					span.classList.add('preprocessor');
					for(;i<n&&(!(k=test(code,i,result.data.end)));i++)
					{
						if(code[i]=='\n')
							span.appendChild(document.createElement('br'));
						else if(code[i]==' ')
							span.innerHTML+='&nbsp;';
						else
							span.innerHTML+=code[i];
					}
					span.innerHTML+=result.data.end;
					flag=true;
					lastword_dom=null;
					i+=k-1;
					if(result.data.end[result.data.end.length-1]=='\n')
						code_dom.appendChild(document.createElement('br'));
					
				}
				else if(result.type=='comment')
				{
					i+=wl;
					var span=document.createElement('span');code_dom.appendChild(span);
					span.innerHTML+=result.data.start;
					span.classList.add('comment');
					for(;i<n&&(!(k=test(code,i,result.data.end)));i++)
						if(code[i]=='\n')
							span.appendChild(document.createElement('br'));
						else if(code[i]==' ')
							span.innerHTML+='&nbsp;';
						else
							span.innerHTML+=code[i];
					span.innerHTML+=result.data.end;
					flag=true;
					lastword_dom=null;
					i+=k-1;
					if(result.data.end[result.data.end.length-1]=='\n')
						code_dom.appendChild(document.createElement('br'));
					
				}
				else if(result.type=='str'&&code[i-1]!='\\')
				{
					i+=wl;
					var span=document.createElement('span');code_dom.appendChild(span);
					span.classList.add('string');
					span.innerHTML+=result.word;
					for(;i<n&&((!(k=test(code,i,result.word)))||(k!=false&&code[i-1]=='\\'))&&!(code[i]=='`'&&code[i+1]=='`'&&code[i+2]=='`');i++)
						if(code[i]=='\n')
							span.appendChild(document.createElement('br'));
						else if(code[i]==' ')
							span.innerHTML+='&nbsp;';
						else
							span.innerHTML+=code[i];
					flag=true;
					if(code[i]=='`'&&code[i]=='`'&&code[i]=='`')
						i-=1;
					else
					{
						span.innerHTML+=result.word;
						lastword_dom=null;
						i+=k-1;
					}
				}
				else
				{
					i+=wl-1;
					if(lastword_dom==null)
						lastword_dom=document.createElement('span'),code_dom.appendChild(lastword_dom);		
					lastword_dom.classList.add('default');
					lastword_dom.innerHTML+=result.word;
					flag=true;
				}
			}
			if(flag==false)
				if(!(wt.test(code[i-1]))&&!isNaN(parseInt(code[i])))
				{
					flag=true;
					var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
					span.innerHTML=code[i];
					span.classList.add('number');
					lastword_dom=null;
				}
			if(flag==false)
			{
				if(lastword_dom==null)
					lastword_dom=document.createElement('span'),code_dom.appendChild(lastword_dom);
				lastword_dom.classList.add('default');
				lastword_dom.innerHTML+=code[i];
			}
		}
	}	
<?php if(JRY_WB_DEBUG_MODE){ ?>console.timeEnd('jry_wb_highlight');<?php } ?>	
	return false;
}
<?php if(false){ ?></script><?php } ?>