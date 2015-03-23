<?php 
$orz_usr_pwd_domain_file="../data/user_pwd";
function get_domainByUser($user){
	global $orz_usr_pwd_domain_file;
	$file=fopen($orz_usr_pwd_domain_file,"r");
	while(!feof($file)){
		$line=explode("\t",trim(fgets($file)));
		if(count($line)<3)continue;
		if($line[0]==$user){
			//$k=$line[0];
			//$v=$line[1];
			//$valid_domain=$line[2];
			return $line[2];
		}
	}
}
/*
   function orz_common_test(){
   $user="orz";
   $domain=get_domainByUser($user);	
   echo $domain;
   } 
   orz_common_test();
 */

?>
