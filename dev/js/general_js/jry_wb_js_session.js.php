<?php if(false){ ?><script><?php } ?>
var jry_wb_js_session=new function()
{
	var keys=[];
	this.close=false;
	var map=new Map();
	if(jry_wb_message.jry_wb_host=='')
		this.close=true;
	else if(typeof SharedWorker=='undefined')
		this.close=true;
	else
	{
		var worker=new SharedWorker(jry_wb_message.jry_wb_host+'jry_wb_js/sharedworker.js');
		worker.port.onmessage=function(data)
		{
			data=data.data;
<?php if(constant('jry_wb_debug_mode')){ ?>			
		console.log('JS session receive message: ',data);
<?php } ?>			
			if(keys.indexOf(data.key)!=-1)
			{
				keys.splice(keys.indexOf(data.key),1);
				return;
			}
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
		worker.port.postMessage({'to':to,'data':data,'key':(keys[keys.length]=Math.random())});
	};
	this.add_listener=function(id,func)
	{
		if(this.close)
			return;
		map.set(id,func);
	};
};
<?php if(false){ ?></script><?php } ?>
