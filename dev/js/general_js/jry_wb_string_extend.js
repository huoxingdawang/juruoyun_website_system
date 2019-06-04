String.prototype.to_time=function()
{
	var dd1=new Date(this);
	if(isNaN(dd1.getTime()))
		dd1=new Date(this.replace(/\-/g, "/"));
	return dd1;
};