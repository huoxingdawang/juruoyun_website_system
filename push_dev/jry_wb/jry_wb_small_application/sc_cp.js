function sc_cp_init(area)
{
	sc_cp_run(area);
}
function sc_cp_run(area)
{
	var main=document.createElement('tr');area.appendChild(main);

	var u0_div  =document.createElement('tr')   ;main  .appendChild(u0_div);
	var u0_text =document.createElement('td')   ;u0_div.appendChild(u0_text);u0_text.innerHTML="起始电压U0(V)";
	var u0_td   =document.createElement('td')   ;u0_div.appendChild(u0_td);
	var u0_value=document.createElement('input');u0_td .appendChild(u0_value);u0_value.onkeyup=suan;u0_value.value=0;

	var u1_div  =document.createElement('tr')   ;main  .appendChild(u1_div);
	var u1_text =document.createElement('td')   ;u1_div.appendChild(u1_text);u1_text.innerHTML="终止电压U1(V)";
	var u1_td   =document.createElement('td')   ;u1_div.appendChild(u1_td);
	var u1_value=document.createElement('input');u1_td .appendChild(u1_value);u1_value.onkeyup=suan;u1_value.value=11.5;
    
	var c0_div  =document.createElement('tr')   ;main  .appendChild(c0_div);
	var c0_text =document.createElement('td')   ;c0_div.appendChild(c0_text);c0_text.innerHTML="电容电量C(F)";
	var c0_td   =document.createElement('td')   ;c0_div.appendChild(c0_td);
	var c0_value=document.createElement('input');c0_td .appendChild(c0_value);c0_value.onkeyup=suan;c0_value.value=4.4;
    
	var t0_div  =document.createElement('tr')   ;main  .appendChild(t0_div);
	var t0_text =document.createElement('td')   ;t0_div.appendChild(t0_text);t0_text.innerHTML="充电时间t(S)";
	var t0_td   =document.createElement('td')   ;t0_div.appendChild(t0_td);
	var t0_value=document.createElement('input');t0_td .appendChild(t0_value);t0_value.onkeyup=suan;t0_value.value=8;
        
	var e0_div  =document.createElement('tr')   ;main  .appendChild(e0_div);
	var e0_text =document.createElement('td')   ;e0_div.appendChild(e0_text);e0_text.innerHTML="能量(J)";
	var e0_value=document.createElement('td')   ;e0_div.appendChild(e0_value);
    
	var p0_div  =document.createElement('tr')   ;main  .appendChild(p0_div);
	var p0_text =document.createElement('td')   ;p0_div.appendChild(p0_text);p0_text.innerHTML="平均功率(W)";
	var p0_value=document.createElement('td')   ;p0_div.appendChild(p0_value);
    
    function suan()
    {
        var u0=parseFloat(u0_value.value);
        var u1=parseFloat(u1_value.value);
        var c0=parseFloat(c0_value.value);
        var t0=parseFloat(t0_value.value);
        var e0=1/2*c0*Math.abs(u1*u1-u0*u0);
        var p0=e0/t0;
		e0_value.innerHTML=e0.toFixed(4);
		p0_value.innerHTML=p0.toFixed(4);
    }
    suan();
}