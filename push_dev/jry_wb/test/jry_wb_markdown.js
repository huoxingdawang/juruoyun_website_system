function jry_wb_highlight(code,start,stop)
{
	
}
function jry_wb_markdown(area,id,time,text,notitle)
{
	if(notitle==null)
		notitle=false;
	this.area=area;
	this.fresh=function(time,text)
	{
		if(typeof jry_wb_ajax_get_text=="function")
			this.text=jry_wb_ajax_get_text(text);
		else
			this.text=(text);
		var buf=document.createElement('div');
		var title_flag=false;
		var title_flag_enable=true;
		for(var i=0,n=this.text.length;i<n;i++)
		{
			console.log(this.text[i]);
			if(this.text[i]=='-'&&this.text[i+1]=='-'&&this.text[i+2]=='-')
			{
				if(title_flag)
					title_flag=false;
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
				if(this.text[i]=='t'&&this.text[i+1]=='i'&&this.text[i+2]=='t'&&this.text[i+3]=='l'&&this.text[i+4]=='e'&&this.text[i+5]==':')
				{
					i+=6;
					var title=document.createElement("div");buf.appendChild(title);
					for(;i<n&&this.text[i]!='\n'&&this.text[i]!='-';i++)
						title.innerHTML+=this.text[i];
					continue;
				}
			}
			buf.innerHTML+=this.text[i];
		}
		if(buf.innerHTML!=area.innerHTML)
			area.innerHTML=buf.innerHTML;
	};
	this.fresh(time,text);
}