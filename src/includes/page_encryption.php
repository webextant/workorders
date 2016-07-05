<?php

function pg_encrypt($str,$ky='',$codeT){
	if($codeT == "decode"){
		$str = str_replace("K|","+",$str); //replace PLSAApAA with +
		$str = str_replace("F|","/",$str); //replace rSlshAAsAA with /
		$str = str_replace("S|","=",$str); //replace rSlshAAsAA with /
	
	
		$str = base64_decode($str);
		
	}
	if($ky=='')return $str;
	$ky=str_replace(chr(32),'',$ky);
	if(strlen($ky)<8)exit('key error');
	$kl=strlen($ky)<32?strlen($ky):32;
	$k=array();for($i=0;$i<$kl;$i++){
	$k[$i]=ord($ky{$i})&0x1F;}
	$j=0;for($i=0;$i<strlen($str);$i++){
	$e=ord($str{$i});
	$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);
	$j++;$j=$j==$kl?0:$j;}
	
	//return base64_encode($str);
	if($codeT == 'encode'){
		//return base64_encode($str);
		
		$str = base64_encode($str);
		$str = str_replace("+","K|",$str);
		$str = str_replace("/","F|",$str);
		$str = str_replace("=","S|",$str);
	
		return $str;
	}
		
		return $str;
}


?>