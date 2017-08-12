<?php

$idcontent = substr($argv[1], -6);
$data = '{"initObj":{"Locale":{"LocaleLanguage":"","LocaleCountry":"","LocaleDevice":"","LocaleUserState":"Unknown"},"Platform":"Web","SiteGuid":"","DomainID":0,"UDID":"","ApiUser":"tvpapi_225","ApiPass":"11111"},"MediaID":"' . $idcontent . '","mediaType":0,"picSize":"full","withDynamic":false}';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://tvpapi-as.ott.kaltura.com/v3_4/gateways/jsonpostgw.aspx?m=GetMediaInfo');
curl_setopt($ch, CURLOPT_POST, 1);   
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_REFERER, $argv[1]);
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686; rv:41.0) Gecko/20100101 Firefox/41.0 Iceweasel/41.0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$result = curl_exec($ch);

curl_close ($ch);

preg_match('/"Format":"dash Main".*?CoGuid":"(.*?)"/', $result, $ids);
preg_match('/"MediaName":"(.*?)"/', $result, $name);
$name= str_ireplace(' ', '-', $name[1]);

preg_match('/(.*?)_0_/', $ids[1], $entryId);

$url = "http://cdnapi.kaltura.com/api_v3/index.php?service=multirequest&apiVersion=3.1&expiry=86400&clientTag=kwidget%3Av2.41__5147509b&format=1&ignoreNull=1&action=null&1:service=session&1:action=startWidgetSession&1:widgetId=_1982551&2:ks=%7B1%3Aresult%3Aks%7D&2:contextDataParams:referrer=http%3A%2F%2Fplayer.kaltura.com%2FkWidget%2Ftests%2FkWidget.getSources.html%23__1982551%2C" . $entryId[1] . "&2:contextDataParams:objectType=KalturaEntryContextDataParams&2:contextDataParams:flavorTags=all&2:service=baseentry&2:entryId=" . $entryId[1] . "&2:action=getContextData&3:ks=%7B1%3Aresult%3Aks%7D&3:service=baseentry&3:action=get&3:version=-1&3:entryId=" . $entryId[1] . "&kalsig=";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Forwarded-For: 27.123.127.255'));
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686; rv:41.0) Gecko/20100101 Firefox/41.0 Iceweasel/41.0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$result1 = curl_exec($ch);
curl_close ($ch);

preg_match_all('/\{"flavorParamsId":.*?,(.*?),"frameRate.*?"id":"(.*?)".*?\}/', $result1, $all);

if(!isset($argv[4])){
    //Si $var n'existe pas.
    foreach($all[1] as $r){

            echo "$r\n";

	    }
}
else{
    //Si $var existe.
    preg_match("/\"bitrate\":$argv[4].*?\"id\":\"(.*?)\"/", $result1, $flavorId);
    $link = 'http://video.voot.com/enc/fhls/p/1982551/sp/198255100/serveFlavor/entryId/' . $entryId[1] . '/v/2/pv/1/flavorId/' . $flavorId[1] . '/name/a.mp4/index.m3u8';
    echo "$link\n\n";

    echo "Starting  livestreamer...\n\n";
	echo "Starting  ffmpeg...\n\n";
	echo shell_exec("$argv[3]ffmpeg -i \"$link\" -bsf:a aac_adtstoasc -c copy \"$argv[2]$name.mp4\" &");
	echo "Done.\n";
    
}

?>
