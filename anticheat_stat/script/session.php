<html>
<head>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
</head>
<?php
require_once("../lib/orz_session.php");
$sl=new orz_Session_list("../data/session_temp");
$uid=NULL;
$file=fopen("../data/temp","r");
$count=0;

while(!feof($file)){
	$line=explode("\t",rtrim(fgets($file)));	
	if(count($line)<9)continue;
	$uid=$line[3];
	if($uid==""){$uid="-";}
	$sl->add_item($uid,$line);
}
$sl->output();

//$kl->write("../data/table_temp","key\tpv\tuv\n");
//$sl->printall();
//require_once("../lib/orz_view_datatable.php");
//orz_view_datatable("../data/table_temp");
?>

