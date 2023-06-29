<?php

	switch($s2??''){
				case 'loadRecs':{
					$jqOutAry=[];
					// die($s3);
					// die(print_r([$slug_page,$s2,$s3]));
					// print_r($_SESSION);exit;
					// print_r($_SESSION['search'][$s3]);exit;
					// print_r($_SESSION['search'][$s3]['qa'] );exit;
					if(isset($_SESSION['search'][$s3]['qa'])){
						$srchO = new Order();
						$tmpRes=$srchO->getOrder(
							c:['c'=>'byFilter'],
							a:['sql'=>$_SESSION['search'][$s3]['qa'] ],
						);
						// print_r($tmpRes);exit;
						if(count($tmpRes)>0){
							$res=$srchO->displayRecs(
								c:['c'=>'dash'],
								a:$tmpRes
								);
							// print_r($res);exit;
							// print_r($_SESSION);exit;
							$jqOutAry[]="$('#result_tbl_wrapper').html(\"{$res}\");";
						}
						else{
							$jqOutAry[]="$('#result_tbl_wrapper').html(\"No Records Found\")";
						}

					}else{
						$jqOutAry[]="console.log('No qa for -{$s3}');";
						$jqOutAry[]="$('#result_tbl_wrapper').html(\"No Records\")";
					}
		
					foreach($jqOutAry as $q)
						echo $q;
				}
				exit;
				break;
				case 'open':
							$US->isLoggedIn($urlUserHash,['logout']);
							$distinct_page_tag=$s2;
							$page_title="Open Orders";
							$srchO = new Order();
							// echo '<pre>'.print_r($srchRQ,1);exit;
							// echo '<pre>'.print_r($_SESSION,1);exit;
							unset($_SESSION['search']);
							if(isset($_SESSION['search'][$s2])){
								// die('my2');
								$cf=$_SESSION['search'][$s2];
							}else{
								$cf=$srchO->setSessionAndFormVals(o:['c'=>$s2]);
								$srchO->buildReqSearchFilters(o:['searchType'=>$s2]);
								// echo '<pre>'.print_r($srchAry,1);exit;
								//echo '<pre>'.print_r($cf,1);exit;
							}
							break;

			break;
	}//s2
?>