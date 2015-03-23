<?php 
function orz_apachelog_pingbackpara($s){
	$line=(explode("\t",trim($s)));
	$request=$line[4];
	$url_s=(explode(" ",trim($request)));
	$para=(explode("?",trim($url_s[1])));
	return $para[1];
}
function orz_http_paras2array($s,$default_s="-"){
	$parastring=explode("&",$s);
	$r=array();
	while (list($key, $val) = each($parastring)){
		$t=explode("=",$val);
		if($t[1]=="")$t[1]=$default_s;
		$r[$t[0]]=$t[1];
	}
	return $r;
}

function orz_apachelog_getpingback($apache_log,$output_file=NULL){
	$para_list=array("t","n","r","l","ln","v","sgsa_id","ua","se","sw","ds","cl","ui","pf","ol");
	$ra=array();//rl:result_array
	$out_file=NULL;
	if($output_file!=NULL){
		$out_file=fopen($output_file,"w") or die("failed to open output_file");
		array_push($ra,"domian");
		array_push($ra,"t");
		array_push($ra,"r");
		array_push($ra,"sgsa_id");
		array_push($ra,"ua");
		array_push($ra,"ds");
		array_push($ra,"ui");
		array_push($ra,"pf");
		array_push($ra,"ol");
		$r=join("\t",$ra);
		fwrite($out_file,$r."\n");
	}
	$file=fopen($apache_log,"r") or die("failed to open access.log file");
	//$c=0;
	while(!feof($file)){		
		$ra=array();
		$tmp=fgets($file);
		if(stripos($tmp,"orz_pv")<=-1)continue;
		$line=orz_apachelog_pingbackpara($tmp);
		$para=orz_http_paras2array($line);
		//$c++;if($c>10)break;
#print_r($para);
		$uid_l=explode("|",$para["sgsa_id"]);
		array_push($ra,$uid_l[0]);
		array_push($ra,$para["t"]);
		array_push($ra,urldecode($para["r"]));
		array_push($ra,$para["sgsa_id"]);
		array_push($ra,$para["ua"]);
		array_push($ra,$para["ds"]);
		array_push($ra,$para["ui"]);
		array_push($ra,$para["pf"]);
		array_push($ra,$para["ol"]);
		$r=join("\t",$ra);
		if($out_file!=NULL){
			fwrite($out_file,$r."\n");	
		}
	}
}
orz_apachelog_getpingback("../access.log","../data/temp");
require_once("../lib/orz_view_datatable.php");
orz_view_datatable("../data/temp");
?>
