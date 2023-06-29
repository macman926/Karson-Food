<?php
	include($_SERVER['DOCUMENT_ROOT'].'/inc/autoloader.php');
	$DBConn=new DBConn();
	if($DBConn->conn_err!=''){ //handle connection error
			echo 'Database connection error.';
			exit;
	}
	$DW=new DW();
	$O=new Order();
	if($DW->apiOperation(op:'Logon')!==false){
		$r = $DW->apiOperation(op:'getOrders');
		die("<pre>".print_r($r,1));
		if(count($r)>0){
			$ct=0;
			foreach($r as $o){
				$rr=$o;
				$dts=$rr['dates']??[];
				unset($rr['dates']);
				
				// die("<pre>".print_r($rr,1));
				$flds=[
					'order_last_updated'=>['val'=>'now()','isSys']
				];
				foreach($rr as $k=>$v)
					$flds[$k]=['val'=>$v];
				
								// die("<pre>".print_r($flds,1));
				$pk = $O->dmlOrder(c:['c'=>'insert_update'],a:['flds'=>$flds]);
				$O->dmlOrderLineItems(c:['c'=>'clearAllByOrderID'],a:['oid'=>$pk]);
				foreach($dts as $k=>$od){
					$tempdt=explode("-",$k);
					if( count($od)>0 && checkdate($tempdt[1],$tempdt[2],$tempdt[0]) ){
						foreach($od as $item){
							$LIflds=[
									'order_id'=>['val'=>$pk],
									'order_date'=>['val'=>$k],
									'item'=>['val'=>$item],
							];
							$O->dmlOrderLineItems(c:['c'=>'insert'],a:['flds'=>$LIflds]);
						}
					}
				}
				if($DW->apiOperation(op:'setIndexValue',a:[
					'docID'=>$rr['DWDOCID'],
					'flds'=>[ ['FieldName'=>'STATUS','Item'=>'Imported','ItemElementName'=>'String'] ],
				])===false)
					die('Error updating source record');
				else{
					$ct++;
				}
			}//foreach
			echo "Completed- Processed {$ct} record(s)";
			
		}
		else{
			echo "No orders";
		}
	}
?>