var jry_wb_js_session=new function()
{
	var lastsend=0;
	var worker=new SharedWorker(jry_wb_message.jry_wb_host+'jry_wb_js/sharedworker.js');
	var map=new Map();
	worker.port.onmessage=function(data)
	{
		data=data.data;
		if(lastsend==data.key)
			return;
		var func=map.get(data.to);
		if(typeof func=='function')
			func(data.data);
	};
	worker.port.start();
	this.send=function(to,data)
	{
		worker.port.postMessage({'to':to,'data':data,'key':(lastsend=Math.random())});
	};
	this.add_listener=function(id,func)
	{
		map.set(id,func);
	};
};