function color_init(color_picker_area)
{
	color_run(color_picker_area);
}
function color_run(color_picker_area)
{
	var color_picker_main=document.createElement('div');color_picker_area.appendChild(color_picker_main);
	color_picker_main.classList.add("cp-default");
	color_picker_value=document.createElement('input');color_picker_area.appendChild(color_picker_value);
	color_picker_value.style.fontSize="30px";
	var color_picker_button=document.createElement('button');color_picker_area.appendChild(color_picker_button);
	color_picker_button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
	color_picker_button.onclick=color_picker_value.onkeyup=function()
	{
		color_picker_area.style.backgroundColor="#"+color_picker_value.value;
	}
	color_picker_button.innerHTML='生成';
	ColorPicker(
		color_picker_main,
		function(hex, hsv, rgb) 
		{
			/*console.log(hsv.h, hsv.s, hsv.v);
			console.log(rgb.r, rgb.g, rgb.b);*/
			color_picker_area.style.backgroundColor = hex;
			color_picker_value.value = hex;
		}
	);	
}