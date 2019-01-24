function jry_wb_nd_get_class_by_type(type)
{
	switch(type)
	{
		/*多媒体*/
		case 'gif':
		case 'png':
		case 'jpg':
		case 'jpeg':
		case 'ico':
			return ['icon-filepicture','jry_wb_netdisk_file_type_pic'];/*图片*/
		case 'mp3':
		case 'wav':
			return ['icon-yinpinwenjian','jry_wb_netdisk_file_type_music'];/*音频*/
		case 'mp4':
		case 'flv':
			return ['icon-filevideo','jry_wb_netdisk_file_type_video'];;/*视频*/
		/*常用文件*/
		case 'plain':
		case 'txt':
			return ['icon-wenjian1','jry_wb_netdisk_file_type_txt'];/*文件*/
		case 'pdf':
			return ['icon-pdf','jry_wb_netdisk_file_type_pdf'];/*PDF*/
		case 'ppt':
		case 'pptx':
			return ['icon-ppt','jry_wb_netdisk_file_type_ppt'];/*PPT*/
		case 'xls':
		case 'xlsx':
			return ['icon-excel','jry_wb_netdisk_file_type_xls'];/*excel*/
		case 'doc':
		case 'docx':
			return ['icon-docx','jry_wb_netdisk_file_type_doc'];/*WORD*/
		case 'c'	:
		case 'cpp'	:		
		case 'h'	:		
		case 'html'	:		
		case 'php'	:		
		case 'css'	:	
		case 'js'	:
		case 'bat'	:		
			return ['icon-daimawenjian-','jry_wb_netdisk_file_type_code'];/*CODE*/
		case 'rar':
		case 'zip':
			return ['icon-yasuowenjian','jry_wb_netdisk_file_type_zip'];/*压缩文件*/
		case 'exe':
		case 'dll':
			return ['icon-chengxu','jry_wb_netdisk_file_type_program'];/*运行程序*/
		case 'dir':
			return ['icon-wenjianjia','jry_wb_netdisk_file_type_dir'];/*文件夹*/
		default:
			return ['icon-file-unknown','jry_wb_netdisk_file_type_unknow'];/*???*/
	}
}