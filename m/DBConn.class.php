<?php
	class DBConn{
		protected static $conn;
		public $conn_err = null;
		// public $conn_test = null;
		private static $conn_lbl='DEMO';
		
		function __construct($opts=[]){
			$this->connect($opts);
		}// <__construct
		
		protected static function connect($opts=[]){
			// if(is_null($this->conn)){
				// try{
					// include $_SERVER['DOCUMENT_ROOT']."/inc/db_settings.php";
					// $this->conn = new PDO(
						// "mysql:"
							// ."host=".$db[$this->conn_lbl]['host']
							// .";dbname=".$db[$this->conn_lbl]['db'].""
						// , $db[$this->conn_lbl]['un']
						// , $db[$this->conn_lbl]['pw']
					// );
					// set the PDO error mode to exception
					// $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
					
				// }
				// catch(PDOException $e){
					// $errstr="Database connection failed|" . $e->getMessage()."|Fn-".__FUNCTION__." L-". __LINE__;
					// if(isset($opts['return_error']) && $opts['return_error']===true)
						// return $errstr;
					// else
						// die($errstr);
				// }
				// echo 'conn';
			// }
			
			if(static::$conn===null){
				try{
					include $_SERVER['DOCUMENT_ROOT']."/inc/db_settings.php";
					static::$conn = new PDO(
						"mysql:"
							."host=".$db[static::$conn_lbl]['host']
							.";dbname=".$db[static::$conn_lbl]['db'].""
						, $db[static::$conn_lbl]['un']
						, $db[static::$conn_lbl]['pw']
					);
					// // set the PDO error mode to exception
					static::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					static::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
					
				}
				catch(PDOException $e){
					$errstr="Database connection failed|" . $e->getMessage()."|Fn-".__FUNCTION__." L-". __LINE__;
					if(isset($opts['return_error']) && $opts['return_error']===true)
						return $errstr;
					else
						die($errstr);
				}
				// echo 'conn';
			}
			
		}
		
		function ex($q,$p,$c=[]){
			//generic_query_w_params
			if(is_null(static::$conn)){
				static::connect([]);
			}
			// print_r(static::$conn);exit;
			$debug_Addon="[ From:".($c['fn']??'')."/".($c['ln']??'')."/".($c['msg']??'')."]";
			switch($c['c']??''){
				case 'debug_exit':
					echo $q.print_r($p,1);exit;
					break;
				case 'insert':
				case 'insert_update':
					// print_r($this);exit;
					$s=static::$conn->prepare($q);
					try{
						// echo $q.print_r($p,1);exit;
						// echo "F";
						$s->execute($p);
						// insert into ph_user( fname ,lname ,username ,password ,salt ,account_status) values( :fn, :ln, :un, :salted_pw, :s, :as )Array ( [fn] => TGI [ln] => ADMIN [un] => tgiadmin [salted_pw] => $argon2id$v=19$m=65536,t=4,p=1$N3dEb3JlUGpsT2V4TTB5ag$4RKtdtyJPX4bzZ/Z5XVcwR0qbyb1m3K/o62gOhxQNXM [s] => 1 [as] => Active )
					}
					catch(PDOException $e){
						if(in_Array('ret_error',$c))
							return $e->getMessage();
						else{
							// die($e->getMessage()."Q--{$q} P--".print_r($p,1)."L--". __LINE__.$debug_Addon .(false?debug_print_backtrace():'') );
							die($e->getMessage()." L--". __LINE__.$debug_Addon .(false?debug_print_backtrace():'') );
						}
					}
					return static::$conn->lastInsertId();
					break;
				case 'update': //use 'update' for insert on duplicate update statements
				case 'delete':
					$s=static::$conn->prepare($q);
					try{
						// print_r($p);exit;
						$s->execute($p);
					}
					catch(PDOException $e){
						// echo '<pre>';print_r($e);exit;
						die($e->getMessage()." L--". __LINE__.$debug_Addon .(false?debug_print_backtrace():'') );
					}
					break;
				case 'select':
				default: //query
					$s=static::$conn->prepare($q);
					try{
						$s->execute($p);
					}
					catch(PDOException $e){
						// die(print_r($s->errorInfo()));
						// die($s->errorInfo()[2]."Q--{$q} P--".print_r($p,1)."L--". __LINE__.$debug_Addon .(false?debug_print_backtrace():'') );
						die($s->errorInfo()[2]." L--". __LINE__.$debug_Addon .(false?debug_print_backtrace():'') );
					}
					$rows = $s->fetchAll(($c['fetch_method']??PDO::FETCH_ASSOC));
					if((count($rows)>0) && in_Array('single',$c))
						return $rows[0];
					else if(count($rows)>0)
						return $rows;
					else{//no rec
						if(in_Array('ret_false',$c))
								return false;
						else
							return array();
					}
					break;
			}
		}// Fn ex

		public function getTblFields($c,$tbl){

			include $_SERVER['DOCUMENT_ROOT']."/inc/db_settings.php";
			$sql="select column_name,data_type,column_type,column_key,character_maximum_length
			from information_schema.columns
			where
			table_schema=:db
			and
			table_name=:tbl
			order by ordinal_position asc";

			$r=$this->ex($sql,['db'=>$db[$this::$conn_lbl]['db'],'tbl'=>$tbl]);
			// die("<pre>".print_r($r,1));
			switch($c['c']??''){
				case 'ary_non_pk':
					$z=[];
					foreach($r as $k=>$v){
						if(!in_array($v['COLUMN_KEY'],['PRI'])){
							$z[]=$v['COLUMN_NAME'];
						}
					}
					return $z;
					break;
				case '':
					return $r;
					break;
			}
		}

		public function getTables($c){
			$db_split_ary=[];
			include $_SERVER['DOCUMENT_ROOT']."/inc/db_settings.php";
			$sql="show tables in ".$db[$this::$conn_lbl]['db'];

			$r=$this->ex($sql,[]);
			foreach($r as $x=>$v){
				$db_split_ary[]=$v['Tables_in_'.strtolower($db[$this::$conn_lbl]['db'])];
				// print_r($v);
			}

			switch($c['c']??''){
				case '':
					return $db_split_ary;
					break;
			}
		}

		function get_ConnLbl(){
			return $this::$conn_lbl;
		}

	}// <class
	

?>