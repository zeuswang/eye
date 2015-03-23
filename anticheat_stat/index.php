<?php require("./lib/top.php");?>
<html>
<body>
<table border=1 width=100% height=100%>
	<tr>
		<td colspan=2 height=5% align=middle>
			<div style='float:left'><a href="login.php">login</a></div>
			<div style='float:left'>|</div>
			<div style='float:left'><a href="" onclick="delCookie('login');">loginout</a></div>
			<div style='float:right'><a href="help.php">help</a></div>
		</td>
        </tr>
	<!--
	<tr>
		<td colspan=2 height=5% align=middle>head</td>
	</tr>
	-->
	<tr>
		<td width=5%>
			<div><a href="#" onclick='upsrc("key=domain")'>domain</a></div>
			<div><a href="#" onclick='upsrc("key=refer")'>refer</a></div>
			<div><a href="#" onclick='upsrc("key=date")'>date</a></div>
			<div><a href="#" onclick='upsrc("key=platform")'>platform</a></div>
			<div><a href="#" onclick='upsrc("key=suid")'>suid</a></div>
			<div><a href="#" onclick='upsrc("key=ds")'>ds</a></div>
			<div><a href="#" onclick='upsrc("key=hour")'>hour</a></div>
			<div><a href="#" onclick='upsrc("key=ip")'>ip</a></div>
		</td >

		<td width=95% height=85% align=middle>
			<!--<div>right-content</div>-->
			<div><iframe id="stat" src="http://123.56.111.132/script/stat.php" width=100% height=100% onload="this.height=Frame1.document.body.scrollHeight"></iframe></div>
		</td>
        </tr>
</table>
<script>
function upsrc(n){
        var t=document.getElementById("stat");
        t.src="http://123.56.111.132/script/stat.php?"+n;
}
function delCookie(name){//为了删除指定名称的cookie，可以将其过期时间设定为一个过去的时间
	date=new Date();
	date.setTime(date.getTime()-10000);
	document.cookie=name+"=a;expires="+date.toGMTString();
}
</script>
</body>

</html>
