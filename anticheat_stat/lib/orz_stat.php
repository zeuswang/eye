<?php
class orz_stat_item     //kv:keyvalue
{
	var $pv;
	var $uv;
	var $session_num;
	var $old_user_num;
	var $click_num;
	var $ml;
	var $uid_list;
	function __construct(){
		$this->pv=0;
		$this->uv=0;
		$this->session_num=0;
		$this->old_user_num=0;
		$this->click_num=0;
		$this->ml=0;
		$this->uid_list=array();
	}
}

class orz_stat_keylist{
	var $key_list;

	function __construct() {
		//$this->pv=0;
		//$this->uv=0;
		$this->key_list=array();
	}
	function fillter(){
		if(1){
			return true;
		}else{

			return false;
		}
	}	

	function add_item($key,$line){  //$key is orz_statkeyvalue
		//if($this->fillter()==false)return 0;
		$cookie=explode("|",$line[3]);
		$cookie_time=date("Ymd",$cookie[1]/1000/1000);	
		$visit_time=date("Ymd",$line[1]/1000);
		//print  $cookie_time."|".$visit_time."\n";
		if(isset($this->key_list[$key])){
			$this->key_list[$key]->pv+=intval($line[10]);
			$this->key_list[$key]->session_num+=1;
			$this->key_list[$key]->uid_list[$line[3]]=1;
			if($visit_time >$cookie_time)
				$this->key_list[$key]->old_user_num+=1;
			$this->key_list[$key]->click_num+=$line[11];
			$this->key_list[$key]->ml+=$line[16];
		}else{
			$this->key_list[$key]=new orz_stat_item();
			$this->key_list[$key]->pv=intval($line[10]);
			$this->key_list[$key]->session_num=1;
			$this->key_list[$key]->uid_list[$line[3]]=1;
			if($visit_time >$cookie_time)
				$this->key_list[$key]->old_user_num=1;
			$this->key_list[$key]->click_num=$line[11];
			$this->key_list[$key]->ml=$line[16];
		}
		return 1;
	}

	function printall(){
		while(list($k,$v)=each($this->key_list)){
			//print gettype($k)."\t".gettype($v)."\n";
			$v->uv=count($v->uid_list);
			$avg_ml=0;
			if($v->click_num>0)$avg=$v->ml/$v->click_num;
			printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\n",$k,$v->pv,$v->uv,$v->session_num,$v->old_user_num,$v->click_num,$avg_ml);
		}
	}
	function write($filename,$head){
		$file=fopen($filename,"w");
		fwrite($file,$head);
		while(list($k,$v)=each($this->key_list)){
			$v->uv=count($v->uid_list);
			 $avg_ml=0;
                        if($v->click_num>0)$avg=$v->ml/$v->click_num;
			fwrite($file,sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\n",$k,$v->pv,$v->uv,$v->session_num,$v->old_user_num,$v->click_num,$avg_ml));
		}
		fclose($file);
	}
}
?>
