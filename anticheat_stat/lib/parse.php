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
	$para_list=array("t","n","r","l","ln","v","sgsa_id","ua","se","sw","ds","cl","ui","pf","ol","ip","ml");//no use,just declare para name
	$ra=array();//rl:result_array
	$out_file=NULL;
	if($output_file!=NULL){
		$out_file=fopen($output_file,"w") or die("failed to open output_file");
		array_push($ra,"domian");	//0
		array_push($ra,"t");		//1
		array_push($ra,"r");		//2
		array_push($ra,"sgsa_id");	//3
		array_push($ra,"ua");		//4
		array_push($ra,"ds");		//5
		array_push($ra,"ui");		//6
		array_push($ra,"pf");		//7
		array_push($ra,"ol");		//8
		array_push($ra,"ip");		//9
		array_push($ra,"n");		//10,n= click or pb_pv
		array_push($ra,"ml");		//11
		$r=join("\t",$ra);
		fwrite($out_file,$r."\n");
	}
	$file=fopen($apache_log,"r") or die("failed to open access.log file");
	while(!feof($file)){		
		$ra=array();
		$tmp=fgets($file);
		if(stripos($tmp,"orz_pv")<=-1)continue;
		$line=orz_apachelog_pingbackpara($tmp);
		$para=orz_http_paras2array($line);
		$uid_l=explode("|",$para["sgsa_id"]);
		array_push($ra,$uid_l[0]);		//0,domain
		array_push($ra,$para["t"]);		//1,
		array_push($ra,urldecode($para["r"]));	//2,
		array_push($ra,$para["sgsa_id"]);	//3,
		array_push($ra,$para["ua"]);		//4
		array_push($ra,$para["ds"]);		//5
		array_push($ra,$para["ui"]);		//6
		array_push($ra,$para["pf"]);		//7
		array_push($ra,$para["ol"]);		//8
		$line_explode=explode("\t",trim($tmp));
		array_push($ra,$line_explode[0]);	//9,ip
                array_push($ra,$para["n"]);             //10
		if(isset($para["ml"])){
			array_push($ra,$para["ml"]);            //11
		}else{
			array_push($ra,"0");
		}

		$r=join("\t",$ra);
		if($out_file!=NULL){
			fwrite($out_file,$r."\n");	
		}
	}
}
//orz_apachelog_getpingback("../access.log","../data/temp");
//require("../lib/orz_view_datatable.php");
//orz_view_datatable("../data/temp");
?>
