<?php
$file=fopen("../access.log","r") or die("file open failed");
$c=0;
while(!feof($file))
{
	$tmp=fgets($file);
	$c+=1;
	if($c>10)continue;
	$line=(explode("\t",trim($tmp)));
	print_r($line);
	print "<br>";
}
?>
