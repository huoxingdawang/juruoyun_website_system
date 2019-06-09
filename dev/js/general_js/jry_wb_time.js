function jry_wb_get_time() 
{
	var date=new Date();
	var seperator1="-";
	var seperator2=":";
	var month=date.getMonth() + 1;
	var strdate=date.getDate();
	if (month>=1&&month<=9)
		month="0" + month;
	if(strdate>=0&&strdate<=9)
		strdate="0" + strdate;
	var currentdate=date.getFullYear()+seperator1+month+seperator1+strdate+" "+date.getHours() +seperator2+date.getMinutes()+seperator2+date.getSeconds();
	return currentdate;
}
function jry_wb_get_server_time()
{
    return (new Date((new Date())-jry_wb_time_different));
}
function jry_wb_get_day(second,all)
{
	if(isNaN(second))
		return '';
	if(all==undefined)
		all=true;
	var date='',showed=false;
	second=Math.abs(second);
	if(Math.floor(second/60/60/24/30/12)!=0)
		date+=Math.floor(second/60/60/24/30/12)+'年',second-=(Math.floor(second/60/60/24/30/12)*60*60*24*30*12),showed=true;
	if(Math.floor(second/60/60/24/30)!=0||(all&&showed))
		date+=Math.floor(second/60/60/24/30)+'月',second-=(Math.floor(second/60/60/24/30)*60*60*24*30),showed=true;
	if(Math.floor(second/60/60/24)!=0||(all&&showed))
		date+=Math.floor(second/60/60/24)+'日',second-=(Math.floor(second/60/60/24)*60*60*24),showed=true;
	if(Math.floor(second/60/60)!=0||(all&&showed))
		date+=Math.floor(second/60/60)+'时',second-=(Math.floor(second/60/60)*60*60),showed=true;
	if(Math.floor(second/60)!=0||(all&&showed))
		date+=Math.floor(second/60)+'分',second-=(Math.floor(second/60)*60),showed=true;
	if(Math.floor(second)!=0||(all&&showed))
		date+=Math.floor(second)+'秒';
	return date;
}
function jry_wb_show_time(intime,addre)
{
	if(typeof addre=='string')
		addre=document.getElementById(addre);
	if(typeof intime.to_time=='function')
		intime=intime.to_time();
	setinterval(function()
	{
		var date='',buf,jie;
		var a=jry_wb_get_server_time();
		buf=intime.getSeconds()-a.getSeconds();
		if(buf<0)
			buf=60+buf,jie=1;
		else
			jie=0;
		date=buf+'秒'+date;
		buf=intime.getMinutes()-a.getMinutes()-jie;
		if(buf<0)
			buf=60+buf,jie=1;
		else
			jie=0;
		date=buf+'分'+date;
		buf=intime.getHours()-a.getHours()-jie;
		if(buf<0)
			buf=24+buf,jie=1;
		else
			jie=0;
		date=buf+'时'+date;
		date=Math.floor((intime.getTime()-a.getTime()-jie)/(1000*60*60*24))+'天'+date;
		addre.innerHTML=date;
	},1000);
}
function jry_wb_compare_time(d1,d2)
{
	if(typeof d1.to_time=='function')
		d1=d1.to_time();
	if(typeof d2.to_time=='function')
		d2=d2.to_time();	
	return d1-d2;
}
function jry_wb_compare_time(d1,d2)
{
	if(d1==undefined)
		d1=new Date('1926-08-17 00:00:00');
	if(d2==undefined)
		d2=new Date('1926-08-17:00:00:00');
	if(typeof d1.to_time=='function')
		d1=d1.to_time();
	if(typeof d2.to_time=='function')
		d2=d2.to_time();
	return d1-d2;
}
