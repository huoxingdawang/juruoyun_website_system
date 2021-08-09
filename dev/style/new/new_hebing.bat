attrib ..\..\..\push_dev\jry_wb\jry_wb_css\default\desktop.css-r /s
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\default\mobile.css-r /s
copy /b default_general\*.css+default_desktop\*.css ..\..\..\push_dev\jry_wb\jry_wb_css\default\desktop.css
copy /b default_general\*.css+default_mobile\*.css ..\..\..\push_dev\jry_wb\jry_wb_css\default\mobile.css
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\default\desktop.css+r /s
attrib ..\..\..\push_dev\jry_wb\jry_wb_css\default\mobile.css+r /s