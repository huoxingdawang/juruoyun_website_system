function py_ccyqdsy_init(area)
{
	py_ccyqdsy_run(area);
}
function py_ccyqdsy_run(area)
{
	var py_ccyqdsy_main=document.createElement('div');area.appendChild(py_ccyqdsy_main);
	var ybkcjd_div=document.createElement('div');py_ccyqdsy_main.appendChild(ybkcjd_div);
	var ybkcjd_text=document.createElement('span');ybkcjd_div.appendChild(ybkcjd_text);ybkcjd_text.innerHTML="游标卡尺的精度";
	var ybkcjd_value=document.createElement('input');ybkcjd_div.appendChild(ybkcjd_value);ybkcjd_value.onkeyup=suan;ybkcjd_value.value=0.02;

	var ybkcld_div=document.createElement('div');py_ccyqdsy_main.appendChild(ybkcld_div);
	var ybkcld_text=document.createElement('span');ybkcld_div.appendChild(ybkcld_text);ybkcld_text.innerHTML="游标卡尺的零点";
	var ybkcld_value=document.createElement('input');ybkcld_div.appendChild(ybkcld_value);ybkcld_value.onkeyup=suan;ybkcld_value.value=0;
	
	var qfcjd_div=document.createElement('div');py_ccyqdsy_main.appendChild(qfcjd_div);
	var qfcjd_text=document.createElement('span');qfcjd_div.appendChild(qfcjd_text);qfcjd_text.innerHTML="千分尺的精度";
	var qfcjd_value=document.createElement('input');qfcjd_div.appendChild(qfcjd_value);qfcjd_value.onkeyup=suan;qfcjd_value.value=0.01;

	var qfcld_div=document.createElement('div');py_ccyqdsy_main.appendChild(qfcld_div);
	var qfcld_text=document.createElement('span');qfcld_div.appendChild(qfcld_text);qfcld_text.innerHTML="千分尺的零点";
	var qfcld_value=document.createElement('input');qfcld_div.appendChild(qfcld_value);qfcld_value.onkeyup=suan;qfcld_value.value=0;
	
	var table=document.createElement('table');py_ccyqdsy_main.appendChild(table);
	
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='';
	for(var i=1;i<=10;i++)
	{
		var td=document.createElement('td');tr.appendChild(td);td.innerHTML=i;
	}
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML="平均值";
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML="修正值";
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML="Ua";
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML="Ub";
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML="U合";
	


	var d=[];
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='d';
	for(var i=0;i<10;i++)
	{
		var td=document.createElement('td');tr.appendChild(td);
		d[i]=document.createElement('input');td.appendChild(d[i]);d[i].onkeyup=suan;
		d[i].style.width="100px";
		d[i].value=0;
	}
	var d_average=document.createElement('td');tr.appendChild(d_average);
	var d_xiu=document.createElement('td');tr.appendChild(d_xiu);
	var d_ua=document.createElement('td');tr.appendChild(d_ua);
	var d_ub=document.createElement('td');tr.appendChild(d_ub);
	var d_uh=document.createElement('td');tr.appendChild(d_uh);
	
	var r1=[];
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='2r1';
	for(var i=0;i<10;i++)
	{
		var td=document.createElement('td');tr.appendChild(td);
		r1[i]=document.createElement('input');td.appendChild(r1[i]);r1[i].onkeyup=suan;
		r1[i].style.width="100px";
		r1[i].value=0;
	}
	var r1_average=document.createElement('td');tr.appendChild(r1_average);
	var r1_xiu=document.createElement('td');tr.appendChild(r1_xiu);
	var r1_ua=document.createElement('td');tr.appendChild(r1_ua);
	var r1_ub=document.createElement('td');tr.appendChild(r1_ub);
	var r1_uh=document.createElement('td');tr.appendChild(r1_uh);
	
	var r2=[];
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='2r2';
	for(var i=0;i<10;i++)
	{
		var td=document.createElement('td');tr.appendChild(td);
		r2[i]=document.createElement('input');td.appendChild(r2[i]);r2[i].onkeyup=suan;
		r2[i].style.width="100px";
		r2[i].value=0;
	}	
	var r2_average=document.createElement('td');tr.appendChild(r2_average);
	var r2_xiu=document.createElement('td');tr.appendChild(r2_xiu);
	var r2_ua=document.createElement('td');tr.appendChild(r2_ua);
	var r2_ub=document.createElement('td');tr.appendChild(r2_ub);
	var r2_uh=document.createElement('td');tr.appendChild(r2_uh);
	
	var V_div=document.createElement('div');py_ccyqdsy_main.appendChild(V_div);
	var V_text=document.createElement('span');V_div.appendChild(V_text);V_text.innerHTML="V=";
	var V_value=document.createElement('span');V_div.appendChild(V_value);
	var E_div=document.createElement('div');py_ccyqdsy_main.appendChild(E_div);
	var E_text=document.createElement('span');E_div.appendChild(E_text);E_text.innerHTML="E=";
	var E_value=document.createElement('span');E_div.appendChild(E_value);
	
	
	
	function suan()
	{
		var tmpd=0,tmpr1=0,tmpr2=0;
		for(var i=0;i<10;++i)tmpd+=parseFloat(d[i].value);
		for(var i=0;i<10;++i)tmpr1+=parseFloat(r1[i].value);
		for(var i=0;i<10;++i)tmpr2+=parseFloat(r2[i].value);
		tmpd/=10,tmpr1/=10,tmpr2/=10;
		d_average.innerHTML=tmpd.toFixed(4);
		r1_average.innerHTML=tmpr1.toFixed(4);
		r2_average.innerHTML=tmpr2.toFixed(4);
		
		var xiud=0,xiur1=0,xiur2=0;
		d_xiu.innerHTML=(xiud=tmpd+parseFloat(qfcld_value.value)).toFixed(4);
		r1_xiu.innerHTML=(xiur1=tmpr1+parseFloat(ybkcld_value.value)).toFixed(4);
		r2_xiu.innerHTML=(xiur2=tmpr2+parseFloat(ybkcld_value.value)).toFixed(4);
		
		var tmp2d=0,tmp2r1=0,tmp2r2=0;
		for(var i=0;i<10;++i)tmp2d+=Math.pow(parseFloat(d[i].value)-tmpd,2);
		for(var i=0;i<10;++i)tmp2r1+=Math.pow(parseFloat(r1[i].value)-tmpr1,2);
		for(var i=0;i<10;++i)tmp2r2+=Math.pow(parseFloat(r2[i].value)-tmpr2,2);
		tmp2d=Math.sqrt(tmp2d/9);
		tmp2r1=Math.sqrt(tmp2r1/9);
		tmp2r2=Math.sqrt(tmp2r2/9);
		d_ua.innerHTML=tmp2d.toFixed(4);
		r1_ua.innerHTML=tmp2r1.toFixed(4);
		r2_ua.innerHTML=tmp2r2.toFixed(4);
		
		d_ub.innerHTML=(parseFloat(qfcjd_value.value)/2).toFixed(4);
		r1_ub.innerHTML=(parseFloat(ybkcjd_value.value)/2).toFixed(4);
		r2_ub.innerHTML=(parseFloat(ybkcjd_value.value)/2).toFixed(4);
		
		d_uh.innerHTML=Math.sqrt(Math.pow(tmp2d,2)+Math.pow(parseFloat(d_ub.innerHTML),2)).toFixed(4);		
		r1_uh.innerHTML=Math.sqrt(Math.pow(tmp2r1,2)+Math.pow(parseFloat(r1_ub.innerHTML),2)).toFixed(4);		
		r2_uh.innerHTML=Math.sqrt(Math.pow(tmp2r2,2)+Math.pow(parseFloat(r2_ub.innerHTML),2)).toFixed(4);
		

		var E=Math.sqrt(Math.pow(parseFloat(d_uh.innerHTML),2)/xiud+Math.pow(2*xiur2*parseFloat(r2_uh.innerHTML)/(Math.pow(xiur2,2)-Math.pow(xiur1,2)),2)
											 +Math.pow(2*xiur1*parseFloat(r1_uh.innerHTML)/(Math.pow(xiur1,2)-Math.pow(xiur2,2)),2));

		var V=(Math.PI*xiud*(Math.pow(xiur2,2)-Math.pow(xiur1,2)))/4;
		U=V*E;
		V_value.innerHTML=V.toFixed(4)+'±'+U.toFixed(4);
		E_value.innerHTML=(E*100).toFixed(2)+'%';
	}
	suan();
}