var jry_wb_indexeddb;
window.onindexeddbopen=function(){jry_wb_beautiful_right_alert.alert('数据库连接建立成功',500,'auto','ok');};
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
(function()
{
	var request=window.indexedDB.open('jry_wb',8);
	request.onerror=function(event){console.error('indexedDB open error');};
	request.onsuccess=function(event)
	{
		jry_wb_indexeddb=request.result;
		window.onindexeddbopen();
	};
	request.onupgradeneeded=function(event)
	{
		jry_wb_beautiful_right_alert.alert('数据库升级中',1000,'auto','warn');
		jry_wb_indexeddb=request.result;
		if(!jry_wb_indexeddb.objectStoreNames.contains('ip')) 
			jry_wb_indexeddb.createObjectStore('ip',{keyPath:'data.ip'});		
		if(!jry_wb_indexeddb.objectStoreNames.contains('user')) 
			jry_wb_indexeddb.createObjectStore('user',{keyPath:'id'});		
		if(!jry_wb_indexeddb.objectStoreNames.contains('manage_user')) 
			jry_wb_indexeddb.createObjectStore('manage_user',{keyPath:'id'});		
		if(!jry_wb_indexeddb.objectStoreNames.contains('lasttime')) 
			jry_wb_indexeddb.createObjectStore('lasttime',{keyPath:'key'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('invitecode')) 
			jry_wb_indexeddb.createObjectStore('invitecode',{keyPath:'incite_code_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('log')) 
			jry_wb_indexeddb.createObjectStore('log',{keyPath:'log_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('oj_question_list')) 
			jry_wb_indexeddb.createObjectStore('oj_question_list',{keyPath:'question_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('oj_manage_question_list')) 
			jry_wb_indexeddb.createObjectStore('oj_manage_question_list',{keyPath:'question_id'});		
		if(!jry_wb_indexeddb.objectStoreNames.contains('oj_logs')) 
			jry_wb_indexeddb.createObjectStore('oj_logs',{keyPath:'log_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('oj_classes')) 
			jry_wb_indexeddb.createObjectStore('oj_classes',{keyPath:'class_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('oj_error')) 
			jry_wb_indexeddb.createObjectStore('oj_error',{keyPath:'question_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('blog_all')) 
			jry_wb_indexeddb.createObjectStore('blog_all',{keyPath:'blog_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('blog_draft')) 
			jry_wb_indexeddb.createObjectStore('blog_draft',{keyPath:'blog_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('chat_rooms')) 
			jry_wb_indexeddb.createObjectStore('chat_rooms',{keyPath:'chat_room_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('chat_messages')) 
			jry_wb_indexeddb.createObjectStore('chat_messages',{keyPath:'chat_text_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('nd_group')) 
			jry_wb_indexeddb.createObjectStore('nd_group',{keyPath:'group_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('nd_area')) 
			jry_wb_indexeddb.createObjectStore('nd_area',{keyPath:'area_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('nd_file_list')) 
			jry_wb_indexeddb.createObjectStore('nd_file_list',{keyPath:'file_id'});	
		if(!jry_wb_indexeddb.objectStoreNames.contains('nd_share_list')) 
			jry_wb_indexeddb.createObjectStore('nd_share_list',{keyPath:'share_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('manage_user_list')) 
			jry_wb_indexeddb.createObjectStore('manage_user_list',{keyPath:'id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('manage_competence')) 
			jry_wb_indexeddb.createObjectStore('manage_competence',{keyPath:'type'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('manage_bigdeal')) 
			jry_wb_indexeddb.createObjectStore('manage_bigdeal',{keyPath:'bigdeal_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('manage_hengfu')) 
			jry_wb_indexeddb.createObjectStore('manage_hengfu',{keyPath:'hengfu_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('manage_tanmu')) 
			jry_wb_indexeddb.createObjectStore('manage_tanmu',{keyPath:'tanmu_id'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('qq_music')) 
			jry_wb_indexeddb.createObjectStore('qq_music',{keyPath:'mid'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('163_music')) 
			jry_wb_indexeddb.createObjectStore('163_music',{keyPath:'mid'});
		if(!jry_wb_indexeddb.objectStoreNames.contains('songlist')) 
			jry_wb_indexeddb.createObjectStore('songlist',{keyPath:'slid'});		
		jry_wb_beautiful_right_alert.alert('数据库升级完成',1000,'auto','ok');
		window.onindexeddbopen();
	}
}());
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
				return callback(new Date('1926-08-17 00:00:00'));
			callback(new Date(this.result.time));
		};
	});
}
function jry_wb_indexeddb_get_all(db_name,callback)
{
	jry_wb_add_on_indexeddb_open(function()
	{	
		var re=jry_wb_indexeddb.transaction([db_name],'readwrite').objectStore(db_name).openCursor();
		var data=[];
		re.onsuccess=function()
		{
			var cursor=event.target.result;
			if (cursor)
				data.push(cursor.value),cursor.continue();
			else
				callback(data);
		};
	});	
}