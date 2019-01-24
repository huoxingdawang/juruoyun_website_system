function jry_wb_copy_to_clipboard(s)
{
	if(window.clipboardData)
		window.clipboardData.setData('text',s);
	else
	{
		(function(s){
			document.oncopy = function(e)
			{
				e.clipboardData.setData('text',s);
				e.preventDefault();	
				document.oncopy = null;
			}
		})(s);
		document.execCommand('Copy');
	}
}
