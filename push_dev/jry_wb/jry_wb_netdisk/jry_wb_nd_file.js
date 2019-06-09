if(typeof jry_wb_netdisk_do_file=='undefined')
	jry_wb_netdisk_do_file=jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_do_file.php';
function jry_wb_nd_get_class(file)
{
	file.type=file.type.toLowerCase();
	if(file.isdir)
		return ['jry_wb_icon_wenjianjia','jry_wb_netdisk_file_type_dir'];/*文件夹*/		
	switch(file.type)
	{
		/*多媒体*/
		case 'gif':
		case 'png':
		case 'jpg':
		case 'jpeg':
		case 'ico':
			return ['jry_wb_icon_filepicture','jry_wb_netdisk_file_type_pic'];/*图片*/
		case 'mp3':
		case 'wav':
			return ['jry_wb_icon_yinpinwenjian','jry_wb_netdisk_file_type_music'];/*音频*/
		case 'mp4':
		case 'flv':
			return ['jry_wb_icon_filevideo','jry_wb_netdisk_file_type_video'];;/*视频*/
		/*常用文件*/
		case 'plain':
		case 'txt':
			return ['jry_wb_icon_wenjian1','jry_wb_netdisk_file_type_txt'];/*文件*/
		case 'pdf':
			return ['jry_wb_icon_pdf','jry_wb_netdisk_file_type_pdf'];/*PDF*/
		case 'ppt':
		case 'pptx':
			return ['jry_wb_icon_ppt','jry_wb_netdisk_file_type_ppt'];/*PPT*/
		case 'xls':
		case 'xlsx':
			return ['jry_wb_icon_excel','jry_wb_netdisk_file_type_xls'];/*excel*/
		case 'doc':
		case 'docx':
			return ['jry_wb_icon_docx','jry_wb_netdisk_file_type_doc'];/*WORD*/
		case 'c'	:
		case 'cpp'	:		
		case 'h'	:		
		case 'html'	:		
		case 'php'	:		
		case 'css'	:	
		case 'js'	:
		case 'bat'	:		
		case 'm'	:		
			return ['jry_wb_icon_daimawenjian-','jry_wb_netdisk_file_type_code'];/*CODE*/
		case 'rar':
		case 'zip':
			return ['jry_wb_icon_yasuowenjian','jry_wb_netdisk_file_type_zip'];/*压缩文件*/
		case 'exe':
		case 'dll':
			return ['jry_wb_icon_chengxu','jry_wb_netdisk_file_type_program'];/*运行程序*/
		default:
			return ['jry_wb_icon_file-unknown','jry_wb_netdisk_file_type_unknow'];/*???*/
	}
}