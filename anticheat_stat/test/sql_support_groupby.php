<?php

function convert_sql4eval($sql,$line="line"){
	global $table_format;
	$sql_arr=explode(",",$sql);
	$r=Array();
	$format=Array();
	while(list($k,$v)=each($sql_arr)){
		array_push($r,sprintf("\$".$line."[%s]",$table_format[$v]));
		array_push($format,"%s");
	}
	$s=join(",",$r);
	$fs=join("\t",$format);
	return $s;
}
function convert_key4eval($key,$line="line"){
	global $table_format;
	$key_arr=explode(",",$sql);
	$r=	
}

class data_item{
	var $v;
	function __construct(){
		$v=null;
	}
	function update($expr,$line){
		$eval_valstr=convert_sql4eval($expr);
		eval("\$this->v=$eval_valstr;");
		//print "data_item:".$this->v."\n";
	}
}

class data_stat{
	var $data_item_list;
	function __construct(){
		$this->data_item_list=array();
	}
	function update($select,$line){
		$select_array=explode(",",rtrim($select));
		while(list($k,$v)=each($select_array)){
			//print "$k,$v\n";
			if(!isset($this->data_item_list[$v])){
				$this->new_item($v);
			}
			$this->data_item_list[$v]->update($v,$line);
		}
	}
	function new_item($item){
		$this->data_item_list[$item]=new data_item();
	}
	function out(){
		$o=array();
		while(list($k,$v)=each($this->data_item_list)){
			//print "=>$k,".$v->v."\n";
			array_push($o,$v->v);
		}		
		return join("\t",$o);
	}

}
class data_stat_key_list{
	var $key_list;	

	function __construct() {
		$this->key_list=array();
	}

	function new_key($k){
		$this->key_list[$k]=new data_stat();
	}

	function update($k,$select,$line){
		if(!isset($this->key_list[$k])){		
			$this->new_key($k);
		}
		$this->key_list[$k]->update($select,$line);
	}
	function out(){
		while(list($k,$v)=each($this->key_list)){
			print "$k\t".$v->out()."\n";
		}
	}
}

function run_sql($key,$select,$from_file){
	$file=fopen($from_file,"r");
	$c=0;
	$kl=new data_stat_key_list();
	while(!feof($file)){
		$line=explode("\t",rtrim(fgets($file)));
		if(count($line)<9)continue;
		if(++$c>10)break;
		$k=null;
		$eval_valstr=convert_sql4eval($key);
		print "===========".$eval_valstr."\n";
		eval("\$k=$eval_valstr;");
		//print $k."\n";
		$kl->update($k,$select,$line);
	}
	$kl->out();
}

$table_format=array("domain"=>0,"ip"=>9);
//$simple_sql="select ip from ../data/temp group by domain";
//echo $simple_sql."\n";
$select ="ip,domain";
$key="domain,ip";
$from_file="../data/temp";

print "select $select\t map to:".convert_sql4eval($select)."\n";
print "group by $key\t map to:".convert_sql4eval($key)."\n";

run_sql($key,$select,$from_file);
?>
