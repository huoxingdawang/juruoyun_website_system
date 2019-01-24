function jry_wb_color_to_string(color)
{
	var rgb=color.split(',');
	var r=parseInt(rgb[0].split('(')[1]);
	var g=parseInt(rgb[1]);
	var b=parseInt(rgb[2].split(')')[0]);
	var hex=""+((1<<24)+(r<<16)+(g<<8)+b).toString(16).slice(1);
	return hex;
}