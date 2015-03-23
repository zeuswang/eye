<?php 
$def=array("domain"=>0,"refer"=>1,"time"=>2);
function t($s){return "-----------------";}

function convert_sql4eval($sql,$line="line",$return="r"){
	global $def;
	$sql_arr=explode(",",$sql);
	$r=Array();
	$format=Array();
	while(list($k,$v)=each($sql_arr)){
		//array_push($r,sprintf("t(\$".$line."[%s])",$def[$v]));
		array_push($r,sprintf("\$".$line."[%s]",$def[$v]));
		array_push($format,"%s");
	}
	$s=join(",",$r);
	$fs=join("\t",$format);
	return "\$$return=sprintf(\"$fs\n\",$s);";
}

$file=fopen("../data/temp","r");
$c=0;
$eval_sql=convert_sql4eval("domain,refer,time","line","r");

while(!feof($file)){
	$line=explode("\t",rtrim(fgets($file)));
	$r="";
	eval($eval_sql);
	print $r;
	if(++$c>10)break;
}
?>
