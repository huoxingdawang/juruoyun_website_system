function jry_wb_show_tr_no_input(table,name,value,id,width)
{
	var tr = document.createElement("tr");
	table.appendChild(tr);
	var td = document.createElement("td");
	td.width=width==null?"400":width;
	var h55 = document.createElement("h56");
	td.appendChild(h55);	
	h55.innerHTML = name;
	tr.appendChild(td);	
	td = null;
	var td = document.createElement("td");
	var h55 = document.createElement("h56");
	h55.id = id;
	td.appendChild(h55);	
	h55.innerHTML = value;
	tr.appendChild(td);
	return td;
}
function jry_wb_show_tr_with_input(table,name,id,value,type,onclick,width)
{
	var tr = document.createElement("tr");
	table.appendChild(tr);
	var td = document.createElement("td");
	td.width=width==null?"400":width;
	var h55 = document.createElement("h55");
	td.appendChild(h55);	
	h55.innerHTML = name;
	tr.appendChild(td);	
	td = null;
	var td = document.createElement("td");
	td.width="*";
	var input = document.createElement("input");
	input.name = input.id = id;
	input.className='h56';
	input.type = type;
	input.value = value;
	input.onclick = onclick;
	jry_wb_set_delate_special_one(input);
	td.appendChild(input);	
	tr.appendChild(td);	
	return td;
}