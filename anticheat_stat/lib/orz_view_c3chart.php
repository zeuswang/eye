<?php

$orz_view_c3chart_host=isset($_SERVER['HTTP_HOST'])?"http://".($_SERVER['HTTP_HOST']):"";
$orz_view_c3chart_chart_num=0;
echo "<link type='text/css' rel='stylesheet' href='$orz_view_c3chart_host/lib/c3/c3.css'>\n";
echo "<script type='text/javascript' src='$orz_view_c3chart_host/lib/c3/d3.v3.min.js'></script>\n";
echo "<script type='text/javascript' src='$orz_view_c3chart_host/lib/c3/c3.js'></script>\n";

function orz_view_c3chart4file($input,$input_name="line",$chart_name=null){	//file hoped to be file or array or str 
	global $orz_view_c3chart_chart_num;	//differrent chart should have different name;
	if($chart_name==null)$chart_name="orz_c3chart".(microtime(true)*10000)."".(++$orz_view_c3chart_chart_num);
	$file=fopen($input,"r");
	$line_arr=Array();
	while(!(feof($file))){
		$s=explode("\t",rtrim(fgets($file)));	
		if($s[0]=="")continue;
		array_push($line_arr,$s[0]);
	}
	$line_str=join(",",$line_arr);

	echo "	<div id='$chart_name' style=''>loading-chart</div>
		<script>
			var chart = c3.generate({
				bindto: '#$chart_name',
				data: {
					columns: [
						['$input_name',$line_str],
					],
					onclick: function (d, element) { console.log('onclick', d, element); },
					onmouseover: function (d) { console.log('onmouseover', d); },
					onmouseout: function (d) { console.log('onmouseout', d); },
				}
			});
		</script>";
}

function orz_view_c3chart4array($input,$input_name="line",$chart_name=null){     //file hoped to be file or array or str 
        global $orz_view_c3chart_chart_num;     //differrent chart should have different name;
        if($chart_name==null)$chart_name="orz_c3chart".(microtime(true)*10000)."".(++$orz_view_c3chart_chart_num);
        $line_arr=Array();
	$x_arr=Array();
        $line_str="";
	$x_str="";
	while(list($key,$val)= each($input)) {
		array_push($x_arr,$key);
		array_push($line_arr,$val);
	}
	$x_str=join(",",$x_arr);
	$line_str=join(",",$line_arr);
		

        echo "  <div id='$chart_name' style=''>loading-chart</div>
                <script>
                        var chart = c3.generate({
                                bindto: '#$chart_name',
                                data: {
					x:'x',
                                        columns: [
						['x',$x_str],
                                                ['$input_name',$line_str],
                                        ],
                                        onclick: function (d, element) { console.log('onclick', d, element); },
                                        onmouseover: function (d) { console.log('onmouseover', d); },
                                        onmouseout: function (d) { console.log('onmouseout', d); },
                                }
                        });
                </script>\n";
}
//orz_view_c3chart("../data/temp_test");
?>
