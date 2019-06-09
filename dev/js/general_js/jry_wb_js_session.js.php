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
		var worker=new SharedWorker(jry_wb_message.jry_wb_host+'jry_wb_js/jry_wb_js_session_worker.js');
		worker.port.onmessage=function(data)
		{
			data=data.data;
			if(keys.indexOf(data.key)!=-1)
			{
				keys.splice(keys.indexOf(data.key),1);
				return;
			}
<?php if(JRY_WB_DEBUG_MODE){ ?>			
		console.log('JS session receive message: ',data);
<?php } ?>			
			var func=map.get(data.to);
			if(typeof func=='function')
				func(data.data);
		};
		worker.port.start();
	}
	this.send=function(to,data)
	{
		var data;
		if(this.close)
			return;
		worker.port.postMessage(data={'to':to,'data':data,'key':(keys[keys.length]=Math.random())});
<?php if(JRY_WB_DEBUG_MODE){ ?>			
		console.log('JS session send message: ',data);
<?php } ?>		
	};
	this.add_listener=function(id,func)
	{
		if(this.close)
			return;
		map.set(id,func);
	};
};
<?php if(false){ ?></script><?php } ?>
