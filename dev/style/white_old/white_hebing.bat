attrib ..\..\push\mywork\css\white_desktop.css -r /s
attrib ..\..\push\mywork\css\white_mobile.css -r /s
attrib ..\..\push\mywork\css\white_general.css -r /s
copy white_desktop\*.css ..\..\push\mywork\css\white_desktop.css 
copy white_mobile\*.css ..\..\push\mywork\css\white_mobile.css 
copy white_general\*.css ..\..\push\mywork\css\white_general.css 
attrib ..\..\push\mywork\css\white_desktop.css +r /s
attrib ..\..\push\mywork\css\white_mobile.css +r /s
attrib ..\..\push\mywork\css\white_general.css +r /s