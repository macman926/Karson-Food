<?php

	class Order extends DBConn{
		public function __construct(){
			
		}
		
		public function getOrder($c,$a=[]){
			switch($c['c']??''){
				case 'byFilter':
				/*
					c[]
						c
							byFilter
						getItems_SortByDate
					a[]
						filterOrder,filterStatus,filterDt
						orderID "",[]
						YrMonth [YYYY,mm]
						sql
				*/
				$p=$w=[];
				if(in_array('filterOrder',$a)){
					if(gettype($a['orderID'])=='string'){
						$w[]="o.id=:orderID";
						$p['orderID']=$a['orderID'];
					}
					else
						$w[]="(o.id in('".implode("','",$a['orderID'])."'))";
					
				}
				if(in_array('filterDt',$a)){
					$w[]="(o.id in(select L.order_id from ".DBTBL__order_LI." L where YEAR(L.order_date)=:y && MONTH(L.order_date)=:m ) )";
					$p['y']=$a['YrMonth'][0];
					$p['m']=$a['YrMonth'][1];
					
				}

				if(isset($a['sql']) && is_Array($a['sql'])){
					$w = array_merge($w,$a['sql']);
				}
				$sql="select 
							o.*
						from ".DBTBL__orders." o 						
						where ".(count($w)>0?implode(" && ",$w):" false ")."";
				
				// die($sql."<pre>".print_r($p,1));
				$r=$this->ex($sql,$p);
				if(in_array('getItems_SortByDate',$c))				
				foreach($r as $id=>$rr)
					$r[$id]['items']=$this->getOrderLineItems(c:['c'=>'getItems_SortByDate'],a:['oid'=>$rr['id']]);

				// die("<pre>--".print_r($r,1));
				return $r;
				break;
			}
		}

		public function getOrderLineItems($c,$a=[]){
			switch($c['c']??''){
				case 'getItems_SortByDate':
					$sql="select L.* 
						from ".DBTBL__order_LI." L
						where L.order_id=:oid
						order by L.order_date asc, L.id asc
					";
					$p=['oid'=>$a['oid']];
					
					// die($sql."<pre>".print_r($p,1));
					$r=$this->ex($sql,$p);
					$z=[];
					foreach($r as $x=>$rr){
						if(!isset($z[$rr['order_date']]))
							$z[$rr['order_date']]=[];
						$z[$rr['order_date']][]=$rr;
						// $z[$rr['order_date']][]=$rr['item'];
					}
					return $z;
					break;
			}
		}
		public function dmlOrder($c,$a=[]){
			switch($c['c']??''){
				/*
					c[]
						c
						id
					a[]
						flds
							fld=>[val=>"",'isSys',pdolbl=>""]
				*/
				case 'insert_update': {
					$i=$i2=$u=$p=[];
					$get_new_id_bl=true;
					if( isset($c['id']) && is_numeric($c['id']) && $c['id']>0 ){
						$i[]="id";
						$i2[]=":id";
						$get_new_id_bl=false;
					}

					foreach($a['flds'] as $fld=>$z){
						$i[]=$fld;
						if(in_array('isSys',$z)){
							$i2[]=$z['val'];
							$u[]=$fld."=".$z['val'];
						}
						else{
							$lbl=$z['pdoLbl']??$fld;
							
							$i2[]=':'.$lbl.'_i';
							$p[$lbl.'_i']=$z['val'];
							
							$u[]=$fld."=".(":{$lbl}_u");
							$p[$lbl.'_u']=$z['val'];
						}		
					}
					$sql="insert into ".DBTBL__orders."(
						".implode(",",$i)."
					)
					values(
					".implode(",",$i2)."
					) 
					on duplicate key update 
						".implode(",",$u)."
					";
					die("<pre>".$sql."<br>".print_r($p,1));
					$newid=$this->ex($sql,$p,['c'=>'insert_update']);
					if($get_new_id_bl)
						return $newid;
					else
						return $a['flds']['id']['val'];
					break;
					}
				case 'update_by_id':{
					$u=$p=[];
					foreach($a['flds'] as $fld=>$z){
						if(in_array('isSys',$z)){
							$u[]=$fld."=".$z['val'];
						}
						else{
							$lbl=$z['pdoLbl']??$fld;
							$u[]=$fld."=".(":{$lbl}_u");
							$p[$lbl.'_u']=$z['val'];
						}							
					}
					//die("yooo".print_r($c['id']));
					$sql="update ".DBTBL__orders.
						" set ".implode(",",$u) . 
						" where id = '". $c['id'] . "'";
					//die("<pre>".$sql."<br>".print_r($p,1));
					$updatedRow = $this->ex($sql,$p,['c'=>'update']);
					return $updatedRow;
					break;
				}

			}//switch
		}//fn
	
		public function dmlOrderLineItems($c,$a=[]){
			/*
			*/
			switch($c['c']??''){
				case 'clearAllByOrderID':
					$sql="delete from ".DBTBL__order_LI." where order_id=:oid";					
					$this->ex($sql,['oid'=>$a['oid']],['c'=>'delete']);
					return $this;
					break;
				case 'insert':
					/*
						a[]
							flds
								fld=>[val]
					*/
					
					$p=$i=$i2=[];
					foreach($a['flds'] as $fld=>$fa){
						$i[]=$fld;
						$i2[]=":{$fld}";
						$p[$fld]=$fa['val'];
					}
					$sql="insert into ".DBTBL__order_LI."(
						".implode(",",$i)."
					)
					values(
						".implode(",",$i2)."
					)";
					$this->ex($sql,$p,['c'=>'insert']);
					break;
			}
		}

		public function setSessionAndFormVals($o){
			$ret=[];
			// die(print_r($o));
			switch($o['c']??''){
				case 'open':
					$ret['order_status']='open';
					$ret['results_order_by']='req_desc';
					$_SESSION['search'][$o['c']]=$ret;
					$_SESSION['search'][$o['c']]['mandatory_filters']=[
						'order_status'=>$ret['order_status'],
					];
					break;
			}
			return $ret;
		}
		public function buildReqSearchFilters($o){
			/**
			 o[]
			 	searchType
				 uid
			 a[]
			 	reqID [] or ""
				request_dt_range ""
				requestor ""
				request_status ""
				

			*/
			// die(print_r($o));
			$sql_ary=$defaultSrchAry=[];
		 	switch($o['searchType']??''){				 
			 	case 'open':
					$defaultSrchAry['order_status']="(o.status='')";
					// $defaultSrchAry['request_status']="(r.request_status in('".implode("','",$this->reqStatus['Open'])."') )";
					break;
				case 'approval':
						//$sql_ary['est_total_range']="( r.est_total between '{$o['cost_range'][0]}' and '{$o['cost_range'][1]}' ) ";	
						if(isset($_SESSION['search'][$o['searchType']]['mandatory_filters']['approvalPrograms'])){
							$sql_ary['approvalPrograms']="( r.program_id in('".implode("','",$_SESSION['search'][$o['searchType']]['mandatory_filters']['approvalPrograms'])."') ) ";
						}
						// $sql_ary['approvalPrograms']="( r.program_id in('".implode("','",$o['approvalPrograms'])."') ) ";
					break;
				case '':
				default:
					break;
			 }
			 $sql_ary=array_merge($sql_ary,$defaultSrchAry);
			 ////////////
			//  echo '<pre>'.print_r($_SESSION['search'],1);exit;
			 if(	isset(	$_SESSION['search'][ ($o['searchType']??'xxx') ]	)	){
				$a=$_SESSION['search'][$o['searchType']];

				if(isset($a['requestor'])){
					switch($a['requestor']){
						case 'ALL':
							$sql_ary['requestor']="(r.submitted_by=r.submitted_by)";
							break;
						case 'ME':
							$sql_ary['requestor']="(r.submitted_by='{$_SESSION['search'][$o['searchType']]['mandatory_filters']['uid']}')";
							// $sql_ary['requestor']="(r.submitted_by='{$o['uid']}')";
							
							break;
					}
				}
				if(isset($a['request_status'])){
					switch($a['request_status']){
						case 'All':
							$sql_ary['request_status']="(r.req_status=r.req_status)";
							break;
						case 'Open':
							$sql_ary['request_status']="(r.req_status in('".implode("','",$this->reqStatus['Open']['incl'])."') )";
							break;
						default:
							$sql_ary['request_status']="(r.req_status='".$a['request_status']."')";
							break;
						
					}
				}
				if( isset($a['request_dt_range']) ){
					if($a['request_dt_range']=='All')
						$sql_ary['request_dt_range']="(r.originally_submitted_ts=r.originally_submitted_ts)";
					else
						$sql_ary['request_dt_range']=$this->date_range_str_replace($a['request_dt_range'],'r.originally_submitted_ts');
				 }

				//OVERWRITES FILTERS
				if( isset($a['id']) && $a['id']!='' ){
					$sql_ary=[];
				 	$sql_ary['id']="( r.id in('". ( (gettype($a['id'])=='array') ?implode("','",$a['id']) : $a['id']) ."') )";
				}
				///////////
				if( isset($a['results_order_by']) )
					$_SESSION['search'][$o['searchType']]['qa_order']=$this->get_order_by($a['results_order_by']);

				 $_SESSION['search'][$o['searchType']]['qa']=$sql_ary;
			}//session isset
			// echo '<pre>'.print_r($sql_ary,1);exit;
			// echo '<pre>'.print_r($_SESSION,1);exit;
		}//fn

		public function get_order_by($str){
			switch($str){
				case 'req_desc':return 'r.id desc';break;
				case 'req_asc':return 'r.id asc';break;
				case 'submitter_asc':return 'submitterLNFN asc';break;
				case 'submitter_desc':return 'submitterLNFN desc';break;
			}
		}

		public function displayRecs($c,$a=[]){
			switch($c['c']??''){
				case 'dash':
					// die('<pre>'.print_r($a,1));
					$ct_to_repeat=20;//repeat header after x rows
					$ct=$ct_to_repeat;
					$hdra=[
						'order_num'=>['lbl'=>'Order #','css'=>"max-width:100px;"],
						'customer'=>['lbl'=>'Customer','css'=>"max-width:800px;"],
						'STUDENTCOUNT'=>['lbl'=>'# Students','css'=>"max-width:100px;"],
						'type'=>['lbl'=>'Type','css'=>"max-width:100px;"],
						'status'=>['lbl'=>'Status','css'=>"max-width:100px;"],
						'last_updated'=>['lbl'=>'Last Updated','css'=>"max-width:160px;"],
						'controls'=>['lbl'=>'-','css'=>"max-width:200px;"],

					];
					$r_ary=[];
					$default_controls=""
						."<button type='button' title='Calendar' class='btn btn-default btn-sm px-3 viewOpenBtn' arg='cal'><i class='far fa-calendar'></i></button>"
						."<button type='button' title'=Order List' class='btn btn-default btn-sm px-3 viewOpenBtn' arg='list'><i class='fa fa-list'></i></button>"
						."<button type='button' title='Order Tally' class='btn btn-default btn-sm px-3 viewOpenBtn' arg='tally'><i class='fa fa-calculator'></i></button>";
					$hdr="<tr class='search_hdr_tr'>";
					foreach($hdra as $h=>$hh){
						$hdr.="<th class='search_hdr_th font-weight-bold' style='".($hh['css']??'')."'>{$hh['lbl']}</th>";
					}
					$hdr.="</tr>";
					foreach($a as $ctx=>$r){
						if(++$ct>=$ct_to_repeat){
							$r_ary[]=$hdr;
							$ct=0;
						}
						
							$r_ary[]=
								 "<tr class='search_rec_tr tr_row_{$ctx}' style='' recID='{$r['id']}'>"
									."<td class='search_rec_td' style='".($hdra['order_num']['css']??'')."'><span class='dash_span'>".$r['id']."</span></td>"
									."<td class='search_rec_td' style='".($hdra['customer']['css']??'')."'><span class='dash_span'>".$r['CUSTOMER_NAME']."</span></td>"
									."<td class='search_rec_td' style='".($hdra['STUDENTCOUNT']['css']??'')."'><span class='dash_span'>".$r['STUDENTCOUNT']."</span></td>"
									."<td class='search_rec_td' style='".($hdra['type']['css']??'')."'><span class='dash_span'>".$r['ORDER_TYPE']."</span></td>"
									."<td class='search_rec_td' style='".($hdra['status']['css']??'')."'><span class='dash_span'>".($r['STATUS']!=''?$r['STATUS']:'N/A')."</span></td>"
									."<td class='search_rec_td' style='".($hdra['last_updated']['css']??'')."'><span class='dash_span'>".date('m/d/y h:i A',strtotime($r['order_last_updated']))."</span></td>"
									."<td class='search_rec_td searc_rec_td_control'  style='".($hdra['controls']['css']??'')."' ><span class='dash_span_ctrl'>".$default_controls."</span></td>"
								."</tr>";
					}
					
					return "<table class='flex-table'>".implode("",$r_ary)."</table>";
					break;
			}
		}

		public function format_multiple_records_int_dates($c,$a){
			switch($c['c']??''){
				case 'splitByField':
					$split_field = $c['splitField']??'XXX';
					// die("<pre>".print_r($a,1));
					$ary=[];
					foreach($a as $orderCt=>$o){
						foreach($o['items'] as $dt=>$item_ary){
							if(!isset($ary[$dt][$o[$split_field]]))
								$ary[$dt][$o[$split_field]]=[];
							$ary[$dt][$o[$split_field]]=array_merge($ary[$dt][$o[$split_field]],$item_ary);
						}
					}
					// die("<pre>".print_r($ary,1));
					//*********************** */
                    //format for calendar

                    //ex ['dt'=>'2023-06-01','Name'=>'JPO','rec_id'=>'55',],

                    $ary2=[];

                    foreach($ary as $dt=>$a1){
                        // echo "<pre>".print_r($a1,1);
                        // exit;
                        foreach($a1 as $ot=>$a2){
                            // echo "<pre>".print_r($a2,1);
                            $tempItmListAry=[];
                            foreach($a2 as $zz=>$a3){
                                $tempItmListAry[]=$a3['item'];
                            }
                            $ary2[]=[
                                    'dt'=>$dt,
                                    'Name'=>"<span class='order_type_lbl'><b>{$ot}</b></span><br>"
                                        ."<span class='order_items_list'>-".implode("<br>-",$tempItmListAry)."</span>",
                                    'additionalClasses'=>"li_order_type__{$ot}",
                                ];
                        }
                    }
                    // die("<pre>".print_r($ary2,1));
                    return $ary2;
                    break;
			}
		}
		public function orderLI_Tally($c,$a=[]){
			/*
				c[]
					c singleRec,multiRec by 'items' container
				a[]
					data=>recs
			 */
			$recAry=$a['data']??[];

			$is_multi=false;
			switch($c['c']??''){
				case 'singleRec':
					$is_multi=true;
					$recAry=[$recAry];//transfer to multidimension

					//continue, no break!
				case 'multiRec':
					// die("<pre>".print_r($recAry,1));
					$r=[];
					if(count($recAry)>0){
						foreach($recAry as $k=>$p){
							if(isset($p['items'])){
								foreach($p['items'] as $dt=>$pp){
									// die("<pre>".print_r($pp,1));
									foreach($pp as $ppp){
										// die("<pre>".print_r($ppp,1));
										if(!isset($r[$ppp['item']])){
											$r[$ppp['item']]=[
													'uom'=>$ppp['item_serving_size_uom'],
													'def_units'=>$ppp['item_serving_size_amt'],
													'units'=>0,//total calcd out
													'order_id_ary'=>[],
													'overall_instance_ct'=>0,
													'overall_count_times_student'=>0,
													'count_by_cust_name'=>[],
												];
										}
										if(!in_array($p['id'],$r[$ppp['item']]['order_id_ary']))
											$r[$ppp['item']]['order_id_ary'][]=$p['id'];
										$r[$ppp['item']]['overall_instance_ct']++;
										
										// echo "<br>{$ppp['item']}={$r[$ppp['item']]['count_times_student']}+{$temp_student_ct_qty}";


										if(!isset($r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]))
											$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]=[
													'totals'=>[
														  'total_instance_ct'=>0
														 ,'count_times_student'=>0														 
													]
													, 'orders'=>[]
												];
										if( !isset($r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]) )
											$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]=[
												'students'=>0
												,'instance_ct'=>0
												,'count_times_student'=>0
												,'dts'=>[]
											];
										$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]['students']=$p['STUDENTCOUNT'];
										$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]['instance_ct']++;
										$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]['count_times_student']=
											$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]['instance_ct']* $r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]['students'];
										$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['orders'][$p['id']]['dts'][]=$dt;

										$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['totals']['count_times_student']+=$p['STUDENTCOUNT'];
										$r[$ppp['item']]['count_by_cust_name'][$p['CUSTOMER_NAME']]['totals']['total_instance_ct']++;

									}
								}
							}							
						}
						// die("<pre>".print_r($r,1));
						foreach($r as $itm=>$a){
							foreach($a['count_by_cust_name'] as $cust=>$b){
								$r[$itm]['overall_count_times_student']+=$b['totals']['count_times_student'];
							}
							$r[$itm]['units']=$r[$itm]['def_units'] * $r[$itm]['overall_count_times_student'];
						}
					}

					
					//die("<pre>".print_r($r,1));
					return $r;
					break;
			}

		}
	}//class
?>