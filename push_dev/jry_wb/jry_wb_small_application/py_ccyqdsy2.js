function py_ccyqdsy2_init(area)
{
	py_ccyqdsy2_run(area);
}
function py_ccyqdsy2_run(area)
{
	var py_ccyqdsy2_main=document.createElement('div');area.appendChild(py_ccyqdsy2_main);
	var clxwqjd_div=document.createElement('div');py_ccyqdsy2_main.appendChild(clxwqjd_div);
	var clxwqjd_text=document.createElement('span');clxwqjd_div.appendChild(clxwqjd_text);clxwqjd_text.innerHTML="测量显微器的精度";
	var clxwqjd_value=document.createElement('input');clxwqjd_div.appendChild(clxwqjd_value);clxwqjd_value.onkeyup=suan;clxwqjd_value.value=0.01;

	var table=document.createElement('table');py_ccyqdsy2_main.appendChild(table);
	
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='';
	for(var i=1;i<=10;i++)
	{
		var td=document.createElement('td');tr.appendChild(td);td.innerHTML=i;
	}
	var x=[];
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='xi';
	for(var i=0;i<10;i++)
	{
		var td=document.createElement('td');tr.appendChild(td);
		x[i]=document.createElement('input');td.appendChild(x[i]);x[i].onkeyup=suan;
		x[i].style.width="100px";
		x[i].value=0;
	}
	var dx=[];
	var tr=document.createElement('tr');table.appendChild(tr);
	var td=document.createElement('td');tr.appendChild(td);td.innerHTML='Δx';
	for(var i=0;i<5;i++)
		tr.appendChild(dx[i]=document.createElement('td'));

	var dbx_div=document.createElement('div');py_ccyqdsy2_main.appendChild(dbx_div);
	var dbx_text=document.createElement('span');dbx_div.appendChild(dbx_text);dbx_text.innerHTML="Δx=";
	var dbx_value=document.createElement('span');dbx_div.appendChild(dbx_value);

	var ua_div=document.createElement('div');py_ccyqdsy2_main.appendChild(ua_div);
	var ua_text=document.createElement('span');ua_div.appendChild(ua_text);ua_text.innerHTML="Ua=";
	var ua_value=document.createElement('span');ua_div.appendChild(ua_value);

	var ub_div=document.createElement('div');py_ccyqdsy2_main.appendChild(ub_div);
	var ub_text=document.createElement('span');ub_div.appendChild(ub_text);ub_text.innerHTML="Ub=";
	var ub_value=document.createElement('span');ub_div.appendChild(ub_value);

	var uh_div=document.createElement('div');py_ccyqdsy2_main.appendChild(uh_div);
	var uh_text=document.createElement('span');uh_div.appendChild(uh_text);uh_text.innerHTML="U合=";
	var uh_value=document.createElement('span');uh_div.appendChild(uh_value);

	var delta_div=document.createElement('div');py_ccyqdsy2_main.appendChild(delta_div);
	var delta_text=document.createElement('span');delta_div.appendChild(delta_text);delta_text.innerHTML="δ=";
	var delta_value=document.createElement('span');delta_div.appendChild(delta_value);

	var E_div=document.createElement('div');py_ccyqdsy2_main.appendChild(E_div);
	var E_text=document.createElement('span');E_div.appendChild(E_text);E_text.innerHTML="E=";
	var E_value=document.createElement('span');E_div.appendChild(E_value);


	function suan()
	{
		var dxs=0;
		for(var i=0;i<5;++i)
		{
			dx[i].innerHTML=(parseFloat(x[i+5].value)-parseFloat(x[i].value)).toFixed(4);
			dxs+=(parseFloat(x[i+5].value)-parseFloat(x[i].value));
		}
		dbx_value.innerHTML=(dxs/=5).toFixed(4);
		var ua=0;
		for(var i=0;i<5;++i)
			ua+=Math.pow((parseFloat(x[i+5].value)-parseFloat(x[i].value))-dxs,2);
		ua/=4;
		ua=Math.sqrt(ua);
		ua_value.innerHTML=ua.toFixed(4);
		var ub=(parseFloat(clxwqjd_value.value)/2);
		ub_value.innerHTML=ub.toFixed(4);
		var uh=Math.sqrt(ua*ua+ub*ub);
		uh_value.innerHTML=uh.toFixed(4);
	
		delta_value.innerHTML=(dxs/5).toFixed(4)+"±"+(uh/5).toFixed(4);
		E_value.innerHTML=(uh/dxs*100).toFixed(4)+'%';
	}
	suan();
}