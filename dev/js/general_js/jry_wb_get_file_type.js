function jry_wb_get_file_type(address)
{
	var buf=address.split('.');
	return buf[buf.length-1].toLowerCase();
}