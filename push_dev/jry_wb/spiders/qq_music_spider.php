<?php
	include_once("../tools/jry_wb_includes.php");
	function jry_wb_qq_music_spider($in_mid)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('spiderdb').'qq_music where mid=?');
		$st->bindParam(1,$in_mid);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0&&date('Y-m-d')!=date('Y-m-d',strtotime($data[0]['lasttime'])))
		{
			$url='http://u.y.qq.com/cgi-bin/musicu.fcg?g_tk=5381&uin=0&format=json&callback=player_jsonp_1&data={"comm"%3A{"ct"%3A23%2C"cv"%3A0}%2C"data_mid"%3A{"module"%3A"track_info.UniformRuleCtrlServer"%2C"method"%3A"GetTrackInfo"%2C"param"%3A{"mids"%3A["'.$in_mid.'"]%2C"types"%3A[0]}}%2C"url_mid"%3A{"module"%3A"vkey.GetVkeyServer"%2C"method"%3A"CgiGetVkey"%2C"param"%3A{"guid"%3A"4845423188"%2C"songmid"%3A["'.$in_mid.'"]%2C"songtype"%3A[0]%2C"uin"%3A"0"%2C"loginflag"%3A0%2C"platform"%3A"23"}}}&_=1537937432379';
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER, 0);    
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			$get_sorce=curl_exec($ch);
			$get_sorce=substr($get_sorce,15,strlen($get_sorce));
			$get_sorce=substr($get_sorce,0,strlen($get_sorce)-1);
			$get_sorce=json_decode($get_sorce);
			$data[0]['music_url']=$get_data['music_url']=$get_sorce->url_mid->data->midurlinfo[0]->purl;
			$st = $conn->prepare('UPDATE '.constant('spiderdb').'qq_music SET `music_url`=?,`lasttime`=? WHERE mid=?');
			$st->bindParam(1,$get_data['music_url']);
			$st->bindParam(2,$data[0]['lasttime']=jry_wb_get_time());
			$st->bindParam(3,$get_data['mid']=$in_mid);
			$st->execute();
		}
		if(count($data)==0)
		{
			$url = 'https://y.qq.com/n/yqq/song/'.$in_mid.'.html';
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HEADER, 0);    
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$get_sorce=curl_exec($ch);
			preg_match_all('/<img src=\".*?\" .*?>/',$get_sorce,$pat_array);
			$get=$pat_array[0][1];
			preg_match_all('/src=\".*?\"/',$get,$pat_array);
			$get=$pat_array[0][0];
			preg_match_all('/\".*?\"/',$get,$pat_array);
			$get=$pat_array[0][0];
			$get_data['pic_url']=substr($get,1,strlen($get)-2);
			preg_match_all('/<h1.*?class=\"data__name_txt\".*?title=\".*?\">.*?<\/h1>/',$get_sorce,$pat_array);
			$get=$pat_array[0][0];
			preg_match_all('/title=\".*?\"/',$get,$pat_array);
			$get=$pat_array[0][0];
			preg_match_all('/\".*?\"/',$get,$pat_array);
			$get=$pat_array[0][0];
			$get_data['name']=substr($get,1,strlen($get)-2);
			$url='http://u.y.qq.com/cgi-bin/musicu.fcg?g_tk=5381&uin=0&format=json&callback=player_jsonp_1&data={"comm"%3A{"ct"%3A23%2C"cv"%3A0}%2C"data_mid"%3A{"module"%3A"track_info.UniformRuleCtrlServer"%2C"method"%3A"GetTrackInfo"%2C"param"%3A{"mids"%3A["'.$in_mid.'"]%2C"types"%3A[0]}}%2C"url_mid"%3A{"module"%3A"vkey.GetVkeyServer"%2C"method"%3A"CgiGetVkey"%2C"param"%3A{"guid"%3A"4845423188"%2C"songmid"%3A["'.$in_mid.'"]%2C"songtype"%3A[0]%2C"uin"%3A"0"%2C"loginflag"%3A0%2C"platform"%3A"23"}}}&_=1537937432379';
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER, 0);    
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			$get_sorce=curl_exec($ch);
			$get_sorce=substr($get_sorce,15,strlen($get_sorce));
			$get_sorce=substr($get_sorce,0,strlen($get_sorce)-1);
			$get_sorce=json_decode($get_sorce);
			$get_data['album']=$get_sorce->data_mid->data->tracks[0]->album->name;
			$get_data['music_url']=$get_sorce->url_mid->data->midurlinfo[0]->purl;
			$singers=$get_sorce->data_mid->data->tracks[0]->singer;
			$total=count($singers);
			for($i=0;$i<$total;$i++)
				$get_data['singers'][$i]=array('name'=>$singers[$i]->name,'id'=>$singers[$i]->id);
			$get_data['singers']=json_encode($get_data['singers']);
			$st = $conn->prepare('INSERT INTO '.constant('spiderdb').'qq_music (`mid`,`pic_url`,`name`,`album`,`music_url`,`singers`,`lasttime`) VALUES (?,?,?,?,?,?,?)');
			$st->bindParam(1,$get_data['mid']=$in_mid);
			$st->bindParam(2,$get_data['pic_url']);
			$st->bindParam(3,$get_data['name']);
			$st->bindParam(4,$get_data['album']);
			$st->bindParam(5,$get_data['music_url']);
			$st->bindParam(6,$get_data['singers']);
			$st->bindParam(7,$get_data['lasttime']=jry_wb_get_time());
			$st->execute();
			$get_data['type']='qq';
		}
		else
			$get_data=array('mid'=>$data[0]['mid'],'type'=>'qq','pic_url'=>$data[0]['pic_url'],'name'=>$data[0]['name'],'album'=>$data[0]['album'],'music_url'=>$data[0]['music_url'],'singers'=>$data[0]['singers'],'lasttime'=>$data[0]['lasttime']);
		return ($get_data);
	}
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		echo JSON_encode(jry_wb_qq_music_spider($_GET['mid']));