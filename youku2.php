<?php
error_reporting(0);
$fname='http://'.$_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"];
$ch='cuowuurl=参数错误，请检查参数！&app=/Pf/4/ctype/12/ev/1&api=http://v.youku.com/player/getPlaylist/VideoIDS/&kurl=http://k.youku.com/player/getFlvPath/sid/&zm=abcdefghijklmnopqrstuvwxyz&sz=-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1,63,52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1';
parse_str($ch);
define("CUOWU","$cuowuurl");
define("URLFN","$fname");
define("API","$api");
define("APP","$app");
define("KUR","$kurl");
define("ZM","$zm");
define("SZ","$sz");
$id=$_REQUEST['id'];
if($id&&$id!=''){$xml.=yk_list_begin($id);exit;}
Get_CW();
function yk_list_begin($vid){
	$url=API.$vid.APP;$ur=API.$vid;
	$bhtml=getbody($ur);
	$bjson=json_decode($bhtml);
	$bdata=$bjson->data[0];
	$html=getbody($url);
	$json=json_decode($html);
	$data=$json->data[0];
	$second=$data->seconds;
	$fileids=$data->streamfileids;
	if(property_exists($fileids,'mp4')){$format='mp4';}else{$format='flv';}
	$fileid=$fileids->$format;
	$segs=$data->segs->$format;
	$bsegs=$bdata->segs->$format;
	$bytes=$data->streamsizes->$format;
	$fileid=yk_file_id($fileid,$data->seed);
	$fileid_1=substr($fileid,0,8);
	$fileid_2=substr($fileid,10);
	list($sid,$token)=explode('_',yk_e('becaf9be',yk_na($data->ep)));

	foreach($segs as $k=>$v){
		$hex=strtoupper(dechex($k)).'';if(strlen($hex)<2)$hex='0'.$hex;
		$fileid=$fileid_1.$hex.$fileid_2;
		$key=$v->k;
		if(!$key||$key==''||$key=='-1')$key=$bsegs[$k]->k;
		$ep=urlencode(iconv("gbk","UTF-8",yk_d(yk_e('bf7e5f01',$sid.'_'.$fileid.'_'.$token))));
		$tvaddr=KUR.$sid.'_00/st/'.$format.'/fileid/'.$fileid.'?K='.$key.'&hd=1&myp=0&ts=';
		$tvaddr.=$v->seconds.'&ypp=0&ctype=12&ev=1&token='.$token.'&oip='.$data->ip.'&ep='.$ep;
		echo $tvaddr . "\r\n";
	}
}

function yk_file_id($fileId,$seed) {$mixed=yk_Mix_String($seed);$ids=explode('*',$fileId);unset($ids[count($ids)-1]);$realId='';for($i=0;$i<count($ids);$i++){$idx=$ids[$i];$realId.=substr($mixed,$idx,1);}return $realId;}
function yk_Mix_String($seed){$string=strtolower(ZM).strtoupper(ZM).'/\\:._-1234567890';$count=strlen($string);for($i=0;$i<$count;$i++){$seed=($seed*211+30031)%65536;$index=($seed/65536*strlen($string));$item=substr($string,$index,1);$mixed.=$item;$string=str_replace($item,'',$string);}return $mixed;unset($mixed);}
function yk_na($a){if(!$a) return "";$h=explode(',',SZ);$i=strlen($a);$f=0;for($e="";$f<$i;){do$c=$h[charCodeAt($a,$f++)&255];while($f<$i&&-1==$c);if(-1==$c)break;do$b=$h[charCodeAt($a,$f++)&255];while($f<$i&&-1==$b);if(-1==$b)break;$e.= fromCharCode($c<<2|($b&48)>>4);do{$c=charCodeAt($a,$f++)&255;if(61==$c)return $e;$c=$h[$c];}while($f<$i&&-1==$c);if(-1==$c)break;$e.=fromCharCode(($b&15)<<4|($c&60)>>2);do{$b=charCodeAt($a,$f++)&255;if(61==$b) return $e;$b=$h[$b];}while ($f <i &&-1 == $b);if(-1==$b)break;$e.=fromCharCode(($c&3)<<6|$b);}return $e;}
function yk_d($a){if(!$a)return '';$f=strlen($a);$b=0;$str=strtoupper(ZM).strtolower(ZM).'0123456789+/';for($c='';$b<$f;){$e=charCodeAt($a,$b++)&255;if($b==$f){$c.=charAt($str,($e>>2));$c.=charAt($str,(($e&3)<<4));$c.="==";break;}$g=charCodeAt($a,$b++);if($b==f){$c.=charAt($str,($e>>2));$c.=charAt($str,(($e&3)<<4|($g&240)>>4));$c.=charAt($str,(($g&15)<<2));$c.="=";break;}$h=charCodeAt($a,$b++);$c.=charAt($str,($e>>2));$c.=charAt($str,(($e&3)<<4|($g&240)>>4));$c.=charAt($str,(($g&15)<<2|($h&192)>>6));$c.=charAt($str,($h&63));}return $c;}
function yk_e($a,$c){for($f=0,$i,$e='',$h=0;256>$h;$h++)$b[$h]=$h;for($h=0;256>$h;$h++){$f=($f+$b[$h]+charCodeAt($a,$h%strlen($a)))%256;$i=$b[$h];$b[$h]=$b[$f];$b[$f]=$i;}for($q=$f=$h=0;$q<strlen($c);$q++){$h=($h+1)%256;$f=($f+$b[$h])%256;$i=$b[$h];$b[$h]=$b[$f];$b[$f]=$i;$e.=fromCharCode(charCodeAt($c,$q)^$b[($b[$h]+$b[$f])%256]);}return $e;}
function fromCharCode($codes){if(is_scalar($codes))$codes= func_get_args();$str='';foreach ($codes as $code) $str.=chr($code);return $str;}
function charCodeAt($str,$index){static $charCode=array();$key=md5($str);$index=$index+1;if(isset($charCode[$key])){return $charCode[$key][$index];}$charCode[$key]=unpack("C*",$str);return $charCode[$key][$index];}
function charAt($str,$index=0){return substr($str,$index,1);}
function deChr($arr){preg_match_all('/([0-9][0-9][0-9])/',$arr,$ar);
foreach($ar[1]as$k=>$v){if($v{0}=='0')$v=substr($v,1);$urv.=chr($v);}return $urv;}
function getbody($url){$user_agent=$_SERVER['HTTP_USER_AGENT'];$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);curl_setopt($ch,CURLOPT_REFERER,$url);curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);curl_setopt($ch,CURLOPT_USERAGENT,$user_agent);@$file=curl_exec($ch);curl_close($ch);return $file;}
function Get_CW(){header("Content-type:text/html;charset=utf-8");echo CUOWU;exit;}
?>