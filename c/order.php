<?php
    if($s2=='nav')
        $US->goHome();
    
    $O=new Order();
    if(in_array($_GET['view'],['monthTally'])){
        list($Y,$m)=explode("_",$s2);
        if(
            (!is_numeric($Y)) || (!is_numeric($m)) || $Y<=2020 || $m<=0 || $m>12
        )
            $US->goHome();
        
    }
    else{
        $recID=$s2;
        $r=$O->getOrder(c:['c'=>'byFilter','getItems_SortByDate'],a:['filterOrder','orderID'=>$recID]);
        //die("<pre>".print_r($r,1));
        $output=[];
        $r=$r[0];
        $mm=str_pad($r['MONTH'],2,"0",STR_PAD_LEFT);
        $tempDT="{$r['YEAR']}-".$mm."-%s";
        $controls="<button type=button id='genDoc' class='btn btn-sm btn-info'>Generate Document</button>";
        $rec_upl_base_path=$_SERVER['DOCUMENT_ROOT']."/rep";
    }
    
    switch($_GET['view']??''){
        case 'doc':{//ajax
                $post=$_POST;
                // die(print_r($post));
                // die("ddddd-{$s2}");
                //Cal
                $fd=$O->format_multiple_records_int_dates(c:['c'=>'splitByField','splitField'=>'ORDER_TYPE'],a:[$r]);
                $C=new Calendar(
                    Y:$r['YEAR'],
                    m:$mm,
                    optAry:[
                        // 'calClass'=>'calWrapperClass',
                        // 'calCtrlClass'=>'',
                        'dataItmClass'=>'dataItmClass',
                    ],
                );
                $C->setDateAry(res:$fd,o:['o'=>'def','date_fieldname'=>'dt']);
                $calTblOut =  $C->buildCalTbl(header_style:'labelOnly')->calTbl;                
                
                //List
                $tbl=diplayOutput(c:['c'=>'listTbl_1'],a:['r'=>$r,'tempDT'=>$tempDT]);
                {
                    $calCSS="
                        :root{
                            --varCalDayCellWidth: 50px;
                            --varCalFontSize: 1.1vw;/*also used for hdr cell height */
                        }
                        #calView{
                            width:95%;
                            border: 1px solid black;
                            border-collapse:collapse;
                        }
                        #CalHeaderLbl{
                            display:inline-block;
                            color:white;
                            font-weight:bold;
                        }
                        .CalHdrDOW{background-color:red;}
                        #btnMonthPrev{float:left;}
                        #btnMonthNext{float:right;}
                        .CalHdrWrapper{background-color:#b0abab;}
                        .CalHdrDOW{background-color:red !important;}
                        .calWkWrapper{height:10%;}
                        .calControls{text-align:center;}
                        .calDate{
                            /* min-width:150px; */
                            /* min-height:800px; */
                            border:solid black 1px;
                            width:15%;
                        }
                        .inactive_day{background-color:#f3f3f3;}
                        .active_day{
                            background-color:#00ff515c;
                            vertical-align:text-top;
                        }
                        .calHdrDays{
                            
                            background-color:white;
                            text-align:center;
                        }
                        .isToday{background-color:#db7bda5c;}
                        .dayNum{text-align:center;font-size:8px;}
                        .dayNumHR{border:solid #b5b5b561 1px;margin:0 0 5px 0;}
                        .dayDataWrapper{
            
                        }
                        .dayDataItm{
                            font-size:70%;
                            width:97%;
                            //height:15px;
                            //max-height:30px;
                            display:block;
                            border-radius:5px;
                            padding-left:5px;
                            margin-bottom:5px;
                            word-break:break-word;
                            overflow:scroll;
                            overflow-x:hidden;
                            font-weight:400;
                        }
                        
                        .dayDataItm.li_order_type__Breakfast{ background-color:#69bfff; }
                        .dayDataItm.li_order_type__Lunch{ background-color:#ffc769; }
                        .dayDataItm.li_order_type__Snack{ background-color:#fffe69; }

                        .order_type_lbl{font-size:8px;}
                        .order_items_list{font-size:6px;}

                        @page { margin: 5px; }
                        body { margin: 5px; }
                    ";
                }
                /////////////


                // $docOut=$tbl;
                
                $docOut=$calTblOut;
                $is_cal=true;

                // echo $docOut;exit;

                $hdr="<h3>Solo order for {$r['CUSTOMER_NAME']} - {$r['MONTH']}/{$r['YEAR']} - {$r['ORDER_TYPE']}</h3>";

                


                $basename=date('YmdHis')."__submission_doc";
                $ext="pdf";
                $fn="{$basename}.{$ext}";
                $fullpath=$rec_upl_base_path."/".$fn;
                $DW=new DW();
                $D=new Document([
                    'dir'=>		"{$rec_upl_base_path}/",
                    // 'emailAttDir'=>		"{$emailAttDir}/",
                    'basename'=>$basename,
                    'ext'=>		$ext,
                    'body'=>	"<img src='data:image/{$company['logoExt']};base64,".base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."{$company['logo_w_label']}"))."' width=200><hr>"
                                    .$hdr
                                    .$docOut,
                    'css'=>"
                        {$calCSS}

                        #listTbl{border-collapse:collapse;width:75%;}
                        #listTbl tr:nth-child(odd){
                            background-color:#bcbebf;
                        }
                    ",
                ]);
                if($is_cal??false)
                    $D->D->set_paper('letter', 'landscape');
                $D->genDOM(a:['rep']);
                $DW->build_dwc(
                    o:['c'=>'rec','upload_type'=>'gen_approved_doc'],
                    fileData:[
                        'fpi'=>pathinfo($fullpath),
                        'custom_path'=>'./'.$fn,
                        'doctype'=>'Calendar',
                        ],
                    reqDataAry:[
                        'id'=>$s2??'',
                        'DWDOCID'=>$r['DWDOCID']??'',
                        'CUISTOMER_NAME'=>$r['CUSTOMER_NAME']??'',

                    ],
                );	
                

                echo "alert('Documents generated');";

            }
            exit;
            break;
        case 'cal' :{

                $d=$O->format_multiple_records_int_dates(c:['c'=>'splitByField','splitField'=>'ORDER_TYPE'],a:[$r]);
                $C=new Calendar(
                    Y:$r['YEAR'],
                    m:$mm,
                    optAry:[
                        // 'calClass'=>'calWrapperClass',
                        // 'calCtrlClass'=>'',
                        'dataItmClass'=>'dataItmClass',
                    ],
                );
                $C->setDateAry(res:$d,o:['o'=>'def','date_fieldname'=>'dt']);
                $calTblOut =  $C->buildCalTbl(header_style:'labelOnly')->calTbl;

                include($_SERVER['DOCUMENT_ROOT'].'/v/cal-view.php');
                exit;                
            }

            break;
        case 'tally':{
                $z = $O->orderLI_Tally(c:['c'=>'singleRec'],a:['data'=>$r]);
                //die("<pre>".print_r($z));
                /*
                    Array
                    (
                        [Fresh Apple-1] => Array
                            (
                                [instance_ct] => 4
                                [count_times_student] => 600
                            )
                 */
               
                $output[]="<table>";
                $output[]="<tr><td><b>Customer: </b>".$r['CUSTOMER_NAME']."</td></tr>";
                $output[]="<tr><td><b>Designated For: </b>".$r['MONTH']."/".$r['YEAR']." - {$r['ORDER_TYPE']} - Sudent Count {$r['STUDENTCOUNT']}</td></tr>";
                $output[]="</table>";
                $output[]="<hr>";
                $tbl=diplayOutput(c:['c'=>'listTbl_2'],a:['r'=>$r,'z'=>$z,'tempDT'=>$tempDT]);
                $output[] = $tbl;
                $calTblOut="<h5>Order Item Tally:</h5>"
                .implode("",$output);
                include($_SERVER['DOCUMENT_ROOT'].'/v/tally-view.php');
            }
            exit;
            break;
        case 'list':{  
                $rights = false;
                if(true){
                    $rights = true;
                    $editStudentCount = "<button type=button id='eStudentCount' class='btn btn-sm btn-info'>Edit</button>";
                    $studentCountInput = "<input type=text id=iStudentCount value='".$r['STUDENTCOUNT']."'>";    
                }
                $output[]="<table>";
                $output[]="<tr><td><b>Customer: </b>".$r['CUSTOMER_NAME']."</td></tr>";
                $output[]="<tr><td><b>Designated For: </b>".$r['MONTH']."/".$r['YEAR']." - {$r['ORDER_TYPE']}</td></tr>";
                $output[]="<tr><td><b># of Students For This Order: </b>"."<span id=updatingCount>" . $r['STUDENTCOUNT']. "</span>"."^PLACEY^^PLACEH^</td></tr>";
                $output[]="</table>";
                $output[]="<hr>";

                $tbl=diplayOutput(c:['c'=>'listTbl_1'],a:['r'=>$r,'tempDT'=>$tempDT]);
                $output[] = $tbl;
                    

                if(false){

                }
                else{

                    $calTblOut =($controls??'')
                    ."<h5>Single Order Summary:</h5>"
                    .implode("",$output);
                    include($_SERVER['DOCUMENT_ROOT'].'/v/list-view.php');

                    // echo"<html>"
                    //         ."<head>"
                    //             ."<style>"
                    //             .".td_date{vertical-align:top;}"
                    //             ."</style>"
                    //         ."</head>"
                    //         ."<body>"
                    //             .($controls??'')
                    //             ."<h3>Single Order Summary:</h3>"
                    //             .implode("",$output)
                    //         ."</body>"
                    //     ."</html>";
                }
            }
            exit;
            break;
        case 'monthTally':{
            $r=$O->getOrder(c:['c'=>'byFilter','getItems_SortByDate'],a:['filterDt','YrMonth'=>[$Y,$m]]);
            $z = $O->orderLI_Tally(c:['c'=>'multiRec'],a:['data'=>$r]);
            // die("<pre>".print_r($z,1) );
            $tbl=diplayOutput(c:['c'=>'listTbl_3'],a:['r'=>$r,'z'=>$z,'YrMonth'=>[$Y,$m]]);
            // die("<pre>".print_r($r,1) );
            // die("<pre>".print_r($z,1) );
            // die($tbl);
            include($_SERVER['DOCUMENT_ROOT'].'/v/monthTally-view.php');
            exit;
        }break;
        case 'edit':{
            // echo "console.log('Entered the edit case in order');";
            $c= array('c'=>'update_by_id', 'id'=>$s2);
            $a= array('flds'=>[$_POST['field']=>array('val'=>$_POST['val'])]);
            $order = new Order();
            //die(print_r(""));
            //die(print_r($a));
            $order->dmlOrder(c:$c,a:$a);
            echo "updatedStudentCount.innerHTML=\"".$_POST['val']."\";";
            //echo "console.log('end of case reached')";
            exit;
        
        }break;
    }//switch


function diplayOutput($c,$a=[]){
    /*
        c[]
            c
        a[]
            $r
            $z
            $tempDT
            $tempDT2
            $tempDTAry
     */
    // die("fff<pre>".print_r($a['z'],1));

    switch($c['c']??''){
        case 'listTbl_3':
            $output=[];
            $output[]="<table id='listTbl'>";
            $output[]="<thead>";
            $output[]="<tr>"
                ."<th>Product</th>"
                ."<th>Overall Qty</th>"
                ."<th>Weight Breakdown</th>"
                ."<th>Customer Order Ct.</th>"
            ."</tr>";
            $output[]="</thead>";
            $output[]="<tbody>";
            include($_SERVER['DOCUMENT_ROOT']."/inc/measurement_conversions.php");
            foreach($a['z'] as $itm=>$aa){
                if(isset($measurement_conversions[$aa['uom']])){
                    // $wt_out=grind_out_uom_conversion($aa['uom'],$aa['units'],$measurement_conversions[$aa['uom']],[]);
                    $wt_out=calc_uom(val:$aa['units'],uom:$aa['uom'],ary:$measurement_conversions[$aa['uom']],ret:'lbl',dp:-1);
                }
                else
                    $wt_out='N/A';
                $tmp=[];
                foreach($aa['count_by_cust_name'] as $cust=>$cc){
                    $tmp[]="<b>{$cust}</b>: {$cc['totals']['count_times_student']}";
                    
                    foreach($cc['orders'] as $oid=>$dd)
                        $tmp[]="<div class='subOrderData'>-Order#<b> {$oid} </b>/ Students:<b> {$dd['students']} </b>/ Day Ct:<b> {$dd['instance_ct']}</b></div>";



                }
                $output[]="<tr>";
                    $output[]="<td>".($itm)."</td>";
                    $output[]="<td>".($aa['overall_count_times_student'])."</td>";
                    $output[]="<td>$wt_out</td>";
                    $output[]="<td>".implode("<br />",$tmp)."</td>";
                $output[]="</tr>";
            }
            $output[]="</tbody>";
            $output[]="</table>";

            return implode("",$output);
            break;
        case 'listTbl_2':
            // die("<pre>".print_r($a['z'],1));
            $output=[];
            $output[]="<table id='listTbl'>";
            $output[]="<tr><th>Product</th><th>Qty</th></tr>";
            if(isset($a['z'])){
                ksort($a['z']);
                foreach($a['z'] as $itm=>$b){
                    $output[]="<tr>"
                        ."<td>{$itm}</td>"
                        ."<td>{$b['overall_count_times_student']}</td>"
                        // ."<td>{$b['instance_ct']}</td>"
                    ."</tr>";
                }
            }
            $output[]="</table>";
            return implode("",$output);
            break;
        case 'listTbl_1':
            $output=[];
            $output[]="<table id='listTbl'>";
            for($x=1;$x<=31;$x++){
                $a['tempDT2']=vsprintf($a['tempDT'],[str_pad($x,2,"0",STR_PAD_LEFT)]);
                $a['tempDTAry']=explode("-",$a['tempDT2']);
                
                if(checkdate($a['tempDTAry'][1],$a['tempDTAry'][2],$a['tempDTAry'][0])){
                    $itmsAry=[];
                    if( isset($a['r']['items'][$a['tempDT2']]) ){                        
                        foreach($a['r']['items'][$a['tempDT2']] as $ctr=>$aa)
                            $itmsAry[]=$aa['item'];
                        // echo $a['tempDT2']."<br>";
                        $output[]="<tr>"
                                ."<td class='td_date'>".date('m/d (l)',strtotime($a['tempDT2']))."</td>"
                                // ."<td><b>".$a['r']['ORDER_TYPE'].":</b><hr>".implode("<br>",$a['r']['items'][$a['tempDT2']])."</td>"
                                ."<td>".implode("<br>",$itmsAry)."</td>"
                            ."</tr>";
                    }//items
                    else{
                        $output[]="<tr>"
                        ."<td class='td_date'>".date('m/d (l)',strtotime($a['tempDT2']))."</td>"
                        ."<td><i>N/A</i></td>"
                    ."</tr>";
                    }//no items
                }//checkdate
            }//for
            $output[]="</table>";
            return implode("",$output);
            break;
    }
}

function calc_uom($val,$uom,$ary,$ret,$dp){
    $out=[];
    // echo "Init val({$val})\n";
    
    if($dp==-1){
    	$dpt=explode(".",$val);
    	if(count($dpt)>1)
    		$dp=$dpt[1];
    	else
    		$dp=0;
    }

    $match=false;
    foreach($ary['scale'] as $u=>$str){
        if($val>=$u){
        	$match=true;
        	// echo "match {$u}\n";
            $z=intdiv($val,$u);
            $out[]="{$z} {$str}";
            $mod=$val % $u;
            // echo "mod-{$mod}\n";
            if($mod > 0)
                $out=array_merge($out,calc_uom(val:$mod,uom:$uom,ary:$ary,ret:'ary',dp:$dp) );
            // echo "returning:".print_r($out,1);
            // return $out;
            goto conv_ret;
             
            
        }
    }
    if(!$match){
    	// echo "no match for {$val}\n";
    	$out[]=$val.($dp>0?".".$dp:'')." {$ary['notation']}";
    }
    conv_ret:
    switch($ret??''){
    	case 'ary'://used in recusive call
    		return $out;
    		break;
    	case 'lbl':
    	case '':
    		return implode(",",$out);
    		break;
    }
    
    
}


?>