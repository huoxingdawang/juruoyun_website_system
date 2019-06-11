Array.prototype.unique=function()
{
	var res=[],json={};
	for(var i=0;i<this.length;i++)
	{
		if(!json[this[i]])
		{
			res.push(this[i]);
			json[this[i]]=1;
		}
	}
	return res;
};
Array.prototype.max=function(a,ex)
{
	if(a=='')
		return undefined;
	if(this.length==0)
		return undefined;
	if(ex=='date')
		var max=this[0][a].to_time();
	else
		var max=this[0][a];
	for(var i=1;i<this.length;i++)
		if(ex=='date')
			max=Math.max(max,this[i][a].to_time());
		else
			max=Math.max(max,this[i][a]);
	return max;
};
