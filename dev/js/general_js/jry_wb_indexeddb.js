var jry_wb_indexeddb;
window.indexedDB=window.indexedDB||window.mozIndexedDB||window.webkitIndexedDB||window.msIndexedDB;
window.IDBTransaction=window.IDBTransaction||window.webkitIDBTransaction||window.msIDBTransaction||{READ_WRITE:"readwrite"};
window.IDBKeyRange=window.IDBKeyRange||window.webkitIDBKeyRange||window.msIDBKeyRange;
window.onindexeddbopen=function(){jry_wb_beautiful_right_alert.alert('思维联络成功!',500,'auto','ok');};
function jry_wb_add_on_indexeddb_open(func)
{
	if(jry_wb_indexeddb!=undefined)
		return func();
	var old_onindexeddbopen=window.onindexeddbopen;
	window.onindexeddbopen=function()
	{
		old_onindexeddbopen();
		func();
	};
}
var jry_wb_indexeddb_restart_cnt=0;
function jry_wb_indexeddb_init()
{
	var request=window.indexedDB.open('jry_wb',12);
	var timer=null;
	var update_flag=false;
	request.onerror=function(event)
	{
		jry_wb_beautiful_right_alert.alert('思维联络失败!',1000,'auto','error');
		jry_wb_indexeddb_restart_cnt++;
		if(jry_wb_indexeddb_restart_cnt<10)
			setTimeout(jry_wb_indexeddb_init,1000);
		else
			jry_wb_beautiful_alert.alert('思维混乱了!','尝试刷新一下康康？');			
	};
	request.onsuccess=function(event)
	{
		jry_wb_indexeddb=request.result;
		if(update_flag)
			jry_wb_beautiful_right_alert.alert('思维升级完成!',1000,'auto','ok');			
		window.onindexeddbopen();
	};
	var creat_list=[{'name':'user'				,'key':'id'}			,{'name':'manage_user'				,'key':'id'}			,{'name':'lasttime'			,'key':'key'}			,{'name':'invitecode'		,'key':'incite_code_id'}	,{'name':'log'				,'key':'log_id'},
					{'name':'oj_question_list'	,'key':'question_id'}	,{'name':'oj_manage_question_list'	,'key':'question_id'}	,{'name':'oj_logs'			,'key':'log_id'}		,{'name':'oj_classes'		,'key':'class_id'}			,{'name':'oj_error'			,'key':'error_id'},
					{'name':'blog_list'			,'key':'blog_id'}		,{'name':'blog_draft_list'			,'key':'blog_id'}		,{'name':'chat_rooms'		,'key':'chat_room_id'}	,{'name':'chat_messages'	,'key':'chat_text_id'}		,{'name':'nd_group'			,'key':'group_id'},
					{'name':'nd_area'			,'key':'area_id'}		,{'name':'nd_file_list'				,'key':'file_id'}		,{'name':'nd_share_list'	,'key':'share_id'}		,{'name':'manage_user_list'	,'key':'id'}				,{'name':'manage_competence','key':'type'},
					{'name':'manage_bigdeal'	,'key':'bigdeal_id'}	,{'name':'manage_hengfu'			,'key':'hengfu_id'}		,{'name':'manage_tanmu'		,'key':'tanmu_id'}		,{'name':'qq_music'			,'key':'mid'}				,{'name':'163_music'		,'key':'mid'},
					{'name':'songlist'			,'key':'slid'}			,{'name':'blog_text'				,'key':'blog_id'}		,{'name':'blog_draft_text'	,'key':'blog_id'}		,{'name':'ip'				,'key':'data.ip'}];
	request.onupgradeneeded=function(event)
	{
		update_flag=true;
		jry_wb_beautiful_right_alert.alert('思维升级中......',1000,'auto','warn');
		jry_wb_indexeddb=request.result;
		for(var i=0,n=creat_list.length;i<n;i++)
			if(!jry_wb_indexeddb.objectStoreNames.contains(creat_list[i].name)) 
				jry_wb_indexeddb.createObjectStore(creat_list[i].name,{keyPath:creat_list[i].key});
	};
	request.onclose=function(){jry_wb_beautiful_right_alert.alert('数据库连接已关闭',1000,'auto','ok');};
};
function jry_wb_indexeddb_reinit()
{
	jry_wb_beautiful_right_alert.alert('正在关闭数据库.....',1000,'auto','warn');
	jry_wb_indexeddb.close();
	jry_wb_beautiful_right_alert.alert('数据库连接已关闭',1000,'auto','ok');
	jry_wb_beautiful_right_alert.alert('正在删除数据库.....',1000,'auto','warn');
	var req=indexedDB.deleteDatabase('jry_wb');
	req.onsuccess=function(){jry_wb_beautiful_right_alert.alert('数据库已删除',1000,'auto','ok');jry_wb_indexeddb_init();};
	req.onerror=function(){jry_wb_beautiful_right_alert.alert('数据库删除失败',1000,'auto','error');};
	req.onblocked=function(){jry_wb_beautiful_right_alert.alert('数据库删除被阻塞',1000,'auto','error');};
}
function jry_wb_indexeddb_clear()
{
	var clear_list=[{'name':'manage_user'				,'key':'id'}			,{'name':'lasttime'			,'key':'key'}			,{'name':'invitecode'		,'key':'incite_code_id'}	,{'name':'log'			,'key':'log_id'},
					{'name':'oj_manage_question_list'	,'key':'question_id'}	,{'name':'oj_error'			,'key':'error_id'},
					{'name':'blog_draft_list'			,'key':'blog_id'}		,{'name':'chat_rooms'		,'key':'chat_room_id'}	,{'name':'chat_messages'	,'key':'chat_text_id'},
					{'name':'nd_area'					,'key':'area_id'}		,{'name':'nd_file_list'		,'key':'file_id'}		,{'name':'nd_share_list'	,'key':'share_id'}		,{'name':'manage_user_list'	,'key':'id'}				,{'name':'manage_competence','key':'type'},
					{'name':'manage_bigdeal'			,'key':'bigdeal_id'}	,{'name':'manage_hengfu'	,'key':'hengfu_id'}		,{'name':'manage_tanmu'		,'key':'tanmu_id'},
					{'name':'blog_draft_text'			,'key':'blog_id'}];
	for(let i=0;i<clear_list.length;i++)
		jry_wb_indexeddb_delete(clear_list[i]);
}
function jry_wb_indexeddb_delete(table)
{
	jry_wb_loading_on();
	jry_wb_indexeddb_set_lasttime(table.name,'1926-08-17 00:00:00');	
	jry_wb_beautiful_right_alert.alert('正在清空表'+table.name,500+Math.random()*500,'auto');
	let re=jry_wb_indexeddb.transaction([table.name],'readwrite').objectStore(table.name);
	re.openCursor().onsuccess=function()
	{
		var cursor=this.result;
		if (cursor)
			re.delete(cursor.value[table.key]),cursor.continue();
		else
			jry_wb_beautiful_right_alert.alert('清空表'+table.name+'成功',1000+Math.random()*1000,'auto','ok'),jry_wb_loading_off();
	};	
}
jry_wb_indexeddb_init();
function jry_wb_indexeddb_set_lasttime(key,time)
{
	jry_wb_add_on_indexeddb_open(function(){jry_wb_indexeddb.transaction(['lasttime'],'readwrite').objectStore('lasttime').put({'key':key,'time':time});});
}
function jry_wb_indexeddb_get_lasttime(key,callback)
{
	jry_wb_add_on_indexeddb_open(function()
	{
		var re=jry_wb_indexeddb.transaction(['lasttime'],'readwrite').objectStore('lasttime').get(key);
		re.onsuccess=function()
		{
			if(this.result==undefined)
				return callback('1926-08-17 00:00:00'.to_time());
			callback(this.result.time.to_time());
		};
	});
}
function jry_wb_indexeddb_get_all(db_name,callback)
{
	jry_wb_loading_on();
	jry_wb_add_on_indexeddb_open(function()
	{	
		var re=jry_wb_indexeddb.transaction([db_name],'readwrite').objectStore(db_name).openCursor();
		var data=[];
		re.onsuccess=function()
		{
			var cursor=this.result;
			if (cursor)
				data.push(cursor.value),cursor.continue();
			else
				jry_wb_loading_off(),callback(data);
		};
	});	
}