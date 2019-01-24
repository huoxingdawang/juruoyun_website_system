<?php
include_once("jry_wb_includes.php");
function gettelsmscode($number)
{
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
	$st->bindParam(1,$number);
	$st->execute();	
	foreach($st->fetchAll()as $tels)
		if(strtotime(date("Y-m-d H:i:s",time()))-strtotime($tels['time'])<5*60)
			return -1;
	$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where time<?');
	$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
	$st->execute();	
	mt_srand();
	$srcstr = "0123456789";		
	$code='';
	for ($i = 0; $i < 6; $i++) 
		$code.=$srcstr[mt_rand(0, 9)];		
	$q = "INSERT INTO ".constant('jry_wb_database_general')."tel_code (tel,code,time) VALUES (?,?,?)";
	$st = $conn->prepare($q);
	$st->bindParam(1,$number);
	$st->bindParam(2,$code);
	$st->bindParam(3,jry_wb_get_time());
	$st->execute();	
	return $code; 	
}
function sendsms($number,$codes,$muban) 
{
	$params = array ();
	$accessKeyId = constant('jry_wb_short_message_aly_accesskeyid');
	$accessKeySecret = constant('jry_wb_short_message_aly_accesskeysecret');
	$params["PhoneNumbers"] = $number;
	$params["SignName"] = "蒟蒻云";
	$params["TemplateCode"] = $muban;
	$params['TemplateParam'] = $codes;
	if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
		$params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
	}
	$helper = new SignatureHelper();
	$content = $helper->request(
		$accessKeyId,
		$accessKeySecret,
		"dysmsapi.aliyuncs.com",
		array_merge($params, array(
			"RegionId" => "cn-hangzhou",
			"Action" => "SendSms",
			"Version" => "2017-05-25",
		))
	);
	return $content;
}
//以下代码来自阿里云
class SignatureHelper {
    public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false) {
        $apiParams = array_merge(array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ), $params);
        ksort($apiParams);
        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }
        $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));
        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));
        $signature = $this->encode($sign);
        $url = ($security ? 'https' : 'http')."://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";
        try {
            $content = $this->fetchContent($url);
            return json_decode($content);
        } catch( \Exception $e) {
            return false;
        }
    }
    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }
    private function fetchContent($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
        ));
        if(substr($url, 0,5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $rtn = curl_exec($ch);

        if($rtn === false) {
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);
        return $rtn;
    }
}