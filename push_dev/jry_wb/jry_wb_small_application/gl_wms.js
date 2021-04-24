function gl_wms_init(area)
{
	gl_wms_run(area);
}
function gl_wms_run(area)
{
	var main=document.createElement('div');area.appendChild(main);
	var source_div=document.createElement('div');main.appendChild(source_div);
	var source_text=document.createElement('span');source_div.appendChild(source_text);source_text.innerHTML="输入位模式<br>";
	var source_value=document.createElement('textarea');source_div.appendChild(source_value);source_value.onkeyup=suan;
    source_value.style.width='200px';
    source_value.style.height='400px'; 
    source_value.style.fontSize='30px'; 
    source_value.style.color='#00F'; 
	var ans_div=document.createElement('div');main.appendChild(ans_div);
    ans_div.style.fontSize='30px'; 
    ans_div.style.color='#00F'; 
    
	function suan()
	{
        ans_div.innerHTML='';
		for(var i=0;i<source_value.value.length;++i)
        {
            if((i%6)==0)ans_div.innerHTML+='<br>';
            var c=source_value.value[i];
            if(c>='A'&&c<='F')ans_div.innerHTML+=parseInt("0x"+c).toString(2)+' ';
            if(c>='a'&&c<='f')ans_div.innerHTML+=parseInt("0x"+c).toString(2)+' ';
            if(c>='8'&&c<='9')ans_div.innerHTML+=parseInt("0x"+c).toString(2)+' ';
            if(c>='4'&&c<='7')ans_div.innerHTML+='0'+parseInt("0x"+c).toString(2)+' ';
            if(c>='2'&&c<='3')ans_div.innerHTML+='00'+parseInt("0x"+c).toString(2)+' ';
            if(c>='0'&&c<='1')ans_div.innerHTML+='000'+parseInt("0x"+c).toString(2)+' ';
        }
	}
	suan();
}