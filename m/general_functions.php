<?php

if(!function_exists('gf')){
	function gf(){}

	function gf_generateRandomString($length = 20) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function gf_convert_date($val,$action,$blank_sub=''){
		if(in_array($val,array('','00-00-0000','0000-00-00'))){
			if($blank_sub!='')
				return $blank_sub;
			elseif(($val=='') &&($action=='to_sql'))//prevent db errors
				return '0000-00-00';
			else
				return '';
		}
		$v = explode("-",str_replace("/","-",$val));
		switch($action){
			case 'to_sql':

				return $v[2]."-".$v[0]."-".$v[1];
				break;
			case 'to_us':
				return $v[1]."/".$v[2]."/".$v[0];
				break;
			case 'to_ts':
				return strtotime($v[0]."-".$v[1]."-".$v[2]);
				break;
		}
	}

    function define_once($constant, $value){
        if (!defined($constant)) {
            define($constant, $value);
        }
    }

	function parseJSONPost($jsonpost,$o=[]){
		$ary_temp=json_decode($jsonpost,true);
		// print_r($ary_temp);exit;
		$out_ary=[];
	
		foreach($ary_temp as $k=>$v){
			if(!isset($out_ary[$v['name']])){ //first time accessing
				$out_ary[$v['name']]=[$v['value']];
			}
			else{//most likely an array, 
				$out_ary[$v['name']][]=$v['value'];//set sting value as 0 element
			}
			//if(str_contains($v['name'],"[]"))
			
		}
		foreach($out_ary as $c=>$a){
			if(count($a)==1 && !str_ends_with($c,'[]'))
				$out_ary[$c]=$a[0];
			if(str_ends_with($c,'[]')){
				$out_ary[trim($c,'[]')]=$a;
				unset($out_ary[$c]);
			}
		}
		// print_r($out_ary);exit;

		if(in_Array('subGroup',$o)){
			$b=[];
			
			foreach($out_ary as $nm=>$a){
				$ct=-1;
				for($x=0;$x<sizeof($a);$x++){
					$ct++;
					if(!isset($b[$ct]))
						$b[$ct]=[];
					$b[$ct][$nm] = $out_ary[$nm][$ct];
					
				}
				
			}
			
			return $b;
		}
		else
			return $out_ary;
	}

	function validateEmail($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			return false;
		return true;
	}
	function parseQueryString($str) { 
		$op = array(); 
		$pairs = explode("&", $str); 
		foreach ($pairs as $pair) { 
			list($k, $v) = array_map("urldecode", explode("=", $pair)); 
			$op[$k] = $v; 
		} 
		return $op; 
	}

	function sterilize_fld($v='',$opt=[]){
		switch($opt['ret']??''){			
			case 'basic':
			case '':
				return htmlentities($v);
				break;
		}
	}

}
?>