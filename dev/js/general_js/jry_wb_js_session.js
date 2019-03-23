var jry_wb_js_session=new function()
{
	var lastsend=0;
	this.close=false;
	var map=new Map();
	if(typeof SharedWorker=='undefined')
		this.close=true;
	else
	{
		var worker=new SharedWorker(jry_wb_message.jry_wb_host+'jry_wb_js/sharedworker.js');
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
	}
	this.send=function(to,data)
	{
		if(this.close)
			return;
		worker.port.postMessage({'to':to,'data':data,'key':(lastsend=Math.random())});
	};
	this.add_listener=function(id,func)
	{
		if(this.close)
			return;
		map.set(id,func);
	};
};