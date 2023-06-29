<?php
$page_title="Monthly Tally";

$jqFileIncAry=[
	CNFG__SiteDocRoot."/js/jq-ajax.php",
];

$jq2="
    $('#genDoc').click(function(){
        
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
            #listTbl{width:50%;}
            #listTbl td{padding:5px; vertical-align:top;}
            #listTbl tr:nth-child(even){background-color:#c9daee;}
			.subOrderData{margin-left:10px;}            


		</style>
	</head>
<body>
	<? include($_SERVER['DOCUMENT_ROOT'].'/v/navbar.inc.php'); ?>
	<div id="body_content" class='<?=(isset($ditch_top_container_cls)&&$ditch_top_container_cls===true?'':'container')?> mt-5 ml-5 col'>
            <h4>Tally view for(<?=date('F Y',strtotime($Y."-".$m."-01"))?>)</h4>
				<?=$page_out['body']??''?>
				<div style='width:90vw;'>
					<?=$tbl?>
				</div>
			</div><!-- /body_content -->
	

</body>
</html>