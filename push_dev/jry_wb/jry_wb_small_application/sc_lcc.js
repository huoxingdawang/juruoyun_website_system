function sc_lcc_init(area)
{
	sc_lcc_run(area);
}
function sc_lcc_run(area)
{
	var main=document.createElement('tr');area.appendChild(main);

	var i0_div  =document.createElement('tr')   ;main  .appendChild(i0_div);
	var i0_text =document.createElement('td')   ;i0_div.appendChild(i0_text);i0_text.innerHTML="输出电流I0(A)";
	var i0_td   =document.createElement('td')   ;i0_div.appendChild(i0_td);
	var i0_value=document.createElement('input');i0_td .appendChild(i0_value);i0_value.onkeyup=suan;i0_value.value=5;

	var u0_div  =document.createElement('tr')   ;main  .appendChild(u0_div);
	var u0_text =document.createElement('td')   ;u0_div.appendChild(u0_text);u0_text.innerHTML="输入电压U0(V)";
	var u0_td   =document.createElement('td')   ;u0_div.appendChild(u0_td);
	var u0_value=document.createElement('input');u0_td .appendChild(u0_value);u0_value.onkeyup=suan;u0_value.value=10;
    
	var l0_div  =document.createElement('tr')   ;main  .appendChild(l0_div);
	var l0_text =document.createElement('td')   ;l0_div.appendChild(l0_text);l0_text.innerHTML="接收线圈电感L0(μH)";
	var l0_td   =document.createElement('td')   ;l0_div.appendChild(l0_td);
	var l0_value=document.createElement('input');l0_td .appendChild(l0_value);l0_value.onkeyup=suan;l0_value.value=13.79;
    
	var f0_div  =document.createElement('tr')   ;main  .appendChild(f0_div);
	var f0_text =document.createElement('td')   ;f0_div.appendChild(f0_text);f0_text.innerHTML="工作频率f0(KHZ)";
	var f0_td   =document.createElement('td')   ;f0_div.appendChild(f0_td);
	var f0_value=document.createElement('input');f0_td .appendChild(f0_value);f0_value.onkeyup=suan;f0_value.value=150;
        
	var x0_div  =document.createElement('tr')   ;main  .appendChild(x0_div);
	var x0_text =document.createElement('td')   ;x0_div.appendChild(x0_text);x0_text.innerHTML="LCC基本电抗X0(Ω)";
	var x0_value=document.createElement('td')   ;x0_div.appendChild(x0_value);
    
	var ls_div  =document.createElement('tr')   ;main  .appendChild(ls_div);
	var ls_text =document.createElement('td')   ;ls_div.appendChild(ls_text);ls_text.innerHTML="输出电感Ls(μH)";
	var ls_value=document.createElement('td')   ;ls_div.appendChild(ls_value);

	var cp_div  =document.createElement('tr')   ;main  .appendChild(cp_div);
	var cp_text =document.createElement('td')   ;cp_div.appendChild(cp_text);cp_text.innerHTML="并联电容Cp(nF)";
	var cp_value=document.createElement('td')   ;cp_div.appendChild(cp_value);    
    
	var cs_div  =document.createElement('tr')   ;main  .appendChild(cs_div);
	var cs_text =document.createElement('td')   ;cs_div.appendChild(cs_text);cs_text.innerHTML="串联电容Cs(nF)";
	var cs_value=document.createElement('td')   ;cs_div.appendChild(cs_value);   
    
    function suan()
    {
        var i0=parseFloat(i0_value.value);
        var u0=parseFloat(u0_value.value);
        var l0=parseFloat(l0_value.value)*(1e-6);
        var f0=parseFloat(f0_value.value)*(1e3);
        var x0=u0/i0;
        var ls=x0/(2*Math.PI*f0);
        var cp=1/(2*Math.PI*f0*x0);
        var cs=1/(Math.pow(2*Math.PI*f0,2)*(l0-ls));
		x0_value.innerHTML=x0.toFixed(4);
		ls_value.innerHTML=(ls*(1e6)).toFixed(4);
		cp_value.innerHTML=(cp*(1e9)).toFixed(4);
		cs_value.innerHTML=(cs*(1e9)).toFixed(4);
        
    }
    suan();
}