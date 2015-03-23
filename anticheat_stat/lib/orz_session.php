<?php
class orz_Session     //kv:keyvalue
{
	//parse format:domian  t       r       sgsa_id ua      ds      ui      pf      ol
	var $domain;
	var $pv;	//访问页面数量
	var $click_num;	//点击数量
	var $ad_click_num;	//广告点击数量
	var $stime;	//开始访问时间
	var $etime;	//最终访问时间

	var $refer;	//入口页面
	var $se_word;	//如果来自搜索引擎则查询词为search-engine-word
	var $leave_page;	//离开网站的所在页面
	var $ip;	//访问所在的ip
	var $ua;
	var $ds;
	var $ui;
	var $pf;
	var $ol;
	var $ml;

	function __construct(){
		$this->domain="";
		$this->pv=0;
		$this->click_num=0;
		$this->ad_click_num=0;
		$this->stime=0;
		$this->etime=0;

		$this->refer="-";
		$this->se_word="";
		$this->leave_page="-";
		$this->ip="";
		$this->ua="";
		$this->ds="";
		$this->ui="";
		$this->pf="";
		$this->ol="";
	}
}

class orz_Session_list{
	var $Session_list;
	var $file_output;
	var $title_str="domian\tstime\trefer\tsgsa_id\tua\tds\tui\tpf\tol\tetime\tpv\tclick_num\tad_click_num\tse_word\tleave_page\tip\tavg_ml\n";
	function __construct($file_name=null) {
		$this->Session_list=array();
		$this->file_out=null;
		if($file_name!=null){
			$this->file_output=fopen($file_name,"w") or die("failed to open output_file");
			fwrite($this->file_output,$this->title_str);
		}else{
			print $this->title_str;
		}
	}
	function get_host($url){
		$s=parse_url($url);
		if(isset($s["host"])){
			return $s["host"];
		}else{
			return $url;	
		}
	}

	function add_item($key,$line){  //$key is orz_statkeyvalue
		if(count($line)<9)return 0;
		if(isset($this->Session_list[$key])){
			$v=$this->Session_list[$key];
			$etime=$line[1];
			if($etime>$v->stime+1800000 || strpos($line[2],$line[0])==false){//时间超过30分钟，则本session结束，开始计算新session,1800000毫秒
				//$this->print_session($key);
				if($this->file_output==null){
					print $this->session_str($key);
				}else{
					fwrite($this->file_output,$this->session_str($key));	
				}
				//new session
				$this->Session_list[$key]=new orz_Session();
				$v=$this->Session_list[$key];
				if($line[10]=="pb_pv"){
					$v->pv=1;
				}else{
					$v->click_num=1;
					$v->ml=$line[11];
				}
				$v->refer=$this->get_host($line[2]);
				$v->leave_page=$line[2];        //应计算当前页面，而非refer
				$v->domain=$line[0];
				$v->ua=$line[4];
				$v->ds=$line[5];
				$v->ui=$line[6];
				$v->pf=$line[7];
				$v->ol=$line[8];
				$v->stime=$line[1];
				$v->etime=$line[1];
				$v->ip=$line[9];
			}else{
				if($line[10]=="pb_pv"){
					$v->pv+=1;
				}else{
					$v->click_num+=1;
					$v->ml=$line[11];
				}
				$v->etime=$etime;
				$v->leave_page=$line[2];	//应计算当前页面，而非refer		
			}
		}else{
			//line=domiani 0  t 1       r 2      sgsa_id 3 ua 4     ds 5     ui6      pf7      ol8 ip 9 n 10 ml 11 
			//print_r($line);
			$this->Session_list[$key]=new orz_Session();
			$v=$this->Session_list[$key];
			if($line[10]=="pb_pv"){
				$v->pv=1;
			}else{
				$v->click_num=1;
				$v->ml=$line[11];
			}
			$v->refer=$this->get_host($line[2]);
			$v->leave_page=$line[2];	//应计算当前页面，而非refer
			$v->domain=$line[0];
			$v->ua=$line[4];
			$v->ds=$line[5];
			$v->ui=$line[6];
			$v->pf=$line[7];
			$v->ol=$line[8];
			$v->stime=$line[1];
			$v->etime=$line[1];
			$v->ip=$line[9];
		}
		return 1;
	}

	function session_str($k){
		$v=$this->Session_list[$k];
		$sgsa_id=$k;
		$avg_ml=0;
		if($v->click_num>0)$avg_ml=$v->ml/($v->click_num);
		//domian 0 ,stime 1,refer 2,sgsa_id 3,ua 4,ds 5, ui 6,pf 7,ol 8,etime 9,
		//pv 10,click_num 11,ad_click_num 12,se_word 13,leave_page 14,ip 15,avg_ml 16
		return sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n"
				,$v->domain,$v->stime,$v->refer,$sgsa_id,$v->ua,$v->ds,$v->ui,$v->pf,$v->ol
				,$v->etime,$v->pv,$v->click_num,$v->ad_click_num,$v->se_word,$v->leave_page,$v->ip,$avg_ml);
	}


	function print_all(){
		while(list($k,$v)=each($this->Session_list)){
			print $this->session_str($k);

		}
	}

	function write_all(){
		while(list($k,$v)=each($this->Session_list)){
			fwrite($this->file_output,$this->session_str($k));
		}
		fclose($this->file_output);
	}
	function output(){
		if($this->file_output==null){
			$this->print_all();
		}else{
			$this->write_all();
		}
	}
}
?>
