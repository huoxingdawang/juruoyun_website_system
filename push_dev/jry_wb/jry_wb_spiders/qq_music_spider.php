<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	function jry_wb_qq_music_spider($in_mid)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_SPIDERS.'qq_music where mid=?');
		$st->bindParam(1,$in_mid);
		$st->execute();
		$data=$st->fetchAll();
		$flag=(count($data)==0);
		if($flag)
			$data[0]=array();
		if($flag||date('Y-m-d')!=date('Y-m-d',strtotime($data[0]['lasttime'])))
		{
			$url='http://u.y.qq.com/cgi-bin/musicu.fcg?g_tk=5381&uin=0&format=json&callback=player_jsonp_1&data={"comm"%3A{"ct"%3A23%2C"cv"%3A0}%2C"data_mid"%3A{"module"%3A"track_info.UniformRuleCtrlServer"%2C"method"%3A"GetTrackInfo"%2C"param"%3A{"mids"%3A["'.$in_mid.'"]%2C"types"%3A[0]}}%2C"url_mid"%3A{"module"%3A"vkey.GetVkeyServer"%2C"method"%3A"CgiGetVkey"%2C"param"%3A{"guid"%3A"4845423188"%2C"songmid"%3A["'.$in_mid.'"]%2C"songtype"%3A[0]%2C"uin"%3A"0"%2C"loginflag"%3A0%2C"platform"%3A"23"}}}&_=1537937432379';
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER, 0);    
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			$get_sorce=curl_exec($ch);
			$get_sorce=substr($get_sorce,15);
			$get_sorce=substr($get_sorce,0,-1);
			$get_sorce=json_decode($get_sorce);
			$album_id=$get_sorce->data_mid->data->tracks[0]->album->id;
			$song_id=$get_sorce->data_mid->data->tracks[0]->id;
			$data[0]['music_url']	=$get_sorce->url_mid->data->midurlinfo[0]->purl;
			$data[0]['pic_url']		='http://imgcache.qq.com/music/photo/album_300/'.$album_id%100 .'/300_albumpic_'.$album_id.'_0.jpg';
			$data[0]['name']		=$get_sorce->data_mid->data->tracks[0]->name;
			$data[0]['album']		=$get_sorce->data_mid->data->tracks[0]->album->name;
			foreach($get_sorce->data_mid->data->tracks[0]->singer as $one)$data[0]['singers'][]=array('name'=>$one->name,'id'=>$one->id);
			$data[0]['singers']		=json_encode($data[0]['singers']);
			if($data[0]['lyric']=='')
			{
				$url='http://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg?nobase64=1&musicid='.$song_id.'&callback=jsonp1&g_tk=5381&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_HEADER, 0);    
				curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch,CURLOPT_HTTPHEADER,array('Origin:https://y.qq.com','Referer:https://y.qq.com/n/yqq/song/'.$in_mid.'.html'));
				$get_sorce=curl_exec($ch);
				$get_sorce=substr($get_sorce,7);
				$get_sorce=substr($get_sorce,0,-1);
				$get_sorce=json_decode($get_sorce);
				$data[0]['lyric']		=str_replace('&#58;',':',str_replace('&#10;',"\n",str_replace('&#46;','.',str_replace('&#45;','-',str_replace('&#32;',' ',$get_sorce->lyric)))));
			}
			if($flag)
				$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_SPIDERS.'qq_music (`pic_url`,`name`,`album`,`music_url`,`singers`,`lasttime`,`lyric`,`mid`) VALUES (?,?,?,?,?,?,?,?)');
			else
				$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_SPIDERS.'qq_music SET `pic_url`=?,`name`=?,`album`=?,`music_url`=?,`singers`=?,`lasttime`=?,`lyric` WHERE `mid`=?');
				
			$st->bindParam(1,$data[0]['pic_url']);
			$st->bindParam(2,$data[0]['name']);
			$st->bindParam(3,$data[0]['album']);
			$st->bindParam(4,$data[0]['music_url']);
			$st->bindParam(5,$data[0]['singers']);
			$st->bindParam(6,$data[0]['lasttime']=jry_wb_get_time());
			$st->bindParam(7,$data[0]['lyric']);
			$st->bindParam(8,$data[0]['mid']=$in_mid);
			$st->execute();
		}
		return array('mid'=>$data[0]['mid'],'type'=>'qq','pic_url'=>$data[0]['pic_url'],'name'=>$data[0]['name'],'album'=>$data[0]['album'],'music_url'=>$data[0]['music_url'],'singers'=>json_decode($data[0]['singers']),'lasttime'=>$data[0]['lasttime'],'lyric'=>$data[0]['lyric']);
	}
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		echo JSON_encode(jry_wb_qq_music_spider($_GET['mid']));