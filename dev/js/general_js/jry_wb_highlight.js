function jry_wb_highlight(area,code,start)
{
	var div=document.createElement("div");
	div.classList.add('jry_wb_highlight');
	var language='';
	function test(text,i,word)
	{
		for(var j=0;j<word.length&&(i+j)<text.length;j++)
			if(word[j]!=text[i+j])
				return false;
		return word.length;
	}	
	for(var i=start,n=code.length;i<n;i++)
	{
		if(code[i]=='`'&&code[i+1]=='`'&&code[i+2]=='`')
			return area.appendChild(div),i+2;
		if(i==start)
		{
			for(;i<n&&code[i]!='\n'&&code[i]!=' ';i++)
				language+=code[i];
			language=language.toLowerCase();
		}
		else if(code[i]=='\n')
			div.appendChild(document.createElement('br'));
		else if(code[i]=='\t')
		{
			var span=document.createElement('span');div.appendChild(span);
			span.innerHTML='&emsp;&emsp;';			
		}
		else if(code[i]==' ')
		{
			var span=document.createElement('span');div.appendChild(span);
			span.innerHTML='&ensp;';			
		}
		else
		{
			var flag=false,important=[],operator=[],str=[],comment=[],preprocessor=[];
			if(language=='c')
				important	=['int','void','const','return','long','inline','register','for','include','define','char','unsigned','if','else','true','flase','null','while','switch','case','break','continue'],
				operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
				str			=['"',"'"],
				comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
				preprocessor=[{'start':'#','end':'\n'}];
			else if(language=='c++'||language=='cpp')
				important	=['int','void','using','namespace','const','return','long','inline','register','for','include','define','char','bool','unsigned','if','else','true','flase','null','while','switch','case','break','new','delete','class','continue'],
				operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
				str			=['"',"'"],
				comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
				preprocessor=[{'start':'#','end':'\n'}];
			else if(language=='javascript'||language=='js')
				important	=['return','var','for','if','else','true','flase','null','while','switch','case','break','new','delete','class','array','continue'],
				operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
				str			=['"',"'"],
				comment		=[{'start':'/*','end':'*/'},{'start':'//','end':'\n'}],
				preprocessor=[{'start':'#','end':'\n'}];				
			else if(language=='markdown'||language=='md')
				operator	=[',','.','(',')','[',']','{','}','|','\\','<','>','?','/','!','@','#','$','%','^','&','*','-','=','+','~','`',';',':'],
				str			=['"',"'"];
			var span=document.createElement('span');div.appendChild(span);
			if(flag==false&&!((/[0-9a-zA-Z_]/i).test(code[i-1])))
				for(var j=0;j<important.length;j++)
					if(k=test(code,i,important[j]))
					{
						if((/[0-9a-zA-Z_]/i).test(code[i+k]))
							continue;
						i+=k-1;
						span.innerHTML=important[j];
						flag=true;
						span.classList.add('important');
						break;
					}
			if(flag==false)
				for(var j=0;j<str.length;j++)
					if(k=test(code,i,str[j]))
					{
						span.innerHTML+=str[j];
						i+=k;
						span.classList.add('string');
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
				for(var j=0;j<comment.length;j++)
					if(k=test(code,i,comment[j].start))
					{
						span.innerHTML+=comment[j].start;
						i+=k;
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
							div.appendChild(document.createElement('br'));
						break;
					}
			if(flag==false)
				for(var j=0;j<preprocessor.length;j++)
					if(k=test(code,i,preprocessor[j].start))
					{
						span.innerHTML+=preprocessor[j].start;
						i+=k;
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
							div.appendChild(document.createElement('br'));
						break;
					}						
			if(flag==false)
				for(var j=0;j<operator.length;j++)
					if(k=test(code,i,operator[j]))
					{
						i+=k-1;
						span.innerHTML=operator[j];
						flag=true;
						span.classList.add('operator');
						break;
					}
			if(flag==false)
				if(!isNaN(parseInt(code[i])))
				{
					span.innerHTML=code[i];
					flag=true;
					span.classList.add('number');
				}
			if(flag==false)
				span.classList.add('default'),span.innerHTML=code[i];
		}
	}
	return false;
}