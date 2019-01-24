function jry_wb_launch_full_screen (element)
{
	if (element.requestFullscreencreen)
		element.requestFullScreen();
	else if (element.mozRequestFullScreen)
		element.mozRequestFullScreen();
	else if (element.webkitRequestFullScreen)
		element.webkitRequestFullScreen();
	else if (element.msRequestFullScreen)
		element.msRequestFullScreen();
}
function jry_wb_exit_full_screen()
{
	if (document.exitFullscreen)
		document.exitFullscreen();
	else if (document.mozCancelFullScreen)
		document.mozCancelFullScreen();
	else if (document.webkitExitFullscreen)
		document.webkitExitFullscreen();
}