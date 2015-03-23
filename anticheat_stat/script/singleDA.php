<?php 
//session format:
//domian,stime,refer,sgsa_id,ua,ds,ui,pf,ol,etime,pv,click_num,ad_click_num,se_word,leave_page,ip

//log parse format:
//("t","n","r","l","ln","v","sgsa_id","ua","se","sw","ds","cl","ui","pf","ol","ip");

function get_ipc($ip_str){
	$t=explode(".",$ip_str);
	if(!(count($t)==4))return $ip_str;
	return sprintf("%s.%s.%s",$t[0],$t[1],$t[2]);
}

function get_ip_pv_analytics($col_to_analytics=9,$k_f=null){
	//$file=fopen("../data/session_temp","r");
	$file=fopen("../data/temp","r");
	$col_arr=Array();
	//get key_pv, key should be ip(9)       
	while(!(feof($file))){
		$line=explode("\t",rtrim(fgets($file)));
		if(count($line)<($col_to_analytics+1))continue;
		$k=$line[$col_to_analytics];
		if($k_f!=null)$k=$k_f($k);

		if(isset($col_arr[$k])){
			$col_arr[$k]+=1;
		}else{
			$col_arr[$k]=1;
		}
	}
	get_single_dimesion_num_distribution($col_arr,"ip_pv_num_DS","ip_nums_has_x_pv");
	//get_single_dimesion_percent_distribution($col_arr,"ip_pv_percent_DS","ip_nums_has_x_percentofpv");
}

//session format:
//domian,stime[1],refer[2],sgsa_id[3],ua[4],ds[5],ui[6],pf[7],ol[8],etime[9],pv[10],click_num[11],ad_click_num[12],se_word[13],leave_page[14],ip[15]

function get_ip_interval_analytics($col_to_analytics=9,$k_f=null){
        //$file=fopen("../data/session_temp","r");
        $file=fopen("../data/session_temp","r");
        $col_arr=Array();
        //get key_pv, key should be ip(9)       
        while(!(feof($file))){
                $line=explode("\t",rtrim(fgets($file)));
                if(count($line)<($col_to_analytics+1))continue;
                $k=$line[$col_to_analytics];
                if($k_f!=null)$k=$k_f($k);

		if($line[10]<=1)continue;
		print "$line[9]\t$line[1]\t".($line[9]-$line[1])."\t$line[10]\t".(($line[9]-$line[1])/$line[10]/1000)."\t$line[15]\n";
                array_push($col_arr,sprintf("%d",($line[9]-$line[1])/($line[10]-1)/1000));
        }
        get_single_dimesion_num_distribution($col_arr,"ip_interval_num_DS","ip_intervals_has_x_pv");
        get_single_dimesion_percent_distribution($col_arr,"ip_pv_percent_DS","ip_nums_has_x_percentofpv");
}

//get_ip_pv_analytics(9,'get_ipc');
//get_ip_pv_analytics(9);
get_ip_interval_analytics(15);

function get_single_dimesion_num_distribution($col_arr,$name="get x distribution",$line_name="line"){
	$arr=Array();
	while(list($key,$val)= each($col_arr)) {
		//print "$key\t$val\n";
		if(isset($arr[$val])){
			$arr[$val]+=1;			
		}else{
			$arr[$val]=1;
		}
	}
	ksort($arr);
	echo "<div>chart_name:$name</div>";
	require_once("../lib/orz_view_c3chart.php");
	orz_view_c3chart4array($arr,$line_name);
}

function get_single_dimesion_percent_distribution($col_arr,$name="get x distribution",$line_name="line"){
	$arr=Array();
	$total=0;
	while(list($key,$val)= each($col_arr)) {
		//print "$key\t$val\n";
		$total+=$val;
		if(isset($arr[$val])){
			$arr[$val]+=1;                  
		}else{
			$arr[$val]=1;
		}
	}
	echo "\n.total_pv:".$total."<br>\n";
	ksort($arr);
	while(list($key,$val)= each($arr)) {
		$arr[$key]=sprintf("%.2f",$val*$key/$total*100);
	}
	echo "<div>chart_name:$name</div>";
	require_once("../lib/orz_view_c3chart.php");
	orz_view_c3chart4array($arr,$line_name);
}

?>
