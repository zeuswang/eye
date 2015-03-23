<?php
//require("../conf/config.php") or die("../conf/config.php failed to open");
function orz_check_login(){
	//print_r($_COOKIE);
	if(isset($_COOKIE["login"])){
		if($_COOKIE["login"]=="true"){
			return true;
		}
	}
	$host=$_SERVER["HTTP_HOST"];
	echo "<meta http-equiv='refresh' content='0.2;url=http://$host/login.php'>";
	return false;
}
orz_check_login();
?>
