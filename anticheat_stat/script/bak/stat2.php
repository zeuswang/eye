<?php
        class orz_stat_item	//kv:keyvalue
	{
		var $pv;
		var $uv;
		var $uidList;
		function __construct(){
			$this->pv=0;
			$this->uv=0;
			$this->uidList=array();
		}

		function up_pv($m){		$this->pv+=$m;}
		function init_pv($m){		$this->pv=$m;}
		function init_uidList($u,$m){	$this->uidList[$u]=$m;}
                function up_uidList($u,$m){	$this->uidList[$u]=$m;}
			

        }

	class orz_stat_keylist{
		var $key_list;

		function __construct() {
			$this->key_list=array();
		}

		function add_item($key,$line){	//$key is orz_statkeyvalue
              		if(isset($this->key_list[$key])){
				$v=$this->key_list[$key];
                        	$v->up_pv(1);
                        	$v->up_uidList($line[3],1);
			}else{
                        	$this->key_list[$key]=new orz_stat_item();
				$v=$this->key_list[$key];

                        	$v->init_pv(1);
                        	$v->init_uidList($line[3],1);
                	}
		}

		function printall(){
			while(list($k,$v)=each($this->key_list)){
				$v->uv=count($v->uidList);
       			        printf("%s\t%s\t%s<br>\n",$k,$v->pv,$v->uv);
       			}
		}

	}

	$kl=new orz_stat_keylist();
	$key=NULL;
	$file=fopen("../data/temp","r");
	while(!feof($file)){
		$line=explode("\t",fgets($file));	
		$kl->add_item($line[0],$line);
	}
	$kl->printall();

?>
