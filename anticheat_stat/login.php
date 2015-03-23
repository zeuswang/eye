<?php header("Content-type: text/html; charset=utf-8"); 
	$user="";
	$pwd="";
	if(isset($_GET["user"])){$user=$_GET["user"];}
	if(isset($_GET["pwd"])){$pwd=$_GET["pwd"];}
	$file=fopen("data/user_pwd","r");
	while(!feof($file)){
		$line=explode("\t",trim(fgets($file)));
		if(count($line)<2)continue;
		$k=$line[0];
		$v=$line[1];
		//print "$k|$v|";
		if($k==$user && $v==$pwd){
			$host=$_SERVER["HTTP_HOST"];
			setcookie("user",$user,time()+3600);
			setcookie("login","true",time()+3600);
			header("location:http://$host/index.php");
			break;
		}
	}
	echo "登录名 密码错误 请重新输入<br>";
?>
<form>
<table width=20% border=0>
<tr><td></td><td align="center">请登录</td></tr>
<tr><td>user</td><td><input type="text" name="user" /></td></tr>
<tr><td>pwd</td><td><input type="text" name="pwd" /></td></tr>
<tr><td></td><td align="center"><input type="submit" value="Submit" action="login.php"></td></tr>
</table>
</form>
