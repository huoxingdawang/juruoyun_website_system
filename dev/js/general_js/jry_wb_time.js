function jry_wb_get_time() 
{
    var date  =  new Date();
    var seperator1  =  "-";
    var seperator2  =  ":";
    var month  =  date.getMonth() + 1;
    var strDate  =  date.getDate();
    if (month >= 1 && month <= 9) {
        month  =  "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate  =  "0" + strDate;
    }
    var currentdate  =  date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
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
	 var date=jry_wb_math_time(intime),_date=date.split("-"),day=_date[0],hour=_date[1],minute=_date[2], s=_date[3];
	 document.getElementById(addre).innerHTML=day+"天"+hour+"时"+minute+"分"+s+"秒";
	 timerid=setTimeout(function(){jry_wb_show_time(intime,addre)},1000);
	 timerRunning=true;
}
function jry_wb_compare_time(d1,d2)
{
	var dd1=new Date(d1);
	if(isNaN(dd1.getTime()))
		dd1=new Date(d1.replace(/\-/g, "/"));
	var dd2=new Date(d2);
	if(isNaN(dd2.getTime()))
		dd2=new Date(d2.replace(/\-/g, "/"));		
	return dd1-dd2;
}
