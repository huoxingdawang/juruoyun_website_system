<?php
//热评API http://music.163.com/api/v1/resource/comments/R_SO_4_574566207
	include_once("../tools/jry_wb_includes.php");
	function jry_wb_163_music_spider($in_mid)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('spiderdb').'163_music where mid=?');
		$st->bindParam(1,$in_mid);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0&&(strtotime(jry_wb_get_time())-strtotime($data[0]['lasttime']))>60*60*0.25)
		{
			$url='http://music.163.com/weapi/song/enhance/player/url?csrf_token=';
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER, 0);    
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,'params='.urlencode(jry_wb_aes_encode(jry_wb_aes_encode('{"ids":"['.$in_mid.']","br":128000,"csrf_token":""}','0CoJUm6Qyw8W8jud','0102030405060708'),'a8LWv2uAtXjzSfkQ','0102030405060708')).'&encSecKey=2d48fd9fb8e58bc9c1f14a7bda1b8e49a3520a67a2300a1f73766caee29f2411c5350bceb15ed196ca963d6a6d0b61f3734f0a0f4a172ad853f16dd06018bc5ca8fb640eaa8decd1cd41f66e166cea7a3023bd63960e656ec97751cfc7ce08d943928e9db9b35400ff3d138bda1ab511a06fbee75585191cabe0e6e63f7350d6');
		//	curl_setopt($ch,CURLOPT_PROXYAUTH,CURLAUTH_BASIC);
		//	curl_setopt($ch,CURLOPT_PROXY,'117.94.71.75');
		//	curl_setopt($ch,CURLOPT_PROXYPORT,"4555");
			$get_sorce=curl_exec($ch);
			curl_close($ch);
			$get_sorce=json_decode($get_sorce);
			$data[0]['music_url']=$get_sorce->data[0]->url;
			$st = $conn->prepare('UPDATE '.constant('spiderdb').'163_music SET `music_url`=?,`lasttime`=? WHERE mid=?');
			$st->bindParam(1,$data[0]['music_url']);
			$st->bindParam(2,$data[0]['lasttime']=jry_wb_get_time());
			$st->bindParam(3,$data[0]['mid']=$in_mid);
			$st->execute();		
		}
		if(count($data)==0)
		{
			$get_data=Array();
			$url='http://music.163.com/weapi/song/enhance/player/url?csrf_token=';
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER, 0);    
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,'params='.urlencode(jry_wb_aes_encode(jry_wb_aes_encode('{"ids":"['.$in_mid.']","br":128000,"csrf_token":""}','0CoJUm6Qyw8W8jud','0102030405060708'),'a8LWv2uAtXjzSfkQ','0102030405060708')).'&encSecKey=2d48fd9fb8e58bc9c1f14a7bda1b8e49a3520a67a2300a1f73766caee29f2411c5350bceb15ed196ca963d6a6d0b61f3734f0a0f4a172ad853f16dd06018bc5ca8fb640eaa8decd1cd41f66e166cea7a3023bd63960e656ec97751cfc7ce08d943928e9db9b35400ff3d138bda1ab511a06fbee75585191cabe0e6e63f7350d6');
		//	curl_setopt($ch,CURLOPT_PROXYAUTH,CURLAUTH_BASIC);
		//	curl_setopt($ch,CURLOPT_PROXY,'117.94.71.75');
		//	curl_setopt($ch,CURLOPT_PROXYPORT,"4555");
			$get_sorce=curl_exec($ch);
			curl_close($ch);
			$get_sorce=json_decode($get_sorce);
		//	print_r($get_sorce);
			$get_data['music_url']=$get_sorce->data[0]->url;
			$url='https://music.163.com/weapi/cloudsearch/get/web?csrf_token=';
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER, 0);    
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,'params='.urlencode(jry_wb_aes_encode(jry_wb_aes_encode('{"s":"'.$in_mid.'","offset":0,"limit":1,"type":"1"}','0CoJUm6Qyw8W8jud','0102030405060708'),'a8LWv2uAtXjzSfkQ','0102030405060708')).'&encSecKey=2d48fd9fb8e58bc9c1f14a7bda1b8e49a3520a67a2300a1f73766caee29f2411c5350bceb15ed196ca963d6a6d0b61f3734f0a0f4a172ad853f16dd06018bc5ca8fb640eaa8decd1cd41f66e166cea7a3023bd63960e656ec97751cfc7ce08d943928e9db9b35400ff3d138bda1ab511a06fbee75585191cabe0e6e63f7350d6');
		//	curl_setopt($ch,CURLOPT_PROXYAUTH,CURLAUTH_BASIC);
		//	curl_setopt($ch,CURLOPT_PROXY,'117.94.71.75');
		//	curl_setopt($ch,CURLOPT_PROXYPORT,"4555");
			$get_sorce=curl_exec($ch);
			curl_close($ch);
			$get_sorce=json_decode($get_sorce);
		//	print_r($get_sorce->result->songs[0]);
			$get_data['name']=$get_sorce->result->songs[0]->name;
			$get_data['pic_url']=$get_sorce->result->songs[0]->al->picUrl;
			$get_data['album']=$get_sorce->result->songs[0]->al->name;
			foreach($get_sorce->result->songs[0]->ar as $ar)
				$get_data['singers'].=$ar->name.' ';
			$st = $conn->prepare('INSERT INTO '.constant('spiderdb').'163_music (`mid`,`pic_url`,`name`,`album`,`music_url`,`singers`,`lasttime`) VALUES (?,?,?,?,?,?,?)');
			$st->bindParam(1,$get_data['mid']=$in_mid);
			$st->bindParam(2,$get_data['pic_url']);
			$st->bindParam(3,$get_data['name']);
			$st->bindParam(4,$get_data['album']);
			$st->bindParam(5,$get_data['music_url']);
			$st->bindParam(6,$get_data['singers']);
			$st->bindParam(7,$get_data['lasttime']=jry_wb_get_time());
			$st->execute();
			$get_data['type']='163';
		}
		else
			$get_data=array('mid'=>$data[0]['mid'],'type'=>'163','pic_url'=>$data[0]['pic_url'],'name'=>$data[0]['name'],'album'=>$data[0]['album'],'music_url'=>$data[0]['music_url'],'singers'=>$data[0]['singers'],'lasttime'=>$data[0]['lasttime']);
		return ($get_data);
	}
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		echo JSON_encode(jry_wb_163_music_spider($_GET['mid']));
?>