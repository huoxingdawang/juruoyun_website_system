var pool=[];
onconnect=function(e)
{
	pool.push(e.ports[0]);
	e.ports[0].onmessage=function(e)
	{
		for(var i=0;i<pool.length;i++)
			pool[i].postMessage(e.data);
	};
};