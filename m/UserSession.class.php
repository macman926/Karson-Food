<?php
	
	class UserSession extends DBConn{
		private $dbConn=null;
		
		private $userSessionData=null;
		public $userSessionHash=null;
		public $userSessionUserID=null;//used for comparing to the one in session data, for checks on impersonating
		public $userSessionCreationTime=null;
		public $userSessionLAstActiveTime=null;
		public $role_shorthand_key='roles_short';
			// private int $GLB_session_timeout_secs=60; //test
			// private int $GLB_session_timeout_secs_warning=30; //test
		private int $GLB_session_timeout_secs=2700; //45 mins
		private int $GLB_session_timeout_secs_warning=2400; //40 mins
		
		public $userPermissionShortnameAry=null;
		public $userPermissionFullAry=null;



		public function __construct($conn=null,$opts=[]){
			// if(is_null(parent::$conn))
				// parent::connect();
			
		} // <__construct
		
		public function logIn($un,$pw,$c=[]){
			//returns hash if good, error code if not
			
			include $_SERVER['DOCUMENT_ROOT']."/inc/db_settings.php";
			require $_SERVER['DOCUMENT_ROOT']."/inc/general_settings.php";			
			switch(CNFG__login_method){
				
				case 'database_user_entry':
					$sql="select u.*, sr.role_name, sr.lock_level 
						from ".DBTBL__user." u
							left join ".DBTBL__site_roles." sr on u.site_role_id=sr.id
						where 
							u.username=:u limit 1";
					$usr=$this->ex($sql,['u'=>$un],['single','ret_false']);
					if(is_Array($usr)){//has user
						if(
							$this->logIn_verify_password(
								pw:substr_replace($salts[$usr['salt']]['v'],$pw,$salts[$usr['salt']]['b'],0),
								stored_pw:$usr['password'],
							)
						){
								$this->destroySessionRec('user_id',$usr['id']);
								$temp_SessObj=$this->buildSessionObj($usr);
								$this->userSessionHash = $this->createSessionRec($usr['id'],json_encode($temp_SessObj),'');
								
								return $this->userSessionHash;
						}
						else{//pw doesnt match
							return 98;
						}
					}
					else{//no user rec
						return 97;
					}
					
				break;
			}
			return 50;
			
		}
		public function setSessionTableFld($c,$fld,$val){
			//user_session > session_data json
			switch($c['c']??''){
				case 'json_single':
				case '':
					
					$this->userSessionData->$fld=$val;


					$this->updateSessionDBrecByFldAry(
						queryByAry:[['fld'=>'hash','val'=>$this->userSessionHash]],
						 updAry:[
								 ['fld'=>'session_data','val'=>json_encode($this->userSessionData)],
							 ] 
					);
					break;
			}
		}
		public function buildSessionObj($userRec=[]){
			$sessionDataObj=new StdClass();
			// print_r($userRec);exit;
			if(empty($userRec))
				$userRec=(new User())->userLookup(['c'=>'by_id'],['id'=>$userRec['id']]);
			$sessionDataObj->sessionUsrID=$userRec['id'];
			$sessionDataObj->displayName=$userRec['fname']." ".$userRec['lname'];
			$sessionDataObj->roleName=$userRec['role_name'];
			$sessionDataObj->role_lock_level=$userRec['lock_level'];
			$sessionDataObj->primary_location=$userRec['primary_location'];
			$sessionDataObj->approver_level=$userRec['approver_level'];
			$sessionDataObj->force_pw_change=$userRec['change_pw'];
			$sessionDataObj->approvalRolePrograms=[];
			
			
			
			list($temp_userPermissionShortnameAry,$temp_userPermissionFullAry)=$this->getUsrPermissions(uid:$userRec['id']);
			$this->userPermissionShortnameAry = $sessionDataObj->userPermissionShortnameAry = $temp_userPermissionShortnameAry;
			$this->userPermissionFullAry = $sessionDataObj->userPermissionFullAry = $temp_userPermissionFullAry;
			
			// $sessionDataObj->roles=$this->get_user_roles(o:['c'=>'ret_ary'],uid:$userRec['id']);
			return $sessionDataObj;
		}
		public function getSessionFld($fld){
			// die(print_r($this->userSessionData));
			return $this->userSessionData->$fld??'';
		}
		public function impersonateUser($usrAry){
			$newUsrSessObj=$this->buildSessionObj($usrAry);
			// die('fffxxx'.print_r($newUsrSessObj));
			$newUsrSessObj->impersonate_source=$this->userSessionUserID;
			$this->updateSessionDBrecByFldAry(
				queryByAry:[['fld'=>'hash','val'=>$this->userSessionHash]],
			 	updAry:[
					 	['fld'=>'last_active','val'=>'now()','dbfunc'=>true],
					 	['fld'=>'session_data','val'=>json_encode($newUsrSessObj)],
				 	] 
			);
			json_decode($this->getSessionFromHash($this->userSessionHash,'impersonate_build'));
			unset($_SESSION['search']);
		}
		public function impersonate_reset($usrAry){
			//!!!!!!!!!! User::getUsers() case 'to_impersonate' must have the fields returned that repopulate the session_data table field
			$usrAry=$usrAry[0];
			// print_r($usrAry);exit;
			$temp_SessObj=$this->buildSessionObj($usrAry);
			$this->updateSessionDBrecByFldAry(
				queryByAry:[['fld'=>'hash','val'=>$this->userSessionHash]],
			 	updAry:[
					 	['fld'=>'last_active','val'=>'now()','dbfunc'=>true],
					 	['fld'=>'session_data','val'=>json_encode($temp_SessObj)],
				 	] 
			);			
			json_decode($this->getSessionFromHash($this->userSessionHash,'impersonate_build'));
			unset($_SESSION['search']);
		}
		public function logIn_verify_password($pw,$stored_pw):bool{
			/*
				pw_ary	['pw'=>'',''=>'stored_pw']
			*/
			if (password_verify($pw, $stored_pw))
				return true;
			return false;
		}
		public function logOut($hash,$error_code='',$exec_code=0){
			if($hash!='')
				$this->destroySessionRec('hash',$hash);
			unset($_SESSION);
			session_destroy();
			switch($exec_code){
				case 2://ajax call of a child window- redirect parent page, then child page(or child modal error with close option)
					break;
				case 1://ajax call- js window redirect
					echo "window.location='/?p=/login".($error_code!=''?'&ec='.$error_code:'')."';";
					exit;
					break;
				case 0://page header redirect
					header('location: /?p=/login'.($error_code!=''?'&ec='.$error_code:'') );
					break;
			}
			exit;
		}
		public function isLoggedIn($hash,$opt=[]){
			//get rec by hash
			// $ret=false;
			$ret=$this->getSessionFromHash($hash,'session_check')??false;
			if(!$ret && isset($_SESSION['h']) && $_SESSION['h']!=''){
				$hash=$_SESSION['h'];
				$ret=$this->getSessionFromHash($hash,'session_check');
			}
			if($ret!==true){
				$msgcode=$def_code=96;
				if(isset($opt['alt_code']))
					$msgcode=$opt['alt_code'];
				if(in_array('ajax_1',$opt))
					$this->logOut('',$msgcode,1);
				elseif(in_array('logout',$opt))
					$this->logOut('',$msgcode);
				else
					return false;
			}else{
				// echo time()." - ".strtotime($this->userSessionLAstActiveTime)." = ". (time() - strtotime($this->userSessionLAstActiveTime))."....".($this->GLB_session_timeout_secs);
				// exit;
				if(
					$this->userSessionLAstActiveTime!='' 
					&& (time()-strtotime($this->userSessionLAstActiveTime) <= $this->GLB_session_timeout_secs)
				){
					$_SESSION['h']=$hash;
					$this->updateSessionDBrecByFldAry(queryByAry:[['fld'=>'hash','val'=>$hash]], updAry:[['fld'=>'last_active','val'=>'now()','dbfunc'=>true]] );

					if((in_array('pwCheck',$opt)) && $this->getSessionFld('force_pw_change') == 'Y' && ($this->getSessionFld('impersonate_source') =='')){
						// field is set to yes and the account is not currently being impersonated
						$this->setSessionVar(c:[],var:'mc',val:'mdUCPpw');
						$this->navToPath(v:'ucp'.'&mc=mdUCPpw');
						exit;
					}
					return true;
				}
				else{//timed out
					$this->logOut($this->userSessionHash,96);
				}
			}
		}
		public function getSessionFromHash($hash,$opt=''){
			//get record
			$sql="select * from ".DBTBL__user_session." where hash=:h";
			$rows = $this->ex($sql,['h'=>$hash]);
			switch($opt){
				
				case 'ret_session':
					//return session data only					
					if(count($rows)===1){
						$this->userSessionHash=$rows[0]['hash'];
						return $rows[0]['session_data'];						
					}
					return false;
					break;
				case 'impersonate_build':
					if(count($rows)===1){
						// echo 'f';exit;
						$this->userSessionData=json_decode($rows[0]['session_data']);						
						$this->userSessionUserID=$this->userSessionData->sessionUsrID;
						$this->userSessionHash=$rows[0]['hash'];//keep
						$this->userSessionCreationTime=$rows[0]['creation_time'];//keep
						$this->userSessionLAstActiveTime=$rows[0]['last_active'];//keep
						return true;						
					}
					break;
				case 'get_activity_ts':
						/*
						0-good, 1=warning zone, 2=expired
						 */
					$sec_diff=999999999;
					if(count($rows)===1){
						$start = new DateTime($rows[0]['last_active']);
						$end = new DateTime(date("Y-m-d H:i:s"));
						$sec_diff= $end->getTimestamp() - $start->getTimestamp();
						if($sec_diff >=$this->GLB_session_timeout_secs)
						$x= 2;
						elseif($sec_diff >=$this->GLB_session_timeout_secs_warning)
						$x= 1;
						else
						$x= 0;
					}
					else{
						$x=2;
					}

					return [$x,$sec_diff];
					break;
				case 'session_check':
					if(count($rows)===1){
						$this->userSessionData=json_decode($rows[0]['session_data']);
						// $this->userSessionUserID=$rows[0]['user_id'];
						$this->userSessionUserID=$this->userSessionData->sessionUsrID;
						$this->userSessionHash=$rows[0]['hash'];						
						$this->userSessionCreationTime=$rows[0]['creation_time'];
						$this->userSessionLAstActiveTime=$rows[0]['last_active'];
						
						$this->userPermissionShortnameAry=$this->userSessionData->userPermissionShortnameAry;
						$this->userPermissionFullAry=$this->userSessionData->userPermissionFullAry;
						return true;						
					}
					return false;
					break;
			}
			
		}
		public function createSessionRec($uid,$data,$hash=''){
				
			if($hash=='')
				$hash=gf_generateRandomString();
			
			$sql="insert into ".DBTBL__user_session."(
				hash,
				user_id,
				session_data,
				creation_time,
				last_active,
				ip,
				ua,
				host
			)
			values(
					:hash,
					:uid,
					:sd,
					now(),
					now(),
					:ip,
					:ua,
					:host
			)
			on duplicate key update
				last_active=now(),
				session_data=:sd2
			";
			$ary=[
				'hash'	=>$hash,
				'uid' 	=>$uid,
				'sd'  	=>$data,
				'sd2' 	=>$data,
				'ip'	=> $_SERVER['REMOTE_ADDR'].":".$_SERVER['REMOTE_PORT'],
				'ua'	=> $_SERVER['HTTP_USER_AGENT'],
				'host'	=> gethostbyaddr($_SERVER['REMOTE_ADDR']),
			];
			
			$this->ex($sql,$ary,['c'=>'update']);
			return $hash;
		}
		public function updateSessionDBrecByFldAry($queryByAry,$updAry,$opt=[]){
			/*
				queryByAry
					fld
					val
					op (operator, optional, default to &&)
				updAry
					[
					fld
					val
					dbfunc optional(default to false. if true- take at exact value- dont wrap in quotes, dont use as pdo arg ary)
					]
					
			*/
			
			$sql_upda_args=$sql_upd_where_args=$pdo_Args=[];
			
			foreach($queryByAry as $z=>$x){
					$sql_upd_where_args[]=$x['fld']."=:"."w".$z;
					$pdo_Args["w".$z]=$x['val'];
			}
			foreach($updAry as $i=>$x){
				if(isset($x['dbfunc']) && $x['dbfunc']===true){
					$sql_upda_args[]=$x['fld']." = ".$x['val'];
				}
				else{
					$sql_upda_args[]=$x['fld']."=:"."u".$i;
					$pdo_Args["u".$i]=$x['val'];
				}
			}
			
			$sql="update ".DBTBL__user_session." 
				set
					".implode(", ",$sql_upda_args)."
				where "
				.implode(" && ",$sql_upd_where_args)
			;
			
			// echo $sql.print_r($pdo_Args,1);exit;
			$this->ex($sql,$pdo_Args,['c'=>'update']);
		}
		public function destroySessionRec($opt,$v){
			/*
				opt-hash,user_id
				v-string, hash|user_id
			*/
			
			switch($opt){
				case 'user_id':
				case 'hash':
					$sql="delete from ".DBTBL__user_session ." where ".$opt."=:v";
					$this->ex($sql,['v'=>$v],['c'=>'delete']);
					// exit;
					break;
			}
			

		}
		public function get_user_roles($o,$uid){

			// $outAry['GLOBAL']=[];
			// $outAry['GLOBAL_SHORT']=[];

					/*
Array
(
    [0] => Array
        (
            [id] => 1
            [role_name] => Site Admin
            [role_types] => GLOBAL,ACP
            [role_data] => 1
        )

    [1] => Array
        (
            [id] => 2
            [role_name] => Impersonate
            [role_types] => GLOBAL
            [role_data] => 1
        )

)
					 */
					// $outAry['GLOBAL'][]=['role_name'=>$role['role_name'],'val'=>$role['role_data']];
					// if(in_Array($role['role_data'],[true,'1']))
					// 	$outAry['GLOBAL_SHORT'][]=$role['role_name'];

					$short_key=$this->role_shorthand_key;
					$temp_ary=$temp_ary_short[$short_key]=$outAry=[];
					$sql="select * from ".DBTBL__roles." 
						where id in(select role_id from ".DBTBL__user_roles." where user_id=:uid)";
					// echo $sql;exit;
					$r=$this->ex($sql,['uid'=>$uid]);
					if(count($r)>0){
						// echo '<pre>'.print_r($r,1);exit;
						foreach($r as $role){
							$type_ary=[];
							if(str_contains($role['role_types'],",")){
								foreach(explode(",",$role['role_types']) as $type){
									$type_ary[]=$type;
								}
							}
							else
								$type_ary[]=$role['role_types'];

							foreach($type_ary as $tt){
								$temp_ary["{$tt}|{$role['role_name']}"]=$r;
								if(in_array($role['role_data'],[true,'1'])){
									if(!isset($temp_ary_short[$short_key][$tt]))
										$temp_ary_short[$short_key][$tt]=[];
									$temp_ary_short[$short_key][$tt][]=$role['role_name'];
								}
							}
							
						}//foreach role rec
						// echo "<pre>".print_r($temp_ary,1);
						// echo "<pre>".print_r($temp_ary_short,1);
						// exit;

					}//if count
			
			switch($o['c']){
				case 'ret_ary':
				case '':
				default:
					return array_merge($temp_ary,$temp_ary_short);
					break;
			}
		}
		public function authUser($v,$o=[]){
			/**
			 # 
			 	v
				o[]
					act
					code (modal code)

			 */
			$authd=false;
			 switch($v){
					case 'rec_list_filter__requestor__All_Opt':
						// if($this->isRole(['c'=>'SESSION','c2'=>'GLOBAL'],['SITE ADMIN']))
						if($this->isRolev2(c:[],a:['Site Admin']))
							$authd = true;
						break;
			 }

			 switch($o['act']??''){
					case 'redir_error':
						break;
					case 'redir_error_ajax':
						break;
					case '':
						return $authd;
						break;
			 }

		}
		public function isRolev2($c,$a){
			switch($c['c']??''){
				case 'standard':
				default:
					//check for string or array of roles
					/**
					 c[
					 	c main control
						must_have_all
						]
					 a[] or "" data
					 */

					if(gettype($a)=='string')$a=[$a];
					$myRole=$this->getSessionFld('roleName');
					$ct=0;
					foreach($a as $r){
						if($r==$myRole){
							$ct++;
						}
					}

					if($ct>0){
						return true;
					}
					return false;
					break;
			}
		}
		function isRole($c,$a,$b=[]){
			/**
				c
					SearchLoc defaults to 'SESSION'
					SearchType defaults to short
				a	[] or ""
			*/
			$searchLoc=$c['SearchLoc']??'SESSION';
			$searchType=$c['SearchType']??'SHORT_ARY';
			$ctrl=$searchLoc."|".$searchType;
			if($ctrl=='|')$ctrl='';
			switch($ctrl??''){
				case 'SESSION|SHORT_ARY':{ //+-
					if(gettype($a)=='string')$a=[$a];
					$roles=$this->userSessionData->roles->{$this->role_shorthand_key} ?? [];
					// echo '<pre>'.print_r($roles,1);exit;
					// echo '<pre>'.print_r($a,1);exit;
					foreach($a as $t1){
						$match_type=$match_role=false;
						list($type,$name_grp)=explode("|",$t1);
						$name_grp_ary=explode(",",$name_grp);
						$type_star=$name_star=false;
						if($type=='*'){$match_type=true;$type_star=true;}
						if($name_grp=='*'){$match_role=true;$name_star=true;}
						//********** */
						if(!$match_type && $match_role){
							if(
								( isset($roles->$type)  && is_Array($roles->$type)  && count($roles->$type)>0 )
								// || (($name_star==true) && ())
							){
								$match_type=true;
							}
						}
						// elseif($match_type && !$match_role && isset($roles->$type) && is_array($roles->$type)){ // *|...
						// 	foreach($roles->$type as $z){
						// 		if(in_array($z,$name_grp_ary))
						// 			$match_role=true;
						// 	}
						// }
						elseif($match_type && !$match_role){ // *|...
							
							$type2=[];
							if($type_star)
								foreach($roles as $s=>$zzzzzz)
									$type2[]=$s;
							elseif(isset($roles->$type) && is_array($roles->$type))
								$type2[]=$type;
							

							foreach($type2 as $t2){
								foreach($roles->$t2 as $z){
									if(in_array($z,$name_grp_ary))
										$match_role=true;
								}
							}
						}
						elseif(!$match_type && !$match_role && isset($roles->$type) && is_array($roles->$type)){// ...|*
							// echo '<pre>'.print_r($a,1);exit;
							foreach($roles->$type as $z){
								if(in_array($z,$name_grp_ary))
									$match_type=$match_role=true;
							}		
						}

						//********** */
						if($match_type && $match_role){
							return true;
						}
					}
					return false;
					} //+-
					break;
				case '':break;
			}
		}
		function isRoleArch($c,$a,$b=[]){
			/*
				c
					c SESSION
					c2 "" | GLOBAL
				a   "123;456"|[123,456]  
				b	[
						'auth' //moves back to home
					]

					
			*/

			$ctrl=$c['c']."|".$c['c2'];
			if($ctrl=='|')
				$ctrl='';
			switch($ctrl??''){
				case 'SESSION|GLOBAL':
					$roles=$this->userSessionData->roles->GLOBAL_SHORT ?? [];
					// print_r($roles);exit;
					if(is_Array($roles) && count($roles)>0){
						if(gettype($a)=='string')
							$a=explode(";",$a);
						foreach($a as $rl){//role being questioned
							if(in_array($rl,$roles))
								return true;
						}
					}
					//failed block
					if(in_Array('auth',$b))
						header('location: '. ($this->rerouteURL('home') ) );
					return false;			
					break;
				case 'being_impersonated':
					break;
			}			
			return false;//catchall
		}
		public function rerouteURL($pvar){
			return CNFG__SiteBaseURL."?p=".$this->userSessionHash."/".$pvar;
		}
		public function get_userSessionData(){
			return $this->userSessionData;
		}
		public function getUserInfo($v,$opt=''){
			
			switch($v){
				case 'impersonater':
					if(isset($this->userSessionData->impersonate_source))
						return $this->userSessionData->impersonate_source;
					return 0;
					break;
				case 'uid':
					return $this->userSessionUserID;
					// return $this->userSessionData->userID;
					break;
				case 'name':
					switch($opt){
						case 'nav1':
							// return 'NAV PLACEHOLDER';
							return $this->userSessionData->displayName;
							break;
						case '':
							return 'getName() DEF PLACEHOLDER';
							break;
					}
					break;
			}			
		}
		public function getHash(){
			return $this->userSessionHash;
		}
		public function retrieveHashFromSession(){
			//
		}
		public function goHome($addt_url=''){
			header('location: '.CNFG__SiteBaseURL."?p=".$this->userSessionHash."/home".$addt_url);
			exit;
		}
		public function navToPath($v,$o=[]){
			/*
				s2/s3...&x=xxxx
			 */

			 if(in_array('ajax',$o)){
				echo "window.location=' ".CNFG__SiteBaseURL."?p={$this->userSessionHash}"."/{$v}';";
				return;
			 }
			 elseif(in_array('js_Str',$o)){
				// echo "window.location=' ".CNFG__SiteBaseURL."?p={$this->userSessionHash}"."/{$v}';";
				return "window.location=' ".CNFG__SiteBaseURL."?p={$this->userSessionHash}"."/{$v}';";
			 }
			 else
				header("location: ".CNFG__SiteBaseURL."?p={$this->userSessionHash}"."/{$v}");
		}
		public function setSessionVar($c=[],$var,$val=[]){
			@session_start();
			switch($c['c']??''){
				case '':
				default:
					//simple assignment
					$_SESSION[$var]=$val;
					break;
			}
		}
	
		public function getUsrPermissions($uid){
			// user_permissions
					$sql="select usp.permission_id, sp.* 
							from ".DBTBL__user_site_permissions." usp
								left join ".DBTBL__site_permissions." sp on usp.permission_id = sp.id						
							where usp.user_id=:uid";
					// echo $sql;exit;
					$r=$this->ex($sql,['uid'=>$uid]);
					$sl=[];
					if(count($r)>0){
						foreach($r as $z=>$zz){
							$sl[]=$zz['permission_shortname'];
						}
					}
					
					return [$sl,$r];
			
		}//fn
		public function hasPermission($p,$c=[]){
			/*
				p string or array, list of permission shortnames
				c []
			*/
			if(gettype($p)=='string')
				$p=[$p];
			
			$has_all=true;
			$has_any=false;
			foreach($p as $x){
				if(isset($this->userPermissionShortnameAry) && in_array($x,$this->userPermissionShortnameAry)){
					$has_any=true;
				}
				else{
					$has_all=false;
				}
			}

			$temp=$has_all=$has_any;
			if(in_array('bounce',$c)&& $temp===false){
				$this->setSessionVar(c:[],var:'mc',val:'wUA');
				$this->goHome('&mc=wUA');
				return;
			}
			switch($c['c']??''){
				case 'any':
					return $has_any;
					break;
				case '':
				default:
					return $has_all;
					break;
			}
			return $has;
		}

		public function returnProperty($v){
			//mostly for private property
			return $this->{$v}??"NULL-{$v}";
		}


		public function getApproverAmounts($c=[],$a=[]){
			switch($c['c']??''){
				case 'byUsrLvl':
					
					$lvl = intval($this->getSessionFld('approver_level'));//user approval PK
					$sql="select * from ".DBTBL__approval_positions."
						where id=:id
						";

						$r=$this->ex($sql,['id'=>$lvl],['c'=>'']);
						switch($c['ret']??''){
							case 'MinMaxAry':
								if(count($r)===1)
									return [$r[0]['approval_dollar_amount_min'],$r[0]['approval_dollar_amount_max']];									
								else
									return [0,0];
								break;
						}
						break;
				}
		}
		public function isAuthApprovalLevel($c=[],$a=[]){
			if(in_array(gettype($a),['string','integer']))$a=[$a];
			$lvl = intval($this->getSessionFld('approver_level'));
			switch($c['c']??''){
				case 'min_lvl':
				case 'max_lvl':
					$min_lvl=$max_lvl=0;
					foreach($a as $x){
						if($x >=$max_lvl)
							$max_lvl=$x;
						if($x <=$min_lvl)
							$min_lvl=$x;
					}
					return ${$c};
					break;
				case 'in_list'://in any
					if( in_array($lvl,$a) )
						return true;
					return false;
					break;
			}
			
		}
		public function getGeneralSetting($v,$o=[]){
			require $_SERVER['DOCUMENT_ROOT']."/inc/general_settings.php";	
			switch($v){
				case 'noRoutingEmail':
					return $operations_ary['noRoutingEmail'];
					break;
			}
		}

		public function hasAProgramRole($c=[],$role=[]){
			if(gettype($role)=='string'){$role=[$role];}
			$ap=$this->getSessionFld('approvalRolePrograms');
			// die(print_r($ap));
			switch($c['c']??''){
				case '':
					//default, if any value given falls into the Role Type, return true
					$r=false;					
					if(!empty($ap)){
						foreach($ap as $Arole=>$a){
							if(in_array($Arole,$role))
								$r=true;
						}
					}
					return $r;
					break;
			}

		}
	}// <class
?>