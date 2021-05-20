function ls_dijkstra_init(area)
{
	ls_dijkstra_run(area);
}
function ls_dijkstra_run(area)
{
	var ls_dijkstra_main=document.createElement('div');area.appendChild(ls_dijkstra_main);
    var road_div=document.createElement('div');ls_dijkstra_main.appendChild(road_div);
	var road_text=document.createElement('span');road_div.appendChild(road_text);road_text.innerHTML="边<br>";
	var road_value=document.createElement('textarea');road_div.appendChild(road_value);road_value.style.width="500px";road_value.style.height="300px";road_value.onkeyup=suan;road_value.value="a b 4\na c 2\nb c 1\nb d 5\nc d 8\nc e 10\ne d 2\ne z 3\nd z 6";
    
    var start_div=document.createElement('div');ls_dijkstra_main.appendChild(start_div);
	var start_text=document.createElement('span');start_div.appendChild(start_text);start_text.innerHTML="起点";
	var start_value=document.createElement('input');start_div.appendChild(start_value);start_value.onkeyup=suan;start_value.value='a';
    
    var end_div=document.createElement('div');ls_dijkstra_main.appendChild(end_div);
	var end_text=document.createElement('span');end_div.appendChild(end_text);end_text.innerHTML="终点";
	var end_value=document.createElement('input');end_div.appendChild(end_value);end_value.onkeyup=suan;end_value.value='z';
    
    var button_div=document.createElement('div');ls_dijkstra_main.appendChild(button_div);
    var prev=document.createElement('button');button_div.appendChild(prev);prev.innerHTML="上一步";
    var next=document.createElement('button');button_div.appendChild(next);next.innerHTML="下一步";
    
    var canvas=document.createElement('canvas');ls_dijkstra_main.appendChild(canvas);
    canvas.height=500; 
    canvas.width=1000; 


    var process=[];
    var draw_cnt=0;
	function suan()
	{
        process=[];
        draw_cnt=0;
        var start=start_value.value;
        var end=end_value.value;
        
        var origin=road_value.value.split('\n');
        var L={};
        //输入合法性检查
        for(var i=0;i<origin.length;++i)
        {
            var tmp=origin[i].split(' ');
            origin[i]=[];
            for(var j=0;j<tmp.length;++j)
                if(tmp[j].length!=0)
                    origin[i][origin[i].length]=tmp[j];
            if(origin[i].length!=3)
            {
                jry_wb_beautiful_right_alert.alert("第"+(i+1)+"行"+(origin[i].length<3?"缺少数据":"有额外的数据"),1000,"auto","warn");
                return;
            }
            origin[i][2]=parseFloat(origin[i][2]);
            if(origin[i][2]<=0)
            {
                jry_wb_beautiful_right_alert.alert("第"+(i+1)+"行负权边",1000,"auto","error");
                return;
            }
            L[origin[i][0]]=0X3F3F3F3F;
            L[origin[i][1]]=0X3F3F3F3F;
        }
        if(typeof L[start]=="undefined")
        {
            jry_wb_beautiful_right_alert.alert("起点不存在",1000,"auto","error");            
            return;
        }
        if(typeof L[end]=="undefined")
        {
            jry_wb_beautiful_right_alert.alert("起点不存在",1000,"auto","error");            
            return;
        }
        L[start]=0;
        var names=Object.keys(L);
        //转矩阵格式
        var w={};
        var r={};
        for(var i=0;i<names.length;++i)
            w[names[i]]={},r[names[i]]=[];
        for(var i=0;i<origin.length;++i)
            w[origin[i][0]][origin[i][1]]=w[origin[i][1]][origin[i][0]]=origin[i][2];
        var S=[];
        while(S.findIndex(function(a){return a==end})==-1)
        {
            var now="";
            for(var i=0,minn=0X3F3F3F3F+1;i<names.length;++i)
                if(S.findIndex(function(a){return a==names[i]})==-1)
                    if(minn>L[names[i]])
                        minn=L[now=names[i]];
            S[S.length]=now;
            for(let i=0;i<names.length;++i)
                if(typeof w[now][names[i]]!="undefined"&&S.findIndex(function(a){return a==names[i]})==-1)
                {
                    process[process.length]={
                        "names":JSON.parse(JSON.stringify(names)),
                        "r":JSON.parse(JSON.stringify(r)),
                        "w":JSON.parse(JSON.stringify(w)),
                        "L":JSON.parse(JSON.stringify(L)),
                        "S":JSON.parse(JSON.stringify(S)),
                        "action":"check",
                        "old_v":L[names[i]],
                        "now_v":L[now]+"+"+w[now][names[i]]+"="+(L[now]+w[now][names[i]]),
                        "now":JSON.parse(JSON.stringify(now)),
                        "to":JSON.parse(JSON.stringify(names[i])),
                    };
                    if(L[now]+w[now][names[i]]<L[names[i]])
                    {
                        var old_v=L[names[i]];
                        L[names[i]]=L[now]+w[now][names[i]];
                        r[names[i]]=JSON.parse(JSON.stringify(r[now]));
                        r[names[i]][r[names[i]].length]=now;
                        process[process.length]={
                            "names":JSON.parse(JSON.stringify(names)),
                            "r":JSON.parse(JSON.stringify(r)),
                            "w":JSON.parse(JSON.stringify(w)),
                            "L":JSON.parse(JSON.stringify(L)),
                            "S":JSON.parse(JSON.stringify(S)),
                            "action":"update",
                            "old_v":old_v,
                            "now_v":L[names[i]],
                            "now":JSON.parse(JSON.stringify(now)),
                            "to":JSON.parse(JSON.stringify(names[i])),
                        };
                    }
                }
        }
        r[end][r[end].length]=end;
        process[process.length]={
            "names":JSON.parse(JSON.stringify(names)),
            "r":JSON.parse(JSON.stringify(r)),
            "w":JSON.parse(JSON.stringify(w)),
            "L":JSON.parse(JSON.stringify(L)),
            "S":JSON.parse(JSON.stringify(S)),
            "action":"finish",
            "from":start,
            "to":end,
        };
        draw(process[0]);
        console.log(process);
	}
    function draw()
    {
        var state=process[draw_cnt];
        var h=500;
        var w=500;
        var ctx=canvas.getContext('2d');
        
        ctx.beginPath();
        ctx.fillStyle='#fff';
        ctx.fillRect(0,0,canvas.width,canvas.height);

        ctx.font="20px Arial";
        ctx.fillStyle="#000000";
        ctx.fillText("第"+(draw_cnt+1)+"步,共"+process.length+"步.S="+state.S.toString(),500,30);
        if(state.old_v==0X3F3F3F3F)
            state.old_v="+∞";
        for(var i=0,n=state.names.length;i<n;++i)
            if(state.L[state.names[i]]==0X3F3F3F3F)
                state.L[state.names[i]]="+∞";
        if(state.action=="finish")
        {
            ctx.fillText("算完了,从"+state.from+"到"+state.to+"的最短路长度是"+state.L[state.to],500,60);
            ctx.fillText("经过"+state.r[state.to].toString(),500,90);
        }
        else if(state.action=="check")
            ctx.fillText("检查新边"+state.now+"=>"+state.to+",旧值:"+state.old_v+",新值:"+state.now_v,500,60);
        else if(state.action=="update")
            ctx.fillText("新值较小，更新权值，点"+state.to+"从"+state.old_v+"更新为"+state.now_v,500,60);
        
        var point={};
        for(var i=0,n=state.names.length;i<n;++i)
        {
            var x=Math.cos(Math.PI*2*i/n)*(w-100)/2+w/2;
            var y=Math.sin(Math.PI*2*i/n)*(h-100)/2+h/2;
            point[state.names[i]]={"x":x,"y":y};
        }
        
        for(var i=0,n=state.names.length;i<n;++i)
            for(var j=0;j<i;++j)
                if(typeof state.w[state.names[i]][state.names[j]]!="undefined")
                {
                    ctx.beginPath();
                    if((state.names[i]==state.now&&state.names[j]==state.to)||(state.names[i]==state.to&&state.names[j]==state.now))
                        ctx.strokeStyle="#ff0000";                    
                    else
                        ctx.strokeStyle="#0000ff";
                    if(state.action=="finish")
                        for(var k=1;k<state.r[state.to].length;++k)
                            if((state.r[state.to][k-1]==state.names[i]&&state.r[state.to][k]==state.names[j])||(state.r[state.to][k-1]==state.names[j]&&state.r[state.to][k]==state.names[i]))
                                ctx.strokeStyle="#ff0000";
                        
                    ctx.lineWidth=3;
                    ctx.moveTo(point[state.names[i]].x,point[state.names[i]].y);
                    ctx.lineTo(point[state.names[j]].x,point[state.names[j]].y);
                    ctx.stroke();
                    ctx.font="30px Arial";
                    ctx.fillStyle="#0000ff";
                    ctx.fillText(state.w[state.names[i]][state.names[j]],(point[state.names[i]].x+point[state.names[j]].x)/2,(point[state.names[i]].y+point[state.names[j]].y)/2);
                }
        

        
        for(var i=0,n=state.names.length;i<n;++i)
        {
            ctx.beginPath();
            ctx.arc(point[state.names[i]].x,point[state.names[i]].y,30,0,Math.PI*2,true);
            if(state.names[i]==state.now)
                ctx.fillStyle="#ff0000";
            else
                ctx.fillStyle="#0000ff";
            ctx.fill();
            ctx.fillStyle="#00ff00";
            ctx.textBaseline="middle";
            ctx.font="30px Arial";
            ctx.fillText(state.names[i],point[state.names[i]].x,point[state.names[i]].y);
            ctx.font="30px Arial";
            
            if(state.action=="update"&&state.names[i]==state.to)
                ctx.fillText(state.old_v+"=>"+state.now_v,point[state.names[i]].x,point[state.names[i]].y+25);
            else
                ctx.fillText(state.L[state.names[i]],point[state.names[i]].x,point[state.names[i]].y+25);
            ctx.fillText(state.r[state.names[i]].toString(),point[state.names[i]].x,point[state.names[i]].y+50);
        }
    }
    prev.onclick=function()
    {
        if(draw_cnt>0)
        {
            --draw_cnt;
            draw();
        }
        else
            jry_wb_beautiful_right_alert.alert("没有上一步了",1000,"auto","error");
    };
    next.onclick=function()
    {
        if(draw_cnt<process.length-1)
        {
            ++draw_cnt;
            draw();
        }
        else
            jry_wb_beautiful_right_alert.alert("没有下一步了",1000,"auto","error");
    };
    
	suan();
}