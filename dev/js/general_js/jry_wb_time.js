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
function jry_wb_math_time(intime) 
{
	var date1=jry_wb_get_server_time();
	var date2= new Date(intime.replace(/\-/g, "/"));
	var ms=(date2.getTime() - date1.getTime());
	var day=parseInt(ms/(24*60*60*1000));
	var hour=parseInt(ms/(60*60*1000))-day*24;
	var minute=parseInt(ms/(60*1000))-hour*60-day*24*60;
	var s = parseInt(ms/(1000))-minute*60-hour*60*60-day*24*60*60;
	return ""+day+"-"+hour+"-"+minute+"-"+s;
}
function jry_wb_show_time(intime,addre)
{
	timerid=setInterval(function()
	{
		var date=jry_wb_math_time(intime),_date=date.split("-"),day=_date[0],hour=_date[1],minute=_date[2], s=_date[3];
		document.getElementById(addre).innerHTML=day+"天"+hour+"时"+minute+"分"+s+"秒";
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
