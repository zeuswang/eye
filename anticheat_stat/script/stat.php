<?php require_once("../lib/top.php");?>
<html>

<head>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
</head>
<style>

</style>
<table width=100%>
<tr><td width=95%>
<div style='float:left'>数据所在时间段：</div>
<div style='float:left;width:70px;text-align:center'><a id='t1' href="" onclick="upsrc(0,0,'t1')">今天</a></div>
<div style='float:left;width:70px;text-align:center'><a id='t2' href="" onclick="upsrc(1,1,'t2')" >昨天</a></div>
<div style='float:left;width:70px;text-align:center'><a id='t3' href="" onclick="upsrc(7,1,'t3')" >最近7天</a></div>
<div style='float:left;width:70px;text-align:center'><a id='t4' href="" onclick="upsrc(30,1,'t4')" >最近30天</a></div>
</td></tr>
<tr>
<?php
$user="";
if(isset($_COOKIE["user"])){
	$user=$_COOKIE["user"];
}

require_once("../lib/orz_common.php");
$validDomain=get_domainByUser($user);

echo "domain=$validDomain<br>\n";
echo "user=$user<br>\n";

//1.up parsed data
require_once("../lib/parse.php");
orz_apachelog_getpingback("../access.log","../data/temp");

//1.1 up session data
require_once("../lib/orz_session.php");
$sl=new orz_Session_list("../data/session_temp");
$uid=NULL;
$file=fopen("../data/temp","r");
while(!feof($file)){
	$line=explode("\t",rtrim(fgets($file)));
	if(count($line)<9)continue;
	$uid=$line[3];
	if($uid==""){$uid="-";}
	$sl->add_item($uid,$line);
}
$sl->output();

//2.get para :key,sdate,edate
$key="refer";if(array_key_exists("key",$_GET))$key=$_GET["key"];
$sdate=date("Ymd",strtotime("-1 day"));if(array_key_exists("sdate",$_GET))$sdate=intval($_GET["sdate"]);
$edate=date("Ymd",strtotime("-1 day"));if(array_key_exists("edate",$_GET))$edate=intval($_GET["edate"]);
//$validDomain="all";//mr_test
//$user="orz";//mr_test
echo $key."|".$sdate."|".$edate."|$user<br>\n";


//3.stat by para	(sdate,edate,validDomain,key)
require_once("../lib/orz_stat.php");
$kl=new orz_stat_keylist();
$key_info=NULL;
//$file=fopen("../data/temp","r");
$file=fopen("../data/session_temp","r");
while(!feof($file)){
	//a.get line
	$line=explode("\t",fgets($file));	
	if(count($line)<9)continue;

	//b.filter
	$date=intval(date('Ymd',intval($line[1]/1000)));
	if(!($date>=$sdate && $date<=$edate))continue;
	if(!($validDomain=="all"||$validDomain==$line[0]))continue;

	//c.get key
	$key_info=$line[0];
	switch($key){
		case "domain":$key_info=$line[0];break;
		case "refer":$key_info=$line[2];break;
		case "date":$key_info=$date;break; 
		case "platform":$key_info=$line[7];break;
		case "suid":$key_info=$line[3];break;
		case "ds":$key_info=$line[5];break;
		case "hour":$key_info=date('H',intval($line[1]/1000));break;
		case "ip":$key_info=rtrim($line[15]);break;
	}
	//print "key:".$key_info;
	if($key_info==""){$key_info="-";}

	//d.add item
	$kl->add_item($key_info,$line);
}

$kl->write("../data/table_temp","key\tpv\tuv\tsession_num\told_user\tclick_num\tavg_ml\n");

require_once("../lib/orz_view_datatable.php");
orz_view_datatable("../data/table_temp");
?>

</tr>
</table>

<?php
	
	//require_once("../lib/orz_view_c3chart.php");
	//orz_view_c3chart("../data/temp_test");
	//orz_view_c3chart("../data/temp_test");
?>


<script>
function getBeforeDate(n){
	var d = new Date();
	d.setDate(d.getDate()-n);  //注意setDate()的用法，0为上月最后一天；setDate(d.getDate()-n)标示N天以前
	year = d.getFullYear();
	mon=d.getMonth()+1;
	day=d.getDate();
	s = year+''+(mon<10?('0'+mon):mon)+''+(day<10?('0'+day):day);
	return s;
}

function upsrc(sdate,edate,id){
	var t=document.getElementById(id);
	t.href="http://123.56.111.132/script/stat.php?sdate="+getBeforeDate(sdate)+"&edate="+getBeforeDate(edate)+"&<?php echo "key=".$key; ?>";
	//alert(t.href);
}
document.write("<script language='javascript' src='http://123.56.111.132/js/h.js'><\/script>");
</script>
</html>

