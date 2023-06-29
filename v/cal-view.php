<?php
$page_title="Single Order View (Calendar) / {$r['CUSTOMER_NAME']}/{$r['ORDER_TYPE']}";
$jqFileIncAry=[
	CNFG__SiteDocRoot."/js/jq-ajax.php",
];



$jq2="
    $('#genDoc').click(function(){
        let jq_config={	url: '/?p=".($US->getHash())."/order/".($r['id'])."&view=doc'};
		jq_ajax_call_w_config( 
			{
				page:'list',
				var:'single',
			} 
		    ,jq_config
        );
    });
";
?>
<html>
	<head>
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-meta.inc.php'); ?>
		<title><?=$company['company_name']."-".$page_title?></title>
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-css.inc.php'); ?>
		<?  include($_SERVER['DOCUMENT_ROOT'].'/v/header-js.inc.php'); ?>
		<script>
			$(function(e){
				<? include $_SERVER['DOCUMENT_ROOT'].'/js/jq-ajax.php'?>
				$('.dataItmClass').click(function(){
					if($(this).attr('expanded')!=='1'){
						$(this).attr('expanded','1');
						$(this).css('height','auto');
						//$(this).html($(this).html()+"123");
					}
                    else{
                        
                    }
				});
				$('.btnMonth').click(function(){
					window.location="/?p=<?=$US->getHash()."/".$slug_page."/"?>"+$(this).val();
				});
				$('#calChgGoBtn').click(function(){
					let p=$(this).parent();
					let m=$(p).find('#cal_m').val();
					let Y=$(p).find('#cal_Y').val();
					// alert(m);
					// alert(Y);
					
					window.location="/?p=<?=$US->getHash()."/".$slug_page."/"?>"+Y+m;
				});//event fn
				<?php
					foreach($jqFileIncAry as $flx)
						include $flx;
				?>
				<?=$jq2??''?>	
			});//jq
		</script>
		<style>
			:root{
				--varCalDayCellWidth: 50px;
				--varCalFontSize: 1.1vw;/*also used for hdr cell height */
			}
			#calView{
				/* min-width:800px;
				min-height:800px; */
				height:100%;
				width:100%;
				border: 1px solid black;
				border-collapse:collapse;
			}
			#CalHeaderLbl{
				display:inline-block;
				font-size:225%;
				color:white;
				font-weight:bold;
			}

			#btnMonthPrev{float:left;}
			#btnMonthNext{float:right;}
			.CalHdrWrapper{background-color:#b0abab;}
			.CalHdrControls>th{height:var(--varCalFontSize);}
			.calWkWrapper{height:10%;}
			.calControls{text-align:center;}
			.calDate{
				/* min-width:150px; */
				/* min-height:800px; */
				width:var(--varCalDayCellWidth);
				max-width:var(--varCalDayCellWidth);
				border:solid black 1px;
			}
			.inactive_day{background-color:#f3f3f3;}
			.active_day{
				background-color:#00ff515c;
				vertical-align:text-top;
			}
			.calHdrDays{
				font-size:var(--varCalFontSize);
				height:var(--varCalFontSize);
				background-color:white;
				text-align:center;
			}
			.isToday{background-color:#db7bda5c;}
			.dayNum{text-align:center;font-size:var(--varCalFontSize);}
			.dayNumHR{border:solid #b5b5b561 1px;}
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
			.dayDataItm:hover {
				cursor:pointer;
			    -moz-box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.7);
			    -webkit-box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.7);
			    box-shadow: inset 0 0 100px 100px rgba(255, 255, 255, 0.7);
			}


			/*scrollbar*/
				::-webkit-scrollbar {
				  width: 15%;
				}
				/* track */
				::-webkit-scrollbar-track {
				  box-shadow: inset 0 0 5px grey; 
				  border-radius: 10px;
				}
				/* Handle */
				::-webkit-scrollbar-thumb {
				  background: green; 
				  border-radius: 10px;
				}
				/* Handle on hover */
				::-webkit-scrollbar-thumb:hover {
				  background: blue; 
				}

		</style>
	</head>
<body>
	<? include($_SERVER['DOCUMENT_ROOT'].'/v/navbar.inc.php'); ?>
	<div id="body_content" class='<?=(isset($ditch_top_container_cls)&&$ditch_top_container_cls===true?'':'container')?> mt-5 ml-5 col'>
			<h4>Single Order View (Calendar): <?="{$r['CUSTOMER_NAME']}/{$r['ORDER_TYPE']}"?></h4>
				<?=$page_out['body']??''?>
				<?=$controls??''?>
				
				<div style='width:90vw;'>
					<?=$calTblOut?>
				</div>
			</div><!-- /body_content -->
	

</body>
</html>