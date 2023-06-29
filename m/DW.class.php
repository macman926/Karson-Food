<?php

	class DW extends DBConn{
		public $DWO;
		public $dwcExt="dwcontrol";
		public $URLIntegration_debug_ary=[];
		public $ch;
		public $cookies;
		public function __construct(){
			$this->DWO=new stdClass();
			include($_SERVER['DOCUMENT_ROOT'].'/inc/dw_settings.php');

			foreach($CNFG_DW as  $k=>$v){
				$this->DWO->{$k}=$v;
			}
		}

		public function build_dwc($o,$fileData=[],$reqDataAry=[]){
			/**
			 * o
			 * 		c
			 * 
			 * fileData
			 * 		fpi=>{file pathinfo()}
			 * 		custom_path -usually ./[fn]
			 * 		rec_dir
			 * 		temp_dir
			 * 
			 * reqDataAry
			 * 
			 */
			$arytoReplace=$ary_find=$ary_replace=[];
			$DWdocFilename=$filedataz='';
			
			$arytoReplace['^DOCTYPE^']=$fileData['doctype']??'';
			switch($o['c']??''){
				case 'temp':
					$arytoReplace['^FILEPATH^']=htmlentities($fileData['custom_path'])??'';
					$arytoReplace['^CABINET^']=$this->DWO->cabinet??'';
					$DWdocFilename=$fileData['fpi']['filename'].".".($this->dwcExt);
					break;
				case 'temp_to_rec'://replace values that were set as placeholders, like requisition #
					// $arytoReplace['^REQNUM^']=$reqDataAry['id']??'';
					$arytoReplace['^DBFIELDS^']=$this->create_dwc_index_fields_xml($reqDataAry,'file')??'';
					$arytoReplace['^ADDTFIELDS^']='';
					unset($reqDataAry['id']);
					$replace_file_Data=true;
					break;
				case 'rec':
					$arytoReplace['^FILEPATH^']=htmlentities($fileData['custom_path'])??'';
					$arytoReplace['^CABINET^']=$this->DWO->cabinet??'';
					$arytoReplace['^ADDTFIELDS^']='';
					$arytoReplace['^DBFIELDS^']=$this->create_dwc_index_fields_xml($reqDataAry,($o['upload_type']??'file'))??'';
					$DWdocFilename=$fileData['fpi']['filename'].".".($this->dwcExt);
					break;
				default:
					break;
			}

			foreach($arytoReplace as $tag=>$val){
				$ary_find[]=$tag;
				$ary_replace[]=$val;
			}

			if($replace_file_Data??false){//xfer from temp to watch folder file already exists, open, collect data, replace

				if(is_dir($fileData['temp_dir'])){
					$dir = new DirectoryIterator($fileData['temp_dir']);
					foreach ($dir as $fileinfo) {
						if ( !$fileinfo->isDot() && $fileinfo->getExtension()==$this->dwcExt) {
							$fnn=$fileData['temp_dir']."/".($fileinfo->getFilename());
							$filedataz=file_get_contents($fnn);
							$filedataz=str_replace($ary_find,$ary_replace,$filedataz);
							fopen($fnn,'w');
							file_put_contents($fnn,$filedataz);
						}
					}

					// rename($fileData['temp_dir'],$fileData['rec_dir']);
					$dir = new DirectoryIterator($fileData['temp_dir']);
					foreach ($dir as $fileinfo) {
						if ( !$fileinfo->isDot() ){
							$fn = ($fileinfo->getFilename());
							rename($fileData['temp_dir']."/{$fn}", $fileData['rec_dir']."/{$fn}" );
						}
					}
					rmdir($fileData['temp_dir']);
					
					
				}
			}
			else{ //write to temp loc or write to watch folder
				$filedata=$this->DWO->dwcontrol_file_template;
					// echo '<pre>'.$DWdocFilename.print_r($ary_find,1).print_r($ary_replace,1);exit;
					$filedata=str_replace($ary_find,$ary_replace,$filedata);
					
					//create doc
					fopen($fileData['fpi']['dirname']."/".$DWdocFilename,'w');
					file_put_contents($fileData['fpi']['dirname']."/".$DWdocFilename,$filedata);
			}
		}

		public function create_dwc_index_fields_xml($fldAry,$type){
			$str="";
			// foreach($fldAry as $fld=>$val){
			// 	if(isset($this->DWO->db_field_conversion[$fld])){
			// 		$str.="\r\n\t\t\t\t\t<Field dbName=\"".($this->DWO->db_field_conversion[$fld]['DW_fieldName'])."\" type=\"".($this->DWO->db_field_conversion[$fld]['DW_dbType'])."\" value=\"".(htmlentities($val)??'')."\" />";
			// 	}
			// }

			// die($type);
			foreach($this->DWO->dwc_fields_by_type[$type] as $fld){
				if(isset($fldAry[$fld])){
					$str.="\r\n\t\t\t\t\t<Field "
						."dbName=\"".($this->DWO->db_field_conversion[$fld]['DW_fieldName'])."\" "
						."type=\"".($this->DWO->db_field_conversion[$fld]['DW_dbType'])."\" "
						."value=\"".(htmlentities($fldAry[$fld])??'')."\" "
						." />";
				}
			}
			return $str;
		}

		public function formatURLTemplate($a=[]){
			/*
				a[]
					template ""
					args []
					lc ""
			 */
			$dwArgs=[
				'lc'			=>$a['lc'],
				'sed'			=>($this->DWO->URL_integration['sed']??''),
				'q'				=>'',
				'tw'			=>($this->DWO->URL_integration['tw']??''),
				//rl
				'p'				=>($this->DWO->URL_integration['p']??''),
				'fc'			=>($this->DWO->URL_integration['fc']??''),
				'displayOneDoc'	=>($this->DWO->URL_integration['displayOneDoc']??''),
				'queryInInvariantCulture'=>($this->DWO->URL_integration['queryInInvariantCulture']??''),
			];
			if(isset($this->DWO->URL_integration)){
				if(isset($this->DWO->URL_integration['templates'][$a['template']]['query'])){
					$dwArgs['q']=base64_encode(vsprintf($this->DWO->URL_integration['templates'][$a['template']]['query'],$a['args']));
				}
			}
			
			//overwrites
			foreach($dwArgs as $k=>$t){
				if(isset($this->DWO->URL_integration['templates'][$a['template']][$k]))
					$dwArgs[$k]=$this->DWO->URL_integration['templates'][$a['template']][$k];
			}
			foreach($dwArgs as $k=>$t)
				$dwArgs[$k]=$k."=".$t;
			$this->URLIntegration_debug_ary[]=$dwArgs;
			
			// die("<pre>".print_r($dwArgs,1));
			
			$params =implode('&',$dwArgs);
			return $params;
		}

		public function launchURLIntegration($a=[]){			
			/*
				a []
					template ""
					queryArgs []
					debug bool
					action
			 */
			$this->URLIntegration_debug_ary[]="::launchURLIntegration() args :: ".print_r($a,1);


			$key=utf8_encode($this->DWO->URL_integration['passphrase']);
			$passphrase = hash('sha512', $key,true);
			$encryption_key  = substr($passphrase,0,32);
			$iv = substr($passphrase,32,16);
			
			list($un,$pw)= $this->getDWUserPass();			
			// $lc ="User={$un}\nPwd={$pw}";
			$lc ='User='.$un.'\nPwd='.$pw;

			$this->URLIntegration_debug_ary[]="key: {$key}";
			$this->URLIntegration_debug_ary[]="Passphrase hash: {$this->DWO->URL_integration['passphrase']} => {$passphrase}";
			$this->URLIntegration_debug_ary[]="encryption_key: {$encryption_key}";
			$this->URLIntegration_debug_ary[]="IV: {$iv}";
			$this->URLIntegration_debug_ary[]="LC :: {$lc}";
			
			$lc = $this->convertToUrlTokenFormat(base64_encode($lc));
			$paramStr = $this->formatURLTemplate(
						a:[
						'template'=>$a['template'],
						'args'=>$a['queryArgs'],
						'lc'=>$lc,
						]
				);
			//$paramStr='lc=VXNlcj1kd3NlcnZpY2VcblB3ZD1kdWNrZjMzdA2&q=[REC_ID]=31&fc=e23d9d8c-1dab-4629-b776-bf12c7634323&sed=379323e1-6b3e-4a19-919a-b5d8f49b1518&p=RLV&displayOneDoc=True&tw=Accounts Payable&queryInInvariantCulture=False';
			$encrypted = openssl_encrypt($paramStr, 'aes-256-cbc', $encryption_key, OPENSSL_RAW_DATA, $iv);
			$encryptedbase64 = $this->convertToUrlTokenFormat(base64_encode($encrypted));
			
			
			$this->URLIntegration_debug_ary[]="final unencrypted params::".print_r($paramStr,1);				
			$this->URLIntegration_debug_ary[]="Encrypted: {$encrypted}";
			$this->URLIntegration_debug_ary[]="EncryptedBase64: {$encryptedbase64}";

			$url=$this->DWO->URL_integration['base_url'].$this->DWO->URL_integration['integration_path'].'ep='.$encryptedbase64;
			$this->URLIntegration_debug_ary[]="URL:: <a href='{$url}' target=_blank >{$url}</a>";
			if( isset($a['debug']) && $a['debug']==true ){
				die("<pre>".print_r($this->URLIntegration_debug_ary,1));
			}else{
				switch($a['action']??''){
					case 'return_debug':
						return $this->URLIntegration_debug_ary;
						break;
					case 'return_url':
						return $url;
						break;
					case '':
						header("location: {$url}");
						exit;
						break;
				}
			}
		}

		public function getDWUserPass(){
			if(isset($this->DWO->URL_integration['round_robin']) && $this->DWO->URL_integration['round_robin']==true){

			}
			else
				return[$this->DWO->svcAcctUN,$this->DWO->svcAcctPW];
		}

		public function convertToUrlTokenFormat($val){
			$padding = substr_count($val, '=');
			$val = str_replace('=', '', $val);
			$val .= $padding;
			$val = str_replace('+', '-', str_replace('/', '_', $val));
			return $val;
		}
	
		//API
		public function apiOperation($op,$a=[]){
			switch($op){
				case 'Logon':
					$this->ch = curl_init();
					curl_setopt_array($this->ch, [
						CURLOPT_URL => $this->DWO->base_url.'/Docuware/Platform/Account/Logon',
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_HEADER => 1,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,

						CURLOPT_VERBOSE => true,
						CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
						CURLOPT_SSL_VERIFYPEER => false,

						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => vsprintf('UserName=%s&Password=%s',[$this->DWO->svcAcctUN,$this->DWO->svcAcctPW]),
						CURLOPT_HTTPHEADER => [
							'Content-Type: application/x-www-form-urlencoded'
							],
					]);
					
					$response = curl_exec($this->ch);
					//$info = curl_getinfo($this->ch);
					// echo '<pre>';print_r($info);echo '</pre>';
					// echo $response;
					// exit;
					if($response=='')
						return false;
					preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
					$cookies = [];
					foreach($matches[1] as $item) {
						parse_str($item, $cookie);
						$cookies= array_merge($cookies, $cookie);
					}
					$this->cookies = $cookies;
					// die(print_r($this->cookies));
					if(count($this->cookies)<1 || !isset($this->cookies['_DWPLATFORMAUTH']))
						return false;
					
					
					return $this;					
					break;
				case 'getOrders':
					$cookies_str=vsprintf('.DWPLATFORMAUTH=%s; ',[$this->cookies['_DWPLATFORMAUTH']]);
					curl_setopt_array($this->ch, [
						// CURLOPT_URL => 'https://karson-foods.docuware.cloud:443/Docuware/Platform/FileCabinets/c867c8ce-bfed-4912-becc-8b8cf6a0232f/Documents',
						CURLOPT_URL => $this->DWO->base_url.'/DocuWare/Search/FileCabinets/'.$this->DWO->file_cabinet_guid.'/Documents?format=json',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'GET',
						CURLOPT_HTTPHEADER => [
							'Accept: application/json',
							'Accept-Encoding: gzip, deflate',
							'Cookie: '.$cookies_str
							],
						]
					);
					$response = curl_exec($this->ch);
					$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
					$header = substr($response, 0, $header_size);
					$body = substr($response, $header_size);
					$ary=json_decode($body,true);					
					
					// die("<pre>".print_r($ary,1));
					$r=[];
					if( isset($ary['Items']) && is_array($ary['Items']) ){
						foreach($ary['Items'] as $ct=>$xa){
							$tempRet=$this->formatapiItemsAry(o:'',a:$xa['Fields']);
							if(is_Array($tempRet))
								$r[]=$tempRet;
						}
					}
					else{//no records
						
					}
					return $r;
					break;
				case 'setIndexValue':
						/**
						 	a[
								docID
								flds[
									[fld=>,val=>,datatyp=>e]
								]
							]

						 */
						$fldJSONAry=json_encode($a['flds']);
						$cookies_str=vsprintf('.DWPLATFORMAUTH=%s; ',[$this->cookies['_DWPLATFORMAUTH']]);
						$url=$this->DWO->base_url.'/DocuWare/Platform/FileCabinets/'.$this->DWO->file_cabinet_guid.'/Documents/'.$a['docID'].'/Fields';
						// die($url);

						curl_setopt_array($this->ch, array(
							CURLOPT_URL => $url,						  
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'PUT',
							CURLOPT_POSTFIELDS =>'{
							  "Field":'.($fldJSONAry).'
						  		}',
							CURLOPT_HTTPHEADER => array(
							  'Accept: application/xml',
							  'Content-Type: application/json',
							  'Cookie: '.$cookies_str
							),
						  ));
						  $response = curl_exec($this->ch);						  
						  if(curl_getinfo($this->ch,CURLINFO_HTTP_CODE ) !=200)
						  	return false;
						return true;

						break;
				case 'closeCH':
					curl_close($this->ch);					
					break;
			}//switch
		}//fn
		public function formatapiItemsAry($o,$a=[]){
			switch($o??''){
				case '':
					$aa=[];
					// die("<pre>".print_r($a,1));
					foreach($a as $ct=>$b){						
						$aa[$b['FieldName']]=$b['Item']??'';
						if($b['FieldName']=='MONTH')
							$aa[$b['FieldName']]=explode("-",$aa['MONTH'])[0];
					}
					//die("<pre>".print_r($aa,1));

					if(
						(!in_array($aa['DOCUMENT_TYPE'],['Order Builder']))
						||(in_array($aa['STATUS'],['Imported']))
						)
						return false;
					
					$ab=['dates'=>[]];
					foreach($aa as $lbl=>$b){
						if(strpos($lbl,'ITEM')===0){
							
							$t=$aa['YEAR']."-".$aa['MONTH']."-".str_pad(explode("_",$lbl)[1],2,'0',STR_PAD_LEFT);
	 						if( !isset($ab['dates'][$t]) ){$ab['dates'][$t]=[];}
							if($b!='')
								array_push($ab['dates'][$t],$b);
							
							
							
						}
						else
							$ab[$lbl]=$this->convertFields($lbl,$b);
					}
	
					unset($ab['DWSECTIONCOUNT']
							,$ab['DWEXTENSION']
							,$ab['DWDOCSIZE']
							,$ab['DWPAGECOUNT']
							,$ab['DWDISK']
							,$ab['DWDISKNO']
							,$ab['DOCUMENT_TYPE']
							// ,$ab['MONTH']
							// ,$ab['YEAR']
							,$ab['REC_ID']//DWDOCID placeholder
							,$ab['ORDER_ID']//order pk placeholder
							,$ab['@CABINETNAME']
							);
		

					// die("<pre>".print_r($ab,1));
					return $ab;
					break;
			}
		}

		public function convertFields($f,$v){
			switch($f){
				case 'DWSTOREDATETIME':
				case 'DWMODDATETIME':
				case 'DWLASTACCESSDATETIME':
				case 'DATE_CREATED':
					// /Date(1682899200000)/

					return date('Y-m-d H:i:s',substr($v,6,10));
					break;
				default:
					return $v;
					break;
				
			}
		}
	
	}//class


	// $x=new DW();
	// echo '<pre>'.print_r($x->DWO,1);
	// echo $x->DWO->cabinet;



?>