function jry_wb_get_size(size)
{
	if(size<1)
		return (size*1024).toFixed(2)+'B';
	if(size<1024)
		return parseFloat(size).toFixed(2)+'KB';
	else if(size<1024*1024)
		return (size/1024).toFixed(2)+'MB';
	else if(size<1024*1024*1024)
		return (size/1024/1024).toFixed(2)+'GB';
	else if(size<1024*1024*1024*1024)
		return (size/1024/1024/1024).toFixed(2)+'TB';
	else
		return (size/1024/1024/1024/1024).toFixed(2)+'PB';
}