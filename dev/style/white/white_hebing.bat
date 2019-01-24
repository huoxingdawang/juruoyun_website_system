attrib ..\..\..\push_dev\jry_wb\jry_wb_css\white\desktop.css -r /s
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\white\mobile.css -r /s
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\white\general.css -r /s
copy white_desktop\*.css ..\..\..\push_dev\jry_wb\jry_wb_css\white\desktop.css 
copy white_mobile\*.css ..\..\..\push_dev\jry_wb\jry_wb_css\white\mobile.css 
copy white_general\*.css ..\..\..\push_dev\jry_wb\jry_wb_css\white\general.css 
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\white\desktop.css +r /s
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\white\mobile.css +r /s
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\white\general.css +r /s