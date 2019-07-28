<?php if(false){ ?><script><?php } ?>
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
	language=language.toLowerCase();
	var important=[],operator=[],str=[],comment=[],preprocessor=[],constant=[];
	if(language=='c')
		important	=['asm','auto','break','case','char','const','continue','default','define','do','double','else','enum','extern','float','for','goto','if','inline','int','long','register','return','short','signed','sizeof','static','struct','switch','true','typedef','union','unsigned','void','volatile','while'],
		constant	=['NULL'],
		operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
		str			=['"',"'"],
		comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
		preprocessor=[{'start':'#','end':'\n'}];
	else if(language=='c++'||language=='cpp')
		important	=['asm','auto','bool','break','case','catch','char','class','const','const_cast','continue','default','define','delete','do','double','dynamic_cast','else','enum','explicit','export','extern','false','float','for','friend','goto','if','inline','int','long','mutable','namespace','new','operator','private','protected','public','register','reinterpret_cast','return','short','signed','sizeof','static','static_cast','struct','switch','template','this','throw','try','typedef','typeid','typename','union','unsigned','using','virtual','void','volatile','wchar_t','while'],
		constant	=['NULL','false','true'],
		operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
		str			=['"',"'"],
		comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
		preprocessor=[{'start':'#','end':'\n'}];
	else if(language=='javascript'||language=='js')
		important	=['abstract','arguments','boolean','break','byte','case','catch','char','class','const','continue','debugger','default','delete','do','double','else','enum','eval','export','extends','false','final','finally','float','for','function','goto','if','implements','import','in','instanceof','int','interface','let','long','native','new','null','package','private','protected','public','return','short','static','super','switch','synchronized','this','throw','throws','transient','true','try','typeof','var','void','volatile','while','with','yield'],
		operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
		str			=['"',"'"],
		constant	=['Array','Date','Infinity','Math','NaN','Number','Object','String','eval','false','function','isFinite','isNaN','length','name','null','prototype','true','undefined'],
		comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
		preprocessor=[{'start':'#','end':'\n'}];				
	else if(language=='markdown'||language=='md')
		operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
		str			=['"',"'"];
	var important_length=important.length,constant_length=constant.length,str_length=str.length,operator_length=operator.length,comment_length=comment.length,preprocessor_length=preprocessor.length;
	var lastword_dom=null;
	for(;i<n;i++)
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
			code_dom.appendChild(document.createElement('br'));
		else if(code[i]=='\t')
		{
			var span=document.createElement('span');code_dom.appendChild(span);
			span.innerHTML='&emsp;&emsp;';			
		}
		else if(code[i]==' ')
		{
			var span=document.createElement('span');code_dom.appendChild(span);
			span.innerHTML='&ensp;';			
		}
		else
		{
			var flag=false;
			if(flag==false&&!((/[0-9a-zA-Z_]/i).test(code[i-1])))
				for(var j=0;j<important_length;j++)
					if(k=test(code,i,important[j]))
					{
						if((/[0-9a-zA-Z_]/i).test(code[i+k]))
							continue;
						i+=k-1;
						flag=true;
						var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
						span.innerHTML=important[j];
						span.classList.add('important');
						break;
					}
			if(flag==false&&!((/[0-9a-zA-Z_]/).test(code[i-1])))
				for(var j=0;j<constant_length;j++)
					if(k=test(code,i,constant[j]))
					{
						if((/[0-9a-zA-Z_]/i).test(code[i+k]))
							continue;
						i+=k-1;
						flag=true;
						var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
						span.innerHTML=constant[j];
						span.classList.add('constant');
						break;
					}					
			if(flag==false)
				for(var j=0;j<str_length;j++)
					if(k=test(code,i,str[j]))
					{
						i+=k;
						var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
						span.classList.add('string');
						span.innerHTML+=str[j];
						for(;i<n&&(!(k=test(code,i,str[j])));i++)
							if(code[i]=='\n')
								span.appendChild(document.createElement('br'));
							else if(code[i]==' ')
								span.innerHTML+='&nbsp;';
							else
								span.innerHTML+=code[i];
						span.innerHTML+=str[j];
						flag=true;
						i+=k-1;
						break;
					}
			if(flag==false)
				for(var j=0;j<comment_length;j++)
					if(k=test(code,i,comment[j].start))
					{
						i+=k;
						var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
						span.innerHTML+=comment[j].start;
						span.classList.add('comment');
						for(;i<n&&(!(k=test(code,i,comment[j].end)));i++)
							if(code[i]=='\n')
								span.appendChild(document.createElement('br'));
							else if(code[i]==' ')
								span.innerHTML+='&nbsp;';
							else
								span.innerHTML+=code[i];
						span.innerHTML+=comment[j].end;
						flag=true;
						i+=k-1;
						if(comment[j].end[comment[j].end.length-1]=='\n')
							code_dom.appendChild(document.createElement('br'));
						break;
					}
			if(flag==false)
				for(var j=0;j<preprocessor_length;j++)
					if(k=test(code,i,preprocessor[j].start))
					{
						i+=k;
						var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
						span.innerHTML+=preprocessor[j].start;
						span.classList.add('preprocessor');
						for(;i<n&&(!(k=test(code,i,preprocessor[j].end)));i++)
						{
							if(code[i]=='\n')
								span.appendChild(document.createElement('br'));
							else if(code[i]==' ')
								span.innerHTML+='&nbsp;';
							else
								span.innerHTML+=code[i];
						}
						span.innerHTML+=preprocessor[j].end;
						flag=true;
						i+=k-1;
						if(preprocessor[j].end[preprocessor[j].end.length-1]=='\n')
							code_dom.appendChild(document.createElement('br'));
						break;
					}						
			if(flag==false)
				for(var j=0;j<operator_length;j++)
					if(k=test(code,i,operator[j]))
					{
						i+=k-1;
						flag=true;
						var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
						span.innerHTML=operator[j];
						span.classList.add('operator');
						break;
					}
			if(flag==false)
				if(!((/[0-9a-zA-Z_]/i).test(code[i-1]))&&!isNaN(parseInt(code[i])))
				{
					flag=true;
					var span=document.createElement('span');code_dom.appendChild(span);lastword_dom=null;
					span.innerHTML=code[i];
					span.classList.add('number');
				}
			if(flag==false)
			{
				if(lastword_dom==null)
					lastword_dom=document.createElement('span');code_dom.appendChild(lastword_dom);
				lastword_dom.classList.add('default'),lastword_dom.innerHTML=code[i];
			}
		}
	}	
<?php if(JRY_WB_DEBUG_MODE){ ?>console.timeEnd('jry_wb_highlight');<?php } ?>	
	return false;
}
<?php if(false){ ?></script><?php } ?>