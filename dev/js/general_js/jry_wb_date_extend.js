Date.prototype.s=function()
{
	return this.getFullYear()+'-'+(this.getMonth()+1)+'-'+this.getDate()+' '+this.getHours()+':'+this.getMinutes()+':'+this.getSeconds();
};
Date.prototype.to_time=function()
{
	return this;
};