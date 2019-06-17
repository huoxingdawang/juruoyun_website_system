function jry_wb_trie()
{
	this.tree=[];
	var newnode=(t,w,i,d)=>
	{
		if(i==w.length)
			return;
		if(t[w[i]]==undefined)
			t[w[i]]={},t[w[i]].c=[],t[w[i]].p=false;
		t[w[i]].p|=(w.length==(i+1));
		if(w.length==(i+1))
			t[w[i]].d=d;
		newnode(t[w[i]].c,w,i+1,d);
	};
	var serch=(t,s,i)=>
	{
		var b=t[s[i]];
		if(b==undefined)
			return null;
		if(s.length==i)
			return null;
		var a=serch(b.c,s,i+1);
		return a==null?(b.p?b:null):a;
	};
	this.add=(word,d)=>
	{
		if(word==undefined||word=='')
			return;
		if(d==undefined)
			d=word;
		newnode(this.tree,word,0,d);
	};
	this.serch=(string,start)=>
	{
		if(start==undefined)
			start=0;
		var data=serch(this.tree,string,start);
		return data==null?null:data.d;
	};
}